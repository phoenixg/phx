<?php

/**
 * LOG CLASS
 *
 * usage: Log::info('test'), Log::warn('test'), Log::error('test')
 */
class Log {  
    private function __construct() {} 

    // write a log
    protected static function write($type, $message)
    {
        $message = static::format($type, $message);
        //echo $message;

        $logFile = PATH_LOGS . 'runtime_' . date('Ymd') . '.log';
        file_put_contents($logFile, $message, LOCK_EX | FILE_APPEND);
    }

    // format a log message
    protected static function format($type, $message)
    {
        return '['.date('Y-m-d H:i:s').'] '.strtoupper($type)." - {$message}".PHP_EOL;
    }
    
    // handle non-existed static method
    public static function __callStatic($method, $parameters)
    {
        static::write($method, $parameters[0]);
    }

}
