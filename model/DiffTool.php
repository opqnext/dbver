<?php

namespace model;

use controller\Index;
class DiffTool 
{
    public function diffFormat($reference, $new)
    {
        $diffFormat = array("old"=>array(),"new"=>array(), "tables"=>array());
        
        $addTable = array_diff_key((array)$new, (array)$reference);
        foreach ($addTable as $table=>$info){
            foreach ($info['fields'] as $name=>$fieldInfo){
                $diffFormat['old'][$table][] = null;
                $diffFormat['new'][$table][] = $fieldInfo[6];
            }
            
            $indexs = $info['indexs'];
            unset($indexs['_FOREIGN_KEY_']);
            foreach($indexs as $name => $indexInfo){
                $diffFormat['old'][$table][] = null;
                $diffFormat['new'][$table][] = $indexInfo;
            }
            
            foreach ($info['indexs']['_FOREIGN_KEY_'] as $name=>$indexInfo){
                $diffFormat['old'][$table][] = null;
                $diffFormat['new'][$table][] = $indexInfo;
            }
            
            $diffFormat['old'][$table][] = null;
            $diffFormat['new'][$table][] = $info['info'][6];
            $diffFormat['tables'][$table] = $info['info'][2];
            unset($new[$table]);
        }
        
        foreach ($reference as $table=>$info){
            $newTables = &$new[$table];
            $diffFormat['tables'][$table]  = $newTables ? $newTables['info'][2] : $info['info'][2];
            
            // 字段差异对比
            $dropFields = array_diff_key((array)$info['fields'], (array)$newTables['fields']);
            foreach($dropFields as $name => $fieldInfo)
            {
                $diffFormat['old'][$table][] = $fieldInfo[6];
                $diffFormat['new'][$table][] = null;
            }
            
            $laseField = "";
            foreach($newTables['fields'] as $name => $fieldInfo){
                if(!isset($info['fields'][$name])){
                    $diffFormat['old'][$table][] = null;
                    $diffFormat['new'][$table][] = $fieldInfo[6];
                }else if($fieldInfo[6] != $info['fields'][$name][6]){
                    $diffFormat['old'][$table][] = $info['fields'][$name][6];
                    $diffFormat['new'][$table][] = $fieldInfo[6];
                }
            }
            
            // 对比索引
            $oldIndexs = (array)$info['indexs'];
            $newIndexs = (array)$newTables['indexs'];
            unset($oldIndexs['_FOREIGN_KEY_'], $newIndexs['_FOREIGN_KEY_']);
            
            $dropIndexs = array_diff_key($oldIndexs, $newIndexs);
            foreach($dropIndexs as $name => $indexInfo){
                $diffFormat['old'][$table][] = $indexInfo;
                $diffFormat['new'][$table][] = null;
            }
            
            foreach($newIndexs as $name => $indexInfo){
                if(!isset($info['indexs'][$name])){                    
                    $diffFormat['old'][$table][] = null;
                    $diffFormat['new'][$table][] = $indexInfo;
                }else if($indexInfo != $info['indexs'][$name]){
                    $diffFormat['old'][$table][] = $info['indexs'][$name];
                    $diffFormat['new'][$table][] = $indexInfo;
                }
            }
            
            // 对比外键
            $oldForeignKeys = (array)$info['indexs']['_FOREIGN_KEY_'];
            $newForeignKeys = (array)$newTables['indexs']['_FOREIGN_KEY_'];
            $dropIndexs = array_diff_key($oldForeignKeys, $newForeignKeys);
            foreach($dropIndexs as $name => $indexInfo){
                $diffFormat['old'][$table][] = $indexInfo;
                $diffFormat['new'][$table][] = null;
            }
            
            foreach($newForeignKeys as $name => $indexInfo){
                if(!isset($oldForeignKeys[$name])){
                    $diffFormat['old'][$table][] = null;
                    $diffFormat['new'][$table][] = $indexInfo;
                }else if($indexInfo != $oldForeignKeys[$name]){
                    $diffFormat['old'][$table][] = $oldForeignKeys[$name];
                    $diffFormat['new'][$table][] = $indexInfo;
                }
            }
            
            // 对比表信息
            if($info['info'][6] != $newTables['info'][6]){
                $diffFormat['old'][$table][] = $info['info'][6];
                $diffFormat['new'][$table][] = $newTables['info'][6];
            }
        }
        ksort($diffFormat['old']);
        ksort($diffFormat['new']);
        return $diffFormat;
    }
    
