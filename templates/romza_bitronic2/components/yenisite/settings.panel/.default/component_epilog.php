<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Page\Asset;
global $rz_b2_options, $USER;
if(count($arParams["EDIT_SETTINGS"]) > 0)
{
	$asset = Asset::getInstance();
	$asset->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/libs/UmAccordeon.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/libs/UmTabs.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/libs/UmComboBlocks.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/js/3rd-party-libs/nouislider.min.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/js/3rd-party-libs/jquery.minicolors.min.js");
	$asset->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/inits/modals/initModalSettings.js");
}