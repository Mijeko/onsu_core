<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arCommonParams = array(
	'REQUEST_URI' => $_SERVER["REQUEST_URI"],
	'SCRIPT_NAME' => $_SERVER["SCRIPT_NAME"],
	
	'USE_CATALOG' => $arResult['CATALOG'],
	'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
	'HIDE_DISABLED_PROPS_OF_OFFERS' => 'Y' == $arParams['HIDE_DISABLED_PROPS_OF_OFFERS'],
	'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
	'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
	'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE_SOLUTION'],
	'DISPLAY_FAVORITE' => $arParams['DISPLAY_FAVORITE'],
	'HAS_SERVICES' => (is_array($arResult['PROPERTIES']['SERVICE']) && !empty($arResult['PROPERTIES']['SERVICE']['VALUE'])),
	'SERVICE_IBLOCK_ID' => $arResult['PROPERTIES']['SERVICE']['LINK_IBLOCK_ID'],
		
	'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
	'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
	'DETAIL' => true,
	'QUICK_VIEW' => $arParams['QUICK_VIEW'],
	'ARTICUL' => is_array($arResult['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) ? implode(' / ', $arResult['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) : $arResult['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'],
	'VISUAL' => array(
		'ID' => $arItemIDs['ID'],
		'PRICE_ID' => $arItemIDs['PRICE'],
		'PRICE_OLD_ID' => $arItemIDs['OLD_PRICE'],
		'ARTICUL_ID' => $arItemIDs['ARTICUL'],
		'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
		'BUY_ID' => $arItemIDs['BUY_LINK'],
		'BUY_ONECLICK' => $arItemIDs['BUY_ONECLICK'],
		'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK'],
		'FAVORITE_ID' => $arItemIDs['FAVORITE_LINK'],
		'REQUEST_ID' => $arItemIDs['REQUEST_LINK'],
		'SUBSCRIBE_ID' => $arItemIDs['SUBSCRIBE_BTN'],
		'AVAILABLE_INFO' => $arItemIDs['AVAILABLE_INFO'],
		'AVAIBILITY_EXPANDED' => $arItemIDs['AVAIBILITY_EXPANDED'],
		'QUANTITY_ID' => $arItemIDs['QUANTITY'],
		'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
		'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
		'PICT_ID' => $arItemIDs['PICT'],
		'SLIDER_CONT_OF_ID' => $arItemIDs['SLIDER_CONT_OF_ID'],
		'SLIDER_MODAL_CONT_OF_ID' => $arItemIDs['SLIDER_MODAL_CONT_OF_ID'],
		'SLIDER_CONT_OF_INNER_ID' => $arItemIDs['SLIDER_CONT_OF_INNER_ID'],
		'SLIDER_CONT_OF_MODAL_INNER_ID' => $arItemIDs['SLIDER_CONT_OF_MODAL_INNER_ID'],
		'PICT_MODAL' => $arItemIDs['PICT_MODAL'],
		'PICT_FLY' => $arItemIDs['PICT_FLY'],
		'DETAIL_LINK_CLASS' => $arItemCLASSes['LINK'],
	),
	'DEFAULT_PICTURE' => array(
		'PICTURE' => array(
			'SRC' => CResizer2Resize::ResizeGD2($arResult['MORE_PHOTO'][0]['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_SMALL']),
			'SRC_BIG' => CResizer2Resize::ResizeGD2($arResult['MORE_PHOTO'][0]['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_BIG']),
			'SRC_ICON' => CResizer2Resize::ResizeGD2($arResult['MORE_PHOTO'][0]['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_ICON']),
		)
	),
	
	'PRODUCT' => array(
		'ID' => $arResult['ID'],
		'IBLOCK_ID' => $arResult['IBLOCK_ID'],
		'NAME' => $arResult['~NAME'],
		'FAVORITE_URL' => $arResult['FAVORITE_URL'],
	),
	
	'BASKET' => array(
		'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
		'BASKET_URL' => $arParams['BASKET_URL'],
		'ADD_URL_TEMPLATE' => $arResult['ADD_URL_TEMPLATE'],
		'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
	),
);

