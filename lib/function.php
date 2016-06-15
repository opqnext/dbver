<?php

function U($c, $a, $params=array(), $isRetain=false)
{
    $params['c'] = $c;
    $params['a'] = $a;
    
    if($isRetain){
        $params = array_merge($params, $_GET);
    }
    
    return 'index.php?'.http_build_query($params);
}

function getTimeFormatText($time)
{
    static $minute,$hour,$day,$month,$year;
    $minute = 60;
    $hour = 60 * $minute;
    $day = 12 * $hour;
    $month = 30 * $day;
    $year = 12 * $month;
    
    $diff = time() - $time;
    if($diff > $year){
        return date("Y-m-d H:i:s", $time);
    }
    
    if($diff > $month){
        return floor($diff/$month)."月之前";
    }
    
    if($diff > $day){
        return floor($diff/$day)."天之前";
    }
    
    if($diff > $hour){
        return floor($diff/$hour)."小时之前";
    }
    
    if($diff > $minute){
        return floor($diff/$minute)."分钟之前";
    }
    
    return "刚刚";
}