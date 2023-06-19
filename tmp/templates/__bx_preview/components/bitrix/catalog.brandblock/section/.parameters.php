<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $arComponentParameters;

// edit BLOCK_ID param
$arComponentParameters['PARAMETERS']['PROP_CODE']["MULTIPLE"] = "N";


// new Params
$arTemplateParameters = array(
	"FILTER_NAME" => array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("CP_BCSF_FILTER_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "arrFilter",
	),
	"PATH_FOLDER" => array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("PATH_FOLDER"),
		"TYPE" => "STRING",
		"DEFAULT" => "/catalog/",
	),
	"CATALOG_FILTER_NAME" => array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("CP_BCSF_CATALOG_FILTER_NAME"),
		"TYPE" => "STRING",
		"DEFAULT" => "arrFilter",
	),
);


unset($arComponentParameters['PARAMETERS']['ELEMENT_ID']);
unset($arComponentParameters['PARAMETERS']['ELEMENT_CODE']);