<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("MY_FEEDBACK_NAME"),
	"DESCRIPTION" => GetMessage("MY_FEEDBACK_DESC"),
	"ICON" => "/images/gb.gif",
	"SORT" => 10,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "romza",
		"SORT" => 2000,
		"NAME" => GetMessage("ROMZA_COMPONENTS"),
		"CHILD" => array(
			"ID" => "my_feedback",
			"NAME" => GetMessage("MY_FEEDBACK"),
			"SORT" => 10,
		)
	),
);

?>