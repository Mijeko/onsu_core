<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("MAIN_BREADCRUMB_NAME"),
	"DESCRIPTION" => GetMessage("MAIN_BREADCRUMB_DESC"),
	"ICON" => "/images/breadcrumb.gif",
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