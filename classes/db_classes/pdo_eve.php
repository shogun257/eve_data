<?php
require_once ('pdo_class.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of db_ams
 *
 * @author j.czoske
 */
class pdo_eve extends pdo_class{
    
    public static $database = "eve";
    public static $data_user = "eve";
    public static $data_pass = "db_eve_2017";
    public static $db_local = "jens.thueringer.name";
    public static $db_server = "jens.thueringer.name";
    public static $server; # = 'mysql:host=localhost;dbname=ams';
    public static $pdo = false;
    public static $stmt;
    public static $MYSQL_ATTR_INIT_COMMAND = "SET NAMES latin1 ";
    
}

#pdo_ams::connect();
