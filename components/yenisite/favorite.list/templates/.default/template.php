<?php

if (!is_array($arParams['OFFERS_FIELD_CODE'])) $arParams['OFFERS_FIELD_CODE'] = array();

$arParams['OFFERS_LIMIT'] = 0;
$arParams['OFFERS_FIELD_CODE'] = array_unique(array_merge($arParams['OFFERS_FIELD_CODE'], array('ID', 'NAME', 'DETAIL_PAGE_URL')));

$APPLICATION->IncludeComponent('bitrix:catalog.section', $arParams['CATALOG_TEMPLATE'], $arParams, $component);