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

	Регистрация<br/><br/>
	
	Регистрация пользователя (Расширенные данные)

</div>

<?php Core\VDeleMainLayout::FinalizeLayout(); ?>
