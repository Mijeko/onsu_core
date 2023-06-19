<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");

if(!$USER->IsAuthorized())
{
	$APPLICATION->AuthForm("");
	return;
}
?>
<? if (CModule::IncludeModule('sale') && CModule::IncludeModule('yenisite.core') && Yenisite\Core\Tools::isComponentExist('bitrix:sale.personal.section')): ?>
<? $APPLICATION->IncludeComponent(
	"bitrix:sale.personal.section", 
	"bitronic2", 
	array(
		"COMPONENT_TEMPLATE" => "bitronic2",
		"SHOW_ACCOUNT_PAGE" => "Y",
		"SHOW_ORDER_PAGE" => "Y",
		"SHOW_PRIVATE_PAGE" => "Y",
		"SHOW_PROFILE_PAGE" => "Y",
		"SHOW_SUBSCRIBE_PAGE" => "Y",
		"SHOW_CONTACT_PAGE" => "Y",
		"SHOW_BASKET_PAGE" => "Y",
		"CUSTOM_PAGES" => "[[\"#favorite\",\"Избранное\",\"favorite\"],[\"/catalog/compare/list/?action=COMPARE\",\"Список сравнения\",\"compare\"],[\"subscribe/\",\"Подписки\",\"messege\"]]",
		"PATH_TO_PAYMENT" => "#SITE_DIR#personal/payment",
		"PATH_TO_CONTACT" => "#SITE_DIR#about/contacts/",
		"PATH_TO_BASKET" => "#SITE_DIR#personal/cart/",
		"PATH_TO_CATALOG" => "#SITE_DIR#catalog/",
		"SEF_MODE" => "Y",
		"SHOW_ACCOUNT_COMPONENT" => "Y",
		"SHOW_ACCOUNT_PAY_COMPONENT" => "Y",
		"ACCOUNT_PAYMENT_SELL_CURRENCY" => "RUB",
		"ACCOUNT_PAYMENT_PERSON_TYPE" => "1",
		"ACCOUNT_PAYMENT_ELIMINATED_PAY_SYSTEMS" => array(
			0 => "0",
		),
		"ACCOUNT_PAYMENT_SELL_SHOW_FIXED_VALUES" => "Y",
		"ACCOUNT_PAYMENT_SELL_TOTAL" => array(
			0 => "100",
			1 => "200",
			2 => "500",
			3 => "1000",
			4 => "5000",
			5 => "",
		),
		"ACCOUNT_PAYMENT_SELL_USER_INPUT" => "Y",
		"SAVE_IN_SESSION" => "Y",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CUSTOM_SELECT_PROPS" => array(
		),
		"PROP_1" => array(
		),
		"PROP_3" => array(
		),
		"PROP_2" => array(
		),
		"PROP_4" => array(
		),
		"ORDER_HISTORIC_STATUSES" => array(
			0 => "F",
		),
		"USE_AJAX_LOCATIONS_PROFILE" => "N",
		"COMPATIBLE_LOCATION_MODE_PROFILE" => "N",
		"SEND_INFO_PRIVATE" => "N",
		"CHECK_RIGHTS_PRIVATE" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_GROUPS" => "Y",
		"PER_PAGE" => "20",
		"NAV_TEMPLATE" => "",
		"SET_TITLE" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"SEF_FOLDER" => "#SITE_DIR#personal/",
		"FEEDBACK_RESIZER_SET" => "#BIG_BASKET_ICON_RESIZER_SET#",
		"PAYMENT_RESIZER_SET" => "#PAYMENT_RESIZER_SET#",
		"FEEDBACK_IBLOCK_TYPE" => "#FEEDBACK_IBLOCK_TYPE#",
		"FEEDBACK_ELEMENT_EXIST_IBLOCK_ID" => "#FEEDBACK_ELEMENT_EXIST_IBLOCK_ID#",
		"FEEDBACK_ELEMENT_EXIST_TITLE" => "Сообщить о поступлении",
		"FEEDBACK_ELEMENT_EXIST_PROP_1_TITLE" => "Дата заявки",
		"FEEDBACK_ELEMENT_EXIST_PROP_1" => array(
		),
		"FEEDBACK_ELEMENT_EXIST_PROP_2_TITLE" => "Контакты",
		"FEEDBACK_ELEMENT_EXIST_PROP_2" => array(
			0 => "EMAIL",
		),
		"FEEDBACK_ELEMENT_CONTACT_IBLOCK_ID" => "#FEEDBACK_ELEMENT_CONTACT_IBLOCK_ID#",
		"FEEDBACK_ELEMENT_CONTACT_TITLE" => "Товары по запросу",
		"FEEDBACK_ELEMENT_CONTACT_PROP_1_TITLE" => "Комментарий",
		"FEEDBACK_ELEMENT_CONTACT_PROP_1" => array(
			0 => "QUANTITY",
			1 => "COMMENT",
		),
		"FEEDBACK_ELEMENT_CONTACT_PROP_2_TITLE" => "Контакты",
		"FEEDBACK_ELEMENT_CONTACT_PROP_2" => array(
			0 => "NAME",
			1 => "EMAIL",
			2 => "PHONE",
		),
		"FEEDBACK_FOUND_CHEAP_IBLOCK_ID" => "#FEEDBACK_FOUND_CHEAP_IBLOCK_ID#",
		"FEEDBACK_FOUND_CHEAP_TITLE" => "Нашли дешевле",
		"FEEDBACK_FOUND_CHEAP_PROP_1_TITLE" => "Информация",
		"FEEDBACK_FOUND_CHEAP_PROP_1" => array(
			0 => "PRICE",
			1 => "PRICE_OTHER",
			2 => "URL",
		),
		"FEEDBACK_FOUND_CHEAP_PROP_2_TITLE" => "Контакты",
		"FEEDBACK_FOUND_CHEAP_PROP_2" => array(
			0 => "EMAIL",
			1 => "PHONE",
			2 => "FIO",
		),
		"FEEDBACK_PRICE_LOWER_IBLOCK_ID" => "#FEEDBACK_PRICE_LOWER_IBLOCK_ID#",
		"FEEDBACK_PRICE_LOWER_TITLE" => "Сообщить о снижении цены",
		"FEEDBACK_PRICE_LOWER_PROP_1_TITLE" => "Цена",
		"FEEDBACK_PRICE_LOWER_PROP_1" => array(
			0 => "PRICE",
		),
		"FEEDBACK_PRICE_LOWER_PROP_2_TITLE" => "Контакты",
		"FEEDBACK_PRICE_LOWER_PROP_2" => array(
			0 => "EMAIL",
		),
		"SEF_URL_TEMPLATES" => array(
			"index" => "",
			"orders" => "orders/",
			"account" => "account/",
			"subscribe" => "products/",
			"profile" => "profiles/",
			"profile_detail" => "profiles/#ID#/",
			"private" => "profile/",
			"order_detail" => "order/detail/#ID#/",
			"order_cancel" => "cancel/#ID#/",
		)
	),
	false
);
?>
<? else: ?>
<main class="container account-page account-settings">
	<h1><? $APPLICATION->ShowTitle(false) ?></h1>
	<div class="account row">
		<?include 'left_menu.php';?>
		<div class="account-content col-xs-12 col-sm-9 col-xl-10">
			<p>Это Ваш личный кабинет. Для навигации Вы можете воспользоваться меню личного кабинета, которое находится слева на странице</p>
		</div>
	</div>
</main>
<? endif ?>
<? require $_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php" ?>