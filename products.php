<?php

require_once dirname(__FILE__) .'/bootstrap.php';

$products = $doli_api->getProducts();
$products_data = [];
foreach ($products as $product) {

        $docProducts = $doli_api->getDocumentImageProduct([
        'modulepart' => 'product',
        'id' => $product->ref
        ]);

        $name_doc = $docProducts['filename'];
        $path_doc = dirname(__FILE__) . '/resources/product/' . $docProducts['level1name'];
        $content_doc = base64_decode($docProducts['content']);

        $doli_api::download_doc($path_doc, [
                'title' => $name_doc,
                'content' => $content_doc
        ]);

        // $url = "http://" . $_SERVER['SERVER_NAME'] . '/woo-dolibarr-sync/resources/product/' . $docProducts['level1name'] . '/' . $name_doc;

        $file_wp = $wp_api->file_or_url_to_wordpress_image($path_doc . '/' . $name_doc);


        $products_data[] = [
        'sku'=>$product->ref,
        'name'=>$product->label,
        'description'=>$product->description,
        'type'=>'simple',
        'regular_price'=>$product->price,
        'weight'=>$product->weight,
        'length'=>$product->length,
        'width'=>$product->width,
        'height'=>$product->height,
        'images'=>[
                [
                        'src' => $file_wp['id']->source_url // $path_doc . '/' . $name_doc
                ]
        ]
	];
}

$wc_api->importProducts( $products_data );

if (!empty($wc_api->getImportStats())) {

        echo json_encode($wc_api->getImportStats());

        $doli_api->delete_resources(dirname(__FILE__) . '/resources');

}

