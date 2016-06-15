<?php

namespace model;

class Analyze extends Model
{
    public function getTbales()
    {
        $sth = self::$db->query("show tables");
        return $sth->fetchAll(\PDO::FETCH_COLUMN);
    }
    
    public function getCreateSql($name)
    {
        $sth = self::$db->query("SHOW CREATE TABLE {$name}");
        return $sth->fetchColumn(1);
    }
    
    public function getTableDetails($name)
    {
        $sth = self::$db->query("SHOW FULL FIELDS FROM {$name}");
        return $sth->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getTableStatus($name)
    {
        $sth = self::$db->query("SHOW TABLE STATUS FROM {$this->dbName} LIKE '{$name}'");
        return $sth->fetch(\PDO::FETCH_ASSOC); 
    }
    
    public function getDatabaseInfo()
    {
        $databaseInfo = array();
        $tables = $this->getTbales();
        foreach($tables  as $tableName){
            $createSql = $this->getCreateSql($tableName);
            $sqlInfo = $this->parseSql($createSql);
            $tableInfo = $this->getTableDetails($tableName);
            $tableFields = array();
            foreach($tableInfo as $field){
                $tableFields[$field['Field']] = array($field['Type'], $field['Collation'], $field['Null'], $field['Default'], $field['Extra'], $field['Comment'], $sqlInfo['fields'][$field['Field']]);
            }

            $tableStatus = $this->getTableStatus($tableName);
            $databaseInfo[$tableName]['fields'] = $tableFields;
            $databaseInfo[$tableName]['indexs'] = $sqlInfo['index'];
            $databaseInfo[$tableName]['sql'] = $sqlInfo['sql'];
            $databaseInfo[$tableName]['info'] = array($tableStatus['Engine'], $tableStatus['Collation'], $tableStatus['Comment'], $tableStatus['Avg_row_length'], $tableStatus['Checksum'], $tableStatus['Create_options'], $sqlInfo['info']);
        }
        
        return $databaseInfo;
    }
    
    public function parseSql($sql)
    {
        $sqlInfo = array('index'=>array(),'fields'=>array(), 'sql'=>"", "info"=>"");
        $sqlLine = explode("\n", $sql);
        foreach($sqlLine as $line){
            $line = rtrim(trim($line), ',');
            
            if($line[0] == '`'){
                preg_match("/`(.*?)`/", $line, $matches);
                if(isset($matches[1])){
                    $sqlInfo['fields'][$matches[1]] = $line;
                }
            }else if (strpos($line, "PRIMARY KEY") === 0){
                $sqlInfo['index']['_PRIMARY_KEY_'] = $line;
            }else if(strpos($line, 'UNIQUE KEY')===0 || strpos($line, 'KEY')===0){
                preg_match("/`(.*?)`/", $line, $matches);
                if(isset($matches[1])){
                   $sqlInfo['index'][$matches[1]] = $line;
                }
            }else if(strpos($line, 'CONSTRAINT') === 0){
                preg_match("/`(.*?)`/", $line, $matches);
                if(isset($matches[1])){
                    $sqlInfo['index']['_FOREIGN_KEY_'][$matches[1]] = $line;
                }
            }
        }
        
        // 去除自增部分
        $line = array_pop($sqlLine);
        $tableInfo = preg_replace("/AUTO_INCREMENT\=\d+\s+/i", "", $line);
        $sqlLine[] = $tableInfo;
        $sqlInfo['sql'] = implode("\n", $sqlLine).";";
        $sqlInfo['info'] = substr($tableInfo, 2);
        return $sqlInfo;
    }
    
    public function execute($sql)
    {
        self::$db->exec($sql);
    }
}