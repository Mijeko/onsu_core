<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
$arResult = CRZBitronic2CatalogUtils::processItemCommon($arResult);
$imgAlt = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);

$arResult['PICTURE_PRINT']['ALT'] = $imgAlt;
$arResult['PICTURE_PRINT']['SRC'] = CRZBitronic2CatalogUtils::getElementPictureById($arResult['ID'], $arParams['RESIZER_SETS']['RESIZER_SECTION']);

if ($arResult['MODULES']['catalog'])
{
	$arResult['CATALOG'] = true;
	if (!isset($arResult['CATALOG_TYPE']))
		$arResult['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
	if (
		(CCatalogProduct::TYPE_PRODUCT == $arResult['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $arResult['CATALOG_TYPE'])
		&& !empty($arResult['OFFERS'])
	)
	{
		$arResult['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
	}
	switch ($arResult['CATALOG_TYPE'])
	{
		case CCatalogProduct::TYPE_SKU:
			break;
		case CCatalogProduct::TYPE_SET:
			$arResult['OFFERS'] = array();
			//no break
		case CCatalogProduct::TYPE_PRODUCT:
		default:
			$arResult['CHECK_QUANTITY'] = ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO']);
			$arResult['FOR_ORDER']      = ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'Y' == $arResult['CATALOG_CAN_BUY_ZERO'] && 0 >= $arResult['CATALOG_QUANTITY']);
			break;
	}
}
else
{
	$arResult['CATALOG_TYPE'] = 0;
	$arResult['OFFERS'] = array();

	if (CModule::IncludeModule('yenisite.bitronic2lite') && CModule::IncludeModule('yenisite.market')) {
		$prices = CMarketPrice::GetItemPriceValues($arResult["ID"], $arResult['PRICES']);
		if(count($prices)>0)
			unset ($arResult["PRICES"]);
		$minPrice = false;
		foreach ($prices as $k => $pr) {
			$pr = floatval($pr);
			$arResult["PRICES"][$k]["VALUE"] = $pr;
			$arResult["PRICES"][$k]["PRINT_VALUE"] = $pr;
			if ((empty($minPrice) || $minPrice > $pr) && $pr > 0) {
				$minPrice = $pr;
				$minPriceId = $k;
			}
		}
		if ($minPrice !== false) {
			$arResult['MIN_PRICE']['PRICE_ID'] = $minPriceId;
			$arResult['MIN_PRICE']['VALUE'] = $minPrice;
			$arResult['MIN_PRICE']['DISCOUNT_VALUE'] = $minPrice;
			$arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE'] = $minPrice;
			$arResult['CAN_BUY'] = true;
		}
		$arResult['CHECK_QUANTITY'] = (CMarketCatalog::UsesQuantity($arParams['IBLOCK_ID']) == 1);
		$arResult['CATALOG_QUANTITY'] = intval($arResult['PROPERTIES']['MARKET_QUANTITY']['VALUE']);

		if ($arResult['CHECK_QUANTITY'] && $arResult['CATALOG_QUANTITY'] <= 0) {
			$arResult['CAN_BUY'] = false;
		}
		$arResult['CATALOG_WEIGHT'] = $arResult['PROPERTIES']['CATALOG_WEIGHT']['VALUE'];
		$arResult['CATALOG_TYPE'] = 1; //simple product
	}
}
if(!empty($arParams['SIMILAR_PRICE_PROPERTIES']))
{
	$cp = $this->__component;
	if (is_object($cp))
	{
		$cp->arResult['PROPERTIES'] = $arResult['PROPERTIES'];
		$cp->SetResultCacheKeys(array('PROPERTIES'));
	}
}

if (CRZBitronic2CatalogUtils::isSale()) {
    CRZBitronic2CatalogUtils::checkPriceOfGiftedItem($arResult);
}