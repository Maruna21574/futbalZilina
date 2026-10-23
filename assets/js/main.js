(() => {
  'use strict';
  document.documentElement.classList.remove('no-js');
  const $ = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => [...c.querySelectorAll(s)];

  // Sticky header tieň
  const header = $('#header');
  const onScroll = () => header && header.classList.toggle('is-stuck', window.scrollY > 10);
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  // Mobilné menu
  const burger = $('#burger');
  const setNav = open => {
    document.body.classList.toggle('nav-open', open);
    burger.setAttribute('aria-expanded', open);
  };
  burger?.addEventListener('click', () => setNav(!document.body.classList.contains('nav-open')));
  document.addEventListener('click', e => {
    if (document.body.classList.contains('nav-open') && !e.target.closest('#nav') && !e.target.closest('#burger')) setNav(false);
  });
  $$('.nav .has-sub > a').forEach(a => a.addEventListener('click', e => {
    if (window.matchMedia('(max-width: 980px)').matches) {
      const li = a.parentElement;
      if (!li.classList.contains('open')) { e.preventDefault(); li.classList.add('open'); }
    }
  }));

  // Odhalenie pri scrolle
  const rev = $$('.reveal');
  if ('IntersectionObserver' in window && rev.length) {
    const io = new IntersectionObserver(es => es.forEach(en => {
      if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); }
    }), { threshold: .12, rootMargin: '0px 0px -40px 0px' });
    rev.forEach(el => io.observe(el));
  } else rev.forEach(el => el.classList.add('in'));

  // Počítadlá
  $$('[data-count]').forEach(el => {
    const to = +el.dataset.count;
    const run = () => {
      const t0 = performance.now();
      const step = t => {
        const p = Math.min(1, (t - t0) / 1400);
        el.textContent = Math.round(to * (1 - Math.pow(1 - p, 3)));
        if (p < 1) requestAnimationFrame(step);
      };
      requestAnimationFrame(step);
    };
    if ('IntersectionObserver' in window) {
      const io = new IntersectionObserver(es => es.forEach(en => { if (en.isIntersecting) { run(); io.disconnect(); } }));
      io.observe(el);
    } else el.textContent = to;
  });

  // Záložky
  $$('.tabs').forEach(tabs => {
    const btns = $$('.tabs__btn', tabs), panels = $$('.tabs__panel', tabs);
    const open = id => {
      btns.forEach(b => b.setAttribute('aria-selected', b.dataset.tab === id));
      panels.forEach(p => p.hidden = p.id !== 'tab-' + id);
    };
    btns.forEach(b => b.addEventListener('click', () => open(b.dataset.tab)));
  });

  // Dnešný deň v rozvrhu
  const today = (new Date().getDay() + 6) % 7;
  $$('.schedule .day')[today]?.classList.add('today');

  // Lightbox
  const links = $$('a[data-lightbox]');
  if (links.length) {
    const lb = document.createElement('div');
    lb.className = 'lb';
    lb.setAttribute('role', 'dialog');
    lb.setAttribute('aria-modal', 'true');
    const svg = '<svg class="i" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">';
    lb.innerHTML = `<button class="lb__x" aria-label="Zavrieť">${svg}<path d="M18 6 6 18M6 6l12 12"/></svg></button>
      <button class="lb__p" aria-label="Predchádzajúca">${svg}<path d="M5 12h14M13 5l7 7-7 7"/></svg></button>
      <img alt=""><button class="lb__n" aria-label="Ďalšia">${svg}<path d="M5 12h14M13 5l7 7-7 7"/></svg></button><div class="lb__c"></div>`;
    document.body.appendChild(lb);
    const img = $('img', lb), counter = $('.lb__c', lb);
    let group = [], idx = 0;
    const show = i => {
      idx = (i + group.length) % group.length;
      img.src = group[idx].href;
      counter.textContent = group.length > 1 ? `${idx + 1} / ${group.length}` : '';
      [-1, 1].forEach(d => { const n = new Image(); n.src = group[(idx + d + group.length) % group.length].href; });
    };
    const close = () => { lb.classList.remove('open'); document.body.style.overflow = ''; img.src = ''; };
    links.forEach(a => a.addEventListener('click', e => {
      e.preventDefault();
      const scope = a.closest('.gallery') || a.closest('.prose') || document;
      group = $$('a[data-lightbox]', scope);
      lb.classList.add('open');
      document.body.style.overflow = 'hidden';
      show(group.indexOf(a));
    }));
    $('.lb__x', lb).onclick = close;
    $('.lb__p', lb).onclick = () => show(idx - 1);
    $('.lb__n', lb).onclick = () => show(idx + 1);
    lb.addEventListener('click', e => { if (e.target === lb) close(); });
    document.addEventListener('keydown', e => {
      if (!lb.classList.contains('open')) return;
      if (e.key === 'Escape') close();
      if (e.key === 'ArrowLeft') show(idx - 1);
      if (e.key === 'ArrowRight') show(idx + 1);
    });
    let x0 = null;
    lb.addEventListener('touchstart', e => x0 = e.touches[0].clientX, { passive: true });
    lb.addEventListener('touchend', e => {
      if (x0 === null) return;
      const dx = e.changedTouches[0].clientX - x0;
      if (Math.abs(dx) > 50) show(idx + (dx < 0 ? 1 : -1));
      x0 = null;
    });
  }
})();

