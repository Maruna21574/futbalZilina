<?php
/** @var ?string $status */
/** @var array $errors */
/** @var array $old */
$yearMap = [];
foreach (REG_CATEGORIES as [, $name, $when, $years]) foreach ($years as $y) $yearMap[$y] = $name . ' – tréningy ' . $when;
$firstErrStep = 1;
if (isset($errors['player_name']) || isset($errors['birth_year']) || isset($errors['experience'])) $firstErrStep = 2;
if (isset($errors['cvc']) || isset($errors['voucher']) || isset($errors['gdpr'])) $firstErrStep = 3;
if (isset($errors['parent_name']) || isset($errors['parent_email']) || isset($errors['parent_phone'])) $firstErrStep = 1;

page_head('Prihláška do klubu', 'Sezóna ' . REG_SEASON . ' · vyplnenie trvá asi dve minúty.', []);
?>
<section class="page-body">
  <div class="container layout-2">
    <div>
      <?php if ($status === 'ok'): ?>
        <div class="thanks reveal">
          <?= icon('check') ?>
          <h2>Vaša prihláška bola zaregistrovaná</h2>
          <p>Ďakujeme za vyplnenie tejto prihlášky. O prihlásení Vášho dieťaťa Vás budeme informovať aj s prípadnými podrobnými informáciami pre ďalšiu sezónu.<?= isset($_GET['mail']) ? ' Potvrdenie sme poslali aj na Váš e-mail.' : '' ?></p>
          <p class="sign">S pozdravom<br>tréneri a vedenie klubu 1. KŠK Žilina</p>
          <a class="btn btn--green" href="<?= e(url()) ?>">Späť na úvod <?= icon('arrow') ?></a>
        </div>
      <?php else: ?>
        <p class="lead reveal">Cez tento formulár môžete žiadať o prihlásenie svojich detí do saleziánskeho futbalového klubu 1. KŠK Žilina pre sezónu <?= e(REG_SEASON) ?>.</p>

        <form class="apply reveal" id="prihlaska" method="post" action="<?= e(url('prihlaska/')) ?>#prihlaska" novalidate data-start="<?= (int)$firstErrStep ?>" data-years="<?= e(json_encode($yearMap, JSON_UNESCAPED_UNICODE)) ?>" data-min="<?= REG_MIN_YEAR ?>" data-max="<?= REG_MAX_YEAR ?>">
          <ol class="steps" aria-label="Postup">
            <li data-s="1"><b>1</b><span>Rodič</span></li>
            <li data-s="2"><b>2</b><span>Hráč</span></li>
            <li data-s="3"><b>3</b><span>Dokončenie</span></li>
          </ol>

          <?php if ($status === 'fail'): ?><div class="alert alert--bad">Prihlášku sa nepodarilo odoslať. Skúste to prosím znova alebo nám napíšte na <?= e(CONTACT_EMAIL) ?>.</div><?php endif; ?>
          <?php if ($status === 'error'): ?><div class="alert alert--bad">Skontrolujte prosím označené polia.</div><?php endif; ?>

          <input type="hidden" name="t" value="<?= time() ?>">
          <div class="hp" aria-hidden="true"><label>Web <input type="text" name="website" tabindex="-1" autocomplete="off"></label></div>

          <fieldset class="step" data-step="1">
            <legend>Kontaktné údaje rodiča</legend>
            <div class="field"><label for="a-pn">Meno a priezvisko rodiča *</label><input type="text" id="a-pn" name="parent_name" value="<?= e($old['parent_name']) ?>" autocomplete="name" required><?php if (isset($errors['parent_name'])): ?><span class="err"><?= e($errors['parent_name']) ?></span><?php endif; ?></div>
            <div class="row">
              <div class="field"><label for="a-pe">E-mail rodiča *</label><input type="email" id="a-pe" name="parent_email" value="<?= e($old['parent_email']) ?>" autocomplete="email" required><?php if (isset($errors['parent_email'])): ?><span class="err"><?= e($errors['parent_email']) ?></span><?php endif; ?></div>
              <div class="field"><label for="a-pt">Telefónne číslo rodiča *</label><input type="tel" id="a-pt" name="parent_phone" value="<?= e($old['parent_phone']) ?>" autocomplete="tel" required><?php if (isset($errors['parent_phone'])): ?><span class="err"><?= e($errors['parent_phone']) ?></span><?php endif; ?></div>
            </div>
          </fieldset>

          <fieldset class="step" data-step="2">
            <legend>Údaje hráča</legend>
            <div class="row">
              <div class="field"><label for="a-pl">Meno a priezvisko hráča *</label><input type="text" id="a-pl" name="player_name" value="<?= e($old['player_name']) ?>" required><?php if (isset($errors['player_name'])): ?><span class="err"><?= e($errors['player_name']) ?></span><?php endif; ?></div>
              <div class="field"><label for="a-by">Rok narodenia hráča *</label><input type="text" inputmode="numeric" maxlength="4" placeholder="napr. 2016" id="a-by" name="birth_year" value="<?= e($old['birth_year']) ?>" required><?php if (isset($errors['birth_year'])): ?><span class="err"><?= e($errors['birth_year']) ?></span><?php endif; ?></div>
            </div>
            <div class="hint" id="year-hint" hidden></div>
            <div class="field"><label for="a-ex">Predošlé skúsenosti s futbalom</label><textarea id="a-ex" name="experience" rows="4" placeholder="Hral/a už v inom klube? Ako dlho?"><?= e($old['experience']) ?></textarea><?php if (isset($errors['experience'])): ?><span class="err"><?= e($errors['experience']) ?></span><?php endif; ?></div>
          </fieldset>

          <fieldset class="step" data-step="3">
            <legend>Vyjadrenie k CVČ</legend>
            <p class="muted">Náš klub spolupracuje s CVČ Žirafa, preto je možné nás podporiť aj takýmto spôsobom. Za každého zapísaného člena v CVČ sú nám poskytnuté finančné prostriedky na úhradu nákladov našich priestorov, resp. na nákup materiálovo-technického zabezpečenia fungovania klubu. Prosíme, vyjadrite sa preto aj k možnosti prihlásenia Vášho dieťaťa do CVČ, aby sme to vedeli včas spracovať (byrokracia je dosť veľká a je nutné to stihnúť v prvej polovici septembra).</p>

            <div class="field">
              <span class="lbl">Mám záujem prihlásiť svoje dieťa do CVČ Žirafa cez 1. KŠK Žilina *</span>
              <div class="choices">
                <label class="choice"><input type="radio" name="cvc" value="ano" <?= $old['cvc'] === 'ano' ? 'checked' : '' ?>><span>mám záujem</span></label>
                <label class="choice"><input type="radio" name="cvc" value="nie" <?= $old['cvc'] === 'nie' ? 'checked' : '' ?>><span>nie, nemám záujem</span></label>
              </div>
              <?php if (isset($errors['cvc'])): ?><span class="err"><?= e($errors['cvc']) ?></span><?php endif; ?>
            </div>
            <div class="field">
              <span class="lbl">Poskytnem 1. KŠK Žilina vzdelávací poukaz *</span>
              <div class="choices">
                <label class="choice"><input type="radio" name="voucher" value="ano" <?= $old['voucher'] === 'ano' ? 'checked' : '' ?>><span>áno, poskytnem</span></label>
                <label class="choice"><input type="radio" name="voucher" value="nie" <?= $old['voucher'] === 'nie' ? 'checked' : '' ?>><span>nie, neposkytnem</span></label>
              </div>
              <?php if (isset($errors['voucher'])): ?><span class="err"><?= e($errors['voucher']) ?></span><?php endif; ?>
            </div>
            <div class="field">
              <label class="check"><input type="checkbox" name="gdpr" value="1"> <span>Súhlasím so spracovaním osobných údajov uvedených v prihláške na účel posúdenia žiadosti o prihlásenie do klubu (<a href="<?= e(url('gdpr/')) ?>" target="_blank">GDPR</a>). *</span></label>
              <?php if (isset($errors['gdpr'])): ?><span class="err"><?= e($errors['gdpr']) ?></span><?php endif; ?>
            </div>
          </fieldset>

          <div class="apply__nav">
            <button type="button" class="btn btn--outline" data-prev hidden>← Späť</button>
            <button type="button" class="btn btn--green" data-next>Ďalej <?= icon('arrow') ?></button>
            <button type="submit" class="btn btn--yellow" data-submit>Odoslať prihlášku <?= icon('arrow') ?></button>
          </div>
        </form>
      <?php endif; ?>
    </div>

    <aside class="aside">
      <div class="box box--yellow reveal">
        <h3>Termín prihlasovania</h3>
        <p>Prihlasovanie prebieha do <b><?= e(REG_DEADLINE) ?></b>. Následne pristupujeme k žiadostiam individuálne podľa voľných miest v jednotlivých kategóriách (ak ešte nejaké budú).</p>
        <p style="font-size:.88rem">Hlavným kritériom je včasnosť prihlásenia a vek hráča. Vyhradzujeme si právo záujemcu odmietnuť, resp. zaradiť medzi čakateľov.</p>
      </div>

      <div class="box reveal">
        <h3>Kategórie</h3>
        <ul class="cats">
          <?php foreach (REG_CATEGORIES as [$years, $name, $when]): ?>
            <li><b><?= e($name) ?></b><span>nar. <?= e($years) ?></span><small><?= e($when) ?></small></li>
          <?php endforeach; ?>
        </ul>
        <p class="note">Do klubu prijímame hráčov narodených v roku <?= REG_MAX_YEAR ?> a starších, ktorí majú skončený 1. ročník ZŠ (k 31.07.2026).</p>
        <p class="note"><b>Dorast</b> (nar. 31.12.2011 a starší) sa neprihlasuje cez formulár, ale osobným pohovorom s prezidentom (Peter Ondrej) alebo manažérom klubu (Michal Horniak). Napíšte nám na <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a>.</p>
      </div>

      <div class="box reveal">
        <h3>Členské</h3>
        <p><b>60 € na polrok</b> (august – január, február – jún). Splatné celé (120 €) do 30.09.2026 alebo v dvoch častiach (2 × 60 € do 30.09.2026 a do 31.01.2027).</p>
        <p style="font-size:.9rem">Zľava pre súrodencov: 5 € / polrok pre každého. Členské je stanovené ako minimálne, klub môžete podporiť aj vyššou sumou. Iné nastavenie platby dohodnite na <?= e(CONTACT_EMAIL) ?>.</p>
      </div>

      <div class="box box--green reveal">
        <h3>Začiatok tréningov</h3>
        <ul class="facts">
          <li><?= icon('calendar') ?><div><small>Dorast, starší a mladší žiaci (nar. 2015 a skôr)</small><b>od 04.08.2026</b></div></li>
          <li><?= icon('calendar') ?><div><small>Prípravka (nar. 2016 a mladší)</small><b>od 19.08.2026</b></div></li>
        </ul>
        <p style="font-size:.88rem;margin-top:1rem">Tréningy spravidla 2× týždenne, súťažné zápasy od posledného augustového víkendu.</p>
      </div>

      <div class="box reveal">
        <h3>Otázky?</h3>
        <p style="margin-bottom:.4rem"><a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a></p>
        <p>tel. <a href="tel:<?= e(CONTACT_PHONE_RAW) ?>"><?= e(CONTACT_PHONE) ?></a> (Michal Horniak)</p>
      </div>
    </aside>
  </div>
</section>
