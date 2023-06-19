<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Page\Asset;
use Bitronic2\Mobile;

if (is_array($arParams['MENU_CATALOG']) && $arParams['MENU_CATALOG']['hits'] == 'Y' && !mobile::isMobile()) {
	Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/inits/sliders/initHorizontalCarousels.js");
}