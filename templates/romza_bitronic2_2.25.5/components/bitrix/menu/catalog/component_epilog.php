<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Page\Asset;

if ($templateData['VIEW_HITS']) {
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/sliders/initHorizontalCarousels.js");
}