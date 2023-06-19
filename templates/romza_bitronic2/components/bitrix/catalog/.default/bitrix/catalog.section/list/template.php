<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$this->setFrameMode(true);

// @var $moduleId
// @var $moduleCode
include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/include/debug_info_dynamic.php';

$this->SetViewTarget('catalog_paginator');
echo $arResult["NAV_STRING"];
$this->EndViewTarget();

if (empty($arResult['ITEMS']))
    return;

$arJsCache = CRZBitronic2CatalogUtils::getJSCache($component);
$jsString = '';

$showBannerOn = ceil(count($arResult['ITEMS']) / 2);
$count = 0;

$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));

$bStores = $arParams["USE_STORE"] == "Y" && Bitrix\Main\ModuleManager::isModuleInstalled("catalog");
?>

<? foreach ($arResult['ITEMS'] as $arItem):
    $this->AddEditAction($templateName . '-' . $arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
    $this->AddDeleteAction($templateName . '-' . $arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
    $strMainID = $this->GetEditAreaId($templateName . '-' . $arItem['ID']);
    $arItemIDs = array(
        'ID' => $strMainID,
        'PICT' => $strMainID . '_pict',
        'SLIDER_CONT_OF_ID' => $strMainID . '_slider_cont_',
        'SECOND_PICT' => $strMainID . '_secondpict',
        'STICKER_ID' => $strMainID . '_sticker',
        'SECOND_STICKER_ID' => $strMainID . '_secondsticker',
        'QUANTITY' => $strMainID . '_quantity',
        'QUANTITY_DOWN' => $strMainID . '_quant_down',
        'QUANTITY_UP' => $strMainID . '_quant_up',
        'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
        'BUY_LINK' => $strMainID . '_buy_link',
        'BUY_ONECLICK' => $strMainID . '_buy_oneclick',
        'BASKET_ACTIONS' => $strMainID . '_basket_actions',
        'AVAILABLE_INFO' => $strMainID . '_avail_info',
        'AVAILABLE_INFO_FULL' => $strMainID . '_avail_info_full',
        'SUBSCRIBE_LINK' => $strMainID . '_subscribe',
        'COMPARE_LINK' => $strMainID . '_compare_link',
        'FAVORITE_LINK' => $strMainID . '_favorite_link',
        'REQUEST_LINK' => $strMainID . '_request_link',

        'PRICE' => $strMainID . '_price',
        'PRICE_CONTAINER' => $strMainID . '_price_container',
        'PRICE_OLD' => $strMainID . '_price_old',
        'DSC_PERC' => $strMainID . '_dsc_perc',
        'SECOND_DSC_PERC' => $strMainID . '_second_dsc_perc',
        'PROP_DIV' => $strMainID . '_sku_tree',
        'PROP' => $strMainID . '_prop_',
        'ARTICUL' => $strMainID . '_articul',
        'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
        'BASKET_PROP_DIV' => $strMainID . '_basket_prop',
        'BASKET_BUTTON' => $strMainID . '_basket_button',
        'STORES' => $strMainID . '_stores',
        'PRICE_ADDITIONAL' => $strMainID . '_price_additional',
    );
    $arItemCLASSes = array(
        'LINK' => $strMainID . '_link',
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

    $bSkuExt = $arItem['bSkuExt'];
    $bShowStore = $bStores && !$arItem['bSkuSimple'];
    $bExpandedStore = $arParams['PRODUCT_AVAILABILITY_VIEW'];
    $bShowOneClick = $arParams['DISPLAY_ONECLICK'] && (!$arItem['bOffers'] || $arItem['bSkuExt']);

    $arItem['ARTICUL'] = (
    $arItem['bOffers'] && $bSkuExt && !empty($arItem['JS_OFFERS'][$arItem['OFFERS_SELECTED']]['ARTICUL'])
        ? $arItem['JS_OFFERS'][$arItem['OFFERS_SELECTED']]['ARTICUL']
        : (
    is_array($arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])
        ? implode(' / ', $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])
        : $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']
    )
    );

    $availableOnRequest = (
    $arItem['bOffers'] && $bSkuExt
        ? empty($arItem['JS_OFFERS'][$arItem['OFFERS_SELECTED']]['MIN_PRICE']) || $arItem['JS_OFFERS'][$arItem['OFFERS_SELECTED']]['MIN_PRICE']['VALUE'] <= 0
        : empty($arItem['MIN_PRICE']) || $arItem['MIN_PRICE']['VALUE'] <= 0
    );
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

    $bEmptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
    $bBuyProps = ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$bEmptyProductProperties);

    $bCatchbuy = ($arParams['SHOW_CATCHBUY'] && $arItem['CATCHBUY']);

    if ($arItem['SHOW_SLIDER']) {
        $arItem['SHOW_SLIDER'] = $arParams['SHOW_GALLERY_THUMB'] == 'Y';
    }
    ?>
    <? if ($count >= $showBannerOn && $arParams['BANNER_PLACE']):?>
    <? $showBannerOn = 99999999999999; ?>
    #BANNER_PLACE#
