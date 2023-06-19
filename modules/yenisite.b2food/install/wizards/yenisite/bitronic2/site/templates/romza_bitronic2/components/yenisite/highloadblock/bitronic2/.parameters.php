<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Bitrix\Highloadblock as HL;

$arProperties = array('-' => GetMessage('CP_BC_TPL_PROP_EMPTY'));

if(!empty($arCurrentValues['BLOCK_ID']) && Loader::includeModule('highloadblock')){
    $dbBlock = HL\HighloadBlockTable::getById($arCurrentValues['BLOCK_ID'])->fetch();
    $hlentity = HL\HighloadBlockTable::compileEntity($dbBlock);
    $strEntityDataClass = $hlentity->getDataClass();

    $rsData = $strEntityDataClass::getList(array(
        'select' => array('*'),
        'order' => array('ID' => 'ASC'),
    ));
    $arItem = $rsData->Fetch();
    foreach ($arItem as $key => $value){
        $arProperties[$key] = '['.$key.']';
    }
}

$arTemplateParameters = array(
	"PATH_TO_CATALOG" => array(
		"NAME" => GetMessage("RZ_HLB_PATH_TO_CATALOG"),
		"TYPE" => "STRING",
		"MULTIPLE" => "N",
		"DEFAULT" => "/catalog/",
		"COLS" => 25,
		"PARENT" => "URL_TEMPLATES",
	),
	"ELEMENT_COUNT_BRANDS" => array(
		"NAME" => GetMessage("RZ_ELEMENT_COUNT_BRANDS"),
		"TYPE" => "STRING",
		"MULTIPLE" => "N",
		"DEFAULT" => "20",
		"PARENT" => "BASE",
	),
);

if (Loader::includeModule('yenisite.resizer2')) {
	$arResizerSets = array();
	$arSets = CResizer2Set::GetList();
	while ($arr = $arSets->Fetch()) {
		$arResizerSets[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
	}
	$arTemplateParameters += array(
		"LIST_RESIZER_SET" => array(
			"PARENT" => "RESIZER",
			"NAME" => GetMessage('RZ_HLB_LIST_RESIZER_SET'),
			"TYPE" => "LIST",
			"VALUES" => $arResizerSets,
			"DEFAULT" => 3
		),
		"VIEW_RESIZER_SET" => array(
			"PARENT" => "RESIZER",
			"NAME" => GetMessage('RZ_HLB_VIEW_RESIZER_SET'),
			"TYPE" => "LIST",
			"VALUES" => $arResizerSets,
			"DEFAULT" => 2
		),
        "SHOW_PROPS_OF_HLB" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage('SHOW_PROPS_OF_HLB'),
            "TYPE" => "LIST",
            "MULTIPLE" => 'Y',
            "VALUES" => $arProperties,
            "DEFAULT" => array('UF_DEF','UF_SORT','UF_LINK'),
        ),
        "PROP_FOR_LINK_COMPONY" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage('PROP_FOR_LINK_COMPONY'),
            "TYPE" => "LIST",
            "VALUES" => $arProperties,
            "DEFAULT" => 'UF_LINK',
    )
	);
}

