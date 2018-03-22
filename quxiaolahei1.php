<?php
include 'token.php';
$black = new BlackList();
$black -> getBlack();
//海鹏的
// $black -> unBlack('ozikF0mbymhWHtGgLUYZeCKpBMuY');

// $black -> unBlack('opC5H0_8LtYN_1wMYcK20LVP1x_U');
// $black -> unBlack('oZjC60ZYqQc29SJdpr9BdFehrkrY');
// $black -> unBlack('oZjC60S2fX9TC7sot0mfqEPRxESo');
// $black -> goBlack('oZjC60XbRKP9-uhHvqmUo36aANrA');
class BlackList
{
	
	function goBlack($user)
	{
		$token = cacheToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchblacklist?access_token=' . $token;
		$str = '{
			 "openid_list":["'.$user.'"]
			}';
		$curl = new MyCurl($url);
		
		//echo $str;
		//$str = json_encode($data, JSON_UNESCAPED_UNICODE);
		$ret = $curl->post($str);
		return $ret;
	}
	// 获取拉黑用户名单
	function getBlack()
	{
		$token = cacheToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/tags/members/getblacklist?access_token=' . $token;
		$curl = new MyCurl($url);
		$data = '{
			"begin_openid":""
		}';
		$ret = $curl->post($data);
		$decStr = json_decode($ret);
		// 得到所有黑名单用户的ip
		$userArr = $decStr->data->openid;
		// echo $ret;
		var_dump($userArr);
		return $userArr;
	}
	// 取消拉黑
	function unBlack($user)
	{	
		$token = cacheToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchunblacklist?access_token=' . $token;
		$curl = new MyCurl($url);
		$data = '{
	 		"openid_list":["'.$user.'"]
		}';
		$ret = $curl->post($data);
		echo $ret;
	}
}
