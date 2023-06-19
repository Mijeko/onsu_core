<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("WORKTIME_TEMPLATE_NAME"),
	"DESCRIPTION" => GetMessage("WORKTIME_TEMPLATE_DESCRIPTION"),
	"PATH" => array(	
		"ID" => "romza",
		"NAME" => GetMessage("ROMZA_COMPONENTS"),
		"CHILD" => array(
			"ID" => "service_rz",
			"NAME" => GetMessage("CD_RO_RSS"),
			"SORT" => 30
		)

	),
);

?>