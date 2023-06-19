<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Romza\WidgetInstagram\Main;

if (empty($arResult['ITEMS'])) return;

$resizer = array('WIDTH' => 191, 'HEIGHT' => 191, 'SET_ID' => $arParams['RESIZER_ITEM']);
foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['IMAGE'] = CRZBitronic2CatalogUtils::GetResizedImg($arItem['IMAGE'], $resizer);
    $arItem['CNT_LIKES'] = Main::formateCntHowInInstagram($arItem['CNT_LIKES']);
    $arItem['CNT_COMMENT']= Main::formateCntHowInInstagram($arItem['CNT_COMMENT']);
}