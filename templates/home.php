<?php
$posts = all_posts();
$latest = array_slice($posts, 0, 4);
$feature = array_shift($latest);
?>
<section class="hero">
  <div class="container hero__in">
    <div class="hero__copy">
      <span class="eyebrow">Mládežnícky futbalový klub · Žilina</span>
      <h1>Bojujeme o <mark>dušu</mark> <span>na ihrisku i v živote</span></h1>
      <p class="hero__lead">Futbal, ktorý vychováva. Od prípravky až po dorast učíme deti a mladých ľudí hrať s radosťou, fair play a charakterom – v duchu saleziánskej tradície dona Bosca.</p>
      <div class="hero__cta">
        <a class="btn btn--yellow" href="<?= e(REG_FORM_URL) ?>" target="_blank" rel="noopener">Prihlásiť do klubu <?= icon('arrow') ?></a>
        <a class="btn btn--ghost" href="<?= e(url('o-nas/')) ?>">Spoznaj klub</a>
      </div>
    </div>
    <aside class="hero__card">
      <div class="hero__photo"><img src="<?= e(upload('2025/06/WhatsApp-Image-2025-06-06-at-21.53.21.jpeg', true)) ?>" alt="Hráči 1. KŠK Žilina" width="480" height="360"></div>
      <div class="hero__reg">
        <small>Nábor</small>
        <strong>Sezóna <?= e(REG_SEASON) ?></strong>
        <span>Prihlasovanie do <?= e(REG_DEADLINE) ?>, potom po individuálnej dohode.</span>
      </div>
    </aside>
  </div>
</section>

<div class="ribbon" aria-hidden="true">
  <div class="ribbon__track">
    <?php for ($r = 0; $r < 2; $r++): foreach (TEAMS as $t): ?><span><?= e($t['name']) ?> <?= e($t['tag']) ?> <?= icon('ball') ?></span><?php endforeach; ?>
    <span>Bojujeme o dušu <?= icon('star') ?></span><span>Prihlasovanie <?= e(REG_SEASON) ?> <?= icon('ball') ?></span><?php endfor; ?>
  </div>
</div>

<section class="section">
  <div class="container">
    <div class="section__head reveal">
      <div>
        <span class="eyebrow">Naše tímy</span>
        <h2>Pre každý vek jedno družstvo</h2>
        <p>Do športovej činnosti pozývame všetky deti, bez ohľadu na ich šikovnosť a nadanie. Prihlásiť sa môžete kedykoľvek počas roka.</p>
      </div>
      <a class="btn btn--outline" href="<?= e(url('timy/')) ?>">Všetky tímy <?= icon('arrow') ?></a>
    </div>
    <div class="teams">
      <?php $i = 0; foreach (TEAMS as $slug => $t) team_card($slug, $t, $i++); ?>
    </div>
  </div>
</section>

<section class="section section--tint">
  <div class="container">
    <div class="section__head reveal">
      <div>
        <span class="eyebrow">Aktuality</span>
        <h2>Čo sa u nás deje</h2>
      </div>
      <a class="btn btn--outline" href="<?= e(url('aktuality/')) ?>">Všetky články <?= icon('arrow') ?></a>
    </div>
    <?php if ($feature): ?>
    <a class="feature-post reveal" href="<?= e(url($feature['slug'] . '/')) ?>">
      <div class="feature-post__img"><?php if ($feature['image']): ?><img src="<?= e(upload($feature['image'], true)) ?>" alt="" loading="lazy"><?php endif; ?></div>
      <div class="feature-post__body">
        <span class="chip"><?= e(category_label($feature['category'])) ?></span>
        <time datetime="<?= e($feature['date']) ?>"><?= e(sk_date($feature['date'])) ?></time>
        <h3><?= e($feature['title']) ?></h3>
        <p><?= e($feature['excerpt']) ?></p>
        <span class="btn btn--yellow btn--sm">Čítať článok <?= icon('arrow') ?></span>
      </div>
    </a>
    <?php endif; ?>
    <div class="post-grid">
      <?php foreach ($latest as $i => $p) post_card($p, $i); ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="container split">
    <div class="reveal">
      <span class="eyebrow">O klube</span>
      <h2>Viac než futbal</h2>
      <p class="lead">1. KŠK Žilina ponúka saleziánsku mládežnícku spiritualitu ako štýl života založený na rodinnosti, optimizme a radostnom prežívaní každodennosti.</p>
      <div class="values">
        <div class="value"><?= icon('users') ?><div><b>Rodinnosť</b><span>Komunita, v ktorej sa každý cíti doma.</span></div></div>
        <div class="value"><?= icon('star') ?><div><b>Optimizmus</b><span>Radosť z hry aj z každého dňa.</span></div></div>
        <div class="value"><?= icon('heart') ?><div><b>Angažovanosť</b><span>Vnímavosť k potrebám druhých.</span></div></div>
        <div class="value"><?= icon('cross') ?><div><b>Hodnoty</b><span>Spravodlivosť, čestnosť, úcta, odvaha.</span></div></div>
      </div>
      <div class="hero__cta">
        <a class="btn btn--green" href="<?= e(url('o-nas/')) ?>">Čítať viac <?= icon('arrow') ?></a>
        <a class="btn btn--outline" href="<?= e(url('treneri/')) ?>">Naši tréneri</a>
      </div>
    </div>
    <div class="split__img reveal" style="--d:.15s">
      <img src="<?= e(upload('2017/02/ziaci-16-17-br-1.jpg')) ?>" alt="Tréning 1. KŠK Žilina" loading="lazy" width="480" height="360">
    </div>
  </div>
</section>

<section class="section section--dark">
  <div class="container">
    <div class="stats reveal">
      <div class="stat"><b data-count="4">4</b><span>Vekové kategórie</span></div>
      <div class="stat"><b data-count="2">2</b><span>Tréningy týždenne</span></div>
      <div class="stat"><b data-count="<?= count($posts) ?>"><?= count($posts) ?></b><span>Článkov z klubu</span></div>
      <div class="stat"><b data-count="100">100</b><span>% radosti z hry</span></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="cta-band reveal">
      <div>
        <span class="eyebrow">Podporte nás</span>
        <h2>Dobite našich futbalistov na 100 %</h2>
        <p>Aj tento rok sa uchádzame o podporu cez poukázanie 1 %, 2 % alebo 3 % z dane. Vaša pomoc ide priamo do tréningového zázemia, výstroja a práce s mládežou.</p>
        <div class="hero__cta">
          <a class="btn btn--yellow" href="<?= e(url('2-percenta/')) ?>">Poukázať 2 % <?= icon('arrow') ?></a>
          <a class="btn btn--ghost" href="<?= e(url('podporim/')) ?>">Ako môžem pomôcť</a>
        </div>
      </div>
      <div class="cta-band__side"><div class="big">2<small>%</small></div><span class="lbl">z dane pre mládež</span></div>
    </div>
  </div>
</section>

<section class="section section--tint" style="padding-top:3rem;padding-bottom:3.5rem">
  <div class="container">
    <div class="section__head reveal" style="margin-bottom:1.6rem"><div><span class="eyebrow">Partneri a podporovatelia</span></div></div>
    <div class="partners reveal">
      <?php foreach (PARTNERS as [$n, $href, $img]): ?>
        <a class="partner" href="<?= e($href) ?>" target="_blank" rel="noopener" title="<?= e($n) ?>"><img src="<?= e(upload($img)) ?>" alt="<?= e($n) ?>" loading="lazy"></a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
