<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\ModuleManager;

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

//no whitespace in this file!!!!!!
$this->setFrameMode(true);

// @var $moduleId
// @var $moduleCode
include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/include/debug_info_dynamic.php';

$this->SetViewTarget('catalog_paginator');
echo $arResult["NAV_STRING"];
$this->EndViewTarget();

if (empty($arResult['ITEMS'])) {
    if ($arParams['SEARCH_PAGE'] == 'Y') {
        $this->__component->__parent->arResult['EMPTY_CATALOG'] = true;
        ShowNote(GetMessage("BITRONIC2_SEARCH_NOTHING_TO_FOUND"));
    }
    return;
}
if ($arParams['SEARCH_PAGE'] == 'Y' && $arParams['SEARCH_PAGE_CLOSE_TAG'] != 'Y') {
    echo '<div class="' . $arParams['SEARCH_PAGE_CLASS'] . '" id="catalog_section" data-hover-effect="' . $arParams['HOVER-MODE'] . '"  data-quick-view-enabled="false">';
}

$arJsCache = CRZBitronic2CatalogUtils::getJSCache($component);

$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

$bStores = $arParams["USE_STORE"] == "Y" && Bitrix\Main\ModuleManager::isModuleInstalled("catalog");
$bHoverMode = $arParams['HOVER-MODE'] == 'detailed-expand';
$showBannerOn = ceil(count($arResult['ITEMS']) / 2);
$count = 0;
$countItems = count($arResult['ITEMS']); ?>
<?
foreach ($arResult['ITEMS'] as $key => $arItem):
    $this->AddEditAction($templateName . '-' . $arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
    $this->AddDeleteAction($templateName . '-' . $arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
    $strMainID = $this->GetEditAreaId($templateName . '-' . $arItem['ID']);
    $arItemIDs = array(
        'ID' => $strMainID,
        'PICT' => $strMainID . '_pict',
        'SECOND_PICT' => $strMainID . '_secondpict',
        'STICKER_ID' => $strMainID . '_sticker',
        'SECOND_STICKER_ID' => $strMainID . '_secondsticker',
        'QUANTITY_CONTAINER' => $strMainID . '_quantity_container',
        'QUANTITY' => $strMainID . '_quantity',
        'QUANTITY_DOWN' => $strMainID . '_quant_down',
        'QUANTITY_UP' => $strMainID . '_quant_up',
        'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
        'BUY_LINK' => $strMainID . '_buy_link',
        'BUY_ONECLICK' => $strMainID . '_buy_oneclick',
        'BASKET_ACTIONS' => $strMainID . '_basket_actions',
        'NOT_AVAILABLE_MESS' => $strMainID . '_not_avail',
        'SUBSCRIBE_LINK' => $strMainID . '_subscribe',
        'COMPARE_LINK' => $strMainID . '_compare_link',
        'FAVORITE_LINK' => $strMainID . '_favorite_link',
        'REQUEST_LINK' => $strMainID . '_request_link',

        'PRICE_CONTAINER' => $strMainID . '_price_container',
        'OLD_PRICE' => $strMainID . '_old_price',
        'PRICE' => $strMainID . '_price',
        'PRICE_ADDITIONAL' => $strMainID . '_price_additional',
        'DSC_PERC' => $strMainID . '_dsc_perc',
        'SECOND_DSC_PERC' => $strMainID . '_second_dsc_perc',
        'PROP_DIV' => $strMainID . '_sku_tree',
        'PROP' => $strMainID . '_prop_',
        'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
        'BASKET_PROP_DIV' => $strMainID . '_basket_prop',
        'AVAILABILITY' => $strMainID . '_availability',
        'AVAILABILITY_MOBILE' => $strMainID . '_availability_mobile',
        'AVAILABLE_INFO_FULL' => $strMainID . '_avail_info_full',
        'ARTICUL' => $strMainID . '_articul',
    );
    $strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

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
    $bShowStore = $bStores && !$arItem['bSkuSimple'];
    $bExpandedStore = $arParams['PRODUCT_AVAILABILITY_VIEW'];
    $bShowOneClick = $arParams['DISPLAY_ONECLICK'] && (!$arItem['bOffers'] || $arItem['bSkuExt']);;
    $bSkuExt = $arItem['bSkuExt'];

    $arItem['ARTICUL'] = (
    $arItem['bOffers'] && $bSkuExt && !empty($arItem['JS_OFFERS'][$arItem['OFFERS_SELECTED']]['ARTICUL'])
        ? $arItem['JS_OFFERS'][$arItem['OFFERS_SELECTED']]['ARTICUL']
        : (
    is_array($arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])
        ? implode(' / ', $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])
        : $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']
    )
    );
    $availableOnRequest = $arItem['ON_REQUEST'];
    $arItem['CAN_BUY'] = (
    $arItem['bOffers'] && $bSkuExt
        ? $arItem['JS_OFFERS'][$arItem['OFFERS_SELECTED']]['CAN_BUY']
        : $arItem['CAN_BUY'] && !$availableOnRequest
    );

    
    $availableClass = (
    !$arItem['CAN_BUY'] && !$availableOnRequest
        ? 'out-of-stock'
        : (
    $arItem['FOR_ORDER'] || $availableOnRequest
        ? 'available-for-order'
        : 'in-stock'
    )
    );
    if ($availableOnRequest) $arItem['CAN_BUY'] = false;

    $bEmptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
    $bBuyProps = ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$bEmptyProductProperties);

    $bCatchbuy = ($arParams['SHOW_CATCHBUY'] && $arItem['CATCHBUY']);
    if ($arItem['SHOW_SLIDER']) {
        $arItem['SHOW_SLIDER'] = $arParams['SHOW_GALLERY_THUMB'] == 'Y';
    }
    ?>
    <? if ($count >= $showBannerOn && $arParams['BANNER_PLACE']): ?>
    <? $showBannerOn = 99999999999999; ?>
    #BANNER_PLACE#
