<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use \Yenisite\Core\Ajax;
use \Yenisite\Core\Tools;

$this->setFrameMode(true);
include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/include/debug_info_dynamic.php';
global $APPLICATION;

$bAjax = Ajax::isAjax();
$bRightRequest = htmlspecialcharsbx($_REQUEST['get_block_reviews']) == 'Y';
$bInCatalog = defined("IN_CATALOG_LIST") || htmlspecialcharsbx($_REQUEST['in_catalog_section']) == 'Y';
$arRelatedItems = is_array($_REQUEST['items_of_reviews']) ? $_REQUEST['items_of_reviews'] : array(0);

if ((!$bAjax && !Tools::isEditModeOn()) || (!$bAjax && $bInCatalog)):
    ?>
    <div id="backend-review-container" class="reviews-wrapper"></div>
    <?
endif;

if ((!$bAjax && !Tools::isEditModeOn()) || (!$bAjax && $bInCatalog)) return;
if ($bAjax && !$bRightRequest) return;

$arParams['PROP_FOR_SHOW_ON_MAIN'] = $arParams['BITRIX_NEWS_LIST']['PROP_FOR_SHOW_ON_MAIN'] ?: 'RZ_SHOW_MAIN';
$arParams['PROP_FOR_RELATED_ITEMS'] = $arParams['BITRIX_NEWS_LIST']['PROP_FOR_RELATED_ITEMS'] ?: 'RELATED_ITEMS';


global $arrNewsFilter;
$arrNewsFilter = array(
    'ACTIVE' => 'Y',
);
if ($bInCatalog) {
    $arrNewsFilter['PROPERTY_' . $arParams['PROP_FOR_RELATED_ITEMS']] = $arRelatedItems;
} else {
    $arrNewsFilter['!PROPERTY_' . $arParams['PROP_FOR_SHOW_ON_MAIN'] . '_VALUE'] = false;
}
if (CRZBitronic2Settings::isPro()) {
    $nameFilter = 'arrNewsFilter';
    CRZBitronic2CatalogUtils::setFilterByGeoStore($nameFilter);
}
?>
<? if (Tools::isEditModeOn() && !$bInCatalog): ?>
    <div id="backend-review-container" class="reviews-wrapper">
<? endif ?>
        <?
        $nameFilter = 'arrNewsFilter';
        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            $arParams['NEWS_TEMPLATE'],
            array_merge($arParams['BITRIX_NEWS_LIST'],
                array("FILTER_NAME" => 'arrNewsFilter',
                    'RESIZER_ITEM' => $arParams['RESIZER_ITEM'],
                )
            ), $component
        ); ?>
<? if (Tools::isEditModeOn() && !$bInCatalog): ?>
    </div>
<? endif ?>