if(!$arResult['bOffers'])
{
	$arJSParams = array(
		'SHOW_PRICE' => (isset($arResult['MIN_PRICE']) && !empty($arResult['MIN_PRICE']) && is_array($arResult['MIN_PRICE'])),
		'VISUAL' => array(
			'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV'],
		),
		'PRODUCT' => array(
			'PICT' => array('SRC' => $arResult['MORE_PHOTO'][0]['SRC']),
			'SUBSCRIPTION' => true,
			'BASIS_PRICE' => $arResult['MIN_PRICE'],
			'PRICE_MATRIX' => $arResult['PRICE_MATRIX'],
			'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
			'SLIDER' => $arResult['MORE_PHOTO'],
			'CAN_BUY' => $arResult['CAN_BUY'],
			'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
			'QUANTITY_FLOAT' => is_double($arResult['CATALOG_MEASURE_RATIO']),
			'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
			'STEP_QUANTITY' => $arResult['CATALOG_MEASURE_RATIO'],
			'ADD_URL' => $arResult['ADD_URL'],
			'COMPARE_URL' => $arResult['COMPARE_URL'],
		),
		'BASKET' => array(
			'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
			'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
			'EMPTY_PROPS' => $bEmptyProductProperties,
		)
	);
}
else
{
	if ('Y' == $arParams['PRODUCT_DISPLAY_MODE_CUSTOM'] && !empty($arResult['OFFERS_PROP']))
	{
		foreach ($arResult['JS_OFFERS'] as &$arOneJS)
		{
			if ($arOneJS['PRICE']['DISCOUNT_VALUE'] != $arOneJS['PRICE']['VALUE'])
			{
				$arOneJS['PRICE']['PRINT_DISCOUNT_DIFF'] = GetMessage('ECONOMY_INFO', array('#ECONOMY#' => $arOneJS['PRICE']['PRINT_DISCOUNT_DIFF']));
				$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'];
				$arOneJS['PRICE']['HTML_VALUE'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arOneJS['PRICE']['CURRENCY'], $arOneJS['PRICE']['VALUE'], $arOneJS['PRICE']['PRINT_VALUE']);
			}
			$arOneJS['PRICE']['HTML_DISCOUNT_VALUE'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arOneJS['PRICE']['CURRENCY'], $arOneJS['PRICE']['DISCOUNT_VALUE'], $arOneJS['PRICE']['PRINT_DISCOUNT_VALUE']);

			$arItemPrices = $arOneJS['PRICES'];
			if (CRZBitronic2Settings::isPro()) {
				$arItemMatrix = $arOneJS['PRICE_MATRIX'];
				$measureName = $arOneJS['MEASURE'];
				$measureRatio = $arOneJS['STEP_QUANTITY'];
				$minPriceId = $arOneJS['PRICE']['PRICE_ID'];
				ob_start();
				include 'additional_prices.php';
				$arOneJS['ADDITIONAL_PRICES'] = ob_get_clean();
				unset($arItemMatrix, $arItemPrices, $minPriceId, $measureName, $measureRatio);
			}
			$arOneJS['ADDITIONAL_PRICES'] = trim($arOneJS['ADDITIONAL_PRICES']);
			unset($arItemPrices, $arOneJS['PRICES']);

			$strProps = '';
			if ($arResult['SHOW_OFFERS_PROPS'])
			{
				if (!empty($arOneJS['DISPLAY_PROPERTIES']))
				{
					foreach ($arOneJS['DISPLAY_PROPERTIES'] as $arOneProp)
					{
						$strProps .= '<dt>'.$arOneProp['NAME'].'</dt><dd>'.(
							is_array($arOneProp['VALUE'])
							? implode(' / ', $arOneProp['VALUE'])
							: $arOneProp['VALUE']
						).'</dd>';
					}
				}
			}
			$arOneJS['DISPLAY_PROPERTIES'] = $strProps;
		}
		if (isset($arOneJS))
		unset($arOneJS);
		
		$arJSParams = array(
			'SHOW_PRICE' => true,
			'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
			'OFFER_GROUP' => $arResult['OFFER_GROUP'],
			'VISUAL' => array(
				'TREE_ID' => $arItemIDs['PROP_DIV'],
				'TREE_ITEM_ID' => $arItemIDs['PROP'],
				'OFFER_GROUP' => $arItemIDs['OFFER_GROUP'],
				'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
			),
			'PRODUCT' => array(
				'IBLOCK_ID_SKU' => $arResult['OFFERS_IBLOCK'],
			),
			'BASKET' => array(
				'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
				'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
			),
			'OFFERS' => $arResult['JS_OFFERS'],
			'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
			'OFFER_VAR_NAME' => $arParams['OFFER_VAR_NAME'],
			'TREE_PROPS' => $arSkuProps
		);
	}
	else
	{
		$arJSParams = array(
			'SHOW_PRICE' => true,
			'SHOW_QUANTITY' => false,
			'SHOW_ABSENT' => false,
			'SHOW_SKU_PROPS' => false,
			
			'OFFERS' => array(),
			'OFFER_SELECTED' => 0,
			'OFFER_SIMPLE' => true,
			'TREE_PROPS' => array(),
			'PRODUCT' => array(
				'IBLOCK_ID_SKU' => $arResult['OFFERS_IBLOCK'],
			),
			'VISUAL' => array(
				'SKU_TABLE' => $arItemIDs['SKU_TABLE'],
			),
		);
	}
}

$arJSParams = array_replace_recursive($arCommonParams, $arJSParams);

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
$templateData['arJSParams'] = $arJSParams;
?>
<script type="text/javascript">
BX.message({
	MESS_BTN_BUY: '<? echo ('' != $arParams['MESS_BTN_BUY'] ? CUtil::JSEscape($arParams['MESS_BTN_BUY']) : GetMessageJS('CT_BCE_CATALOG_BUY')); ?>',
	MESS_BTN_ADD_TO_BASKET: '<? echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? CUtil::JSEscape($arParams['MESS_BTN_ADD_TO_BASKET']) : GetMessageJS('CT_BCE_CATALOG_ADD')); ?>',
	MESS_NOT_AVAILABLE: '<? echo ('' != $arParams['MESS_NOT_AVAILABLE'] ? CUtil::JSEscape($arParams['MESS_NOT_AVAILABLE']) : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE')); ?>',
	MESS_FOR: '<? echo GetMessageJS('BITRONIC2_FOR')?>',
	TITLE_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR') ?>',
	TITLE_BASKET_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS') ?>',
	BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
	BTN_SEND_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS'); ?>',
	BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE') ?>',
	SITE_ID: '<? echo SITE_ID; ?>'
});
</script>
