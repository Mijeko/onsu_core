<?
include_once "include_stop_statistic.php";

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

include_once "include_module.php";

include_once($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lang/".LANGUAGE_ID."/ajax.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && !defined('BX_UTF')) 
{
	$_REQUEST = $APPLICATION->ConvertCharsetArray($_REQUEST, 'utf-8', LANG_CHARSET);
}

include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/components/bitrix/catalog.set.constructor/bitronic2/popup.php";