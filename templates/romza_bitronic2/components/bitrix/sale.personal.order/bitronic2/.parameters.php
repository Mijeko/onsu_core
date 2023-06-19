<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (CModule::IncludeModule('sale'))
{
	$dbStat = CSaleStatus::GetList(array('sort' => 'asc'), array('LID' => LANGUAGE_ID), false, false, array('ID', 'NAME'));
	$statList = array();
	while ($item = $dbStat->Fetch())
		$statList[$item['ID']] = $item['NAME'];

	$statList['PSEUDO_CANCELLED'] = 1;	

	$availColors = array(
		'green' => GetMessage("SPO_STATUS_COLOR_GREEN"),
		'yellow' => GetMessage("SPO_STATUS_COLOR_YELLOW"),
		'red' => GetMessage("SPO_STATUS_COLOR_RED"),
		'gray' => GetMessage("SPO_STATUS_COLOR_GRAY"),
	);

	$colorDefaults = array(
		'N' => 'green', // new
		'P' => 'yellow', // payed
		'F' => 'gray', // finished
		'PSEUDO_CANCELLED' => 'red' // cancelled
	);

	foreach ($statList as $id => $name)
		$arTemplateParameters["STATUS_COLOR_".$id] = array(
			"NAME" => $id == 'PSEUDO_CANCELLED' ? GetMessage("SPO_PSEUDO_CANCELLED_COLOR") : GetMessage("SPO_STATUS_COLOR").' "'.$name.'"',
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => $availColors,
			"DEFAULT" => empty($colorDefaults[$id]) ? 'gray' : $colorDefaults[$id],
		);
}

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$resizer_sets_list = array () ;
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
	"RESIZER_BASKET_ICON" => array(
		"PARENT" => "RESIZER_SETS",
		"NAME" => GetMessage("RESIZER_BASKET_ICON"),
		"TYPE" => "LIST",
		"VALUES" => $resizer_sets_list,
		"DEFAULT" => "6",
	),
	"HIDE_PRICE_TYPE" => array(
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("HIDE_PRICE_TYPE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
);
?>