<?php
$page = page_content('projekt');
$imgs = content_images($page['html']);
$text = strip_images($page['html']);
page_head('Projekt MŠVVaŠ SR', 'Podpora žiakov základných a stredných škôl – šport, hudba a rozvoj projektových zručností.', []);
?>
<section class="page-body">
  <div class="container layout-2">
    <div>
      <div class="prose reveal">
        <?php
        // úvod, zoznam cieľov (4 odseky) a zvyšok
        preg_match_all('~<p>(.*?)</p>~s', $text, $pm);
        $ps = $pm[1];
        echo '<p class="lead">' . $ps[0] . '</p>';
        echo '<p>' . $ps[1] . '</p>';
        ?>
      </div>
      <div class="values reveal" style="margin:2rem 0">
        <div class="value"><?= icon('ball') ?><div><b><?= e(strip_tags($ps[2])) ?></b></div></div>
        <div class="value"><?= icon('music') ?><div><b><?= e(strip_tags($ps[3])) ?></b></div></div>
        <div class="value"><?= icon('star') ?><div><b><?= e(strip_tags($ps[4])) ?></b></div></div>
        <div class="value"><?= icon('users') ?><div><b><?= e(strip_tags($ps[5])) ?></b></div></div>
      </div>
      <div class="prose reveal"><?php for ($k = 6; $k < count($ps); $k++) echo '<p>' . $ps[$k] . '</p>'; ?></div>
      <?php if ($imgs): ?>
      <a class="figure-link reveal" href="<?= e(upload($imgs[0])) ?>" data-lightbox><img class="team-hero-img" src="<?= e(upload($imgs[0])) ?>" alt="Informácie o projekte" loading="lazy"></a>
      <?php endif; ?>
      <?php if (count($imgs) > 1): ?>
      <div class="gallery reveal">
        <?php foreach (array_slice($imgs, 1) as $im): ?><a href="<?= e(upload($im)) ?>" data-lightbox><img src="<?= e(upload($im, true)) ?>" alt="" loading="lazy"></a><?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
    <aside class="aside">
      <div class="box box--yellow reveal"><h3>Plánované aktivity</h3><p>Týždenný rozvrh tréningov, hudobných a duchovných aktivít.</p><a class="btn btn--green btn--sm" href="<?= e(url('plany/')) ?>">Rozvrh <?= icon('arrow') ?></a></div>
      <div class="box reveal"><h3>Udialo sa</h3><p>Čo sa na aktivitách robí a kto ich vedie.</p><a class="btn btn--outline btn--sm" href="<?= e(url('udialo-sa/')) ?>">Zobraziť <?= icon('arrow') ?></a></div>
    </aside>
  </div>
</section>
