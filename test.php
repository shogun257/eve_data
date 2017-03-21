<?php


$jsonfile = file_get_contents('https://crest-tq.eveonline.com/market/10000013/orders/all/'); 
$jsonarray = json_decode($jsonfile); 

echo '<pre>';
#print_r($jsonarray);
foreach ($jsonarray->items as $key => $value) {
    print_r($value);
}