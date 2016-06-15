<?php
error_reporting(0);
header("Content-type: text/html; charset=utf-8");
define('ROOT_PATH', __DIR__.'/');
define('STATIC_PATH', dirname($_SERVER["PHP_SELF"]).'/static/');

$config = require dirname(__FILE__) . '/config.php';
require_once dirname(__FILE__) . '/lib/Dbver.php';
new Dbver($config);