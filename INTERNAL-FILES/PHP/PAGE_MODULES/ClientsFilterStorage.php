<?php

 namespace SITE_PAGE_MODULES;
 use SITE_CORE AS Core;
 
 class ClientsFilterStorage {
 
	public static $SessionName		= 'ClientsFilter';
		 
	public static $ClientsFilters		= null;
	public static $ClientsFiltersActive	= null;		// Те, которые имеют значения
	 
	public static function Init() {
	 
		$CF	= Core\Session::GetValueByRoot(ClientsFilterStorage::$SessionName);
		if ($CF) {
			ClientsFilterStorage::$ClientsFilters			= isset($CF['ClientsFilters'])			? $CF['ClientsFilters']			: null;
			ClientsFilterStorage::$ClientsFiltersActive		= isset($CF['ClientsFiltersActive'])	? $CF['ClientsFiltersActive']	: null;
		}
	 
	}
	 
	public static function Clear() {
		Core\Session::UnsetByRoot(ClientsFilterStorage::$SessionName);
		ClientsFilterStorage::$ClientsFilters		= null;
		ClientsFilterStorage::$ClientsFiltersActive	= null;
	}
	
	public static function SaveFilters($ClientsFilters) {
		Core\Session::SetValueByArrayPath(array(ClientsFilterStorage::$SessionName, 'ClientsFilters'), $ClientsFilters);
		ClientsFilterStorage::$ClientsFilters	= $ClientsFilters;
	}
	
	public static function SaveFiltersActive($ClientsFiltersActive) {
		Core\Session::SetValueByArrayPath(array(ClientsFilterStorage::$SessionName, 'ClientsFiltersActive'), $ClientsFiltersActive);
		ClientsFilterStorage::$ClientsFiltersActive	= $ClientsFiltersActive;
	}
	
	public static function SaveAllFilters($ClientsFilters, $ClientsFiltersActive) {
		$Arr = array(
				'ClientsFilters'			=> $ClientsFilters
				, 'ClientsFiltersActive'	=> $ClientsFiltersActive
		);
		Core\Session::SetValueByRoot(ClientsFilterStorage::$SessionName, $Arr);
		ClientsFilterStorage::$ClientsFilters		= $ClientsFilters;
		ClientsFilterStorage::$ClientsFiltersActive	= $ClientsFiltersActive;
	}
	 
}
	 
ClientsFilterStorage::Init();
 
 

?>