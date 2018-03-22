<?php
include 'token.php';
$token = cacheToken();
// 获取所有用户
$url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='. $token;
$curl = new MyCurl($url);
// 得到json数据的所有用户
$ret = $curl->get();
$decStr = json_decode($ret);
// 得到所有用户的ip
$userArr = $decStr->data->openid;
// 通过循环将所有用户保存到数组,以达到发送给所有用户消息
$i = 0;
foreach ($userArr as $key => $value) {
	$data['touser'][$i] = $value;
	$i++;
}
$xiaoxi = $_POST['xx'];

// 开始群发
$urlTwo = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='. $token;
$curlTwo = new MyCurl($urlTwo);
$data['msgtype'] = "text";
$data['text']['content'] = $xiaoxi;
$endata = json_encode($data);
// var_dump($data['touser']);
$res = $curlTwo->post($endata);
echo $res;