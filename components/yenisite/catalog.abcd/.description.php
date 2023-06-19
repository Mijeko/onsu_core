<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("IBLOCK_FILTER_TEMPLATE_NAME"),
	"DESCRIPTION" => GetMessage("IBLOCK_FILTER_TEMPLATE_DESCRIPTION"),
	"ICON" => "/images/iblock_filter_abcd.gif",
	"CACHE_PATH" => "Y",
	"SORT" => 70,
	"PATH" => array(
		"ID" => "romza",
		"NAME" => GetMessage("ROMZA_COMPONENTS"),
		"CHILD" => array(
			"ID" => "catalog_rz",
			"NAME" => GetMessage("YENISITE_DESC_CATALOG"),
			"SORT" => 30
				),
			),
		);
?>