    public function diffSql($reference, $new)
    {
        $diffSql = array();
        
        $dropTable = array_diff_key((array)$reference, (array)$new);
        foreach($dropTable as $table=>$info){
            $diffSql[$table] = "DROP TABLE `{$table}`;";
            unset($reference[$table]);
        }
        
        $addTable = array_diff_key((array)$new, (array)$reference);
        foreach ($addTable as $table=>$info){
            $diffSql[$table] = $info['sql'];
            unset($new[$table]);
        }

        foreach ($reference as $table => $info){
            $newTables = &$new[$table];
            $alterSql = array("ALTER TABLE `{$table}`");
        
            // 字段差异对比
            $dropFields = array_diff_key((array)$info['fields'], (array)$newTables['fields']);
            foreach($dropFields as $name => $fieldInfo)
            {
                $alterSql[] = " DROP COLUMN `{$name}`";
            }
        
            $laseField = "";
            foreach($newTables['fields'] as $name => $fieldInfo){
                if(!isset($info['fields'][$name])){
                    $alterSql[] = " ADD COLUMN {$fieldInfo[6]}".($laseField?" AFTER `{$laseField}`":" FIRST");
                }else if($fieldInfo[6] != $info['fields'][$name][6]){
                    $alterSql[] = " CHANGE `{$name}` {$fieldInfo[6]}";
                }
                $laseField = $name;
            }
        
            // 对比索引
            $oldIndexs = (array)$info['indexs'];
            $newIndexs = (array)$newTables['indexs'];
            unset($oldIndexs['_FOREIGN_KEY_'], $newIndexs['_FOREIGN_KEY_']);
        
            $dropIndexs = array_diff_key($oldIndexs, $newIndexs);
            foreach($dropIndexs as $name => $indexInfo){
                if(strpos($indexInfo, 'UNIQUE KEY')===0 || strpos($indexInfo, 'KEY')===0){
                    $alterSql[] = " DROP INDEX `{$name}`";
                }else if(strpos($indexInfo, "PRIMARY KEY") === 0){
                    $alterSql[] = " DROP PRIMARY KEY";
                }
            }
        
            foreach($newIndexs as $name => $indexInfo){
                if(!isset($info['indexs'][$name])){
                    if( strpos($indexInfo, 'KEY')===0){
                        $alterSql[] = " ADD INDEX ".substr($indexInfo, 3);
                    }else if(strpos($indexInfo, 'UNIQUE KEY')===0){
                        $alterSql[] = " ADD UNIQUE INDEX ".substr($indexInfo, 10);
                    }else if(strpos($indexInfo, "PRIMARY KEY") === 0){
                        $alterSql[] = " ADD {$indexInfo}";
                    }
                }else if($indexInfo != $info['indexs'][$name]){
                    if( strpos($indexInfo, 'KEY')===0){
                        $alterSql[] = " DROP INDEX `{$name}`";
                        $alterSql[] = " ADD INDEX ".substr($indexInfo, 3);
                    }else if(strpos($indexInfo, 'UNIQUE KEY')===0){
                        $alterSql[] = " DROP INDEX `{$name}`";
                        $alterSql[] = " ADD UNIQUE INDEX ".substr($indexInfo, 10);
                    }else if(strpos($indexInfo, "PRIMARY KEY") === 0){
                        $alterSql[] = " DROP PRIMARY KEY";
                        $alterSql[] = " ADD {$indexInfo}";
                    }
                }
            }
        
            // 对比外键
            $oldForeignKeys = (array)$info['indexs']['_FOREIGN_KEY_'];
            $newForeignKeys = (array)$newTables['indexs']['_FOREIGN_KEY_'];
            $dropIndexs = array_diff_key($oldForeignKeys, $newForeignKeys);
            foreach($dropIndexs as $name => $indexInfo){
                $alterSql[] = " DROP FOREIGN KEY `{$name}`";
            }
        
            foreach($newForeignKeys as $name => $indexInfo){
                if(!isset($oldForeignKeys[$name])){
                    $alterSql[] = " ADD `{$indexInfo}`";
                }else if($indexInfo != $oldForeignKeys[$name]){
                    $alterSql[] = " DROP FOREIGN KEY `{$name}`";
                    $alterSql[] = " ADD `{$indexInfo}`";
                }
            }
        
            // 对比表信息
            if($info['info'][6] != $newTables['info'][6]){
                $alterSql[] = " {$newTables['info'][6]}";
            }
        
            if(isset($alterSql[1])){
                $first = array_shift($alterSql);
                $diffSql[$table] = $first."\n".implode(",\n", $alterSql).";";
            }
        }
        
        ksort($diffSql);
        return $diffSql;
    }
    
