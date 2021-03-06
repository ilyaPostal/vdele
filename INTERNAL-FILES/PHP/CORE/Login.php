<?php

namespace SITE_CORE;

class LoginInfo {
	
	public static $SessionName		= 'LoginInfo';
	
	public static $UserID			= null;
	public static $UserName			= null;
	public static $UserTypeID		= null;
	public static $IP				= null;
	
	public static function Init() {
		
		$LoginInfo					= Session::GetValueByRoot(LoginInfo::$SessionName);
		if ($LoginInfo) {
			LoginInfo::$UserID		= isset($LoginInfo['UserID'])		? $LoginInfo['UserID'] 		: null;
			LoginInfo::$UserName	= isset($LoginInfo['UserName'])		? $LoginInfo['UserName'] 	: null;
			LoginInfo::$UserTypeID	= isset($LoginInfo['UserTypeID'])	? $LoginInfo['UserTypeID']	: null;
			LoginInfo::$IP			= GetGlobalData::ClientIP();
		}

	}
	
	public static function Clear() {
		Session::UnsetByRoot(LoginInfo::$SessionName);
		LoginInfo::$UserID		= null;
		LoginInfo::$UserName	= null;
		LoginInfo::$UserTypeID	= null;
	}
	
	public static function Save($UserID, $UserName, $UserTypeID) {
		
		$Values = array('UserID' => $UserID, 'UserName' => $UserName, 'UserTypeID' => $UserTypeID);
		
		Session::SetValueByRoot(LoginInfo::$SessionName, $Values);
		
		LoginInfo::$UserID			= $UserID;
		LoginInfo::$UserName		= $UserName;
		LoginInfo::$UserTypeID		= $UserTypeID;
	}
	
	public static function GetAsArray() {
		return array( 'UserID' => LoginInfo::$UserID, 'UserName' => LoginInfo::$UserName, 'UserTypeID' => LoginInfo::$UserTypeID);
	}
	
	//
	// Генерирование ошибки доступа
	//
	
	public static function LoginAccessErrorGenerate($JsonPost) {
		throw new \Exception("Ошибка доступа "
				. "<br>" . MainLayout::$CurrentPage
				. "<br>" . (isset($JsonPost['Action']) ? $JsonPost['Action'] : '')
				. "<br>" . LoginInfo::$UserID . ' ' . LoginInfo::$UserName
				. "<br>" . LoginInfo::$IP);
	}
}

LoginInfo::Init();

?>