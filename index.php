<?php
define('TOKEN', 'miaomiao');
//第一步  实现微信服务器和自己的服务器结合
include 'token.php';
include 'curl.func.php';
include './xiaoxi/config.php';
include './xiaoxi/mysql.php';

$token = cacheToken();
$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='. $token;

$weixin = new WeixinCheck();
if (!isset($_GET['echostr'])) {
    $weixin->responseMessage();
}else{
    $weixin->check();
}
// $weixin->check();
// $weixin->responseMessage();
class WeixinCheck
{

	public function responseMessage()
	{
		//接受来自微信服务器的消息
        //1.首先判断版本：__PHP_VERSION__(判断php版本)、函数：phpversion()也可以获取php版本
        if (phpversion()>=7) {
            $postr = file_get_contents('php://input');
        } else {
            $postr = $GLOBALS['HTTP_RAW_POST_DATA'];
        }
        //从XML转化为可以看懂的代码
        $obj = simplexml_load_string($postr);


        //从用户发的消息是什么类型来根据情况返回不同的值
        switch ($obj->MsgType) {
        	case 'text':
                $this->responseText($obj);
                break;
            case 'image':
                $media_id = $obj->MediaId;
				$this->image($obj , $media_id);
                break;
        	case 'event':
                $this->responseEvent($obj);
                break;
            case 'voice':
                $this->responseVoice($obj);
                break;
        	default:
        		# code...
        		break;
        }
	}

	//回复你发的语言
	public function responseVoice($obj)
	{
		// 回复你说的语音
		/*$strVoice = '<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[%s]]></MsgType>
		<Voice>
		<MediaId><![CDATA[%s]]></MediaId>
		</Voice>
		</xml>';*/

		// 返回你说语音的文字
		/*$strVoice = "<xml>
	       <ToUserName><![CDATA[%s]]></ToUserName>
	       <FromUserName><![CDATA[%s]]></FromUserName>
	       <CreateTime>%s</CreateTime>
	       <MsgType><![CDATA[%s]]></MsgType>
	       <Content><![CDATA[%s]]></Content>
	       <FuncFlag>0</FuncFlag>
	       </xml>"; */

	    $recognition = $obj->Recognition;

	    $this->responseText($obj , $recognition);


    	/*$contentStr = "您发送的是语音消息"."\n"."语音内容为:"."\n".$recognition;
		// $media_id = $obj->MediaId;

		$type = 'text';
		$fromUser = $obj->ToUserName;
		$toUser = $obj->FromUserName;
		$time = time();
		echo sprintf($strVoice, $toUser, $fromUser, $time, $type, $contentStr);*/
	}

	//关注自动回复
	public function responseEvent($obj)
	{
		if ($obj->Event == 'subscribe') {
			$this->responseSubscribe($obj);
			die;
		} else if($obj->EventKey == 'tup'){
			$media_id = 'EyEFYPkD98owNt7nSpBq-g4-CeRswg0ZZ38xWWDu7zdyAiCCPl17LfrydrxKuGFP';
			$this->image($obj , $media_id);
			die;
		} else if($obj->EventKey == 'ship'){
			$media_id = 'WNXPbdP-MJg5zHkvQEkN6dgZKIv-uqDz0Nb-CVSaSwTABJdTnWZSng_Znjsxfq24';
			$this->video($obj , $media_id);
			die;
		} else if($obj->EventKey == 'yiny') {
			$media_id = '3JJH486z6uxuNtNOyh8rGQ9oLgA4-PqBACqJoVj6eNL5aUlI5K11ju_eC_p7NOSg';
			$this->yinyue($obj , $media_id);
			die;
		} else if($obj->EventKey == 'rili') {
			$this->rili($obj);
			die;
		} else if($obj->EventKey == 'weihao') {
			$this->weihao($obj);
			die;
		} else if($obj->EventKey == 'tianqi') {
			$this->tianqi($obj);
			die;
		}else if($obj->EventKey == 'tuwen') {
			$this->tuwen($obj);
			die;
		}
	}

