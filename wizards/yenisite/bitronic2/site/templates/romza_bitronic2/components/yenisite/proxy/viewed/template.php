<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use \Yenisite\Core\Ajax;
use \Yenisite\Core\Tools;

$bAjax = Ajax::isAjax();
$bEditMode = Tools::isEditModeOn();
$this->setFrameMode(true);

?>
<div id="backend-viewd-container" class="top-line-item you-watched">
    <?if (($bAjax && $_REQUEST['get_block_viewed'] == 'Y') || $bEditMode):?>
        <?
        global $rz_b2_options;
        $arPrepareParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
        $APPLICATION->IncludeComponent(
            "bitrix:catalog.viewed.products",
            "header",
            Array(
                "DISPLAY_COMPARE_SOLUTION" => $arPrepareParams['DISPLAY_COMPARE_SOLUTION'],
                "SHOW_VOTING" => $rz_b2_options['block_show_stars'],
                "DISPLAY_FAVORITE" => $arPrepareParams["DISPLAY_FAVORITE"],
                "DISPLAY_ONECLICK" => $arPrepareParams["DISPLAY_ONECLICK"],
                "IBLOCK_TYPE" => $arPrepareParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arPrepareParams["IBLOCK_ID"],
                "SHOW_PRODUCTS_" . $arPrepareParams["IBLOCK_ID"] => "Y",///////////
                "CACHE_TYPE" => $arPrepareParams["CACHE_TYPE"],
                "CACHE_TIME" => $arPrepareParams["CACHE_TIME"],
                "BASKET_URL" => $arPrepareParams["BASKET_URL"],
                "ACTION_VARIABLE" => $arPrepareParams["ACTION_VARIABLE"],
                "PRODUCT_ID_VARIABLE" => $arPrepareParams["PRODUCT_ID_VARIABLE"],
                "PRODUCT_QUANTITY_VARIABLE" => $arPrepareParams["PRODUCT_QUANTITY_VARIABLE"],
                "ADD_PROPERTIES_TO_BASKET" => (isset($arPrepareParams["ADD_PROPERTIES_TO_BASKET"]) ? $arPrepareParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                "PRODUCT_PROPS_VARIABLE" => $arPrepareParams["PRODUCT_PROPS_VARIABLE"],
                "PARTIAL_PRODUCT_PROPERTIES" => (isset($arPrepareParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arPrepareParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                "PAGE_ELEMENT_COUNT" => $arPrepareParams["PAGE_ELEMENT_COUNT"],
                "SHOW_OLD_PRICE" => $arPrepareParams['SHOW_OLD_PRICE'],
                "SHOW_DISCOUNT_PERCENT" => $arPrepareParams['SHOW_DISCOUNT_PERCENT'],
                "PRICE_CODE" => $arPrepareParams["PRICE_CODE"],
                "SHOW_PRICE_COUNT" => $arPrepareParams["SHOW_PRICE_COUNT"],
                "PRODUCT_SUBSCRIPTION" => 'N',
                "PRICE_VAT_INCLUDE" => $arPrepareParams["PRICE_VAT_INCLUDE"],
                "USE_PRODUCT_QUANTITY" => $arPrepareParams['USE_PRODUCT_QUANTITY'],
                "SHOW_NAME" => "Y",
                "SHOW_IMAGE" => "Y",
                "MESS_BTN_BUY" => $arPrepareParams['MESS_BTN_BUY'],
                "MESS_BTN_DETAIL" => $arPrepareParams["MESS_BTN_DETAIL"],
                "MESS_NOT_AVAILABLE" => $arPrepareParams['MESS_NOT_AVAILABLE'],
                "MESS_BTN_SUBSCRIBE" => $arPrepareParams['MESS_BTN_SUBSCRIBE'],
                "HIDE_NOT_AVAILABLE" => "N",
                //"OFFER_TREE_PROPS_".$arRecomData['OFFER_IBLOCK_ID'] => $arPrepareParams["OFFER_TREE_PROPS"],
                "CART_PROPERTIES_{$arPrepareParams['IBLOCK_ID']}" => $arPrepareParams['PRODUCT_PROPERTIES'],
                "ADDITIONAL_PICT_PROP_" . $arPrepareParams["IBLOCK_ID"] => $arPrepareParams['ADD_PICT_PROP'],
                //"ADDITIONAL_PICT_PROP_".$arRecomData['OFFER_IBLOCK_ID'] => $arPrepareParams['OFFER_ADD_PICT_PROP'],
                //"PROPERTY_CODE_".$arRecomData['OFFER_IBLOCK_ID'] => array(),
                "CONVERT_CURRENCY" => $arPrepareParams["CONVERT_CURRENCY"],
                "CURRENCY_ID" => $arPrepareParams["CURRENCY_ID"],
                "RESIZER_SECTION" => $arParams['RESIZER_ITEM'],
                "HOVER-MODE" => $arPrepareParams["HOVER-MODE"],

                //PARAMS FOR HIDE ITEMS
                'HIDE_ITEMS_NOT_AVAILABLE' => $arParams['HIDE_ITEMS_NOT_AVAILABLE'],
                'HIDE_ITEMS_ZER_PRICE' => $arParams['HIDE_ITEMS_ZER_PRICE'],
                'HIDE_ITEMS_WITHOUT_IMG' => $arParams['HIDE_ITEMS_WITHOUT_IMG'],
                'ORDER_VIEWED_PRODUCTS' => $arPrepareParams["ORDER_VIEWED_PRODUCTS"],
            ),
            $component
        ); ?>
    <?endif?>
</div>