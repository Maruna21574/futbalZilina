<?php
// Pomocné funkcie

function e(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function base_url(): string
{
    static $base = null;
    if ($base === null) {
        $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
        $base = rtrim($dir, '/') . '/';
    }
    return $base;
}

function url(string $path = ''): string
{
    return base_url() . ltrim($path, '/');
}

function abs_url(string $path = ''): string
{
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
    return ($https ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . url($path);
}

function asset(string $path): string
{
    $file = __DIR__ . '/../assets/' . $path;
    $v = is_file($file) ? filemtime($file) : 1;
    return url('assets/' . $path) . '?v=' . $v;
}

function thumb_rel(string $rel): string
{
    return preg_replace('/\.(png|gif)$/i', '.jpg', $rel);
}

function upload(string $rel, bool $thumb = false): string
{
    if ($thumb) {
        $rel = 'thumbs/' . thumb_rel($rel);
    }
    return url('assets/uploads/' . $rel);
}

// Prepíše relatívne odkazy v migrovanom obsahu na správny základ (kvôli podadresáru)
function content_html(string $html): string
{
    $b = base_url();
    $html = str_replace(['src="assets/', 'href="assets/'], ['src="' . $b . 'assets/', 'href="' . $b . 'assets/'], $html);
    $html = preg_replace('~href="/(?!/)~', 'href="' . $b, $html);
    $html = preg_replace('~<a href="(https?://[^"]+)"~', '<a href="$1" target="_blank" rel="noopener"', $html);
    return $html;
}

function load_json(string $name): array
{
    static $cache = [];
    if (!isset($cache[$name])) {
        $f = __DIR__ . '/../content/' . $name . '.json';
        $cache[$name] = is_file($f) ? (json_decode(file_get_contents($f), true) ?: []) : [];
    }
    return $cache[$name];
}

function all_posts(): array { return load_json('posts'); }
function page_content(string $slug): array { return load_json('pages')[$slug] ?? ['title' => '', 'html' => '']; }

function find_post(string $slug): ?array
{
    foreach (all_posts() as $p) {
        if ($p['slug'] === $slug) return $p;
    }
    return null;
}

function posts_by_category(?string $cat): array
{
    $posts = all_posts();
    return $cat ? array_values(array_filter($posts, fn($p) => $p['category'] === $cat)) : $posts;
}

function category_label(string $slug): string
{
    $map = ['pripravka' => 'Prípravka', 'mladsi-ziaci' => 'Mladší žiaci', 'ziaci' => 'Starší žiaci', 'dorast' => 'Dorast', 'klub' => 'Klub', 'nezaradene' => 'Klub', 'turnaj' => 'Turnaj'];
    return $map[$slug] ?? ucfirst($slug);
}

function sk_date(string $d): string
{
    $t = strtotime($d);
    $m = ['', 'januára', 'februára', 'marca', 'apríla', 'mája', 'júna', 'júla', 'augusta', 'septembra', 'októbra', 'novembra', 'decembra'];
    return date('j', $t) . '. ' . $m[(int)date('n', $t)] . ' ' . date('Y', $t);
}

// Obrázky z HTML obsahu (plné veľkosti), napr. na galérie tímov
function content_images(string $html): array
{
    preg_match_all('~<img src="assets/uploads/(?!thumbs/)([^"]+)"~', $html, $m);
    preg_match_all('~<a href="assets/uploads/([^"]+)" data-lightbox~', $html, $m2);
    return array_values(array_unique(array_merge($m[1], $m2[1])));
}

function strip_images(string $html): string
{
    $html = preg_replace('~<div class="gallery">.*?</div>~s', '', $html);
    $html = preg_replace('~<a [^>]*data-lightbox[^>]*>\s*<img[^>]*>\s*</a>~', '', $html);
    $html = preg_replace('~<img[^>]*>~', '', $html);
    return preg_replace('~<p>\s*</p>~', '', $html);
}

function icon(string $name): string
{
    static $icons = [
        'phone' => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/>',
        'mail' => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
        'pin' => '<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'arrow' => '<path d="M5 12h14M13 5l7 7-7 7"/>',
        'calendar' => '<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>',
        'clock' => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
        'users' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8"/>',
        'heart' => '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8l1 1.1L12 21l7.8-7.5 1-1.1a5.5 5.5 0 0 0 0-7.8z"/>',
        'ball' => '<circle cx="12" cy="12" r="10"/><path d="m12 7 4.5 3.3-1.7 5.2H9.2L7.5 10.3zM12 7V2M16.5 10.3l5-1.6M14.8 15.5l3.4 4M9.2 15.5l-3.4 4M7.5 10.3l-5-1.6"/>',
        'download' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/>',
        'check' => '<path d="M20 6 9 17l-5-5"/>',
        'menu' => '<path d="M3 6h18M3 12h18M3 18h18"/>',
        'close' => '<path d="M18 6 6 18M6 6l12 12"/>',
        'chev' => '<path d="m6 9 6 6 6-6"/>',
        'star' => '<path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.8-6.2-3.3-6.2 3.3L7 14.2 2 9.3l6.9-1z"/>',
        'music' => '<path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>',
        'cross' => '<path d="M12 2v20M6 8h12"/>',
        'camera' => '<path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/>',
    ];
    return '<svg class="i i-' . $name . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($icons[$name] ?? '') . '</svg>';
}

function render(string $template, array $vars = []): void
{
    extract($vars);
    $meta = $meta ?? [];
    require_once __DIR__ . '/../templates/_parts.php';
    ob_start();
    require __DIR__ . '/../templates/' . $template . '.php';
    $content = ob_get_clean();
    require __DIR__ . '/../templates/_layout.php';
}

function not_found(): void
{
    http_response_code(404);
    render('404', ['meta' => ['title' => 'Stránka sa nenašla', 'robots' => 'noindex']]);
    exit;
}
