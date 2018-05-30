<?php

//
// Настройка .htaccess в корне сайта.
//
// Все обращения к клиентским файлам(CSS, JS, images...)
// должны получать прямой доступ(без перенаправлений).
//
// Все остальные обращения(обращения к страницам PHP)
// должны переадресовываться на этот файл.
//

namespace SITE_CORE;


//////////////////////////////////////////////
//		 Подключение основных модулей		//
//////////////////////////////////////////////

require_once 'RequireModules.php';


//////////////////////////////////////////////
//				Точка входа					//
//////////////////////////////////////////////

Main($_SERVER['SERVER_NAME'], $_SERVER['REQUEST_URI']);

function Main($SiteName, $RequestUri) {
	
	$PageName	= null;
	
	try {
		
		//
		// Получение адреса страницы без GET запроса
		//
		
		if ($RequestUri) {
			$IndexQuery	= strpos($RequestUri, '?');
			$PageName	= $IndexQuery ? substr($RequestUri, 0, strpos($RequestUri, '?')) : $RequestUri;
		}
		
		//
		// Если адрес не является страницей
		//
		
		if ($PageName == null || $PageName[0] != '/') {
			PageError404();
			return;
		}
		
		
		//
		// Если пользователь неавторизован, то перенаправляем на страницу авторизации.
		//
		
// 		if (LoginInfo::$UserID == null && !($PageName == EmptyLayoutPages::$Login)) {
// 			header('Location: ' . MainDirs::$Site . EmptyLayoutPages::$Login);
// 			exit;
// 		}
		
		
		//
		// Определение настроек подключения к БД
		//

		DBConfiguration::Init($SiteName);

		
		//
		// Попытка найти и вызванить сраницу из основного макета 
		//

		//$IsOpen = VDeleMainLayout::TryCallPage($PageName, function() { echo 'Hello World';});
		$IsOpen = VDeleMainLayout::TryCallPage($PageName);
		
		
		//
		// Попытка найти и вызванить сраницу из пустого макета 
		//
		
		if (!$IsOpen)
			$IsOpen = EmptyLayout::TryCallPage($PageName);
		
			
		//
		// Если страницы не нашлось,
		// то вывод страницы с ошибкой 404.
		//
		
		if (!$IsOpen)
			PageError404();
		
		
	} catch (\Exception $ex) {
		echo 'Ошибка при открытии страницы: ' . $ex->getMessage();
		exit;
	}
		
}

//
// Открытие страницы с ошибкой 404 (страница отсутствует).
//

function PageError404() {
	?><!DOCTYPE html>
	<html>
	<meta charset="utf-8">
	<h1>Страница не существует.</h1>
	<?php 
}

?>