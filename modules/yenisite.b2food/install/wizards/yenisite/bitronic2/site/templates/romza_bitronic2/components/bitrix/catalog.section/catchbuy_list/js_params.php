<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arJSParams = array(
	'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
	'SHOW_QUANTITY' => ($arParams['USE_PRODUCT_QUANTITY'] == 'Y'),
	'SHOW_ADD_BASKET_BTN' => false,
	'SHOW_BUY_BTN' => false,
	'SHOW_ABSENT' => false,
	'SHOW_SKU_PROPS' => false,
	'SECOND_PICT' => $arItem['SECOND_PICT'],
	'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
	'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
	'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
	'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
	'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE_SOLUTION'],
	'DISPLAY_FAVORITE' => $arParams['DISPLAY_FAVORITE'],
	'REQUEST_URI' => $_SERVER["REQUEST_URI"],
	'SCRIPT_NAME' => $_SERVER["SCRIPT_NAME"],
	'DEFAULT_PICTURE' => array(
		'PICTURE' => $arItem['PRODUCT_PREVIEW'],
		'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
	),
	'VISUAL' => array(
		'ID' => $arItemIDs['ID'],
		'PICT_ID' => $arItemIDs['PICT'],
		'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
		'QUANTITY_ID' => $arItemIDs['QUANTITY'],
		'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
		'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
		'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
		'PRICE_ID' => $arItemIDs['PRICE'],
		'TREE_ID' => $arItemIDs['PROP_DIV'],
		'TREE_ITEM_ID' => $arItemIDs['PROP'],
		'BUY_ID' => $arItemIDs['BUY_LINK'],
		'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
		'DSC_PERC' => $arItemIDs['DSC_PERC'],
		'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
		'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
		'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
		'NOT_AVAILABLE_MESS' => $arItemIDs['NOT_AVAILABLE_MESS'],
		'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK'],
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
		'NAME' => $productTitle,
		'PICT' => ('Y' == $arItem['SECOND_PICT'] ? $arItem['PREVIEW_PICTURE_SECOND'] : $arItem['PREVIEW_PICTURE']),
		'CAN_BUY' => $arItem["CAN_BUY"],
		'SUBSCRIPTION' => ('Y' == $arItem['CATALOG_SUBSCRIPTION']),
		'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
		'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
		'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
		'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
		'SUBSCRIBE_URL' => $arItem['~SUBSCRIBE_URL'],
		'BASIS_PRICE' => $arItem['MIN_BASIS_PRICE']
	),
	'OFFERS' => array(),
	'OFFER_SELECTED' => 0,
	'TREE_PROPS' => array(),
	'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
);
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
