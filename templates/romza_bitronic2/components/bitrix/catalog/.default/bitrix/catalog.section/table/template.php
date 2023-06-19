<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

// @var $moduleId
// @var $moduleCode
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';

$this->SetViewTarget('catalog_paginator');
echo $arResult["NAV_STRING"];
$this->EndViewTarget();

if(empty($arResult['ITEMS']))
    return;

$arJsCache = CRZBitronic2CatalogUtils::getJSCache($component);

$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

$showBannerOn = ceil(count($arResult['ITEMS']) / 2) ;
$count = 0;

$bStores = $arParams["USE_STORE"] == "Y" && Bitrix\Main\ModuleManager::isModuleInstalled("catalog");

$commonBuyId = 'add_basket_table';
?>
    <div class="table-header clearfix">
        <div class="actions">
            <button type="button" class="btn-main disabled" id="<?=$commonBuyId?>">
                <span class="text"><?=GetMessage('BITRONIC2_TABLE_ADD_TO_BASKET')?> (<span class="number">0</span>)</span>
            </button>
        </div>
        <div class="quan"><?=GetMessage('BITRONIC2_TABLE_QUANTITY')?></div>
        <div class="units"><?=GetMessage('BITRONIC2_TABLE_MEASURE')?></div>
        <div class="avail-n-price"><?=GetMessage('BITRONIC2_TABLE_AVAIL_AND_PRICE')?></div>
        <?if($arParams['SHOW_ARTICLE'] == 'Y' && $arParams['ARTICUL_PROP'] && $arResult['ITEMS'][0]['PROPERTIES'][$arParams['ARTICUL_PROP']]['NAME']):?>
            <!-- use class .no-art on this div if there are no arts at all -->
            <div class="art-wrap<?=($arResult['NO_ARTICUL']?' no-art':'')?>"><?=$arResult['ITEMS'][0]['PROPERTIES'][$arParams['ARTICUL_PROP']]['NAME']?></div>
        <?endif?>
        <div class="name"><?=GetMessage('BITRONIC2_TABLE_NAME')?></div>
    </div>

    <table <?//if($USER->IsAdmin()):?>class="has-admin-info"<?//endif?>>
        <?foreach($arResult['ITEMS'] as $arItem):
            $this->AddEditAction($templateName.'-'.$arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
            $this->AddDeleteAction($templateName.'-'.$arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
            $strMainID = $this->GetEditAreaId($templateName.'-'.$arItem['ID']);
            $arItemIDs = array(
                'ID' => $strMainID,
                'PICT' => $strMainID.'_pict',
                'SECOND_PICT' => $strMainID.'_secondpict',
                'STICKER_ID' => $strMainID.'_sticker',
                'SECOND_STICKER_ID' => $strMainID.'_secondsticker',
                'QUANTITY_CONTAINER' => $strMainID.'_quantity_container',
                'QUANTITY' => $strMainID.'_quantity',
                'QUANTITY_DOWN' => $strMainID.'_quant_down',
                'QUANTITY_UP' => $strMainID.'_quant_up',
                'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
                'BUY_LINK' => $strMainID.'_buy_link',
                'COMMON_BUY_ID' => $commonBuyId,
                'BASKET_ACTIONS' => $strMainID.'_basket_actions',
                'AVAILABLE_INFO' => $strMainID.'_avail_info',
                'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
                'SUBSCRIBE_LINK' => $strMainID.'_subscribe',
                'COMPARE_LINK' => $strMainID.'_compare_link',
                'FAVORITE_LINK' => $strMainID.'_favorite_link',

                'OLD_PRICE' => $strMainID.'_old_price',
                'PRICE' => $strMainID.'_price',
                'DSC_PERC' => $strMainID.'_dsc_perc',
                'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',
                'PROP_DIV' => $strMainID.'_sku_tree',
                'PROP' => $strMainID.'_prop_',
                'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
                'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
            );
            $strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
            $productTitle = (
            !empty($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
                ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
                : $arItem['NAME']
            );
            $imgTitle = (
            !empty($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'])
                ? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
                : $arItem['NAME']
            );
            $bShowStore = $bStores && !$arItem['bOffers'];
            $bExpandedStore = $arParams['PRODUCT_AVAILABILITY_VIEW'];
            $availableOnRequest = $arItem['ON_REQUEST'];
            $availableClass = (
            !$arItem['CAN_BUY'] && !$availableOnRequest
                ? 'out-of-stock'
                : (
            $arItem['FOR_ORDER'] || $availableOnRequest
                ? 'available-for-order'
                : 'in-stock'
            )
            );
            if ($availableOnRequest) {
                $arItem['CAN_BUY'] = false;
            }
            $bArticul = $arParams['ARTICUL_PROP'] && !empty($arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']);
            if ($arParams['SHOW_ARTICLE'] == 'N') {
                $bArticul = false;
            }
            $bBuyProps = ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !empty($arItem['PRODUCT_PROPERTIES']));
            ?>
            <?if ($count >= $showBannerOn && $arParams['BANNER_PLACE'] && CRZBitronic2Settings::getEdition() != 'LITE'):?>
            <?$showBannerOn = 99999999999999;?>
            <tr class="catalog-item catalog-table-item wow fadeInUp banner-item">
                <td colspan="6">
                    #BANNER_PLACE#
                </td>
            </tr>
            <tr><td colspan="6"></td></tr>
        <?endif?>
            <tr class="catalog-item catalog-table-item wow fadeIn<?= ($arItem['VIP'] ? ' big-item' : '') ?>" id="<?=$arItemIDs['ID']?>">
                <td class="art-wrap <?=(!$bArticul) ? ' no-art' : ''?>">
                    <div class="art">
                        <div class="photo-wrap">
                            <a title="<?= $imgTitle ?>" href="<?=$arItem['DETAIL_PAGE_URL']?>" class="photo">
                                <img class="lazy" data-original="<?=$arItem['PICTURE_PRINT']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['PICTURE_PRINT']['ALT']?>" title="<?= $imgTitle ?>">
                            </a>
                        </div>
                        <?if($bArticul):?>
                            <strong><?=is_array($arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) ? implode(' / ', $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) : $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']?></strong>
                        <?endif?>
                    </div>
                </td>
                <td class="name">
                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link"><span class="text"><?=$productTitle?></span></a>
                </td>
                <td class="avail-n-price <?=$availableClass?>">
                    <div class="xs-switch">
                        <i class="flaticon-arrow128 when-closed"></i>
                        <i class="flaticon-key22 when-opened"></i>
                    </div>
                    <?
                    $availableForOrderText = &$arItem['PROPERTIES']['RZ_FOR_ORDER_TEXT']['VALUE'];
                    $availableItemID = &$arItem['ID'];
                    $availableMeasure = &$arItem['CATALOG_MEASURE_NAME'];
                    $availableQuantity = &$arItem['CATALOG_QUANTITY'];
                    $availableStoresPostfix = 'table';
                    $availableSubscribe = $arItem['bOffers'] ? 'N' : $arItem['CATALOG_SUBSCRIBE'];
                    include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/availability_dots.php';
                    ?>

                    <div class="price" id="<?=$arItemIDs['PRICE']?>">
                        <? $frame = $this->createFrame($arItemIDs['PRICE'], false)->begin(CRZBitronic2Composite::insertCompositLoader()) ?>
                        <? if(empty($availableOnRequest)): ?>
                            <?=($arItem['bOffers'] && $arItem['bOffersNotEqual']) ? GetMessage('BITRONIC2_TABLE_FROM') : ''?>
                            <?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);?>
                            <?
                            if (is_array($arItem['PRICE_MATRIX'])):
                                foreach ($arItem['PRICE_MATRIX']['COLS'] as $colKey => $arCol):
                                    foreach ($arItem['PRICE_MATRIX']['ROWS'] as $rowKey => $arRow):
                                        if ($arRow['QUANTITY_FROM'] <= $arItem['CATALOG_MEASURE_RATIO']
                                            && ($arRow['QUANTITY_TO'] >= $arItem['CATALOG_MEASURE_RATIO'] || $arRow['QUANTITY_TO'] == 0)) continue;
                                        $arColPrice = &$arItem['PRICE_MATRIX']['MATRIX'][$colKey][$rowKey];
                                        ?>

                                        <div class="quan-price"><?=GetMessage('BITRONIC2_TABLE_FROM')?> <?=$arRow['QUANTITY_FROM']?> <?=$arItem['CATALOG_MEASURE_NAME']?> - <?=CRZBitronic2CatalogUtils::getElementPriceFormat(
                                                $arColPrice['CURRENCY'],
                                                $arColPrice['DISCOUNT_PRICE'],
                                                $arColPrice['DISCOUNT_PRICE']
                                            )?></div>
                                        <?
                                    endforeach;
                                endforeach;
                            endif ?>
                        <? endif ?>
                        <? $frame->end() ?>
                    </div>
                </td>
                <td class="units" data-text="<?=GetMessage('BITRONIC2_TABLE_MEASURE')?>">
			<span data-tooltip data-placement="right" title="<?=$arItem['CATALOG_MEASURE_NAME']?>">
				<?=$arItem['CATALOG_MEASURE_RATIO']?>
			</span>
                </td>
                <td class="quan">
                    <?if ('Y' == $arParams['USE_PRODUCT_QUANTITY'] && !$arItem['bOffers'] && $arItem['CAN_BUY'] && !$bBuyProps):?>
                        <div class="quantity-counter"
                             data-tooltip
                             data-placement="right"
                             title="<?=$arItem['CATALOG_MEASURE_NAME']?>">
                            <!-- parent must have class .quantity-counter! -->
                            <button type="button" class="btn-silver quantity-change decrease disabled" id="<?=$arItemIDs['QUANTITY_DOWN']?>"><span class="minus"></span></button>
                            <input type="text" name="quantity" class="quantity-input textinput" value="0" id="<?=$arItemIDs['QUANTITY']?>" data-item-id="<?=$arItem['ID']?>">
                            <button type="button" class="btn-silver quantity-change increase" id="<?=$arItemIDs['QUANTITY_UP']?>"><span class="plus"></span></button>
                        </div>
                    <?elseif($availableOnRequest):?>
                        <a href="#" id="<?=$arItemIDs['REQUEST_LINK']?>" data-toggle="modal" data-target="#modal_contact_product"
                           data-product-id="<?=$arItem['ID']?>" data-measure-name="<?=$arItem['CATALOG_MEASURE_NAME']?>" class="link"><span class="text"><?=COption::GetOptionString($moduleId, 'button_text_request')?></span></a>
                    <?elseif($arItem['bOffers'] || ($bBuyProps && $arItem['CAN_BUY'])):?>
                        <?if (CRZBitronic2Settings::getEdition() != 'LITE'):?>
                            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link"><span class="text"><?=COption::GetOptionString($moduleId, 'button_text_offers')?></span></a>
                        <?endif?>
                    <?endif?>
                </td>
                <td class="actions">
                    <?if ($arParams['DISPLAY_FAVORITE'] && !$arItem['bOffers']):?>
                        <button
                                type="button"
                                class="btn-action favorite"
                                data-favorite-id="<?=$arItem['ID']?>"
                                data-tooltip title="<?=GetMessage('BITRONIC2_TABLE_ADD_TO_FAVORITE')?>"
                                id="<?=$arItemIDs['FAVORITE_LINK']?>">
                            <i class="flaticon-heart3"></i>
                        </button>
                    <?endif?>
                    <?if ($arParams['DISPLAY_COMPARE_SOLUTION']):?>
                        <button
                                type="button"
                                class="btn-action compare"
                                data-compare-id="<?=$arItem['ID']?>"
                                data-tooltip title="<?=GetMessage('BITRONIC2_TABLE_ADD_TO_COMPARE')?>"
                                id="<?=$arItemIDs['COMPARE_LINK']?>">
                            <i class="flaticon-balance3"></i>
                        </button>
                    <?endif?>
                </td>
            </tr><!-- /.catalog-item.table-item -->
            <tr>
                <td colspan="6">
                    <? // ADMIN INFO
                    include 'admin_info.php';
                    ?>
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
                        'REQUEST_URI' => $_SERVER["REQUEST_URI"],
                        'SCRIPT_NAME' => $_SERVER["SCRIPT_NAME"],
                        'DEFAULT_PICTURE' => array(
                            'PICTURE' => $arItem['PRODUCT_PREVIEW'],
                            'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
                        ),
                        'VISUAL' => array(
                            'ID' => $arItemIDs['ID'],
                            'PICT_ID' => $arItemIDs['PICT'],
                            'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
                            'QUANTITY_ID' => $arItemIDs['QUANTITY'],
                            'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
                            'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
                            'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
                            'PRICE_ID' => $arItemIDs['PRICE'],
                            'TREE_ID' => $arItemIDs['PROP_DIV'],
                            'TREE_ITEM_ID' => $arItemIDs['PROP'],
                            'BUY_ID' => $arItemIDs['BUY_LINK'],
                            'COMMON_BUY_ID' => $arItemIDs['COMMON_BUY_ID'],
                            'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
                            'DSC_PERC' => $arItemIDs['DSC_PERC'],
                            'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
                            'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
                            'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
                            'NOT_AVAILABLE_MESS' => $arItemIDs['NOT_AVAILABLE_MESS'],
                            'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK'],
                            'FAVORITE_ID' => $arItemIDs['FAVORITE_LINK']
                        ),
                        'BASKET' => array(
                            'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                            'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
                            'SKU_PROPS' => $arItem['OFFERS_PROP_CODES'],
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
                            'SUBSCRIPTION' => ('Y' == $arItem['CATALOG_SUBSCRIPTION']),
                            'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
                            'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
                            'MIN_QUANTITY' => 0,
                            'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
                            'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
                            'SUBSCRIBE_URL' => $arItem['~SUBSCRIBE_URL'],
                            'BASIS_PRICE' => $arItem['MIN_BASIS_PRICE'],
                            'PRICE_MATRIX' => $arItem['PRICE_MATRIX']
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

                    $jsString = ($jsString ?: '') . 'var ' . $strObName . ' = new JCCatalogItem('. CUtil::PhpToJSObject($arJSParams, false, true) .');';
                    ?>
                </td>
            </tr>
            <?
            $count++;
        endforeach;?>
    </table>
<?
$frame = $this->createFrame()->begin('');
if ($arJsCache['file']):
    $bytes = fwrite($arJsCache['file'], $jsString);
    if ($bytes === false || $bytes != mb_strlen($jsString, 'windows-1251')) {
        fclose($arJsCache['file']);
        $arJsCache['file'] = false;
    }
endif;
if(!$arJsCache['file']):?>
    <script type="text/javascript">
        <?=$jsString?>
    </script>
<?endif;
$frame->end();

if ($arJsCache['file']) {
    $templateData['jsFile'] = $arJsCache['path'].'/'.$arJsCache['idJS'];
    fclose($arJsCache['file']);
}