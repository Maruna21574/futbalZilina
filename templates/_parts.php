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
  <img src="<?= e(upload($t['hero'], true)) ?>" alt="<?= e($t['name']) ?>" loading="lazy" width="480" height="360">
  <span class="team-card__tag"><?= e($t['tag']) ?></span>
  <div class="team-card__body">
    <small><?= e($t['ages']) ?></small>
    <h3><?= e($t['name']) ?></h3>
    <p><?= e($t['blurb']) ?></p>
    <span class="team-card__go">Spoznaj tím <?= icon('arrow') ?></span>
  </div>
</a>
<?php }
