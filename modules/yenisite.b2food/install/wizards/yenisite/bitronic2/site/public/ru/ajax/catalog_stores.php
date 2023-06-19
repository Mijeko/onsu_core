<?
use Bitrix\Main\Loader;
use \Yenisite\Core\Catalog;
include_once "include_stop_statistic.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php";

if(!\Bitrix\Main\Application::GetInstance()->GetContext()->GetRequest()->IsAjaxRequest())
{
    die();
}
include_once $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lang/".LANGUAGE_ID."/header.php";

include_once "include_module.php";

global $rz_b2_options;
include $_SERVER["DOCUMENT_ROOT"] . SITE_DIR . "include_areas/header/settings.php";
$APPLICATION->RestartBuffer();

$templateName = 'tooltip';
if ($rz_b2_options['product-availability'] == 'expanded' && $_REQUEST['STORE_POSTFIX'] == 'detail'){
    $templateName = 'expanded';
}
if ($rz_b2_options['product-availability'] == 'tabs' && $_REQUEST['STORE_POSTFIX'] == 'detail'){
    $templateName = 'tabs';
}
if (Loader::IncludeModule('yenisite.core')) {
    $arParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
}
if(!is_array($arParams) || empty($arParams)) {
    die("[ajax died] loading params");
}

include_once "include_options.php";
include_once $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/components/bitrix/catalog/.default/include/prepare_params_element.php";

if ($rz_b2_options['store_amount_type'] == 'numeric') {
    $arMeasureData = Catalog::getMeasureOfProduct(intval($_REQUEST['ITEM_ID']));
}

//show catalog store amount
$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", $templateName, array(
    "PER_PAGE" => "100",
    "USE_STORE_PHONE" => $arPrepareParams["USE_STORE_PHONE"],
    "SCHEDULE" => $arParams["USE_STORE_SCHEDULE"],
    "USE_MIN_AMOUNT" => 'N',
    "MIN_AMOUNT" => $arPrepareParams["MIN_AMOUNT"],
    "ELEMENT_ID" => intval($_REQUEST['ITEM_ID']),
    "STORE_PATH"  =>  $arParams["STORE_PATH"],
    "MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
    "CACHE_TYPE" => $arPrepareParams["CACHE_TYPE"],
    "CACHE_TIME" => $arPrepareParams["CACHE_TIME"],
    "CACHE_GROUPS" => $arPrepareParams["CACHE_GROUPS"],
    'STORE_CODE' => $arPrepareParams["STORE_CODE"],
    "FIELDS" => $arPrepareParams['STORES_FIELDS'],
    'CONTAINER_ID_POSTFIX' => $_REQUEST['STORE_POSTFIX'],
    'STORE_DISPLAY_TYPE' => $arPrepareParams['STORE_DISPLAY_TYPE'],
    'STORES' => $arPrepareParams['STORES'],
    'SHOW_EMPTY_STORE' => $arParams['SHOW_EMPTY_STORE'],
    'MEASURE_NAME' => $arMeasureData['~SYMBOL_RUS']
),
    $component,
    array("HIDE_ICONS"=>"Y")
);
?>
