<?php

return array(
    /* 基本信息，放在一级下 */

    'hostname' => 'localhost',
    'base_url' => '/phx/',

    // 时区
    'timezone'           => 'Asia/Shanghai',

    // 默认控制器名称
    'default_controller' => 'default',

    // 默认控制器方法名称
    'default_action'     => 'index',

    // 是否开启错误报告，生产环境中请设置为false
    'error_reporting' => true,

    // 是否开启调试
    'mode_debug'         => true,

    // 调试的方案
    'debug_soft'           => 'firephp', //or dbug





    // 加密盐
    'key'                  => '123456abcdefg',



    // 这是用来测试的
    'aaa'                  => array( 'bbb' => '111',
                                     'ccc' => '222',
                                     'ddd' => array('eee' => '12345'))

);
