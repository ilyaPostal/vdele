<?php

namespace SITE_CORE;

class EmptyLayoutPages {
	
	public static $Login;
	
}

class EmptyLayout {
	
	public static $CurrentPage;
	
	public static $SiteTitle					= 'bdc-web';
	public static $PageHeaderText				= 'Страница авторизации';
	
	public static $RequireCSS					= '';
	public static $RequireJS					= '';
	
	//////////////////////////////////////////
	//			  Initialization			//
	//////////////////////////////////////////
	
	public static function Init() {
		EmptyLayoutPages::$Login	= MainDirs::$Root . 'login/';
		EmptyLayout::$CurrentPage	= null;
	}
	
	//////////////////////////////////////////
	//			  Start Layout				//
	//////////////////////////////////////////
	
	public static function StartLayout($PageAddress = '') {
		
		$PageAddress						= $PageAddress === '' ? EmptyLayout::$CurrentPage : $PageAddress;
		
		?><!DOCTYPE html>
<html style="height: 100%">
<title><?php echo EmptyLayout::$SiteTitle; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="windows-1251">
<link rel="shortcut icon" href="<?php echo MainDirs::$Site . MainDirs::$Images; ?>favicon.ico" type="image/x-icon">

									<!-- CSS -->
									
<link rel="stylesheet" href="<?php echo MainDirs::$Site . CSS::$CommonMain; ?>">
<link rel="stylesheet" href="<?php echo MainDirs::$Site . CSS::$CommonW3; ?>">
<link rel="stylesheet" href="<?php echo MainDirs::$Site . CSS::$CommonW3ThemeGray; ?>">
<link rel="stylesheet" href="<?php echo MainDirs::$Site . CSS::$CommonFontAwesome_4_7_0; ?>">
<link rel="stylesheet" href="<?php echo MainDirs::$Site . CSS::$CommonFontAwesome_5_0_9; ?>">
<?php echo EmptyLayout::$RequireCSS; ?>

									<!-- JS -->													<?php //async defer ?>
									
<script src="<?php echo MainDirs::$Site . JS::$CommonMainDefer; ?>" charset="utf-8" defer></script>
<script src="<?php echo MainDirs::$Site . JS::$CommonMain; ?>" charset="utf-8"></script>
<?php echo EmptyLayout::$RequireJS; ?>

<body style="height: 100%">

		
<?php
	}
	
	//////////////////////////////////////////
	//			 Finalize Layout			//
	//////////////////////////////////////////
	
	public static function FinalizeLayout($PageAddress = '') {
		$PageAddress = $PageAddress === '' ? EmptyLayout::$CurrentPage : $PageAddress;
		?>

</body>
</html>
		<?php 
	}
	
	//////////////////////////////////////////
	//			   Try Call Page			//
	//////////////////////////////////////////
	
	public static function TryCallPage($PageAddress = '') {
		$IsOpen			= false;
		$MLref			= new \ReflectionClass('SITE_CORE\EmptyLayoutPages');
		$StaticProps	= $MLref->getStaticProperties();
		foreach($StaticProps as $key => $value)
			if ($value && $value[0] == '/' && $value == $PageAddress) {
				EmptyLayout::SetCurrentPage($PageAddress);
				
				//
				// Получение прав пользователя
				//
				
				// LoginInfo::$IsWriteAccess = CheckAccessToPage($PageAddress);
				
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
		EmptyLayout::$CurrentPage	= $PageAddress;
		EmptyLayout::$PageHeaderText	= EmptyLayout::PageHeaderText($PageAddress);
	}

	//////////////////////////////////////////
	//			  PageHeaderText			//
	//////////////////////////////////////////
	
	public static function PageHeaderText($PageAddress) {
		$HeaderText	= EmptyLayout::$PageHeaderText;
		return $HeaderText;
	}
	
	//////////////////////////////////////////
	//		  Подключение CSS вручную.		//
	//////////////////////////////////////////

	public static function RequireCSSInHeader($CSSVar) {
		EmptyLayout::$RequireCSS .= '<link rel="stylesheet" href="' . MainDirs::$Site . $CSSVar . '">' . "\r\n";
	}
	
	//////////////////////////////////////////
	//		  Подключение JS вручную.		//
	//////////////////////////////////////////

	public static function RequireJSInHeader($JSVar, $RequireType = '') {
		EmptyLayout::$RequireJS .= '<script src="' . MainDirs::$Site . $JSVar . '"' . ' ' . $RequireType . ' charset="utf-8"></script>' . "\r\n";
	}

}

//////////////////////////////////////////
//			  Initialization			//
//////////////////////////////////////////

EmptyLayout::Init();

?>