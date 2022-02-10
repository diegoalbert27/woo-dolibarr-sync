<?php

require_once dirname(__FILE__) .'/src/dolibarr.php';
require_once dirname(__FILE__) .'/src/woocommerce.php';

// $doli_api = new doli_api('https://qukymodainfantil.woow.cat', 'yosiet', 'b1L2p2kVvFUhpbAsI1yWbG1135T70RsU');
// $wc_api = new wc_api('http://wordpress.test', 'ck_914f5b830a89ec006bcc1346eaf9f82cb5a5246f', 'cs_e1fc7001ddb2f28dd0272f88b125a778a6a05db1');

$doli_api = new doli_api('https://dolibarr.test', 'webservices', 'gPfxsP3IE5Zo');
$wc_api = new wc_api('https://wp-site.com/index.php', 'ck_6ed6964e61d963a2f6e9c8caad7ea7a8b9ace979', 'cs_e2ee3264e1e6315030b2101716f72dbb736a2565');

$products = $doli_api->getProducts();
$products_data = [];
foreach ($products as $product) {
	$products_data[] = [
        'sku'=>$product->ref,
        'name'=>$product->label,
        'description'=>$product->description,
        'type'=>'simple',
        'regular_price'=>$product->price,
        'weight'=>$product->weight,
        'length'=>$product->length,
        'width'=>$product->width,
        'height'=>$product->height
	];
}

$wc_api->importProducts( $products_data );

echo json_encode($wc_api->getImportStats());