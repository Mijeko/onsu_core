<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

if (!Loader::includeModule('iblock'))
	return;

// RESIZER
$resizer_sets_list = array () ;
if(Loader::IncludeModule("yenisite.resizer2")){
	$arSets = CResizer2Set::GetList();
	while($arr = $arSets->Fetch())
	{
		$resizer_sets_list[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
	}
}



// ARTICUL
$arTemplateParameters['ARTICUL_PROP'] = array(
	'PARENT' => 'VISUAL',
	'NAME' => GetMessage('ARTICUL_PROP'),
	'TYPE' => 'STRING',
	'MULTIPLE' => 'N',
	'DEFAULT' => 'ARTICUL',
);

$arTemplateParameters["RESIZER_SET"] = array(
	"PARENT" => "VISUAL",
	"NAME" => GetMessage("RESIZER_SET"),
	"TYPE" => "LIST",
	"VALUES" => $resizer_sets_list,
	"DEFAULT" => "3",
);
if(Loader::includeModule('yenisite.favorite'))
{
	$arTemplateParameters['DISPLAY_FAVORITE'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('DISPLAY_FAVORITE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
		'HIDDEN' => 'Y',
	);
}

$arTemplateParameters["DISPLAY_TOP_PAGER"]['HIDDEN'] = 'Y';
$arTemplateParameters["DISPLAY_BOTTOM_PAGER"]['HIDDEN'] = 'Y';