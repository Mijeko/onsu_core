<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("ROMZA_CATALOG_SECTION_PROXY_NAME"),
	"DESCRIPTION" => GetMessage("ROMZA_CATALOG_SECTION_PROXY_DESCRIPTION"),
	"ICON" => "/images/cs.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 70,
	"PATH" => array(
		"ID" => "romza",
		"NAME" => GetMessage("ROMZA_COMPONENTS"),
		"CHILD" => array(
			"ID" => "rz_core",
			"NAME" => GetMessage("ROMZA_CORE"),
			"SORT" => 30
				),
			),
		);
?>