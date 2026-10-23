<?php
$page = page_content('zastavsanasajmajmesiac');
page_head('Zastav sa na jeden mesiac', 'Dobrodružná cesta – video a úlohy.');
?>
<section class="page-body">
  <div class="container"><div class="prose"><?= content_html($page['html']) ?></div></div>
</section>
