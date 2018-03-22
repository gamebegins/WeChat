<?php

include 'token.php';

$token = cacheToken();
$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='. $token;

//a为媒体数组
$a[0] = [
	'type'=>'click',
	'name' => '图片',
	'key' =>'tup'
	// 'url' =>'G53Gt_hv4aynrQcT8Srwvp72voun852NMcFXWOEQEYBRq9Z-n83ZLXMg6fqny_or'
];
$a[1] = [
	'type'=>'click',
	'name' => '视频',
	'key' =>'ship'
];
$a[2] = [
	'type'=>'click',
	'name' => '音乐',
	'key' =>'yiny'
];

//b为接口
$b[0] = [
	'type'=>'click',
	'name' => '日历',
	'key' =>'rili'
];
$b[1] = [
	'type'=>'view',
	'name' => '百度',
	'url' =>'http://www.baidu.com'
];
$b[2] = [
	'type'=>'click',
	'name' => '北京尾号限行',
	'key' =>'weihao'
];
$b[3] = [
	'type'=>'click',
	'name' => '北京天气',
	'key' =>'tianqi'
];
$b[4] = [
	'type'=>'click',
	'name' => '每日推送',
	'key' =>'tuwen'
];

$data['button'][0] = [
	'name' => '媒体',
	'sub_button' => $a
];
$data['button'][1] = [
	'name' => '查询',
	'sub_button' => $b
];
$data['button'][2] = [
	'type'=>'scancode_push',
	'name' => '扫一扫',
	'key' =>'sao'
];

$curl = new MyCurl($url);
$str = json_encode($data, JSON_UNESCAPED_UNICODE);
// echo $str;
$ret = $curl->post($str);
echo $ret;