<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;

if (!empty($arResult["PROPERTIES"]["RELATED_ITEMS"])) {
	$arResult['CATALOG_ID'] = $arResult["PROPERTIES"]["RELATED_ITEMS"]["LINK_IBLOCK_ID"];
	$arResult['CATALOG_PARAMS'] = array();
	$arMainParams = $arParams;

	$arParams = array();
	if (Loader::IncludeModule('yenisite.core')) {
		$arParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
	}

	// @var $arPrepareParams
	include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/prepare_params_element.php';

	$arResult['CATALOG_PARAMS'] = $arPrepareParams;
	$arParams = $arMainParams;
	unset($arMainParams);
}
?>

<?$frame = new \Bitrix\Main\Page\FrameBuffered("news_epilog_related");?>
<?$frame->begin('');?>
<?if (!empty($arResult["CATALOG_ID"]) && Loader::includeModule('catalog')):?>
<?$APPLICATION->IncludeComponent("bitrix:catalog.recommended.products", "bitronic2", array(
	"HEADER_TEXT" => $arParams["RELATED_HEADER_TEXT"],
	"DISPLAY_COMPARE_SOLUTION" => $arResult['CATALOG_PARAMS']['DISPLAY_COMPARE_SOLUTION'],
	"DISPLAY_FAVORITE" => $arResult['CATALOG_PARAMS']["DISPLAY_FAVORITE"],
	"DISPLAY_ONECLICK" => $arResult['CATALOG_PARAMS']["DISPLAY_ONECLICK"],
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"ID" => $arResult['ID'],
	"PROPERTY_LINK" => "RELATED_ITEMS",
	"SHOW_PRODUCTS_".$arResult["CATALOG_ID"] => "Y",///////////
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"BASKET_URL" => $arResult['CATALOG_PARAMS']["BASKET_URL"],
	"ACTION_VARIABLE" => $arResult['CATALOG_PARAMS']["ACTION_VARIABLE"],
	"PRODUCT_ID_VARIABLE" => $arResult['CATALOG_PARAMS']["PRODUCT_ID_VARIABLE"],
	"PRODUCT_QUANTITY_VARIABLE" => $arResult['CATALOG_PARAMS']["PRODUCT_QUANTITY_VARIABLE"],
	"ADD_PROPERTIES_TO_BASKET" => (isset($arResult['CATALOG_PARAMS']["ADD_PROPERTIES_TO_BASKET"]) ? $arResult['CATALOG_PARAMS']["ADD_PROPERTIES_TO_BASKET"] : ''),
	"PRODUCT_PROPS_VARIABLE" => $arResult['CATALOG_PARAMS']["PRODUCT_PROPS_VARIABLE"],
	"PARTIAL_PRODUCT_PROPERTIES" => (isset($arResult['CATALOG_PARAMS']["PARTIAL_PRODUCT_PROPERTIES"]) ? $arResult['CATALOG_PARAMS']["PARTIAL_PRODUCT_PROPERTIES"] : ''),
	"PAGE_ELEMENT_COUNT" => $arResult['CATALOG_PARAMS']["PAGE_ELEMENT_COUNT"],
	"SHOW_OLD_PRICE" => $arResult['CATALOG_PARAMS']['SHOW_OLD_PRICE'],
	"SHOW_DISCOUNT_PERCENT" => $arResult['CATALOG_PARAMS']['SHOW_DISCOUNT_PERCENT'],
	"PRICE_CODE" => $arResult['CATALOG_PARAMS']["PRICE_CODE"],
	"SHOW_PRICE_COUNT" => $arResult['CATALOG_PARAMS']["SHOW_PRICE_COUNT"],
	"PRODUCT_SUBSCRIPTION" => 'N',
	"PRICE_VAT_INCLUDE" => $arResult['CATALOG_PARAMS']["PRICE_VAT_INCLUDE"],
	"USE_PRODUCT_QUANTITY" => $arResult['CATALOG_PARAMS']['USE_PRODUCT_QUANTITY'],
	"SHOW_NAME" => "Y",
	"SHOW_IMAGE" => "Y",
	"MESS_BTN_BUY" => $arResult['CATALOG_PARAMS']['MESS_BTN_BUY'],
	"MESS_BTN_DETAIL" => $arResult['CATALOG_PARAMS']["MESS_BTN_DETAIL"],
	"MESS_NOT_AVAILABLE" => $arResult['CATALOG_PARAMS']['MESS_NOT_AVAILABLE'],
	"MESS_BTN_SUBSCRIBE" => $arResult['CATALOG_PARAMS']['MESS_BTN_SUBSCRIBE'],
	"HIDE_NOT_AVAILABLE" => $arResult['CATALOG_PARAMS']["SLIDERS_HIDE_NOT_AVAILABLE"],
	//"OFFER_TREE_PROPS_".$arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"],
	//"OFFER_TREE_PROPS_".$arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"],
	"ADDITIONAL_PICT_PROP_".$arResult["CATALOG_ID"] => $arResult['CATALOG_PARAMS']['ADD_PICT_PROP'],
	//"ADDITIONAL_PICT_PROP_".$arRecomData['OFFER_IBLOCK_ID'] => $arResult['CATALOG_PARAMS']['OFFER_ADD_PICT_PROP'],
	//"PROPERTY_CODE_".$arRecomData['OFFER_IBLOCK_ID'] => array(),
	"CONVERT_CURRENCY" => $arResult['CATALOG_PARAMS']["CONVERT_CURRENCY"],
	"CURRENCY_ID" => $arResult['CATALOG_PARAMS']["CURRENCY_ID"],
	"RESIZER_SECTION" => $arResult['CATALOG_PARAMS']['RESIZER_SETS']['RESIZER_SECTION'],
	'HOVER-MODE' => $arParams['HOVER-MODE'],
))?>
<?endif?>
<?$frame->end()?>