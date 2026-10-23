<?php page_head('Naše tímy', 'Prípravka, mladší žiaci, starší žiaci a dorast. Prihlásiť sa k nám do klubu je možné kedykoľvek počas roka.'); ?>
<section class="section" style="padding-top:2rem">
  <div class="container">
    <div class="teams">
      <?php $i = 0; foreach (TEAMS as $slug => $t) team_card($slug, $t, $i++); ?>
    </div>
    <div class="cta-band reveal" style="margin-top:4rem">
      <div>
        <h2>Prihlás sa na sezónu <?= e(REG_SEASON) ?></h2>
        <p>Prihlasovanie trvá do <?= e(REG_DEADLINE) ?> a následne po individuálnej dohode.</p>
        <a class="btn btn--yellow" href="<?= e(REG_FORM_URL) ?>" target="_blank" rel="noopener">Prihlasovací formulár <?= icon('arrow') ?></a>
      </div>
      <div class="cta-band__side"><div class="big">4</div><span class="lbl">tímy pre každý vek</span></div>
    </div>
  </div>
</section>
