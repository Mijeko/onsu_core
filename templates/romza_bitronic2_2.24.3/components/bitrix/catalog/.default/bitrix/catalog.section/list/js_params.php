<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arCommonParams = array(
	'REQUEST_URI' => $arItem['DETAIL_PAGE_URL'],//$_SERVER["REQUEST_URI"], need for AJAX to work on search page
	'SCRIPT_NAME' => $_SERVER["SCRIPT_NAME"],
	
	'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
	'SHOW_ADD_BASKET_BTN' => false,
	'SECOND_PICT' => $arItem['SECOND_PICT'],
	'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
	'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
	'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
	'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
	'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE_SOLUTION'],
	'DISPLAY_FAVORITE' => $arParams['DISPLAY_FAVORITE'],
	'DEFAULT_PICTURE' => array(
		'PICTURE' => $arItem['PICTURE_PRINT'],
		'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
	),
	'ARTICUL' => is_array($arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) ? implode(' / ', $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) : $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'],
	'VISUAL' => array(
		'ID' => $arItemIDs['ID'],
		'PICT_ID' => $arItemIDs['PICT'],
		'SLIDER_CONT_OF_ID' => $arItemIDs['SLIDER_CONT_OF_ID'],
		'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
		'QUANTITY_ID' => $arItemIDs['QUANTITY'],
		'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
		'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
		'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
		'PRICE_ID' => $arItemIDs['PRICE'],
		'PRICE_OLD_ID' => $arItemIDs['PRICE_OLD'],
		'TREE_ID' => $arItemIDs['PROP_DIV'],
		'TREE_ITEM_ID' => $arItemIDs['PROP'],
		'BUY_ID' => $arItemIDs['BUY_LINK'],
		'BUY_ONECLICK' => $arItemIDs['BUY_ONECLICK'],
		'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
		'DSC_PERC' => $arItemIDs['DSC_PERC'],
		'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
		'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
		'ARTICUL_ID' => $arItemIDs['ARTICUL'],
		'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
		'AVAILABLE_INFO' => $arItemIDs['AVAILABLE_INFO'],
		'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK'],
		'DETAIL_LINK_CLASS' => $arItemCLASSes['LINK'],
		'FAVORITE_ID' => $arItemIDs['FAVORITE_LINK']
	),
	'BASKET' => array(
		'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
		'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
		'SKU_PROPS' => $arItem['OFFERS_PROP_CODES'],
		'BASKET_URL' => $arParams['BASKET_URL'],
		'ADD_URL_TEMPLATE' => $arResult['ADD_URL_TEMPLATE'],
		'BUY_URL_TEMPLATE' => $arResult['BUY_URL_TEMPLATE']
	),
	'PRODUCT' => array(
		'ID' => $arItem['ID'],
		'IBLOCK_ID' => $arItem['IBLOCK_ID'],
		'NAME' => $productTitle
	),
	
	'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
);

