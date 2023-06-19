<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;

$resizer_sets_list = array ();
if(Loader::IncludeModule("yenisite.resizer2")){
	$arSets = CResizer2Set::GetList();
	while($arr = $arSets->Fetch())
	{
		$resizer_sets_list[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
	}
}

/* DOES NOT WORK IN LAST BITRIX VERSION
global $arComponentParameters;

// RESIZER:
$arComponentParameters["GROUPS"]["RESIZER_SETS"]= array(
	"NAME" => GetMessage("RESIZER_SETS"),
	"SORT" => 1
);
*/

$arTemplateParameters = array(
	/* These parameters should be accessed only through source code
	"SLIDER_WIDTH" => array(
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("SLIDER_WIDTH"),
		"TYPE" => "STRING",
		"DEFAULT" => '={$rz_b2_options["big-slider-width"]}',
	),
	"MENU_CATALOG" => array(
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("MENU_CATALOG"),
		"TYPE" => "STRING",
		"DEFAULT" => '={$rz_b2_options["catalog-placement"]}',
	),
	*/
	"YOUTUBE_PARAMETERS" => array(
		"NAME" => GetMessage("YOUTUBE_PARAMETERS"),
		"TYPE" => "STRING",
	),
	"RESIZER_SET_991" => array(
		"PARENT" => "RESIZER_SETS",
		"NAME" => GetMessage("RESIZER_SET_991"),
		"TYPE" => "LIST",
		"VALUES" => $resizer_sets_list,
		"DEFAULT" => "2",
	),
	"RESIZER_SET_1200" => array(
		"PARENT" => "RESIZER_SETS",
		"NAME" => GetMessage("RESIZER_SET_1200"),
		"TYPE" => "LIST",
		"VALUES" => $resizer_sets_list,
		"DEFAULT" => "7",
	),
	"USE_RESIZER_SET_FROM_1200" => array(
		"PARENT" => "RESIZER_SETS",
		"NAME" => GetMessage("USE_RESIZER_SET_FROM_1200"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
		"REFRESH" => "Y",
	),
);

if ($arCurrentValues["USE_RESIZER_SET_FROM_1200"] === 'Y') {
	$arTemplateParameters['RESIZER_SET_FROM_1200'] = array(
		"PARENT" => "RESIZER_SETS",
		"NAME" => GetMessage("RESIZER_SET_FROM_1200"),
		"TYPE" => "LIST",
		"VALUES" => $resizer_sets_list,
		"DEFAULT" => "1",
	);
}

// HIDE exist params
$arTemplateParameters['SORT_BY1']['HIDDEN'] = 'Y';
$arTemplateParameters['SORT_ORDER1']['HIDDEN'] = 'Y';
$arTemplateParameters['SORT_BY2']['HIDDEN'] = 'Y';
$arTemplateParameters['SORT_ORDER2']['HIDDEN'] = 'Y';
$arTemplateParameters['DETAIL_URL']['HIDDEN'] = 'Y';
$arTemplateParameters['AJAX_MODE']['HIDDEN'] = 'Y';
$arTemplateParameters['AJAX_OPTION_JUMP']['HIDDEN'] = 'Y';
$arTemplateParameters['AJAX_OPTION_STYLE']['HIDDEN'] = 'Y';
$arTemplateParameters['AJAX_OPTION_HISTORY']['HIDDEN'] = 'Y';
$arTemplateParameters['PREVIEW_TRUNCATE_LEN']['HIDDEN'] = 'Y';
$arTemplateParameters['ACTIVE_DATE_FORMAT']['HIDDEN'] = 'Y';
$arTemplateParameters['INCLUDE_IBLOCK_INTO_CHAIN']['HIDDEN'] = 'Y';
$arTemplateParameters['ADD_SECTIONS_CHAIN']['HIDDEN'] = 'Y';
$arTemplateParameters['HIDE_LINK_WHEN_NO_DETAIL']['HIDDEN'] = 'Y';
$arTemplateParameters['PARENT_SECTION']['HIDDEN'] = 'Y';
$arTemplateParameters['PARENT_SECTION_CODE']['HIDDEN'] = 'Y';
$arTemplateParameters['INCLUDE_SUBSECTIONS']['HIDDEN'] = 'Y';
$arTemplateParameters['PAGER_TEMPLATE']['HIDDEN'] = 'Y';
$arTemplateParameters['DISPLAY_TOP_PAGER']['HIDDEN'] = 'Y';
$arTemplateParameters['DISPLAY_BOTTOM_PAGER']['HIDDEN'] = 'Y';
$arTemplateParameters['PAGER_TITLE']['HIDDEN'] = 'Y';
$arTemplateParameters['PAGER_SHOW_ALWAYS']['HIDDEN'] = 'Y';
$arTemplateParameters['PAGER_DESC_NUMBERING']['HIDDEN'] = 'Y';
$arTemplateParameters['PAGER_DESC_NUMBERING_CACHE_TIME']['HIDDEN'] = 'Y';
$arTemplateParameters['PAGER_SHOW_ALL']['HIDDEN'] = 'Y';
$arTemplateParameters['AJAX_OPTION_ADDITIONAL']['HIDDEN'] = 'Y';

// edit params
$arTemplateParameters['NEWS_COUNT']['NAME'] = GetMessage("NEWS_COUNT");
?>