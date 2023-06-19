<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

global $rz_b2_options;

$APPLICATION->IncludeComponent("yenisite:highloadblock.view", "", Array(
		"BLOCK_ID"   => $arParams['BLOCK_ID'],
		"LIST_URL"   => $arResult['PATH_TO_LIST'],
		"ROW_ID"     => $arResult['VARIABLES']['ID'],
		"ROW_XML_ID" => $arResult['VARIABLES']['XML_ID'],
		"SET_TITLE"  => $arParams['SET_TITLE'],
		"BROWSER_TITLE"     => $arParams['BROWSER_TITLE'],
		"SET_BROWSER_TITLE" => $arParams['SET_BROWSER_TITLE'],
		"ADD_ELEMENT_CHAIN" => $arParams['ADD_ELEMENT_CHAIN'],
	),
	$component
);
