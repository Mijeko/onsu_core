<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
// AJAX PATH
$ajaxPath = SITE_DIR."ajax/catalog.php";
$ajaxPathCompare = SITE_DIR."ajax/compare.php";
$ajaxPathFavorite = SITE_DIR."ajax/favorites.php";
$arResult['ADD_URL_TEMPLATE'] = $ajaxPath."?".$arParams["ACTION_VARIABLE"]."=ADD2BASKET&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#&ajax_basket=Y";
$arResult['BUY_URL_TEMPLATE'] = $ajaxPath."?".$arParams["ACTION_VARIABLE"]."=BUY&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#&ajax_basket=Y";
$arResult['COMPARE_URL_TEMPLATE'] = $ajaxPathCompare."?".$arParams["ACTION_VARIABLE"]."=ADD_TO_COMPARE_LIST&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#&ajax_basket=Y";
$arResult['COMPARE_URL_TEMPLATE_DEL'] = $ajaxPathCompare."?".$arParams["ACTION_VARIABLE"]."=DELETE_FROM_COMPARE_LIST&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#&ajax_basket=Y";

$arResult['FAVORITE_URL_TEMPLATE'] = $ajaxPathFavorite."?ACTION=ADD&ID=#ID#";
$arResult['FAVORITE_URL_TEMPLATE_DEL'] = $ajaxPathFavorite."?ACTION=DELETE&ID=#ID#";

$arResult['CURRENCY'] = CModule::IncludeModule("currency");
$boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']) && $arResult['CURRENCY'];
if (!$boolConvert) {
	$strBaseCurrency = $arResult['CURRENCY'] ? CCurrency::GetBaseCurrency() : 'RUB';
}
$arResult['NO_ARTICUL'] = true;

if (CModule::IncludeModule('yenisite.market')) {
	$arResult['CHECK_QUANTITY'] = (CMarketCatalog::UsesQuantity($arParams['IBLOCK_ID']) == 1);
}

$arParams['USE_PRICE_COUNT'] = ($arParams['USE_PRICE_COUNT_'] === 'Y');

