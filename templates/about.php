<?php
$page = page_content('o-nas');
$imgs = content_images($page['html']);
$text = strip_images($page['html']);
$text = preg_replace('~<h1>.*?</h1>~s', '', $text);
page_head('O nás', 'Futbal, ktorý vychováva – saleziánska mládežnícka spiritualita na ihrisku.', [], upload($imgs[2] ?? 'x'));
?>
<section class="page-body">
  <div class="container split">
    <div class="reveal">
      <span class="eyebrow">Kto sme</span>
      <h2>1. KŠK Žilina</h2>
      <div class="prose"><?= content_html(preg_replace('~^<p>~', '<p class="lead">', trim($text))) ?></div>
      <div class="hero__cta" style="margin-top:2rem">
        <a class="btn btn--green" href="<?= e(url('timy/')) ?>">Naše tímy <?= icon('arrow') ?></a>
        <a class="btn btn--outline" href="<?= e(url('kontakt/')) ?>">Kontakt</a>
      </div>
    </div>
    <?php if ($imgs): ?>
    <div class="split__img reveal" style="--d:.15s"><img src="<?= e(upload($imgs[0], true)) ?>" alt="1. KŠK Žilina" loading="lazy" width="480" height="360"></div>
    <?php endif; ?>
  </div>
</section>

<section class="section section--tint">
  <div class="container">
    <div class="section__head reveal"><div><span class="eyebrow">Na čom stojíme</span><h2>Saleziánske hodnoty</h2><p>Nadväzujeme na výchovnú tradíciu dona Bosca – so zameraním na rodinnosť, optimizmus a radosť každodenného života.</p></div></div>
    <div class="values reveal" style="grid-template-columns:repeat(auto-fit,minmax(250px,1fr))">
      <div class="value"><?= icon('users') ?><div><b>Rodinnosť</b><span>Atmosféra rodiny, v ktorej je každý prijatý.</span></div></div>
      <div class="value"><?= icon('star') ?><div><b>Optimizmus</b><span>Radostné prežívanie každodennosti.</span></div></div>
      <div class="value"><?= icon('heart') ?><div><b>Angažovanosť</b><span>Vnímavosť pre potreby chudobných a druhých.</span></div></div>
      <div class="value"><?= icon('cross') ?><div><b>Vzťah s Bohom</b><span>Osobný vzťah s Bohom ako základ života.</span></div></div>
    </div>
  </div>
</section>

<?php if (count($imgs) > 0): ?>
<section class="section">
  <div class="container">
    <div class="section__head reveal"><div><span class="eyebrow">Fotogaléria</span><h2>Z nášho života</h2></div></div>
    <div class="gallery reveal">
      <?php foreach ($imgs as $im): ?><a href="<?= e(upload($im)) ?>" data-lightbox><img src="<?= e(upload($im, true)) ?>" alt="" loading="lazy"></a><?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>
