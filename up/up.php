<?php
include '../token.php';

$type = $_POST['mediaType'];
$file = $_FILES['file'];

// 获取后缀
$suffix = pathinfo($file['name'])['extension'];
// 重新命名
$newName = uniqid().'.'.$suffix;
$newPath = "upload/".$newName;
// 如果向自己的服务器移动成功，再从自己服务器向微信服务器提交
if(move_uploaded_file($file["tmp_name"],$newPath)){

	$token = cacheToken();
	$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=$token&type=$type";
	$curl = new MyCurl($url);
 	if (class_exists('\CURLFile')) {  
	    $data = array('media' => new \CURLFile(realpath($newPath)));  
	} else {  
	    $data = array('media' => '@' . realpath($newPath));  
	} 
	 $ret = $curl->post($data);
	 // var_dump($newPath);
	 // var_dump($ret);
	 echo $ret;

} else {
	echo '移动失败';
}


