<?php
$page = page_content('gdpr');
$html = preg_replace('~^\s*<h1>.*?</h1>~s', '', $page['html']);
page_head('GDPR', 'Informácie o spracúvaní a ochrane osobných údajov.');
?>
<section class="page-body">
  <div class="container">
    <div class="prose"><?= content_html($html) ?></div>
  </div>
</section>