if(!$arItem['bOffers'])
{
	$arJSParams = array(
		'VISUAL' => array(
			'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV'],
		),
		'SHOW_QUANTITY' => ($arParams['USE_PRODUCT_QUANTITY'] == 'Y'),
		'SHOW_BUY_BTN' => false,
		'SHOW_ABSENT' => false,
		'SHOW_SKU_PROPS' => false,
			
		'PRODUCT' => array(
			'PICT' => ('Y' == $arItem['SECOND_PICT'] ? $arItem['PREVIEW_PICTURE_SECOND'] : $arItem['PICTURE_PRINT']),
			'CAN_BUY' => $arItem["CAN_BUY"],
			'SUBSCRIPTION' => ('Y' == $arItem['CATALOG_SUBSCRIPTION']),
			'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
			'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
			'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
			'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
			'SUBSCRIBE_URL' => $arItem['~SUBSCRIBE_URL'],
			'BASIS_PRICE' => $arItem['MIN_BASIS_PRICE'],
			'PRICE_MATRIX' => $arItem['PRICE_MATRIX'],
		),
		'BASKET' => array(
			'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
			'EMPTY_PROPS' => $bEmptyProductProperties,
		),
		'OFFERS' => array(),
		'OFFER_SELECTED' => 0,
		'TREE_PROPS' => array(),
	);
}
else
{
	if ('Y' == $arParams['PRODUCT_DISPLAY_MODE_CUSTOM'] && !empty($arItem['OFFERS_PROP']))
	{
		$arSkuProps = array();
		foreach ($arResult['SKU_PROPS'] as $arOneProp)
		{
			if (!isset($arItem['OFFERS_PROP'][$arOneProp['CODE']]))
				continue;
			$arSkuProps[] = array(
				'ID' => $arOneProp['ID'],
				'SHOW_MODE' => $arOneProp['SHOW_MODE'],
				'VALUES_COUNT' => $arOneProp['VALUES_COUNT']
			);
		}
		foreach ($arItem['JS_OFFERS'] as &$arOneJs)
		{
			if (0 < $arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'])
			{
				$arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'] = '-'.$arOneJs['PRICE']['DISCOUNT_DIFF_PERCENT'];
				$arOneJs['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'] = '-'.$arOneJs['BASIS_PRICE']['DISCOUNT_DIFF_PERCENT'];
			}
			if (0 < $arOneJs['PRICE']['DISCOUNT_DIFF']) {
				$arOneJs['PRICE']['HTML_VALUE'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arOneJs['PRICE']['CURRENCY'], $arOneJs['PRICE']['VALUE'], $arOneJs['PRICE']['PRINT_VALUE']);
			}
			$arOneJs['PRICE']['HTML_DISCOUNT_VALUE'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arOneJs['PRICE']['CURRENCY'], $arOneJs['PRICE']['DISCOUNT_VALUE'], $arOneJs['PRICE']['PRINT_DISCOUNT_VALUE']);
			$arItemPrices = $arOneJs['PRICES'];
			if (!empty($arItemPrices) && CRZBitronic2Settings::isPro()) {
				$minPriceId = $arOneJs['PRICE']['PRICE_ID'];
				ob_start();
				include 'additional_prices.php';
				$arOneJs['ADDITIONAL_PRICES'] = ob_get_clean();
			}
			$arOneJs['ADDITIONAL_PRICES'] = trim($arOneJs['ADDITIONAL_PRICES']);
			unset($arItemPrices, $arOneJs['PRICES']);
		}
		unset($arOneJs);
		
		$arJSParams = array(
			'SHOW_QUANTITY' => ($arParams['USE_PRODUCT_QUANTITY'] == 'Y'),
			'SHOW_BUY_BTN' => true,
			'SHOW_ABSENT' => true,
			'SHOW_SKU_PROPS' => $arItem['OFFERS_PROPS_DISPLAY'],
			
			'OFFERS' => $arItem['JS_OFFERS'],
			'OFFER_SELECTED' => $arItem['OFFERS_SELECTED'],
			'TREE_PROPS' => $arSkuProps,
			
			'PRODUCT' => array(
				'IBLOCK_ID_SKU' => $arResult['OFFERS_IBLOCK'],
			),
		);
	}
	else
	{
		$arJSParams = array(
			'SHOW_QUANTITY' => false,
			'SHOW_BUY_BTN' => false,
			'SHOW_ABSENT' => false,
			'SHOW_SKU_PROPS' => false,
			
			'OFFERS' => array(),
			'OFFER_SELECTED' => 0,
			'TREE_PROPS' => array(),
		);
	}
}

$arJSParams = array_merge_recursive($arCommonParams, $arJSParams);

if ($arParams['DISPLAY_COMPARE_SOLUTION'])
{
	$arJSParams['COMPARE'] = array(
		'COMPARE_URL_TEMPLATE' => $arResult['COMPARE_URL_TEMPLATE'],
		'COMPARE_URL_TEMPLATE_DEL' => $arResult['COMPARE_URL_TEMPLATE_DEL'],
		'COMPARE_PATH' => $arParams['COMPARE_PATH']
	);
}
if ($arParams['DISPLAY_FAVORITE'])
{
	$arJSParams['FAVORITE'] = array(
		'FAVORITE_URL_TEMPLATE' => $arResult['FAVORITE_URL_TEMPLATE'],
		'FAVORITE_URL_TEMPLATE_DEL' => $arResult['FAVORITE_URL_TEMPLATE_DEL'],
		'FAVORITE_PATH' => $arParams['FAVORITE_PATH']
	);
}

$jsString .= 'var ' . $strObName . ' = new JCCatalogItem('. CUtil::PhpToJSObject($arJSParams, false, true) .');';