<?endif ?>
    <div itemscope itemtype="http://schema.org/Product" class="catalog-item-wrap<?= ($arItem['VIP'] ? ' big-item' : '') ?>" id="<?= $arItemIDs['ID'] ?>">
        <div class="catalog-item list-item wow fadeIn">
            <div class="photo-wrap <?= !$arItem['SHOW_SLIDER'] ? ' no-thumbs' : '' ?>">
                <div itemscope itemtype="http://schema.org/ImageObject" class="photo">
                    <a title="<?= $imgTitle ?>" href="<?= $arItem['DETAIL_PAGE_URL'] ?>"
                       class="<?= $arItemCLASSes['LINK'] ?>">
                        <img class="lazy" data-original="<?= $arItem['PICTURE_PRINT']['SRC'] ?>"
                             src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                             alt="<?= $arItem['PICTURE_PRINT']['ALT'] ?>" id="<?= $arItemIDs['PICT'] ?>"
                             title="<?= $imgTitle ?>"
                             itemprop="contentUrl">
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
                                    <div class="value countdown-amount"><?= intVal($arItem['CATCHBUY']['PERCENT']) ?>%
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

                    <?= $arItem['yenisite:stickers'] ?>

                    <div class="quick-view-switch" data-toggle="modal" data-target="#modal_quick-view">
						<span class="quick-view-fake-btn">
							<span class="text"><?= GetMessage('BITRONIC2_LIST_QUICK_VIEW') ?></span>
						</span>
                        <i class="flaticon-zoom62"></i>
                    </div>
                </div><!-- .photo -->
                <? if (!$bSkuExt):?>
                    <? if ($arItem['SHOW_SLIDER']):?>
                        <div class="photo-thumbs">
                            <div class="slidee">
                                <? foreach ($arItem['MORE_PHOTO'] as $arPhoto):
                                    ?>
                                    <div itemscope itemtype="http://schema.org/ImageObject" class="photo-thumb">
                                    <img    itemprop="contentUrl"
                                            class="lazy"
                                            data-original="<?= CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams['RESIZER_SECTION_ICON']) ?>"
                                            src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                            title="<?= strlen($arPhoto['DESCRIPTION']) > 0 ? $arPhoto['DESCRIPTION'] : $arItem['PICTURE_PRINT']['ALT'] ?>"
                                            alt="<?= strlen($arPhoto['DESCRIPTION']) > 0 ? $arPhoto['DESCRIPTION'] : $arItem['PICTURE_PRINT']['ALT'] ?>"
                                            data-medium-image="<?= CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams['RESIZER_SECTION']) ?>"
                                    >
                                    </div><?
                                endforeach; ?>
                            </div>
                            <? if ($arItem['MORE_PHOTO_COUNT'] > 4):?>
                                <div class="carousel-dots"></div>
                            <?endif ?>
                        </div>
                    <?endif ?>
                    <?
                else:?>
                    <? foreach ($arItem['OFFERS'] as $arOffer):?>
                        <? if ($arOffer['SHOW_SLIDER']):?>
                            <div class="photo-thumbs" id="<? echo $arItemIDs['SLIDER_CONT_OF_ID'] . $arOffer['ID']; ?>"
                                 style="display:none">
                                <div class="slidee">
                                    <? foreach ($arOffer['MORE_PHOTO'] as $arPhoto):
                                        ?>
                                        <div itemscope itemtype="http://schema.org/ImageObject" class="photo-thumb">
                                        <img    itemprop="contentUrl"
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
                                <? if ($arOffer['MORE_PHOTO_COUNT'] > 4):?>
                                    <div class="carousel-dots"></div>
                                <?endif ?>
                            </div>
                        <?endif ?>
                    <?endforeach ?>
                <?endif ?>
            </div><!-- /.photo-wrap -->
            <div class="main-data">
                <div class="name">
                    <a itemprop="url" href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="link <?= $arItemCLASSes['LINK'] ?>"><span
                                itemprop="name" class="text"><?= $productTitle ?></span></a>
                </div>
                <div class="art-rate clearfix">
                    <? if ($arParams['SHOW_ARTICLE'] == 'Y'): ?>
                        <span id="<?= $arItemIDs['ARTICUL'] ?>"
                              class="art<? if (empty($arItem['ARTICUL'])): ?> hidden<? endif ?>">
							<?= $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['NAME'] ?>:
							<strong><?= $arItem['ARTICUL'] ?></strong>
						</span>
                    <? endif ?>
                    <div class="info rating rating-w-comments">
                        <?if ($arParams['SHOW_STARS'] == 'Y'): ?>
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
                            ); ?>
                        <? endif ?>
                        <? if (isset($arItem['REVIEW_COUNT'])): ?>
                            <span class="comments">
							<?= $arItem['REVIEW_COUNT'] ?>
                            <?= \Yenisite\Core\Tools::rusQuantity($arItem['REVIEW_COUNT'], GetMessage('BITRONIC2_LIST_REVIEW')) ?>
						</span>
                        <? endif ?>
                    </div>
                </div>
                <div class="availability clearfix" id="<?= $arItemIDs['AVAILABLE_INFO_FULL'] ?>">
                    <?$frame = $this->createFrame($arItemIDs['AVAILABLE_INFO_FULL'], false)->begin(CRZBitronic2Composite::insertCompositLoader()) ?>
                    <?
                    $availableID = &$arItemIDs['AVAILABLE_INFO'];
                    $availableFrame = false;
                    $availableForOrderText = &$arItem['PROPERTIES']['RZ_FOR_ORDER_TEXT']['VALUE'];
                    $availableItemID = &$arItem['ID'];
                    $availableMeasure = &$arItem['CATALOG_MEASURE_NAME'];
                    $availableQuantity = &$arItem['CATALOG_QUANTITY'];
                    $availableStoresPostfix = 'list';
                    $availableSubscribe = (!$arItem['bOffers'] || $bSkuExt) ? $arItem['CATALOG_SUBSCRIBE'] : 'N';
                    $bShowEveryStatus = ($arItem['bOffers'] && $bSkuExt);
                    include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/availability_info.php';
                    ?>
                    <? if ($bShowStore && $arParams['HIDE_STORE_LIST'] == 'N'):?>
                        <?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "store", array(
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
                    <?endif ?>
                    <?$frame->end()?>
                </div>


                <?// DISPLAY_PROPERTIES
                ?>
                <dl class="techdata">
                    <? foreach ($arItem['DISPLAY_PROPERTIES'] as $arProp):
                        $arProp["DISPLAY_VALUE"] = (substr_count($arProp["DISPLAY_VALUE"], "a href") > 0)
                            ? strip_tags($arProp["DISPLAY_VALUE"])
                            : $arProp["DISPLAY_VALUE"];
                        ?>
                        <dt><?= $arProp['NAME'] ?>:</dt>
                        <dd><?= (is_array($arProp['DISPLAY_VALUE']) ? implode(' / ', $arProp['DISPLAY_VALUE']) : $arProp['DISPLAY_VALUE']) ?></dd>
                    <?endforeach ?>
                </dl>
                <?
                if ($bSkuExt && $arItem['OFFERS_PROPS_DISPLAY']) {
                    foreach ($arItem['JS_OFFERS'] as $keyOffer => $arJSOffer) {
                        $strProps = '';
                        if (!empty($arJSOffer['DISPLAY_PROPERTIES'])) {
                            foreach ($arJSOffer['DISPLAY_PROPERTIES'] as $arOneProp) {
                                $strProps .= '<dt>' . $arOneProp['NAME'] . ':</dt><dd>' . (
                                    is_array($arOneProp['VALUE'])
                                        ? implode(' / ', $arOneProp['VALUE'])
                                        : $arOneProp['VALUE']
                                    ) . '</dd>';
                            }
                        }
                        $arItem['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
                    }
                    ?>
                    <dl class="techdata clearfix" id="<? echo $arItemIDs['DISPLAY_PROP_DIV']; ?>"
                        style="display: none;"></dl>
                    <?
                }
                ?>
            </div><!-- main-data -->
            <div itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer" class="buy-block <?= !$arItem['bOffers'] && !$bSkuExt && !$arItem['CAN_BUY'] && $availableOnRequest ? 'on-request' : '' ?>">
                <div id="<?= $arItemIDs['PRICE_CONTAINER'] ?>">
                    <?$frame = $this->createFrame($arItemIDs['PRICE_CONTAINER'], false)->begin(CRZBitronic2Composite::insertCompositLoader()) ?>
                    <div class="prices <?= (empty($availableOnRequest) ? '' : ' hide') ?>">
						<span class="price-old" id="<?= $arItemIDs['PRICE_OLD'] ?>">
						<? if ($arItem['MIN_PRICE']['DISCOUNT_DIFF'] > 0 && $arParams['SHOW_OLD_PRICE'] == 'Y'): ?>
                            <?= CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['VALUE'], $arItem['MIN_PRICE']['PRINT_VALUE']); ?>
                            <span class="hidden" itemprop="highPrice"><?=$arItem['MIN_PRICE']['VALUE']?></span>
                        <? endif ?>
						</span>
                        <span class="price" id="<?= $arItemIDs['PRICE'] ?>">
						<?= ($arItem['bOffers'] && !$bSkuExt && $arItem['bOffersNotEqual']) ? GetMessage('BITRONIC2_LIST_FROM') : '' ?>
                        <?= CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']); ?>
                            <span class="hidden" itemprop="lowPrice"><?=$arItem['MIN_PRICE']['DISCOUNT_VALUE'] ? : 0?></span>
                            <span class="hidden" itemprop="priceCurrency"><?=$arItem['MIN_PRICE']['CURRENCY']?></span>
						</span>
                    </div>
                    <div id="<?= $arItemIDs['PRICE_ADDITIONAL'] ?>"
                         class="prices additional-price-container <?= (empty($availableOnRequest) && CRZBitronic2Settings::isPro() ? '' : ' hide') ?>">
                        <?
                        $arItemPrices = $arItem['PRICES'];
                        $minPriceId = $arItem['MIN_PRICE']['PRICE_ID'];
                        include 'additional_prices.php'
                        ?>
                    </div>
                    <? $frame->end() ?>
                </div>
                <div id="<?= $arItemIDs['BASKET_BUTTON']; ?>">
                    <? $frame = $this->createFrame($arItemIDs['BASKET_BUTTON'], false)->begin(CRZBitronic2Composite::insertCompositLoader()) ?>
                    <form action="#" method="post" class="form_buy" id="<?= $arItemIDs['PROP_DIV']; ?>">
                        <?
                        // ***************************************
                        // *********** BUY WITH PROPS ************
                        // ***************************************
                        if ($bBuyProps && $arParams['SHOW_BUY_BTN']):
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
                                if (!$emptyProductProperties) {
                                    foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo) {/* TODO
				if(
					'L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']
					&& 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']
				)
				{
?>
							<div class="select-wrap">
								<div class="text"><?=$arItem['PROPERTIES'][$propID]['NAME']?>:</div>
<?
					foreach($propInfo['VALUES'] as $valueID => $value)
					{
						?>
								<label class="radio-styled">
									<input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? 'checked="checked"' : ''); ?>>
									<span class="radio-content">
										<span class="radio-fake"></span>
										<span class="text"><? echo $value; ?></span>
									</span>
								</label>
<?
					}
?>
							</div>
<?
				}
				else
				{*/
                                        ?>
                                        <div class="select-wrap">
                                        <select class="select-styled"
                                                name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
                                            foreach ($propInfo['VALUES'] as $valueID => $value) {
                                                ?>
                                                <option
                                                value="<? echo $valueID; ?>" <? echo($valueID == $propInfo['SELECTED'] ? ' selected="selected"' : ''); ?>><?= $arItem['PROPERTIES'][$propID]['NAME'] ?>
                                                : <?= $value ?></option><?
                                            }
                                            ?></select></div><?
                                        //} TODO
                                    }
                                }
                                ?>
                            </div>
                        <? endif ?>

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
                                if ('TEXT' == $arProp['SHOW_MODE']) {
                                    ?>
                                    <select name="sku" class="select-styled" data-customclass="sku"
                                            id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_list">
                                        <? foreach ($arProp['VALUES'] as $arOneValue):
                                            $arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']); ?>
                                            <?if(!in_array($arOneValue['NAME'], $arSkuList)) continue;?>
                                            <option
                                                    data-treevalue="<? echo $arProp['ID'] . '_' . $arOneValue['ID']; ?>"
                                                    data-onevalue="<? echo $arOneValue['ID']; ?>"
                                                    data-showmode="<? echo $arProp['SHOW_MODE']; ?>"
                                                    id="<? echo $arItemIDs['PROP'] . $arProp['ID'] . '_' . $arOneValue['ID']; ?>"
                                                    value="<? echo $arOneValue['ID']; ?>"
                                            ><?= $arProp['NAME'] ?>: <?= $arOneValue['NAME'] ?></option>
                                        <?endforeach ?>
                                    </select>
                                    <?
                                } elseif ('PICT' == $arProp['SHOW_MODE']) {
                                    ?>

                                    <div class="selection-color sku"
                                         id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_cont">
                                        <span class="text"><? echo htmlspecialcharsBx($arProp['NAME']); ?>:</span>
                                        <span id="<? echo $arItemIDs['PROP'] . $arProp['ID']; ?>_list">
						<? foreach ($arProp['VALUES'] as $arOneValue):
                            $arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']); ?>
                            <?if(!in_array($arOneValue['XML_ID'], $arSkuList)) continue;?>
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
                        <?endforeach; ?>
					</span>
                                    </div>
                                    <?
                                } elseif ('BOX' == $arProp['SHOW_MODE']) {
                                    if(!in_array($arOneValue['NAME'], $arSkuList)) continue;?>

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
                        <?endforeach; ?>
					</span>
                                    </div>
                                    <?
                                }
                            }
                            unset($arProp);
                        }
                        ?>
                        <div class="xs-switch">
                            <i class="flaticon-arrow128 when-closed"></i>
                            <i class="flaticon-key22 when-opened"></i>
                        </div>
                        <?
                        /* TODO
                        include '_/buttons/btn-action_to-fav.html';
                        */
                        ?>
                        <? if ($arParams['SHOW_BUY_BTN']):?>
                            <div class="quantity-counter"
                                 data-tooltip
                                 data-placement="bottom"
                                 title="<?= $arItem['CATALOG_MEASURE_NAME'] ?>">
                                <? if ('Y' == $arParams['USE_PRODUCT_QUANTITY'] && (!$arItem['bOffers'] || $bSkuExt) && $arItem['CAN_BUY']):?>
                                    <!-- parent must have class .quantity-counter! -->
                                    <button type="button" class="btn-silver quantity-change decrease disabled"
                                            id="<?= $arItemIDs['QUANTITY_DOWN'] ?>"><span class="minus"></span></button>
                                    <input type="text" class="quantity-input textinput"
                                           value="<?= $arItem['CATALOG_MEASURE_RATIO'] ?>"
                                           id="<?= $arItemIDs['QUANTITY'] ?>">
                                    <button type="button" class="btn-silver quantity-change increase"
                                            id="<?= $arItemIDs['QUANTITY_UP'] ?>"><span class="plus"></span></button>
                                <?endif ?>
                            </div>
                            <div class="btn-buy-wrap text-only" id="<?= $arItemIDs['BASKET_ACTIONS'] ?>">
                                <? if ($arItem['bOffers'] && !$bSkuExt):?>
                                    <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>"
                                       class="btn-action buy when-in-stock <?= $arItemCLASSes['LINK'] ?>">
                                        <i class="flaticon-shopping109"></i>
                                        <span class="text"><?= COption::GetOptionString($moduleId, 'button_text_offers') ?></span>
                                    </a>
                                    <?
                                else:?>
                                    <? if ($arItem['bOffers'] && $bSkuExt):?>
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
                                        <?
                                    elseif ($arItem['CAN_BUY']):?>
                                        <button type="button" class="btn-action buy when-in-stock"
                                                id="<?= $arItemIDs['BUY_LINK'] ?>"<? if (!$bBuyProps || $emptyProductProperties):?> data-product-id="<?= $arItem['ID'] ?>"<?endif ?>>
                                            <i class="flaticon-shopping109"></i>
                                            <span class="text"><?= COption::GetOptionString($moduleId, 'button_text_buy') ?></span>
                                            <span class="text in-cart"><?= COption::GetOptionString($moduleId, 'button_text_incart') ?></span>
                                        </button>
                                        <?
                                    elseif ($availableOnRequest):?>
                                        <button type="button" class="btn-action buy when-in-stock"
                                                id="<?= $arItemIDs['REQUEST_LINK'] ?>" data-toggle="modal"
                                                data-target="#modal_contact_product"
                                                data-product-id="<?= $arItem['ID'] ?>"
                                                data-measure-name="<?= $arItem['CATALOG_MEASURE_NAME'] ?>">
                                            <i class="flaticon-speech90"></i>
                                            <span class="text"><?= COption::GetOptionString($moduleId, 'button_text_request') ?></span>
                                        </button>
                                        <?
                                    else:?>
                                        <span class="when-out-of-stock"><?= COption::GetOptionString($moduleId, 'button_text_na') ?></span>
                                    <?endif ?>
                                <?endif ?>
                            </div>
                        <?endif ?>
                    </form>
                    <? if ($bShowOneClick && !$bBuyProps):?>
                        <button id="<?= $arItemIDs['BUY_ONECLICK'] ?>" type="button"
                                class="action one-click-buy<?= ($arItem['CAN_BUY']) ? '' : ' hide' ?>"
                                data-toggle="modal"
                                data-target="#modal_quick-buy" data-id="<?= $arItem['ID'] ?>"
                                data-props="<?= \Yenisite\Core\Tools::GetEncodedArParams($arParams['OFFER_TREE_PROPS']) ?>">
                            <i class="flaticon-shopping220"></i>
                            <span class="text"><?= GetMessage('BITRONIC2_LIST_ONECLICK') ?></span>
                        </button>
                    <?endif ?>
                    <? $frame->end() ?>
                </div>
                <div class="action-buttons">
                    <? if ($arParams['DISPLAY_FAVORITE']):?>
                        <button
                                type="button"
                                class="with-icon toggleable action favorite"
                                data-favorite-id="<?= $arItem['ID'] ?>"
                                data-tooltip title="<?= GetMessage('BITRONIC2_BLOCKS_ADD_TO_FAVORITE') ?>"
                                id="<?= $arItemIDs['FAVORITE_LINK'] ?>">
                            <i class="flaticon-heart3"></i>
                            <span class="text when-not-toggled"><?=GetMessage('BITRONIC2_TO_FAVORITE')?></span>
                            <span class="text when-toggled"><?=GetMessage('BITRONIC2_IN_FAVORITE')?></span>
                        </button>
                    <?endif ?>
                    <? if ($arParams['DISPLAY_COMPARE_SOLUTION']):?>
                        <button
                                type="button"
                                class="with-icon toggleable action compare"
                                data-compare-id="<?= $arItem['ID'] ?>"
                                data-tooltip title="<?= GetMessage('BITRONIC2_LIST_ADD_TO_COMPARE') ?>"
                                id="<?= $arItemIDs['COMPARE_LINK'] ?>">
                            <i class="flaticon-balance3"></i>
                            <span class="text when-not-toggled"><?=GetMessage('BITRONIC2_TO_COMPARE')?></span>
                            <span class="text when-toggled"><?=GetMessage('BITRONIC2_IN_COMPARE')?></span>
                        </button>
                    <?endif ?>
                </div>
            </div><!-- /.buy-block -->
            <? if (!empty($arItem['PREVIEW_TEXT']) && $arParams['HOVER-MODE'] == 'detailed-expand'): ?>

                <div class="description full-view">
                    <?= $arItem['PREVIEW_TEXT'] ?>
                </div>
            <? endif ?>

            <? // ADMIN INFO
            include 'admin_info.php';
            ?>

        </div><!-- /.catalog-item.blocks-item -->
        <? // JS PARAMS
        include 'js_params.php';
        ?>
    </div><!-- /.catalog-item-wrap --><?
    $count++;
endforeach;
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";

$frame = $this->createFrame()->begin('');
if ($arJsCache['file']):
    $bytes = fwrite($arJsCache['file'], $jsString);
    if ($bytes === false || $bytes != mb_strlen($jsString, 'windows-1251')) {
        fclose($arJsCache['file']);
        $arJsCache['file'] = false;
    }
endif;
if (!$arJsCache['file']):?>
    <script type="text/javascript">
        <?=$jsString?>
    </script>
<?endif;
$frame->end();

if ($arJsCache['file']) {
    $templateData['jsFile'] = $arJsCache['path'] . '/' . $arJsCache['idJS'];
    fclose($arJsCache['file']);
}