<?php

namespace SITE_PAGE_MODULES;

class CheckHTMLFields {
	
	public static function CleanStringRef(&$value) {
		$value = trim($value);
		$value = stripslashes($value);
		$value = strip_tags($value);
		$value = htmlspecialchars($value);
		//return $value;
	}
	
	public static function CleanString($value = "") {
		$value = trim($value);
		$value = stripslashes($value);
		$value = strip_tags($value);
		$value = htmlspecialchars($value);
		return $value;
	}
	
	public static function CheckStringLength($value = "", $max = 255, $min = 5) {
	    $result = (mb_strlen($value) < $min || mb_strlen($value) > $max);
	    return !$result;
	}
	
	public static function CheckStringEmail($value) {
		return filter_var($value, FILTER_VALIDATE_EMAIL); 
	}
	
	public static function IsNatural($var) {
		return is_numeric($var) && $var - intval($var) == 0 && $var > 0;
	}
	
	public static function IsIntBool($var) {
		return is_numeric($var) && $var - intval($var) == 0 && $var > 0 && $var < 2;
	}
	
}

?>