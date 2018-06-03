<?php

namespace SITE_CORE;

class Json {
	
	//
	// Encode - Преобразует ассоциативный массив php (utf-8) в строку Json в кодировке utf-8
	//
	
	public static function Encode($x) {
		return json_encode($x, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
	
}

class Ajax {
	
	public static function CreateResponse($IsSuccessful = true, $Message = '', $Object = null) {
		$result = array();
		$result['IsSuccessful']	= $IsSuccessful;
		$result['Message']		= $Message;
		$result['Data']			= $Object;
		//return iconv('cp1251', 'utf-8', Json_1251::Encode($result, true));
		//return Json_1251::Encode($result, true);
		return Json::Encode($result);
	}
	
}

?>