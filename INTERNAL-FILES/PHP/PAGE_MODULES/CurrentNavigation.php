<?php
/*
namespace SITE_PAGE_MODULES;
use SITE_CORE AS Core;

class CurrentNavigation {
	
	public static $SessionName		= 'CurrentNavigation';
	
	public static $ClientID			= null;
	public static $ClientName		= null;
	public static $ContractID		= null;
	public static $TOID				= null;
	
	public static function Init() {
		
		$CurrentNavigation					= Core\Session::GetValueByRoot(CurrentNavigation::$SessionName);
		if ($CurrentNavigation) {
			CurrentNavigation::$ClientID	= isset($CurrentNavigation['ClientID'])		? $CurrentNavigation['ClientID'] 	: null;
			CurrentNavigation::$ClientName	= isset($CurrentNavigation['ClientName'])	? $CurrentNavigation['ClientName'] 	: null;
			CurrentNavigation::$ContractID	= isset($CurrentNavigation['ContractID'])	? $CurrentNavigation['ContractID']	: null;
			CurrentNavigation::$TOID		= isset($CurrentNavigation['TOID'])			? $CurrentNavigation['TOID'] 		: null;
		}
		
	}
	
	public static function Clear() {
		Core\Session::UnsetByRoot(CurrentNavigation::$SessionName);
		CurrentNavigation::$ClientID	= null;
		CurrentNavigation::$ClientName	= null;
		CurrentNavigation::$ContractID	= null;
		CurrentNavigation::$TOID		= null;
	}
	
	public static function Save($ClientID, $ClientName, $ContractID, $TOID) {
		
		$Values = array('ClientID' => $ClientID, 'ClientName' => $ClientName, 'ContractID' => $ContractID, 'TOID' => $TOID);
		Core\Session::SetValueByRoot(CurrentNavigation::$SessionName, $Values);
		
		CurrentNavigation::$ClientID	= $ClientID;
		CurrentNavigation::$ClientName	= $ClientName;
		CurrentNavigation::$ContractID	= $ContractID;
		CurrentNavigation::$TOID		= $TOID;
	}
	
}

CurrentNavigation::Init();

*/

?>