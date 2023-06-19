<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
if (CRZBitronic2Settings::isPro()) {
    $nameFilter = 'arrNewsFilterInInclude';
    CRZBitronic2CatalogUtils::setFilterByGeoStore($nameFilter);
}
if ($arResult["FILE"] <> '') include($arResult["FILE"]);