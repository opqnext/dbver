<?php

namespace controller;

use lib\RepoTool;

class Version extends Base
{
    
    public function index()
    {
       // 更新远程版本库
       RepoTool::pull();
       $this->view->fall = RepoTool::fall();
       
       $model = new \model\Analyze();
       $databaseInfo = $model->getDatabaseInfo();
       
       $lastDatabaseInfo = json_decode(file_get_contents(LOCAL_DATA_PATH.'details.json'), true);
       
       $diffTool = new \model\DiffTool();
       $diffFormat = $diffTool->diffFormat($lastDatabaseInfo, $databaseInfo);
       $diffSql = $diffTool->diffSql($lastDatabaseInfo, $databaseInfo);
       
       $this->view->diffFormat = $diffFormat;
       $this->view->diffSql = $diffSql;
       $this->view->display('version');
    }
    
    public function commit()
    {
        // 更新远程版本库
        RepoTool::pull();
        $fall = RepoTool::fall();
        
        if($fall > 0 ){
            $this->showMessage("您本地的版本落后于公共版本{$fall}次修改，请先执行更新！", self::ERR, array(U('Version', 'index'), 3));
        }
        
        if(!$_POST['message']){
            $this->showMessage("请填写提交说明！", self::ERR, array(U('Version', 'index'), 3));
        }
        
        $model = new \model\Analyze();
        $databaseInfo = $model->getDatabaseInfo();
        $lastDatabaseInfo = json_decode(file_get_contents(LOCAL_DATA_PATH.'details.json'), true);
         
        $diffTool = new \model\DiffTool();
        $diffFormat = $diffTool->diffFormat($lastDatabaseInfo, $databaseInfo);
        $diffSql = $diffTool->diffSql($lastDatabaseInfo, $databaseInfo);
        
        $databaseSql = "";
        foreach($databaseInfo as $table=>$info){
            $databaseSql .= "-- Table structure for table `{$table}`\n";
            $databaseSql .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $databaseSql .= $info['sql']."\n";
            $databaseSql .= "-- Dumping data for table `{$table}`\n";
        }
        
        $changeSql = "";
        foreach($diffSql as $table=>$sql){
            $changeSql .= "-- Modify the data table table `{$table}`\n";
            $changeSql .= $sql."\n";
            $changeSql .= "-- Dumping data for table `{$table}`\n";
        }
        
        if(\lib\RepoTool::upPro($databaseSql, $databaseInfo, $changeSql, $_POST['message'])){
            $this->showMessage("提交修改成功！", self::INFO, array(U('Index', 'index'), 3));
        }
        
        $this->showMessage("很遗憾提交修改失败，注意设置git相关信息及data目录权限！", self::ERR, array(U('Version', 'index'), 5));
    }
    
    public function update()
    {
        RepoTool::pull();
        $model = new \model\Analyze();
        $localDatabase = $model->getDatabaseInfo();
        $remoteDatabase = json_decode(file_get_contents(GIT_DATA_PATH.'details.json'), true);
        $referDatabase = json_decode(file_get_contents(LOCAL_DATA_PATH.'details.json'), true);
        
        $diffTool = new \model\DiffTool();
        $diffData = $diffTool->diffEdit($referDatabase, $localDatabase, $remoteDatabase);
        
        $this->view->diffData = $diffData;
        $this->view->display('diff_update');
    }
    
    public function change()
    {
        $model = new \model\Analyze();
        $localDatabase = $model->getDatabaseInfo();
        $remoteDatabase = json_decode(file_get_contents(GIT_DATA_PATH.'details.json'), true);
        $referDatabase = json_decode(file_get_contents(LOCAL_DATA_PATH.'details.json'), true);
        
        $diffTool = new \model\DiffTool();
        $diffData = $diffTool->diffEdit($referDatabase, $localDatabase, $remoteDatabase);
        
        $tables = explode(",", (string)$_GET['tables']);
        foreach ($diffData['it_sql'] as $table=>$sql){
            if($diffData['tables'][$table]['clash'] && in_array($table, $tables)){
                $sql = $diffData['my_sql'][$table];
            }
            
            if($sql){
               $model->execute($sql);
            }
        }
        
        if(RepoTool::localSync()){
            $this->showMessage("本地版本库更新成功！", self::INFO, array(U('Index', 'index'), 3));
        }
        
        $this->showMessage("本地版本库更新失败，注意设置git相关信息及data目录权！", self::ERR, array(U('Index', 'index'), 5));
    }
}