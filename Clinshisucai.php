<?php  
include 'token.php';

$token = cacheToken();
/* 新增一个临时素材 */  
//url 里面的需要2个参数一个 access_token 一个是 type（值可为image、voice、video和缩略图thumb）  
$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$token."&type=video";  
$url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$token."&media_id=Tc6DGlorCHqtsX7p4OIPZZfyHfUbC1UbrxovAk5kC4OM-NMww6-BkwadvRBhmqe4";


$curl = new MyCurl($url);
  
$ret = $curl->post($data);
$row = json_decode($ret);//对JSON格式的字符串进行编码  
var_dump($ret);