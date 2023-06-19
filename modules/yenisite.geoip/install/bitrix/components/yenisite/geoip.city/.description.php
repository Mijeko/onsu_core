<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("YENISITE_LOCATION_NAME"),
	"DESCRIPTION" => GetMessage("YENISITE_LOCATION_DESCRIPTION"),
	"ICON" => "/images/icon.png",
	"CACHE_PATH" => "Y",
	"SORT" => 30,
	"PATH" => array(
		"ID" => "romza",
		"NAME" => GetMessage("ROMZA_COMPONENTS"),
		"CHILD" => array(
			"ID" => "ys-geoip",
			"NAME" => GetMessage("YENISITE_LOCATION"),
			// "NAME" => GetMessage("YENISITE_DESC_CATALOG"),
			"SORT" => 30
		)
	)
);
?>
