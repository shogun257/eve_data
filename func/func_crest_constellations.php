<?php

require_once 'classes/db_classes/pdo_eve.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function get_regions_2_constellations_by_regions_id($regions_id) {
    $url = 'https://crest-tq.eveonline.com/regions/' . $regions_id . '/';
    get_regions_2_constellations_by_regions_url($url);
}

function get_regions_2_constellations_by_regions_url($url) {
    $jsonfile = file_get_contents($url);
    $jsonarray = json_decode($jsonfile);
    #$sql_itens = 'INSERT IGNORE INTO `eve`.`tbl_constellations` (`id`, `name`) VALUES ';
    $sql_regions_2_constellations = 'INSERT IGNORE INTO `eve`.`tbl_regions_2_constellations` (`tbl_regions_id`, `tbl_constellations_id`) VALUES ';
    $trenner = '';
    $count = 0;
    foreach ($jsonarray->constellations as $value) {
        #$sql_itens .= $trenner . '("' . $value->id . '", "' . mysql_escape_string($value->name) . '");';
        $sql_regions_2_constellations .= $trenner . '("' . $jsonarray->id . '","' . $value->id . '")';
        $trenner = ', ';
        $count++;
    }
    #if (pdo_eve::mysql_query($sql_itens)) {
    #    echo "Angelegt<br>";
    #}
    #echo "<br>" . $sql_regions_2_constellations . "<br>";
    if (pdo_eve::mysql_query($sql_regions_2_constellations)) {
        echo "groups_2_items Angelegt<br>";
    }
    echo "<b>" . $count . " constellations in region</b><br>";
    if ($jsonarray->pageCount > 1) {
        echo "<br><b>zweite seite gefunden</b><br>";
    }
}

function get_constellations_all($add_solarsystems = FALSE) {
    $url = 'https://crest-tq.eveonline.com/constellations/';
    $jsonfile = file_get_contents($url);
    $jsonarray = json_decode($jsonfile);
    echo '<pre>';
    print_r($jsonarray);
    #die();
    $sql_itens = 'INSERT IGNORE INTO `eve`.`tbl_constellations` (`id`, `name`) VALUES ';
    $trenner = '';
    $count = 0;
    foreach ($jsonarray->items as $value) {
        $sql_itens .= $trenner . '("' . $value->id . '", "' . mysql_escape_string($value->name) . '");';
        $trenner = ', ';
        $count++;
    }
    if (pdo_eve::mysql_query($sql_itens)) {
        echo "Angelegt<br>";
    }
    echo "<b>" . $count . " constellations</b><br>";
    if ($jsonarray->pageCount > 1) {
        echo "<br><b>zweite seite gefunden</b><br>";
    }
}
