<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $USER;
$arNewParams = array();
foreach ($arParams as $key => $param) {
    if (strpos($key, '-') !== false) {
        $ar = explode('-', $key);

        $arNewParams[$ar[0]][$ar[1]] = $param;
    } else {
        $arNewParams[$key] = $param;
    }
}

$arParams['BITRIX_NEWS_LIST'] = $arNewParams['BITRIX_NEWS_LIST'];