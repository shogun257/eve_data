<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function get_station_by_id($station_id) {
    $url = 'https://crest-tq.eveonline.com/stations/' . $station_id . '/';
    get_station_by_url($url);
}

function get_station_by_url($url) {
    $jsonfile = file_get_contents($url);
    $jsonarray = json_decode($jsonfile);

    echo '<pre>';
    print_r($jsonarray);
    #foreach ($jsonarray->items as $key => $value) {
    #print_r($value);
    #$sql = "INSERT INTO `eve`.`tbl_order` (`id`, `buy`, `issued`, `price`, `volume`, `duration`, `min_volume`, `range`, `station_id`, `type`) VALUES"
    #            . " ('" . $value->id . "', '" . $value->buy . "', '" . $value->issued . "', '" . $value->price . "', '" . $value->volume . "', '" . $value->duration . "', '" . $value->minVolume . "', '" . $value->range . "', '" . $value->stationID . "', '" . $value->type . "');";
    #echo "<br>" . $sql . "<br>";
    #pdo_eve::mysql_query($sql);
    #}
}
