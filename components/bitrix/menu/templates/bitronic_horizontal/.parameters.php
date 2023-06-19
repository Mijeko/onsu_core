<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $arComponentParameters;

$arComponentParameters["GROUPS"]["YENISITE_BS_FILTER"]= array(
	"NAME" => GetMessage("YS_BS_GROUP_NAME"),
	"SORT" => 2000,
);

if(!CModule::IncludeModule("catalog"))
{
	$arPrice = array("YS_EMPTY" => "-----");
	foreach($arCurrentValues["IBLOCK_ID_IN"] as $id)
	if($id > 0)
	{
	    $rsPrice = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $id, 
	    	array("LOGIC" => "OR", array("PROPERTY_TYPE" => "S"), array("PROPERTY_TYPE" => "N"))));
	    while($arr=$rsPrice->Fetch())
	    {
	    	if(!in_array($arr["NAME"], $arPrice))
	    	{
	    		 $arPrice[$arr["CODE"]] = $arr["NAME"];
	    	}
	    }
	}
}
else
{	
	$rsPrice = CCatalogGroup::GetList($v1 = "sort", $v2 = "asc");
	while($arr = $rsPrice->Fetch()) 
	{
		$arPrice[$arr["NAME"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
	}
}

if(CModule::IncludeModule("currency"))
{
	$rsCur = CCurrency::GetList(($by = "name"), ($order1 = "asc"), LANGUAGE_ID);
	while($arCur = $rsCur->Fetch())
	{
		$arCurrencies[$arCur["CURRENCY"]] = $arCur["CURRENCY"];
	}
}

$arTemplateParameters = array(
	"INCLUDE_JQUERY" => Array(
		"PARENT" => "YENISITE_BS_FILTER",
		"NAME" => GetMessage("INCLUDE_JQUERY"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => 'Y'
	),
	"THEME" => array(
		"PARENT" => "YENISITE_BS_FILTER",
		"NAME" => GetMessage("THEME"),
		"TYPE" => "LIST",
		"VALUES" => array(
			"red" => GetMessage("THEME_RED"),
			"ice" => GetMessage("THEME_ICE"),
			"green" => GetMessage("THEME_GREEN"),
			"yellow" => GetMessage("THEME_YELLOW"),
			"pink" => GetMessage("THEME_PINK"),
			"metal" => GetMessage("THEME_METAL"),
		),
		"ADDITIONAL_VALUES" => "Y",
		"DEFAULT" => '={$ys_options["color_scheme"]}',
	),
	"VIEW_HIT" => array(
		"PARENT" => "YENISITE_BS_FILTER",
		"NAME" => GetMessage("VIEW_HIT"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "N",
	),
	"PRICE_CODE" => array(
		"PARENT" => "YENISITE_BS_FILTER",
		"NAME" => GetMessage("PRICE_CODE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"VALUES" => $arPrice,
	),
	"CURRENCY" => array(
		"PARENT" => "YENISITE_BS_FILTER",
		"NAME" => GetMessage("CURRENCY"),
		"TYPE" => "LIST",
		"MULTIPLE" => "N",
		"VALUES" => $arCurrencies,
	),
	"RUB_SIGN" => array(
		"PARENT" => "YENISITE_BS_FILTER",
		"NAME" => GetMessage("RUB_SIGN"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"MASK" => array(
		"PARENT" => "YENISITE_BS_FILTER",
		"NAME" => GetMessage("MASK"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	),
	"SPEED" => array(
		"PARENT" => "YENISITE_BS_FILTER",
		"NAME" => GetMessage("SPEED"),
		"TYPE" => "LIST",
		"VALUES" => array(
			'NONE'   => GetMessage('SPEED_NONE'),
			'FAST'   => GetMessage('SPEED_FAST'),
			'NORMAL' => GetMessage('SPEED_NORMAL'),
			'SLOW'   => GetMessage('SPEED_SLOW')
		),
		"DEFAULT" => "NORMAL",
	),
);

if(!CModule::IncludeModule('catalog')) unset($arTemplateParameters["CURRENCY"]);
unset($arComponentParameters['PARAMETERS']['MAX_LEVEL']);
?>