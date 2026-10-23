<?php
$page = page_content('treneri');
page_head('Profil trénera', 'Správny chlap z kostola, s vášňou pre futbal a pedagogickou kompetenciou.', [['O nás', 'o-nas/']]);
?>
<section class="page-body">
  <div class="container layout-2">
    <div>
      <div class="prose reveal"><?= content_html(preg_replace('~^<p>~', '<p class="lead">', trim($page['html']))) ?></div>
      <h2 style="margin-top:3rem" class="reveal">Cnosti, ktoré rozvíjame</h2>
      <div class="values reveal" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr))">
        <div class="value"><?= icon('check') ?><div><b>Spravodlivosť</b><span>Fér prístup k spoluhráčom aj súperom.</span></div></div>
        <div class="value"><?= icon('check') ?><div><b>Čestnosť</b><span>Pravdivosť na ihrisku aj mimo neho.</span></div></div>
        <div class="value"><?= icon('check') ?><div><b>Úcta</b><span>Rešpekt k druhým ľuďom.</span></div></div>
        <div class="value"><?= icon('check') ?><div><b>Tímový duch</b><span>Spolu víťazíme aj prehrávame.</span></div></div>
        <div class="value"><?= icon('check') ?><div><b>Odvaha</b><span>Pustiť sa do výzvy a nevzdať sa.</span></div></div>
      </div>
    </div>
    <aside class="aside">
      <div class="box box--green reveal">
        <h3>Tréneri a lektori</h3>
        <p>Mnohí naši tréneri sú držitelia trénerských licencií a všetci sa naďalej vzdelávajú.</p>
        <a class="btn btn--yellow btn--sm" href="<?= e(url('udialo-sa/')) ?>">Kto vedie tréningy <?= icon('arrow') ?></a>
      </div>
      <div class="box reveal"><h3>Chceš trénovať?</h3><p>Posilu do trénerského tímu radi privítame. Ozvi sa nám.</p><a class="btn btn--green btn--sm" href="<?= e(url('kontakt/')) ?>">Kontaktuj nás</a></div>
    </aside>
  </div>
</section>
