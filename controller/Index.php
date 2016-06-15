<?php

namespace controller;

use lib\Git;
class Index extends Base
{
    public function index()
    {
        $jsonData = file_get_contents(GIT_DATA_PATH.'details.json');
        $this->view->details = json_decode($jsonData, true);
        $this->view->display('index');
    }
    
    
    public function dictionary()
    {
        $git = Git::open(GIT_DATA_PATH);
        $_GET['id'] || $_GET['id'] = 'master';
        $jsonData = $git->run("show {$_GET['id']}:details.json");
        
        $commitInfo = array();
        $logs = $git->run("log --no-merges -1 --skip={$page->pageStart} --pretty=format:'%at\x01%h\x01%p\x01%an\x01%ae\x01%s\x03' {$_GET['id']}");
        if($logs){
            $logs = explode("\x03", trim($logs, "\x03\n"));
            foreach($logs as $v){
                $line = explode("\x01", trim($v,"\n"));
                $commitInfo = array(
                    'dateline' => $line[0],
                    'id' => $line[1],
                    'pid' => $line[2],
                    'name' => $line[3],
                    'email' => $line[4],
                    'message' => $line[5]
                );
                break;
            }
        }
        
        $this->view->commitInfo = $commitInfo;
        $this->view->details = json_decode($jsonData, true);
        $this->view->display('index');
    }
}