// Prihláška: kroky, validácia, nápoveda ku kategórii
(() => {
  const form = document.querySelector('form.apply');
  if (!form) return;
  const steps = [...form.querySelectorAll('.step')];
  const items = [...form.querySelectorAll('.steps li')];
  const prev = form.querySelector('[data-prev]'), next = form.querySelector('[data-next]');
  const q = n => form.querySelector(`[name="${n}"]`);
  const min = +form.dataset.min, max = +form.dataset.max;
  const years = JSON.parse(form.dataset.years || '{}');
  let cur = Math.min(steps.length, Math.max(1, +form.dataset.start || 1));

  const note = (box, msg) => {
    box.querySelectorAll('.err.js').forEach(x => x.remove());
    if (!msg) return;
    const s = document.createElement('span');
    s.className = 'err js';
    s.textContent = msg;
    box.appendChild(s);
  };
  const rules = {
    parent_name: v => v.trim().length < 3 && 'Zadajte meno a priezvisko rodiča.',
    parent_email: v => !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(v.trim()) && 'Zadajte platný e-mail.',
    parent_phone: v => !/^\+?[0-9 ()\/-]{9,20}$/.test(v.trim()) && 'Zadajte platné telefónne číslo.',
    player_name: v => v.trim().length < 3 && 'Zadajte meno a priezvisko hráča.',
    birth_year: v => {
      if (!/^\d{4}$/.test(v.trim())) return 'Zadajte rok narodenia v tvare RRRR (napr. 2016).';
      if (+v < min) return 'Hráči narodení v roku 2011 a starší sa neprihlasujú cez formulár (dorast – osobný pohovor).';
      if (+v > max) return `Do klubu prijímame len hráčov narodených v roku ${max} a starších.`;
      return false;
    },
  };
  const validate = step => {
    let first = null;
    step.querySelectorAll('.err:not(.js)').forEach(x => x.remove());
    for (const [name, rule] of Object.entries(rules)) {
      const el = q(name);
      if (!el || !step.contains(el)) continue;
      const m = rule(el.value);
      el.classList.toggle('invalid', !!m);
      note(el.closest('.field'), m);
      if (m && !first) first = el;
    }
    if (step.contains(q('cvc'))) {
      for (const n of ['cvc', 'voucher']) {
        const box = q(n).closest('.field');
        const ok = form.querySelector(`[name="${n}"]:checked`);
        note(box, ok ? '' : 'Vyberte jednu možnosť.');
        if (!ok && !first) first = q(n);
      }
      const g = q('gdpr');
      note(g.closest('.field'), g.checked ? '' : 'Potvrďte súhlas so spracovaním osobných údajov.');
      if (!g.checked && !first) first = g;
    }
    return first;
  };
  const show = (n, scroll = true) => {
    cur = n;
    steps.forEach((s, i) => s.classList.toggle('is-active', i === n - 1));
    items.forEach((li, i) => { li.classList.toggle('on', i === n - 1); li.classList.toggle('done', i < n - 1); });
    prev.hidden = n === 1;
    form.classList.toggle('on-last', n === steps.length);
    if (scroll) form.scrollIntoView({ behavior: 'smooth', block: 'start' });
  };
  next.addEventListener('click', () => { const bad = validate(steps[cur - 1]); if (bad) bad.focus(); else show(cur + 1); });
  prev.addEventListener('click', () => show(cur - 1));
  form.addEventListener('keydown', e => {
    if (e.key === 'Enter' && e.target.tagName === 'INPUT' && e.target.type !== 'submit' && cur < steps.length) { e.preventDefault(); next.click(); }
  });
  form.addEventListener('submit', e => {
    for (let i = 0; i < steps.length; i++) {
      const bad = validate(steps[i]);
      if (bad) { e.preventDefault(); show(i + 1, false); bad.focus(); return; }
    }
  });

  const hint = document.getElementById('year-hint'), by = q('birth_year');
  const upd = () => {
    const v = by.value.trim();
    if (!/^\d{4}$/.test(v)) { hint.hidden = true; return; }
    const y = +v;
    hint.hidden = false;
    hint.classList.toggle('bad', !years[y]);
    hint.textContent = years[y]
      ? `Kategória: ${years[y]}.`
      : (y < min ? 'Ročník 2011 a starší: dorast sa neprihlasuje cez formulár, ale osobným pohovorom. Napíšte nám e-mail.' : `Na túto sezónu prijímame ročníky ${max} a starších.`);
  };
  by.addEventListener('input', upd);
  upd();
  show(cur, false);
})();
