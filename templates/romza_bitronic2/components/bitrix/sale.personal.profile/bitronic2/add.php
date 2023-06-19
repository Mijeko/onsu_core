<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!CRZBitronic2Settings::isPro()) return;

$APPLICATION->IncludeComponent(
	"yenisite:sale.personal.profile.add",
	"bitronic2",
	array(
		"PATH_TO_LIST" => $arResult["PATH_TO_LIST"],
		"PATH_TO_DETAIL" => $arResult["PATH_TO_DETAIL"], // TODO: fix component to work with SEF URLs
		"SET_TITLE" => $arParams["SET_TITLE"],
		"USE_AJAX_LOCATIONS" => $arParams['USE_AJAX_LOCATIONS'],
		"AJAX_LOCATIONS_TEMPLATE" => $arParams['AJAX_LOCATIONS_TEMPLATE'],
	),
	$component
);
