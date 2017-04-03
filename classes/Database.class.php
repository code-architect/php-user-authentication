<?php


class Database
{

private static $_db; //singleton connection object

private function __construct() {}	// disallow creating a new obj of the class with new Database()

private function __clone(){}	// disallow cloaning the class


    public static function get_instance()
    {
        if(static::$_db === null)
        {
            $dsn = 'mysql:host='.Config::DB_HOST.';dbname='.Config::DB_NAME.';charset=utf8';
            static::$_db = new PDO($dsn, Config::DB_USER, Config::DB_PASS);

            // Rais exception when a database exception occurs
            static::$_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return static::$_db;
    }






}