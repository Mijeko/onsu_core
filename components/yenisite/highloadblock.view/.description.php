<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage('ROMZA_HLVIEW_COMPONENT_NAME'),
	"DESCRIPTION" => GetMessage('ROMZA_HLVIEW_COMPONENT_DESCRIPTION'),
	"ICON" => "images/hl_detail.gif",
	"SORT" => 20,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "romza",
		"CHILD" => array(
			"ID" => "hlblock",
		),
	),
);

?>