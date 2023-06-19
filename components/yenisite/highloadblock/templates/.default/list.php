<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

if (empty($_REQUEST['sort_id'])) {
	$_REQUEST['sort_id'] = $_GET['sort_id'] = 'UF_SORT';
}

if (empty($_REQUEST['sort_type'])) {
	$_REQUEST['sort_type'] = $_GET['sort_type'] = 'ASC';
}

$APPLICATION->IncludeComponent("bitrix:highloadblock.list", "", Array(
		"BLOCK_ID" => $arParams['BLOCK_ID'],
		"DETAIL_URL" => $arResult['PATH_TO_VIEW'],
		"NAV_TEMPLATE" => $arParams['NAV_TEMPLATE'],
	),
	$component
);