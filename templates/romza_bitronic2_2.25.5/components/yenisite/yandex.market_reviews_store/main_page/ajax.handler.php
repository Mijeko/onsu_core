<?
$_SERVER['REQUEST_URI'] = $_REQUEST['URL'];
?>
<?require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");?>
<?
$APPLICATION->IncludeComponent("yenisite:yandex.market_reviews_store.query", "main", array(), false);
?>