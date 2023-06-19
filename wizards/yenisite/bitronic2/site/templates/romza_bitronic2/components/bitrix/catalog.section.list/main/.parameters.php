<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
global $USER_FIELD_MANAGER;

if(!\Bitrix\Main\Loader::includeModule("iblock"))
    return;

$resizer_sets_list = array () ;
if(Loader::IncludeModule("yenisite.resizer2")){
	$arSets = CResizer2Set::GetList();
	while($arr = $arSets->Fetch())
	{
		$resizer_sets_list[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
	}
}

global $arComponentParameters;

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock = array();
$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE"], "ACTIVE"=>"Y"));
while($arr=$rsIBlock->Fetch())
    $arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];

$arProperty_UF = array();
$arUserFields = $USER_FIELD_MANAGER->GetUserFields("IBLOCK_".$arCurrentValues["IBLOCK_ID"]."_SECTION", 0, LANGUAGE_ID);
foreach($arUserFields as $FIELD_NAME=>$arUserField)
{
    $arUserField['LIST_COLUMN_LABEL'] = (string)$arUserField['LIST_COLUMN_LABEL'];
    $arProperty_UF[$FIELD_NAME] = $arUserField['LIST_COLUMN_LABEL'] ? '['.$FIELD_NAME.']'.$arUserField['LIST_COLUMN_LABEL'] : $FIELD_NAME;
}

// RESIZER:
$arComponentParameters["GROUPS"]["RESIZER_SETS"]= array(
	"NAME" => GetMessage("RESIZER_SETS"),
	"SORT" => 1
);

$arTemplateParameters = array(
	"RESIZER_SECTION_ICON" => array(
		"PARENT" => "RESIZER_SETS",
		"NAME" => GetMessage("RESIZER_SECTION_ICON"),
		"TYPE" => "LIST",
		"VALUES" => $resizer_sets_list,
		"DEFAULT" => "6",
	),
    "RESIZER_SECTION_LARGE" => array(
        "PARENT" => "RESIZER_SETS",
        "NAME" => GetMessage("RESIZER_SECTION_LARGE"),
        "TYPE" => "LIST",
        "VALUES" => $resizer_sets_list,
        "DEFAULT" => "4",
    ),
    "RESIZER_SECTION_BIG" => array(
        "PARENT" => "RESIZER_SETS",
        "NAME" => GetMessage("RESIZER_SECTION_BIG"),
        "TYPE" => "LIST",
        "VALUES" => $resizer_sets_list,
        "DEFAULT" => "2",
    ),
    "PROP_OF_BIG_IMG" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("PROP_OF_BIG_IMG"),
        "TYPE" => "LIST",
        "VALUES" => $arProperty_UF,
        "DEFAULT" => "UF_IMG_BLOCK_FOTO",
    ),
);

?>