<?php

ini_set("max_execution_time", "180");
#10000002
require_once 'classes/db_classes/pdo_eve.php';
require_once 'func/func_crest_stations.php';


#$region_ids[] = '10000002'; #The Forge
#$region_ids[] = '10000013'; #malpais
$station_ids[] = '61000161';

foreach ($station_ids as $station_id) {
    get_station_by_id($station_id);
}
