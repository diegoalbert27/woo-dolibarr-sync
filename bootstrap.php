<?php

require_once dirname(__FILE__) .'/src/dolibarr.php';
require_once dirname(__FILE__) .'/src/woocommerce.php';

$doli_api = new doli_api('https://qukymodainfantil.woow.cat', 'yosiet', 'b1L2p2kVvFUhpbAsI1yWbG1135T70RsU');
$wc_api = new wc_api('https://qukymodainfantil.com/', 'ck_57a96d291f50917675180fb0604a7c09a46028cf', 'cs_8a683ca3b92c07e63f31a2dfff949c4b0e5cdd9f');
