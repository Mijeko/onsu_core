<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("SALE_PERSONAL_ORDER_DETAIL_B2_NAME"),
	"DESCRIPTION" => GetMessage("SALE_PERSONAL_ORDER_DETAIL_B2_DESC"),
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
