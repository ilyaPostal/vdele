<?php

namespace SITE_CORE;

//////////////////////////////////////////////
//		Определение структуры каталогов		//
//////////////////////////////////////////////

class MainDirs {
	
	public static $Site;
	public static $Root;
	public static $INTERNALFILES;
	public static $JS;
	public static $JSCommon;
	public static $JSLayouts;
	public static $JSComponents;
	public static $JSModules;
	public static $JSPages;
	public static $CSS;
	public static $CSSCommon;
	public static $CSSLayouts;
	public static $Images;
	public static $Files;
	public static $FilesPublic;
	public static $FilesPrivate;
	public static $Core;
	public static $DB;
	public static $PageModules;
	public static $Logs;
	public static $Sessions;
	
}

MainDirs::$Site				= (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
if (GetGlobalData::ClientIP() == $_SERVER['HTTP_HOST']) {
	MainDirs::$Root = substr($_SERVER['REDIRECT_URL'], 0 , strpos($_SERVER['REDIRECT_URL'], '/', 1) + 1);
} else {
	MainDirs::$Root = '/';
}
MainDirs::$INTERNALFILES	= MainDirs::$Root			. 'INTERNAL-FILES/';
MainDirs::$JS				= MainDirs::$INTERNALFILES	. 'JS/';
MainDirs::$JSCommon			= MainDirs::$INTERNALFILES	. 'JS/Common/';
MainDirs::$JSLayouts		= MainDirs::$INTERNALFILES	. 'JS/Layouts/';
MainDirs::$JSComponents		= MainDirs::$INTERNALFILES	. 'JS/Components/';
MainDirs::$JSModules		= MainDirs::$INTERNALFILES	. 'JS/Modules/';
MainDirs::$JSPages			= MainDirs::$INTERNALFILES	. 'JS/Pages/';
MainDirs::$CSS				= MainDirs::$INTERNALFILES	. 'CSS/';
MainDirs::$CSSCommon		= MainDirs::$INTERNALFILES	. 'CSS/Common/';
MainDirs::$CSSLayouts		= MainDirs::$INTERNALFILES	. 'CSS/Layouts/';
MainDirs::$Images			= MainDirs::$INTERNALFILES	. 'Images/';
MainDirs::$Files			= MainDirs::$INTERNALFILES	. 'Files/';
MainDirs::$FilesPublic		= MainDirs::$INTERNALFILES	. 'Files/Public/';
MainDirs::$FilesPrivate		= MainDirs::$INTERNALFILES	. 'Files/Private/';
MainDirs::$Core				= MainDirs::$INTERNALFILES	. 'PHP/CORE/';
MainDirs::$DB				= MainDirs::$INTERNALFILES	. 'PHP/DB/';
MainDirs::$PageModules		= MainDirs::$INTERNALFILES	. 'PHP/PAGE_MODULES/';
MainDirs::$Logs				= MainDirs::$INTERNALFILES	. 'PHP/logs/';
MainDirs::$Sessions			= MainDirs::$INTERNALFILES	. 'PHP/sessions/';


//////////////////////////////////////////////
//		    Расположение файлов JS			//
//////////////////////////////////////////////

class JS {
	
	public static $CommonMain;
	public static $CommonMainDefer;
	public static $ModulesAlerts;
	public static $ModulesCookie;
	public static $ModulesUrlParams;
	
	public static $ComponentsBasicDisplayTable;
	public static $ComponentsBasicPageNavigation;
	public static $ComponentsBasicComboBox;
	public static $ComponentsBasicTextEdit;
	public static $ComponentsBasicTableEdit;
	public static $ComponentsBasicCheckBox;
	public static $ComponentsBasicComboBoxEdit;
	public static $ComponentsPagesClientsFiltersDisplay;
	public static $ComponentsPagesFiltersFiltersEdit;
	
	public static $PagesLoginMain;
	
	public static function Init() {
		
		JS::$CommonMain								= MainDirs::$JSCommon		. 'main_0.0.0.2.js';
		JS::$CommonMainDefer						= MainDirs::$JSCommon 		. 'main-defer_0.0.0.4.js';
		JS::$ModulesAlerts							= MainDirs::$JSModules		. 'alerts_0.0.0.1.js';
		JS::$ModulesCookie							= MainDirs::$JSModules		. 'cookie.js';
		JS::$ModulesUrlParams						= MainDirs::$JSModules		. 'url-params_0.0.0.3.js';
		
		JS::$ComponentsBasicDisplayTable			= MainDirs::$JSComponents	. 'Basic/table-display_0.0.0.11.js';
		JS::$ComponentsBasicPageNavigation			= MainDirs::$JSComponents	. 'Basic/page-navigation_0.0.0.6.js';
		JS::$ComponentsBasicComboBox				= MainDirs::$JSComponents	. 'Basic/combobox_0.0.0.2.js';
		JS::$ComponentsBasicTextEdit				= MainDirs::$JSComponents	. 'Basic/text-edit_0.0.0.1.js';
		JS::$ComponentsBasicTableEdit				= MainDirs::$JSComponents	. 'Basic/table-edit.js';
		JS::$ComponentsBasicCheckBox				= MainDirs::$JSComponents	. 'Basic/checkbox_0.0.0.1.js';
		JS::$ComponentsBasicComboBoxEdit			= MainDirs::$JSComponents	. 'Basic/combobox-edit_0.0.0.2.js';
		
		
		JS::$PagesLoginMain							= MainDirs::$JSPages		. 'login/main_0.0.0.3.js';
		
	}
}

JS::Init();


//////////////////////////////////////////////
//		    Расположение файлов CSS			//
//////////////////////////////////////////////

class CSS {
	
	public static $CommonW3;
	public static $CommonW3ThemeGray;
	public static $CommonFontAwesome_4_7_0;
	public static $CommonFontAwesome_5_0_9;
	public static $CommonMain;
	public static $LayoutsVDeleMainLayout;
	
	public static function Init() {
		CSS::$CommonW3					= MainDirs::$CSSCommon	. 'w3.css';
		CSS::$CommonW3ThemeGray			= MainDirs::$CSSCommon	. 'w3-theme-gray-0.0.0.1.css';
		CSS::$CommonFontAwesome_4_7_0	= MainDirs::$CSSCommon	. 'font-awesome_4_7_0.min.css';
		CSS::$CommonFontAwesome_5_0_9	= MainDirs::$CSSCommon	. 'fontawesome-all_5_0_9.min.css';
		CSS::$CommonMain				= MainDirs::$CSSCommon	. 'main-0.0.0.14.css';
		CSS::$LayoutsVDeleMainLayout	= MainDirs::$CSSLayouts	. 'v-dele-main-layout.css';
	}
}

CSS::Init();


//////////////////////////////////////////////
//		    Настройки подключения к БД		//
//////////////////////////////////////////////

class DBConfiguration {

	#########################################
	#	   Configuration automatically		# 
	#	     is selected by site name		#
	#########################################
	
	public static $ProductionSiteName			= 'www.abrakodabra';
	public static $DevelopmentSiteName			= 'www.abrakodabra2';
	public static $LocalSiteName				= 'vdele';
	public static $LocalIP						= '127.0.0.1';
	public static $LocalSiteIsDev				= true;
	
	
	//////////////////////////////////////////
	//				PDO MSSQL				//
	//////////////////////////////////////////
	
// 	public static $MSSQLStringConnection		= null;
// 	public static $MSSQLUserNameConnection		= null;
// 	public static $MSSQLUserPasswordConnection	= null;
// 	public static $PDOMSSQLConParams			= null;
// 	public static $MSSQLIsEmulatePrepares		= null;
	
	//////////////////////////////////////////
	//				PDO MySQL				//
	//////////////////////////////////////////
	
	public static $MySQLStringConnection		= null;
	public static $MySQLUserNameConnection		= null;
	public static $MySQLUserPasswordConnection	= null;
	public static $PDOMySQLConParams			= null;
	public static $MySQLIsEmulatePrepares		= null;
	
	//////////////////////////////////////////
	//			  Initialization			//
	//////////////////////////////////////////
	
	public static function Init($SiteName) {
		
		if ($SiteName == DBConfiguration::$ProductionSiteName
				|| ($SiteName == DBConfiguration::$LocalSiteName && !DBConfiguration::$LocalSiteIsDev)
				|| ($SiteName == DBConfiguration::$LocalIP && !DBConfiguration::$LocalSiteIsDev)) {
					
					DBConfiguration::ProductionInit();
					
		} else if ($SiteName == DBConfiguration::$DevelopmentSiteName
				|| ($SiteName == DBConfiguration::$LocalSiteName && DBConfiguration::$LocalSiteIsDev)
				|| ($SiteName == DBConfiguration::$LocalIP && DBConfiguration::$LocalSiteIsDev)) {
					
					DBConfiguration::DebugInit();
					
		} else {
			
			throw new ExceptionToLog('Неизвестное название сайта для выбора настроек подключения к БД: ' . $SiteName );
			
		}
	}
	
	private static function DebugInit() {
		
		//////////////////////////////////////////
		//				PDO MSSQL				//
		//////////////////////////////////////////
		
// 		DBConfiguration::$MSSQLStringConnection			= 'sqlsrv:server=bdc;database=dev';
// 		DBConfiguration::$MSSQLUserNameConnection		= 'bdc-web';
// 		DBConfiguration::$MSSQLUserPasswordConnection	= 'skfhAOsf927GJDV';
// 		DBConfiguration::$PDOMSSQLConParams				= array(\PDO::ATTR_PERSISTENT => false);
// 		DBConfiguration::$MSSQLIsEmulatePrepares		= false;
		
		//////////////////////////////////////////
		//				PDO MySQL				//
		//////////////////////////////////////////
		
		//'mysql:host=localhost;port=4040;dbname=test;charset=UTF8';
		DBConfiguration::$MySQLStringConnection			= 'mysql:host=127.0.0.1;dbname=vdele_db;charset=UTF8';
		DBConfiguration::$MySQLUserNameConnection		= 'root';
		DBConfiguration::$MySQLUserPasswordConnection	= '';
		DBConfiguration::$PDOMySQLConParams				= array(\PDO::ATTR_PERSISTENT => true);
		DBConfiguration::$MySQLIsEmulatePrepares		= false;
		
	}
	
	private static function ProductionInit() {
		
		//////////////////////////////////////////
		//				PDO MSSQL				//
		//////////////////////////////////////////
		
// 		DBConfiguration::$MSSQLStringConnection			= 'sqlsrv:server=bdc;database=consbase';
// 		DBConfiguration::$MSSQLUserNameConnection		= 'bdc-web';
// 		DBConfiguration::$MSSQLUserPasswordConnection	= 'skfhAOsf927GJDV';
// 		DBConfiguration::$PDOMSSQLConParams				= array(\PDO::ATTR_PERSISTENT => false);
// 		DBConfiguration::$MSSQLIsEmulatePrepares		= false;
		
		//////////////////////////////////////////
		//				PDO MySQL				//
		//////////////////////////////////////////
		
		DBConfiguration::$MySQLStringConnection			= '';
		DBConfiguration::$MySQLUserNameConnection		= '';
		DBConfiguration::$MySQLUserPasswordConnection	= '';
		DBConfiguration::$PDOMySQLConParams				= array(\PDO::ATTR_PERSISTENT => true);
		DBConfiguration::$MySQLIsEmulatePrepares		= false;
		
	}

}


//////////////////////////////////////////
//				  Сессии				//
//////////////////////////////////////////

$SessionPath = $_SERVER['DOCUMENT_ROOT'] . MainDirs::$Sessions;
if (!is_dir($SessionPath))
	mkdir($SessionPath);
ini_set('session.save_path'				, realpath($SessionPath));
ini_set('session.use_only_cookies'		, 1);
ini_set('session.gc_maxlifetime'		, 43200);//3600 - 1 час, 43200 - 12 часов
ini_set('session.cookie_lifetime'		, 43200);


//////////////////////////////////////////
//			Настройки веремени			//
//////////////////////////////////////////

ini_set('date.timezone'					, 'Etc/GMT-11');	// Сахалинское время


//////////////////////////////////////////
//			  Память скрипта			//
//////////////////////////////////////////

//ini_set('memory_limit'					, '300M');


//////////////////////////////////////////
//			  Передача файлов			//
//////////////////////////////////////////

ini_set('always_populate_raw_post_data'	, -1);		// Чтобы не генерировалась ошибка при получении POST данных.
ini_set('file_uploads'					, 'On');	// Принимать файлы в запросе
	



?>