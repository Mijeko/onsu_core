<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

global $rz_b2_options;
 
if (is_array($arResult["GRID"]["ROWS"])) {
	foreach ($arResult["GRID"]["ROWS"] as &$arItem) {
		$arItem['data']['PICTURE_PRINT']['SRC'] = CRZBitronic2CatalogUtils::getElementPictureById($arItem['data']['PRODUCT_ID'], $arParams['RESIZER_BASKET_PHOTO']);
	}
	unset($arItem);
}

if (empty($arParams['PATH_TO_SETTINGS'])) {
	$arParams['PATH_TO_SETTINGS'] = SITE_DIR.'personal/profile/';
}

$arResult['USE_BONUS'] = false;

if ($rz_b2_options['pro_vbc_bonus']) {
	$arResult['USE_BONUS'] = true;
	if ($_POST["PAY_BONUS_ACCOUNT"] == "Y") {
		$arResult['USER_VALS']["PAY_BONUS_ACCOUNT"] = "Y";
	}
	if ($_POST["PAY_BONUSORDERPAY"] == "Y") {
		$arResult['USER_VALS']["PAY_BONUSORDERPAY"] = "Y";
	}
}