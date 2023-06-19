<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!CRZBitronic2Settings::isPro()) return;
global $rz_b2_options;

if ($arResult['VARIABLES']['ID'] === 'add'):
	include 'add.php';
else:
	$APPLICATION->IncludeComponent(
		"bitrix:sale.personal.profile.detail",
		"bitronic2",
		array(
			"PATH_TO_LIST" => $arResult["PATH_TO_LIST"],
			"PATH_TO_DETAIL" => $arResult["PATH_TO_DETAIL"],
			"SET_TITLE" => $arParams["SET_TITLE"],
			"USE_AJAX_LOCATIONS" => $arParams['USE_AJAX_LOCATIONS'],
			"AJAX_LOCATIONS_TEMPLATE" => $arParams['AJAX_LOCATIONS_TEMPLATE'],
			"ID" => $arResult["VARIABLES"]["ID"],
		),
		$component
	);
endif;
?>
