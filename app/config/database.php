<?php
// 暂时只支持mysql
return array (
    'enable' => true,
    'host'            => 'localhost',
    'database'        => 'test',
    'username'        => 'root',
    'password'        => '123456',
    'charset'         => 'utf8',
    'prefix'          => '',
    'idiom_column_id' => array( // 配置每张表的id字段名
        'employees'   => 'employee_id',
        'departments' => 'department_id'
    )
);
