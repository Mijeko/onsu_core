<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;

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
	"RESIZER_SET_COMPARE" => array(
		"PARENT" => "RESIZER_SETS",
		"NAME" => GetMessage("RESIZER_SET_COMPARE"),
		"TYPE" => "LIST",
		"VALUES" => $resizer_sets_list,
		"DEFAULT" => "6",
	),
	
	"SHOW_VOTING" => array(
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("SHOW_VOTING"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
);

/*
$arTemplateParameters = array(
	'POSITION_FIXED' => array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BCCL_TPL_PARAM_TITLE_POSITION_FIXED'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
		'REFRESH' => 'Y'
	)
);

if (!isset($arCurrentValues['POSITION_FIXED']) || $arCurrentValues['POSITION_FIXED'] == 'Y')
{
	$positionList = array(
		'top left' => GetMessage('CP_BCCL_TPL_PARAM_POSITION_TOP_LEFT'),
		'top right' => GetMessage('CP_BCCL_TPL_PARAM_POSITION_TOP_RIGHT'),
		'bottom left' => GetMessage('CP_BCCL_TPL_PARAM_POSITION_BOTTOM_LEFT'),
		'bottom right' => GetMessage('CP_BCCL_TPL_PARAM_POSITION_BOTTOM_RIGHT')
	);
	$arTemplateParameters['POSITION'] = array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage('CP_BCCL_TPL_PARAM_TITLE_POSITION'),
		'TYPE' => 'LIST',
		'VALUES' => $positionList,
		'DEFAULT' => 'top left'
	);
	unset($positionList);
}*/
?>