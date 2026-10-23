<?php
/** @var array $post */
$all = all_posts();
$idx = array_search($post['slug'], array_column($all, 'slug'));
$newer = $all[$idx - 1] ?? null;
$older = $all[$idx + 1] ?? null;
$related = array_slice(array_filter(posts_by_category($post['category']), fn($p) => $p['slug'] !== $post['slug']), 0, 3);
?>
<section class="pagehead">
  <div class="container">
    <nav class="crumbs"><a href="<?= e(url()) ?>">Domov</a><span>/</span><a href="<?= e(url('aktuality/')) ?>">Aktuality</a><span>/</span><a href="<?= e(url('category/' . $post['category'] . '/')) ?>"><?= e(category_label($post['category'])) ?></a></nav>
    <span class="chip pagehead__tag"><?= e(category_label($post['category'])) ?></span>
    <h1 style="max-width:24ch"><?= e($post['title']) ?></h1>
    <div class="article-meta"><time datetime="<?= e($post['date']) ?>"><?= icon('calendar') ?> <?= e(sk_date($post['date'])) ?></time></div>
  </div>
  <?= wave() ?>
</section>
<article class="article">
  <div class="container">
    <div class="prose<?= str_contains($post['html'], 'class="gallery"') ? ' prose--wide' : '' ?>">
      <?= content_html($post['html']) ?>
    </div>
    <div class="share-nav">
      <?php if ($older): ?><a class="btn btn--outline btn--sm" href="<?= e(url($older['slug'] . '/')) ?>">← Staršie</a><?php else: ?><span></span><?php endif; ?>
      <a class="btn btn--green btn--sm" href="<?= e(url('aktuality/')) ?>">Všetky aktuality</a>
      <?php if ($newer): ?><a class="btn btn--outline btn--sm" href="<?= e(url($newer['slug'] . '/')) ?>">Novšie →</a><?php else: ?><span></span><?php endif; ?>
    </div>
  </div>
</article>
<?php if ($related): ?>
<section class="section section--tint">
  <div class="container">
    <div class="section__head"><div><span class="eyebrow">Mohlo by vás zaujímať</span><h2>Podobné články</h2></div></div>
    <div class="post-grid"><?php foreach ($related as $i => $p) post_card($p, $i); ?></div>
  </div>
</section>
<?php endif; ?>
