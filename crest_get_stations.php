<?php

ini_set("max_execution_time", "180");
#10000002
require_once 'classes/db_classes/pdo_eve.php';
require_once 'func/func_crest_stations.php';
require_once 'func/func_crest_items.php';


#$region_ids[] = '10000002'; #The Forge
#$region_ids[] = '10000013'; #malpais
#$station_ids[] = '61000161';

$sql_find_stations_in_orders = " SELECT station_id FROM tbl_order GROUP BY station_id";
$res = pdo_eve::mysql_query($sql_find_stations_in_orders);
while ($station_ids = pdo_eve::mysql_fetch_num($res)) {

    foreach ($station_ids as $station_id) {
        #print_r($station_id);
        get_station_by_id($station_id);
    }
}
