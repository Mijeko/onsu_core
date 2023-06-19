<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if (isset($_REQUEST["backurl"]) && strlen($_REQUEST["backurl"])>0) 
	LocalRedirect($backurl);

$APPLICATION->SetTitle("Авторизация");
$APPLICATION->AddChainItem("Авторизация");
?>
<main class="container about-page">
	<div class="row">
		<div class="col-xs-12">
<p>Вы зарегистрированы и успешно авторизовались.</p>
 
Каждый зарегистрированный пользователь получает ряд преимуществ:
<ul>
	<li>Отслеживание состояния заказа</li>
	<li>Информация об акциях и скидках</li>
	<li>Индивидуальные предложения</li>
</ul>
 
<p><a href="<?=SITE_DIR?>">Вернуться на главную страницу</a></p>

		</div>
	</div>
</main>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>