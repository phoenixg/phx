<?php
// 教程： http://www.cnblogs.com/ikodota/archive/2012/06/15/php_url_Router.html
/*

index.php
index.php/controller
index.php/controller/method
index.php/controller/method/prarme1/value1
index.php/controller/method/param1/value1/param2/value2.....

*/
require '../../../share/debug/dBug.php';


define('MODULE_DIR', './classes/');

//D:\xampp\htdocs\phpframework\phx\test\ci-like-router\router.php
$APP_PATH= str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__);    

$SE_STRING=str_replace($APP_PATH, '', $_SERVER['REQUEST_URI']);   
//phpframework/phx/test/ci-like-router/router.php 计算出index.php后面的字段 index.php/controller/methon/id/3
$SE_STRING=trim($SE_STRING,'/');

/*
$constants = get_defined_constants(true);
new dBug($constants['user']);

$varialbes = get_defined_vars();
new dBug($varialbes);
*/


//这里需要对$SE_STRING进行过滤处理。
$ary_url=array(
    'controller'=>'index',
    'method'=>'index',
    'pramers'=>array()
    );

$ary_se=explode('/', $SE_STRING);
$se_count=count($ary_se);

//路由控制
if($se_count==1 and $ary_se[0]!='' ){
    $ary_url['controller']=$ary_se[0];
}else if($se_count>1){//计算后面的参数，key-value
    $ary_url['controller']=$ary_se[0];
    $ary_url['method']=$ary_se[1];
    if($se_count>2 and $se_count%2!=0){ //没有形成key-value形式
        die('参数错误');
    }else{
        for($i=2;$i<$se_count;$i=$i+2){
            $ary_kv_hash=array(strtolower($ary_se[$i])=>$ary_se[$i+1]);
            $ary_url[pramers]=array_merge($ary_url[pramers],$ary_kv_hash);
        }
    }
}

$module_name=$ary_url['controller'];
$module_file=MODULE_DIR.$module_name.'.class.php';
//echo $module_file;
$method_name=$ary_url['method'];
if(file_exists($module_file)){
    include($module_file);
    $obj_module=new $module_name();    //实例化模块m

    if(!method_exists($obj_module, $method_name)){
        die('方法不存在');
    }else{
        if(is_callable(array($obj_module, $method_name))){    //该方法是否能被调用
            //var_dump($ary_url[pramers]);
            $get_return=$obj_module->$method_name($ary_url[pramers]);    //执行a方法,并把key-value参数的数组传过去
            if(!is_null($get_return)){ //返回值不为空
                var_dump($get_return);
            }
            
        }else{
            die('该方法不能被调用');
        }
        
    }
}
else
{
    die('模块文件不存在');
}