	//关注自动回复
	public function responseSubscribe($obj)
	{
		//发送给用户的id可以获取到
		//那个逼崽子给我发的消息
		$fromUser = $obj->ToUserName;
		//我要给那个逼崽子发消息
		$toUser = $obj->FromUserName;
		//给刚关注的那逼崽子发消息
		$value = '哈喽，MMP，现在才来关注啊';
		//时间
		$time = time();
		//发送的类型
		$type = 'text';
		//固定格式
		$str = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
			
			</xml>";
		echo sprintf($str,$toUser,$fromUser, $time, $type, $value);
	}

	//按照用户发送的消息回复
	public function responseText($obj , $content='')
	{
		
		if (empty($content)) {
			//用户发给我的内容
			$content = $obj->Content;
		}
		file_put_contents('a.txt' , $content);
			
		//根据不同的内容返回文字
		if (strstr($content, '你好')) {
			$value = 'TMD滚，今天心情不大好';
			$this->text($obj , $value);
            die;
		} else if (strstr($content, '滚')) {
			$value = '你TMD骂谁';
			$this->text($obj , $value);
            die;
		} else if(strstr($content, '骂')) {
			$value = '你NB，老子一会踢了你';
			$this->text($obj , $value);
            die;
		} else if(strstr($content, '图片')) {
			$media_id = 'EyEFYPkD98owNt7nSpBq-g4-CeRswg0ZZ38xWWDu7zdyAiCCPl17LfrydrxKuGFP';
			$this->image($obj , $media_id);
			die;
		} else if(strstr($content, '视频')) {
			$media_id = 'WNXPbdP-MJg5zHkvQEkN6dgZKIv-uqDz0Nb-CVSaSwTABJdTnWZSng_Znjsxfq24';
			$this->video($obj , $media_id);
			die;
		} else if(strstr($content, '音乐')) {
			$media_id = '3JJH486z6uxuNtNOyh8rGQ9oLgA4-PqBACqJoVj6eNL5aUlI5K11ju_eC_p7NOSg';
			$this->yinyue($obj , $media_id);
			die;
		} else if(strstr($content, '图文')){
			$this->tuwen($obj);
			die;
		} else if(strstr($content, '历')){
			$this->rili($obj);
			die;
		} else if(strstr($content, '尾号')){
			$this->weihao($obj);
			die;
		} else if(strstr($content, '天气')){
			$this->tianqi($obj);
			die;
		} else {
			$value = '我不知道你在说什么';
			$this->text($obj , $value);
            die;
		}

		
	}
	//图文消息
	public function tuwen($obj)
	{
		$str = '<xml>
					<ToUserName><![CDATA[' . $obj->FromUserName . ']]></ToUserName>
					<FromUserName><![CDATA[' . $obj->ToUserName . ']]></FromUserName>
					<CreateTime>' . time() . '</CreateTime>
					<MsgType><![CDATA[news]]></MsgType>
					<ArticleCount>2</ArticleCount>
					<Articles>
						<item>
							<Title><![CDATA[新鲜的美铝出炉咯，走过路过不要错过]]></Title> 
							<Description><![CDATA[新鲜的美铝出炉咯，走过路过不要错过，新鲜的美铝出炉咯，走过路过不要错过，新鲜的美铝出炉咯，走过路过不要错过]]></Description>
							<PicUrl><![CDATA[http://ai.chenjiangjiang.cn/10.jpg]]></PicUrl>
							<Url><![CDATA[http://ai.chenjiangjiang.cn/lianjie.html]]></Url>
						</item>
						<item><Title><![CDATA[表情包你有吗，要不要进来看看，不要收藏哦]]></Title>
							<Description><![CDATA[来一波表情包吧，表情包你有吗，要不要进来看看，不要收藏哦，表情包你有吗，要不要进来看看，不要收藏哦]]></Description>
							<PicUrl><![CDATA[http://ai.chenjiangjiang.cn/11.jpg]]></PicUrl>
							<Url><![CDATA[http://ai.chenjiangjiang.cn/lianjie.html]]></Url>
						</item>
					</Articles>
				</xml>';
		echo $str;
	}


	//北京天气
	public function tianqi($obj)
	{
		$fromUser = $obj->ToUserName;
		$toUser = $obj->FromUserName;
		$time = time();
		$type = 'text';

		$appkey = '2a7393232e995255';//你的appkey
 
		$city = '北京';//utf8
		$cityid='1';//任选
		$citycode='101010100';//任选
		$url = "http://api.jisuapi.com/weather/query?appkey=$appkey&city=$city";
		$result = curlOpen($url);
		$jsonarr = json_decode($result, true);
		//exit(var_dump($jsonarr));
		if($jsonarr['status'] != 0)
		{
		    echo $jsonarr['msg'];
		    exit();
		}
		$result = $jsonarr['result'];
		$value = $result['city'].' '.$result['week'].' '.$result['weather'].' '.$result['winddirect']."\n".'现在温度'.$result['temp']."\n".'最高温度'.$result['temphigh']."\n".'最低温度'.$result['templow'];

	
		$strTq = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
 		<FromUserName><![CDATA[%s]]></FromUserName>
 		<CreateTime>%s</CreateTime>
 		<MsgType><![CDATA[%s]]></MsgType>
		<Content><![CDATA[%s]]></Content>
 		</xml>";
 		echo sprintf($strTq,$toUser,$fromUser,$time,$type,$value);

	}


	//北京尾号限行
	public function weihao($obj)
	{
		$fromUser = $obj->ToUserName;
		$toUser = $obj->FromUserName;
		$time = time();
		$type = 'text';

		$appkey = '2a7393232e995255';//你的appkey
		$city = 'beijing';//城市
		$date = date('Y-m-d' , time());//时间
		$url = "http://api.jisuapi.com/vehiclelimit/query?appkey=$appkey&city=$city&date=$date";
		$result = curlOpen($url);
		$jsonarr = json_decode($result, true);
		//exit(var_dump($jsonarr));
		if($jsonarr['status'] != 0)
		{
		    echo $jsonarr['msg'];
		    exit();
		}
		$result = $jsonarr['result'];
		$value = $result['cityname'].' '.$result['date']."\n".$result['week'].' '.implode(' ', $result['time']).' '.$result['numberrule'].' '.$result['number']."\n".$result['area']."\n".$result['summary'];

		$strWh = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
 		<FromUserName><![CDATA[%s]]></FromUserName>
 		<CreateTime>%s</CreateTime>
 		<MsgType><![CDATA[%s]]></MsgType>
		<Content><![CDATA[%s]]></Content>
 		</xml>";
 		echo sprintf($strWh,$toUser,$fromUser,$time,$type,$value);



	}

	//日历接口
	public function rili($obj)
	{
		$fromUser = $obj->ToUserName;
		$toUser = $obj->FromUserName;
		$time = time();
		$type = 'text';
		$appkey = '48e4f23a118fbf7b';//你的appkey 
		$date = date('Y-m-d');
		$url = "http://api.jisuapi.com/calendar/query?appkey=$appkey&date=$date";
		$result = curlOpen($url);
		$jsonarr = json_decode($result, true);
		if($jsonarr['status'] != 0)
		{
		    echo $jsonarr['msg'];
		    exit();
		}
		$result = $jsonarr['result'];
		$huangli = $result['huangli'];
		$value = $result['year'].'年 '.$result['month'].'月 '.$result['day'].'日
星期'.$result['week'].'
'.$huangli['nongli'].'
'.'宜 '.join(',',$huangli['yi'])."\n忌 ".join(',',$huangli['ji']) ;
 		$strLi = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
 		<FromUserName><![CDATA[%s]]></FromUserName>
 		<CreateTime>%s</CreateTime>
 		<MsgType><![CDATA[%s]]></MsgType>
		<Content><![CDATA[%s]]></Content>
 		</xml>";
 		echo sprintf($strLi,$toUser,$fromUser,$time,$type,$value);
	}


	//回复text消息
	public function text($obj , $value)
	{
		//发送给用户的id可以获取到
		//那个逼崽子给我发的消息
		$fromUser = $obj->ToUserName;
		//我要给那个逼崽子发消息
		$toUser = $obj->FromUserName;

		//////////////////////////////////////////////////
		// toUser为用户的opendId  text为用户发的消息  ////
		/////////////   开始先存数据库  //////////////////
		//////////////////////////////////////////////////
		
		//保存数据库存的用户发到消息
		$text = $obj->Content;

		//链接数据库操作
		$link = connect(DB_HOST,DB_USER,DB_PWD,DB_CHARSET,DB_NAME);
		// file_put_contents('a.txt', $link);
		$sql = "insert into weixi (opendId , text) values('$toUser' , '$text')";
		$result = mysqli_query($link,$sql);
		file_put_contents('a.txt', '666666');
		//////////////////////////////////////////////////
		////////   肯定能存进去所以不用判断  /////////////
		////////   这里只是负责插入数据库的  /////////////
		/////////////   插入数据库结束  //////////////////
		//////////////////////////////////////////////////



		// file_put_contents('a.txt', $toUser);
		//时间
		$time = time();
		//发送的类型
		$type = 'text';
		//固定格式
		$str = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
			
			</xml>";
		echo sprintf($str,$toUser,$fromUser, $time, $type, $value);
	}
	//回复图片消息
	public function image($obj , $media_id)
	{
		//发送给用户的id可以获取到
		//那个逼崽子给我发的消息
		$fromUser = $obj->ToUserName;
		//我要给那个逼崽子发消息
		$toUser = $obj->FromUserName;
		//时间
		$time = time();
		//发送的类型
		$type = 'image';
		$strPic = '<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
		<FromUserName><![CDATA[%s]]></FromUserName>
		<CreateTime>%s</CreateTime>
		<MsgType><![CDATA[%s]]></MsgType>
		<Image>
		<MediaId><![CDATA[%s]]></MediaId>
		</Image>
		</xml>';
		echo sprintf($strPic,$toUser,$fromUser, $time, $type, $media_id);
	}
	//回复视频消息
	public function video($obj , $media_id)
	{
		$type='video';
	  	$title='视频';
	  	$description='这是一个视频';
	  	$fromUser = $obj->ToUserName;
		$toUser = $obj->FromUserName;
		$time = time();


	  	$strV='<xml>
	  	<ToUserName><![CDATA[%s]]></ToUserName>
	  	<FromUserName><![CDATA[%s]]></FromUserName>
	  	<CreateTime>%s</CreateTime>
	  	<MsgType><![CDATA[%s]]></MsgType>
	  	<Video><MediaId><![CDATA[%s]]></MediaId>
	  	<Title><![CDATA[%s]]></Title>
	  	<Description><![CDATA[%s]]></Description></Video>
	  	 </xml>';
	  	echo sprintf($strV,$toUser,$fromUser,$time,$type,$media_id,$title,$description);

	}

	//回复音乐消息
	public function yinyue($obj , $media_id)
	{
		$fromUser = $obj->ToUserName;
		$toUser = $obj->FromUserName;
		$time = time();
		$type='music';
	  	$title='音乐';
	  	$description='这是一段音乐';
	  	$music='http://ai.chenjiangjiang.cn/11.amr';
	  	$hqmusic='http://ai.chenjiangjiang.cn/11.amr';
	  	$str='<xml>
		<ToUserName><![CDATA[%s]]></ToUserName>
	  	<FromUserName><![CDATA[%s]]></FromUserName>
	  	<CreateTime>%s</CreateTime>
	  	<MsgType><![CDATA[%s]]></MsgType>
	  	<Music>
	  	<Title><![CDATA[%s]]></Title>
	  	<Description><![CDATA[%s]]></Description>
	  	<MusicUrl><![CDATA[%s]]></MusicUrl>
	  	<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
	  	<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
	  	</Music>
	  	</xml>';
	  	echo sprintf($str,$toUser,$fromUser,$time,$type,$title,$description,$music,$hqmusic,$media_id);
	}


	public function check()
	{
		$echoStr = $_GET['echostr'];
		if ($this->checkSignature()) {
			//赞正成功
			echo $echoStr;
		} else {

		}
	}
	private function checkSignature()
	{
    $signature =  $_GET["signature"];
    $timestamp = $_GET["timestamp"];
    $nonce =  $_GET["nonce"];
	$token = TOKEN;
	$tmpArr = array($token, $timestamp, $nonce);
	sort($tmpArr, SORT_STRING);
	$tmpStr = implode( $tmpArr );
	$tmpStr = sha1( $tmpStr );

	if( $signature == $tmpStr ){
			return true;
	}else{
			return false;
		}
	}
}
