<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arTemplateParameters = array(
	"AJAX_LOCATIONS_TEMPLATE" => array(
		"NAME" => GetMessage("AJAX_LOCATIONS_TEMPLATE"),
		"TYPE" => "LIST",
		"VALUES" => array(
			'search' => GetMessage('AJAX_LOCATIONS_TEMPLATE_SEARCH'),
			'steps' => GetMessage('AJAX_LOCATIONS_TEMPLATE_STEPS')
		),
		"DEFAULT" => "steps",
	),
);
?>