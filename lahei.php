<?php

include 'token.php';
$token = cacheToken();
$url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchblacklist?access_token='. $token;
$id = $_GET['id'];
$str = '{
	 "openid_list":["'.$id.'"]
	}';
$curl = new MyCurl($url);

//echo $str;
//$str = json_encode($data, JSON_UNESCAPED_UNICODE);
$ret = $curl->post($str);
echo $ret;