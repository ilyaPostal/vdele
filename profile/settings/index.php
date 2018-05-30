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
	
	Настройки<br/><br/>

	Различные настройки профиля:<br/>
	- Смена пароля<br/>
	- Смена фотографии аватара<br/>
	- Я ХОЧУ СТАТЬ МАСТЕРОМ<br/>

</div>

<?php Core\VDeleMainLayout::FinalizeLayout(); ?>
