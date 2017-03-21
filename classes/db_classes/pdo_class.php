<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pdo_class
 *
 * @author j.czoske
 */
include_once realpath(dirname(__file__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR . 'dbinc' . DIRECTORY_SEPARATOR . 'db_config.php';

abstract class pdo_class {

    public static $PDOException = null;
    protected static $err_mode = PDO::ERRMODE_SILENT;

    public static function connect() {
        if (static::$pdo === FALSE) {
            if (file_exists('dbinc/is_live.txt')) {
                static::$server = 'mysql:host=' . static::$db_server . ';dbname=' . static::$database;
            } else {
                static::$server = 'mysql:host=' . static::$db_local . ';dbname=' . static::$database;
            }
            try {
                $options = array(PDO::ATTR_PERSISTENT => false);
                $val = static::$MYSQL_ATTR_INIT_COMMAND;
                if (!empty($val)) {
                    $key = PDO::MYSQL_ATTR_INIT_COMMAND;
                    $options[$key] = $val;
                }
                static::$pdo = new PDO(static::$server, static::$data_user, static::$data_pass, $options);
            } catch (PDOException $e) {
                static::$PDOException = $e;
                die('PDO_CLASS::CONNECT(): Datenbank ' . static::$database . ' zur zeit nicht erreichbar! Bitte Verständigen sie ihren Datenbankadministrator.');
                return false;
            }
            static::$pdo->setAttribute(PDO::ATTR_ERRMODE, static::$err_mode);
            static::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        return true;
    }

    public static function set_error_mode($mode = FALSE) {

        if (strtoupper($mode) == 'EXCEPTION') {
            // static::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            static::$err_mode = PDO::ERRMODE_EXCEPTION;
        } elseif (strtoupper($mode) == 'WARNING') {
            static::$err_mode = PDO::ERRMODE_WARNING;
        } else {
            static::$err_mode = PDO::ERRMODE_SILENT;
        }
        if (static::$pdo !== false) {
            static::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
    }

    public static function beginTransaction() {
        static::$pdo->beginTransaction();
    }

    public static function get_error() {
        $error = static::$pdo->errorInfo();
        $error['SQLSTATE'] = $error[0];
        $error['CODE'] = $error[1];
        $error['MSG'] = $error[2];
        return $error;
    }

    public static function commit() {
        static::$pdo->commit();
    }

    public static function rollBack() {
        static::$pdo->rollBack();
    }

    public static function fetch_assoc($stmt) {
        return static::mysql_fetch_assoc($stmt);
    }

    public static function mysql_query($query) {

        if (stripos(substr($query, '0', '10'), 'INSERT') !== FALSE || stripos(substr($query, '0', '10'), 'UPDATE') !== FALSE || stripos(substr($query, '0', '10'), 'REPLACE') !== FALSE || stripos(substr($query, '0', '10'), 'DELETE') !== FALSE) {
            static::query("SET SESSION sql_mode = 'ALLOW_INVALID_DATES'");
            return static::exec($query);
        } else {
            return static::query($query);
        }
    }

    public static function query($query) {

        if (static::connect()) {

            static::$stmt = static::$pdo->query($query);
            return static::$stmt;
        } else {
            return false;
        }
    }

    public static function exec($query) {

        if (static::connect()) {

            $nums = static::$pdo->exec($query);
            return $nums;
        } else {
            return false;
        }
    }

    public static function num_rows($stmt = FALSE) {
        if ($stmt) {
            static::$stmt = $stmt;
        }
        if (is_object(static::$stmt)) {
            return static::$stmt->rowcount();
        } else {
            return false;
        }
    }

    public static function prepare($query) {
        static::$stmt = static::$pdo->prepare($query);
        return static::$stmt;
    }

    public static function execute() {
        static::$stmt->execute();
        return static::$stmt;
    }

    /* public static function bindParam($i, $value){
      static::$stmt->bindParam($i, $value);
      } */

    public static function mysql_fetchAll_assoc($stmt = FALSE) {
        if ($stmt) {
            static::$stmt = $stmt;
        }
        if (is_object(static::$stmt)) {
            $dat = static::$stmt->fetchAll(PDO::FETCH_ASSOC);
            return $dat;
        } else {
            return false;
        }
    }

    public static function mysql_fetch_assoc($stmt = FALSE) {
        if ($stmt) {
            static::$stmt = $stmt;
        }
        if (is_object(static::$stmt)) {
            $dat = static::$stmt->fetch(PDO::FETCH_ASSOC);
            return $dat;
        } else {
            return false;
        }
    }

    public static function mysql_fetchAll_array($stmt = FALSE) {
        if ($stmt) {
            static::$stmt = $stmt;
        }
        $dat = static::$stmt->fetchAll(PDO::FETCH_BOTH);
        return $dat;
    }

    public static function mysql_fetch_array($stmt = FALSE) {
        if ($stmt) {
            static::$stmt = $stmt;
        }
        $dat = static::$stmt->fetch(PDO::FETCH_BOTH);
        return $dat;
    }

    public static function mysql_fetchAll_num($stmt = FALSE) {
        if ($stmt) {
            static::$stmt = $stmt;
        }
        if (is_object(static::$stmt)) {
            $dat = static::$stmt->fetchAll(PDO::FETCH_NUM);
            return $dat;
        } else {
            return false;
        }
    }

    public static function mysql_fetch_num($stmt = FALSE) {
        if ($stmt) {
            static::$stmt = $stmt;
        }
        if (is_object(static::$stmt)) {
            $dat = static::$stmt->fetch(PDO::FETCH_NUM);
            return $dat;
        } else {
            return false;
        }
    }

    public static function lastInsertId() {
        return static::$pdo->lastInsertId();
    }

    /**
     * @desc erstellt MySql INSERT string aus tabellenname und Valueliste
     * @param string $tbl tabellenname
     * @param Array $arr_value kye = feldname, value = value
     * @return string MySql INSERT string
     */
    public static function create_insert_string($tbl, $arr_value) {
        $feldliste = '';
        $values = '';
        $trenner = '';
        foreach ($arr_value as $key => $value) {
            if ($value !== NULL && $value !== '') {
                $feldliste .= $trenner . $key;
                $values .= $trenner . "'" . mysql_escape_string($value) . "'";
                $trenner = ",";
            }
        }
        $sql = "INSERT INTO " . $tbl . "(" . $feldliste . ") VALUES (" . $values . ")";
        return $sql;
    }

    /**
     * @desc erstellt MySql UPDATE string aus tabellenname und Valueliste
     * @param string $tbl tabellenname
     * @param Array $arr_value kye = feldname, value = value
     * @param string $where wherebedingung ohne 'WHERE'
     * @return string MySql UPDATE string
     */
    public static function create_update_string($tbl, $arr_value, $where) {
        $feldliste = '';
        $trenner = '';
        foreach ($arr_value as $key => $value) {
            if ($value === NULL) {
                $feldliste .= $trenner . $key . "= NULL ";
            } else {
                $feldliste .= $trenner . $key . "= '" . mysql_escape_string($value) . "' ";
            }
            $trenner = ",";
        }
        $sql = "UPDATE " . $tbl . " SET " . $feldliste . " WHERE " . $where;
        return $sql;
    }

}
