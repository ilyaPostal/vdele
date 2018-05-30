<?php

use SITE_CORE	AS Core;


try {
	
	//Core\LogWrite('Test');
	// echo Test;
	// exit;
	
} catch(Exception $ex) {
	Core\LogWrite($ex->getMessage());
	die($ex->getMessage());
}

Core\VDeleMainLayout::StartLayout();

?>

<div>

	<style>
		.my-bar > a {
			height: 75px;
			line-height: 57px;
		}
	</style>

	<div class="w3-top w3-center" style="height: 75px; background-color: #FFF;">
		<div style="display: inline-block; width: 26em; text-align: right;">
			<div class="w3-bar my-bar" style="display: inline-block; height: 75px;">
				<a href="#заявки" class="w3-bar-item w3-button" style="">Заявки</a>
				<a href="#мастера" class="w3-bar-item w3-button">Мастера</a>
				<a href="#о сервисе" class="w3-bar-item w3-button">О сервисе</a>
			</div>
		</div>
		<img src="<?php echo Core\MainDirs::$Images; ?>logo_vdele.png" 
				style="display: inline-block; width:100px; height: 100px; margin: 0 30px; transform: translateY(-35px);">
		<div style="display: inline-block; width: 26em;">
			<div style="display: inline-block; height: 75px; overflow: hidden; padding: 13px 0 20px 0; margin: 0 30px 0 0;"> 
				<a href="javascript:void(0)">Южно-Сахалинск</a>
				<div style="margin-top: 5px;">
					<b><font color="#7F7F7F">+7 (914)</font> <font color="#61BD4F">742-54-03</font></b>
				</div>
			</div>
			<div style="display: inline-block; height: 75px; overflow: hidden; padding: 10px 0;"> 
				<a href="javascript:void(0)" class="w3-button w3-green w3-round" style="padding: 5px 11px;">Оставить заявку</a>
				<div>
					<font color="green" font-style=bold><a href="#вход">Вход</a></font> / 
					<font color="green" font-style=bold> <a href="#регистрация">Регистрация</a></font>
				</div>
			</div>
		</div>
	</div>
	
	<div class="w3-display-container" style="margin-top: 75px; height: 500px; background-color: #DDD;">
	
		<!-- Тестовый блок -->
		<div class="w3-display-middle">
			<div style="display: inline-block; width: 26em;">
				<div style="display: inline-block; height: 75px; overflow: hidden; padding: 13px 0 20px 0; margin: 0 30px 0 0;"> 
					<a href="javascript:void(0)">Южно-Сахалинск</a>
					<div style="margin-top: 5px;">
						<b><font color="#7F7F7F">+7 (914)</font> <font color="#61BD4F">742-54-03</font></b>
					</div>
				</div>
				<div style="display: inline-block; height: 75px; overflow: hidden; padding: 10px 0;"> 
					<a href="javascript:void(0)" class="w3-button w3-green w3-round" style="padding: 5px 11px;">Оставить заявку</a>
					<div>
						<font color="green" font-style=bold><a href="#вход">Вход</a></font> / 
						<font color="green" font-style=bold> <a href="#регистрация">Регистрация</a></font>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Картинка внизу по центру -->
		
		
		
	</div>
	
	<!-- 
	

	
	 -->
	


</div>

<?php Core\VDeleMainLayout::FinalizeLayout(); ?>
