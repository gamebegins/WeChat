<?php 


class MyCurl
{
	private $url;
	function __construct($url)
	{
		$this->url = $url;
	}
	function get () {
		//初始化一个curl,自己去查手册
		$curl = curl_init();
		//去设置一些属性方法
		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER , true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		$content = curl_exec($curl);
		curl_close($curl);
		return $content;

	}
	function post($data)
	{
		//初始化一个curl,自己去查手册
		$curl = curl_init();
		//去设置一些属性方法
		curl_setopt($curl, CURLOPT_URL, $this->url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER , true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		$content = curl_exec($curl);
		curl_close($curl);
		return $content;
	}
}