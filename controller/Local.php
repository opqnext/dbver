<?php

namespace controller;

use model\Analyze;
use lib\RepoTool;

class Local extends Base
{
    public function __construct()
    {
        in_array(ACTION,array('init', 'upLocal', 'upData')) && $this->checkRepo = false;
        parent::__construct();
    }
    
    public function init()
    {
        RepoTool::create();
        RepoTool::pull();
        
        // 检查本地数据是否有数据表
        $model = new Analyze();
        if($model->getTbales()){
            $databaseInfo = $model->getDatabaseInfo();
            $lastDatabaseInfo = json_decode(file_get_contents(GIT_DATA_PATH.'details.json'), true);
            $diffTool = new \model\DiffTool();
            $diffFormat = $diffTool->diffFormat($lastDatabaseInfo, $databaseInfo);
            if($diffFormat['new']){
                $diffSql = $diffTool->diffSql($databaseInfo, $lastDatabaseInfo);
                $this->view->diffFormat = $diffFormat;
                $this->view->diffSql = $diffSql;
                $this->view->display('diff_local');
            }else{
                $this->upData();
            }
        }else{
            
            $databaseInfo = json_decode(file_get_contents(GIT_DATA_PATH.'details.json'), true);
        
            $delaySql = array();
            foreach($databaseInfo as $table=>$info){
                $sql = array("ALTER TABLE `{$table}`");
                if(isset($info['indexs']['_FOREIGN_KEY_'])){
                    foreach($info['indexs']['_FOREIGN_KEY_'] as $foreignKey){
                        $info['sql'] = preg_replace("/,\n\s+".addcslashes($foreignKey,"`()_-")."(,*?)/", "", $info['sql']);
                        $sql[] = "ADD {$foreignKey}";
                    }
                }
                
                if(isset($sql[1])){
                    $first = array_shift($sql);
                    $delaySql[] = $first."\n".implode(",\n", $sql).";";
                }
                
                $model->execute($info['sql']);
            }
         
            foreach($delaySql as $sql){
                $model->execute($sql);
            }
            
            $this->upData();
        }
    }
    
    public function upData()
    {
        RepoTool::create();
        RepoTool::pull();
        
        $model = new \model\Analyze();
        $databaseInfo = $model->getDatabaseInfo();
        $lastDatabaseInfo = json_decode(file_get_contents(GIT_DATA_PATH.'details.json'), true);
        $diffTool = new \model\DiffTool();
        $diffSql = $diffTool->diffSql($databaseInfo, $lastDatabaseInfo);

        foreach($diffSql as $sql){
            $model->execute($sql);
        }
        
        if(RepoTool::localSync()){
            $this->showMessage("本地版本库同步成功！", self::INFO, array(U('Index', 'index'), 3));
        }
        
        $this->showMessage("本地版本库同步失败，注意设置git相关信息及data目录权！", self::ERR, array(U('Index', 'index'), 5));
    }
    
    public function upLocal()
    {
        RepoTool::create();
        RepoTool::pull();
        
        if(RepoTool::localSync()){
            $this->showMessage("本地版本库同步成功！", self::INFO, array(U('Index', 'index'), 3));
        }
        
        $this->showMessage("本地版本库同步失败，注意设置git相关信息及data目录权！", self::ERR, array(U('Index', 'index'), 5));
    }
}