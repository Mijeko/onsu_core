<?
use Bitrix\Main\Loader;
include_once "include_stop_statistic.php";

// for sef of standart bitrix component
$_SERVER["REQUEST_URI"] = !empty($_REQUEST["REQUEST_URI"]) ? $_REQUEST["REQUEST_URI"] : $_SERVER["REQUEST_URI"];
$_SERVER["SCRIPT_NAME"] = !empty($_REQUEST["SCRIPT_NAME"]) ? $_REQUEST["SCRIPT_NAME"] : $_SERVER["SCRIPT_NAME"];

if(isset($_GET['ajax_basket']) || (isset($_POST["rz_ajax"]) && $_POST["rz_ajax"] === "y"))
{
    require $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";
}else{
    if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
}
include_once $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lang/".LANGUAGE_ID."/header.php";

// @var $moduleId
require_once "include_module.php";

if (isset($_GET['action']) && $_GET['action'] == 'ADD2BASKET' && $moduleId == 'yenisite.bitronic2lite') {
    include_once $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'/ajax/basket_market.php';
    die();
}

if (Loader::IncludeModule('yenisite.core')) {
    $arParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', ($_REQUEST['CUSTOM_CACHE_KEY'] ?:false), CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
}
if(!is_array($arParams) || empty($arParams)) {
    die("[ajax died] loading params");
}

//fill $_GET & $_REQUEST from REQUEST_URI
if (($searchStart = strpos($_SERVER['REQUEST_URI'], '?')) !== false) {
    $search      = substr($_SERVER['REQUEST_URI'], $searchStart+1);

    $arCheckURIParams = array(
        'rz_all_elements',
        $arParams['OFFER_VAR_NAME']
    );

    $arGet = explode('&', $search);
    foreach ($arGet as $param) {
        $param = explode('=', $param);
        if (!in_array($param[0], $arCheckURIParams)) continue;

        $_GET[$param[0]] = $param[1];
        $_REQUEST[$param[0]] = $param[1];
    }
    unset($arGet, $param, $search);
}

@include_once "include_options.php";

if(isset($rz_b2_options['DEMO_CONTENT']['CATALOG']) && isset($arParams['IBLOCK_ID'])) {
    $arParams['IBLOCK_ID'] = $rz_b2_options['DEMO_CONTENT']['CATALOG'];
    $arParams["SEF_URL_TEMPLATES"]["sections"] = $rz_b2_options['DEMO_CONTENT']['CATALOG_SEF']['LIST_PAGE_URL'];
    $arParams["SEF_URL_TEMPLATES"]["section"] = $rz_b2_options['DEMO_CONTENT']['CATALOG_SEF']['SECTION_PAGE_URL'];
    $arParams["SEF_URL_TEMPLATES"]["element"] = $rz_b2_options['DEMO_CONTENT']['CATALOG_SEF']['DETAIL_PAGE_URL'];
    $arParams["SEF_URL_TEMPLATES"]["smart_filter"] = $rz_b2_options['DEMO_CONTENT']['CATALOG_SEF']['SECTION_PAGE_URL'] . "filter/#SMART_FILTER_PATH#/apply/";
}

if (!empty($_GET['action']) && empty($_GET[$arParams['ACTION_VARIABLE']])){
    $request = \Bitrix\Main\Context::getCurrent()->getRequest();
    $_GET[$arParams['ACTION_VARIABLE']] = $_GET['action'];
    $_REQUEST[$arParams['ACTION_VARIABLE']] = $_REQUEST['action'];
    $request->set($_REQUEST);
    unset($_GET['action']);
}

@include_once "custom_ext_filter.php";

//FOR CLEAR RESPONS FOR <div incl_area
$_SESSION["SESS_INCLUDE_AREAS"] = $_SESSION["SESS_INCLUDE_AREAS"] && $_REQUEST['action'] == 'ADD2BASKET' ? !$_SESSION["SESS_INCLUDE_AREAS"]  : $_SESSION["SESS_INCLUDE_AREAS"] ;

//show catalog page
$APPLICATION->IncludeComponent("bitrix:catalog", "", $arParams, false);

require $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php";

?>
