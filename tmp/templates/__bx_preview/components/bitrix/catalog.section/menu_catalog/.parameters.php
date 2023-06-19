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

global $arComponentParameters;

// RESIZER:
$arComponentParameters["GROUPS"]["RESIZER_SETS"]= array(
	"NAME" => GetMessage("RESIZER_SETS"),
	"SORT" => 1
);

$arTemplateParameters = array(
	"RESIZER_SET" => array(
		"PARENT" => "RESIZER_SETS",
		"NAME" => GetMessage("RESIZER_SET"),
		"TYPE" => "LIST",
		"VALUES" => $resizer_sets_list,
		"DEFAULT" => "3",
	),
);


// HIDE exist params
$arTemplateParameters['LINE_ELEMENT_COUNT']['HIDDEN'] = 'Y';
$arTemplateParameters['PAGER_TEMPLATE']['HIDDEN'] = 'Y';
$arTemplateParameters['DISPLAY_TOP_PAGER']['HIDDEN'] = 'Y';
$arTemplateParameters['DISPLAY_BOTTOM_PAGER']['HIDDEN'] = 'Y';
$arTemplateParameters['PAGER_TITLE']['HIDDEN'] = 'Y';
$arTemplateParameters['PAGER_SHOW_ALWAYS']['HIDDEN'] = 'Y';
$arTemplateParameters['PAGER_DESC_NUMBERING']['HIDDEN'] = 'Y';
$arTemplateParameters['PAGER_DESC_NUMBERING_CACHE_TIME']['HIDDEN'] = 'Y';
$arTemplateParameters['PAGER_SHOW_ALL']['HIDDEN'] = 'Y';
$arTemplateParameters['AJAX_OPTION_ADDITIONAL']['HIDDEN'] = 'Y';
$arTemplateParameters['DISPLAY_COMPARE']['HIDDEN'] = 'Y';
$arTemplateParameters['BASKET_URL']['HIDDEN'] = 'Y';
$arTemplateParameters['USE_PRODUCT_QUANTITY']['HIDDEN'] = 'Y';
$arTemplateParameters['ADD_PROPERTIES_TO_BASKET']['HIDDEN'] = 'Y';
$arTemplateParameters['PRODUCT_PROPS_VARIABLE']['HIDDEN'] = 'Y';
$arTemplateParameters['PARTIAL_PRODUCT_PROPERTIES']['HIDDEN'] = 'Y';
$arTemplateParameters['PRODUCT_PROPERTIES']['HIDDEN'] = 'Y';
$arTemplateParameters['ACTION_VARIABLE']['HIDDEN'] = 'Y';
$arTemplateParameters['PRODUCT_ID_VARIABLE']['HIDDEN'] = 'Y';
$arTemplateParameters['SET_STATUS_404']['HIDDEN'] = 'Y';
$arTemplateParameters['ADD_SECTIONS_CHAIN']['HIDDEN'] = 'Y';
$arTemplateParameters['SET_META_DESCRIPTION']['HIDDEN'] = 'Y';
$arTemplateParameters['SET_META_KEYWORDS']['HIDDEN'] = 'Y';
$arTemplateParameters['SET_BROWSER_TITLE']['HIDDEN'] = 'Y';
$arTemplateParameters['SET_TITLE']['HIDDEN'] = 'Y';
$arTemplateParameters['PROPERTY_CODE']['HIDDEN'] = 'Y';
$arTemplateParameters['SHOW_ALL_WO_SECTION']['HIDDEN'] = 'Y';

// EDIT exist params
$arTemplateParameters['PAGE_ELEMENT_COUNT']['NAME'] = GetMessage('PAGE_ELEMENT_COUNT');
?>