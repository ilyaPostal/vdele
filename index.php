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
        .my-green{
            color: #61BD4F;
        }
        .my-grey{
            color: #7F7F7F;
        }
        .background-green{
            background-color: #61BD4F;
        }
        .my-opacity{
            opacity: 0.7;
        }
        .opacity-disable:hover{
            opacity: 1;
        }
        .mySlides {display:none}
        .icon-block{
            font-size:30px;
            width: 60px;
            height:80px;
        }
        .border-green{
            border: 10px solid;
            border-color: rgba(97,189,79,0.3); 
        }
        .size-170{
            width: 170px;
            height:170px;
        }
        .size-reduce-165:hover{
            width: 165px;
            height: 165px;
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
				<a class="my-green" href="javascript:void(0)">Южно-Сахалинск</a>
				<div style="margin-top: 5px;">
					<b><font class="my-grey">+7 (914)</font> <font class="my-green">742-54-03</font></b>
				</div>
			</div>
			<div style="display: inline-block; height: 75px; overflow: hidden; padding: 10px 0;"> 
				<a href="javascript:void(0)" class="w3-button w3-green w3-round-large" style="padding: 5px 11px;">Оставить заявку</a>
				<div>
					<font class="my-green"><a href="#вход">Вход</a></font> / 
					<font class="my-green"> <a href="#регистрация">Регистрация</a></font>
				</div>
			</div>
		</div>
	</div>
	
	<div class="w3-display-container" style="margin-top: 75px; height: 653px; background-color: #DDD; overflow: hidden;">
        <!-- Слайды -->
        <img class="mySlides" src="<?php echo Core\MainDirs::$Images; ?>32.jpg" style="width:100%; min-height: 653px; min-width:1160px;">
        <img class="mySlides" src="<?php echo Core\MainDirs::$Images; ?>1212.jpg" style="width:100%; min-height: 653px; min-width:1160px;">
        <img class="mySlides" src="<?php echo Core\MainDirs::$Images; ?>electrician-1080554_1280.jpg" style="width:100%; min-height: 653px; min-width:1160px;">
        
        <script>
        var myIndex = 0;
        carousel();

        function carousel() {
            var i;
            var x = document.getElementsByClassName("mySlides");
            for (i = 0; i < x.length; i++) {
              x[i].style.display = "none";  
            }
            myIndex++;
            if (myIndex > x.length) {myIndex = 1}    
            x[myIndex-1].style.display = "block";  
            setTimeout(carousel, 5000);    
        }
        </script>
        
		<!-- Тестовый блок -->
		<div class="w3-display-middle w3-center" style="padding-bottom: 100px; width: 900px;">
            <div style="font-size:80px"><font class="my-green">С</font><font color="white">делаем дело за Вас</font>
            </div>
            <div style="font-size:23px; padding: 10px;"><font color="white">Поможем найти надёжного мастера для любых задач</font>
            </div>
            <div class="w3-center w3-white w3-round-large" style="width: 900px; padding: 3px 3px 5px;">
                <div style="display: inline-block; width: 724px;">
                    <input class="w3-input" type="text" placeholder="Что нужно сделать?">
                </div>
                <button ref="#оставить_заявку" class="w3-button w3-green w3-round-large" style="display: inline-block;">Оставить заявку</button>
            </div>
            <div style="color: white; text-align: left;">Например: <a href="#пример_поиска"><u>купить и доставить еду из ресторана</u></a>
            </div>
            <div style="font-size:20px; color:#1BDFF7; padding: 30px;"><img src="<?php echo Core\MainDirs::$Images; ?>chit2.png"><a href="#стать_мастером"><u>стать мастером и начать зарабатывать</u></a>
            </div>
        </div>
        
		<!-- Картинка внизу по центру -->
		<div class="w3-display-bottommiddle w3-center" style="height: 181px; width: 1200px;">
            <img class="my-opacity opacity-disable" src="<?php echo Core\MainDirs::$Images; ?>11.png" "display: inline-block;"
            ><img class="my-opacity opacity-disable" src="<?php echo Core\MainDirs::$Images; ?>21.jpg" "display: inline-block;"
            ><img class="my-opacity opacity-disable" src="<?php echo Core\MainDirs::$Images; ?>31.png" "display: inline-block;">
            <!--
            <div class="my-green-opacity" style="display: inline-block; width: 300px; height: 100px;">   
            </div><div class="my-green" style="display: inline-block; width: 300px; height: 100px;"> 
            </div><div class="my-green" style="display: inline-block; width: 300px; height: 100px;">  
            </div>
        -->
        </div>
		
	</div>
    <div style="width:1100px; margin:auto">
        <div style="width:1150px; padding: 0px 70px;">
            <div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px; ">Курьерские услуги</div>
                </div>
            </div
            ><div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px; ">Ремонт и строительство</div>
                </div>
            </div
            ><div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px; ">Грузоперевозки</div>
                </div>
            </div
            ><div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px; ">Уборка</div>
                </div>
            </div
            ><div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px; ">Компьютерная помощь</div>
                </div>
            </div
            ><div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px; ">Фото- и видео- услуги</div>
                </div>
            </div
            ><div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px; ">Web-разработка</div>
                </div>
            </div
            ><div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px; ">Установка и ремонт техники</div>
                </div>
            </div>
            <div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px; ">Мероприятия и промо-акции</div>
                </div>
            </div
            ><div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px; ">Дизайн</div>
                </div>
            </div
            ><div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px; ">Виртуальный помощник</div>
                </div>
            </div
            ><div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px; ">Ремонт цифровой техники</div>
                </div>
            </div
            ><div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px; ">Красота и здоровье</div>
                </div>
            </div
            ><div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px; ">Юридическая помощь</div>
                </div>
            </div
            ><div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px; ">Ремонт транспорта</div>
                </div>
            </div
            ><div style="display: inline-block; width: 240px; height:80px;">
                <div class="w3-display-container w3-left icon-block"><i class="w3-display-middle fa fa-envelope" aria-hidden="true"></i></div>
                <div class="w3-display-container w3-right" style="width: 180px; height:80px;">
                    <div class="w3-display-left" style="font-size:18px;">Репетиторы и образование</div>
                </div>
            </div>
            <div class="w3-center" style="max-width:960px;">
                <button ref="#все_категории" class="w3-button w3-white w3-border w3-border-green w3-round-large"><font class="my-green">Все категории</font></button>
            </div>
        </div>
        
        <div class="w3-center" style="width:1100px; padding: 0px 70px;">
            
            <div class="border-green" style="display: inline-block; margin:50px; padding:15px; font-size:40px; letter-spacing:-1px;">
                «вДеле» <br> экономит ваше время и деньги
            </div>
            
            <div>
                <div style="display: inline-block; width: 320px;">
                    <div class="w3-display-container" style="height:200px;">
                        <img class="w3-display-middle size-170 size-reduce-165" src="<?php echo Core\MainDirs::$Images; ?>hiw-1.JPG">
                    </div>
                    <div style="height:100px; font-size:25px;">
                        Оставьте заявку
                    </div>
                    <div style="height:100px;">
                        Опишите своими словами задачу, которую требуется выполнить. Укажите свою стоимость и срок выполнения.
                    </div>
                </div
                ><div style="display: inline-block; width: 320px;">
                    <div class="w3-display-container" style="height:200px;">
                        <img class="w3-display-middle size-170 size-reduce-165" src="<?php echo Core\MainDirs::$Images; ?>hiw-2.JPG">
                    </div>
                    <div style="height:100px; font-size:25px;">
                        Мастера предложат вам свои услуги и цены
                    </div>
                    <div style="height:100px;">
                        Уже через несколько минут вы начнете получать предложения от мастеров, готовых выполнить ваше задание.
                    </div>
                </div
                ><div style="display: inline-block; width: 320px;">
                    <div class="w3-display-container" style="height:200px;">
                        <img class="w3-display-middle size-170 size-reduce-165" src="<?php echo Core\MainDirs::$Images; ?>hiw-3.JPG">
                    </div>
                    <div style="height:100px; font-size:25px;">
                        Выберите лучшее предложение
                    </div>
                    <div style="height:100px;">
                        Вы можете выбрать мастера по цене, отзывам заказчиков, рейтингу, примерам работ.
                    </div>
                </div>
            </div>
            
        </div>
            
        <div class="w3-display-container w3-center" style="margin-top: 25px;">
            <img class="w3-round-large" src="<?php echo Core\MainDirs::$Images; ?>fghfgh.jpg" style="width:100%;">
            <div class="w3-display-topmiddle border-green" style="margin-top: 20px; padding:15px; font-size:40px; letter-spacing:-1px; color:white; width: 900px;">
                Создайте заявку <br> прямо сейчас и найдите исполнителя <br> за несколько минут
            </div>
            <button ref="#оставить_заявку" class="w3-button w3-display-bottommiddle w3-green w3-round-large" style="margin: 20px;">Оставить заявку</button>
        </div>
        
        <div class="w3-center" style="width:1100px; padding: 0px 70px;">
            <div class="border-green" style="display: inline-block; margin:50px; padding:15px; font-size:40px; letter-spacing:-1px;">
                Отзывы о мастерах
            </div>
        </div>

        <div class="w3-round-large" style="background-color: #555555; text-align: left; color: #FFFFFF; padding: 25px; margin:25px 0px; height:420px;">
            <div style="display: inline-block; width: 250px;">
                <font style="font-size:25px;">Как это работает</font>
                <br>
                Как оставить заявку
                <br>
                Как стать мастером
                <br>
                Заявки
                <br>
                Мастера
            </div>
            <div style="display: inline-block; width: 250px;">
                <font style="font-size:25px;">О нас</font>
                <br>
                О сервисе
                <br>
                Принципы безопасности
                <br>
                Отзывы
                <br>
                Положение о конфиденциальности
                <br>
                Реклама на сайте
                <br>
                Статьи
            </div>
            <div style="display: inline-block; width: 250px;">
                <font style="font-size:25px;">Служба поддержки</font>
                <br>
                Пользовательское соглашение
                <br>
                Контакты
            </div>
            <div style="display: inline-block; width: 250px;">
                <font style="font-size:25px;">Ваш город</font>
                <br>
            </div>
        
        </div>
    </div>
    
    <footer class="w3-center" style="background-color: #445460; color: #FFFFFF; font-size: 11px; padding: 3px 0px;">
        <p>Все права защищены Copyright © 2018</p>
    </footer>
    
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




<?php Core\VDeleMainLayout::FinalizeLayout(); ?>
