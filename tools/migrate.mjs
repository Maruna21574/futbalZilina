// Migrácia obsahu zo starého WordPress webu (REST API dump v tools/raw) do content/*.json
// Spustenie: node migrate.mjs   (potom node download-images.mjs)
import fs from 'node:fs';
import path from 'node:path';
import * as cheerio from 'cheerio';

const ROOT = path.resolve(import.meta.dirname, '..');
const RAW = path.join(import.meta.dirname, 'raw');
const OUT_CONTENT = path.join(ROOT, 'content');
const j = f => JSON.parse(fs.readFileSync(path.join(RAW, f), 'utf8'));

const posts = [...j('posts.json'), ...j('posts2.json')];
const pages = j('pages.json');
const cats = j('categories.json');

const images = new Map(); // originalUrl -> {rel, fallback}
const files = new Set();
const origUrl = u => u.replace(/-\d+x\d+(?=\.\w+$)/, '').replace(/^http:/, 'https:').replace('://www.', '://');

function register(url) {
  if (!/^https?:\/\//i.test(url)) return null;
  const o = origUrl(url);
  let rel;
  const m = o.match(/kskzilina\.sk\/wp-content\/uploads\/(.+)$/);
  if (m) rel = decodeURIComponent(m[1]);
  else {
    const u = new URL(o);
    if (!/\.(jpe?g|png|gif|webp)$/i.test(u.pathname)) return null;
    rel = 'ext/' + u.hostname + '/' + path.basename(decodeURIComponent(u.pathname));
  }
  rel = rel.replace(/[^\w\-./]+/g, '_');
  if (!images.has(o)) images.set(o, { rel, fallback: url });
  return images.get(o).rel;
}

const full = rel => `assets/uploads/${rel}`;
const thumb = rel => `assets/uploads/thumbs/${rel.replace(/\.(png|gif)$/i, '.jpg')}`;
const isImg = h => /\.(jpe?g|png|gif|webp)$/i.test(h || '');

function clean(html) {
  const $ = cheerio.load(`<div id="r">${html}</div>`, null, false);
  $('style,script,noscript,form,button,input,.fusion-alert').remove();

  // galérie -> jednoduchý grid
  $('.fusion-gallery, .gallery, .awb-gallery-wrapper').each((_, el) => {
    if ($(el).parents('.fusion-gallery, .gallery').length) return;
    const items = [];
    $(el).find('a[href]').each((_, a) => {
      const href = $(a).attr('href') || '';
      const rel = isImg(href) ? register(href) : null;
      if (rel && !items.includes(rel)) items.push(rel);
    });
    if (!items.length) { $(el).remove(); return; }
    $(el).replaceWith(`<div class="gallery">${items.map(r => `<a href="${full(r)}" data-lightbox><img src="${thumb(r)}" alt="" loading="lazy"></a>`).join('')}</div>`);
  });

  // obrázky v obsahu
  $('img').each((_, el) => {
    if ($(el).closest('.gallery').length) return;
    const rel = register($(el).attr('src') || '');
    if (!rel) { $(el).remove(); return; }
    const alt = ($(el).attr('alt') || '').replace(/"/g, '');
    const parent = $(el).parent('a');
    const tag = `<img src="${full(rel)}" alt="${alt}" loading="lazy">`;
    if (parent.length && isImg(parent.attr('href'))) parent.replaceWith(`<a href="${full(rel)}" data-lightbox>${tag}</a>`);
    else $(el).replaceWith(tag);
  });

  // odkazy
  $('a[href]').each((_, a) => {
    const h = $(a).attr('href');
    if ($(a).attr('data-lightbox') !== undefined) return;
    if (/kskzilina\.sk\/wp-content\/uploads\//i.test(h)) {
      if (isImg(h)) { $(a).attr('href', full(register(h))).attr('data-lightbox', ''); }
      else { files.add(h); $(a).attr('href', h.replace(/^https?:\/\/(www\.)?kskzilina\.sk\/wp-content\/uploads\//, 'assets/files/')); }
    } else if (/^https?:\/\/(www\.)?kskzilina\.sk\//i.test(h)) {
      $(a).attr('href', h.replace(/^https?:\/\/(www\.)?kskzilina\.sk\/?/, '/').replace(/^\/$/, '/'));
    }
  });

  const keep = new Set(['p','h1','h2','h3','h4','h5','h6','ul','ol','li','strong','b','em','i','a','img','blockquote','table','thead','tbody','tr','td','th','br','iframe','figure','figcaption','div','span','hr','u']);
  $('#r *').each((_, el) => {
    if (!keep.has(el.name)) { $(el).replaceWith($(el).contents()); return; }
    for (const k of Object.keys(el.attribs)) {
      const ok =
        (el.name === 'a' && ['href', 'target', 'data-lightbox'].includes(k)) ||
        (el.name === 'img' && ['src', 'alt', 'loading'].includes(k)) ||
        (el.name === 'iframe' && ['src', 'allowfullscreen', 'title'].includes(k)) ||
        (el.name === 'div' && k === 'class' && el.attribs[k] === 'gallery') ||
        (['td', 'th'].includes(el.name) && ['colspan', 'rowspan'].includes(k));
      if (!ok) $(el).removeAttr(k);
    }
  });
  for (let i = 0; i < 12; i++) $('#r div:not(.gallery), #r span').each((_, el) => { $(el).replaceWith($(el).contents()); });
  $('#r p, #r h1, #r h2, #r h3, #r h4, #r h5, #r h6, #r li, #r strong, #r em').each((_, el) => {
    if (!$(el).text().trim() && !$(el).find('img,iframe').length) $(el).remove();
  });
  return $('#r').html().replace(/ /g, ' ').replace(/\n\s*\n+/g, '\n').trim();
}

const text = h => cheerio.load(`<div>${h}</div>`)('div').text().replace(/\s+/g, ' ').trim();
const catMap = Object.fromEntries(cats.map(c => [c.id, { slug: c.slug, name: c.name }]));

async function featured(id) {
  if (!id) return null;
  try {
    const r = await fetch(`https://kskzilina.sk/wp-json/wp/v2/media/${id}?_fields=source_url`);
    const d = await r.json();
    return d.source_url ? register(d.source_url) : null;
  } catch { return null; }
}

const outPosts = [];
for (const p of [...posts].sort((a, b) => b.date.localeCompare(a.date))) {
  const html = clean(p.content.rendered);
  let feat = await featured(p.featured_media);
  if (!feat) {
    const m = html.match(/assets\/uploads\/(?:thumbs\/)?([^"]+)"/);
    if (m) feat = m[1].replace(/\.jpg$/, (x) => x); // thumb rel
  }
  const plain = text(html);
  outPosts.push({
    slug: p.slug,
    title: text(p.title.rendered),
    date: p.date.slice(0, 10),
    category: (catMap[p.categories[0]] || { slug: 'nezaradene' }).slug,
    image: feat,
    excerpt: plain.length > 190 ? plain.slice(0, 190).replace(/\s+\S*$/, '') + '…' : plain,
    html,
  });
}

const pageSlugs = ['domov','o-nas','treneri','pripravka','mladsi-ziaci','ziaci','dorast','kontakt','projekt','skuska2','podporte-nas','podporte-nas-2','gdpr','ukazka-strany','zastavsanasajmajmesiac'];
const outPages = {};
for (const s of pageSlugs) {
  const pg = pages.find(x => x.slug === s);
  if (pg) outPages[s] = { title: text(pg.title.rendered), html: clean(pg.content.rendered) };
}

fs.mkdirSync(OUT_CONTENT, { recursive: true });
fs.writeFileSync(path.join(OUT_CONTENT, 'posts.json'), JSON.stringify(outPosts, null, 1));
fs.writeFileSync(path.join(OUT_CONTENT, 'pages.json'), JSON.stringify(outPages, null, 1));
fs.writeFileSync(path.join(OUT_CONTENT, 'categories.json'), JSON.stringify(cats.map(c => ({ slug: c.slug, name: c.name, count: c.count })), null, 1));
fs.writeFileSync(path.join(import.meta.dirname, 'images.json'), JSON.stringify([...images].map(([url, v]) => ({ url, ...v })), null, 1));
fs.writeFileSync(path.join(import.meta.dirname, 'files.json'), JSON.stringify([...files]));
console.log('posts', outPosts.length, 'pages', Object.keys(outPages).length, 'images', images.size, 'files', files.size);
