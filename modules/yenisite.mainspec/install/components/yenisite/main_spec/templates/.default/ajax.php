<?
if($_REQUEST["ys_ms_ajax_call"] === "y"){
	require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
}else{
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
}
$arParams = array();
if(intval($_REQUEST["cache_time"]) >0 )
	$arParams['CACHE_TIME'] = intval($_REQUEST["cache_time"]);

global $APPLICATION;
$APPLICATION->IncludeComponent("yenisite:main_spec", ".default", $arParams, false);
?>