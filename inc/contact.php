<?php
// Kontaktná stránka + spracovanie formulára (odoslanie cez mail())

$status = null;
$old = ['name' => '', 'email' => '', 'phone' => '', 'message' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($old as $k => $_) $old[$k] = trim((string)($_POST[$k] ?? ''));

    $tooFast = (time() - (int)($_POST['t'] ?? 0)) < 3;   // roboty vypĺňajú okamžite
    if (!empty($_POST['website']) || $tooFast) {
        $status = 'ok';                                     // honeypot – tváriť sa, že prešlo
    } else {
        if (mb_strlen($old['name']) < 2) $errors['name'] = 'Zadajte meno.';
        if (!filter_var($old['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Zadajte platný e-mail.';
        if (mb_strlen($old['message']) < 5) $errors['message'] = 'Napíšte správu.';
        if (empty($_POST['gdpr'])) $errors['gdpr'] = 'Potvrďte súhlas so spracovaním údajov.';

        if (!$errors) {
            $clean = fn(string $s) => str_replace(["\r", "\n"], ' ', $s);
            $subject = '=?UTF-8?B?' . base64_encode('Správa z webu – ' . $clean($old['name'])) . '?=';
            $body = "Meno: {$old['name']}\nE-mail: {$old['email']}\nTelefón: {$old['phone']}\n\n{$old['message']}\n";
            $host = preg_replace('~^www\.~', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
            $headers = [
                'From: ' . SITE_NAME . ' <noreply@' . $host . '>',
                'Reply-To: ' . $clean($old['email']),
                'Content-Type: text/plain; charset=UTF-8',
            ];
            $status = @mail(CONTACT_EMAIL, $subject, $body, implode("\r\n", $headers)) ? 'ok' : 'fail';
            if ($status === 'ok') $old = array_fill_keys(array_keys($old), '');
        } else {
            $status = 'error';
        }
    }
}

render('contact', [
    'status' => $status, 'errors' => $errors, 'old' => $old,
    'meta' => ['title' => 'Kontakt', 'description' => 'Kontakt na 1. KŠK Žilina – Rajecká 17, Žilina. Telefón, e-mail, fakturačné údaje.'],
]);
