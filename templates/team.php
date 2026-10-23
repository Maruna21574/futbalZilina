<?php
/** @var string $slug */
/** @var array $team */
$page = page_content($slug);
$imgs = content_images($page['html']);
$text = strip_images($page['html']);
$news = array_slice(posts_by_category($slug), 0, 6);
$hero = $imgs[0] ?? $team['hero'];
$gallery = array_slice($imgs, $imgs ? 1 : 0);
page_head($team['name'], $team['blurb'], [['Tímy', 'timy/']], upload($hero), $team['tag']);
?>
<section class="page-body">
  <div class="container layout-2">
    <div>
      <div class="prose reveal">
        <p class="lead"><?= e(strip_tags(explode('</p>', $text)[0])) ?></p>
        <?php $rest = preg_replace('~^.*?</p>~s', '', $text, 1); echo content_html($rest); ?>
      </div>
      <?php if (count($imgs) === 1): ?>
        <a class="figure-link reveal" href="<?= e(upload($imgs[0])) ?>" data-lightbox><img class="team-hero-img" src="<?= e(upload($imgs[0])) ?>" alt="<?= e($team['name']) ?>" loading="lazy"></a>
      <?php elseif ($imgs): ?>
        <div class="gallery reveal">
          <?php foreach ($imgs as $im): ?><a href="<?= e(upload($im)) ?>" data-lightbox><img src="<?= e(upload($im, true)) ?>" alt="<?= e($team['name']) ?>" loading="lazy"></a><?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
    <aside class="aside">
      <div class="box box--yellow reveal">
        <h3>Chceš hrať s nami?</h3>
        <p>Prihlásiť sa môžeš cez formulár. Sezóna <?= e(REG_SEASON) ?>.</p>
        <a class="btn btn--green btn--sm" href="<?= e(REG_FORM_URL) ?>" target="_blank" rel="noopener">Prihlasovací formulár <?= icon('arrow') ?></a>
      </div>
      <div class="box reveal">
        <h3>Info o tíme</h3>
        <ul class="facts">
          <li><?= icon('users') ?><div><small>Kategória</small><b><?= e($team['name']) ?> (<?= e($team['tag']) ?>)</b></div></li>
          <li><?= icon('star') ?><div><small>Vek</small><b><?= e($team['ages']) ?></b></div></li>
          <li><?= icon('ball') ?><div><small>Súťaž</small><b><?= e($team['league']) ?></b></div></li>
          <li><?= icon('pin') ?><div><small>Miesto</small><b>Rajecká 17, Žilina</b></div></li>
        </ul>
      </div>
      <div class="box reveal">
        <h3>Ďalšie tímy</h3>
        <ul>
          <?php foreach (TEAMS as $s => $t): if ($s === $slug) continue; ?><li><a href="<?= e(url($s . '/')) ?>"><?= e($t['name']) ?> <span><?= e($t['tag']) ?></span></a></li><?php endforeach; ?>
          <li><a href="<?= e(url('plany/')) ?>">Rozvrh tréningov <?= icon('arrow') ?></a></li>
        </ul>
      </div>
    </aside>
  </div>
</section>

<?php team_results($slug, $team); ?>

<?php if ($news): ?>
<section class="section">
  <div class="container">
    <div class="section__head reveal">
      <div><span class="eyebrow">Z dianí tímu</span><h2>Články – <?= e($team['name']) ?></h2></div>
      <a class="btn btn--outline" href="<?= e(url('category/' . $slug . '/')) ?>">Všetky články <?= icon('arrow') ?></a>
    </div>
    <div class="post-grid"><?php foreach ($news as $i => $p) post_card($p, $i % 3); ?></div>
  </div>
</section>
<?php endif; ?>
