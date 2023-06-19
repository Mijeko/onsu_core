<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (empty($arParams['BRAND_DETAIL'])) {
	$arParams['BRAND_DETAIL'] = SITE_DIR . 'brands/#ID#/';
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

if (!empty($arParams['FILTER_NAME'])) {
	global ${$arParams["FILTER_NAME"]};
	$arrFilter = ${$arParams["FILTER_NAME"]};

	if (is_array($arrFilter))
	foreach($arrFilter as $k=>$v)
	{
		if(strpos($k, 'NAME') !== false)
		{
			$arFilter['filter'][str_replace('NAME', 'UF_NAME', $k)] = $v;
		}
	}
}
$fieldsList = $entityDataClass::getMap();
if (count($fieldsList) === 1 && isset($fieldsList['ID']))
	$fieldsList = $entityDataClass::getEntity()->getFields();

$directoryOrder = array();
if (isset($fieldsList['UF_SORT']))
	$directoryOrder['UF_SORT'] = 'ASC';
$directoryOrder['ID'] = 'ASC';
$arFilter['order'] = $directoryOrder;		
		
$rsPropEnums = $entityDataClass::getList($arFilter);

$arResult['ITEMS'] = array();

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

	$smartFilter->fillItemValues($arResult['FILTER_PROP'], $arEnum['UF_XML_ID']);

	if ($arParams['BRANDS_EXT'] == 'N') {
		$arItem['LINK'] = (isset($arEnum['UF_LINK']) && '' != $arEnum['UF_LINK'])
		                ? $arParams["SEF_BASE_URL"].$arEnum['UF_LINK']
		                : (empty($arParams['CATALOG_FILTER_NAME'])
		                  ? ''
		                  : $arParams["PATH_FOLDER"]
		                    .'?'.$arParams['CATALOG_FILTER_NAME']
		                    .$arResult['FILTER_PROP']['VALUES'][$arEnum['UF_XML_ID']]['CONTROL_ID']
		                    .'=Y&amp;set_filter=y&amp;rz_all_elements=y'
		                  );
	} else {
		$arItem['LINK'] = str_replace(
			array('#ID#', '#XML_ID#'),
			array($arItem['ID'], $arItem['UF_XML_ID']),
			$arParams['BRAND_DETAIL']
		);
	}
	
	$arResult['ITEMS'][$key] = $arItem;
}

unset($arResult['BRAND_BLOCKS']);
