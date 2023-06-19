<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (\Bitrix\Main\Loader::includeModule('yenisite.geoipstore')) {
	$arComponentParameters = array();
	/** @noinspection PhpDynamicAsStaticMethodCallInspection */
	\CComponentUtil::__IncludeLang($componentPath, ".parameters.php");
	include($_SERVER["DOCUMENT_ROOT"].$componentPath."/.parameters.php");
	$arTemplateParameters['PRICE_CODE'] = array(
		'TYPE' => 'STRING',
		'NAME' => $arComponentParameters['PARAMETERS']['PRICE_CODE']['NAME'],
		'MULTIPLE' => 'N'
	);
	unset($arComponentParameters);
}