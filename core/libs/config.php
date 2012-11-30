<?php namespace Phx;

class Config
{

    public static $items = array();

    public function __construct(array $arr)
    {
        static::$items = $arr;
        //var_export(static::$items);
        //var_dump(static::$items["application"]["aaa"]["ddd"]["eee"]["out"]);
    }

    public static function get($str, $default = null)
    {
        $str = static::parse($str);
        if (is_null($str) || !isset($str)) {
            return $default;
        }

        return $str;
    }

    protected static function parse($str)
    {
        $str = 'static::$items["' . str_replace('.', '"]["', $str) . '"]';
        $str_parent = substr($str, 0, strrpos($str, '['));

        if (eval('return is_array('.$str_parent.');') && eval('return isset('.$str.');')) {
            return eval('return '.$str.';');
        }
        
        return null;
    }

    public static function has($str)
    {
        return ! is_null(static::get($str));
    }
}
