<?php
// Prihláška do klubu: spracovanie formulára (uloženie do data/prihlasky.csv + e-mail klubu + potvrdenie rodičovi)

function category_for_year(int $year): ?string
{
    foreach (REG_CATEGORIES as [, $name, , $years]) {
        if (in_array($year, $years, true)) return $name;
    }
    return null;
}

function csv_safe(string $s): string
{
    $s = str_replace(["\r", "\n"], ' ', trim($s));
    return preg_match('/^[=+\-@\t]/', $s) ? "'" . $s : $s;   // ochrana pred vzorcami v Exceli
}

function header_safe(string $s): string
{
    return trim(str_replace(["\r", "\n", ','], ' ', $s));
}

$fields = ['parent_name', 'parent_email', 'parent_phone', 'player_name', 'birth_year', 'experience', 'cvc', 'voucher'];
$old = array_fill_keys($fields, '');
$errors = [];
$status = isset($_GET['odoslane']) ? 'ok' : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $k) $old[$k] = trim((string)($_POST[$k] ?? ''));

    $tooFast = (time() - (int)($_POST['t'] ?? 0)) < 6;      // roboty odošlú formulár okamžite
    if (!empty($_POST['website']) || $tooFast) {
        header('Location: ' . url('prihlaska/?odoslane=1'), true, 303);   // tvárime sa, že prešlo
        exit;
    }

    if (mb_strlen($old['parent_name']) < 3) $errors['parent_name'] = 'Zadajte meno a priezvisko rodiča.';
    if (!filter_var($old['parent_email'], FILTER_VALIDATE_EMAIL)) $errors['parent_email'] = 'Zadajte platný e-mail.';
    if (!preg_match('/^\+?[0-9 ()\/-]{9,20}$/', $old['parent_phone'])) $errors['parent_phone'] = 'Zadajte platné telefónne číslo.';
    if (mb_strlen($old['player_name']) < 3) $errors['player_name'] = 'Zadajte meno a priezvisko hráča.';

    $year = (int)$old['birth_year'];
    if (!preg_match('/^\d{4}$/', $old['birth_year'])) {
        $errors['birth_year'] = 'Zadajte rok narodenia v tvare RRRR (napr. 2016).';
    } elseif ($year < REG_MIN_YEAR) {
        $errors['birth_year'] = 'Hráči narodení v roku 2011 a starší sa neprihlasujú cez formulár, ale osobným pohovorom (dorast). Napíšte nám na ' . CONTACT_EMAIL . '.';
    } elseif ($year > REG_MAX_YEAR) {
        $errors['birth_year'] = 'Do klubu na túto sezónu prijímame len hráčov narodených v roku ' . REG_MAX_YEAR . ' a starších.';
    }

    if (mb_strlen($old['experience']) > 1500) $errors['experience'] = 'Text je príliš dlhý (max. 1 500 znakov).';
    if (!in_array($old['cvc'], ['ano', 'nie'], true)) $errors['cvc'] = 'Vyberte jednu možnosť.';
    if (!in_array($old['voucher'], ['ano', 'nie'], true)) $errors['voucher'] = 'Vyberte jednu možnosť.';
    if (empty($_POST['gdpr'])) $errors['gdpr'] = 'Potvrďte súhlas so spracovaním osobných údajov.';

    if (!$errors) {
        $category = category_for_year($year) ?? '';
        $cvcTxt = $old['cvc'] === 'ano' ? 'mám záujem' : 'nie, nemám záujem';
        $vouchTxt = $old['voucher'] === 'ano' ? 'áno poskytnem' : 'nie neposkytnem';

        // 1) uloženie (záloha, ak by e-mail nedošiel)
        $saved = false;
        $dir = __DIR__ . '/../data';
        if (is_dir($dir) || @mkdir($dir, 0755, true)) {
            $file = $dir . '/prihlasky.csv';
            $new = !is_file($file);
            if ($h = @fopen($file, 'ab')) {
                if (flock($h, LOCK_EX)) {
                    if ($new) fputcsv($h, ['cas', 'sezona', 'rodic', 'email', 'telefon', 'hrac', 'rok_narodenia', 'kategoria', 'skusenosti', 'cvc_zirafa', 'vzdelavaci_poukaz'], ';');
                    fputcsv($h, [date('Y-m-d H:i:s'), REG_SEASON, csv_safe($old['parent_name']), csv_safe($old['parent_email']), csv_safe($old['parent_phone']), csv_safe($old['player_name']), $year, $category, csv_safe($old['experience']), $cvcTxt, $vouchTxt], ';');
                    flock($h, LOCK_UN);
                    $saved = true;
                }
                fclose($h);
            }
        }

        // 2) e-mail klubu
        $host = preg_replace('~^www\.~', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
        $from = 'From: ' . SITE_NAME . ' <noreply@' . $host . '>';
        $subject = '=?UTF-8?B?' . base64_encode('Nová prihláška ' . REG_SEASON . ' – ' . header_safe($old['player_name'])) . '?=';
        $body = "Nová prihláška do klubu – sezóna " . REG_SEASON . "\n\n"
              . "RODIČ\nMeno: {$old['parent_name']}\nE-mail: {$old['parent_email']}\nTelefón: {$old['parent_phone']}\n\n"
              . "HRÁČ\nMeno: {$old['player_name']}\nRok narodenia: {$year}\nKategória: {$category}\nSkúsenosti s futbalom: " . ($old['experience'] ?: '–') . "\n\n"
              . "CVČ Žirafa: {$cvcTxt}\nVzdelávací poukaz: {$vouchTxt}\n\nOdoslané: " . date('d.m.Y H:i') . "\n";
        $mailed = @mail(CONTACT_EMAIL, $subject, $body, implode("\r\n", [$from, 'Reply-To: ' . header_safe($old['parent_email']), 'Content-Type: text/plain; charset=UTF-8']));

        if ($mailed || $saved) {
            // 3) potvrdenie rodičovi
            $conf = "Dobrý deň,\n\nďakujeme za vyplnenie prihlášky. Vaša prihláška (" . $old['player_name'] . ", ročník " . $year . ") bola zaregistrovaná.\n"
                  . "O prihlásení Vášho dieťaťa Vás budeme informovať aj s prípadnými podrobnými informáciami pre ďalšiu sezónu.\n\n"
                  . "S pozdravom\ntréneri a vedenie klubu\n" . SITE_NAME . "\n" . CONTACT_EMAIL . ", " . CONTACT_PHONE . "\n";
            $confOk = @mail($old['parent_email'], '=?UTF-8?B?' . base64_encode('Prihláška do klubu ' . SITE_NAME . ' – potvrdenie') . '?=', $conf,
                implode("\r\n", [$from, 'Reply-To: ' . CONTACT_EMAIL, 'Content-Type: text/plain; charset=UTF-8']));

            header('Location: ' . url('prihlaska/?odoslane=1' . ($confOk ? '&mail=1' : '')), true, 303);
            exit;
        }
        $status = 'fail';
    } else {
        $status = 'error';
    }
}

render('apply', [
    'status' => $status, 'errors' => $errors, 'old' => $old,
    'meta' => ['title' => 'Prihláška do klubu – sezóna ' . REG_SEASON, 'description' => 'Online prihláška do 1. KŠK Žilina na sezónu ' . REG_SEASON . '. Kategórie, členské a termíny tréningov.'],
]);
