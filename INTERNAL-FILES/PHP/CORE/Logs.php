<?php

namespace SITE_CORE;

//////////////////////////////////////////////////////////////////////////////////////////
//		   Хранятся логи пол года. Удаление в функции ErrorHandler(), LogsDelete.		//
//////////////////////////////////////////////////////////////////////////////////////////

class LogsConfiguration {
	
	//
	// Путь к логам
	//
	
	public static function GetPath() {
		return $_SERVER['DOCUMENT_ROOT'] . MainDirs::$Logs;
	}
	
	//
	// Вероятность запуска удаления 1 / 10 при записи в лог.
	//
	
	public static $gc_probability	= 1;
	public static $gc_divisor		= 10;
	
	//
	// Время хранения логов. От текущей даты.
	//
	
	public static $SaveTimeDiffStr = '-1 month';//'-6 month'; // '-6 day'
}

//
// Свой обработчик ошибок PHP
//

function ErrorHandler($errno, $errstr, $errfile, $errline)
{
	
	$ErrorNumberCodeCaption	= null;
	$ConstantArrays			= get_defined_constants(true);
	$ErrorNumberCodeCaption	= array_search($errno, $ConstantArrays['Core']);
	$ErrorNumberCodeCaption	= $ErrorNumberCodeCaption == null ? 'Неизвестный тип ошибки' : $ErrorNumberCodeCaption;
	
	$ErrorMessage = "<b>$ErrorNumberCodeCaption</b> [$errno] $errstr<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Файл:  $errfile	<br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ошибка в строке: $errline <br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Пользователь: " . (LoginInfo::$UserName != null ? LoginInfo::$UserName : 'не определён') . " <br />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IP пользователя: " . LoginInfo::$IP . " <br />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PHP " . PHP_VERSION . " (" . PHP_OS . ")<br /><br />";
	
	//
	// Запись в лог
	//
	
	LogWrite($ErrorMessage);
	throw new \Exception ($ErrorMessage);
	
}

//
// Регистрация своего обработчика ошибок
//

\set_error_handler("SITE_CORE\ErrorHandler", E_ALL);

//
// Запись в лог
//

function LogWrite($Message, $Prefix = 'MainLog_') {
	$FileName		= $Prefix . date('Y-m-d'). '.html';
	$LogsPath		= LogsConfiguration::GetPath();
	if (!is_dir($LogsPath))
		mkdir($LogsPath);
	$FullPath		= $LogsPath . $FileName;
	$FullMessage	= '<br>' . date("H:i:s") . '  -  ' . $Message . '<br>';
	if (!file_exists($FullPath))
		//$FullMessage = '<meta charset="utf-8">' . $FullMessage;
		$FullMessage = '<meta charset="windows-1251">' . $FullMessage;
	file_put_contents($FullPath, $FullMessage, FILE_APPEND);
	
	//
	// Удаление старых логов c учётом вероятности запуска удаления
	//
	
	LogsDeleteByProbability();
}

//
// Удаление логов
//

function LogsDeleteByProbability() {
	if (mt_rand(LogsConfiguration::$gc_probability, LogsConfiguration::$gc_divisor) == 1) {
		$SaveDate = new \DateTime();
		$SaveDate->modify(LogsConfiguration::$SaveTimeDiffStr);
		LogsDelete($SaveDate);
	}
}

function LogsDelete($SaveDate) {
	$LogsPath	= LogsConfiguration::GetPath();
	if (!is_dir($LogsPath))
		mkdir($LogsPath);
	//$Files		= scandir($LogsPath, SCANDIR_SORT_NONE);
	//$Files		= scandir($LogsPath, SCANDIR_SORT_ASCENDING);
	$Files		= scandir($LogsPath, SCANDIR_SORT_NONE);
	$Files		= array_values(array_diff($Files, array('..', '.', 'desktop.ini')));
	$FilesCoun	= count($Files);
	for ($i = 0; $i < $FilesCoun; $i++) {
		$CurrentFile	= $LogsPath . $Files[$i];
		$DateTimeModify = \DateTime::createFromFormat('Y-m-d', date('Y-m-d', filemtime($CurrentFile)));
		if ($DateTimeModify < $SaveDate) {
			try {
				unlink($CurrentFile);
			} catch(\Exception $ex) {
				
			}
		} 
		
// 		else {
			
// 			//
// 			// Выход из цикла с учётом того что в
// 			// папке нет лишних файлов, они отсортированны
// 			// в алфавитном порядке, и логи
// 			// извне не редактируются
// 			//
			
// 			break;
// 		}
	}
}

//
// Класс исключения с автоматической записью в лог
//

class ExceptionToLog extends \Exception
{
	public function __construct($message = null, $code = 0, \Exception $previous = null) {
		parent::__construct($message, $code, $previous);
		if ($message !== null) {
			//LogsDeleteByProbability();
			LogWrite($message);
		}
	}
}

?>