<?
include_once "include_stop_statistic.php";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

include_once "include_module.php";
include_once "include_options.php";
include_once($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lang/".LANGUAGE_ID."/header.php");

$pf = '';
if ('Y' == $rz_b2_options['change_contacts']) {
	$pf = $rz_b2_options['GEOIP']['INCLUDE_POSTFIX'];
}
\Yenisite\Core\Tools::includePostfixArea($pf, SITE_DIR . "include_areas/footer/address_popup.php", true, NULL, true);