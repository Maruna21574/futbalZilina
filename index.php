<?php
// Front controller – všetky požiadavky idú cez tento súbor (pozri .htaccess)

// Vstavaný PHP server (php -S): statické súbory necháme na ňom
if (PHP_SAPI === 'cli-server') {
    $f = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file($f)) return false;
}

mb_internal_encoding('UTF-8');
date_default_timezone_set('Europe/Bratislava');
require __DIR__ . '/inc/config.php';
require __DIR__ . '/inc/helpers.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
$base = base_url();
if (str_starts_with($path, $base)) $path = substr($path, strlen($base));
$path = trim(rawurldecode($path), '/');
if ($path === 'index.php') $path = '';

function redirect(string $to, int $code = 301): never
{
    header('Location: ' . url($to), true, $code);
    exit;
}

// Stránky a ich šablóny
if ($path === '') {
    render('home', ['meta' => ['title' => SITE_NAME . ' – ' . SITE_TAGLINE, 'description' => 'Mládežnícky futbalový klub v Žiline. Prípravka, mladší a starší žiaci, dorast. Saleziánska výchova, futbal a komunita.', 'home' => true]]);
    exit;
}

if (isset(REDIRECTS[$path])) redirect(REDIRECTS[$path]);

if (preg_match('~^page/(\d+)$~', $path, $m)) redirect('aktuality/' . ($m[1] > 1 ? '?strana=' . $m[1] : ''));

if ($path === 'sitemap.xml') {
    header('Content-Type: application/xml; charset=utf-8');
    $urls = ['', 'o-nas/', 'treneri/', 'timy/', 'pripravka/', 'mladsi-ziaci/', 'ziaci/', 'dorast/', 'aktuality/', 'projekt/', 'plany/', 'udialo-sa/', 'podporim/', '2-percenta/', 'prihlaska/', 'kontakt/', 'gdpr/'];
    foreach (all_posts() as $p) $urls[] = $p['slug'] . '/';
    echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    foreach ($urls as $u) echo '<url><loc>' . e(abs_url($u)) . '</loc></url>';
    echo '</urlset>';
    exit;
}

if ($path === 'robots.txt') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-agent: *\nAllow: /\nSitemap: " . abs_url('sitemap.xml') . "\n";
    exit;
}

$simple = [
    'o-nas' => ['about', 'O nás', 'Futbalový klub 1. KŠK Žilina s saleziánskou mládežníckou spiritualitou.'],
    'treneri' => ['coaches', 'Profil trénera', 'Trénerom v našom klube je správny chlap z kostola s vášňou pre futbal.'],
    'timy' => ['teams', 'Naše tímy', 'Prípravka, mladší žiaci, starší žiaci a dorast – všetky vekové kategórie 1. KŠK Žilina.'],
    'projekt' => ['project', 'Projekt MŠVVaŠ SR', 'Projekt na podporu žiakov – šport, hudba, projektové a podnikateľské zručnosti.'],
    'plany' => ['plans', 'Plánované aktivity', 'Pravidelné týždenné aktivity klubu – tréningy, zápasy, hudba a duchovné programy.'],
    'udialo-sa' => ['trainings', 'Udialo sa', 'Čo sa na tréningoch a aktivitách klubu robí – tréningové plány a lektori.'],
    'podporim' => ['support', 'Podporím klub', 'Stratégia podpory, úspechy a finančné potreby 1. KŠK Žilina.'],
    '2-percenta' => ['tax', '2 % z dane', 'Poukáž 1 %, 2 % alebo 3 % z dane 1. KŠK Žilina. Stiahni si tlačivo.'],
    'gdpr' => ['gdpr', 'GDPR – ochrana osobných údajov', 'Informácie o spracúvaní a ochrane osobných údajov.'],
    'zastavsanasajmajmesiac' => ['adventure', 'Zastav sa na jeden mesiac', 'Dobrodružná cesta – video a úlohy.'],
];

if (isset($simple[$path])) {
    [$tpl, $title, $desc] = $simple[$path];
    render($tpl, ['meta' => ['title' => $title, 'description' => $desc]]);
    exit;
}

if (isset(TEAMS[$path])) {
    $team = TEAMS[$path];
    render('team', ['slug' => $path, 'team' => $team, 'meta' => ['title' => $team['name'] . ' (' . $team['tag'] . ')', 'description' => $team['blurb'], 'image' => 'assets/uploads/' . $team['hero']]]);
    exit;
}

if ($path === 'prihlaska') {
    require __DIR__ . '/inc/apply.php';
    exit;
}

if ($path === 'kontakt') {
    require __DIR__ . '/inc/contact.php';
    exit;
}

if ($path === 'aktuality' || preg_match('~^category/([a-z0-9-]+)$~', $path, $m)) {
    $cat = $m[1] ?? null;
    $list = posts_by_category($cat);
    if ($cat && !$list) not_found();
    $pages = max(1, (int)ceil(count($list) / POSTS_PER_PAGE));
    $page = min($pages, max(1, (int)($_GET['strana'] ?? 1)));
    render('news', [
        'cat' => $cat, 'page' => $page, 'pages' => $pages, 'total' => count($list),
        'items' => array_slice($list, ($page - 1) * POSTS_PER_PAGE, POSTS_PER_PAGE),
        'meta' => ['title' => $cat ? 'Aktuality – ' . category_label($cat) : 'Aktuality', 'description' => 'Články, správy zo zápasov, turnajov a života klubu 1. KŠK Žilina.'],
    ]);
    exit;
}

// Článok (zachované pôvodné URL: /slug/)
if (preg_match('~^[a-z0-9-]+$~', $path) && ($post = find_post($path))) {
    render('post', ['post' => $post, 'meta' => ['title' => $post['title'], 'description' => $post['excerpt'], 'image' => $post['image'] ? 'assets/uploads/thumbs/' . thumb_rel($post['image']) : null, 'type' => 'article']]);
    exit;
}

not_found();
