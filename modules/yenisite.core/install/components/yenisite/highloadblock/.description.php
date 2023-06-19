<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("ROMZA_HIGHLOADBLOCK_NAME"),
	"DESCRIPTION" => GetMessage("ROMZA_HIGHLOADBLOCK_DESCRIPTION"),
	"ICON" => "/images/hl.gif",
	"PATH" => array(
		"ID" => "romza",
		"CHILD" => array(
			"ID" => "hlblock",
			"NAME" => GetMessage("ROMZA_HIGHLOADBLOCK_CATEGORY_TITLE"),
		)
	),
);
?>