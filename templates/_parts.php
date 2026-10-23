<?php
// Opakované časti šablón

function wave(string $class = 'pagehead__wave'): string
{
    return '<svg class="' . $class . '" viewBox="0 0 1440 80" preserveAspectRatio="none" aria-hidden="true"><path d="M0 80V38C180 6 330 0 520 24s330 56 540 34c150-16 250-44 380-32v56z"/></svg>';
}

function page_head(string $title, string $lead = '', array $crumbs = [], ?string $bg = null, string $tag = ''): void
{ ?>
<section class="pagehead">
  <?php if ($bg): ?><div class="pagehead__bg" style="background-image:url('<?= e($bg) ?>')"></div><?php endif; ?>
  <div class="container">
    <nav class="crumbs" aria-label="Omrvinková navigácia">
      <a href="<?= e(url()) ?>">Domov</a>
      <?php foreach ($crumbs as [$l, $h]): ?><span>/</span><a href="<?= e(url($h)) ?>"><?= e($l) ?></a><?php endforeach; ?>
      <span>/</span><?= e($title) ?>
    </nav>
    <?php if ($tag): ?><span class="chip pagehead__tag"><?= e($tag) ?></span><?php endif; ?>
    <h1><?= e($title) ?></h1>
    <?php if ($lead): ?><p><?= e($lead) ?></p><?php endif; ?>
  </div>
  <?= wave() ?>
</section>
<?php }

function post_card(array $p, int $delay = 0): void
{ ?>
<a class="post-card reveal" style="--d:<?= $delay * .08 ?>s" href="<?= e(url($p['slug'] . '/')) ?>">
  <div class="post-card__img">
    <?php if ($p['image']): ?><img src="<?= e(upload($p['image'], true)) ?>" alt="" loading="lazy" width="480" height="360"><?php else: ?><div class="post-card__ph"><img src="<?= e(asset('img/logo.png')) ?>" alt=""></div><?php endif; ?>
    <span class="chip"><?= e(category_label($p['category'])) ?></span>
  </div>
  <div class="post-card__body">
    <time datetime="<?= e($p['date']) ?>"><?= icon('calendar') ?> <?= e(sk_date($p['date'])) ?></time>
    <h3><?= e($p['title']) ?></h3>
    <p><?= e($p['excerpt']) ?></p>
    <span class="post-card__more">Čítať viac <?= icon('arrow') ?></span>
  </div>
</a>
<?php }

function team_card(string $slug, array $t, int $delay = 0): void
{ ?>
<a class="team-card reveal" style="--d:<?= $delay * .1 ?>s" href="<?= e(url($slug . '/')) ?>">
  <img src="<?= e(asset($t['card'])) ?>" alt="<?= e($t['name']) ?>" loading="lazy" width="760" height="960">
  <span class="team-card__tag"><?= e($t['tag']) ?></span>
  <div class="team-card__body">
    <small><?= e($t['ages']) ?></small>
    <h3><?= e($t['name']) ?></h3>
    <p><?= e($t['blurb']) ?></p>
    <span class="team-card__go">Spoznaj tím <?= icon('arrow') ?></span>
  </div>
</a>
<?php }

function results_tiles(?string $only = null): void
{ ?>
<div class="results">
  <?php foreach (RESULTS as $i => [$slug, $label, $href, $host]): if ($only && $slug !== $only) continue; ?>
    <a class="result reveal" style="--d:<?= $i * .08 ?>s" href="<?= e($href) ?>" target="_blank" rel="noopener">
      <span class="result__ico"><?= icon('trophy') ?></span>
      <span class="result__txt"><b><?= e($label) ?></b><small>Tabuľka a výsledky · <?= e($host) ?></small></span>
      <?= icon('ext') ?>
    </a>
  <?php endforeach; ?>
</div>
<?php }

function team_results(string $slug, array $team): void
{
    if (empty($team['sportnet'])) return;
    $base = 'https://sportnet.sme.sk/futbalnet/k/1-ksk-zilina/tim/' . $team['sportnet'] . '/';
    $items = [
        ['Tabuľka', 'Aktuálne poradie v súťaži', 'tabulky/', 'trophy'],
        ['Výsledky', 'Odohrané zápasy a skóre', 'vysledky/', 'ball'],
        ['Program', 'Nadchádzajúce zápasy', 'program/', 'calendar'],
        ['Súpiska', 'Hráči a tréneri tímu', 'hraci/', 'users'],
    ];
    foreach (RESULTS as [$s, $label, $href, $host]) {
        if ($s === $slug && $host === 'sportvin.sk') $items[] = [$label, 'Výsledky na sportvin.sk', null, 'trophy', $href];
    }
    ?>
<section class="section section--tint" id="vysledky">
  <div class="container">
    <div class="section__head reveal">
      <div>
        <span class="eyebrow">Súťaž</span>
        <h2>Výsledky a tabuľky</h2>
        <p><?= e($team['name']) ?> (<?= e($team['tag']) ?>) · <?= e($team['league']) ?>. Aktuálne dáta nájdeš na portáli sportnet.sme.sk.</p>
      </div>
    </div>
    <div class="results results--team">
      <?php foreach ($items as $i => $it): $href = $it[4] ?? $base . $it[2]; ?>
        <a class="result reveal" style="--d:<?= $i * .08 ?>s" href="<?= e($href) ?>" target="_blank" rel="noopener">
          <span class="result__ico"><?= icon($it[3]) ?></span>
          <span class="result__txt"><b><?= e($it[0]) ?></b><small><?= e($it[1]) ?></small></span>
          <?= icon('ext') ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php }
