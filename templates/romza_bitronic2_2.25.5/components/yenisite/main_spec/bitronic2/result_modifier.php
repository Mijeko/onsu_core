<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitronic2\Mobile;
use Bitrix\Main\Loader;
CJSCore::Init(array('rz_b2_bx_catalog_item'));

//echo '<pre>', var_export($arResult, 1), '</pre>';

$obCache = new CPHPCache;
$cacheId = serialize(array_keys($arResult['TABS'])) . $arParams['IBLOCK_ID'];
foreach ($arResult['TABS'] as $codeTab => &$arTab) {
	if (isset($arParams['TAB_PROPERTY_'.$codeTab])) {
		$cacheId .= $arParams['TAB_PROPERTY_'.$codeTab];
	}
}
$cachePath = '/bitronic2/mainSpecFilter/';
if ($obCache->InitCache($arParams['CACHE_TIME'], $cacheId, $cachePath)) {
	$arResult['TABS'] = $obCache->GetVars();
} else {
	CBitrixComponent::includeComponentClass("bitrix:catalog.smart.filter");

	$arContainer = array(
		'NEW' 			=> array('CLASS' => 'new', 'ICON' => 'flaticon-new2'),
		'HIT' 			=> array('CLASS' => 'hits', 'ICON' => 'flaticon-first43'),
		'SALE' 			=> array('CLASS' => 'recommended', 'ICON' => 'flaticon-sale'),
		'BESTSELLER' 	=> array('CLASS' => 'superprice', 'ICON' => 'flaticon-like'),
	);
	$arProps = CIBlockSectionPropertyLink::GetArray($arParams['IBLOCK_ID'], 0);

	$smartFilter = new CBitrixCatalogSmartFilter();
	
	foreach($arResult['TABS'] as $codeTab => &$arTab)
	{
		$arTab['CONTAINER_HEADER_CLASS'] = $arContainer[$codeTab]['CLASS'];
		$arTab['CONTAINER_HEADER_ICON'] = $arContainer[$codeTab]['ICON'];
		$arTab['HEADER'] = strlen($arParams['TAB_TEXT_'.$codeTab]) > 0 
		                 ? $arParams['TAB_TEXT_'.$codeTab]
		                 : GetMessage('BITRONIC2_MAINSPEC_TAB_'.$codeTab);
		$propCode = $arParams['TAB_PROPERTY_'.$codeTab] ?: $codeTab;
		$arProp = CIBlockProperty::GetList(
			array('SORT' => 'ASC', 'ID' => 'ASC'),
			array(
				'IBLOCK_ID' => $arParams['IBLOCK_ID'],
				'CODE' => $codeTab,
				'ACTIVE' => 'Y'
			)
		)->Fetch();
		if ($arProp && isset($arProps[$arProp['ID']]) && $arProps[$arProp['ID']]['SMART_FILTER'] == 'Y') {
			$arPropValue = CIBlockProperty::GetPropertyEnum($arProp['ID'], array('sort' => 'asc'), array('IBLOCK_ID' => $arParams['IBLOCK_ID']))->Fetch();
			$value = $arPropValue['ID']?:false;

			$smartFilter->fillItemValues($arProp, $value);
			
			$arTab['LINK'] = '/catalog/?arrFilter' . $arProp['VALUES'][$value]['CONTROL_ID'] . '=Y&amp;set_filter=y&amp;rz_all_elements=y';
		} else {
			$arTab['LINK'] = false;
		}
	}
	if (isset($arTab)) {
		unset($arTab);
	}
	if ($obCache->StartDataCache($arParams['CACHE_TIME'], $cacheId, $cachePath)) {
		if (defined('BX_COMP_MANAGED_CACHE')) {
			global $CACHE_MANAGER;
			$CACHE_MANAGER->StartTagCache($cachePath);
			CIBlock::registerWithTagCache($arParams['IBLOCK_ID']);
			$CACHE_MANAGER->EndTagCache();
		}
		$obCache->EndDataCache($arResult['TABS']);
	}
}

// hack for correct work with SKU
if(is_array($arParams["PROPERTY_CODE"]))
	$arParams["PROPERTY_CODE"] = array_diff($arParams["PROPERTY_CODE"], array(0, null));
if(empty($arParams["PROPERTY_CODE"])) 
	$arParams["PROPERTY_CODE"] = array('1');

if(!is_array($arParams['OFFERS_PROPERTY_CODE'])) $arParams['OFFERS_PROPERTY_CODE'] = array();
if(is_array($arParams['OFFER_TREE_PROPS']))
{
	foreach($arParams['OFFER_TREE_PROPS'] as $tree_prop)
	{
		if(!in_array($tree_prop, $arParams['OFFERS_PROPERTY_CODE']))
			$arParams['OFFERS_PROPERTY_CODE'][] = $tree_prop;
	}
	$arParams['LIST_OFFERS_LIMIT'] = 0;
}

//resizer
$arParams['RESIZER_SET_BIG'] = intval($arParams['RESIZER_SET_BIG']) ? $arParams['RESIZER_SET_BIG'] : 3;
$arParams['RESIZER_SET_BIG'] = intval($arParams['IMAGE_SET']) ? $arParams['IMAGE_SET'] : $arParams['RESIZER_SET_BIG'];
$arParams['RESIZER_SECTION_ICON'] = intval($arParams['RESIZER_SECTION_ICON']) ? $arParams['RESIZER_SECTION_ICON'] : 5;

//mobile
$arParams["IS_MOBILE"] = mobile::isMobile();

$arParams['DISPLAY_FAVORITE'] = $arParams['DISPLAY_FAVORITE'] && Loader::includeModule('yenisite.favorite');

// ##### Set params from setting.panel
global $rz_b2_options;
$arResult['SB-MODE'] = $rz_b2_options['sb-mode'];
$arResult['HOVER-MODE'] =  Mobile::isMobile() ? 'border-n-shadow' : $rz_b2_options['product-hover-effect'];

if ($rz_b2_options['convert_currency']) {
	$arParams['CONVERT_CURRENCY'] = 'Y';
	$arParams['CURRENCY_ID'] = $rz_b2_options['active-currency'];
}

if (Bitrix\Main\ModuleManager::isModuleInstalled("catalog")) {
	$arParams['SHOW_DISCOUNT_PERCENT'] = ($rz_b2_options['show_discount_percent'] === 'N') ? 'N' : 'Y';
	// you need it to support live preview of stores popups and have different cache
	$arParams['SHOW_STORE'] = $rz_b2_options['stores'];
}

?>