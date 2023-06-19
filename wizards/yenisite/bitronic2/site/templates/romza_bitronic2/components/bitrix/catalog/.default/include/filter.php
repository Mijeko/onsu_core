<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arParams['HIDE_NOT_AVAILABLE'] = $arParams['HIDE_ITEMS_NOT_AVAILABLE'] == 'Y' ? 'Y' : $arParams['HIDE_NOT_AVAILABLE'];
$arFilterParams = array(
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"SECTION_ID" => $arCurSection["ID"],
	"FILTER_NAME" => $arParams["FILTER_NAME"],
	'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	"SAVE_IN_SESSION" => "N",
	"INSTANT_RELOAD" => "N",
	"PRICE_CODE" => $arParams["PRICE_CODE"],
	"XML_EXPORT" => "N",
	"SECTION_TITLE" => "-",
	"SECTION_DESCRIPTION" => "-",
	"DISPLAY_ELEMENT_COUNT" => $arParams["FILTER_DISPLAY_ELEMENT_COUNT"],
	"VISIBLE_PROPS_COUNT" => $arParams["FILTER_VISIBLE_PROPS_COUNT"],
	"HIDE_DISABLED_PROPS" => ($arParams["FILTER_HIDE_DISABLED_VALUES"] === 'Y'),
	"SHOW_NAME_FIELD" => $arParams["FILTER_SHOW_NAME_FIELD"],
	"HIDE" => $arParams['FILTER_HIDE'],
	"FILTER_PLACE" => $arResult['FILTER_PLACE'],
	"CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
	"CURRENCY_ID" => $arParams['CURRENCY_ID'],
	"RESIZER_FILTER" => $arParams['RESIZER_FILTER'],
	"BRAND_HIDE" => $arParams['LIST_BRAND_USE'],
	"BRAND_PROP_CODE" => $arParams['LIST_BRAND_PROP_CODE'],
	'SHOW_ALL_WO_SECTION' => $arParams['SHOW_ALL_WO_SECTION'],
	"SEF_MODE" => $arParams["SEF_MODE"],
	"SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
	"SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
	"STICKERS" => array(
		"NEW" => $arParams['TAB_PROPERTY_NEW'],
		"HIT" => $arParams['TAB_PROPERTY_HIT'],
		"SALE" => $arParams['TAB_PROPERTY_SALE'],
		"BESTSELLER" => $arParams['TAB_PROPERTY_BESTSELLER'],
		"CUSTOM" => iRZProp::STICKERS
		),
	"MAIN_SP_ON_AUTO_NEW" => $arParams['MAIN_SP_ON_AUTO_NEW'],
	"STICKER_NEW" => isset($arParams['STICKER_NEW']) ? $arParams['STICKER_NEW'] : 14,
    'FILTER_SHOW_CNT' => $arParams['FILTER_SHOW_CNT'] ? $arParams['FILTER_SHOW_CNT'] : 3,
    'INCLUDE_SUBSECTIONS' => $arParams['INCLUDE_SUBSECTIONS'] ? : 'Y'
);

if (CModule::IncludeModule('catalog')) {

	$dbRes = CCatalogGroup::GetList();

	while ($arCatalogGroup = $dbRes->Fetch()) {
		if ($arCatalogGroup['CAN_BUY'] == 'Y') continue;

		$key = array_search($arCatalogGroup['NAME'], $arFilterParams['PRICE_CODE']);
		if ($key !== false && !empty($arFilterParams['PRICE_CODE'][$key])) {
			unset($arFilterParams['PRICE_CODE'][$key]);
		}
	}
}

if ($arParams['FILTER_SHOW_NAME_FIELD'] !== 'N' && !empty($_REQUEST[$arParams['FILTER_NAME'].'_FIELD_NAME'])) {
	$nameValue = trim($_REQUEST[$arParams['FILTER_NAME'].'_FIELD_NAME']);
	if (!empty($nameValue)) {
		global ${$arParams['FILTER_NAME']};
		$_arFilter = &${$arParams['FILTER_NAME']};
		if (empty($_arFilter) || !is_array($_arFilter)) {
			$_arFilter = array();
		}
		$nameValue = str_replace(array('%', '_'), array('\%', '\_'), $nameValue);
		$_arFilter['NAME'] = '%' . $nameValue . '%';
	}
}
global ${$arParams['FILTER_NAME']};
CRZBitronic2CatalogUtils::setFilterAvPrFoto(${$arParams['FILTER_NAME']}, $arParams);

$APPLICATION->IncludeComponent("bitrix:catalog.smart.filter", "filter", $arFilterParams,
$component
);