<?php

namespace model;

use \lib\Config;

abstract  class Model
{
    static $db;
    protected $dbName;
    
    public function __construct($config=null)
    {
        $config || $config = Config::$config;
        if(!self::$db){
            self::$db = new \PDO("mysql:host={$config['DB_HOST']};{$config['DB_PORT']};dbname={$config['DB_NAME']}", $config['DB_USER'], $config['DB_PWD']);
            self::$db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$db->query("SET NAMES {$config['DB_CHARSET']}");
            self::$db->query("use {$config['DB_NAME']}");
        }
        
        $this->dbName = Config::$config['DB_NAME'];
    }
}