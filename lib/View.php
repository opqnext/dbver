<?php

namespace lib;

class View 
{
    protected $var;
    
    public function display($fileName)
    {
        include T.$fileName.'.php';
        exit;
    }
    
    public function import($fileName)
    {
        include T.$fileName.'.php';
    }
    
    public function __get($name)
    {
        return $this->var[$name];
    }
    
    public function __set($name, $value)
    {
        $this->var[$name] = $value;
    }
}