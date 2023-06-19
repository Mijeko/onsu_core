<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление заказа");

$orderId = intval($_REQUEST['id']) ? :intval($_REQUEST['ORDER_ID']);
?>
<main class="container order-successful-page">
	<h2 class="hidden-xs">Оформление заказа</h2>
	<img src="<?=SITE_TEMPLATE_PATH?>/img/4sprites/smile.png" alt="Заказ оформлен успешно">
	<p class="order-success"><strong>Заказ сформирован</strong></p>
	<p>Ваш заказ <strong class="order-number">№<a href="<?=SITE_DIR?>personal/orders/?ID=<?=$orderId?>"><?=$orderId?></a></strong> успешно создан.</p>
	<p>Вы можете следить за выполнением своего заказа в <a href="<?=SITE_DIR?>personal/orders/" class="link"><span class="text">Личном кабинете</span></a>.
	Обратите внимание, что для входа в этот раздел вам необходимо будет ввести логин и пароль пользователя сайта.</p><?/*
	<p><strong>Оплата заказа:</strong></p>
	<p class="payment-type">Наличные курьеру</p>*/?>
</main>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>