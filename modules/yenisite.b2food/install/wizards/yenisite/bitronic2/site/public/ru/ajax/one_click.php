<?
include_once "include_stop_statistic.php";

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

include_once "include_module.php";
include_once "include_options.php";

include_once($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lang/".LANGUAGE_ID."/ajax.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && !defined('BX_UTF')) 
{
	\Yenisite\Core\Tools::encodeAjaxRequest($_REQUEST);
	\Yenisite\Core\Tools::encodeAjaxRequest($_POST);
	CRZBitronic2Handlers::$encodeRegisterHandlers = true;
}
$arProps = array();
if (!empty($_REQUEST['PROPS'])) {
	$arProps = \Yenisite\Core\Tools::GetDecodedArParams($_REQUEST['PROPS']);
}
include $_SERVER["DOCUMENT_ROOT"].SITE_DIR."include_areas/catalog/one_click.php";