<?php
class IoC {
   protected static $registry = array();
   
   // 注册一个类
   public static function register($name, Closure $resolver)
   {
      static::$registry[$name] = $resolver;
   }
   
   // 获取已注册类的实例
   public static function resolve($name)
   {
      try{
         if (!static::registered($name)) {
            throw new Exception('这个类：' . $name . '没有被注册过');
         }
         $name = static::$registry[$name];
         return $name();
      } catch (Exception $e) {
         echo $e->getMessage();
      }
   }
   
   // 检查该类是否已注册
   public static function registered($name)
   {
      return array_key_exists($name, static::$registry);
   }
}

