<?php
/**
 * Created by PhpStorm.
 * User: zhangshuang
 * Date: 2016/12/12
 * Time: 15:39
 */

namespace App\Tools;

use Monolog\Handler\SyslogHandler;

class ToolSyslog{

    protected static $syslogHandler = null;


    /**
     * 获取syslogHandler
     *
     */
    public static function getSyslogHandler(){

        if(self::$syslogHandler === null){
            self::$syslogHandler = new SyslogHandler('9douyu_service', 'local6');
        }
        return self::$syslogHandler;
    }
}