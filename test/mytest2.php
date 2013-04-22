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
//$ch = curl_init('http://localhost/spbooks-PHPPRO1-ae9bb56/chapter_03/rest/index.php/events/2');

$ch = curl_init('http://localhost/phx/default');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
$item   = json_decode($response, 1);

$item['title'] = '哈利波特与密室的新名称';

$data = json_encode($item);
$ch = curl_init('http://localhost/phx/default/3');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$response = curl_exec($ch);
var_dump($response); // 说明利用REST修改记录成功！

