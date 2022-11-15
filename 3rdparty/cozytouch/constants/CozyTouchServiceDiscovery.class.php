<?php
class CozyTouchServiceDiscovery
{
	const ATLANTIC_CLIENTID = "Q3RfMUpWeVRtSUxYOEllZkE3YVVOQmpGblpVYToyRWNORHpfZHkzNDJVSnFvMlo3cFNKTnZVdjBh";
	const ATLANTIC_BASE_URL = "https://apis.groupe-atlantic.com";

	const BASE_URL = "https://ha110-1.overkiz.com/enduser-mobile-web/enduserAPI";
	Const END_POINT = [
		"token"=>self::ATLANTIC_BASE_URL."/token",
		"jwt"=>self::ATLANTIC_BASE_URL."/magellan/accounts/jwt",
		"login"=>self::BASE_URL."/login",
		"setup"=>self::BASE_URL."/setup",
		"devices"=>self::BASE_URL."/setup/devices",
		"deviceInfo"=>self::BASE_URL."/setup/devices/{deviceURL}/states",
		"stateInfo"=>self::BASE_URL."/setup/devices/{deviceURL}/states/{nameState}",
		"apply"=>self::BASE_URL."/exec/apply"
	];

	public static function ResolveAtlantic($key,$arg=null)
	{
		if(!array_key_exists($key,self::ATLANTIC_END_POINT))
		{
			return "";
		}
		$url = self::ATLANTIC_END_POINT[$key];
		
		if(!empty($arg) && is_array($arg))
		{
			foreach ($arg as $key => $value)
			{
				$url = str_replace("{".$key."}", urlencode($value), $url);
			}
		}
		return $url;
	}

	public static function Resolve($key,$arg=null)
	{
		if(!array_key_exists($key,self::END_POINT))
		{
			return "";
		}
		$url = self::END_POINT[$key];
		
		if(!empty($arg) && is_array($arg))
		{
			foreach ($arg as $key => $value)
			{
				$url = str_replace("{".$key."}", urlencode($value), $url);
			}
		}
		return $url;
	}
}
?>