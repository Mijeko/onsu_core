<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!CRZBitronic2Settings::isPro()) return;

if ($arResult['VARIABLES']['ID'] === 'add'):
	include 'add.php';
else:
	$APPLICATION->IncludeComponent(
		"bitrix:sale.personal.profile.list",
		"bitronic2",
		array(
			"PATH_TO_DETAIL" => $arResult["PATH_TO_DETAIL"],
			"PER_PAGE" => $arParams["PER_PAGE"],
			"SET_TITLE" =>$arParams["SET_TITLE"],
		),
		$component
	);
endif;
?>
