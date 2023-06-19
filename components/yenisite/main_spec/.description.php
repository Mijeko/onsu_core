<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("ROMZA_NAME"),
	"DESCRIPTION" => GetMessage("ROMZA_DESCRIPTION"),
	"ICON" => "/images/main_spec.png",
	"CACHE_PATH" => "Y",
	"SORT" => 100,
	"PATH" => array(
		"ID" => "romza",
		"NAME" => GetMessage("ROMZA_COMPONENTS"),
		"CHILD" => array(
			"ID" => "catalog_rz",
			"NAME" => GetMessage("ROMZA_DESC_CATALOG"),
			"SORT" => 30
		)
	),
);
?>