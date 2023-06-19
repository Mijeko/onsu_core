<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
// AJAX PATH
$ajaxPath = SITE_DIR."ajax/cool-slider.php";
$arResult['ADD_URL_TEMPLATE'] = $ajaxPath."?".$arParams["ACTION_VARIABLE"]."=ADD2BASKET&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#&ajax_basket=Y";
$arResult['BUY_URL_TEMPLATE'] = $ajaxPath."?".$arParams["ACTION_VARIABLE"]."=BUY&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#&ajax_basket=Y";

$arResult['CURRENCY'] = CModule::IncludeModule("currency");
$boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']) && $arResult['CURRENCY'];
if (!$boolConvert)
	$strBaseCurrency = $arResult['CURRENCY'] ? CCurrency::GetBaseCurrency() : 'RUB';

if (CModule::IncludeModule('yenisite.market')) {
	$arResult['CHECK_QUANTITY'] = (CMarketCatalog::UsesQuantity($arParams['IBLOCK_ID']) == 1);
}
foreach ($arResult['ITEMS'] as $index => &$arItem) {
	$arItem = CRZBitronic2CatalogUtils::processItemCommon($arItem);
	$arItem['bFirst'] = $index == 0;
	if (!empty($arItem['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_ALT'])) {
		$imgAlt = $arItem['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_ALT'];
	} else {
		$imgAlt = $arItem['NAME'];
	}
	$arItem['PICTURE_PRINT_BIG']['ALT'] = $arItem['PICTURE_PRINT_SMALL']['ALT'] = $imgAlt;
	$arItem['PICTURE_PRINT_BIG']['SRC'] = CRZBitronic2CatalogUtils::getElementPictureById($arItem['ID'], $arParams['RESIZER_SET_BIG']);
	$arItem['PICTURE_PRINT_SMALL']['SRC'] = CRZBitronic2CatalogUtils::getElementPictureById($arItem['ID'], $arParams['RESIZER_SET_SMALL']);

	//Prices for MARKET
	if (CModule::IncludeModule('yenisite.bitronic2lite') && CModule::IncludeModule('yenisite.market')) {
		$prices = CMarketPrice::GetItemPriceValues($arItem['ID'], $arItem['PRICES']);
		if (count($prices) > 0) {
			unset($arItem['PRICES']);
		}
		$minPrice = false;
		foreach ($prices as $k => $pr) {
			$pr = floatval($pr);
			$arItem['PRICES'][$k]['VALUE'] = $pr;
			$arItem['PRICES'][$k]['PRINT_VALUE'] = $pr;
			if ((empty($minPrice) || $minPrice > $pr) && $pr > 0) {
				$minPrice = $pr;
			}
		}
		if ($minPrice !== false) {
			$arItem['MIN_PRICE']['VALUE'] = $arItem['MIN_PRICE']['VALUE'] = $minPrice;
			$arItem['MIN_PRICE']['PRINT_VALUE'] = $minPrice;
			$arItem['MIN_PRICE']['DISCOUNT_VALUE'] = $minPrice;
			$arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'] = $minPrice;
			$arItem['CATALOG_MEASURE_RATIO'] = 1;
			$arItem['CAN_BUY'] = $arItem['CAN_BUY'] = true;
		}
		$arItem['CHECK_QUANTITY'] = $arResult['CHECK_QUANTITY'];
		$arItem['CATALOG_QUANTITY'] = CMarketCatalogProduct::GetQuantity($arItem['ID'], $arItem['IBLOCK_ID']);
		
		if ($arItem['CHECK_QUANTITY'] && $arItem['CATALOG_QUANTITY'] <= 0) {
			$arItem['CAN_BUY'] = $arItem['CAN_BUY'] = false;
		}
		$arItem['CATALOG_TYPE'] = 1; //simple product
	}
	//end Prices for MARKET
	
	$arItem['ON_REQUEST'] = $arItem['ON_REQUEST'] = (empty($arItem['MIN_PRICE']) || $arItem['MIN_PRICE']['VALUE'] <= 0);

    if (CRZBitronic2Settings::isPro() && CRZBitronic2CatalogUtils::checkAvPrFotoForElement($arItem, $arParams)){
        unset ($arResult['ITEMS'][$index]);
        continue;
    }

	if (isset($arItem['OFFERS']) && !empty($arItem['OFFERS'])) {
		CRZBitronic2CatalogUtils::fillMinPriceFromOffers(
			$arItem,
			$boolConvert ? $arResult['CONVERT_CURRENCY']['CURRENCY_ID'] : $strBaseCurrency,
			$bForOrder = false
		);
	}

	if ($arItem['ON_REQUEST']) {
		$arItem['CAN_BUY'] = false;
	}
}
if (isset($arItem)) {
	unset($arItem);
}
