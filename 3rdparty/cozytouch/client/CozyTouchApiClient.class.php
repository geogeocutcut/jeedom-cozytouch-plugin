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
	public $atlantic_token_expire=0;
	public $atlantic_jwt='';
	
	public static $CURL_OPTS = array(
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT        => 60,	
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HTTPHEADER     => array('Accept: application/json'),
			CURLOPT_COOKIESESSION  => true)
	);
	
	function __construct($params = array()) {
		if(array_key_exists ( 'userId', $params )==true) {
			$this->userId=$params['userId'];
		}
		if(array_key_exists ( 'userPassword', $params )==true) {
			$this->userPassword=$params['userPassword'];
		}
		if(array_key_exists ( 'jsessionId', $params )==true) {
			$this->jsessionId=$params['jsessionId'];
	}
	}

	public function authenticate() {
		log::add('cozytouch', 'debug', 'authenticate function ');
		$this->getToken();
		if ($this->atlantic_token !== '') {
		$this->getJwt();
			if ($this->atlantic_jwt !== '') {
		$this->getJSessionId();
	}
		}
	}

	public function getToken() {
		// If token has expired or is empty, we ask a new one
		if(config::byKey('atlantic_token_expire', 'cozytouch', 0)<time()-5 || config::byKey('atlantic_token', 'cozytouch', '') == '')	{
			log::add('cozytouch', 'debug', 'Ask new token');
			$post_data = array(
					'grant_type' => 'password',
					'username' => 'GA-PRIVATEPERSON/'.config::byKey('username', 'cozytouch'),
					'password' => config::byKey('password', 'cozytouch'),
			);
			$opts = self::$CURL_OPTS;
			
			// Header Authorization : Basic Q3RfMUpWeVRtSUxYOEllZkE3YVVOQmpGblpVYToyRWNORHpfZHkzNDJVSnFvMlo3cFNKTnZVdjBh
			// Content-Type : application/x-www-form-urlencoded
			$curl_response = $this->makeRequest("token", 'POST', $post_data, false, false,["Content-Type: application/x-www-form-urlencoded","Authorization: Basic ".CozyTouchServiceDiscovery::ATLANTIC_CLIENTID]);
			if (!$curl_response) {
				log::add('cozytouch', 'info', 'pas de réponse in ask new token');
			}

			log::add('cozytouch', 'debug', 'curl response : ' . $curl_response);
			$result_arr = json_decode($curl_response);
			log::add('cozytouch', 'debug', 'curl response array : ' . print_r($result_arr, true));
            if (isset($result_arr->access_token) && isset($result_arr->expires_in)) {
			config::save('atlantic_token', $result_arr->access_token,'cozytouch');
			config::save('atlantic_token_expire', $result_arr->expires_in + time(),'cozytouch');
			} else {
				log::add('cozytouch', 'info', 'No token in response');
				config::save('atlantic_token', '','cozytouch');
				config::save('atlantic_token_expire', 0,'cozytouch');
			}
		}
		$this->atlantic_token = config::byKey('atlantic_token', 'cozytouch');
		$this->atlantic_token_expire = config::byKey('atlantic_token', 'cozytouch');
	}

	public function getJwt() {
		log::add('cozytouch', 'debug', 'getJwt function ');
			$opts = self::$CURL_OPTS;
			// Header Authorization : Bearer $this->token
			$curl_response = $this->makeRequest("jwt", 'GET', null, false, false, ["Authorization: Bearer ".$this->atlantic_token]);
			if (!$curl_response) {
				log::add('cozytouch', 'info', 'no response in getJwt');
			}
			log::add('cozytouch', 'debug', 'getJwt response : ' . $curl_response);
			$this->atlantic_jwt = trim($curl_response, '"');
	}

	public function getJSessionId() {
		log::add('cozytouch', 'debug', 'getJSessionId function ');
		$post_data = array(
				'jwt' => $this->atlantic_jwt,
		);
		$opts = self::$CURL_OPTS;
		$curl_response = $this->makeRequest("login",'POST',$post_data,true);
		preg_match('/JSESSIONID=([^;]+)/u', $curl_response, $matches);
		if (isset($matches[1])) {
			$jsessionid = 'JSESSIONID='.$matches[1];
			log::add('cozytouch', 'debug', "JSESSIONID ok");
		} else {
			$jsessionid = '';
			log::add('cozytouch', 'debug', "JSESSIONID not found");
		}
		
		$this->jsessionId = $jsessionid;

		if($this->jsessionId=='') {
			log::add('cozytouch', 'debug', 'jsessionId empty in getJSessionId');
			$post_data = array(				
				'userId' => config::byKey('username', 'cozytouch'),
				'userPassword' => config::byKey('password', 'cozytouch')
			);
			$opts = self::$CURL_OPTS;
			$curl_response = $this->makeRequest("login",'POST',$post_data,true);
			preg_match('/JSESSIONID=([^;]+)/u', $curl_response, $matches);
			if (isset($matches[1])) {
				$jsessionid = 'JSESSIONID='.$matches[1];
				log::add('cozytouch', 'debug', "pass 2 JSESSIONID ok");
			} else {
				$jsessionid = '';
				log::add('cozytouch', 'debug', "pass 2 JSESSIONID not found");
			}
			
			$this->jsessionId = $jsessionid;
		}
		
		config::save('jsessionId', $this->jsessionId,'cozytouch');
	}

	private function makeAuthRequest($retry = false, $route,  $method = 'GET', $data = array(), $header = false, $format_JSON = false, $headers = array())
	{
		log::add('cozytouch', 'debug','makeAuthRequest function');
		log::add('cozytouch', 'debug','jsessionId : ' . $this->jsessionId);
		try
		{
			$res = $this->makeRequest($route, $method, $data,$header,$format_JSON, $headers);
			return $res;
		}
		catch(InvalidArgumentException $ex)
		{
			if(!$retry)
			{
				$this->authenticate();
				$res = $this->makeRequest($route, $method, $data,$header,$format_JSON, $headers);
				return $res;
				
			}
		}
		catch(Exception $ex)
		{
		}
		return '';
	}

	private function makeRequest($route, $method = 'GET', $data = array(), $header = false, $format_JSON = false, $headers = array()) {
		log::add('cozytouch', 'debug', 'makeRequest function route : '.$route . ' method : '. $method);
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
		
		log::add('cozytouch', 'debug', 'call url : '.$url);
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
		log::add('cozytouch', 'debug', 'curl result : '. $result);
		if ($result === false)  {
			log::add('cozytouch', 'debug', 'curl error ..... ');
			$e = new Exception(curl_errno($ch).' | '.curl_error($ch));
			curl_close($ch);
			throw $e;
		}

		$http_code = intval(curl_getinfo($ch, CURLINFO_HTTP_CODE));
		if($http_code==401)
		{
			log::add('cozytouch', 'debug', 'Problème authentification ..... ');
			$e = new InvalidArgumentException();
			curl_close($ch);
			throw $e;
		}
		else if ($http_code>401)
		{
			log::add('cozytouch', 'debug', 'Problème autre ..... ');
			$e = new Exception();
			curl_close($ch);
			throw $e;
		}

		curl_close($ch);
		return $result;
	}
	
	public function getSetup() {
		log::add('cozytouch', 'debug', 'getSetup function ');
		$auth = false;
		if($this->jsessionId=='') {
			log::add('cozytouch', 'debug', 'JSESSIONID vide in getSetup');
			$this->authenticate();
			if($this->jsessionId=='') {
				log::add('cozytouch', 'debug', 'Unable to obtain JSESSIONID, abandon !');
				return;
			}
			$auth = true;
		}
		$curl_response = $this->makeAuthRequest($auth,'setup','GET');
		if (!$curl_response)
		{
			log::add('cozytouch', 'debug', 'pas de réponse setup');
			return;
		}
		$result_arr = json_decode($curl_response);
		log::add('cozytouch', 'debug', 'Response in getSetup : '. print_r($result_arr, true));
		return (new CozyTouchResponseHandler($result_arr))->getData('setup');
		
	}


	/*** */
	public function getDevices($post_data=array()) {
		$auth = false;
		if($this->jsessionId=='')
		{
			log::add('cozytouch', 'info', 'JSESSIONID vide');
			$this->authenticate();
			$auth = true;
		}
		$curl_response = $this->makeAuthRequest($auth,'devices','GET');
		if (!$curl_response)
		{
			log::add('cozytouch', 'info', 'pas de réponse');
		}
		$result_arr = json_decode($curl_response);
		return (new CozyTouchResponseHandler($result_arr))->getData('devices');
	}
	
	public function getDeviceInfo($device_url,$controllableName) {
		$auth = false;
		if($this->jsessionId=='')
		{
			log::add('cozytouch', 'info', 'JSESSIONID vide');
			$this->authenticate();
			$auth = true;
		}
		$curl_response = $this->makeAuthRequest($auth,'deviceInfo','GET',["deviceURL"=>$device_url]);
		if (!$curl_response)
		{
			log::add('cozytouch', 'info', 'pas de réponse');
		}
		$result_arr = json_decode($curl_response);
		return (new CozyTouchResponseHandler($result_arr))->getData('deviceInfo',$controllableName);
	}
	public function applyCommand($post_data=array()) {
		$auth = false;
		if($this->jsessionId=='')
		{
			log::add('cozytouch', 'info', 'JSESSIONID vide');
			$this->authenticate();
			$auth = true;
		}
		$curl_response = $this->makeAuthRequest($auth,'apply','POST',$post_data,false,true);
		if (!$curl_response)
		{
			log::add('cozytouch', 'info', 'pas de réponse');
		}
		$result_arr = json_decode($curl_response);
		log::add('cozytouch', 'debug', $curl_response);
	}
}

?>
