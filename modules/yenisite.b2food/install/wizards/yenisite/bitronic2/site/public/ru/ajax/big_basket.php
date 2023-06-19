<?
include_once "include_stop_statistic.php";
require $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";

include_once "include_module.php";
include_once "include_options.php";
if (!CModule::IncludeModule('yenisite.core')) die('Error');
use Yenisite\Core\Tools;
use Yenisite\Core\Ajax;

Tools::encodeAjaxRequest($_POST);
Tools::encodeAjaxRequest($_REQUEST);
$arParams = Ajax::getParams('bitrix:sale.basket.basket', 'main_basket');

if (empty($_SESSION['RZ_BIG_BASKET_URL'])) {
	if(!isset($_POST["rz_ajax"]) || $_POST["rz_ajax"] !== "y") {
		die();
	}

	if (empty($_POST['self_url'])) {
		die();
	}

	if (in_array($_POST['tab'], array('items', 'delay', 'subscribe', 'na'))) {
		$_SESSION['RZ_BASKET_TAB'] = $_POST['tab'];
	}

	$_POST['self_url'] = str_replace('../', '', $_POST['self_url']);
	$_SESSION['RZ_BIG_BASKET_URL'] = $_POST['self_url'];

	if ($_REQUEST[$_REQUEST["action_var"]] == 'delete' && $_REQUEST['id'] == 'all') {
		if (CModule::IncludeModule('sale')) {
			CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
		}
		LocalRedirect($APPLICATION->GetCurPage());
	}
} else {
	$_POST['self_url'] = $_REQUEST['self_url'] = $_SESSION['RZ_BIG_BASKET_URL'];
	$_POST['rz_ajax_no_header'] = $_REQUEST['rz_ajax_no_header'] = 'y';
}

$path = realpath($_SERVER['DOCUMENT_ROOT'].$_POST['self_url']);

if (!empty($_REQUEST["BasketRefresh"]) || !empty($_REQUEST["BasketOrder"]) || isset($_REQUEST["action_var"]) && !empty($_REQUEST[$_REQUEST["action_var"]]) ) {
} else {
	$_SERVER['SCRIPT_FILENAME'] = $path;
	$_SERVER['REQUEST_URI'] = $_POST['self_url'];
	$_SERVER['SCRIPT_NAME'] = $_POST['self_url'];
	$_SERVER['PHP_SELF'] = $_POST['self_url'];
	unset($_SESSION['RZ_BIG_BASKET_URL']);
}

include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . "/lang/" . LANGUAGE_ID . '/header.php';

$APPLICATION->IncludeComponent('bitrix:sale.basket.basket', 'big_basket', $arParams);
?>