<? endif ?>
    <div itemscope itemtype="http://schema.org/Product"
         class="catalog-item-wrap active<?= ($arItem['VIP'] ? ' big-item' : '') ?> <?= $countItems - 1 == $key ? ' last-item' : '' ?>"
         id="<?= $arItemIDs['ID'] ?>">
        <div class="catalog-item blocks-item wow fadeIn">
            <div class="photo-wrap <?= !$arItem['SHOW_SLIDER'] ? ' no-thumbs' : '' ?>">
                <div itemscope itemtype="http://schema.org/ImageObject" class="photo">
                    <a title="<?= $imgTitle ?>" href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                        <img itemprop="contentUrl" class="lazy" data-original="<?= $arItem['PICTURE_PRINT']['SRC'] ?>"
                             src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                             alt="<?= $arItem['PICTURE_PRINT']['ALT'] ?>" title="<?= $imgTitle ?>"
                             id="<?= $arItemIDs['PICT'] ?>">
                    </a><?
                    if (!$bSkuExt):
                        if ($bCatchbuy):?>
                            <div class="countdown">
                            <div class="timer-wrap"><?

                                if (!empty($arItem['CATCHBUY']['ACTIVE_TO'])): ?>

                                <div class="timer"
                                     data-until="<?= str_replace('XXX', 'T', ConvertDateTime($arItem['CATCHBUY']['ACTIVE_TO'], 'YYYY-MM-DDXXXhh:mm:ss')) ?>"></div><?

                                endif ?>

                                <div class="already-sold">
                                    <div class="value countdown-amount"><?= (int)$arItem['CATCHBUY']['PERCENT'] ?>%
                                    </div>
                                    <div class="countdown-period"><?= GetMessage('BITRONIC2_SOLD') ?></div>
                                </div>
                                <div class="already-sold__track">
                                    <div class="bar"
                                         style="width: <?= floatval($arItem['CATCHBUY']['PERCENT']) ?>%"></div>
                                </div>
                            </div>
                            </div><?
                        endif;
                    elseif ($arParams['SHOW_CATCHBUY']):
                        foreach ($arItem['OFFERS'] as $arOffer):
                            if ($arOffer['CATCHBUY']):?>

                            <div class="countdown" id="<?= $arItemIDs['ID'] ?>_countdown_<?= $arOffer['ID'] ?>"
                                 style="display:none">
                                <div class="timer-wrap">
                                    <div class="timer"
                                         data-until="<?= str_replace('XXX', 'T', ConvertDateTime($arOffer['CATCHBUY']['ACTIVE_TO'], 'YYYY-MM-DDXXXhh:mm:ss')) ?>"></div>
                                    <div class="already-sold">
                                        <div class="value countdown-amount"><?= intVal($arOffer['CATCHBUY']['PERCENT']) ?>
                                            %
                                        </div>
                                        <div class="countdown-period"><?= GetMessage('BITRONIC2_SOLD') ?></div>
                                    </div>
                                    <div class="already-sold__track">
                                        <div class="bar"
                                             style="width: <?= floatval($arOffer['CATCHBUY']['PERCENT']) ?>%"></div>
                                    </div>
                                </div>
                                </div><?
                            endif;
                        endforeach;
                    endif ?>
                    <?$frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader());?>
                        <?= $arItem['yenisite:stickers'] ?>
                    <?$frame->end()?>
                    <div class="quick-view-switch" data-toggle="modal" data-target="#modal_quick-view">
						<span class="quick-view-fake-btn">
							<span class="text"><?= GetMessage('BITRONIC2_BLOCKS_QUICK_VIEW') ?></span>
						</span>
                        <i class="flaticon-zoom62"></i>
                    </div>
                </div><!-- .photo -->
                <? if (!$bSkuExt): ?>
                    <? if ($arItem['SHOW_SLIDER']): ?>
                        <div class="photo-thumbs">
                            <div class="slidee">
                                <? foreach ($arItem['MORE_PHOTO'] as $arPhoto):
                                    ?>
                                    <div itemscope itemtype="http://schema.org/ImageObject" class="photo-thumb">
                                    <img itemprop="contentUrl"
                                         class="lazy"
                                         data-original="<?= CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams['RESIZER_SECTION_ICON']) ?>"
                                         src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                         alt="<?= strlen($arPhoto['DESCRIPTION']) > 0 ? $arPhoto['DESCRIPTION'] : $arItem['PICTURE_PRINT']['ALT'] ?>"
                                         title="<?= strlen($arPhoto['DESCRIPTION']) > 0 ? $arPhoto['DESCRIPTION'] : $arItem['PICTURE_PRINT']['ALT'] ?>"
                                         data-medium-image="<?= CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams['RESIZER_SECTION']) ?>"
                                    >
                                    </div><?
                                endforeach; ?>
                            </div>
                            <? if ($arItem['MORE_PHOTO_COUNT'] > 4): ?>
                                <div class="carousel-dots"></div>
                            <? endif ?>
                        </div>
                    <? endif ?>
                <? else: ?>
                    <? foreach ($arItem['OFFERS'] as $arOffer): ?>
                        <? if ($arOffer['SHOW_SLIDER']): ?>
                            <div class="photo-thumbs" id="<? echo $arItemIDs['SLIDER_CONT_OF_ID'] . $arOffer['ID']; ?>"
                                 style="display:none">
                                <div class="slidee">
                                    <? foreach ($arOffer['MORE_PHOTO'] as $arPhoto):
                                        ?>
                                        <div itemscope itemtype="http://schema.org/ImageObject" class="photo-thumb">
                                        <img itemprop="contentUrl"
                                             class="lazy"
                                             data-original="<?= CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams['RESIZER_SECTION_ICON']) ?>"
                                             src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                             data-src="<?= CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams['RESIZER_SECTION_ICON']) ?>"
                                             alt="<?= strlen($arPhoto['DESCRIPTION']) > 0 ? $arPhoto['DESCRIPTION'] : $arOffer['PICTURE_PRINT']['ALT'] ?>"
                                             title="<?= strlen($arPhoto['DESCRIPTION']) > 0 ? $arPhoto['DESCRIPTION'] : $arOffer['PICTURE_PRINT']['ALT'] ?>"
                                             data-medium-image="<?= CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams['RESIZER_SECTION']) ?>"
                                        >
                                        </div><?
                                    endforeach; ?>
                                </div>
                                <? if ($arOffer['MORE_PHOTO_COUNT'] > 4): ?>
                                    <div class="carousel-dots"></div>
                                <? endif ?>
                            </div>
                        <? endif ?>
                    <? endforeach ?>
                <? endif ?>
            </div><!-- /.photo-wrap -->
            <div class="main-data">
                <div class="name">
                    <a itemprop="url" href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="link"><span itemprop="name"
                                                                                                  class="text"><?= $productTitle ?></span></a>
                </div>
                <div class="art-rate clearfix">
                    <? if ($arParams['SHOW_ARTICLE'] == 'Y'): ?>
                        <? if (!empty($arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])): ?>
                            <span id="<?= $arItemIDs['ARTICUL'] ?>"
                                  class="art"><?= $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['NAME'] ?>
                                : <strong><?= is_array($arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) ? implode(' / ', $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) : $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'] ?></strong></span>
                        <? endif ?>
                    <? endif ?>
                    <? if ($arParams['SHOW_STARS'] == 'Y'): ?>
                        <? $APPLICATION->IncludeComponent("bitrix:iblock.vote", "stars", array(
                            "IBLOCK_TYPE" => $arItem['IBLOCK_TYPE_ID'],
                            "IBLOCK_ID" => $arItem['IBLOCK_ID'],
                            "ELEMENT_ID" => $arItem['ID'],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "MAX_VOTE" => "5",
                            "VOTE_NAMES" => array("1", "2", "3", "4", "5"),
                            "SET_STATUS_404" => "N",
                        ),
                            $component, array("HIDE_ICONS" => "Y")
                        );
                        ?><?
                    endif
                    ?>
                    <?
                    $availableID = &$arItemIDs['AVAILABILITY'];
                    $availableFrame = true;
                    $availableForOrderText = &$arItem['PROPERTIES']['RZ_FOR_ORDER_TEXT']['VALUE'];
                    $availableItemID = &$arItem['ID'];
                    $availableMeasure = &$arItem['CATALOG_MEASURE_NAME'];
                    $availableQuantity = &$arItem['CATALOG_QUANTITY'];
                    $availableStoresPostfix = 'blocks';
                    $availableSubscribe = $arItem['bOffers'] ? 'N' : $arItem['CATALOG_SUBSCRIBE'];
                    $bShowEveryStatus = ($arItem['bOffers'] && $bSkuExt);
                    include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/availability_info.php';
                    ?>
                    <? if ($bShowStore && $arParams['HIDE_STORE_LIST'] == 'N'): ?>
                        <? $APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "store", array(
                            "PER_PAGE" => "10",
                            "USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
                            "SCHEDULE" => $arParams["USE_STORE_SCHEDULE"],
                            "USE_MIN_AMOUNT" => 'N',
                            "MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
                            "ELEMENT_ID" => $arItem['ID'],
                            "STORE_PATH" => $arParams["STORE_PATH"],
                            "MAIN_TITLE" => $arParams["MAIN_TITLE"],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                            'STORE_CODE' => $arParams["STORE_CODE"],
                            'FIELDS' => array('DESCRIPTION'),
                            'STORE_DISPLAY_TYPE' => $arParams['STORE_DISPLAY_TYPE'],
                            'STORES' => $arParams['STORES']
                        ),
                            $component,
                            array("HIDE_ICONS" => "Y")
                        ); ?>
                    <? endif ?>
                </div>
                <div itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer" class="prices"
                     id="<?= $arItemIDs['PRICE_CONTAINER'] ?>">
                    <? $frame = $this->createFrame($arItemIDs['PRICE_CONTAINER'], false)->begin(CRZBitronic2Composite::insertCompositLoader()) ?>
                    <div class="<?= (empty($availableOnRequest) ? '' : ' invisible') ?>">
						<span class="price-old" id="<?= $arItemIDs['OLD_PRICE'] ?>">
							<? if ($arItem['MIN_PRICE']['DISCOUNT_DIFF'] > 0 && $arParams['SHOW_OLD_PRICE'] == 'Y'): ?>
                                <?= CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['VALUE'], $arItem['MIN_PRICE']['PRINT_VALUE']); ?>
                                <span class="hidden" itemprop="highPrice"><?= $arItem['MIN_PRICE']['VALUE'] ?></span>
                            <? endif ?>
						</span>
                        <span class="price" id="<?= $arItemIDs['PRICE'] ?>">
								<?= ($arItem['bOffers'] && $arItem['bOffersNotEqual']) ? GetMessage('BITRONIC2_BLOCKS_FROM') : '' ?>
                            <?= CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']); ?>
                            <span class="hidden"
                                  itemprop="lowPrice"><?= $arItem['MIN_PRICE']['DISCOUNT_VALUE'] ?: 0 ?></span>
                            <span class="hidden" itemprop="priceCurrency"><?= $arItem['MIN_PRICE']['CURRENCY'] ?></span>
						</span>
                    </div>
                    <div id="<?= $arItemIDs['PRICE_ADDITIONAL'] ?>"
                         class="additional-price-container <?= (empty($availableOnRequest) && CRZBitronic2Settings::isPro() ? '' : ' invisible') ?>"><?
                        if (count($arItem['PRICES']) > 1 && CRZBitronic2Settings::isPro()):?>

                            <div class="wrapper baron-wrapper additional-prices-wrap">
                            <div class="scroller scroller_v">
                                <? foreach ($arItem['PRICES'] as $priceCode => $arPrice): ?>
                                    <? if ($arResult['PRICES'][$priceCode]['CAN_VIEW'] == false) continue; ?>
                                    <? if ($arPrice['DISCOUNT_VALUE'] <= 0) continue; ?>

                                    <div class="additional-price-type <?= $arPrice['PRICE_ID'] == $arItem['MIN_PRICE']['PRICE_ID'] ? 'current' : '' ?>">
                                        <span class="price-desc"><?= $arResult['PRICES'][$priceCode]['TITLE'] ?>:</span>
                                        <span class="price"><? if (!empty($arItem['OFFERS'])) echo GetMessage('RZ_OT') ?><?
                                            echo CRZBitronic2CatalogUtils::getElementPriceFormat(
                                                $arPrice['CURRENCY'],
                                                $arPrice['DISCOUNT_VALUE'],
                                                $arPrice['PRINT_DISCOUNT_VALUE'],
                                                array("itemprop" => "highPrice")
                                            );
                                            ?></span>
                                    </div>
                                <? endforeach ?>

                                <div class="scroller__track scroller__track_v">
                                    <div class="scroller__bar scroller__bar_v"></div>
                                </div>
                            </div>
                            </div><?

                        endif ?>

                    </div>
                    <? $frame->end() ?>
                </div>
                <?
                $availableID = &$arItemIDs['AVAILABILITY_MOBILE'];
                $availableFrame = true;
                $availableForOrderText = &$arItem['PROPERTIES']['RZ_FOR_ORDER_TEXT']['VALUE'];
                $availableItemID = &$arItem['ID'];
                $availableMeasure = &$arItem['CATALOG_MEASURE_NAME'];
                $availableQuantity = &$arItem['CATALOG_QUANTITY'];
                $availableStoresPostfix = 'blocks_mobile';
                $bShowEveryStatus = ($arItem['bOffers'] && $bSkuExt);
                $availableSubscribe = $arItem['bOffers'] ? 'N' : $arItem['CATALOG_SUBSCRIBE'];
                include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/availability_info.php';
                ?>
                <?
                // ***************************************
                // *********** BUY WITH PROPS ************
                // ***************************************
                if ($bBuyProps):
                    ?>
                    <div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>">
                        <?
                        if (!empty($arItem['PRODUCT_PROPERTIES_FILL'])) {
                            foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo) {
                                ?>
                                <input type="hidden"
                                       name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
                                       value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
                                <?
                                if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
                                    unset($arItem['PRODUCT_PROPERTIES'][$propID]);
                            }
                        }
                        $emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
                        ?>
                    </div>
                <? endif ?>
                <?
                $bBuyButton = false;
                $bCompareButton = false;
                $bFavoriteButton = false;
                $bQuantityInput = false;

                if ($bHoverMode) {
                    ob_start();
                }
                ?>
                <div class="buy-wrap">
                    <? if ('Y' == $arParams['USE_PRODUCT_QUANTITY'] && !$arItem['bOffers'] && $arItem['CAN_BUY'] && (!$bBuyProps || $emptyProductProperties) && $arParams['SHOW_BUY_BTN']): ?>
                        <form action="#" method="post" class="quantity-counter"
                              data-tooltip
                              data-placement="bottom"
                              title="<?= $arItem['CATALOG_MEASURE_NAME'] ?>">
                            <!-- parent must have class .quantity-counter! -->
                            <button type="button" class="btn-silver quantity-change decrease disabled"
                                    id="<?= $arItemIDs['QUANTITY_DOWN'] ?>"><span class="minus"></span></button>
                            <input type="text" class="quantity-input textinput"
                                   value="<?= $arItem['CATALOG_MEASURE_RATIO'] ?>" id="<?= $arItemIDs['QUANTITY'] ?>">
                            <button type="button" class="btn-silver quantity-change increase"
                                    id="<?= $arItemIDs['QUANTITY_UP'] ?>"><span class="plus"></span></button>
                        </form>
                        <? $bQuantityInput = true ?>
                    <? endif ?>
                    <? $frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader()) ?>
                        <? if ($arParams['SHOW_BUY_BTN']): ?>
                            <? $bBuyButton = true ?>
                            <div class="btn-buy-wrap text-only">
                                <? if (($arItem['bOffers'] && !$bSkuExt) || ($bBuyProps && !$emptyProductProperties && $arItem['CAN_BUY'])): ?>
                                    <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="btn-action buy when-in-stock">
                                        <i class="flaticon-shopping109"></i>
                                        <span class="text"><?= COption::GetOptionString($moduleId, 'button_text_offers') ?></span>
                                    </a>
                                    <?
                                else:?>
                                    <? if ($arItem['bOffers'] && $bSkuExt): ?>
                                        <button type="button"
                                                class="btn-action buy when-in-stock<?= ($arItem['CAN_BUY']) ? '' : ' hide' ?>"
                                                id="<?= $arItemIDs['BUY_LINK'] ?>"
                                                data-product-id="<?= $arItem['ID'] ?>"
                                                data-offer-id="<?= $arItem['OFFERS'][$arItem['OFFERS_SELECTED']]['ID'] ?>">
                                            <i class="flaticon-shopping109"></i>
                                            <span class="text"><?= COption::GetOptionString($moduleId, 'button_text_buy') ?></span>
                                            <span class="text in-cart"><?= COption::GetOptionString($moduleId, 'button_text_incart') ?></span>
                                        </button>
                                        <button type="button" class="btn-action buy when-in-stock on-request"
                                                id="<?= $arItemIDs['REQUEST_LINK'] ?>" data-toggle="modal"
                                                data-target="#modal_contact_product"
                                                data-product-id="<?= $arItem['ID'] ?>"
                                                data-offer-id="<?= $arItem['OFFERS'][$arItem['OFFERS_SELECTED']]['ID'] ?>"
                                                data-measure-name="<?= $arItem['OFFERS'][$arItem['OFFERS_SELECTED']]['CATALOG_MEASURE_NAME'] ?>">
                                            <i class="flaticon-speech90"></i>
                                            <span class="text"><?= COption::GetOptionString($moduleId, 'button_text_request') ?></span>
                                        </button>
                                    <? elseif ($arItem['CAN_BUY']): ?>
                                        <button type="button" class="btn-action buy when-in-stock"
                                                id="<?= $arItemIDs['BUY_LINK'] ?>" data-product-id="<?= $arItem['ID'] ?>">
                                            <i class="flaticon-shopping109"></i>
                                            <span class="text"><?= COption::GetOptionString($moduleId, 'button_text_buy') ?></span>
                                            <span class="text in-cart"><?= COption::GetOptionString($moduleId, 'button_text_incart') ?></span>
                                        </button>
                                        <?
                                    elseif ($availableOnRequest):?>
                                        <button type="button" class="btn-action buy when-in-stock"
                                                data-measure-name="<?= $arItem['CATALOG_MEASURE_NAME'] ?>"
                                                data-product-id="<?= $arItem['ID'] ?>" data-toggle="modal"
                                                data-target="#modal_contact_product">
                                            <i class="flaticon-speech90"></i>
                                            <span class="text"><?= COption::GetOptionString($moduleId, 'button_text_request') ?></span>
                                        </button>
                                        <?
                                    else:?>
                                        <span class="when-out-of-stock"><?= COption::GetOptionString($moduleId, 'button_text_na') ?></span>
                                        <? $bBuyButton = false ?>
                                    <? endif ?>
                                <? endif ?>
                            </div>
                        <? endif ?>
                        <? if ($arItem['CAN_BUY'] && $bShowOneClick && (!$bBuyProps || $emptyProductProperties)): ?>
                            <button id="<?= $arItemIDs['BUY_ONECLICK'] ?>" type="button" class="action one-click-buy"
                                    data-toggle="modal" data-target="#modal_quick-buy" data-id="<?= $arItem['ID'] ?>"
                                    data-props="<?= \Yenisite\Core\Tools::GetEncodedArParams($arParams['OFFER_TREE_PROPS']) ?>">
                                <i class="flaticon-shopping220"></i>
                                <span class="text"><?= GetMessage('BITRONIC2_BLOCKS_ONECLICK') ?></span>
                            </button>
                        <? endif ?>
                    <? $frame->end() ?>
                </div>
                <div class="action-buttons" id="<?= $arItemIDs['BASKET_ACTIONS'] ?>">
                    <? $frame = $this->createFrame($arItemIDs['BASKET_ACTIONS'], false)->begin(CRZBitronic2Composite::insertCompositLoader()) ?>
                    <div class="xs-switch">
                        <i class="flaticon-arrow128 when-closed"></i>
                        <i class="flaticon-key22 when-opened"></i>
                    </div>
                    <? if ($arParams['DISPLAY_FAVORITE']): ?>
                        <button
                                type="button"
                                class="btn-action favorite"
                                data-favorite-id="<?= $arItem['ID'] ?>"
                                data-tooltip title="<?= GetMessage('BITRONIC2_BLOCKS_ADD_TO_FAVORITE') ?>"
                                id="<?= $arItemIDs['FAVORITE_LINK'] ?>">
                            <i class="flaticon-heart3"></i>
                        </button>
                        <? $bFavoriteButton = true ?>
                    <? endif ?>
                    <? if ($arParams['DISPLAY_COMPARE_SOLUTION'] && $arItem['CATALOG_TYPE'] != 4): ?>
                        <button
                                type="button"
                                class="btn-action compare"
                                data-compare-id="<?= $arItem['ID'] ?>"
                                data-tooltip title="<?= GetMessage('BITRONIC2_BLOCKS_ADD_TO_COMPARE') ?>"
                                id="<?= $arItemIDs['COMPARE_LINK'] ?>">
                            <i class="flaticon-balance3"></i>
                        </button>
                        <? $bCompareButton = true ?>
                    <? endif ?>
                    <? $frame->end() ?>
                </div>

                <? $frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader()) ?>
                <form action="#" method="post" class="form_buy" id="<?= $arItemIDs['PROP_DIV']; ?>">
                    <?
                    // ***************************************
                    // ************ EXTENDED SKU *************
                    // ***************************************
                    if (isset($arItem['OFFERS']) && !empty($arItem['OFFERS']) && !empty($arItem['OFFERS_PROP']) && $arParams['SHOW_BUY_BTN']) {
                        foreach (GetModuleEvents(CRZBitronic2Settings::getModuleId(), "OnBeforeSectionlistExtSkuProp", true) as $arEvent)
                            ExecuteModuleEventEx($arEvent, array(&$arItem, &$arResult, &$arParams));
                        $arSkuProps = array();
                        $arSkuList = array();

                        foreach ($arItem['OFFERS_PROP'] as $propKey => $propVal) {
                            foreach ($arItem['OFFERS'] as $offer) {
                                if (!in_array($offer['PROPERTIES'][$propKey]['VALUE'], $arSkuList)) {
                                    array_push($arSkuList, $offer['PROPERTIES'][$propKey]['VALUE']);
                                }
                            }
                        }

                        foreach ($arResult['SKU_PROPS'] as &$arProp) {
                            if (!isset($arItem['OFFERS_PROP'][$arProp['CODE']]))
                                continue;
                            $arSkuProps[] = array(
                                'ID' => $arProp['ID'],
                                'SHOW_MODE' => $arProp['SHOW_MODE'],
                                'VALUES_COUNT' => $arProp['VALUES_COUNT']
                            );
                            $arProp['NAME'] = htmlspecialcharsBx($arProp['NAME']);
                            if ('TEXT' == $arProp['SHOW_MODE'] || 'PICT' == $arProp['SHOW_MODE'] || 'BOX' == $arProp['SHOW_MODE']) {
                                ?>
                                <select name="sku" class="select-styled" data-customclass="sku"
                                        id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_list">
                                    <? foreach ($arProp['VALUES'] as $arOneValue):
                                        $arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
                                    if(!empty($arOneValue['XML_ID']) && !in_array($arOneValue['XML_ID'], $arSkuList)) continue;
                                    if(empty($arOneValue['XML_ID']) && !in_array($arOneValue['NAME'], $arSkuList)) continue;
                                        if ($arOneValue['ID'] <=0) continue;?>
                                        <option
                                                data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID']; ?>"
                                                data-onevalue="<? echo $arOneValue['ID']; ?>"
                                                data-showmode="<? echo $arProp['SHOW_MODE']; ?>"
                                                id="<? echo $arItemIDs['PROP'] . $arProp['ID'] . '_' . $arOneValue['ID']; ?>"
                                                value="<? echo $arOneValue['ID']; ?>"
                                        ><?= $arProp['NAME'] ?>: <?= $arOneValue['NAME'] ?></option>
                                    <? endforeach ?>
                                </select>
                                <?
                            } elseif ('PICT' == $arProp['SHOW_MODE']) {
                                if(!in_array($arOneValue['XML_ID'], $arSkuList)) continue;
                                ?>

                                <div class="selection-color sku"
                                     id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_cont">
                                    <span class="text"><? echo htmlspecialcharsBx($arProp['NAME']); ?>:</span>
                                    <span id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_list">
						<? foreach ($arProp['VALUES'] as $arOneValue):
                            $arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']); ?>
                            <? if (!in_array($arOneValue['XML_ID'], $arSkuList)) continue; ?>
                            <span
                                    class="selection-item"
                                    data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID'] ?>"
                                    data-onevalue="<? echo $arOneValue['ID']; ?>"
                                    data-showmode="<? echo $arProp['SHOW_MODE']; ?>"
                            >
								<img class="lazy" data-original="<? echo $arOneValue['PICT']['SRC']; ?>"
                                     alt="<? echo $arOneValue['NAME']; ?>"
                                     src="<?= ConsVar::showLoaderWithTemplatePath() ?>" data-tooltip
                                     title="<? echo $arOneValue['NAME']; ?>">
							</span>
                        <? endforeach; ?>
					</span>
                                </div>
                                <?
                            } elseif ('BOX' == $arProp['SHOW_MODE']) {
                                if(!in_array($arOneValue['NAME'], $arSkuList)) continue;
                                ?>

                                <div class="selection-text sku"
                                     id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_cont">
                                    <span class="text"><? echo htmlspecialcharsBx($arProp['NAME']); ?>:</span>
                                    <span id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_list">
						<? foreach ($arProp['VALUES'] as $arOneValue):
                            $arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']); ?>
                            <span
                                    class="selection-item"
                                    data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID'] ?>"
                                    data-onevalue="<? echo $arOneValue['ID']; ?>"
                                    data-showmode="<? echo $arProp['SHOW_MODE']; ?>"
                                    data-tooltip title="<?= $arOneValue['NAME'] ?>"
                            >
								<?= $arOneValue['NAME'] ?>

							</span>
                        <? endforeach; ?>
					</span>
                                </div>
                                <?
                            }
                        }
                        unset($arProp);
                    }
                    ?>
                </form>
                <? $frame->end() ?>
                <?
                if ($bHoverMode) {
                    $htmlButtons = ob_get_clean();
                }
                ?>
            </div>
            <?
            if (
                $bHoverMode &&
                (
                    $bBuyButton || $bCompareButton ||
                    $bFavoriteButton || $bQuantityInput ||
                    !empty($arItem['DISPLAY_PROPERTIES']) ||
                    !empty($arItem['PREVIEW_TEXT'])
                )
            ):
                ?>

                <div class="description full-view">
                    <?= $htmlButtons ?>
                    <? if (!empty($arItem['DISPLAY_PROPERTIES'])) : ?>
                        <dl class="techdata">
                            <? foreach ($arItem['DISPLAY_PROPERTIES'] as $arProp): ?>
                                <dt><?= $arProp['NAME'] ?></dt>
                                <dd><?= strip_tags(is_array($arProp['DISPLAY_VALUE']) ? implode(' / ', $arProp['DISPLAY_VALUE']) : $arProp['DISPLAY_VALUE']) ?></dd>
                            <? endforeach ?>
                        </dl>
                    <? endif ?>
                    <?= $arItem['PREVIEW_TEXT'] ?>
                </div>
            <? endif ?>

            <? // ADMIN INFO
            include 'admin_info.php'; ?>
        </div><!-- /.catalog-item.blocks-item -->

        <? // JS PARAMS
        include 'js_params.php';
        ?>
    </div><!-- /.catalog-item-wrap --><?
    $count++;
endforeach;
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";

?>
    <script type="application/javascript">
        $("#catalog_section").toggleClass("availability-comments-enabled", <?=($arResult['AVAILABILITY_COMMENTS_ENABLED'] ? 'true' : 'false')?>);
    </script><?

$frame = $this->createFrame()->begin('');
if ($arJsCache['file']):
    $bytes = fwrite($arJsCache['file'], $jsString);
    if ($bytes === false || $bytes != mb_strlen($jsString, 'windows-1251')) {
        fclose($arJsCache['file']);
        $arJsCache['file'] = false;
    }
endif;
if (!$arJsCache['file']):

    ?>
    <script type="text/javascript">
        <?=$jsString?>
    </script><?

endif;
$frame->end();

if ($arParams['SEARCH_PAGE'] == 'Y' && ($arParams['SEARCH_PAGE_CLOSE_TAG'] == 'Y' || $arParams['SEARCH_PAGE_ONLY_CATALOG'] == 'Y')) {
    echo '</div>';
}

if ($arJsCache['file']) {
    $templateData['jsFile'] = $arJsCache['path'] . '/' . $arJsCache['idJS'];
    fclose($arJsCache['file']);
}
