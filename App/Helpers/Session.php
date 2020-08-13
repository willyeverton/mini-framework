<?php

namespace App\Helpers;

class Session
{
    public static function start()
    {
        if(!isset($_SESSION))
            session_start();
    }

    public static function config() 
    {
        $name = md5('seg'.$_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);
        session_name($name);
        session_cache_expire(60 * 8); // 8 horas
        
        self::start();
        $_SESSION['session_name'] = $name;
    }
}