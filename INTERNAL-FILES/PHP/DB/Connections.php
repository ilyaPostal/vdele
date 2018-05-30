<?php

namespace SITE_DB;
use SITE_CORE AS Core;

//////////////////////////////////////////
//			Return PDO object,			//
//		  whith connect to MySQL.		//
//////////////////////////////////////////

class Connections {
	
// 	public static function GetPDOMSSQL() {
// 		$PDOMSSQLCon = null;
// 		try {
// 			for ($i = 0; $i < 10; $i++) {
// 				try {
// 					$PDOMSSQLCon = new \PDO(Core\DBConfiguration::$MSSQLStringConnection, Core\DBConfiguration::$MSSQLUserNameConnection
// 							, Core\DBConfiguration::$MSSQLUserPasswordConnection, Core\DBConfiguration::$PDOMSSQLConParams);
// 				} catch(\Exception $e) {
// 					$PDOMSSQLCon = null;
// 					//��� ATTR_PERSISTENT == true ����� �� ������������ � ������� ���� ����� �������.
// 				};
// 				if ($PDOMSSQLCon != null)
// 					break;
// 			}
// 			if ($PDOMSSQLCon == null)
// 				throw new \Exception('�� ������� ������������ � �������.');
// 			$PDOMSSQLCon->setAttribute(\PDO::ATTR_ERRMODE				, \PDO::ERRMODE_EXCEPTION);
// 			//$IsEmulate = Core\DBConfiguration::$MSSQLIsEmulatePrepares;
// 			//$PDOMSSQLCon->setAttribute(\PDO::ATTR_EMULATE_PREPARES		, $IsEmulate);
// 			$PDOMSSQLCon->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE	, \PDO::FETCH_ASSOC);
// 			//$PDOMSSQLCon->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE	, \PDO::SQLSRV_FETCH_ASSOC);
// 			$PDOMSSQLCon->setAttribute(\PDO::SQLSRV_ATTR_ENCODING		, \PDO::SQLSRV_ENCODING_SYSTEM);
// 			//$PDOMSSQLCon->exec("SET NAMES 'utf8';");
// 			return $PDOMSSQLCon;
// 		} catch (\Exception $e) {
// 			throw new Core\ExceptionToLog('������ ���������� c MSSQL: ' . $e->getMessage() 
// 					. '<br>' . Core\DBConfiguration::$MSSQLStringConnection
// 					. '<br>' . Core\DBConfiguration::$MSSQLUserNameConnection);
// 		}
// 	}
	
	
	public static function GetPDOMySQL() {
		$PDOMySQLCon = null;
		try {
			for ($i = 0; $i < 10; $i++) {
				try {
					$PDOMySQLCon = new \PDO(Core\DBConfiguration::$MySQLStringConnection, Core\DBConfiguration::$MySQLUserNameConnection
							, Core\DBConfiguration::$MySQLUserPasswordConnection, Core\DBConfiguration::$PDOMySQLConParams);
				} catch(\Exception $e) {
					$PDOMySQLCon = null;
					//��� ATTR_PERSISTENT == true ����� �� ������������ � ������� ���� ����� �������.
				};
				if ($PDOMySQLCon != null)
					break;
			}
			if ($PDOMySQLCon == null)
				throw new \Exception('�� ������� ������������ � �������.');
				$PDOMySQLCon->setAttribute(\PDO::ATTR_ERRMODE				, \PDO::ERRMODE_EXCEPTION);
				$PDOMySQLCon->setAttribute(\PDO::ATTR_EMULATE_PREPARES		, Core\DBConfiguration::$MySQLIsEmulatePrepares);
				$PDOMySQLCon->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE	, \PDO::FETCH_ASSOC);
				//$PDOMySQLCon->exec("SET NAMES 'utf8';");
				return $PDOMySQLCon;
		} catch (\Exception $e) {
			//throw new \Exception('������ ���������� c MySQL:' . iconv('cp1251', 'UTF-8', $e->getMessage()) );
			//throw new Core\ExceptionToLog('������ ���������� c MySQL:' . mb_convert_encoding($e->getMessage(), 'utf-8', mb_detect_encoding($e->getMessage())) );
			throw new Core\ExceptionToLog('������ ���������� c MySQL: ' . $e->getMessage());
			//throw new Core\ExceptionToLog('������ ���������� c MySQL:' . iconv('cp1251', 'UTF-8', $e->getMessage()) );
		}
	}
}



?>