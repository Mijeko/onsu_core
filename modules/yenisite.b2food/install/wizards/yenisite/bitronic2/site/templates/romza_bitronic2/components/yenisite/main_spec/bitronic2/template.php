<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (method_exists($this, 'setFrameMode')) $this->setFrameMode(true); ?>

<?
use Bitrix\Main\Loader;
use \Yenisite\Core\Tools;

global $rz_main_spec_filter;
global $rz_b2_options;
// echo "<pre style='text-align:left;'>";print_r($arParams);echo "</pre>";
?>

<? if (strtolower($_REQUEST['rz_ajax']) === 'y' || strtolower($_REQUEST['ajax_basket']) === 'y'):
    $APPLICATION->RestartBuffer();
    $tabU = strtoupper($_REQUEST['tab']);
    $tabL = strtolower($_REQUEST['tab']);

    if (!array_key_exists($tabU, $arResult['TABS'])) {
        reset($arResult['TABS']);
        $tabU = key($arResult['TABS']);
    }

    $arTab = $arResult['TABS'][$tabU];
    $rz_main_spec_filter = $arTab['FILTER'];

    $arParamsCatalog = array();
    if (Loader::IncludeModule('yenisite.core')) {
        $arParamsCatalog = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
    }

    CRZBitronic2CatalogUtils::setFilterAvPrFoto($rz_main_spec_filter,$arParamsCatalog);
    $arParams['USE_PRODUCT_QUANTITY'] = $rz_b2_options['block-quantity'];

    $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "spec",
        array_merge($arParams, array(
            "IS_YS_MS" => "Y",
            "TAB_BLOCK" => $tabL,
            "TAB_LINK" => $arTab['LINK'],
            "FILTER_NAME" => "rz_main_spec_filter",
            "OFFERS_SORT_FIELD" => (!empty($arParams["LIST_PRICE_SORT"])) ? $arParams["LIST_PRICE_SORT"] : $arParams["OFFERS_SORT_FIELD"],
            "OFFERS_SORT_ORDER" => (!empty($arParams["LIST_PRICE_SORT"])) ? "asc" : $arParams["OFFERS_SORT_ORDER"],
            "PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
            'STORE_DISPLAY_TYPE' => $arParams['STORE_DISPLAY_TYPE'],
            "USE_PRICE_COUNT" => "N",
            "USE_PRICE_COUNT_" => $rz_b2_options["extended-prices-enabled"],
            "SHOW_STARS" => $rz_b2_options["block_show_stars"],
            "DISPLAY_FAVORITE" => Loader::includeModule('yenisite.favorite') && $rz_b2_options["block_show_favorite"] === "Y",
            "DISPLAY_ONECLICK" => Loader::includeModule('yenisite.oneclick') && $rz_b2_options["block_show_oneclick"] === 'Y',
            "DISPLAY_COMPARE_SOLUTION" => $rz_b2_options["block_show_compare"] == "Y",
            "SHOW_ARTICLE" => $rz_b2_options["block_show_article"],
            "SHOW_COMMENT_COUNT" => $rz_b2_options["block_show_comment_count"],
            "SHOW_GALLERY_THUMB" => $rz_b2_options["block_show_gallery_thumb"],
            "SHOW_BUY_BTN" => $rz_b2_options['block-buy_button'] === 'Y',
            'HOVER-MODE' => $arResult['HOVER-MODE'],

            'HIDE_ITEMS_NOT_AVAILABLE' => $arParamsCatalog['HIDE_ITEMS_NOT_AVAILABLE'],
            'HIDE_ITEMS_ZER_PRICE' => $arParamsCatalog['HIDE_ITEMS_ZER_PRICE'],
            'HIDE_ITEMS_WITHOUT_IMG' => $arParamsCatalog['HIDE_ITEMS_WITHOUT_IMG'],
            'SHOW_HOVER_PHOTO' => $rz_b2_options['product-hover-effect'] == 'switch_photo',
        )),
        $component
    );
    ?>
    <?
