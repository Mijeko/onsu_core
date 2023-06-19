<?
include_once "include_stop_statistic.php";

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

// @var $moduleId
include_once "include_module.php";

include_once($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lang/".LANGUAGE_ID."/header.php");

if (isset($_GET['action']) && $_GET['action'] == 'ADD2BASKET' && $moduleId == 'yenisite.bitronic2lite') {
	include_once $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'/ajax/basket_market.php';
	die();
}

include_once "include_options.php";

//show slider
include $_SERVER["DOCUMENT_ROOT"].SITE_DIR."include_areas/index/cool-slider.php";
