<?php

require_once dirname(__FILE__) .'/src/dolibarr.php';
require_once dirname(__FILE__) .'/src/woocommerce.php';
require_once dirname(__FILE__) .'/src/wordpress.php';

$doli_api = new doli_api('https://dolibarr.test', 'webservices', 'gPfxsP3IE5Zo');
$wc_api = new wc_api('https://wp-site.com/index.php/', 'ck_6ed6964e61d963a2f6e9c8caad7ea7a8b9ace979', 'cs_e2ee3264e1e6315030b2101716f72dbb736a2565');
$wp_api = new wp_api('https://wp-site.com/index.php/', 'daxciber', 'B6jW jhm7 QjJH koTC NFA5 avI9');
