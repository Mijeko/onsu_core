<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if ($arParams['SET_METTA']){
    global $APPLICATION;
    $arCurSection = $templateData['CUR_SECTION'];
    $arIpropValues = $arCurSection['IPROPERTY_VALUES'];

    $APPLICATION->SetTitle($arIpropValues['SECTION_PAGE_TITLE'] ? : $arCurSection['NAME']);
    $APPLICATION->SetPageProperty('keywords',$arIpropValues['SECTION_META_KEYWORDS'] ? : $arCurSection['NAME']);
    $APPLICATION->SetPageProperty('description',$arIpropValues['SECTION_META_DESCRIPTION'] ? : $arCurSection['NAME']);
    $APPLICATION->SetPageProperty('title',$arIpropValues['SECTION_META_TITLE'] ? : $arCurSection['NAME']);
    $APPLICATION->AddChainItem($arIpropValues['SECTION_PAGE_TITLE'] ? : $arCurSection['NAME'],$arCurSection['SECTION_PAGE_URL']);
}