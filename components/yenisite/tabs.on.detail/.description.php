<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("ROMZA_TABS_NAME"),
	"DESCRIPTION" => GetMessage("ROMZA_TABS_DESCRIPTION"),
	"ICON" => "/images/tabs.gif",
	"CACHE_PATH" => "Y",
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