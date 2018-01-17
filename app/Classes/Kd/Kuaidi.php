<?php
namespace App\Classes\Kd;

class Kuaidi{
	public $url = 'http://api.kuaidi100.com/api?';
	public $appKey = '8ae825850f4a8dfb';
	public $company = array();
	
	public function getTransport ($company = '',$num = ''){
		if($num == '' || $company == '') return false;
		$url = self::build($company,$num);
		$curl = curl_init();
		curl_setopt ($curl, CURLOPT_URL, $url);
		curl_setopt ($curl, CURLOPT_HEADER,0);
		curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt ($curl, CURLOPT_TIMEOUT,5);
		$response = curl_exec($curl);
		curl_close ($curl);
		return $response;
	}
	
	public function build ($company,$num){
		$url = $this->url;
		$param = array(
			'id' => $this->appKey,
			'com' => $company,
			'nu' => $num,
			'show' => 0,
			'muti' => 1,
			'order' => 'desc'
		);
		$url .= http_build_query($param);
		return $url;
	}
}