$arVipItems = array();
foreach ($arResult['ITEMS'] as $index => &$arItem) {
	$arItem = CRZBitronic2CatalogUtils::processItemCommon($arItem);
	$arItem['bFirst'] = $index == 0;

    $arItem['VIP'] = ('Y' === $arItem['PROPERTIES'][$arParams['VIP_ITEM_PROPERTY']]['VALUE']);

	if (!empty($arItem['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_ALT'])) {
		$imgAlt = $arItem['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_ALT'];
	} else {
		$imgAlt = $arItem['NAME'];
	}
	$arItem['PICTURE_PRINT']['ALT'] = $imgAlt;
	$arItem['PICTURE_PRINT']['SRC'] = CRZBitronic2CatalogUtils::getElementPictureById($arItem['ID'], $arParams['RESIZER_SECTION']);

	$arItem['CHECK_QUANTITY'] = false;
	if (!isset($arItem['CATALOG_MEASURE_RATIO'])) {
		$arItem['CATALOG_MEASURE_RATIO'] = 1;
	}
	if (!isset($arItem['CATALOG_QUANTITY'])) {
		$arItem['CATALOG_QUANTITY'] = 0;
	}
	$arItem['CATALOG_QUANTITY'] = (
		0 < $arItem['CATALOG_QUANTITY'] && is_float($arItem['CATALOG_MEASURE_RATIO'])
		? floatval($arItem['CATALOG_QUANTITY'])
		: intval($arItem['CATALOG_QUANTITY'])
	);
	$arItem['CATALOG'] = false;
	if (!isset($arItem['CATALOG_SUBSCRIPTION']) || 'Y' != $arItem['CATALOG_SUBSCRIPTION']) {
		$arItem['CATALOG_SUBSCRIPTION'] = 'N';
	}

	if ($arResult['MODULES']['catalog'])
	{
		$arItem['CATALOG'] = true;
		if (!isset($arItem['CATALOG_TYPE']))
			$arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
		if (
			(CCatalogProduct::TYPE_PRODUCT == $arItem['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $arItem['CATALOG_TYPE'])
			&& !empty($arItem['OFFERS'])
		)
		{
			$arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
		}
		switch ($arItem['CATALOG_TYPE'])
		{
			case CCatalogProduct::TYPE_SKU:
				break;
			case CCatalogProduct::TYPE_SET:
				$arItem['OFFERS'] = array();
				//no break;
			case CCatalogProduct::TYPE_PRODUCT:
			default:
				$arItem['CHECK_QUANTITY'] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'N' == $arItem['CATALOG_CAN_BUY_ZERO']);
				$arItem['FOR_ORDER']      = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'Y' == $arItem['CATALOG_CAN_BUY_ZERO'] && 0 >= $arItem['CATALOG_QUANTITY']);
				break;
		}
	}
	else
	{
		$arItem['CATALOG_TYPE'] = 0;
		$arItem['OFFERS'] = array();

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
	}

    if (isset($arItem['OFFERS']) && !empty($arItem['OFFERS'])) {
		CRZBitronic2CatalogUtils::fillMinPriceFromOffers(
			$arItem,
			$boolConvert ? $arResult['CONVERT_CURRENCY']['CURRENCY_ID'] : $strBaseCurrency
		);
		if ($arParams['FILTER_SET']) {
			$intSelected = -1;
			foreach ($arItem['OFFERS'] as $keyOffer => $arOffer) {
                if (!empty($arParams['DATA_FILTER'])) {
                    if (CRZBitronic2CatalogUtils::checkOfferOnSetFilterInCatalog($arOffer, $arParams['DATA_FILTER'])) {
                        unset ($arItem['OFFERS'][$keyOffer]);
                        continue;
                    }
                }
				if ($arItem['OFFER_ID_SELECTED'] > 0)
					$foundOffer = ($arItem['OFFER_ID_SELECTED'] == $arOffer['ID']);
				else
					$foundOffer = $arOffer['CAN_BUY'];
				if ($foundOffer) {
					$intSelected = $keyOffer;
					break;
				}
			}
			if (empty($arItem['OFFERS'])){
			    unset($arResult['ITEMS'][$index]);
			    continue;
            }
			unset($foundOffer);
			if (-1 == $intSelected) {
				$intSelected = 0;
			}
			$arItem['DETAIL_PAGE_URL'] = $arItem['OFFERS'][$intSelected]['DETAIL_PAGE_URL'];
			$arItem['PICTURE_PRINT']['SRC'] = CRZBitronic2CatalogUtils::getElementPictureById($arItem['OFFERS'][$intSelected]['ID'], $arParams['RESIZER_SECTION']);
		}
	} else {
		// PRICE MATRIX
		if ($arParams["USE_PRICE_COUNT"] && CRZBitronic2Settings::isPro() && $arResult['MODULES']['catalog'] && is_array($arItem['MIN_PRICE'])) {
			$arItem["PRICE_MATRIX"] = CRZBitronic2CatalogUtils::getPriceMatrix($arItem["ID"], $arItem['MIN_PRICE']['PRICE_ID'], $arResult['CONVERT_CURRENCY']);
			$arItem['PRICE_MATRIX'] = $arItem['PRICE_MATRIX'];
		}
	}

    $arItem['ON_REQUEST'] = (empty($arItem['MIN_PRICE']) || $arItem['MIN_PRICE']['VALUE'] <= 0);

    CRZBitronic2CatalogUtils::setNotAvailbaleStatusForEmptyOffer($arItem);

    if (empty($arItem['OFFERS']) && $arParams['SEARCH_PAGE'] != 'Y' && CRZBitronic2Settings::isPro() && CRZBitronic2CatalogUtils::checkAvPrFotoForElement($arItem, $arParams)) {
        unset ($arResult['ITEMS'][$index]);
        continue;
    }

	if ($arParams['ARTICUL_PROP'] && !empty($arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])) {
		$arResult['NO_ARTICUL'] = false;
	}

    if($arItem['VIP']){
        $arVipItems[] = $arItem;
        unset($arResult['ITEMS'][$index]);
    }
}
if (isset($arItem)) {
	unset($arItem);
}

if (!empty($arVipItems)){
    foreach ($arVipItems as $arVipItem) {
        array_unshift($arResult['ITEMS'],$arVipItem);
    }
}

$cp = $this->__component;
if (is_object($cp)) {
	if ($arResult['NAV_RESULT']->PAGEN >= $arResult['NAV_RESULT']->nEndPage) {
		$iPaginationSelect = $arResult['NAV_RESULT']->NavRecordCount;
	} else {
		$iPaginationSelect = $arResult['NAV_RESULT']->PAGEN * $arResult['NAV_RESULT']->SIZEN;
	}
	$iPaginationCount = $arResult['NAV_RESULT']->NavRecordCount;

	$cp->arResult['NAV_PAGINATION'] = array(
		'NUM' => $arResult['NAV_RESULT']->NavNum,
		'PAGEN' => $arResult['NAV_RESULT']->PAGEN,
		'END_PAGE' => $arResult['NAV_RESULT']->nEndPage,
		'SELECT' => $iPaginationSelect,
		'COUNT' => $arResult['NAV_RESULT']->NavRecordCount,
	);
	$cp->SetResultCacheKeys(array('NAV_PAGINATION'));
}