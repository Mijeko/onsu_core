<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

if(Loader::IncludeModule("iblock")){
	
}

$resizer_sets_list = array ();
if(Loader::IncludeModule("yenisite.resizer2")){
	$arSets = CResizer2Set::GetList();
	while($arr = $arSets->Fetch())
	{
		$resizer_sets_list[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
	}
}

$catalogIncluded = Loader::includeModule('catalog');

$arPrice = array();
if ($catalogIncluded)
{
	$arPrice = CCatalogIBlockParameters::getPriceTypesList();
}

$siteId = $_REQUEST['src_site'];
$siteTemplate = $_REQUEST['siteTemplateId'];
if (empty($siteTemplate)) $siteTemplate = $_REQUEST['site_template'];
if (empty($siteTemplate)) $siteTemplate = $_REQUEST['template_id'];
// @var $moduleId
include $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/templates/' . $siteTemplate . '/include/module_code.php';
Loader::includeModule($moduleId);

global $arComponentParameters;

// RESIZER:
$arComponentParameters["GROUPS"]["RESIZER_SETS"]= array(
	"NAME" => GetMessage("RESIZER_SETS"),
	"SORT" => 1
);
	
$arTemplateParameters["RESIZER_SET"] = array(
	"PARENT" => "RESIZER_SETS",
	"NAME" => GetMessage("RESIZER_SET"),
	"TYPE" => "LIST",
	"VALUES" => $resizer_sets_list,
	"DEFAULT" => "3",
);
$arTemplateParameters["ICON_RESIZER_SET"] = array(
	"PARENT" => "RESIZER_SETS",
	"NAME" => GetMessage("ICON_RESIZER_SET"),
	"TYPE" => "LIST",
	"VALUES" => $resizer_sets_list,
	"DEFAULT" => "5",
);
$arTemplateParameters["HITS_COMPONENT"] = array(
	"NAME" => GetMessage("HITS_COMPONENT"),
	"TYPE" => "LIST",
	"VALUES" => array(
		'CATALOG' => GetMessage('HITS_COMPONENT_CATALOG'),
		'BIGDATA' => GetMessage('HITS_COMPONENT_BIGDATA')
		),
	"SIZE" => 2,
	"DEFAULT" => "CATALOG",
	"REFRESH" => "Y",
);
/*
$arTemplateParameters["HITS_POSITION"] = array(
	"NAME" => GetMessage("HITS_POSITION"),
	"TYPE" => "LIST",
	"VALUES" => array(
		'TOP' => GetMessage('HITS_POSITION_TOP'),
		'BOTTOM' => GetMessage('HITS_POSITION_BOTTOM')
		),
	"SIZE" => 2,
	"DEFAULT" => "TOP",
);
*/
$arTemplateParameters["HITS_TYPE"] = array(
	"NAME" => GetMessage("HITS_TYPE"),
	"TYPE" => "LIST",
	"VALUES" => array(
		'NEW' => GetMessage('HITS_TYPE_NEW'),
		'HIT' => GetMessage('HITS_TYPE_HIT'),
		'SALE' => GetMessage('HITS_TYPE_SALE'),
		'RECS' => GetMessage('HITS_TYPE_RECS'),
		'SHOW' => GetMessage('HITS_TYPE_SHOW')
		),
	"DEFAULT" => "SHOW",
);
if ($arCurrentValues["HITS_COMPONENT"] == 'BIGDATA') {
	$arTemplateParameters['HITS_TYPE']['VALUES'] = array(
		'bestsell' => GetMessage('HITS_TYPE_BESTSELL'),
		'personal' => GetMessage('HITS_TYPE_PERSONAL')
		);
	$arTemplateParameters['HITS_TYPE']['DEFAULT'] = 'bestsell';
}
if (!array_key_exists($arCurrentValues['HITS_TYPE'], $arTemplateParameters['HITS_TYPE']['VALUES'])) {
	$arCurrentValues['HITS_TYPE'] = $arTemplateParameters['HITS_TYPE']['DEFAULT'];
}

if($catalogIncluded)
{
	$arTemplateParameters["PRICE_CODE"] = array(
		//"PARENT" => "PRICES",
		"NAME" => GetMessage("IBLOCK_PRICE_CODE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "Y",
		"VALUES" => $arPrice,
	);
	$arTemplateParameters["SLIDERS_HIDE_NOT_AVAILABLE"] = array(
		'NAME' => GetMessage('HITS_HIDE_NOT_AVAILABLE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'HIDDEN' => CRZBitronic2Settings::isPro($bWithGeoip = true, $siteId) ? 'Y' : 'N'
	);
}
if (\Bitrix\Main\Loader::includeModule('yenisite.geoipstore')) {
	$arTemplateParameters['PRICE_CODE']['TYPE'] = 'STRING';
	$arTemplateParameters['PRICE_CODE']["MULTIPLE"] = "N";
}
?>