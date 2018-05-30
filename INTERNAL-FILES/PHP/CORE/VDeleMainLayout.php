<?php

namespace SITE_CORE;
use SITE_DB				AS DB;

class VDeleMainLayoutPages {
	
	public static $Root;
	
	public static $Tasks;
	public static $Tasks_New;
	public static $Masters;
	public static $Registration;
	public static $Profile;
	public static $Profile_Notify;
	public static $Profile_Mytasks;
	public static $Profile_Settings;
	public static $About;
	
	public static $Debug;
	
}

class VDeleMainLayout {
	
	public static $CurrentPage;
	public static $CurrentPageUrl;
	public static $BDConnection;
	
	public static $SiteTitle;
	public static $PageHeaderText	= 'Главная страница';
	public static $PageMinWidth		= '500px';
	
	public static $RequireCSS		= '';
	public static $RequireJS		= '';
	
	//////////////////////////////////////////
	//			  Initialization			//
	//////////////////////////////////////////
	
	public static function InitPages() {
		
		VDeleMainLayoutPages::$Root					= MainDirs::$Root;
		
		VDeleMainLayoutPages::$Tasks				= MainDirs::$Root . 'tasks/';
		VDeleMainLayoutPages::$Tasks_New			= MainDirs::$Root . 'tasks/new/';
		VDeleMainLayoutPages::$Masters				= MainDirs::$Root . 'masters/';
		VDeleMainLayoutPages::$Registration			= MainDirs::$Root . 'registration/';
		VDeleMainLayoutPages::$Profile				= MainDirs::$Root . 'profile/';
		VDeleMainLayoutPages::$Profile_Notify		= MainDirs::$Root . 'profile/notify/';
		VDeleMainLayoutPages::$Profile_Mytasks		= MainDirs::$Root . 'profile/mytasks/';
		VDeleMainLayoutPages::$Profile_Settings		= MainDirs::$Root . 'profile/settings/';
		VDeleMainLayoutPages::$About				= MainDirs::$Root . 'about/';
		
		VDeleMainLayoutPages::$Debug				= MainDirs::$Root . 'debug/';
		
	}
	
	public static function Init() {
		
		VDeleMainLayout::$SiteTitle					= '';
		VDeleMainLayout::$CurrentPageUrl			= MainDirs::$Site . VDeleMainLayout::$CurrentPage;
		
	}
	
	//////////////////////////////////////////
	//			  Start Layout				//
	//////////////////////////////////////////
	
	public static function StartLayout($PageAddress = '') {
		
		//header("Content-Type: text/html; charset=utf-8");
		
		?><!DOCTYPE html>
<html style="min-width: <?php echo VDeleMainLayout::$PageMinWidth; ?>">
<title><?php echo VDeleMainLayout::$SiteTitle; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="utf-8">
<link rel="shortcut icon" href="<?php echo MainDirs::$Site . MainDirs::$Images; ?>favicon.ico" type="image/x-icon">

									<!-- CSS -->
									
<link rel="stylesheet" href="<?php echo MainDirs::$Site . CSS::$CommonMain; ?>">
<link rel="stylesheet" href="<?php echo MainDirs::$Site . CSS::$LayoutsVDeleMainLayout; ?>">
<link rel="stylesheet" href="<?php echo MainDirs::$Site . CSS::$CommonW3; ?>">
<link rel="stylesheet" href="<?php echo MainDirs::$Site . CSS::$CommonW3ThemeGray; ?>">
<link rel="stylesheet" href="<?php echo MainDirs::$Site . CSS::$CommonFontAwesome_4_7_0; ?>">
<link rel="stylesheet" href="<?php echo MainDirs::$Site . CSS::$CommonFontAwesome_5_0_9; ?>">
<?php echo VDeleMainLayout::$RequireCSS; ?>

									<!-- JS -->													
	
<script src="<?php echo MainDirs::$Site . JS::$CommonMainDefer; ?>" charset="utf-8" defer></script>
<script src="<?php echo MainDirs::$Site . JS::$CommonMain; ?>" charset="utf-8"></script>
<script src="<?php echo MainDirs::$Site . JS::$ModulesUrlParams; ?>" charset="utf-8"></script>
<?php echo VDeleMainLayout::$RequireJS; ?>

<body class="TransitionDisabled" style="min-width: <?php echo VDeleMainLayout::$PageMinWidth; ?>;">

	<div class="w3-main">
		<!-- class="w3-container" -->
		<div >
		
<?php
	}
	
	//////////////////////////////////////////
	//			 Finalize Layout			//
	//////////////////////////////////////////
	
	public static function FinalizeLayout($PageAddress = '') {
		$PageAddress = $PageAddress === '' ? VDeleMainLayout::$CurrentPage : $PageAddress;
		?>
		
		</div>

	</div>

</body>
</html>
		<?php 
	}
	
	
	//////////////////////////////////////////
	//			   Try Call Page			//
	//////////////////////////////////////////
	
	public static function TryCallPage($PageAddress = '') {
		$IsOpen			= false;
		$MLref			= new \ReflectionClass('SITE_CORE\VDeleMainLayoutPages');
		$StaticProps	= $MLref->getStaticProperties();
		foreach($StaticProps as $key => $value)
			if ($value && $value == $PageAddress) {
				VDeleMainLayout::SetCurrentPage($PageAddress);
				
				//
				// Инициализация макета
				//
				
				VDeleMainLayout::Init();
				
				//
				// Передача управления странице
				//
				
				require $_SERVER['DOCUMENT_ROOT'] . $value . 'index.php';
				$IsOpen = true;
				break;
			}
		return $IsOpen;
	}
	
	
	//////////////////////////////////////////
	//			  Set Current Page			//
	//////////////////////////////////////////
	
	public static function SetCurrentPage($PageAddress) {
		VDeleMainLayout::$CurrentPage	= $PageAddress;
		VDeleMainLayout::$PageHeaderText	= VDeleMainLayout::PageHeaderText($PageAddress);
	}

	
	//////////////////////////////////////////
	//			  PageHeaderText			//
	//////////////////////////////////////////
	
	public static function PageHeaderText($PageAddress) {
		$HeaderText	= VDeleMainLayout::$PageHeaderText;
		return $HeaderText;
	}
	
	
	//////////////////////////////////////////
	//		  Подключение CSS вручную.		//
	//////////////////////////////////////////

	public static function RequireCSSInHeader($CSSVar) {
		VDeleMainLayout::$RequireCSS .= '<link rel="stylesheet" href="' . MainDirs::$Site . $CSSVar . '">' . "\r\n";
	}
	
	
	//////////////////////////////////////////
	//		  Подключение JS вручную.		//
	//////////////////////////////////////////

	public static function RequireJSInHeader($JSVar, $RequireType = '') {
		VDeleMainLayout::$RequireJS .= '<script src="' . MainDirs::$Site . $JSVar . '"' . ' ' . $RequireType . ' charset="utf-8"></script>' . "\r\n";
	}
	
	
	//////////////////////////////////////////
	//		Получение подключения к БД		//
	//////////////////////////////////////////
	
	public static function GetConnection() {
		VDeleMainLayout::$BDConnection = !VDeleMainLayout::$BDConnection ? DB\Connections::GetPDOMSSQL() : VDeleMainLayout::$BDConnection;
		return VDeleMainLayout::$BDConnection;
	}
	
}

//////////////////////////////////////////
//			  Initialization			//
//////////////////////////////////////////

VDeleMainLayout::InitPages();

?>