<?
$arJSParams = array(
    'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
    'SHOW_QUANTITY' => ($arParams['USE_PRODUCT_QUANTITY'] == 'Y'),
    'SHOW_ADD_BASKET_BTN' => false,
    'SHOW_BUY_BTN' => false,
    'SHOW_ABSENT' => false,
    'SHOW_SKU_PROPS' => false,
    'SECOND_PICT' => $arItem['SECOND_PICT'],
    'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
    'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
    'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
    'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
    'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE_SOLUTION'],
    'DISPLAY_FAVORITE' => $arParams['DISPLAY_FAVORITE'],
    'REQUEST_URI' => $arItem['DETAIL_PAGE_URL'],
    'SCRIPT_NAME' => BX_ROOT.'/urlrewrite.php',
    'DEFAULT_PICTURE' => array(
        'PICTURE' => $arItem['PRODUCT_PREVIEW'],
        'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
    ),
    'VISUAL' => array(
        'ID' => $arItemIDs['ID'],
        'PRICE_ID' => $arItemIDs['PRICE'],
        'BUY_ID' => $arItemIDs['BUY_LINK'],
        'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
        'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
    ),
    'BASKET' => array(
        'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
        'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
        'EMPTY_PROPS' => $bEmptyProductProperties,
        'BASKET_URL' => $arParams['BASKET_URL'],
        'ADD_URL_TEMPLATE' => $arResult['ADD_URL_TEMPLATE'],
        'BUY_URL_TEMPLATE' => $arResult['BUY_URL_TEMPLATE']
    ),
    'PRODUCT' => array(
        'ID' => $arItem['ID'],
        'IBLOCK_ID' => $arItem['IBLOCK_ID'],
        'NAME' => $productTitle,
        'PICT' => ('Y' == $arItem['SECOND_PICT'] ? $arItem['PREVIEW_PICTURE_SECOND'] : $arItem['PREVIEW_PICTURE']),
        'CAN_BUY' => $arItem["CAN_BUY"],
        'BASIS_PRICE' => $arItem['MIN_BASIS_PRICE']
    ),
    'OFFERS' => array(),
    'OFFER_SELECTED' => 0,
    'TREE_PROPS' => array(),
    'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
);
if ($arParams['DISPLAY_COMPARE_SOLUTION'])
{
    $arJSParams['COMPARE'] = array(
        'COMPARE_URL_TEMPLATE' => $arResult['COMPARE_URL_TEMPLATE'],
        'COMPARE_URL_TEMPLATE_DEL' => $arResult['COMPARE_URL_TEMPLATE_DEL'],
        'COMPARE_PATH' => $arParams['COMPARE_PATH']
    );
}
if ($arParams['DISPLAY_FAVORITE'])
{
    $arJSParams['FAVORITE'] = array(
        'FAVORITE_URL_TEMPLATE' => $arResult['FAVORITE_URL_TEMPLATE'],
        'FAVORITE_URL_TEMPLATE_DEL' => $arResult['FAVORITE_URL_TEMPLATE_DEL'],
        'FAVORITE_PATH' => $arParams['FAVORITE_PATH']
    );
}
?>

<script type="text/javascript">
    var <?=$strObName?> = new JCCatalogItem(<?=CUtil::PhpToJSObject($arJSParams, false, true)?>);
</script>
