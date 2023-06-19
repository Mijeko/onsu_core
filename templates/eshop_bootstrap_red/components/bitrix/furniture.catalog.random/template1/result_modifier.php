<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$arResult['NAME'] = $arResult['PREVIEW_TEXT'];

$arResult['PROPERTY_PRICE_VALUE'] = number_format($arResult['PROPERTY_PRICE_VALUE'], 0, '.', ' ');
$arResult['PROPERTY_PRICE_VALUE'] .= ' '.$arResult['PROPERTY_PRICECURRENCY_VALUE'];
?>