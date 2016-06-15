<?php

namespace controller;

use \lib\RepoTool;

class Git extends Base
{
    public function __construct()
    {
        ACTION == 'init' && $this->checkRepo = false;
        parent::__construct();
    }
    
    public function init()
    {
        RepoTool::create();
        
        if (!RepoTool::isGitEmpty()){
            $this->redirect(U('Index', 'index'));
        }
        
        $model = new \model\Analyze();
        $databaseInfo = $model->getDatabaseInfo();
        $databaseSql = "";
        foreach($databaseInfo as $table=>$info){
            $databaseSql .= "-- Table structure for table `{$table}`\n";
            $databaseSql .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $databaseSql .= $info['sql']."\n";
            $databaseSql .= "-- Dumping data for table `{$table}`\n";
        }
        
        if(\lib\RepoTool::createPro($databaseSql, $databaseInfo)){
           $this->showMessage("恭喜你，成功创建版本库！", self::INFO, array(U('Index', 'index'), 3));
        }
        
        $this->showMessage("很遗憾版本库创建失败，注意设置git相关信息及data目录权限！", self::ERR, array(U('Index', 'index'), 5));
    }
}