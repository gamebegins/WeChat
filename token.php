<?php

//第一个使用curl
include 'MyCurl.php';
//第二个 appid  appsecret
define('APPID', 'wx36d91f52328a54e4');
define('SECRET', '420ebe1beed02924c1d053c05d3b1da6');

//得到缓存的token
function cacheToken()
{
	//首先你得判断有没有token.txt这个文件
	//若果有，需要判断这个token是否过期，如果过期，就重新请求获取
	//如果没有的话，就直接获取
	if (file_exists('token.txt')) {
		//读取缓存的token
		$token = file_get_contents('token.txt');
		//判断是否过期，如果过期就重新获取
		//如何判断token是否过期？ 
		$filectime = filectime('token.txt');
		///////////
		//判断过期 //
		if ($filectime + 7200 > time()) {
			return $token;
		} else {
			//过期
			$token = getToken();
			//干掉原来的token.txt
			unlink('token.txt');
			file_put_contents('token.txt', $token);
			return $token;
		}
	} else {
		//不存在
		$token = getToken();
		//将宝贵的token
		//写到token.txt里面
		file_put_contents('token.txt', $token);
		return $token;
	}
}
function getToken()
{
	$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';
	$data['appid'] = APPID;
	$data['secret'] = SECRET;
	//直接字符串拼接一下即可
	$url = $url . '&' . http_build_query($data);
	//创建curl对象
	$curl = new MyCurl($url);
	$content = $curl->get();
	//将返回过来的数据中的token
	//得到
	$content = json_decode($content, true);
	return $content['access_token'];
}