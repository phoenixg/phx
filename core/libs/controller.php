<?php

class Controller {
    












    //加载模型，返回模型对象实例
    public function loadModel($name)
    {
        require(APP_DIR .'models/'. strtolower($name) .'.php');

        $model = new $name;
        return $model;
    }
    
    //加载视图，返回视图对象实例
    public function loadView($name)
    {
        $view = new View($name);
        return $view;
    }
    
    //加载插件
    public function loadPlugin($name)
    {
        require(APP_DIR .'plugins/'. strtolower($name) .'.php');
    }
    
    //加载辅助类库，返回辅助类库实例
    public function loadHelper($name)
    {
        require(APP_DIR .'helpers/'. strtolower($name) .'.php');
        $helper = new $name;
        return $helper;
    }

    public function to_home()
    {
        global $CFG;
        header('Location: '. $CFG::get('application.hostname').$CFG::get('application.base_url'));
        exit;
    }

    public function test()
    {
        echo 'test';
    }


    
}