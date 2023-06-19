<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (CRZBitronic2Settings::isPro()) {
    CRZBitronic2CatalogUtils::setFilterByGeoStore($arParams['FILTER_NAME']);
}