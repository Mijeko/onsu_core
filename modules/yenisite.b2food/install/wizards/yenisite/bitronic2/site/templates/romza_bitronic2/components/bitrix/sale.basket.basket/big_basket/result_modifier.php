<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Yenisite\Core\Ajax;
use Bitrix\Main\Loader;

global $rz_b2_options;

if (strlen($arParams['DELIVERY_URL']) < 1) {
	$arParams['DELIVERY_URL'] = SITE_DIR.'about/delivery/';
}
$arParams['SHOW_ARTICLE'] = ($rz_b2_options["block_show_article"] !== 'N');
$arParams['SHOW_ONECLICK'] = ($rz_b2_options["block_show_oneclick"] !== "N" && CModule::IncludeModule("yenisite.oneclick"));

$arResult['FULL_PRICE_WITHOUT_DISCOUNT'] = 0;
$arResult['CATALOG_PARAMS'] = array();
$arResult['USE_STORE'] = false;
if ($rz_b2_options['convert_currency']) {
    $arParams['CURRENCY_ID'] = $rz_b2_options['active-currency'];
}

if(is_array($arResult["GRID"]["ROWS"]))
{
	if (Loader::IncludeModule('yenisite.core')) {
		$catalogParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
		if (is_array($catalogParams) && !empty($catalogParams)) {
			$catalogParams['STORE_DISPLAY_TYPE'] = $rz_b2_options['store_amount_type'];
			$arResult['USE_STORE'] = $catalogParams["USE_STORE"] == "Y" && Bitrix\Main\ModuleManager::isModuleInstalled("catalog");
			$arResult['CATALOG_PARAMS'] = $catalogParams;
		}
	}
	foreach($arResult["GRID"]["ROWS"] as &$arItem)
	{
        $bConvertCurrency = !empty($arParams['CURRENCY_ID']) && $arItem['CURRENCY'] != $arParams['CURRENCY_ID'];
        if($bConvertCurrency)
        {
            $arResult['NEED_CONVERT_CURRENCY'] = true;
            $arItem["PRICE"] = CCurrencyRates::ConvertCurrency($arItem["PRICE"], $arItem['CURRENCY'], $arParams['CURRENCY_ID']);
            $arItem["FULL_PRICE"] = CCurrencyRates::ConvertCurrency($arItem["FULL_PRICE"], $arItem['CURRENCY'], $arParams['CURRENCY_ID']);
            $arItem["DISCOUNT_PRICE"] = CCurrencyRates::ConvertCurrency($arItem["DISCOUNT_PRICE"], $arItem['CURRENCY'], $arParams['CURRENCY_ID']);
            $arItem['CURRENCY'] = $arParams['CURRENCY_ID'];
        }
		$arItem['CAN_BUY'] = CRZBitronic2CatalogUtils::getAvailableStatus($arItem['PRODUCT_ID'], $arItem['CAN_BUY']) ? 'Y' : 'N';
		$arItem['AVAILABLE_QUANTITY'] = CRZBitronic2CatalogUtils::getStoresCount($arItem['PRODUCT_ID'], $arItem['AVAILABLE_QUANTITY']);
		$arItem['PICTURE_PRINT']['SRC'] = CRZBitronic2CatalogUtils::getElementPictureById($arItem['PRODUCT_ID'], $arParams['RESIZER_BASKET_PHOTO']);
		$arItem['SUM_NOT_FORMATED'] = $arItem['QUANTITY'] * $arItem['PRICE'];
		$arItem['FULL_SUM_NOT_FORMATED'] = $arItem['QUANTITY'] * $arItem['FULL_PRICE'];
		$arItem['FOR_ORDER'] = false;


		$arProduct = CCatalogProduct::GetByID($arItem['PRODUCT_ID']);
		$arItem['SUBSCRIBE'] = $arProduct['SUBSCRIBE'];
		if ($arItem['CAN_BUY'] == 'Y') {
			$arResult['FULL_PRICE_WITHOUT_DISCOUNT'] += $arItem['FULL_SUM_NOT_FORMATED'];
			$arItem['FOR_ORDER'] = ('Y' == $arProduct['QUANTITY_TRACE'] && 'Y' == $arProduct['CAN_BUY_ZERO'] && 0 >= $arItem['AVAILABLE_QUANTITY']);
		}
	}
	unset($arItem);
}


$arResult['CURRENCIES'] = CRZBitronic2CatalogUtils::getCurrencyArray();
if ($arResult['NEED_CONVERT_CURRENCY']){
    $arResult["allSum"] =  CCurrencyRates::ConvertCurrency($arResult["allSum"], $arResult['CURRENCY'], $arParams['CURRENCY_ID']);
    $arResult["allVATSum"] =  CCurrencyRates::ConvertCurrency($arResult["allVATSum"], $arResult['CURRENCY'], $arParams['CURRENCY_ID']);
    $arResult["DISCOUNT_PRICE_ALL"] =  CCurrencyRates::ConvertCurrency($arResult["DISCOUNT_PRICE_ALL"], $arResult['CURRENCY'], $arParams['CURRENCY_ID']);
    $arResult['CURRENCY'] = $arParams['CURRENCY_ID'];
}

if(!\Bitrix\Main\Loader::includeModule('yenisite.core')) {
	die('Module yenisite.core not installed!');
}
unset($arParams['COLUMNS_LIST_EXT']);
Ajax::saveParams($this, $arParams, 'main_basket');