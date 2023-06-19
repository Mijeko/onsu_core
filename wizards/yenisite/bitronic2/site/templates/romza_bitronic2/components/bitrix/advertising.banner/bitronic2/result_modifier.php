<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arNewBanners = array();
if (!empty($arResult['BANNERS_PROPERTIES']) && is_array($arResult['BANNERS_PROPERTIES'])) {
	foreach ($arResult['BANNERS_PROPERTIES'] as $arBanner) {
		if(empty($arBanner) || intval($arBanner["IMAGE_ID"]) < 1) continue;
		$arNewBanners[] = $arBanner;
	}
}
$arResult['BANNERS_PROPERTIES'] = $arNewBanners;
