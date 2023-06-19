<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Page\Asset;

global $rz_b2_options;

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/back-end/ajax/main_spec.js");
if ($rz_b2_options['quick-view'] === 'Y') {
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/3rd-party-libs/jquery.mobile.just-touch.min.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/inits/initMainGallery.js");
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/inits/toggles/initGenInfoToggle.js");
}
