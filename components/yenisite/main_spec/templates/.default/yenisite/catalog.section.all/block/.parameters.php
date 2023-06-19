<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $arComponentParameters;
$arComponentParameters["GROUPS"]["STICKERS"]= array(
	"NAME" => GetMessage("STICKER_GROUP"),
	"SORT" => '10000',
);
$arPrice = array();
if(CModule::IncludeModule("catalog"))
{
	$rsPrice=CCatalogGroup::GetList($v1="sort", $v2="asc");
	while($arr=$rsPrice->Fetch()) $arPrice[$arr["NAME"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
}
else
{
	$arPrice = $arProperty_N;
}

$arTemplateParameters = array(
	"STICKER_NEW" => array(
		"PARENT" => "STICKERS",
		"NAME" 	 => GetMessage('STICKER_NEW'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '14',
	),
	"STICKER_HIT" => array(
		"PARENT" => "STICKERS",
		"NAME" 	 => GetMessage('STICKER_HIT'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '100',
	),
	"STICKER_BESTSELLER" => array(
		"PARENT" => "STICKERS",
		"NAME" 	 => GetMessage('STICKER_BESTSELLER'),
		"TYPE"	 => "STRING",
		"DEFAULT" => '3',
	),
	"LIST_PRICE_SORT" => array(
        "PARENT" => "DATA_SOURCE",
        "NAME"   => GetMessage("PRICE_SORT"),
        "TYPE"   => "LIST",
        "MULTIPLE" => "N",
		"ADDITIONAL_VALUES" => "N",
        "VALUES" => $arPrice,
        "DEFAULT" => "CATALOG_PRICE_1"
    ),
	'HIDE_BUY_IF_PROPS' => array(
		"PARENT" => "PRICES",
		"NAME" => GetMessage("HIDE_BUY_IF_PROPS"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y"
	),
);
?>