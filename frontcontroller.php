<?php

/*
 * 先引导系统，然后单例方式实例化本前端控制器类（FC），FC负责解释请求的变量，接管程序的运行，分发路由至用户自定义的类，即动作控制器（AC）
 */
class FrontController {
    private static $_instance = null;
    protected $_controller;
    protected $_action;
    protected $_params = array();
    
    // 防止直接new
    private function __construct() {
        global $CFG;

        // parse uri into string
        $request_uri = $_SERVER['REQUEST_URI'];
        $request_str = str_replace($CFG::get('application.base_url'), '', $request_uri);
        
        while(substr($request_str, strlen($request_str)-1) == '/') {
            $request_str = substr($request_str, 0, -1);
        }

        // convert uri into array
        $request_str_arr = explode('/', $request_str);
        unset($request_uri);
        unset($request_str);

        // first two segments is controller/action
        $this->_controller = empty($request_str_arr['0']) ? $CFG::get('application.default_controller') : $request_str_arr['0'];
        $this->_action = empty($request_str_arr['1']) ?  $CFG::get('application.default_action') : $request_str_arr['1'];

        // uri parameters
        for($i = 2; $i < count($request_str_arr); $i++)
        {
            $f = $i%2;
            if($f == 0) $this->_params[$request_str_arr[$i]] = empty($request_str_arr[$i + 1]) ? null : $request_str_arr[$i + 1];
        }

        Log::info('Initialized FC successfully');
    }
    
    // 防止直接clone
    private function __clone() {}

    public static function getInstance() {
        if(!(self::$_instance instanceof self)) {
            self::$_instance = new FrontController();
        }
        return self::$_instance;
    }
    
    // 根据控制器和方法名称，执行控制器对应的方法
    public function route() {
        $controllerName = ucfirst($this->_controller) . '_Controller';
        $controllerHandler = new $controllerName();

        $action = 'action_'.$this->_action; 
        if(!method_exists($controllerHandler, $action)) 
            throw new Exception('不存在方法：'.$action);
            
        $controllerHandler->$action($this->_params);
    }
}
