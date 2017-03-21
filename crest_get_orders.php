<?php
ini_set("max_execution_time", "180");
#10000002
require_once 'classes/db_classes/pdo_eve.php';
require_once 'func/func_crest_stations.php';
require_once 'func/func_crest_items.php';


#$region_ids[] = '10000002'; #The Forge
$region_ids[] = '10000013'; #malpais
foreach ($region_ids as $region_id) {
    $url = 'https://crest-tq.eveonline.com/market/' . $region_id . '/orders/all/';
    get_market_by_url($url);
}

function get_market_by_url($url) {
    $jsonfile = file_get_contents($url);
    $jsonarray = json_decode($jsonfile);

    echo '<pre>';
    #print_r($jsonarray);
    foreach ($jsonarray->items as $key => $value) {
        #print_r($value);
        $sql = "INSERT INTO `eve`.`tbl_order` (`id`, `buy`, `issued`, `price`, `volume`, `duration`, `min_volume`, `range`, `station_id`, `type`) VALUES"
                . " ('" . $value->id . "', '" . $value->buy . "', '" . $value->issued . "', '" . $value->price . "', '" . $value->volume . "', '" . $value->duration . "', '" . $value->minVolume . "', '" . $value->range . "', '" . $value->stationID . "', '" . $value->type . "');";
        echo "<br>" . $sql . "<br>";
        pdo_eve::mysql_query($sql);
    }
    echo '<br>' . $jsonarray->next->href . '<br>';
    get_market_by_url($jsonarray->next->href);
}
