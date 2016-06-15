<?php

define("T", ROOT_PATH.'templates/');
defined("DATA_PATH") || define("DATA_PATH", ROOT_PATH.'data/');
defined("GIT_DATA_PATH") || define("GIT_DATA_PATH", ROOT_PATH.'data/versions/');
defined("LOCAL_DATA_PATH") || define("LOCAL_DATA_PATH", ROOT_PATH.'data/local/');
require __DIR__.'/function.php';

use \lib\Config;

class Dbver 
{
    public function __construct($config)
    {
        set_exception_handler('\Dbver::appException');
        
        spl_autoload_register(array('Dbver', 'autoload'));
        Config::$config = $config;
        
        $controller = $_GET['c'] ?:"Index";
        $action = $_GET['a'] ?:'index';
        $class = "\controller\\{$controller}";
        
        define('ACTION', $action);
        define('CONTROLLER', $controller);
        (new $class())->$action();
    }
    
    public function autoload($name)
    {
        require ROOT_PATH.str_replace('\\','/','/'.$name).'.php';
    }
    
    public static function appException($e)
    {
        $view = new \lib\View();
        $view->e = $e;
        $view->display("exception");
    }
}