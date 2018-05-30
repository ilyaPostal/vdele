<?php

use SITE_CORE	AS Core;


try {
	
	//Core\LogWrite('Test');
	
} catch(Exception $ex) {
	Core\LogWrite($ex->getMessage());
	die($ex->getMessage());
}

Core\VDeleMainLayout::StartLayout();

?>

<div>

	<br/>

	Все Задания<br/><br/>
	
	- Отображение заказов<br/>
	- Выбор категорий<br/>
	- Сортировка:<br/>
	по возрастанию цен<br/>
	по убыванию цен<br/>
	по наименованию<br/>
	по рейтингу<br/>
	- Фильтр:<br/>
	Дата<br/>
	Цена (от до)<br/>
	Категории<br/>
	Вид Оплаты<br/>
	- Кнопка Подробнее<br/>


</div>

<?php Core\VDeleMainLayout::FinalizeLayout(); ?>
