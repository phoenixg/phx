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

    // 是否加载调试工具
    'debug'         => true,

    // 调试工具（dbug 或 kint）， 是否再包括firephp
    'debug_tool'           => 'kint',

    // 加密盐
    'key'                  => '123456abcdefg',

    // 这是用来测试的
    'aaa'                  => array( 'bbb' => '111',
                                     'ccc' => '222',
                                     'ddd' => array('eee' => '12345'))

);
