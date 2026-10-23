<?php
/** @var ?string $status */
/** @var array $errors */
/** @var array $old */
page_head('Kontakt', 'Ozvite sa nám – radi odpovieme na otázky o tréningoch, prihlásení aj spolupráci.');
?>
<section class="page-body">
  <div class="container">
    <div class="contact-grid">
      <div class="contact-cards">
        <a class="contact-card reveal" href="tel:<?= e(CONTACT_PHONE_RAW) ?>"><?= icon('phone') ?><div><small>Telefón</small><b><?= e(CONTACT_PHONE) ?></b></div></a>
        <a class="contact-card reveal" href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= icon('mail') ?><div><small>E-mail</small><b><?= e(CONTACT_EMAIL) ?></b></div></a>
        <div class="contact-card reveal"><?= icon('pin') ?><div><small>Adresa</small><b><?= e(implode(', ', CONTACT_ADDRESS)) ?></b></div></div>
        <div class="org reveal">
          <h3>1. KŠK Žilina</h3>
          <dl>
            <dt>IČO</dt><dd><?= e(ORG_ICO) ?></dd>
            <dt>DIČ</dt><dd><?= e(ORG_DIC) ?></dd>
            <dt>Banka</dt><dd><?= e(ORG_IBAN) ?></dd>
          </dl>
        </div>
      </div>

      <form class="form reveal" method="post" action="<?= e(url('kontakt/')) ?>#formular" id="formular" novalidate>
        <h2>Napíšte nám</h2>
        <?php if ($status === 'ok'): ?><div class="alert alert--ok">Ďakujeme! Správa bola odoslaná, ozveme sa vám čo najskôr.</div><?php endif; ?>
        <?php if ($status === 'fail'): ?><div class="alert alert--bad">Správu sa nepodarilo odoslať. Skúste to prosím znova alebo nám napíšte priamo na <?= e(CONTACT_EMAIL) ?>.</div><?php endif; ?>
        <?php if ($status === 'error'): ?><div class="alert alert--bad">Skontrolujte prosím označené polia.</div><?php endif; ?>
        <input type="hidden" name="t" value="<?= time() ?>">
        <div class="hp" aria-hidden="true"><label>Web <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>
        <div class="row">
          <div class="field"><label for="f-name">Meno a priezvisko *</label><input type="text" id="f-name" name="name" value="<?= e($old['name']) ?>" required autocomplete="name"><?php if (isset($errors['name'])): ?><span class="err"><?= e($errors['name']) ?></span><?php endif; ?></div>
          <div class="field"><label for="f-phone">Telefón</label><input type="tel" id="f-phone" name="phone" value="<?= e($old['phone']) ?>" autocomplete="tel"></div>
        </div>
        <div class="field"><label for="f-email">E-mail *</label><input type="email" id="f-email" name="email" value="<?= e($old['email']) ?>" required autocomplete="email"><?php if (isset($errors['email'])): ?><span class="err"><?= e($errors['email']) ?></span><?php endif; ?></div>
        <div class="field"><label for="f-msg">Správa *</label><textarea id="f-msg" name="message" required><?= e($old['message']) ?></textarea><?php if (isset($errors['message'])): ?><span class="err"><?= e($errors['message']) ?></span><?php endif; ?></div>
        <div class="field"><label class="check"><input type="checkbox" name="gdpr" value="1"> <span>Súhlasím so spracovaním osobných údajov na účel vybavenia správy (<a href="<?= e(url('gdpr/')) ?>" target="_blank">GDPR</a>).</span></label><?php if (isset($errors['gdpr'])): ?><span class="err"><?= e($errors['gdpr']) ?></span><?php endif; ?></div>
        <button class="btn btn--green" type="submit">Odoslať správu <?= icon('arrow') ?></button>
      </form>
    </div>

    <div class="map reveal">
      <iframe title="Mapa – Rajecká 17, Žilina" loading="lazy" src="https://www.openstreetmap.org/export/embed.html?bbox=18.7204%2C49.2072%2C18.7444%2C49.2212&amp;layer=mapnik&amp;marker=49.2142511%2C18.7324446"></iframe>
    </div>
    <p style="text-align:center;margin-top:1rem"><a href="https://www.openstreetmap.org/?mlat=49.2142511&amp;mlon=18.7324446#map=17/49.2142511/18.7324446" target="_blank" rel="noopener">Otvoriť väčšiu mapu</a></p>
  </div>
</section>
