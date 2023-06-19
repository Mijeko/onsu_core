<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;


$arComponentParameters = array();
/** @noinspection PhpDynamicAsStaticMethodCallInspection */
\CComponentUtil::__IncludeLang($componentPath, ".parameters.php");
include($_SERVER["DOCUMENT_ROOT"].$componentPath."/.parameters.php");

$arTemplateParameters['FILE_NAME'] = $arComponentParameters['FILE_NAME'];
$arTemplateParameters['FILE_NAME']['DEFAULT'] = '/pricelist/price';
$arTemplateParameters['FILE_NAME']['HIDDEN'] = 'Y';

if (Loader::includeModule('yenisite.geoipstore')) {
	$arTemplateParameters['PRICE_CODE'] = $arComponentParameters['PARAMETERS']['PRICE_CODE'];
	$arTemplateParameters['PRICE_CODE']['TYPE'] = 'STRING';
	$arTemplateParameters['PRICE_CODE']['MULTIPLE'] = 'N';
}

unset($arComponentParameters);