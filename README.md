# 1. KŠK Žilina – web (PHP + SCSS + JS)

Bez databázy, bez frameworku – beží na bežnom PHP hostingu (Websupport, PHP 8.0+, Apache + mod_rewrite).

## Štruktúra
- `index.php` – router (všetky URL), `.htaccess` – prepisovanie URL, cache, zákaz interných priečinkov
- `inc/config.php` – **texty, kontakty, tímy, menu, partneri, odkaz na prihlasovací formulár, presmerovania zo starých URL**
- `templates/` – šablóny stránok, `assets/scss` → `assets/css/main.css`, `assets/js/main.js`
- `content/*.json` – migrovaný obsah (118 článkov, stránky, rozvrh, tréningy)
- `assets/uploads` – fotky (full max 1600 px + `thumbs/`), `assets/files` – PDF tlačivá
- `tools/` – migračné skripty (NENAHRÁVAŤ na hosting)

## Lokálne spustenie
`php -S 127.0.0.1:8080 index.php` (alebo Laragon)

## Úprava štýlov
`npm i -g sass`, potom `sass assets/scss/main.scss assets/css/main.css --style=compressed --no-source-map` (alebo `--watch`).

## Nasadenie na Websupport
Nahrať cez FTP obsah priečinka okrem `tools/` a `README.md` do koreňa domény. Po zapnutí SSL odkomentovať presmerovanie na https v `.htaccess`.
Kontaktný formulár používa `mail()` – príjemca je `CONTACT_EMAIL` v `inc/config.php`.

## Pridanie článku
V `content/posts.json` pridať objekt na začiatok poľa (`slug`, `title`, `date`, `category`, `image`, `excerpt`, `html`).
