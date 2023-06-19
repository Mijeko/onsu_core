<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arCurrency = Array();
if (CModule::IncludeModule("currency"))
{
	$rsCurrency = CCurrency::GetList(($by="SORT"), ($order="ASC"));
	while($arr=$rsCurrency->Fetch()) 
		$arCurrency[$arr["CURRENCY"]] = "[".$arr["CURRENCY"]."] ".$arr["FULL_NAME"];
}

$arComponentParameters = Array(
	"PARAMETERS" => Array(
		"USER_ID" => Array(
			"NAME" => GetMessage("SAPP_USER_ID"),
			"TYPE" => "STRING",
			"DEFAULT" => $USER->GetID(),
		),
		"ACCOUNT_CURRENCY" => Array(
			"NAME"=>GetMessage("SAPP_ACCOUNT_CURRENCY"),
			"TYPE"=>"LIST",
			"MULTIPLE"=>"N",
			"VALUES" => $arCurrency,
			"ADDITIONAL_VALUES"=>"N",
		),
		
		"CACHE_TIME"  =>  Array("DEFAULT"=>1800),
	)
);
?>