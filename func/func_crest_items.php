<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function get_item_by_id($station_id) {
    $url = 'https://crest-tq.eveonline.com/stations/' . $station_id . '/';
    get_item_by_url($url);
}

function get_item_by_url($url) {
    preg_match("/[0-9]{3,}/", $url, $item_id);
    $jsonfile = file_get_contents($url);
    $jsonarray = json_decode($jsonfile);

    echo '<pre>';
    print_r($jsonarray);
die();
    if ($station_id !== NULL) {
        $sql = "INSERT INTO `eve`.`tbl_stations` (`id`, `system_name`, `type_id`, `name`) VALUES "
                . "('" . $station_id . "', '" . $jsonarray->system->name . "', '" . $jsonarray . "', '" . $jsonarray . "')";
        echo "<br>" . $sql . "<br>";
        #pdo_eve::mysql_query($sql);
    }
}