<?php

use SITE_CORE	AS Core;
use SITE_DB 	AS DB;


try {
	
	$JsonPost = Core\GetGlobalData::InputJsonObject();
	
	
	//
	// GET
	//
	
	if ($JsonPost == null) {
		
		
	//
	// POST
	//
		
	} else {
		
		//
		// Авторизация
		//
		
		if ($JsonPost["action"] == "LogIn") {

			try {
				
				Core\RequireModule::DBAccess();
				$DSQL	= new DB\DirectSQL(DB\Connections::GetPDOMySQL());
				$DSQL->Select("SELECT `id`, `is_active`, `type_id`, `user_name`, `password`
										, `first_name`, `last_name`, `father_name`
								FROM vdele_users
								WHERE BINARY user_name = :UserName AND BINARY `password` = :UserPassword;"
						, array('UserName' => $JsonPost['UserName'], 'UserPassword' => $JsonPost['UserPassword']));
				
				if (count($DSQL->SelectResult) > 0) {
					if ($DSQL->SelectResult[0]['is_active']) {
						
						Core\LoginInfo::Save($DSQL->SelectResult[0]['id'], $JsonPost['UserName'], $DSQL->SelectResult[0]['type_id']);
						
						Core\LogWrite('Вход учётной записью: ' . $JsonPost['UserName'] . '&nbsp;&nbsp; IP: ' . Core\GetGlobalData::ClientIP(), 'AccessLog_');
						
						echo Core\Ajax::CreateResponse();
						
					} else {
						throw new Exception("Отказано в доступе.");
					}
				} else {
					throw new Exception("Отправлены неверные данные.");
				}
			} catch (Exception $e) {
				Core\LogWrite($e->getMessage() . ' Учётная запись: ' . $JsonPost['UserName'] . '&nbsp;&nbsp; IP: ' . Core\GetGlobalData::ClientIP(), 'AccessLog_');
				echo Core\Ajax::CreateResponse(false, $e->getMessage());
			}
			
		//
		// Сброс авторизации
		//
			
		} else if ($JsonPost["action"] == "LogOut") {
			
			try {
				
				$IsRealyReset = Core\LoginInfo::$UserID;
				
				if (Core\LoginInfo::$UserID) {
					Core\LogWrite('Выход учётной записью: ' . Core\LoginInfo::$UserName . '&nbsp;&nbsp; IP: ' . Core\LoginInfo::$IP , 'AccessLog_');
				}
				
				if (isset($_COOKIE)) {
					foreach ($_COOKIE as $key => $value) {
						setcookie($key, '', time() - 3600);
					}
				}
				
				Core\LoginInfo::Clear();
				
				echo Core\Ajax::CreateResponse(true, ($IsRealyReset ? 'Успешный сброс авторизации.' : 'Авторизация отсутствовала.'));
				
			} catch (Exception $e) {
				Core\LogWrite($e->getMessage() . ' Учётная запись: ' . Core\LoginInfo::$UserName . '&nbsp;&nbsp; IP: ' . Core\GetGlobalData::ClientIP(), 'AccessLog_');
				echo Core\Ajax::CreateResponse(false, $e->getMessage());
			}
		}
		
		exit;
	}
	
} catch(Exception $ex) {
	Core\LogWrite($ex->getMessage());
	die($ex->getMessage());
}

Core\VDeleMainLayout::StartLayout();

?>	

<script>

	var UserInfo = <?php echo Core\Json::Encode(Core\LoginInfo::GetAsArray());  ?>;

</script>

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
	
	<script>

	//
	// Авторизация
	//
	
// 	AjaxSendObject(window.location.href, { action : "LogIn", UserName : "Syzako", UserPassword : "aspirine" }
// 											, function (ResponseObject) { alert('Авторизация прошла успешно.'); }
// 											, function (ResponseMessage) { alert('Ошибка на сервере: ' + ResponseMessage); }
// 											, function (ResponseMessage) { alert('Ошибка в браузере: ' + ResponseMessage); });
	
	//
 	// Сброс авторизации
	//
	
// 	AjaxSendObject(window.location.href, { action : "LogOut" }
// 										, function (ResponseObject) { alert(ResponseObject.Message); }
// 										, function (ResponseMessage) { alert('Ошибка на сервере: ' + ResponseMessage); }
// 										, function (ResponseMessage) { alert('Ошибка в браузере: ' + ResponseMessage); });
	
	</script>


</div>

<?php Core\VDeleMainLayout::FinalizeLayout(); ?>
