<?php

namespace SITE_CORE;

class Session {
	
	//
	// Save Value
	//
	
	public static function SetValueByArrayPath($PathArr, $Value) {
		
		if (count($PathArr) == 0)
			return;
		
		if (session_status() !== PHP_SESSION_ACTIVE)
			session_start();
		
		$CurrentPath = &$_SESSION[$PathArr[0]];
		for ($i = 1; $i < (count($PathArr) - 1); $i++) {
			$CurrentPath = &$CurrentPath[$PathArr[$i]];
		}
		$CurrentPath[$PathArr[$i]] = $Value;
		
		session_write_close();
		
	}
	
	public static function SetValueByRoot($Root, $Value) {
		if (session_status() !== PHP_SESSION_ACTIVE)
			session_start();
		$_SESSION[$Root] = $Value;
		session_write_close();
	}
	
	public static function UnsetByRoot($Root) {
		if (session_status() !== PHP_SESSION_ACTIVE)
			session_start();
		unset($_SESSION[$Root]);
		session_write_close();
	}
	
	//
	// Get Value
	//
	
	public static function GetValueByArrayPath($PathArr) {
		$DefaultValue	= null;
		if (count($PathArr) == 0)
			return $DefaultValue;
		
		if (session_status() !== PHP_SESSION_ACTIVE)
			session_start();
			
		$ResultValue	= isset($_SESSION[$PathArr[0]]) ? $_SESSION[$PathArr[0]] : $DefaultValue;
		for ($i = 1; $i < count($PathArr); $i++) {
			$ResultValue = isset($ResultValue[$PathArr[$i]])
			? $ResultValue[$PathArr[$i]]
			: $DefaultValue;
		}
		return $ResultValue;
		
		session_abort();
		//session_write_close();
	}
	
	public static function GetValueByRoot($Root) {
		if (session_status() !== PHP_SESSION_ACTIVE)
			session_start();
		$Result = isset($_SESSION[$Root]) ? $_SESSION[$Root] : null;
		session_abort();
		//session_write_close();
		return $Result;
	}
	
	public static function GetValueRoot() {
		if (session_status() !== PHP_SESSION_ACTIVE)
			session_start();
		$Result = $_SESSION;
		session_abort();
		//session_write_close();
		return $Result;
	}
	
	//
	// Clear Session
	//
	
	public static function Clear() {
		if (session_status() !== PHP_SESSION_ACTIVE)
			session_start();
		session_unset();
		session_destroy();
	}
	
}

?>