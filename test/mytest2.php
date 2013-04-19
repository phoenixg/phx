<?php
// rest测试，写到单元测试里面



// post测试
/*
$data = json_encode(array('a','b','c'));

$ch = curl_init('http://localhost/phx/default');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);
$events   = json_decode($response, 1);
var_dump($events); // 说明利用REST创建记录成功！
*/

// put测试


