<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

$templateData = $arResult['TEMPLATE_DATA'];

$bStores = $arParams["SHOW_AMOUNT_STORE"] == "Y" && Bitrix\Main\ModuleManager::isModuleInstalled("catalog");
$bExpandedStore = false;
$itemCount = count($arResult['ITEMS']);
$id = 'bxdinamic_bitronic2_watched_list_' . $arParams['IBLOCK_ID'];
?>
<a href="#" class="btn-you-watched pseudolink with-icon"
   data-popup="#popup_you-watched" id="you-watched-toggler">
    <i class="flaticon-eye36"></i>
    <span class="items-inside" id="<?= $id ?>">
        <?
        $frame = $this->createFrame($id, false)->begin(CRZBitronic2Composite::insertCompositLoader());
        ?>
        <?= $itemCount ?>
        <? $frame->end(); ?>
    </span>
    <span class="link-text"><?= GetMessage('BITRONIC2_WATCHED_TITLE') ?></span>
</a>
<div class="top-line-popup popup_you-watched" id="popup_you-watched" data-darken>
    <button class="btn-close" data-popup="#popup_you-watched">
        <span class="btn-text"><?= GetMessage('BITRONIC2_MODAL_CLOSE') ?></span>
        <i class="flaticon-close47"></i>
    </button>
    <div class="popup-header">
        <? $frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader()); ?>
        <span class="header-text">
            <?= GetMessage('BITRONIC2_YOU_WATCHED') ?> <?= $itemCount ?> <?= \Yenisite\Core\Tools::rusQuantity($itemCount, GetMessage('BITRONIC2_ITEMS_CNT')) ?>:
        </span>
        <? $frame->end(); ?>
    </div>
    <div class="table-wrap">
        <div class="scroller scroller_v">
            <? $frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader()); ?>
            <? include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/include/debug_info.php'; ?>
            <? if ($itemCount > 0): ?>
                <table class="items-table">
                    <thead>
                    <tr>
                        <th colspan="2"><?= GetMessage('BITRONIC2_WATCHED_GOOD') ?></th>
                        <th class="availability"></th>
                        <th class="price"><?= GetMessage('BITRONIC2_COMPARE_PRICE') ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <? foreach ($arResult['ITEMS'] as $arItem):
                        $strMainID = $this->GetEditAreaId($arItem['ID'].rand());
                        $arItemIDs = array(
                            'ID' => $strMainID,
                            'BUY_LINK' => $strMainID.'_buy_link',
                            'BASKET_ACTIONS' => $strMainID.'_basket_actions',
                            'PRICE' => $strMainID.'_price',
                        );
                        $strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID).'_viewed';

                        $imgTitle = (
                        !empty($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'])
                            ? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
                            : $arItem['NAME']
                        );
                        $bShowStore = $bStores && !$arItem['bOffers'];
                        $bShowBlocksIfEmptySKU = ($arItem['bOffers'] && !empty($arItem['OFFERS'])) || !$arItem['bOffers'];
                        $arParams['DISPLAY_FAVORITE'] = !$bShowBlocksIfEmptySKU ? $bShowBlocksIfEmptySKU : $arParams['DISPLAY_FAVORITE'];
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
                        if ($availableOnRequest) $arItem['CAN_BUY'] = false;
                        ?>
                        <tr id="<?=$arItemIDs['ID']?>" class="table-item popup-table-item  <?= $availableClass ?>">
                            <td itemscope itemtype="http://schema.org/ImageObject" class="photo">
                                <img itemprop="contentUrl" class="lazy"
                                     data-original="<?= $arItem['PICTURE_PRINT']['SRC'] ?>"
                                     src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                     alt="<?= $arItem['PICTURE_PRINT']['ALT'] ?>" title="<?= $imgTitle ?>">
                            </td>
                            <td class="name">
                                <input type="hidden" name="quantity"
                                       value="<?= ($arItem['CAN_BUY'] ? $arItem['CATALOG_MEASURE_RATIO'] : 0) ?>"
                                       data-id="<?= $arItem['ID'] ?>">
                                <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="link"><span
                                            class="text"><?= $arItem['NAME'] ?></span></a>
                                <?
                                if ($arParams['SHOW_VOTING'] == 'Y') {
                                    $APPLICATION->IncludeComponent("bitrix:iblock.vote", "stars", array(
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
                                } ?>
                            </td>
                            <td class="availability">
                                <?
                                $availableID = false;
                                $availableClass = '';
                                $availableFrame = false;
                                $availableForOrderText = &$arItem['PROPERTIES']['RZ_FOR_ORDER_TEXT']['VALUE'];
                                $availableItemID = &$arItem['ID'];
                                $availableMeasure = &$arItem['CATALOG_MEASURE_NAME'];
                                $availableQuantity = &$arItem['CATALOG_QUANTITY'];
                                $availableStoresPostfix = 'compare';
                                $availableSubscribe = $arItem['CATALOG_SUBSCRIBE'];
                                $bShowEveryStatus = true;
                                $bExpandedStore = false;
                                include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/availability_info.php';
                                ?>
                            </td>


                            <td class="price">
                                <?if ($bShowBlocksIfEmptySKU):?>
                                    <? if (!$arItem['ON_REQUEST']): ?>
                                        <span id="<?=$arItemIDs['PRICE']?>" class="price-new">
                                    <?= ($arItem['bOffers'] && $arItem['bOffersNotEqual']) ? GetMessage('BITRONIC2_COMPARE_FROM') : '' ?>
                                    <?= CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']); ?></span>
                                        <div>
                                            <? if ($arItem['MIN_PRICE']['DISCOUNT_DIFF'] > 0): ?>
                                                <span class="price-old"><?= CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['VALUE'], $arItem['MIN_PRICE']['PRINT_VALUE']); ?></span>
                                            <? endif ?>
                                        </div>
                                    <? endif ?>
                                <?endif?>
                            </td>
                            <td id="<?=$arItemIDs['BASKET_ACTIONS']?>" class="actions">
                                <? if ($arItem['CAN_BUY'] && !$arItem['bOffers']): ?>
                                    <button id="<?=$arItemIDs['BUY_LINK']?>" data-product-id="<?=$arItem['ID']?>" class="buy btn-basket-popup pseudolink with-icon" data-tooltip data-placement="bottom">
                                        <i class="flaticon-shopping109"></i>
                                        <span class="text btn-text"><?=COption::GetOptionString($moduleId, 'button_text_buy')?></span>
                                        <span class="text in-cart"><?=COption::GetOptionString($moduleId, 'button_text_incart')?></span>
                                    </button>
                                <?elseif($arItem['bOffers']):?>
                                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="btn-basket-popup pseudolink with-icon" >
                                        <i class="flaticon-check14"></i>
                                        <span class="text btn-text"><?= GetMessage('BITRONIC2_WATCHED_CHOOSE') ?></span>
                                    </a>
                                <? endif; ?>
                            </td>
                        </tr>
                        <?include 'js_params.php'?>
                    <? endforeach; ?>
                    </tbody>
                </table>
            <? endif ?>
            <? $frame->end(); ?>
            <div class="scroller__track scroller__track_v">
                <div class="scroller__bar scroller__bar_v"></div>
            </div>
        </div>
    </div>
</div>
<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
