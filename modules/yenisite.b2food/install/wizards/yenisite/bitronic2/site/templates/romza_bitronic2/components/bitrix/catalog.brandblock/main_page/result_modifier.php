<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult['show_cloud'] = $arParams['BRANDS_CLOUD'] == 'Y';

if (empty($arParams['BRAND_DETAIL'])) {
	$arParams['BRAND_DETAIL'] = SITE_DIR . 'brands/#ID#/';
}
if (empty($arParams['BRAND_LIST'])) {
	$arParams['BRAND_LIST'] = SITE_DIR . 'brands/';
}

$arProp = CIBlockProperty::GetList(
	array('SORT' => 'ASC', 'ID' => 'ASC'),
	array(
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'CODE' => $arParams['PROP_CODE'][0],
		'ACTIVE' => 'Y'
	)
)->Fetch();
CBitrixComponent::includeComponentClass("bitrix:catalog.smart.filter");
$arResult['FILTER_PROP'] = $arProp;


$hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(
	array(
		"filter" => array(
			'TABLE_NAME' => $arProp['USER_TYPE_SETTINGS']['TABLE_NAME']
		)
	)
)->fetch();



$entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
$entityDataClass = $entity->getDataClass();

$arFilter = array();
/*
global ${$arParams["FILTER_NAME"]};
$arrFilter = ${$arParams["FILTER_NAME"]};

foreach($arrFilter as $k=>$v)
{
	if(strpos($k, 'NAME') !== false)
	{
		$arFilter['filter'][str_replace('NAME', 'UF_NAME', $k)] = $v;
	}
}
*/
$rsPropEnums = $entityDataClass::getList($arFilter);

$arResult['ITEMS'] = array();
$arItems = array();

$smartFilter = new CBitrixCatalogSmartFilter();

while ($arEnum = $rsPropEnums->fetch())
{
	if (isset($arResult['BRAND_BLOCKS'][$arEnum['ID']])) {
		$arItem = $arResult['BRAND_BLOCKS'][$arEnum['ID']];
		$key = $arEnum['ID'];
	}
	else {
		$key = $hlblock['NAME'].'_'.$arEnum['ID'];
		if (isset($arResult['BRAND_BLOCKS'][$key])) {
			$arItem = $arResult['BRAND_BLOCKS'][$key];
		} else {
			continue;
		}
	}
	$arItem['ID'] = $arEnum['ID'];
	
	$arItem['UF_XML_ID'] = $arEnum['UF_XML_ID'];

	if ($arParams['BRANDS_EXT'] == 'N') {
	
		$smartFilter->fillItemValues($arResult['FILTER_PROP'], $arEnum['UF_XML_ID']);
		
		$arItem['LINK'] = (isset($arEnum['UF_LINK']) && '' != $arEnum['UF_LINK'])
		                ? $arParams["SEF_BASE_URL"].$arEnum['UF_LINK']
		                : $arParams["PATH_FOLDER"]
		                  . '?' . $arParams['CATALOG_FILTER_NAME']
		                  . $arResult['FILTER_PROP']['VALUES'][$arEnum['UF_XML_ID']]['CONTROL_ID']
		                  . '=Y&amp;set_filter=y&amp;rz_all_elements=y';
	} else {
		$arItem['LINK'] = str_replace(
			array('#ID#', '#XML_ID#'),
			array($arItem['ID'], $arItem['UF_XML_ID']),
			$arParams['BRAND_DETAIL']
		);
	}

	$arItems[$key] = $arItem;
}
foreach ($arResult['BRAND_BLOCKS'] as $itemId => $bb) {
	if (!array_key_exists($itemId, $arItems)) continue;
	$arResult['ITEMS'][$itemId] = $arItems[$itemId];
}

unset($arResult['BRAND_BLOCKS']);
unset($arItems);

if ($arResult['show_cloud']) {
	/** @noinspection PhpDynamicAsStaticMethodCallInspection */
	$rs = \CIBlockElement::GetList(array(),
		array(
			'IBLOCK_ID' => $arResult['FILTER_PROP']['IBLOCK_ID'],
			'!PROPERTY_' . $arResult['FILTER_PROP']['CODE'] => false,
		),
		array (
			'PROPERTY_' . $arResult['FILTER_PROP']['CODE']
		)
	);
	$arBrandsCNT = array();
	$arResult['BRANDS_CNT_ALL'] = 0;
	$arResult['BRANDS_CNT_MIN'] = INF;
	$arResult['BRANDS_CNT_MAX'] = 0;
	while ($ar = $rs->Fetch()) {
		$arBrandsCNT[$ar['PROPERTY_' . $arResult['FILTER_PROP']['CODE'] . '_VALUE']] = $ar['CNT'];
		$arResult['BRANDS_CNT_ALL'] += $ar['CNT'];
		if ($ar['CNT'] < $arResult['BRANDS_CNT_MIN']) {
			$arResult['BRANDS_CNT_MIN'] = $ar['CNT'];
		}
		if ($ar['CNT'] > $arResult['BRANDS_CNT_MAX']) {
			$arResult['BRANDS_CNT_MAX'] = $ar['CNT'];
		}
	}
	$arResult['BRANDS_CNT'] = $arBrandsCNT;

	unset($arBrandsCNT);
}
