<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Yenisite\Core\ModulesCheck;?>
<?$APPLICATION->IncludeComponent("bitrix:catalog.section.list", "catalog", array(
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"SECTION_ID" => intval($arResult["VARIABLES"]["SECTION_ID"]),
	"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
	"COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
	"TOP_DEPTH" => '1',
	"SECTION_URL" => "",
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	"ADD_SECTIONS_CHAIN" => "N",
	"VIEW_MODE" => strtoupper($rz_b2_options['catalog_subsection_view']),
	"SHOW_PARENT_NAME" => "N",
	"SHOW_SUBSECTIONS" => $bDescBottom ? 'N' : $rz_b2_options['block_list-sub-sections'],
	"SHOW_DESCRIPTION" => $showDescription,
	"IS_BOTTOM" => $bDescBottom,
	"SECTIONS_START_COLUMNS" => $arParams['SECTIONS_START_COLUMNS'],
	"RESIZER_SET" => $arParams['RESIZER_SUBSECTION'],
    'SHOW_DESC' => $pagen < 2 || (!empty($_GLOBALS[$arParams['FILTER_NAME']]) && ModulesCheck::isSeoFilter())
	),
	$component
);?>