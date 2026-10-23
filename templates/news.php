<?php
/** @var ?string $cat */
$cats = ['' => 'Všetko', 'klub' => 'Klub', 'pripravka' => 'Prípravka', 'mladsi-ziaci' => 'Mladší žiaci', 'ziaci' => 'Starší žiaci', 'dorast' => 'Dorast', 'turnaj' => 'Turnaje', 'nezaradene' => 'Ostatné'];
page_head($cat ? category_label($cat) : 'Aktuality', 'Správy zo zápasov, turnajov a života klubu.', $cat ? [['Aktuality', 'aktuality/']] : []);
$link = fn(int $p) => url(($cat ? 'category/' . $cat . '/' : 'aktuality/') . ($p > 1 ? '?strana=' . $p : ''));
?>
<section class="page-body">
  <div class="container">
    <div class="filters">
      <?php foreach ($cats as $s => $l): ?><a class="<?= ($cat ?? '') === $s ? 'on' : '' ?>" href="<?= e(url($s ? 'category/' . $s . '/' : 'aktuality/')) ?>"><?= e($l) ?></a><?php endforeach; ?>
    </div>
    <div class="post-grid"><?php foreach ($items as $i => $p) post_card($p, $i % 3); ?></div>
    <?php if ($pages > 1): ?>
      <nav class="pagination" aria-label="Stránkovanie">
        <?php if ($page > 1): ?><a href="<?= e($link($page - 1)) ?>" aria-label="Predchádzajúca">←</a><?php endif; ?>
        <?php for ($n = 1; $n <= $pages; $n++): ?>
          <?= $n === $page ? '<span class="cur">' . $n . '</span>' : '<a href="' . e($link($n)) . '">' . $n . '</a>' ?>
        <?php endfor; ?>
        <?php if ($page < $pages): ?><a href="<?= e($link($page + 1)) ?>" aria-label="Ďalšia">→</a><?php endif; ?>
      </nav>
    <?php endif; ?>
  </div>
</section>
