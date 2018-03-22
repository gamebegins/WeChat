<?php  
/** 
  * 作者：smalle 
  * 网址：http://blog.csdn.net/oldinaction 
  * 公众号：smallelife 
  */  
  
define("AppID","wx36d91f52328a54e4");  
define("AppSecret", "420ebe1beed02924c1d053c05d3b1da6");  
  
/* 新增一个临时素材 */  
//url 里面的需要2个参数一个 access_token 一个是 type（值可为image、voice、video和缩略图thumb）  
$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".get_token()."&type=voice";
if (class_exists('\CURLFile')) {  
    $josn = array('media' => new \CURLFile(realpath("11.amr")));  
} else {  
    $josn = array('media' => '@' . realpath("1.jpg"));  
}  
  
$ret = curl_post($url,$josn);  
$row = json_decode($ret);//对JSON格式的字符串进行编码  
echo '此素材的唯一标识符media_id为：'.$row->media_id;//得到上传素材后，此素材的唯一标识符media_id  
  
//获取access_token  
function get_token(){  
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".AppID."&secret=".AppSecret;  
    $data = json_decode(file_get_contents($url),true);  
    if($data['access_token']){  
        return $data['access_token'];  
    }else{  
        echo "Error";  
        exit();  
    }  
}  
  
//curl实现post请求  
function curl_post($url, $data = null)  
{  
    //创建一个新cURL资源  
    $curl = curl_init();  
    //设置URL和相应的选项   
    curl_setopt($curl, CURLOPT_URL, $url);  
    if (!empty($data)){  
        curl_setopt($curl, CURLOPT_POST, 1);  
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);  
    }  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
    //执行curl，抓取URL并把它传递给浏览器  
    $output = curl_exec($curl);  
    //关闭cURL资源，并且释放系统资源  
    curl_close($curl);  
    return $output;  
}  
  
?>  