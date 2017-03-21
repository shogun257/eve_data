<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function get_item_by_id($item_id) {
    $url = 'https://crest-tq.eveonline.com/inventory/types/' . $item_id . '/';
    get_item_by_url($url);
}

function get_item_by_url($url) {
    preg_match("/[0-9]{3,}/", $url, $item_id);
    $jsonfile = file_get_contents($url);
    $jsonarray = json_decode($jsonfile);

    echo '<pre>';
    echo 'id: ' . $item_id[0] . '<br>';
    print_r($jsonarray);

    #if ($jsonarray->name > 0) {
        $sql = "INSERT INTO `eve`.`tbl_items` (`id`, `name`) VALUES "
                . "('" . $item_id[0] . "', '$jsonarray->name');";
        echo "<br>" . $sql . "<br>";
        pdo_eve::mysql_query($sql);
    #}
    return $item_id[0];
}
