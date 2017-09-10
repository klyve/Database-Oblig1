<?php

require_once('config.php');

/* Use a singleton for db ffs you dumb cunt */
class Database {
    private static $connection = false;
    public static function instance() {
        if(!self::$connection) {
            self::$connection = new PDO('mysql:dbname='.DB_NAME.';host=' . DB_HOST, DB_USER, DB_PWD,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        }
        return self::$connection;
    }
}