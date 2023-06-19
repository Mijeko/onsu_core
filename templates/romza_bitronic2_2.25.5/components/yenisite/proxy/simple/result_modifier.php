<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arNewBanners = array();
if (!empty($arParams['FILE'])) {
    $arNewBanners['IMAGE_URL'] = $arParams['FILE'];
    $arNewBanners['IMG_ALT'] = $arParams['IMG_ALT'] ? : GetMessage('IMG_ALT');
    $arNewBanners['URL_TARGET'] = $arParams['URL_BANNER'] ? : GetMessage('URL_BANNER');
    $arResult['BANNER_PROPERTIES'] = $arNewBanners;
}
