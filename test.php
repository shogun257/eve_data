<?php

require_once 'classes/db_classes/pdo_eve.php';

$jsonfile = file_get_contents('https://crest-tq.eveonline.com/market/10000013/orders/all/');
$jsonarray = json_decode($jsonfile);

echo '<pre>';
#print_r($jsonarray);
foreach ($jsonarray->items as $key => $value) {
    print_r($value);
}#s
$sql="INSERT INTO `eve`.`tbl_order` (`id`, `buy`, `issued`, `price`, `volume`, `duration`, `minVolume`, `range`, `station_id`, `type`) VALUES ('1', '1', '1', '1', '1', '1', '1', '1', '1', '1');";
pdo_eve::mysql_query($sql);