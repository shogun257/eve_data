<?php
require_once 'classes/db_classes/pdo_eve.php';
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
    preg_match("/[0-9]{3,}/", $url, $station_id);
    $jsonfile = file_get_contents($url);
    $jsonarray = json_decode($jsonfile);

    echo '<pre>';
    print_r($jsonarray);

    $sql = "INSERT INTO `eve`.`tbl_stations` (`id`, `system_name`, `type_id`, `name`) VALUES "
            . "('" . $station_id[0] . "', '" . $jsonarray->system->name . "', '" . get_item_by_url($jsonarray->type->href) . "', '" . $jsonarray->name . "')";
    echo "<br>" . $sql . "<br>";
    pdo_eve::mysql_query($sql);
}
