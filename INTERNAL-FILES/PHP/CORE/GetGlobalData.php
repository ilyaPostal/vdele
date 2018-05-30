<?php

namespace SITE_CORE;

class GetGlobalData {
	
	//
	// Получение необработанного потока данных
	//
	
	public static function RawInput() {
		return file_get_contents("php://input");
	}
	
	//
	// Получение необработанного потока данных
	// и преобразование в ассоциативный массив,
	// предполагая что данные пришли в формате JSON
	//
	
	public static function InputJsonObject() {
		return json_decode(self::RawInput(), true);
		
		//return Json_1251::Decode(self::RawInput());
	}
	
	//
	// Получение IP пользователя
	//
	
	public static function ClientIP() {
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}

?>