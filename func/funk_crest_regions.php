<?php

require_once 'classes/db_classes/pdo_eve.php';
require_once 'func/func_crest_constellations.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function get_regions_all($add_costellation = FALSE) {
    $url = 'https://crest-tq.eveonline.com/regions/';
    $jsonfile = file_get_contents($url);
    $jsonarray = json_decode($jsonfile);

    $sql_truncate = "TRUNCATE TABLE `eve`.`tbl_regions`;";
    $sql_insert = "INSERT IGNORE INTO `eve`.`tbl_regions` (`id`, `name`) VALUES ";
    $trenner = "";
    foreach ($jsonarray->items as $value) {
        $sql_insert .= $trenner . "('" . $value->id . "', '" . mysql_escape_string($value->name) . "')";
        $trenner = ", ";
        if ($add_costellation) {
            get_regions_2_constellations_by_regions_url($value->href);
        }
    }
    #if (pdo_eve::mysql_query($sql_truncate)) {
    #    echo "<br>solarsystems loeschen Erfolgreich<br>";
    #}
    if (pdo_eve::mysql_query($sql_insert)) {
        echo "<br>solarsystems regions Erfolgreich<br>";
    } else {
        echo "<br>solarsystems regions Fehlgeschlagen<br>";
    }
    if ($jsonarray->pageCount > 1) {
        echo "<br><b>zweite seite gefunden</b><br>";
    }
    echo '<br>' . $jsonarray->next->href . '<br>';
    if ($jsonarray->pageCount > 1) {
        echo "<br><b>zweite seite gefunden</b><br>";
    }
}
