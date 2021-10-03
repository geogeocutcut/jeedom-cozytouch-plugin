<?php
require_once dirname(__FILE__) . '/../../../../../core/php/core.inc.php';

if (!class_exists('CozyTouchServiceDiscovery')) {
	require_once dirname(__FILE__) . "/../constants/CozyTouchServiceDiscovery.class.php";
}

if (!class_exists('CozyTouchResponseHandler')) {
	require_once dirname(__FILE__) . "/../handlers/CozyTouchResponseHandler.class.php";
}

class CozyTouchApiClient
{
	public $jsessionId ='';
	public $userId ='';
	public $userPassword='';
	public $atlantic_token='';
	public $atlantic_jwt='';
	
	public static $CURL_OPTS = array(
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_TIMEOUT        => 60,	
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_HTTPHEADER     => array("Accept: application/json",
				CURLOPT_COOKIESESSION  => TRUE)
	);
	
	function __construct($params = array()) {
		if(array_key_exists ( 'userId', $params )==true)
			$this->userId=$params['userId'];
		if(array_key_exists ( 'userPassword', $params )==true)
			$this->userPassword=$params['userPassword'];
		$this->getToken();
		$this->getJwt();
		$this->getJSessionId();
		
	}

	public function getToken() {
		$post_data = array(
				'grant_type' => 'password',
				'username' => $this->userId,
				'password' => $this->userPassword,
		);
		$opts = self::$CURL_OPTS;
		
		// Header Authorization : Basic czduc0RZZXdWbjVGbVV4UmlYN1pVSUM3ZFI4YTphSDEzOXZmbzA1ZGdqeDJkSFVSQkFTbmhCRW9h
		// Content-Type : application/x-www-form-urlencoded
		$curl_response = $this->makeRequest("token",'POST',$post_data,FALSE,FALSE,["Content-Type: application/x-www-form-urlencoded","Authorization: Basic ".CozyTouchServiceDiscovery::ATLANTIC_CLIENTID]);
		if (!$curl_response)
		{
			log::add('cozytouch', 'info', 'pas de réponse');
		}
		
		log::add('cozytouch', 'debug', $curl_response);
		$result_arr = json_decode($curl_response);
		log::add('cozytouch', 'debug', $result_arr);
		$this->atlantic_token = $result_arr->access_token;
	}

	public function getJwt() {
		$opts = self::$CURL_OPTS;
		// Header Authorization : Bearer $this->token
		$curl_response = $this->makeRequest("jwt",'GET',null,FALSE,FALSE,["Authorization: Bearer ".$this->atlantic_token]);
		if (!$curl_response)
		{
			log::add('cozytouch', 'info', 'pas de réponse');
		}
		log::add('cozytouch', 'debug', $curl_response);
		$this->atlantic_jwt = trim($curl_response, '"');
	}

	public function getJSessionId() {
		$post_data = array(
				'jwt' => $this->atlantic_jwt,
		);
		$opts = self::$CURL_OPTS;
		$curl_response = $this->makeRequest("login",'POST',$post_data,TRUE);
		preg_match("/JSESSIONID=\\w{32}/u", $curl_response, $jsessionid);
		
		$this->jsessionId = implode($jsessionid);
	}
	
	public function makeRequest($route, $method = 'GET', $data = array(),$header = FALSE,$format_JSON=FALSE, $headers = array()){
		$ch = curl_init();
		$opts = self::$CURL_OPTS;
		$url=CozyTouchServiceDiscovery::Resolve($route);
		if ($data)
		{
			switch ($method)
			{
				case 'GET':
					$url=CozyTouchServiceDiscovery::Resolve($route,$data);
					break;
					// Method override as we always do a POST.
				default:
					$url=CozyTouchServiceDiscovery::Resolve($route);
					if($format_JSON)
					{
						$opts[CURLOPT_POSTFIELDS] = json_encode($data);
						$opts[CURLOPT_HTTPHEADER][] = "Content-Type: application/json";
						log::add('cozytouch', 'debug', 'json '.$opts[CURLOPT_POSTFIELDS]);
					}
					else
					{
						$opts[CURLOPT_POSTFIELDS] = http_build_query($data);
						log::add('cozytouch', 'debug', 'data '.$opts[CURLOPT_POSTFIELDS]);
					}
					break;
			}
		}
		
		log::add('cozytouch', 'debug', 'call '.$url);
		$opts[CURLOPT_URL] = $url;
		$opts[CURLOPT_HTTPHEADER][]='Cookie: '.$this->jsessionId;
		if(is_array($headers))
		{
			foreach($headers as $h)
			{
				$opts[CURLOPT_HTTPHEADER][]=$h;
				
				log::add('cozytouch', 'debug', 'header : '.$h);
			}
		}
		$opts[CURLOPT_HEADER] = $header;
		curl_setopt_array($ch, $opts);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	public function getSetup() {
		if($this->jsessionId=='')
		{
			log::add('cozytouch', 'info', 'JSESSIONID vide');
		}
		$curl_response = $this->makeRequest('setup','GET');
		if (!$curl_response)
		{
			log::add('cozytouch', 'info', 'pas de réponse');
		}
		$result_arr = json_decode($curl_response);
		log::add('cozytouch', 'debug', $result_arr);
		return (new CozyTouchResponseHandler($result_arr))->getData('setup');
		
	}


	/*** */
	public function getDevices($post_data=array()) {
		if($this->jsessionId=='')
		{
			//die('Not Authorised');
		}
		$curl_response = $this->makeRequest('devices','GET');
		if (!$curl_response)
		{
			//die('error occured');
		}
		$result_arr = json_decode($curl_response);
		return (new CozyTouchResponseHandler($result_arr))->getData('devices');
	}
	
	public function getDeviceInfo($device_url,$controllableName) {
		if($this->jsessionId=='')
		{
			//die('Not Authorised');
		}
		$curl_response = $this->makeRequest('deviceInfo','GET',["deviceURL"=>$device_url]);
		if (!$curl_response)
		{
			//die('error occured');
		}
		$result_arr = json_decode($curl_response);
		return (new CozyTouchResponseHandler($result_arr))->getData('deviceInfo',$controllableName);
	}
	public function applyCommand($post_data=array()) {
		if($this->jsessionId=='')
		{
			//die('Not Authorised');
		}
		$curl_response = $this->makeRequest('apply','POST',$post_data,false,true);
		if (!$curl_response)
		{
			//die('error occured');
		}
		$result_arr = json_decode($curl_response);
		log::add('cozytouch', 'debug', $curl_response);
	}
}

?>