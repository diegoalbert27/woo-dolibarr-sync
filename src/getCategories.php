<?php

require_once './dolibarr_to_wc.php';

$DoliberrToWc = new DoliberrToWc('webservices', 'PPovCAG2uiaS');
$Categories = $DoliberrToWc->getCategories();

echo json_encode($Categories);
