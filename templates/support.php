<?php
$page = page_content('podporte-nas-2');
preg_match_all('~<li><i></i>\s*<p>(.*?)</p>\s*</li>~s', $page['html'], $m);
$wins = $m[1];
$pdf = 'assets/files/2024/02/2percenta.ksk_.pdf';
page_head('Podporím klub', 'Stratégia podpory, naše úspechy a to, čo ešte potrebujeme.', []);
?>
<section class="page-body">
  <div class="container layout-2">
    <div>
      <span class="eyebrow reveal">Stratégia podpory</span>
      <p class="lead reveal" style="margin-top:.8rem">Naplno dávať priestor mladým, aby rástli a rozvíjali svoje dary, napomáhať skvalitňovať prostredie pre ich rast, ako i skvalitňovať „tréneskú“ základňu.</p>

      <h2 style="margin-top:2.5rem" class="reveal">Naše úspechy</h2>
      <ul class="wins reveal">
        <?php foreach ($wins as $w): ?><li><?= $w ?></li><?php endforeach; ?>
      </ul>

      <h2 style="margin-top:3rem" class="reveal">Finančné potreby</h2>
      <ul class="needs reveal">
        <li><?= icon('heart') ?><span>Umelá tráva <small>(máme 16 000 €)</small></span></li>
        <li><?= icon('heart') ?><span>Nová kosačka <small>(máme 1 600 €)</small></span></li>
        <li><?= icon('heart') ?><span>9-miestne auto na zápasy <small>(máme darované 7-miestne)</small></span></li>
        <li><?= icon('heart') ?><span>Obnova osvetlenia umelej trávy</span></li>
        <li class="done"><?= icon('check') ?><span>Umelé zavlažovanie, vyrovnanie a obnova ihriska s prírodnou trávou</span><em>Dokončené</em></li>
      </ul>

      <a class="download reveal" href="<?= e(url($pdf)) ?>" target="_blank" rel="noopener">
        <?= icon('download') ?><div><b>Tlačivo na darovanie 2 %</b><span>Vyhlásenie o poukázaní 2 % alebo 3 % z dane (PDF)</span></div>
      </a>
    </div>
    <aside class="aside">
      <div class="box box--yellow reveal">
        <h3>Podporte nás priamo</h3>
        <p>Finančný dar môžete poslať na náš účet:</p>
        <p style="font-family:'Barlow Condensed';font-weight:800;font-size:1.4rem;line-height:1.2;margin:0"><?= e(ORG_IBAN) ?></p>
        <p style="font-size:.85rem;margin:.6rem 0 0">Prijímateľ: 1. KŠK Žilina, IČO <?= e(ORG_ICO) ?></p>
      </div>
      <div class="box box--green reveal"><h3>Poukáž 2 % z dane</h3><p>Od roku 2020 máme možnosť prijímať 1 %, 2 % alebo 3 % z dane.</p><a class="btn btn--yellow btn--sm" href="<?= e(url('2-percenta/')) ?>">Ako na to <?= icon('arrow') ?></a></div>
    </aside>
  </div>
</section>
