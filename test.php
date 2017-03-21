<?php

require_once 'classes/db_classes/pdo_eve.php';

$jsonfile = file_get_contents('https://crest-tq.eveonline.com/market/10000013/orders/all/');
$jsonarray = json_decode($jsonfile);

echo '<pre>';
#print_r($jsonarray);
foreach ($jsonarray->items as $key => $value) {
    print_r($value);
    $sql = "INSERT INTO `eve`.`tbl_order` (`id`, `buy`, `issued`, `price`, `volume`, `duration`, `minVolume`, `range`, `station_id`, `type`) VALUES"
            . " ('" . $value->id . "', '" . $value->buy . "', '" . $value->issued . "', '" . $value->price . "', '" . $value->volume . "', '" . $value->duration . "', '" . $value->minVolume . "', '" . $value->range . "', '" . $value->stationID . "', '" . $value->type . "');";
    echo "<br>" . $sql . "<br>";
    pdo_eve::mysql_query($sql);
} 
