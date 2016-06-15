<?php
$config = array();

$config['UPGRADE']['DB_HOST'] = '';
$config['UPGRADE']['DB_USER'] = '';
$config['UPGRADE']['DB_PWD'] = '';
$config['UPGRADE']['DB_PORT'] = '';
$config['UPGRADE']['DB_NAME'] = '';
$config['UPGRADE']['DB_CHARSET'] = '';
$config['UPGRADE']['SYNC_BIN'] = '';

$i = $j = 0;
// 项目数据库
$config['SERVERS'][$i]["NAME"] = "project name";
$config['SERVERS'][$i]['GIT_BIN'] = "";
$config['SERVERS'][$i]['GIT_PATCH'] = "";
$config['SERVERS'][$i]['GIT_USER'] = "";
$config['SERVERS'][$i]['GIT_EMAIL'] = "";
$config['SERVERS'][$i]['DATA_PATH'] = ROOT_PATH."data/project name/";

// 本地开发数据
$config['SERVERS'][$i]['env'][$j]['NAME'] = "本地数据库";
$config['SERVERS'][$i]['env'][$j]['DB_HOST'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_USER'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_PWD'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_PORT'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_NAME'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_CHARSET'] = 'utf8';
$config['SERVERS'][$i]['env'][$j]['DATA_PATH'] = 'local/';
$j++;

// 正式环境
$config['SERVERS'][$i]['env'][$j]['NAME'] = "正式环境数据库";
$config['SERVERS'][$i]['env'][$j]['DB_HOST'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_USER'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_PWD'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_PORT'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_NAME'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_CHARSET'] = 'utf8';
$config['SERVERS'][$i]['env'][$j]['DATA_PATH'] = 'pro/';
$j++;

// 测试环境
$config['SERVERS'][$i]['env'][$j]['NAME'] = "测试环境";
$config['SERVERS'][$i]['env'][$j]['DB_HOST'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_USER'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_PWD'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_PORT'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_NAME'] = '';
$config['SERVERS'][$i]['env'][$j]['DB_CHARSET'] = 'utf8';
$config['SERVERS'][$i]['env'][$j]['DATA_PATH'] = 'test/';


// 以下不需要配置
// 根据cookie判断使用的环境
if(isset($_GET['server']) && isset($config['SERVERS'][$_GET['server']])){
    setcookie("server", $_GET['server'], time() + 31536000);
    $_COOKIE['server'] = $_GET['server'];
}
$server = isset($_COOKIE['server']) && isset($config['SERVERS'][$_COOKIE['server']]) ? $config['SERVERS'][$_COOKIE['server']] : reset($config['SERVERS']);

if(isset($_GET['env']) && isset($server['env'][$_GET['env']])){
    setcookie("env", $_GET['env'], time() + 31536000);
    $_COOKIE['env'] = $_GET['env'];
}
$env = isset($_COOKIE['env']) && isset($server['env'][$_COOKIE['env']]) ? $server['env'][$_COOKIE['env']] : reset($server['env']);

// 定义全局常量
define("DATA_PATH", $server['DATA_PATH']);
define("GIT_DATA_PATH", DATA_PATH.'versions/');
define("LOCAL_DATA_PATH", DATA_PATH.$env['DATA_PATH']);

mkdir(GIT_DATA_PATH, 0755, true);
mkdir(LOCAL_DATA_PATH, 0755, true);

$config['SERVER_ENVS'] = $server['env'];
$config['SERVER_NAME'] = $server['NAME'];
$config['ENVS_NAME'] = $env['NAME'];

/**
 * GIT_BIN 
 * git命令可执行路径，git必须配置为可用，否者会出现意想不到的问题
 * windows必须把git加入到环境变量里，这里只写入git或者为空。
 * 
 * GIT_PATCH
 * 用来管理数据库版本的git仓库，必须拥有master推送权限
 * 
 * GIT_USER 及 GIT_EMAIL
 * 用来设置当前版本库的提交用户信息
 */
$config['GIT_BIN'] = $server['GIT_BIN'];
$config['GIT_PATCH'] = $server['GIT_PATCH'];
$config['GIT_USER'] = $server['GIT_USER'];
$config['GIT_EMAIL'] = $server['GIT_EMAIL'];

/**
 * 本工具依赖mysql PDO扩展，mysql帐号必须拥有该数据所有权限
 * 该工具会有创建库、表、索引等系列操作
 */
$config['DB_HOST'] = $env['DB_HOST'];
$config['DB_USER'] = $env['DB_USER'];
$config['DB_PWD'] = $env['DB_PWD'];
$config['DB_PORT'] = $env['DB_PORT'];
$config['DB_NAME'] = $env['DB_NAME'];
$config['DB_CHARSET'] = $env['DB_CHARSET'];

$config['-'] = 'del';
$config['+'] = 'add';
$config['?'] = "clash";
$config['☷'] = "edit";
define("IS_WINDOWS", strpos(strtoupper(PHP_OS), "WIN") !==false);

return $config;