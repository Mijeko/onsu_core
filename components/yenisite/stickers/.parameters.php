<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arComponentParameters = array(
	"GROUPS" => array(
		"DEFAULT" => array(
			"NAME" => GetMessage("DEFAULT_GROUP"),
			"SORT" => '50',
		),
	),
	"PARAMETERS"=> array(
		"CUSTOM_COUNT" => array(
			"PARENT" => "BASE",
			"NAME" 	 => GetMessage('CUSTOM_COUNT'),
			"TYPE"	 => "STRING",
			"DEFAULT" => '0',
			"REFRESH" => 'Y',
		),	
		"STICKER_NEW" => array(
			"PARENT" => "DEFAULT",
			"NAME" 	 => GetMessage('STICKER_NEW'),
			"TYPE"	 => "STRING",
			"DEFAULT" => '14',
		),
		"STICKER_HIT" => array(
			"PARENT" => "DEFAULT",
			"NAME" 	 => GetMessage('STICKER_HIT'),
			"TYPE"	 => "STRING",
			"DEFAULT" => '100',
		),
		"STICKER_BESTSELLER" => array(
			"PARENT" => "DEFAULT",
			"NAME" 	 => GetMessage('STICKER_BESTSELLER'),
			"TYPE"	 => "STRING",
			"DEFAULT" => '3',
		),
	),
);


$NUM_CATEGORIES = intval($arCurrentValues["CUSTOM_COUNT"]);
if($NUM_CATEGORIES < 0)
	$NUM_CATEGORIES = 0;

for($i = 0; $i < $NUM_CATEGORIES; $i++)
{

	$arComponentParameters["GROUPS"]["CUSTOM_".$i] = array(
		"NAME" => GetMessage("NUM_CUSTOM", array("#NUM#" => $i+1))
	);
	
	$arIBlock=array();
	$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["CUSTOM_".$i."_IBLOCK_TYPE"], "ACTIVE"=>"Y"));
	while($arr=$rsIBlock->Fetch())
	{
		$arIBlock[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
	}

	$arComponentParameters["PARAMETERS"]["CUSTOM_".$i."_IBLOCK_TYPE"] = array(
		"PARENT" => "CUSTOM_".$i,
		"NAME" => GetMessage("IBLOCK_TYPE"),
		"TYPE" => "LIST",
		"VALUES" => $arIBlockType,
		"REFRESH" => "Y",
	);
	$arComponentParameters["PARAMETERS"]["CUSTOM_".$i."_IBLOCK_ID"] = array(
		"PARENT" => "CUSTOM_".$i,
		"NAME" => GetMessage("IBLOCK_IBLOCK"),
		"TYPE" => "LIST",
		"ADDITIONAL_VALUES" => "Y",
		"VALUES" => $arIBlock,
		"REFRESH" => "Y",
	);
	$arComponentParameters["PARAMETERS"]["CUSTOM_".$i."_STICKER_NEW"] = array(
		"PARENT" => "CUSTOM_".$i,
		"NAME" 	 => GetMessage('STICKER_NEW'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '14',
	);
	$arComponentParameters["PARAMETERS"]["CUSTOM_".$i."_STICKER_HIT"] = array(
		"PARENT" => "CUSTOM_".$i,
		"NAME" 	 => GetMessage('STICKER_HIT'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '100',
	);
	$arComponentParameters["PARAMETERS"]["CUSTOM_".$i."_STICKER_BESTSELLER"] = array(
		"PARENT" => "CUSTOM_".$i,
		"NAME" 	 => GetMessage('STICKER_BESTSELLER'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '3',
	);
	
}



?>