<?php
class Config
{
    private static $_instance = null;
    private static $_items = array();

    private function __construct(array $arr)
    {
        self::$_items = $arr;
    }

    public static function getInstance(array $arr)
    {
        if(!(self::$_instance instanceof Config)) {
            self::$_instance = new Config($arr);
        }

        return self::$_instance;
    }

    public static function get($str, $default = null)
    {
        $str = self::parse($str);
        if (is_null($str) || !isset($str)) {
            return $default;
        }

        return $str;
    }

    protected static function parse($str)
    {
        $str = 'self::$_items["' . str_replace('.', '"]["', $str) . '"]';
        $str_parent = substr($str, 0, strrpos($str, '['));

        if (eval('return is_array('.$str_parent.');') && eval('return isset('.$str.');')) {
            return eval('return '.$str.';');
        }

        return null;
    }

    public static function has($str)
    {
        return ! is_null(self::get($str));
    }
}
