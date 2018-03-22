<?php
include '../token.php';
getResource();
//获取永久素材列表
function getResource()
{
	$token = cacheToken();
	$url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='. $token;
	$curl = new MyCurl($url);
	$data = json_encode([
							"type" => 'voice',
							 "offset" => 0,
							 "count"  => 10
						]);
	 $ret = $curl->post($data);
	 $de = json_decode($ret);
	 var_dump($de);

	// echo "";
}
