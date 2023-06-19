<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("RZ_CP_NAME"),
	"DESCRIPTION" => GetMessage("RZ_CP_DESCRIPTION"),
	"PATH" => array(	
		"ID" => "romza",
		"NAME" => GetMessage("ROMZA_COMPONENTS"),
		"CHILD" => array(
			"ID" => "sale_rz",
			"NAME" => GetMessage("RZ_SALE_FOLDER"),
			"SORT" => 30
		)

	),
);
?>