else: ?>
    <?
    $uniqID = 'special-blocks-' . $this->randString();
    ?>
    <div class="special-blocks drag-section sSpecialBlocks" data-order="<?=$rz_b2_options['order-sSpecialBlocks']?>" id="<?= $uniqID ?>">
        <?
        if (strtolower($_REQUEST['ajax_basket']) != 'y') {
            $frameSpec = $this->createFrame(false, $uniqID)->begin(CRZBitronic2Composite::insertCompositLoader());
            include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/include/debug_info_dynamic.php';
        }
        //	<!-- combo-blocks can have "tabs" or "full" class
        //	data-mode contains name of radiogroup for switching mode
        //	same value is used for local storage checks -->
        ?>
        <div class="container combo-blocks" id="special-blocks" data-sb-mode="<?= $arResult['SB-MODE'] ?>"
             data-sb-mode-def-expanded="<?= ($arParams['SB_FULL_DEFAULT'] == 'open') ? 'true' : 'false' ?>">
            <div class="combo-links">
                <? foreach ($arResult['TABS'] as $codeTab => $arTab):
                    $bActive = ($codeTab == $arParams['DEFAULT_TAB']);
                    $codeTab = strtolower($codeTab);
                    ?>
                    <span data-target="#tab_<?= $codeTab ?>"
                          class="combo-link <?= $arTab['CONTAINER_HEADER_CLASS'] ?> <?= ($rz_b2_options['block_home-specials_icons'] !== 'N' ? $arTab['CONTAINER_HEADER_ICON'] : '') ?><?= $bActive ? ' active' : '' ?>">
					<?= $arTab['HEADER'] ?><?
                        if ($rz_b2_options['block_home-specials_count'] !== 'N'): //($arParams['SHOW_TAB_CNT'] != 'N'):
                            if ($arTab['LINK']):
                                ?> <a href="<?= $arTab['LINK'] ?>" class="i-number"><?= $arTab['COUNT'] ?></a><?
                            else:
                                ?> <span class="i-number"><?= $arTab['COUNT'] ?></span><?
                            endif;
                        endif ?>

				</span>
                <? endforeach ?>
            </div>
            <?
            $catalogClass = 'catalog blocks special-blocks-carousel';
            if ($arParams['HIDE_ICON_SLIDER'] === 'Y') {
                $catalogClass .= ' thumbs-disabled';
            }
            $isTab = $arResult['SB-MODE'] == 'tabs';
            $i = 0;
            foreach ($arResult['TABS'] as $codeTab => $arTab):
                if ($arResult['SB-MODE'] != 'tabs' && $arParams['SB_FULL_DEFAULT'] == 'open') {
                    $bActive = true;
                } else {
                    $bActive = ($codeTab == $arParams['DEFAULT_TAB']);
                }
                $codeTab = strtolower($codeTab); ?>
                <div class="combo-target <?= $bActive ? 'shown' : '' ?><?= (!$isTab ? ' wow fadeIn' : '') ?>"
                     id="tab_<?= $codeTab ?>">
                    <div class="combo-header <?= $arTab['CONTAINER_HEADER_CLASS'] ?> <?= ($rz_b2_options['block_home-specials_icons'] !== 'N' ? $arTab['CONTAINER_HEADER_ICON'] : '') ?>">
                        <?= $arTab['HEADER'] ?><?
                        if ($rz_b2_options['block_home-specials_count'] !== 'N'): //($arParams['SHOW_TAB_CNT'] != 'N'):
                            if ($arTab['LINK']):
                                ?> <a href="<?= $arTab['LINK'] ?>" class="i-number"><?= $arTab['COUNT'] ?></a><?
                            else:
                                ?> <span class="i-number"><?= $arTab['COUNT'] ?></span><?
                            endif;
                        endif ?>
                    </div>
                    <div class="combo-target-content">
                        <div class="<?= $catalogClass ?>" id="special-<?= $codeTab ?>"
                             data-hover-effect="<?= $arResult['HOVER-MODE'] ?>" data-quick-view-enabled="false">
                            <?
                            $arJSParams = array(
                                'tab' => $codeTab,
                                'tabId' => 'tab_' . $codeTab,
                                'contentId' => 'special-' . $codeTab,
                                'edit_mode' => Tools::isEditModeOn() ? 'Y' : 'N'
                            );
                            $jsName = 'obTab' . ucfirst($codeTab);
                            ?>
                            <script type="text/javascript">
                                var <?=$jsName?>Load = function () {
                                    require([
                                            'back-end/ajax/main_spec',
                                            'libs/bootstrap/tooltip.min',
                                            'init/popups/initTooltips',
                                            'init/forms/initRatingStars',
                                        ], function () {
                                            RZB2.ajax.<?=$jsName?> = new RZB2.ajax.MainSpecTab(<?=CUtil::PhpToJSObject($arJSParams, false, true)?>);
                                            RZB2.ajax.<?=$jsName?>.Init();
                                        }
                                    );
                                }
                                if (typeof window.frameCacheVars !== "undefined") {
                                    BX.addCustomEvent("onFrameDataReceived", <?=$jsName?>Load);
                                } else {
                                    jQuery(window).load(<?=$jsName?>Load);
                                }
                                <?if ($bActive && $arParams['SB_FULL_DEFAULT'] !== 'open'):?>

                                serverSettings.sbModeDefExpanded = <?=$i?>;
                                <?endif?>

                            </script>
                        </div>
                    </div>
                </div><!-- .tab-target -->
                <? $i++; endforeach ?>
        </div><!-- /.container -->
        <?
        if (strtolower($_REQUEST['ajax_basket']) != 'y') {
            $frameSpec->end();
        } ?>
    </div><!-- /.special-blocks -->

    <?
endif;
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
