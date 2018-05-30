<?php

namespace SITE_CORE;

//////////////////////////////////////////////
//  Подключение модулей во время выполнения	//
//////////////////////////////////////////////

class RequireModule {
	
	//////////////////////////////////////////////
	//			  namespace SITE_DB				//
	//////////////////////////////////////////////
	
	//
	// Подключить средства для работы с БД
	//
	
	public static function DBAccess($IsRequireMySQL = false, $IsRequireMSSQL = true) {
		//require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$DB. 'Configuration.php';
		require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$DB. 'Connections.php';
		require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$DB. 'MicroORM/DirectSQL.php';
		// 		if ($IsRequireMySQL)
			// 			require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$DB. 'MicroORM/MySQLDirectSQL.php';
			// 		if ($IsRequireMSSQL)
				// 			require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$DB. 'MicroORM/MSSQLDirectSQL.php';
	}
	
	//////////////////////////////////////////////
	//		  namespace SITE_PAGE_MODULES		//
	//////////////////////////////////////////////
	
	//
	// Текущая навигация по клиентам, договорам и т.д.
	//
	
	public static function CurrentNavigation() {
		require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$PageModules . 'CurrentNavigation.php';
	}
	
	//
	// Главный фильтр по клиентам
	//
	
	public static function ClientsFilter() {
		require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$PageModules . 'ClientsFilter.php';
	}
	
	//
	// Средства сохранения данных ClientsFilter в сеансе
	//
	
	public static function ClientsFilterStorage() {
		require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$PageModules . 'ClientsFilterStorage.php';
	}
	
	//
	// Подключить средства для проверки
	// переменных, полученых из формы.
	//
	
	public static function CheckFieldsHTML() {
		require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$PageModules . 'CheckHTMLFields.php';
	}
	
	//
	// Подключить средства для упрощённого создания HTML разметки
	//
	
	public static function GenerateHTML() {
		require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$PageModules . 'GenerateHTML.php';
	}
	
	//
	// Подключение средств отправки файлов
	//
	
	public static function FileSend() {
		require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$PageModules . 'FileSend.php';
	}
}


?>