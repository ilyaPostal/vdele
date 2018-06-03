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
// 					//При ATTR_PERSISTENT == true может не подключиться с первого раза после простоя.
// 				};
// 				if ($PDOMSSQLCon != null)
// 					break;
// 			}
// 			if ($PDOMSSQLCon == null)
// 				throw new \Exception('Не удалось подключиться к серверу.');
// 			$PDOMSSQLCon->setAttribute(\PDO::ATTR_ERRMODE				, \PDO::ERRMODE_EXCEPTION);
// 			//$IsEmulate = Core\DBConfiguration::$MSSQLIsEmulatePrepares;
// 			//$PDOMSSQLCon->setAttribute(\PDO::ATTR_EMULATE_PREPARES		, $IsEmulate);
// 			$PDOMSSQLCon->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE	, \PDO::FETCH_ASSOC);
// 			//$PDOMSSQLCon->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE	, \PDO::SQLSRV_FETCH_ASSOC);
// 			$PDOMSSQLCon->setAttribute(\PDO::SQLSRV_ATTR_ENCODING		, \PDO::SQLSRV_ENCODING_SYSTEM);
// 			//$PDOMSSQLCon->exec("SET NAMES 'utf8';");
// 			return $PDOMSSQLCon;
// 		} catch (\Exception $e) {
// 			throw new Core\ExceptionToLog('Ошибка соединения c MSSQL: ' . $e->getMessage() 
// 					. '<br>' . Core\DBConfiguration::$MSSQLStringConnection
// 					. '<br>' . Core\DBConfiguration::$MSSQLUserNameConnection);
// 		}
// 	}
	
	
	public static function GetPDOMySQL() {
		if (Connections::$PDOMySQLConnection) {
			return Connections::$PDOMySQLConnection;
		}
		Connections::$PDOMySQLConnection = null;
		try {
			for ($i = 0; $i < 10; $i++) {
				try {
					Connections::$PDOMySQLConnection = new \PDO(Core\DBConfiguration::$MySQLStringConnection, Core\DBConfiguration::$MySQLUserNameConnection
							, Core\DBConfiguration::$MySQLUserPasswordConnection, Core\DBConfiguration::$PDOMySQLConParams);
				} catch(\Exception $e) {
					Connections::$PDOMySQLConnection = null;
					//При ATTR_PERSISTENT == true может не подключиться с первого раза после простоя.
				};
				if (Connections::$PDOMySQLConnection != null)
					break;
			}
			if (Connections::$PDOMySQLConnection == null)
				throw new \Exception('Не удалось подключиться к серверу.');
			Connections::$PDOMySQLConnection->setAttribute(\PDO::ATTR_ERRMODE				, \PDO::ERRMODE_EXCEPTION);
			Connections::$PDOMySQLConnection->setAttribute(\PDO::ATTR_EMULATE_PREPARES		, Core\DBConfiguration::$MySQLIsEmulatePrepares);
			Connections::$PDOMySQLConnection->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE	, \PDO::FETCH_ASSOC);
			//Connections::$PDOMySQLConnection->exec("SET NAMES 'utf8';");
			return Connections::$PDOMySQLConnection;
		} catch (\Exception $e) {
			//throw new \Exception('Ошибка соединения c MySQL:' . iconv('cp1251', 'UTF-8', $e->getMessage()) );
			//throw new Core\ExceptionToLog('Ошибка соединения c MySQL:' . mb_convert_encoding($e->getMessage(), 'utf-8', mb_detect_encoding($e->getMessage())) );
			throw new Core\ExceptionToLog('Ошибка соединения c MySQL: ' . $e->getMessage());
			//throw new Core\ExceptionToLog('Ошибка соединения c MySQL:' . iconv('cp1251', 'UTF-8', $e->getMessage()) );
		}
	}
	
	private static $PDOMySQLConnection = null;
}



?>