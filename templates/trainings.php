<?php
$groups = load_json('trainings');
page_head('Udialo sa', 'Tréningové plány, aktivity a lektori – čo všetko sa v klube a v rámci projektu robí.', [['Projekt MŠVVaŠ SR', 'projekt/']]);
?>
<section class="page-body">
  <div class="container">
    <?php foreach ($groups as $gi => $g): ?>
      <div class="tabs reveal">
        <h2><?= e($g['title']) ?></h2>
        <div class="tabs__nav" role="tablist">
          <?php foreach ($g['tabs'] as $ti => $t): ?>
            <button class="tabs__btn" role="tab" data-tab="<?= e($t['id']) ?>" aria-selected="<?= $ti === 0 ? 'true' : 'false' ?>"><?= e(preg_replace('~^Tréning\s*[–-]?\s*~u', '', $t['title']) ?: $t['title']) ?></button>
          <?php endforeach; ?>
        </div>
        <?php foreach ($g['tabs'] as $ti => $t): ?>
          <div class="tabs__panel" role="tabpanel" id="tab-<?= e($t['id']) ?>" <?= $ti === 0 ? '' : 'hidden' ?>>
            <h3><?= e($t['title']) ?></h3>
            <?php if ($t['lektori']): ?><div class="by"><?= icon('users') ?><div><b>Lektori:</b> <?= e($t['lektori']) ?></div></div><?php endif; ?>
            <div class="prose prose--wide"><?= $t['html'] ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>
