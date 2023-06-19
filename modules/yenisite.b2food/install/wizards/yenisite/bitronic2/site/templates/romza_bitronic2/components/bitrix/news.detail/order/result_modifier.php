<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

// @var $moduleId
include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/module_code.php';

CModule::IncludeModule($moduleId);

if (is_array($arResult['FIELDS'])) {
	if (isset($arResult['FIELDS']['CREATED_BY'])) {
		unset($arResult['FIELDS']['CREATED_BY']);
		
		$cp = $this->__component;

		if (is_object($cp))
		{
			$cp->SetResultCacheKeys(array('CREATED_BY'));
		}
	}
	
	if (isset($arResult['FIELDS']['DATE_CREATE'])) {
		unset($arResult['FIELDS']['DATE_CREATE']);
		
		$arResult['DATE_CREATE_FORMATTED'] = date('d.m.Y', MakeTimeStamp($arResult['DATE_CREATE']));
	}
	
	if (count($arResult['FIELDS']) <= 0) {
		unset($arResult['FIELDS']);
	}
}

/*    PAYMENT_PROPERTIES    */
$arResult['PAYMENT_PROPERTIES'] = array();

if (array_key_exists('PAYMENT_E', $arResult['DISPLAY_PROPERTIES'])) {
	$arResult['PAYMENT_PROPERTIES']['PAYMENT_E'] = $arResult['DISPLAY_PROPERTIES']['PAYMENT_E'];
	unset($arResult['DISPLAY_PROPERTIES']['PAYMENT_E']);
}
if (array_key_exists('DELIVERY_E', $arResult['DISPLAY_PROPERTIES'])) {
	$arResult['PAYMENT_PROPERTIES']['DELIVERY_E'] = $arResult['DISPLAY_PROPERTIES']['DELIVERY_E'];
	unset($arResult['DISPLAY_PROPERTIES']['DELIVERY_E']);
}

/*    Set last display property     */
end($arResult['DISPLAY_PROPERTIES']);
$lastKey = key($arResult['DISPLAY_PROPERTIES']);
if ($lastKey != NULL) {
	$arResult['DISPLAY_PROPERTIES'][$lastKey]['LAST'] = true;
}


/*    Set basket items      */
$arFilter = array('ID' => $arResult['PROPERTIES']['ITEMS']['VALUE']);
$arSelect = array('ID', 'NAME', 'CODE', 'DETAIL_PAGE_URL');

$arResult['ITEMS'] = array();
$rsElement = CIBlockElement::GetList(array('sort' => 'asc'), $arFilter, false, false, $arSelect);
while ($obElement = $rsElement->GetNextElement()) {
	$arRes = $obElement->GetFields();
	$arRes['PICTURE'] = CRZBitronic2CatalogUtils::getElementPictureById($arRes['ID'], $arParams['RESIZER_BASKET_PHOTO']);
	$arResult['ITEMS'][$arRes['ID']] = $arRes;
}

$regExp = '/<p class="basket_item" data-id="item_([^"]+)">.*<span class="item_name">([^<]*)<\\/span>.*<span class="item_count">([^<]*)<\\/span>.*<span class="item_price">([^<]*)</U';
$out = array();
preg_match_all($regExp, $arResult['DETAIL_TEXT'], $out, PREG_SET_ORDER);

$arResult['BASKET_ITEMS'] = array();
foreach ($out as $match) {
	$arItem = array();
	$arItem['ID'] = intval($match[1]);
	$arItem['NAME_FULL'] = htmlspecialcharsBx($match[2]);
	$arItem['COUNT'] = intval($match[3]);
	$arItem['PRICE'] = floatval($match[4]);
	if (array_key_exists($arItem['ID'], $arResult['ITEMS'])) {
		$arElement = $arResult['ITEMS'][$arItem['ID']];
		$arItem['PICTURE'] = $arElement['PICTURE'];
		$arItem['DETAIL_PAGE_URL'] = $arElement['DETAIL_PAGE_URL'];
		$arItem['NAME'] = $arElement['NAME'];
	}
	$arResult['BASKET_ITEMS'][] = $arItem;
}

/*    Find and set delivery price    */
$regExp = '#.*<span class="delivery_price">([^<]*)</span>.*#Um';
$out = null;
preg_match($regExp, $arResult['DETAIL_TEXT'], $out);
if (is_array($out) && array_key_exists(1, $out) && is_array($arResult['PAYMENT_PROPERTIES']['DELIVERY_E']['LINK_ELEMENT_VALUE'])) {
	foreach ($arResult['PAYMENT_PROPERTIES']['DELIVERY_E']['LINK_ELEMENT_VALUE'] as &$link) {
		$link['NAME'] .= ' - ' . $out[1];
	}unset($link);
} 