<?php

require_once dirname(__FILE__) .'/src/dolibarr.php';
require_once dirname(__FILE__) .'/src/woocommerce.php';

$doli_api = new doli_api('webservice', 'RxvjB9d85SLht6998QbA3VoGt9KBGn5y');
$wc_api = new wc_api('http://wordpress.test', 'ck_914f5b830a89ec006bcc1346eaf9f82cb5a5246f', 'cs_e1fc7001ddb2f28dd0272f88b125a778a6a05db1');

$categories = $doli_api->getCategories();
$categories_data = [];
foreach ($categories as $k=>$v) {

	$categories_data[$k] = [
        'name'=>$v->label,
        'description'=>$v->description,
        'parent'=>(int)$v->fk_parent,
	];

	//build tree 
	if ($v->fk_parent) {
		$doli_parent = $doli_api->getCategories( ["id"=>$v->fk_parent] ); 
		if ($doli_parent) {
			//generate slug from category name
			$slug = $wc_api->slugify( $doli_parent->label ); 

			//search wc category by slug
			$parent_found = $wc_api->searchCategory( $slug );

			if (count( $parent_found ) >= 1) {
				//walk categories and search unique product
				foreach ($parent_found as $c) {
					if ($c->slug === $slug) {
						$categories_data[$k]["parent"] = (int)$c->id;
					}
				}
			}
		}
	}

}
$wc_api->importCategories( $categories_data );

echo json_encode($wc_api->getImportStats());