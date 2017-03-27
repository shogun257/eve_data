<?php
require_once 'classes/db_classes/pdo_eve.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function get_solarsystems_all() {
    $url = 'https://crest-tq.eveonline.com/solarsystems/';
    $jsonfile = file_get_contents($url);
    $jsonarray = json_decode($jsonfile);
    $sql_truncate = "TRUNCATE TABLE `eve`.`tbl_solarsystems`;";
    $sql_insert = "INSERT IGNORE INTO `eve`.`tbl_solarsystems` (`id`, `name`) VALUES ";
    $trenner = "";
    foreach ($jsonarray->items as $value) {
        $sql_insert .= $trenner . "('" . $value->id . "', '" . mysql_escape_string($value->name) . "')";
        $trenner = ", ";
    }
    #if (pdo_eve::mysql_query($sql_truncate)) {
    #    echo "<br>solarsystems loeschen Erfolgreich<br>";
    #}
    if (pdo_eve::mysql_query($sql_insert)) {
        echo "<br>solarsystems speichern Erfolgreich<br>";
    } else {
        echo "<br>solarsystems speichern Fehlgeschlagen<br>";
    }
    if ($jsonarray->pageCount > 1) {
        echo "<br><b>zweite seite gefunden</b><br>";
    }
}