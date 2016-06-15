<?php

namespace lib;

use \lib\Git;
use \lib\Config;

class RepoTool
{
    
    public static function create()
    {
        if(! is_dir(LOCAL_DATA_PATH . '.git')) {
            $git = Git::create(LOCAL_DATA_PATH);
            $git->run('config user.name "'.Config::$config['GIT_USER'].'"');
            $git->run('config user.email "'.Config::$config['GIT_EMAIL'].'"');
            $git->run('config core.filemode false');
            $git->run('commit --allow-empty -m "initial"');
        }
        
        if (! is_dir(GIT_DATA_PATH . '.git')) {
           $git = Git::create(GIT_DATA_PATH, Config::$config['GIT_PATCH']);
           $git->run('config user.name "'.Config::$config['GIT_USER'].'"');
           $git->run('config user.email "'.Config::$config['GIT_EMAIL'].'"');
           $git->run("config core.filemode false");
           $git->run("config push.default simple");
           if(self::isGitEmpty()){
                $git->run('commit --allow-empty -m "initial"');
           }
        }
    }
    
    public static function isGitEmpty()
    {
        return !is_file(GIT_DATA_PATH . 'database.sql');
    }
    
    public static function isLocalEmpty()
    {
        return !is_file(LOCAL_DATA_PATH . 'database.sql');
    }
    
    public static function createPro($databaseSql, $databaseInfo, $changeSql="")
    {
       if( self::upLocalPro($databaseSql, $databaseInfo,$changeSql, "Initialize the database version.") && 
           self::upGitPro($databaseSql, $databaseInfo, $changeSql,"Initialize the database version.")){
           return true;
       }
       
       return false;
    }
    
    public static function upPro($databaseSql, $databaseInfo, $changeSql, $message)
    {
        if( self::upLocalPro($databaseSql, $databaseInfo,$changeSql, $message) &&
            self::upGitPro($databaseSql, $databaseInfo, $changeSql,$message)){
            return true;
        }
         
        return false;
    }
    
    public static function upLocalPro($databaseSql, $databaseInfo,$changeSql, $message)
    {
        $git = Git::open(LOCAL_DATA_PATH);
        if(file_put_contents(LOCAL_DATA_PATH."database.sql", $databaseSql) && file_put_contents(LOCAL_DATA_PATH."details.json", json_encode($databaseInfo)) && file_put_contents(LOCAL_DATA_PATH."change.sql", $changeSql)!==false){
            try{
                $git->add();
                $git->commit($message);
            }catch (\Exception $e){
                self::rollbacks($git);
                throw new \Exception($e->getMessage());
            }
            return true;
        }
        
        self::rollbacks($git);
        return false;
    }
    
    public static function upGitPro($databaseSql, $databaseInfo,$changeSql, $message)
    {
        $git = Git::open(GIT_DATA_PATH);
        if(file_put_contents(GIT_DATA_PATH."database.sql", $databaseSql) && file_put_contents(GIT_DATA_PATH."details.json", json_encode($databaseInfo)) && file_put_contents(GIT_DATA_PATH."change.sql", $changeSql)!==false){
            try{
                $git->add();
                $git->commit($message);
            }catch (\Exception $e){
                self::rollbacks($git, true);
                throw new \Exception($e->getMessage());
            }
            
            try{
                $commitId = $git->run("rev-parse HEAD");
                file_put_contents(LOCAL_DATA_PATH."lastcommit", $commitId);
                $git->push(Config::$config['GIT_PATCH'], 'master');
            }catch (\Exception $e){
                self::rollbacks($git, true, true);
                throw new \Exception($e->getMessage());
            }
            return true;
        }
        
        self::rollbacks($git, true);
        return false;
    }
    
    public static function localSync()
    {
        $git = Git::open(LOCAL_DATA_PATH);
        $commitId = Git::open(GIT_DATA_PATH)->run("rev-parse HEAD");
        if(copy(GIT_DATA_PATH.'database.sql', LOCAL_DATA_PATH.'database.sql') && copy(GIT_DATA_PATH.'details.json', LOCAL_DATA_PATH.'details.json') && copy(GIT_DATA_PATH.'change.sql', LOCAL_DATA_PATH.'change.sql') && file_put_contents(LOCAL_DATA_PATH."lastcommit", $commitId)){
            try{
                $git->add();
                $git->commit("Initialize the database version.");
            }catch (\Exception $e){
                self::rollbacks($git);
                throw new \Exception($e->getMessage());
            }
            return true;
        }
        
        self::rollbacks($git);
        return false;
    }
    
    public static function pull()
    {
        Git::open(GIT_DATA_PATH)->run('pull');
    }
    
    public static function fall()
    {
        $git = Git::open(GIT_DATA_PATH);
        $tmp = file(LOCAL_DATA_PATH."lastcommit", FILE_IGNORE_NEW_LINES);
        $lastCommit = reset($tmp);
        $count = (int)$git->run("rev-list --count --no-merges ".$git->run("rev-parse HEAD"));
        $lastCount = (int)$git->run("rev-list --count --no-merges {$lastCommit}");
        return $count - $lastCount > 0 ? $count-$lastCount : 0;
    }
    
    protected static function rollbacks($git, $isRelated=false,$isParent=false)
    {
        $git->run("reset --hard HEAD".($isParent?"^":""));
        $git->clean(true, true);
        
        $isRelated && Git::open(LOCAL_DATA_PATH)->run("reset --hard HEAD^");
    }
}