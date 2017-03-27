<?php

require_once 'classes/db_classes/pdo_eve.php';
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

function get_items_by_groups_url($url, $arr_items_id = array()) {
    $jsonfile = file_get_contents($url);
    $jsonarray = json_decode($jsonfile);
    $count = 0;
    $sql_itens = 'INSERT IGNORE INTO `eve`.`tbl_items` (`id`, `name`) VALUES ';
    $sql_groups_2_items = 'INSERT IGNORE INTO `eve`.`tbl_groups_2_items` (`tbl_groups_id`, `tbl_items_id`) VALUES ';
    $trenner = '';
    $insert = FALSE;
    foreach ($jsonarray->types as $value) {
        if (!isset($arr_items_id[$value->id])) {
            $sql_itens .= $trenner . '("' . $value->id . '", "' . mysql_escape_string($value->name) . '")';
            $sql_groups_2_items .= $trenner . '("' . $jsonarray->id . '","' . $value->id . '")';
            $trenner = ', ';
            $insert = TRUE;
        }
        $count++;
    }
    if ($insert && pdo_eve::mysql_query($sql_itens)) {
        echo "Angelegt<br>";
    }
    if ($insert && pdo_eve::mysql_query($sql_groups_2_items)) {
        echo "groups_2_items Angelegt<br>";
    }
    echo "<b>" . $count . " items in group</b><br>";
}