    public function diffEdit($refer, $data1, $data2)
    {
        $data = array("remote"=>array(), "local"=>array(), "my_sql"=>array(), "it_sql"=>array(), "clash"=>false, 'tables'=>array());
        
        $this->dropDiff($refer, $data1, $data2, $data);
        $this->addDiff($refer, $data1, $data2, $data);
        
        foreach ($data2 as $table=>$info){
            
            $localTable = &$data1[$table];
            $referTable = &$refer[$table];
            if($localTable == $info){
                continue;
            }
            
            $data['tables'][$table]['name']  = $localTable ? $localTable['info'][2] : $info['info'][2];
            $alterSql = array("ALTER TABLE `{$table}`");
            $myAlterSql = array("ALTER TABLE `{$table}`");
            
            // 字段差异对比
            $dropFields = array_diff_key((array)$referTable['fields'], (array)$info['fields']);
            foreach($dropFields as $name => $fieldInfo){
                if(!isset($localTable['fields'][$name])){
                    $data['remote'][$table][] = array($fieldInfo[6],'-', 'equal');
                    $data['local'][$table][] = array($fieldInfo[6],'-', 'equal');
                }else if($fieldInfo[6] != $localTable['fields'][$name][6]){
                    $data['remote'][$table][] = array($fieldInfo[6],'-', 'clash');
                    $data['local'][$table][] = array($localTable['fields'][$name][6],'☷', 'clash');
                    $data['tables'][$table]['clash'] = true;
                    $alterSql[] = " DROP COLUMN `{$name}`";
                }else{
                    $data['remote'][$table][] = array($fieldInfo[6],'-', 'keep');
                    $data['local'][$table][] = array($localTable['fields'][$name][6],'', 'keep');
                    $myAlterSql[] = $alterSql[] = " DROP COLUMN `{$name}`";
                }
            }
            
            $laseField = "";
            foreach($info['fields'] as $name => $fieldInfo){
                if(!isset($referTable['fields'][$name])){
                    
                    if(!isset($localTable['fields'][$name])){
                        $data['remote'][$table][] = array($fieldInfo[6],'+', 'keep');
                        $data['local'][$table][] = array("",'', 'keep');
                        $myAlterSql[] = $alterSql[] = " ADD COLUMN {$fieldInfo[6]}".($laseField?" AFTER `{$laseField}`":" FIRST");
                    }else if($fieldInfo[6] != $localTable['fields'][$name][6]){
                        $data['remote'][$table][] = array($fieldInfo[6],'+', 'clash');
                        $data['local'][$table][] = array($localTable['fields'][$name][6],'+', 'clash');
                        $data['tables'][$table]['clash'] = true;
                        $alterSql[] = " CHANGE `{$name}` {$fieldInfo[6]}";
                    }else{
                        $data['remote'][$table][] = array($fieldInfo[6],'+', 'equal');
                        $data['local'][$table][] = array($localTable['fields'][$name][6],'+', 'equal');
                    }
                    
                }else if($fieldInfo[6] != $referTable['fields'][$name][6]){

                    if(!isset($localTable['fields'][$name])){
                        $data['remote'][$table][] = array($fieldInfo[6],'☷', 'clash');
                        $data['local'][$table][] = array($referTable['fields'][$name][6],'-', 'clash');
                        $data['tables'][$table]['clash'] = true;
                        $alterSql[] = " ADD COLUMN {$fieldInfo[6]}".($laseField?" AFTER `{$laseField}`":" FIRST");
                    }else if($referTable['fields'][$name][6] == $localTable['fields'][$name][6]){
                        $data['remote'][$table][] = array($fieldInfo[6],'☷', 'keep');
                        $data['local'][$table][] = array($referTable['fields'][$name][6],'', 'keep');
                        $myAlterSql[] = $alterSql[] = " CHANGE `{$name}` {$fieldInfo[6]}";
                    }else if($localTable['fields'][$name][6] != $fieldInfo[6]){
                        $data['remote'][$table][] = array($fieldInfo[6],'☷', 'clash');
                        $data['local'][$table][] = array($localTable['fields'][$name][6],'☷', 'clash');
                        $data['tables'][$table]['clash'] = true;
                        $alterSql[] = " CHANGE `{$name}` {$fieldInfo[6]}";
                    }else{
                        $data['remote'][$table][] = array($fieldInfo[6],'☷', 'equal');
                        $data['local'][$table][] = array($localTable['fields'][$name][6],'☷', 'equal');
                    }
                }
                
                $laseField = $name;
            }
            
            $referIndex = (array)$referTable['indexs'];
            $remoteIndex = (array)$info['indexs'];
            unset($referIndex['_FOREIGN_KEY_'], $remoteIndex['_FOREIGN_KEY_']);
            $dropIndexs = array_diff_key($referIndex, $remoteIndex);
            foreach ($dropIndexs as $name => $indexInfo){
                if(!isset($localTable['indexs'][$name])){
                    $data['remote'][$table][] = array($indexInfo,'-', 'equal');
                    $data['local'][$table][] = array($indexInfo,'-', 'equal');
                }else if($indexInfo != $localTable['indexs'][$name]){
                    $data['remote'][$table][] = array($indexInfo,'-', 'clash');
                    $data['local'][$table][] = array($localTable['indexs'][$name],'☷', 'clash');
                    $data['tables'][$table]['clash'] = true;
                    $alterSql[] = $this->dropIndex($name, $indexInfo);
                }else{
                    $data['remote'][$table][] = array($indexInfo,'-', 'keep');
                    $data['local'][$table][] = array($localTable['indexs'][$name],'', 'keep');
                    $myAlterSql[] = $alterSql[] = $this->dropIndex($name, $indexInfo);
                }
            }
            
            foreach ($remoteIndex as $name => $indexInfo){
                if(!isset($referTable['indexs'][$name])){
                
                    if(!isset($localTable['indexs'][$name])){
                        $data['remote'][$table][] = array($indexInfo,'+', 'keep');
                        $data['local'][$table][] = array("",'', 'keep');
                        $myAlterSql[] = $alterSql[] = $this->addIndex($indexInfo);
                    }else if($indexInfo != $localTable['indexs'][$name]){
                        $data['remote'][$table][] = array($indexInfo,'+', 'clash');
                        $data['local'][$table][] = array($localTable['indexs'][$name],'+', 'clash');
                        $data['tables'][$table]['clash'] = true;
                        $alterSql[] = $this->dropIndex($name, $localTable['indexs'][$name]);
                        $alterSql[] = $this->addIndex($indexInfo);
                    }else{
                        $data['remote'][$table][] = array($indexInfo,'+', 'equal');
                        $data['local'][$table][] = array($indexInfo,'+', 'equal');
                    }
                
                }else if($indexInfo != $referTable['indexs'][$name]){
                
                    if(!isset($localTable['indexs'][$name])){
                        $data['remote'][$table][] = array($indexInfo,'☷', 'clash');
                        $data['local'][$table][] = array($referTable['indexs'][$name],'-', 'clash');
                        $data['tables'][$table]['clash'] = true;
                        $alterSql[] = $this->addIndex($indexInfo);
                    }else if($referTable['indexs'][$name] == $localTable['indexs'][$name]){
                        $data['remote'][$table][] = array($indexInfo,'☷', 'keep');
                        $data['local'][$table][] = array($localTable['indexs'][$name],'', 'keep');
                        $myAlterSql[] = $alterSql[] = $this->dropIndex($name, $localTable['indexs'][$name]);
                        $myAlterSql[] = $alterSql[] = $this->addIndex($indexInfo);
                    }else if($localTable['indexs'][$name] != $indexInfo){
                        $data['remote'][$table][] = array($indexInfo,'☷', 'clash');
                        $data['local'][$table][] = array($localTable['indexs'][$name],'☷', 'clash');
                        $data['tables'][$table]['clash'] = true;
                        $alterSql[] = $this->dropIndex($name, $localTable['indexs'][$name]);
                        $alterSql[] = $this->addIndex($indexInfo);
                    }else{
                        $data['remote'][$table][] = array($indexInfo,'☷', 'equal');
                        $data['local'][$table][] = array($localTable['indexs'][$name],'☷', 'equal');
                    }
                }
            }
            
            // 对比外键
            $referForeignKeys = (array)$referTable['indexs']['_FOREIGN_KEY_'];
            $remoteForeignKeys = (array)$info['indexs']['_FOREIGN_KEY_'];
            $dropIndexs = array_diff_key($referForeignKeys, $remoteForeignKeys);
            foreach($dropIndexs as $name => $indexInfo){
                if(!isset($localTable['indexs']['_FOREIGN_KEY_'][$name])){
                    $data['remote'][$table][] = array($indexInfo,'-', 'equal');
                    $data['local'][$table][] = array($indexInfo,'-', 'equal');
                }else if($indexInfo != $localTable['indexs']['_FOREIGN_KEY_'][$name]){
                    $data['remote'][$table][] = array($indexInfo,'-', 'clash');
                    $data['local'][$table][] = array($localTable['indexs']['_FOREIGN_KEY_'][$name],'☷', 'clash');
                    $data['tables'][$table]['clash'] = true;
                    $alterSql[] = " DROP FOREIGN KEY `{$name}`";
                }else{
                    $data['remote'][$table][] = array($indexInfo,'-', 'keep');
                    $data['local'][$table][] = array($localTable['indexs']['_FOREIGN_KEY_'][$name],'', 'keep');
                    $myAlterSql[] = $alterSql[] = " DROP FOREIGN KEY `{$name}`";
                }
            }
            
            foreach ($remoteForeignKeys as $name => $indexInfo){
                if(!isset($referForeignKeys[$name])){
            
                    if(!isset($localTable['indexs']['_FOREIGN_KEY_'][$name])){
                        $data['remote'][$table][] = array($indexInfo,'+', 'keep');
                        $data['local'][$table][] = array("",'', 'keep');
                        $myAlterSql[] = $alterSql[] = " ADD `{$indexInfo}`";
                    }else if($indexInfo != $localTable['indexs']['_FOREIGN_KEY_'][$name]){
                        $data['remote'][$table][] = array($indexInfo,'+', 'clash');
                        $data['local'][$table][] = array($localTable['indexs']['_FOREIGN_KEY_'][$name],'+', 'clash');
                        $data['tables'][$table]['clash'] = true;
                        $alterSql[] = " DROP FOREIGN KEY `{$name}`";
                        $alterSql[] = " ADD `{$indexInfo}`";
                    }else{
                        $data['remote'][$table][] = array($indexInfo,'+', 'equal');
                        $data['local'][$table][] = array($indexInfo,'+', 'equal');
                    }
            
                }else if($indexInfo != $referForeignKeys[$name]){
            
                    if(!isset($localTable['indexs']['_FOREIGN_KEY_'][$name])){
                        $data['remote'][$table][] = array($indexInfo,'☷', 'clash');
                        $data['local'][$table][] = array($referTable['indexs']['_FOREIGN_KEY_'][$name],'-', 'clash');
                        $data['tables'][$table]['clash'] = true;
                        $alterSql[] = " ADD `{$indexInfo}`";
                    }else if($referForeignKeys[$name] == $localTable['indexs']['_FOREIGN_KEY_'][$name]){
                        $data['remote'][$table][] = array($indexInfo,'☷', 'keep');
                        $data['local'][$table][] = array($localTable['indexs']['_FOREIGN_KEY_'][$name],'', 'keep');
                        $myAlterSql[] = $alterSql[] = " DROP FOREIGN KEY `{$name}`";
                        $myAlterSql[] = $alterSql[] = " ADD `{$indexInfo}`";
                    }else if($localTable['indexs']['_FOREIGN_KEY_'][$name] != $indexInfo){
                        $data['remote'][$table][] = array($indexInfo,'☷', 'clash');
                        $data['local'][$table][] = array($localTable['indexs']['_FOREIGN_KEY_'][$name],'☷', 'clash');
                        $data['tables'][$table]['clash'] = true;
                        $alterSql[] = " DROP FOREIGN KEY `{$name}`";
                        $alterSql[] = " ADD `{$indexInfo}`";
                    }else{
                        $data['remote'][$table][] = array($indexInfo,'☷', 'equal');
                        $data['local'][$table][] = array($localTable['indexs']['_FOREIGN_KEY_'][$name],'☷', 'equal');
                    }
                }
            }
            
            // 对比表信息
           if($info['info'][6] != $referTable['info'][6]){
               if($localTable['info'][6] == $referTable['info'][6]){
                   $data['remote'][$table][] = array($info['info'][6],'☷', 'keep');
                   $data['local'][$table][] = array($localTable['info'][6],'', 'keep');
                   $myAlterSql[] = $alterSql[] = " {$info['info'][6]}";
               }else if($localTable['info'][6] != $info['info'][6]){
                   $data['remote'][$table][] = array($info['info'][6],'☷', 'clash');
                   $data['local'][$table][] = array($localTable['info'][6],'☷', 'clash');
                   $data['tables'][$table]['clash'] = true;
                   $alterSql[] = " {$info['info'][6]}";
               }else {
                   $data['remote'][$table][] = array($info['info'][6],'☷', 'equal');
                   $data['local'][$table][] = array($localTable['info'][6],'☷', 'equal');
               }
           }
           $data['tables'][$table]['clash'] && $data['clash'] = true;
           
           if(!$localTable){
               $data['my_sql'][$table] = "";
               $data['it_sql'][$table] = $info['sql'];
           }else{
               $data['it_sql'][$table] = "";
               if(isset($alterSql[1])){
                   $first = array_shift($alterSql);
                   $data['it_sql'][$table] = $first."\n".implode(",\n", $alterSql).";";
               }
               
               $data['my_sql'][$table] = "";
               if(isset($myAlterSql[1])){
                   $first = array_shift($myAlterSql);
                   $data['my_sql'][$table] = $first."\n".implode(",\n", $myAlterSql).";";
               }
           }
           
        }
        
        ksort($data['remote']);
        ksort($data['local']);
        return $data;
    }
    
