<?php

namespace controller;

use \lib\Page;
use \lib\Git;

class Log extends Base
{
    
    public function index()
    {
        $git = \lib\Git::open(GIT_DATA_PATH);
      
        $count = (int)$git->run("rev-list --all --count --no-merges -- database.sql");
        $page = new Page($count);
        $data = array();
        $logs = $git->run("log --no-merges -10 --skip={$page->pageStart} --pretty=format:'%at\x01%h\x01%p\x01%an\x01%ae\x01%s\x03' -- database.sql");
        if($logs){
            $logs = explode("\x03", trim($logs, "\x03\n"));
            foreach($logs as $v){
                $line = explode("\x01", trim($v,"\n")); 
                $data[date('Y-m-d', $line[0])][] = array(
                    'dateline' => $line[0],
                    'id' => $line[1],
                    'pid' => $line[2],
                    'name' => $line[3],
                    'email' => $line[4],
                    'message' => $line[5]
                );
            }
        }
        
        $this->view->page = $page->pageShow();
        $this->view->data = $data;
        $this->view->display('history');
    }
    
    public function details()
    {
        $git = Git::open(GIT_DATA_PATH);
        $_GET['id'] || $_GET['id'] = 'master';
        $changeSql = $git->run("show {$_GET['id']}:change.sql");
        if(!$changeSql){
            $changeSql = $git->run("show {$_GET['id']}:database.sql");
        }
        
        $changeSql = preg_replace("/\-\- Dumping data for table \`.*?\`/i", "\n", $changeSql);
        $changeSql = preg_replace("/((\-\- Modify the data table table \`.*?\`)|(\-\- Table structure for table \`.*?\`))\n/", "", $changeSql);
        $changeSql = trim($changeSql, "\n");
        $this->view->sql = $changeSql;
        $this->view->display("log_details");
    }
    
    public function diffLog()
    {
        $git = Git::open(GIT_DATA_PATH);
        $_GET['id'] || $_GET['id'] = 'master';
        $databaseInfo = (array)json_decode($git->run("show {$_GET['id']}:details.json"), true);
        try{
            $lastDatabaseInfo = (array)json_decode($git->run("show {$_GET['id']}^:details.json"), true);
        }catch (\Exception $e){
            $lastDatabaseInfo = array();
        }
        
        $diffTool = new \model\DiffTool();
        $diffFormat = $diffTool->diffFormat($lastDatabaseInfo, $databaseInfo);
        $diffSql = $diffTool->diffSql($lastDatabaseInfo, $databaseInfo);
         
        $this->view->diffFormat = $diffFormat;
        $this->view->diffSql = $diffSql;
        $this->view->display('diff_log');
    }
}