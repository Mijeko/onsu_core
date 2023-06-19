<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("MY_FEEDBACK_LIST_NAME"),
	"DESCRIPTION" => GetMessage("MY_FEEDBACK_LIST_DESC"),
	"ICON" => "/images/gb_list.gif",
	"SORT" => 10,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "romza",
		"SORT" => 2000,
		"NAME" => GetMessage("ROMZA_COMPONENTS"),
		"CHILD" => array(
			"ID" => "my_feedback",
			"NAME" => GetMessage("MY_FEEDBACK_LIST"),
			"SORT" => 10,
		)
	),
);

?>