    public function dropDiff(&$refer, &$data1, &$data2, &$data)
    {
        $dropTable = array_diff_key((array)$refer, (array)$data2);
        foreach($dropTable as $table=>$info){
            if(!isset($data1[$table])){
               continue;
            }
            
            $localTable = &$data1[$table];
            $data['tables'][$table]['name']  = $localTable ? $localTable['info'][2] : $info['info'][2];
            
            // 字段差异对比
            $dropFields = array_diff_key((array)$info['fields'], (array)$localTable['fields']);
            foreach($dropFields as $name => $fieldInfo)
            {
                $data['remote'][$table][] = array($fieldInfo[6],'-', 'equal');
                $data['local'][$table][] = array($fieldInfo[6],'-', 'equal');
            }
            
            foreach($localTable['fields'] as $name => $fieldInfo){
                if(!isset($info['fields'][$name])){
                    $data['remote'][$table][] = array("", "?", "clash");
                    $data['local'][$table][] = array($fieldInfo[6], "+", 'clash');
                    $data['tables'][$table]['clash'] = true;
                }else if($fieldInfo[6] != $info['fields'][$name][6]){
                    $data['remote'][$table][] = array($info['fields'][$name][6], "-", 'clash');
                    $data['local'][$table][] = array($fieldInfo[6], "☷", "clash");
                    $data['tables'][$table]['clash'] = true;
                }else{
                    $data['remote'][$table][] = array($info['fields'][$name][6], "-", 'keep');
                    $data['local'][$table][] = array($fieldInfo[6], "", "keep");
                }
            }
            
            // 对比索引
            $referIndexs = (array)$info['indexs'];
            $localIndexs = (array)$localTable['indexs'];
            unset($referIndexs['_FOREIGN_KEY_'], $localIndexs['_FOREIGN_KEY_']);
            $dropIndexs = array_diff_key($referIndexs, $localIndexs);
            foreach($dropIndexs as $name => $indexInfo){
                $data['remote'][$table][] = array($indexInfo, '-', 'equal');
                $data['local'][$table][] = array($indexInfo, '-', 'equal');
            }
            
            foreach($localIndexs as $name => $indexInfo){
                if(!isset($info['indexs'][$name])){
                    $data['remote'][$table][] = array("","?","clash");
                    $data['local'][$table][] = array($indexInfo, "+", "clash");
                    $data['tables'][$table]['clash'] = true;
                }else if($indexInfo != $info['indexs'][$name]){
                    $data['remote'][$table][] = array($info['indexs'][$name], "-", "clash");
                    $data['local'][$table][] = array($indexInfo, "☷", "clash");
                    $data['tables'][$table]['clash'] = true;
                }else{
                    $data['remote'][$table][] = array($info['indexs'][$name], "-", "keep");
                    $data['local'][$table][] = array($indexInfo, "", "keep");
                }
            }
            
            // 对比外键
            $referForeignKeys = (array)$info['indexs']['_FOREIGN_KEY_'];
            $localForeignKeys = (array)$localTable['indexs']['_FOREIGN_KEY_'];
            $dropIndexs = array_diff_key($referForeignKeys, $localForeignKeys);
            foreach($dropIndexs as $name => $indexInfo){
                $data['remote'][$table][] = array($indexInfo, '-', 'equal');
                $data['local'][$table][] = array($indexInfo, '-', 'equal');
            }
            
            foreach($localForeignKeys as $name => $indexInfo){
                if(!isset($referForeignKeys[$name])){
                    $data['remote'][$table][] = array("","?","clash");
                    $data['local'][$table][] = array($indexInfo, "+", "clash");
                    $data['tables'][$table]['clash'] = true;
                }else if($indexInfo != $referForeignKeys[$name]){
                    $data['remote'][$table][] = array($referForeignKeys[$name], "-", "clash");
                    $data['local'][$table][] = array($indexInfo, "☷", "clash");
                    $data['tables'][$table]['clash'] = true;
                }else{
                    $data['remote'][$table][] = array($referForeignKeys[$name], "-", "keep");
                    $data['local'][$table][] = array($indexInfo, "", "keep");
                }
            }
            
            // 对比表信息
            if($info['info'][6] != $localTable['info'][6]){
                $data['remote'][$table][] = array($info['info'][6], "-", "clash");
                $data['local'][$table][] = array($localTable['info'][6], "☷", "clash");
                $data['tables'][$table]['clash'] = true;
            }else{
                $data['remote'][$table][] = array($info['info'][6], "-", "keep");
                $data['local'][$table][] = array($localTable['info'][6], "", "keep");
            }
            
            $data['tables'][$table]['clash'] && $data['clash'] = true;
            $data['it_sql'][$table] = "DROP TABLE `{$table}`;";
            $data['my_sql'][$table] = "";
            unset($refer[$table], $data1[$table]);
        }
    }
    
