<?php

class Default_Controller extends Controller {

    // 普通的是以action_开头
    public function action_index()
    {


        echo 'you are in default controller and default method';
    }

    public function action_hello(array $params)
    {
        var_dump($params);
        echo 'you are in hello';

        $this->test();
        /*
        $model = new User_Model();
        $myVar =  $model->query();
        var_dump($myVar);
        */

        $viewPath = dirname(__FILE__) . '/../views/default.php';
        if(!file_exists($viewPath))
            throw new Exception('不存在视图文件：'.$viewPath);

        include $viewPath;
    }

    // 只有put, delete, post方法才能使用到的方法，以rest_开头
    public function rest_post($request)
    {
        // 这里应该做一些安全验证和过滤
        // 存数据，获取返回id
        $id = 123;
        return $id;
    }

    public function rest_put($request)
    {
        print_r($request);
        return 'dog';
    }

    public function rest_delete($request)
    {
        print_r($request);
        return 'dog';
    }


}
