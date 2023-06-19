<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("MY_FEEDBACK_ADD_NAME"),
	"DESCRIPTION" => GetMessage("MY_FEEDBACK_ADD_DESC"),
	"ICON" => "/images/gb_add.gif",
	"SORT" => 10,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "romza",
		"SORT" => 2000,
		"NAME" => GetMessage("ROMZA_COMPONENTS"),
		"CHILD" => array(
			"ID" => "my_feedback",
			"NAME" => GetMessage("MY_FEEDBACK_ADD"),
			"SORT" => 10,
		)
	),
);

?>