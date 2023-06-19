<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */
if (!empty($arResult['ITEMS']))
{
    if (empty($arParams['HEADER_TEXT'])) $arParams['HEADER_TEXT'] = GetMessage('BITRONIC2_VIEWED_HEADER_TEXT');
    // AJAX PATH
    $ajaxPath = SITE_DIR."ajax/catalog.php";
    $ajaxPathCompare = SITE_DIR."ajax/compare.php";
    $ajaxPathFavorite = SITE_DIR."ajax/favorites.php";
    $arResult['ADD_URL_TEMPLATE'] = $ajaxPath."?".$arParams["ACTION_VARIABLE"]."=ADD2BASKET&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#&ajax_basket=Y";
    $arResult['BUY_URL_TEMPLATE'] = $ajaxPath."?".$arParams["ACTION_VARIABLE"]."=BUY&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#&ajax_basket=Y";
    $arResult['COMPARE_URL_TEMPLATE'] = $ajaxPathCompare."?".$arParams["ACTION_VARIABLE"]."=ADD_TO_COMPARE_LIST&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#&ajax_basket=Y";
    $arResult['COMPARE_URL_TEMPLATE_DEL'] = $ajaxPathCompare."?".$arParams["ACTION_VARIABLE"]."=DELETE_FROM_COMPARE_LIST&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#&ajax_basket=Y";

    $arResult['FAVORITE_URL_TEMPLATE'] = $ajaxPathFavorite."?ACTION=ADD&ID=#ID#";
    $arResult['FAVORITE_URL_TEMPLATE_DEL'] = $ajaxPathFavorite."?ACTION=DELETE&ID=#ID#";

    $arSKUPropList = array();
    $arSKUPropIDs = array();
    $arSKUPropKeys = array();
    $boolSKU = false;
    $strBaseCurrency = '';
    $arResult['CURRENCY'] = CModule::IncludeModule("currency");
    $boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']) && $arResult['CURRENCY'];

    //
    $skuPropList = array(); // array("id_catalog" => array(...))
    $skuPropIds = array(); // array("id_catalog" => array(...))
    $skuPropKeys = array(); // array("id_catalog" => array(...))

    if (!$boolConvert)
        $strBaseCurrency = CCurrency::GetBaseCurrency();

    $arNewItemsList = array();
    $subKey = 0;

    foreach ($arResult['ITEMS'] as $key => $arItem)
    {
        $arItem['bOffers'] = CCatalogSku::IsExistOffers($arItem['ID'], $arParams['IBLOCK_ID']);
        $arItem = CRZBitronic2CatalogUtils::processItemCommon($arItem);

        //replace picture from resizer
        if(!empty($arItem['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_ALT']))
        {
            $imgAlt = $arItem['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_ALT'];
        }
        else
        {
            $imgAlt = $arItem['NAME'];
        }
        $arItem['PICTURE_PRINT']['ALT'] = $imgAlt;
        $arItem['PICTURE_PRINT']['SRC'] = CRZBitronic2CatalogUtils::getElementPictureById($arItem['ID'], $arParams['RESIZER_SECTION']);

        //continue with default result_modifier.php
        $arItem['CATALOG_QUANTITY'] = (
        0 < $arItem['CATALOG_QUANTITY'] && is_float($arItem['CATALOG_MEASURE_RATIO'])
            ? floatval($arItem['CATALOG_QUANTITY'])
            : intval($arItem['CATALOG_QUANTITY'])
        );
        $arItem['CATALOG'] = false;
        $arItem['LABEL'] = false;
        if (!isset($arItem['CATALOG_SUBSCRIPTION']) || 'Y' != $arItem['CATALOG_SUBSCRIPTION'])
            $arItem['CATALOG_SUBSCRIPTION'] = 'N';

        // Item Label Properties
        $itemIblockId = $arItem['IBLOCK_ID'];
        $propertyName = isset($arParams['LABEL_PROP'][$itemIblockId]) ? $arParams['LABEL_PROP'][$itemIblockId] : false;

        if ($propertyName && isset($arItem['PROPERTIES'][$propertyName]))
        {
            $property = $arItem['PROPERTIES'][$propertyName];

            if (!empty($property['VALUE']))
            {
                if ('N' == $property['MULTIPLE'] && 'L' == $property['PROPERTY_TYPE'] && 'C' == $property['LIST_TYPE'])
                {
                    $arItem['LABEL_VALUE'] = $property['NAME'];
                }
                else
                {
                    $arItem['LABEL_VALUE'] = (is_array($property['VALUE'])
                        ? implode(' / ', $property['VALUE'])
                        : $property['VALUE']
                    );
                }
                $arItem['LABEL'] = true;

                if (isset($arItem['DISPLAY_PROPERTIES'][$propertyName]))
                    unset($arItem['DISPLAY_PROPERTIES'][$propertyName]);
            }
            unset($property);
        }
        // !Item Label Properties

        $arItem['CATALOG'] = true;
        if (!isset($arItem['CATALOG_TYPE']))
            $arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
        if (
            (CCatalogProduct::TYPE_PRODUCT == $arItem['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $arItem['CATALOG_TYPE'])
            && !empty($arItem['OFFERS'])
        )
        {
            $arItem['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
        }
        switch ($arItem['CATALOG_TYPE'])
        {
            case CCatalogProduct::TYPE_SET:
                $arItem['OFFERS'] = array();
                $arItem['CATALOG_MEASURE_RATIO'] = 1;
                $arItem['CATALOG_QUANTITY'] = 0;
                $arItem['CHECK_QUANTITY'] = false;
                break;
            case CCatalogProduct::TYPE_SKU:
                break;
            case CCatalogProduct::TYPE_PRODUCT:
            default:
                $arItem['CHECK_QUANTITY'] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'N' == $arItem['CATALOG_CAN_BUY_ZERO']);
                break;
        }

        $arItem['ON_REQUEST'] = (empty($arItem['MIN_PRICE']) || $arItem['MIN_PRICE']['VALUE'] <= 0);
        CRZBitronic2CatalogUtils::setNotAvailbaleStatusForEmptyOffer($arItem);

        if ($arItem['ON_REQUEST']) {
            $arItem['CAN_BUY'] = false;
        }

        $arItem['FOR_ORDER'] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'Y' == $arItem['CATALOG_CAN_BUY_ZERO'] && 0 >= $arItem['CATALOG_QUANTITY']);

        // Offers
        if ($arItem['CATALOG'] && isset($arItem['OFFERS']) && !empty($arItem['OFFERS'])) {
            CRZBitronic2CatalogUtils::fillMinPriceFromOffers(
                $arItem,
                $boolConvert ? $arResult['CONVERT_CURRENCY']['CURRENCY_ID'] : $strBaseCurrency,
                $bForOrder = false
            );
        }

        if ($arItem['CATALOG'] && CCatalogProduct::TYPE_PRODUCT == $arItem['CATALOG_TYPE'])
        {
            CIBlockPriceTools::setRatioMinPrice($arItem, true);
        }

        if (!empty($arItem['DISPLAY_PROPERTIES']))
        {
            foreach ($arItem['DISPLAY_PROPERTIES'] as $propKey => $arDispProp)
            {
                if ('F' == $arDispProp['PROPERTY_TYPE'])
                    unset($arItem['DISPLAY_PROPERTIES'][$propKey]);
            }
        }

        if (CRZBitronic2CatalogUtils::checkAvPrFotoForElement($arItem, $arParams)){
            unset($arResult['ITEMS'][$key]);
            $subKey++;
            continue;
        }

        $arItem['LAST_ELEMENT'] = 'N';
        $arNewItemsList[$key] = $arItem;
    }

    if (!empty($arResult['ITEMS'])) {
        $arNewItemsList[$key - $subKey]['LAST_ELEMENT'] = 'Y';
        $arResult['ITEMS'] = $arNewItemsList;
    }
}