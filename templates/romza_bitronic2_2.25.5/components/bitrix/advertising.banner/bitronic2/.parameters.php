<?php

global $MESS;

$arComponentParameters = array();
/** @noinspection PhpDynamicAsStaticMethodCallInspection */
//\CComponentUtil::__IncludeLang($componentPath, ".parameters.php");
include $_SERVER["DOCUMENT_ROOT"] . $componentPath . "/.parameters.php";

$arTemplateParameters['QUANTITY'] = $arComponentParameters['QUANTITY'];
$arTemplateParameters['QUANTITY']['NAME'] = GetMessage('QUANTITY');

$MESS['QUANTITY_TIP'] = GetMessage('QUANTITY_TIP_BITRONIC2');

unset($arComponentParameters);
