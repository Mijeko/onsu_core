<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use \Bitrix\Main\Page\Asset;
use \Bitrix\Main\Page\AssetLocation;

unset($templateData['~AJAX_PARAMS']['PAGE_PARAMS']);

$JSString = '<script>RZB2.ajax.Vote.arParams["'.$templateData['~AJAX_PARAMS']['SESSION_PARAMS'].'"] = '.CUtil::PhpToJSObject($templateData["~AJAX_PARAMS"]).';</script>';

if ($_SERVER['HTTP_BX_AJAX'] !== null || $_SERVER["HTTP_X_REQUESTED_WITH"] === "XMLHttpRequest") {
	echo $JSString;
} else {
	Asset::getInstance()->addString($JSString, $unique = true, $location = AssetLocation::AFTER_JS);
}
?>
