<?php
$days = load_json('schedule');
function event_class(array $it): string
{
    $t = mb_strtolower($it['title']);
    $c = 'event';
    if (str_contains($t, 'tréning')) $c .= '';
    elseif (preg_match('~zápas|turnaj~u', $t)) $c .= ' event--match';
    elseif (preg_match('~gitar|zbor|synkop|inštrument|hudob|cajon~u', $t)) $c .= ' event--music';
    else $c .= ' event--other';
    if (!empty($it['irregular'])) $c .= ' event--irreg';
    return $c;
}
page_head('Plánované aktivity', 'Pravidelné týždenné aktivity – tréningy, zápasy, hudba a duchovné programy.', [['Projekt MŠVVaŠ SR', 'projekt/']]);
?>
<section class="page-body">
  <div class="container">
    <div class="schedule reveal">
      <?php foreach ($days as $d): ?>
        <div class="day">
          <div class="day__name"><?= e($d['name']) ?></div>
          <div class="day__list">
            <?php foreach ($d['items'] as $it): ?>
              <div class="<?= e(event_class($it)) ?>"><b><?= e($it['title']) ?></b><time><?= icon('clock') ?> <?= e($it['time']) ?></time></div>
            <?php endforeach; ?>
            <?php if (!$d['items']): ?><div class="event event--other"><b>Voľno</b></div><?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="legend reveal">
      <span>Tréning</span><span class="m">Zápas / turnaj</span><span class="mu">Hudba</span><span class="o">Ostatné aktivity</span><span class="ir">Nepravidelné mesačné aktivity</span>
    </div>
    <div class="hero__cta" style="margin-top:2.5rem">
      <a class="btn btn--green" href="<?= e(url('udialo-sa/')) ?>">Čo sa na aktivitách robí <?= icon('arrow') ?></a>
      <a class="btn btn--outline" href="<?= e(url('kontakt/')) ?>">Opýtať sa na detaily</a>
    </div>
  </div>
</section>
