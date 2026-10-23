<?php
page_head('2 % z dane', 'Dobite našich futbalistov na 100 %. Poukáž 1 %, 2 % alebo 3 % z dane.', [['Podporte nás', 'podporim/']]);
?>
<section class="page-body">
  <div class="container layout-2">
    <div>
      <div class="cta-band reveal" style="grid-template-columns:1fr;margin-bottom:2.5rem">
        <div>
          <h2 style="max-width:none">Dobite našich futbalistov na 100 %</h2>
          <p>Aj tento rok sa uchádzame o podporu cez poukázanie vašich <b>1 %</b> (organizácia alebo firmy), <b>2 %</b> alebo <b>3 %</b> z dane.</p>
        </div>
      </div>
      <div class="alert alert--ok reveal">Nezabudnite požiadať zamestnávateľa o zúčtovanie dane – do 15. februára 2025!</div>
      <div class="values reveal" style="grid-template-columns:1fr;margin-top:1.6rem">
        <a class="download" href="<?= e(url('assets/files/2025/03/VYhlasenie_KSK_2perc_2025.pdf')) ?>" target="_blank" rel="noopener">
          <?= icon('download') ?><div><b>Stiahni si tlačivo</b><span>Vyhlásenie o poukázaní 2 % (PDF, 2025)</span></div>
        </a>
        <a class="download" href="<?= e(url('assets/files/2024/02/2percenta.ksk_.pdf')) ?>" target="_blank" rel="noopener">
          <?= icon('download') ?><div><b>Vyhlásenie o poukázaní 2 % alebo 3 % z dane</b><span>Staršie tlačivo (PDF, 2024)</span></div>
        </a>
      </div>
    </div>
    <aside class="aside">
      <div class="box box--yellow reveal">
        <h3>Údaje prijímateľa</h3>
        <ul class="facts">
          <li><?= icon('users') ?><div><small>Názov</small><b>1. KŠK Žilina</b></div></li>
          <li><?= icon('check') ?><div><small>IČO</small><b><?= e(ORG_ICO) ?></b></div></li>
          <li><?= icon('heart') ?><div><small>Účet</small><b><?= e(ORG_IBAN) ?></b></div></li>
        </ul>
      </div>
    </aside>
  </div>
</section>
