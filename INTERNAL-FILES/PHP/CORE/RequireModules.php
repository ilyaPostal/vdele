<?php

namespace SITE_CORE;

//
// Подключение средств для упрощённого получения данных.
//

require_once 'GetGlobalData.php';

//
// Настройки и расположения файлов
//

require_once 'Configuration.php';

//
// Запись системных сообщений.
//

require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$Core. 'Logs.php';

//
// Класс для работы с сессиями.
//

require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$Core . 'Sessions.php';

//
// Информация о пользователе.
//

require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$Core. 'Login.php';

//
// Средства подключения модулей во время выполнения
//

require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$Core. 'RequirePageModule.php';

//
// Средства преобразования данных
//

require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$Core. 'DataTransformation.php';

//
// Основной макет.
//

require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$Core. 'VDeleMainLayout.php';

//
// Пустой макет.
//

require_once $_SERVER['DOCUMENT_ROOT'] . MainDirs::$Core. 'EmptyLayout.php';

?>