<?php
/** @var string $content */
/** @var array $meta */
$title = ($meta['title'] ?? SITE_NAME);
$fullTitle = !empty($meta['home']) ? $title : $title . ' | ' . SITE_NAME;
$desc = $meta['description'] ?? SITE_TAGLINE;
$ogImage = abs_url($meta['image'] ?? 'assets/img/og.jpg');
$current = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');
$current = trim(substr('/' . $current, strlen(rtrim(base_url(), '/'))), '/');

function nav_active(string $href, string $current): bool
{
    $h = trim($href, '/');
    return $h === '' ? $current === '' : ($current === $h || str_starts_with($current, $h . '/'));
}
?><!doctype html>
<html lang="sk" class="no-js">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($fullTitle) ?></title>
<meta name="description" content="<?= e($desc) ?>">
<meta name="theme-color" content="#0b6b36">
<?php if (!empty($meta['robots'])): ?><meta name="robots" content="<?= e($meta['robots']) ?>"><?php endif; ?>
<link rel="canonical" href="<?= e(abs_url($current === '' ? '' : $current . '/')) ?>">
<meta property="og:site_name" content="<?= e(SITE_NAME) ?>">
<meta property="og:type" content="<?= e($meta['type'] ?? 'website') ?>">
<meta property="og:title" content="<?= e($fullTitle) ?>">
<meta property="og:description" content="<?= e($desc) ?>">
<meta property="og:image" content="<?= e($ogImage) ?>">
<meta property="og:locale" content="sk_SK">
<link rel="icon" type="image/png" href="<?= e(asset('img/favicon.png')) ?>">
<link rel="apple-touch-icon" href="<?= e(asset('img/apple-touch-icon.png')) ?>">
<link rel="preload" href="<?= e(url('assets/fonts/Inter-latin.woff2')) ?>" as="font" type="font/woff2" crossorigin>
<link rel="stylesheet" href="<?= e(asset('css/main.css')) ?>">
</head>
<body class="<?= !empty($meta['home']) ? 'is-home' : '' ?>">
<a class="skip" href="#main">Preskočiť na obsah</a>

<div class="topbar">
  <div class="container topbar__in">
    <span class="topbar__claim"><?= icon('ball') ?> <?= e(SITE_TAGLINE) ?></span>
    <div class="topbar__links">
      <a href="tel:<?= e(CONTACT_PHONE_RAW) ?>"><?= icon('phone') ?> <?= e(CONTACT_PHONE) ?></a>
      <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= icon('mail') ?> <?= e(CONTACT_EMAIL) ?></a>
    </div>
  </div>
</div>

<header class="header" id="header">
  <div class="container header__in">
    <a class="brand" href="<?= e(url()) ?>" aria-label="<?= e(SITE_NAME) ?> – úvod">
      <img src="<?= e(asset('img/logo.png')) ?>" alt="" width="46" height="64">
      <span class="brand__text"><b>1. KŠK</b><span>Žilina</span></span>
    </a>
    <button class="burger" id="burger" aria-label="Menu" aria-expanded="false" aria-controls="nav"><?= icon('menu') ?></button>
    <nav class="nav" id="nav" aria-label="Hlavné menu">
      <ul>
        <li class="nav__home"><a href="<?= e(url()) ?>">Domov</a></li>
        <?php foreach (NAV as $item):
          [$label, $href] = $item;
          $sub = $item[2] ?? null;
          if ($sub === 'results') {
              $sub = array_map(fn($r) => [$r[1], $r[2]], RESULTS);
          }
          $isExt = fn($h) => (bool)preg_match('~^https?://~', (string)$h);
          $active = nav_active($href, $current) && $href[0] !== '#';
          if (!$active && is_array($sub)) foreach ($sub as $s) { if (!empty($s[1]) && !$isExt($s[1]) && nav_active($s[1], $current)) { $active = true; break; } }
          $href = $href[0] === '#' ? url() . $href : url($href);
        ?>
          <li class="<?= $sub ? 'has-sub' : '' ?> <?= $active ? 'is-active' : '' ?>">
            <a href="<?= e($href) ?>"><?= e($label) ?><?= $sub ? icon('chev') : '' ?></a>
            <?php if ($sub): ?>
              <ul class="sub">
                <?php foreach ($sub as $s): ?>
                  <?php if (($s[2] ?? '') === 'heading'): ?><li class="sub__head"><?= e($s[0]) ?></li>
                  <?php elseif ($isExt($s[1])): ?><li><a href="<?= e($s[1]) ?>" target="_blank" rel="noopener"><?= e($s[0]) ?> <?= icon('ext') ?></a></li>
                  <?php else: ?><li><a href="<?= e(url($s[1])) ?>"><?= e($s[0]) ?></a></li><?php endif; ?>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
      <a class="btn btn--yellow nav__cta" href="<?= e(url('prihlaska/')) ?>">Prihlásiť sa</a>
    </nav>
  </div>
</header>

<main id="main">
<?= $content ?>
</main>

<footer class="footer">
  <div class="footer__top">
    <div class="container footer__grid">
      <div class="footer__brand">
        <img src="<?= e(asset('img/logo.png')) ?>" alt="1. KŠK Žilina" width="90" height="126" loading="lazy">
        <p><?= e(SITE_TAGLINE) ?>. Saleziánsky mládežnícky futbalový klub v Žiline.</p>
      </div>
      <div>
        <h3>Klub</h3>
        <ul>
          <li><a href="<?= e(url('o-nas/')) ?>">O nás</a></li>
          <li><a href="<?= e(url('treneri/')) ?>">Profil trénera</a></li>
          <li><a href="<?= e(url('aktuality/')) ?>">Aktuality</a></li>
          <li><a href="<?= e(url('projekt/')) ?>">Projekt MŠVVaŠ SR</a></li>
          <li><a href="<?= e(url('gdpr/')) ?>">GDPR</a></li>
        </ul>
      </div>
      <div>
        <h3>Tímy</h3>
        <ul>
          <?php foreach (TEAMS as $slug => $t): ?><li><a href="<?= e(url($slug . '/')) ?>"><?= e($t['name']) ?> <small><?= e($t['tag']) ?></small></a></li><?php endforeach; ?>
        </ul>
      </div>
      <div>
        <h3>Kontakt</h3>
        <ul class="footer__contact">
          <li><?= icon('pin') ?> <?= e(implode(', ', CONTACT_ADDRESS)) ?></li>
          <li><?= icon('phone') ?> <a href="tel:<?= e(CONTACT_PHONE_RAW) ?>"><?= e(CONTACT_PHONE) ?></a></li>
          <li><?= icon('mail') ?> <a href="mailto:<?= e(CONTACT_EMAIL) ?>"><?= e(CONTACT_EMAIL) ?></a></li>
        </ul>
        <a class="btn btn--yellow btn--sm" href="<?= e(url('2-percenta/')) ?>">Podporte nás 2 %</a>
      </div>
    </div>
  </div>
  <div class="footer__bottom">
    <div class="container footer__bottom-in">
      <span>© <?= date('Y') ?> 1. KŠK Žilina · IČO <?= e(ORG_ICO) ?></span>
      <span>Rajecká 17, 010 01 Žilina</span>
    </div>
  </div>
</footer>

<script src="<?= e(asset('js/main.js')) ?>" defer></script>
</body>
</html>
