<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();

$asset->addJs(SITE_TEMPLATE_PATH . '/js/3rd-party-libs/isotope.pkgd.min.js');
$asset->addJs(SITE_TEMPLATE_PATH . '/js/custom-scripts/inits/pages/initVacancyPage.js');
