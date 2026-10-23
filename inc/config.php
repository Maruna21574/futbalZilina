<?php
// Základná konfigurácia webu

const SITE_NAME   = '1. KŠK Žilina';
const SITE_TAGLINE = 'Bojujeme o dušu na ihrisku i v živote';
const CONTACT_EMAIL = '1kskzilina@saleziani.sk';
const CONTACT_PHONE = '+421 908 153 703';
const CONTACT_PHONE_RAW = '+421908153703';
const CONTACT_ADDRESS = ['Rajecká 17', '010 01 Žilina', 'Slovensko'];
const ORG_ICO  = '42221307';
const ORG_DIC  = '2023638463';
const ORG_IBAN = 'SK98 8330 0000 0025 0144 4805';

// Prihlasovanie na sezónu (banner na úvodnej stránke)
const REG_SEASON = '2026/2027';
const REG_DEADLINE = '30.06.2026';
const REG_FORM_URL = 'https://forms.gle/LNrfVzafCem1FCaw7';

const POSTS_PER_PAGE = 12;

// Tímy (kľúč = URL slug aj slug kategórie článkov)
const TEAMS = [
    'pripravka' => [
        'name' => 'Prípravka', 'tag' => 'U11', 'ages' => '1. – 4. ročník ZŠ',
        'league' => 'Turnaje OFZ Žilina', 'hero' => '2025/02/pripravka.jpg',
        'blurb' => 'Najmladšie deti, ktoré sa učia radosť z hry, vedenie lopty a prvé prihrávky.',
    ],
    'mladsi-ziaci' => [
        'name' => 'Mladší žiaci', 'tag' => 'U13', 'ages' => '5. – 7. ročník ZŠ',
        'league' => 'I. MIRAGE trieda mladší žiaci', 'hero' => '2017/02/DSC_5048_1.jpg',
        'blurb' => 'Ligová súťaž, turnaje a letné sústredenie ako súčasť celoročného programu.',
    ],
    'ziaci' => [
        'name' => 'Starší žiaci', 'tag' => 'U15', 'ages' => '13 – 15 rokov',
        'league' => 'I. MIRAGE trieda OBFZA Žilina', 'hero' => '2019/10/rocniky.na_.fotky1_.jpg',
        'blurb' => 'Výkon na ihrisku a formovanie osobnosti, hodnôt a charakteru.',
    ],
    'dorast' => [
        'name' => 'Dorast', 'tag' => 'U19', 'ages' => 'Mladí muži',
        'league' => 'I.A2B OBFZA Žilina', 'hero' => '2020/10/dorast.20.21.zeke_.jpg',
        'blurb' => 'Mladí muži v súťaži OBFZA Žilina. Prihlášky prijímame celoročne.',
    ],
];

const PARTNERS = [
    ['Mesto Žilina', 'https://www.zilina.sk', '2017/02/financna_podpora_Mesta_Zilina.jpg'],
    ['Žilinský samosprávny kraj', 'https://www.zilinskazupa.sk', '2025/11/zsk_logo_zsk_color.png'],
    ['Toyota Žilina', 'https://www.toyota-zilina.sk', '2017/02/logo-at-toyota1.png'],
    ['Kardan servis', 'https://www.kardanservis.sk', '2018/08/kardan.png'],
    ['Kinekus', 'https://www.kinekus.sk', '2025/10/logo_kinekus_vseobecne_blue.png'],
    ['100 rokov Saleziánov', 'https://100.saleziani.sk', '2023/01/logo.100rocnica.sdb_.png'],
];

const NAV = [
    ['Domov', ''],
    ['O nás', 'o-nas/', [['Info o klube', 'o-nas/'], ['Profil trénera', 'treneri/']]],
    ['Tímy', 'timy/', [['Prípravka (U11)', 'pripravka/'], ['Mladší žiaci (U13)', 'mladsi-ziaci/'], ['Starší žiaci (U15)', 'ziaci/'], ['Dorast (U19)', 'dorast/']]],
    ['Aktuality', 'aktuality/'],
    ['Projekt MŠVVaŠ SR', 'projekt/', [['Základné info', 'projekt/'], ['Plánované aktivity', 'plany/'], ['Udialo sa', 'udialo-sa/']]],
    ['Podporte nás', 'podporim/', [['Podporím klub', 'podporim/'], ['2 % z dane', '2-percenta/']]],
    ['Kontakt', 'kontakt/'],
];

// Staré URL adresy (WordPress) -> nové
const REDIRECTS = [
    'skuska2' => 'plany/',
    'ukazka-strany' => 'udialo-sa/',
    'podporte-nas-2' => 'podporim/',
    'podporte-nas' => '2-percenta/',
    'donor-dashboard' => 'podporim/',
    'donation-history' => 'podporim/',
    'donation-failed' => 'podporim/',
    'donation-confirmation' => 'podporim/',
];
