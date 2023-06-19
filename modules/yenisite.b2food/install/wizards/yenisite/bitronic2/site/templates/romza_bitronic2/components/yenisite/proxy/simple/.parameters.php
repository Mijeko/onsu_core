<?php

global $MESS;

$arComponentParameters = array();
/** @noinspection PhpDynamicAsStaticMethodCallInspection */
//\CComponentUtil::__IncludeLang($componentPath, ".parameters.php");
include $_SERVER["DOCUMENT_ROOT"] . $componentPath . "/.parameters.php";

$arTemplateParameters['QUANTITY'] = $arComponentParameters['QUANTITY'];
$arTemplateParameters['QUANTITY']['NAME'] = GetMessage('QUANTITY');

$MESS['QUANTITY_TIP'] = GetMessage('QUANTITY_TIP_BITRONIC2');

$ext = 'jpg,jpeg,png';

$arTemplateParameters['FILE'] = array(
    "PARENT" => "BASE",
    "NAME" => GetMessage('BANNER_IMG'),
    "TYPE" => "FILE",
    "FD_TARGET" => "F",
    "FD_EXT" => $ext,
    "FD_UPLOAD" => true,
    "FD_USE_MEDIALIB" => true,
    "FD_MEDIALIB_TYPES" => Array('image')
);
$arTemplateParameters['URL_BANNER'] = array(
    "PARENT" => "BASE",
    "NAME" => GetMessage('URL_BANNER'),
    "TYPE" => "STRING",
    "DEFAULT" => GetMessage('DEF_URL_BANNER')
);
$arTemplateParameters['IMG_ALT'] = array(
    "PARENT" => "BASE",
    "NAME" => GetMessage('IMG_ALT'),
    "TYPE" => "STRING",
    "DEFAULT" => GetMessage('DEF_IMG_ALT')
);

unset($arComponentParameters);
