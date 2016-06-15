<?php
$config = array();

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
$config['GIT_BIN'] = "git";
$config['GIT_PATCH'] = "";
$config['GIT_USER'] = "";
$config['GIT_EMAIL'] = "";

/**
 * 本工具依赖mysql PDO扩展，mysql帐号必须拥有该数据所有权限
 * 该工具会有创建库、表、索引等系列操作
 */
$config['DB_HOST'] = '';
$config['DB_USER'] = '';
$config['DB_PWD'] = '';
$config['DB_PORT'] = '';
$config['DB_NAME'] = '';
$config['DB_CHARSET'] = '';

/**
 * 生产环境对比配置
 */
$config['UPGRADE']['DB_HOST'] = '';
$config['UPGRADE']['DB_USER'] = '';
$config['UPGRADE']['DB_PWD'] = '';
$config['UPGRADE']['DB_PORT'] = '';
$config['UPGRADE']['DB_NAME'] = '';
$config['UPGRADE']['DB_CHARSET'] = '';
$config['UPGRADE']['SYNC_BIN'] = '';

/**
 * 图标对应样式，系统配置
 */
$config['-'] = 'del';
$config['+'] = 'add';
$config['?'] = "clash";
$config['☷'] = "edit";
define("IS_WINDOWS", strpos(strtoupper(PHP_OS), "WIN") !==false);

return $config;