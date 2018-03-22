<?php
include 'token.php';
$token = cacheToken();
// 获取所有用户用的链接
$url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='. $token;
$curl = new MyCurl($url);
// 得到json数据的所有用户
$ret = $curl->get();
$decStr = json_decode($ret);
$userArr = $decStr->data->openid;


//查询所有黑名单
$url2 = 'https://api.weixin.qq.com/cgi-bin/tags/members/getblacklist?access_token=' . $token;
$curl2 = new MyCurl($url2);
$data5 = '{
	"begin_openid":""
}';
$ret2 = $curl2->post($data5);
$decStr2 = json_decode($ret2);
// var_dump($decStr2);
// 得到所有黑名单用户的ip
$userArr2 = $decStr2->data->openid;


//把所有的openid放在数组中
$i = 0;
foreach ($userArr as $key => $value) {
	if(in_array($value, $userArr2)){
		
	} else {
		$openid[$i] = $value;
		$i++;
	}
}
//每页显示数
$number = 2;
//数据总条数
$zong = $i;
//得到总页数
$totalPage = ceil($zong / $number);
//当前页
//获取到当前页的值
if (empty($_GET['page'])) {
	$page = 1;
} else {
	$page = (int)$_GET['page'];
}
//判断当前页
if($page < 1) {
	$page = 1;
}
if($page > $totalPage) {
	$page = $totalPage;
}

$a = ($page - 1)*$number;
if ($page == $totalPage) {
	$b = $zong;
} else {
	$b = $a+$number;
}

for ($j = $a; $j < $b; $j++) { 
	$id = $openid[$j];
	$url1 = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$token.'&openid='.$id.'&lang=zh_CN';
	$curl1 = new MyCurl($url1);
	$ret1 = $curl1->get();
	$decStr1 = json_decode($ret1);

	$data['name'] = $decStr1->nickname;
	$data['touxiang'] = $decStr1->headimgurl;
	$data['time'] = date('Y-m-d',$decStr1->subscribe_time);
	$data['city'] = $decStr1->city;
	$data['openid'] = $id;
	$shuju[] = $data;
}
$data1['data'] = $shuju;
$data1['page'] = $page;
$data1['totalPage'] = $totalPage;
echo json_encode($data1);
