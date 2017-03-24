<?php

ini_set("max_execution_time", "600");
#10000002
require_once 'classes/db_classes/pdo_eve.php';
$url = 'https://crest-tq.eveonline.com/inventory/categories/';

$sql_categories = "SELECT * FROM `eve`.`tbl_categories`";
$arr_categories_id = array();
$res_categories = pdo_eve::mysql_query($sql_categories);
while ($dat_categories = pdo_eve::mysql_fetch_assoc($res_categories)) {
    $arr_categories_id[$dat_categories['id']] = $dat_categories['name'];
}
$sql_groups = "SELECT * FROM `eve`.`tbl_groups` JOIN `tbl_categories_2_groups` ON `id` = `tbl_groups_id`";
$arr_groups_id = array();
$res_groups = pdo_eve::mysql_query($sql_groups);
while ($dat_groups = pdo_eve::mysql_fetch_assoc($res_groups)) {
    $arr_groups_id[$dat_groups['id']] = $dat_groups['name'];
}

$sql_items = "SELECT * FROM `eve`.`tbl_items` JOIN `tbl_groups_2_items` ON `id` = `tbl_items_id`";
$arr_items_id = array();
$res_items = pdo_eve::mysql_query($sql_items);
while ($dat_items = pdo_eve::mysql_fetch_assoc($res_items)) {
    $arr_items_id[$dat_items['id']] = $dat_items['name'];
}
get_categories_by_url($url, $arr_categories_id, $arr_groups_id, $arr_items_id);

function get_categories_by_url($url, $arr_categories_id, $arr_groups_id, $arr_items_id) {
    $jsonfile = file_get_contents($url);
    $jsonarray = json_decode($jsonfile);
    echo '<pre>';
    foreach ($jsonarray->items as $key => $value) {
        echo "<br><b>New categorie " . $value->id . " " . $value->name . "</b><br>";
        if (!isset($arr_categories_id[$value->id])) {
            $sql = 'INSERT INTO `eve`.`tbl_categories` (`id`, `name`) VALUES ("' . $value->id . '", "' .  mysql_escape_string($value->name) . '");';
            #echo "<br>" . $sql . "<br>";
            if (pdo_eve::mysql_query($sql)) {
                echo "Angelegt<br>";
            } else {
                echo "Nicht Angelegt<br>";
            }
        } else {
            echo "Bereits Vorhanden<br>";
        }
        echo '<br>' . $value->href . '<br>';
        get_categorie_by_url($value->href, $arr_groups_id, $arr_items_id);
    }
    echo '<br>' . $jsonarray->next->href . '<br>';
    if ($jsonarray->pageCount > 1) {
        echo "<br><b>zweite seite gefunden</b><br>";
    }
}

function get_categorie_by_url($url, $arr_groups_id, $arr_items_id) {
    $jsonfile = file_get_contents($url);
    $jsonarray = json_decode($jsonfile);

    foreach ($jsonarray->groups as $key => $value) {
        echo "<br><b>New groups " . $value->id . " " . $value->name . "</b><br>";
        if (!isset($arr_groups_id[$value->id])) {
            $sql = 'INSERT INTO `eve`.`tbl_groups` (`id`, `name`) VALUES ("' . $value->id . '", "' . mysql_escape_string($value->name) . '");';
            #echo "<br>" . $sql . "<br>";
            if (pdo_eve::mysql_query($sql)) {
                echo "Angelegt<br>";
            } else {
                echo "Nicht Angelegt<br>";
            }
            $sql = 'INSERT INTO `eve`.`tbl_categories_2_groups` (`tbl_categories_id`, `tbl_groups_id`) VALUES ("' . $jsonarray->id . '","' . $value->id . '");';
            #echo "<br>" . $sql . "<br>";
            if (pdo_eve::mysql_query($sql)) {
                echo "categories_2_groups Angelegt<br>";
            } else {
                echo "categories_2_groups Nicht Angelegt<br>";
            }
        } else {
            echo "Bereits Vorhanden<br>";
        }
        get_groups_by_url($value->href, $arr_items_id);
    }
}

function get_groups_by_url($url, $arr_items_id) {
    $jsonfile = file_get_contents($url);
    $jsonarray = json_decode($jsonfile);
    $count = 1;
    foreach ($jsonarray->types as $key => $value) {
        if (!isset($arr_items_id[$value->id])) {
            echo "<br><b>" . $count . " " . $value->id . " " . $value->name . "</b><br>";
            $sql = 'INSERT INTO `eve`.`tbl_items` (`id`, `name`) VALUES ("' . $value->id . '", "' . mysql_escape_string($value->name) . '");';
            #echo $sql . "<br>";
            if (pdo_eve::mysql_query($sql)) {
                echo "Angelegt<br>";
            } else {
                echo "Nicht Angelegt<br>";
            }
            $sql = 'INSERT INTO `eve`.`tbl_groups_2_items` (`tbl_groups_id`, `tbl_items_id`) VALUES ("' . $jsonarray->id . '","' . $value->id . '");';
            #echo "" . $sql . "<br>";
            if (pdo_eve::mysql_query($sql)) {
                echo "groups_2_items Angelegt<br>";
            } else {
                echo "groups_2_items Nicht Angelegt<br><br>";
            }
        }
        #get_types_by_url($value->href);
        $count++;
    }
    echo "<b>" . ($count - 1) . " items in group</b><br>";
    #die();
}