    public function addDiff(&$refer, &$data1, &$data2, &$data)
    {
        $addTable = array_diff_key((array)$data2, (array)$refer);
        foreach ($addTable as $table=>$info){
            $localTable = &$data1[$table];
            if($localTable == $info){
                continue;
            }
            $data['tables'][$table]['name']  = $localTable ? $localTable['info'][2] : $info['info'][2];
            
            $alterSql = array("ALTER TABLE `{$table}`");
            $myAlterSql = array("ALTER TABLE `{$table}`");
            
            $laseField = "";
            foreach($info['fields'] as $name => $fieldInfo){
                if(!isset($localTable['fields'][$name])){
                    $data['remote'][$table][] = array($fieldInfo[6], "+", "keep");
                    $data['local'][$table][] = array("", "", "keep");
                    $myAlterSql[] = $alterSql[] = " ADD COLUMN {$fieldInfo[6]}".($laseField?" AFTER `{$laseField}`":" FIRST");
                }else if($fieldInfo[6] != $localTable['fields'][$name][6]){
                    $data['remote'][$table][] = array($fieldInfo[6], "+", "clash");
                    $data['local'][$table][] = array($localTable['fields'][$name][6], "+", "clash");
                    $data['tables'][$table]['clash'] = true;
                    $alterSql[] = " CHANGE `{$name}` {$fieldInfo[6]}";
                }else{
                    $data['remote'][$table][] = array($fieldInfo[6], "+", "equal");
                    $data['local'][$table][] = array($localTable['fields'][$name][6], "+", "equal");
                }
                $laseField = $name;
            }
            
            $indexs = $info['indexs'];
            unset($indexs['_FOREIGN_KEY_']);
            foreach($indexs as $name => $indexInfo){
                if(!isset($localTable['indexs'][$name])){
                    $data['remote'][$table][] = array($indexInfo, "+", "keep");
                    $data['local'][$table][] = array("", "", "keep");
                    $myAlterSql[] = $alterSql[] = $this->addIndex($indexInfo);
                }else if($indexInfo != $localTable['indexs'][$name]){
                    $data['remote'][$table][] = array($indexInfo, "+", "clash");
                    $data['local'][$table][] = array($localTable['indexs'][$name], "+", "clash");
                    $data['tables'][$table]['clash'] = true;
                    $alterSql[] = $this->dropIndex($name, $indexInfo);
                    $alterSql[] = $this->addIndex($indexInfo);
                }else{
                    $data['remote'][$table][] = array($indexInfo, "+", "equal");
                    $data['local'][$table][] = array($localTable['indexs'][$name], "+", "equal");
                }
            }
            
            foreach ($info['indexs']['_FOREIGN_KEY_'] as $name=>$indexInfo){
                if(!isset($localTable['indexs']['_FOREIGN_KEY_'][$name])){
                    $data['remote'][$table][] = array($indexInfo, "+", "keep");
                    $data['local'][$table][] = array("", "", "keep");
                    $myAlterSql[] = $alterSql[] = " ADD `{$indexInfo}`";
                }else if(!$indexInfo != $localTable['indexs']['_FOREIGN_KEY_'][$name]){
                    $data['remote'][$table][] = array($indexInfo, "+", "clash");
                    $data['local'][$table][] = array($localTable['indexs']['_FOREIGN_KEY_'][$name], "+", "clash");
                    $data['tables'][$table]['clash'] = true;
                    $alterSql[] = " DROP FOREIGN KEY `{$name}`";
                    $alterSql[] = " ADD `{$indexInfo}`";
                }else {
                    $data['remote'][$table][] = array($indexInfo, "+", "equal");
                    $data['local'][$table][] = array($localTable['indexs']['_FOREIGN_KEY_'][$name], "+", "equal");
                }
            }
            
            if(!isset($localTable['info'][6])){
                $data['remote'][$table][] = array($info['info'][6], "+", "keep");
                $data['local'][$table][] = array("", "", "keep");
                $myAlterSql[] = $alterSql[] = " {$info['info'][6]}";
            }else if($info['info'][6] != $localTable['info'][6]){
                $data['remote'][$table][] = array($info['info'][6], "+", "clash");
                $data['local'][$table][] = array($localTable['info'][6], "+", "clash");
                $data['tables'][$table]['clash'] = true;
                $alterSql[] = " {$info['info'][6]}";
            }else{
                $data['remote'][$table][] = array($info['info'][6], "+", "equal");
                $data['local'][$table][] = array($localTable['info'][6], "+", "equal");
            }
                        
            if(!isset($data1[$table])){
                $data['it_sql'][$table] = $info['sql'];
                $data['my_sql'][$table] = "";
            }else{
                $data['it_sql'][$table] = "";
                $data['my_sql'][$table] = "";
                if(isset($alterSql[1])){
                    $first = array_shift($alterSql);
                    $data['it_sql'][$table] = $first."\n".implode(",\n", $alterSql).";";
                }
                
                if(isset($myAlterSql[1])){
                    $first = array_shift($myAlterSql);
                    $data['my_sql'][$table] = $first."\n".implode(",\n", $myAlterSql).";";
                }
            }
            $data['tables'][$table]['clash'] && $data['clash'] = true;
            unset($data1[$table], $data2[$table]);
        }
    }
    
    protected function dropIndex($name, $info){
        if(strpos($info, 'UNIQUE KEY')===0 || strpos($info, 'KEY')===0){
            return " DROP INDEX `{$name}`";
        }else if(strpos($info, "PRIMARY KEY") === 0){
            return " DROP PRIMARY KEY";
        }
    }
    
    protected function addIndex($info){
        if( strpos($info, 'KEY')===0){
            return " ADD INDEX ".substr($info, 3);
        }else if(strpos($info, 'UNIQUE KEY')===0){
            return " ADD UNIQUE INDEX ".substr($info, 10);
        }else if(strpos($info, "PRIMARY KEY") === 0){
            return " ADD {$info}";
        }
    }
}