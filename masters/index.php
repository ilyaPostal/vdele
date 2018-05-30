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

	Мастера<br/><br/>
	
	- Отображение исполнителей<br/>
	- Выбор категорий<br/>
	- Кнопка предложить задание<br/>


</div>

<?php Core\VDeleMainLayout::FinalizeLayout(); ?>
