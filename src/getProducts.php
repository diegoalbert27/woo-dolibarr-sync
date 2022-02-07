<?php

require_once './dolibarr_to_wc.php';

$DoliberrToWc = new DoliberrToWc('webservices', 'PPovCAG2uiaS');
$Products = $DoliberrToWc->getProducts();

echo json_encode($Products);
