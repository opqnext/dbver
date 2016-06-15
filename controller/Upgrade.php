<?php

namespace controller;

use lib\Config;
use model\Analyze;
use model\DiffTool;

class Upgrade extends Base
{
    public function update()
    {
        $descriptorspec = array(
            1 => array('pipe', 'w'),
            2 => array('pipe', 'w'),
        );
        $pipes = array();
        $cwd = "/tmp";
        $resource = proc_open(Config::$config['UPGRADE']['SYNC_BIN'].
            ' --host '.Config::$config['UPGRADE']['DB_HOST'].
            ' --user '.Config::$config['UPGRADE']['DB_USER'].
            ' --password '.Config::$config['UPGRADE']['DB_PWD'].
            ' --dbname '.Config::$config['UPGRADE']['DB_NAME'].
            ' --port '.Config::$config['UPGRADE']['DB_PORT']
            , $descriptorspec, $pipes, $cwd, $_ENV);
        
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        foreach ($pipes as $pipe) {
            fclose($pipe);
        }
        
        $status = trim(proc_close($resource));
        if ($status) {
            throw new \Exception($stderr);
        }
        
        setcookie("Upgrade", time(), time() + 604800);
        $this->redirect(U('Upgrade', 'diff'));    
    }
    
    public function diff()
    {
        // 检查本地数据是否有数据表
        $model = new Analyze(Config::$config['UPGRADE']);
        if($_COOKIE["Upgrade"] && $model->getTbales()){
            $databaseInfo = $model->getDatabaseInfo();
            $lastDatabaseInfo = json_decode(file_get_contents(GIT_DATA_PATH.'details.json'), true);
            $diffTool = new \model\DiffTool();
            $diffFormat = $diffTool->diffFormat($databaseInfo, $lastDatabaseInfo);
            $diffSql = $diffTool->diffSql($databaseInfo, $lastDatabaseInfo);
            $this->view->diffFormat = $diffFormat;
            $this->view->diffSql = $diffSql;
            $this->view->upDate = $_COOKIE["Upgrade"];
            $this->view->display('diff_upgreade');
        }else{
            $this->redirect(U('Upgrade', 'update'));
        }
    }
}