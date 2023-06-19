<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

// @var $moduleId
// @var $moduleCode
include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/module_code.php';

$this->setFrameMode(true);
$compositeLoader = CRZBitronic2Composite::insertCompositLoader();
$templateLibrary = array();
$currencyList = '';

if (!empty($arResult['CURRENCIES'])) {
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}
$templateData = array(
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList,
    'TURBO_YANDEX_LINK' => $arResult["PROPERTIES"]["TURBO_YANDEX_LINK"]["VALUE"],
);

if ($arResult['CATALOG'] && isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
    $templateData['OFFERS_KEYS'] = array();
    foreach ($arResult['OFFERS'] as $keyOffer => $arOffer) {
        $templateData['OFFERS_KEYS'][$arOffer['ID']] = $keyOffer;
    }
}

$arJsCache = CRZBitronic2CatalogUtils::getJSCache($component);
$_SESSION['RZ_DETAIL_JS_FILE'] = $arJsCache['file'];
$templateData['jsFile'] = $arJsCache['path'] . '/' . $arJsCache['idJS'];
$templateData['jsFullPath'] = $arJsCache['path-full'];

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
    'ID' => $strMainID,
    'PICT' => $strMainID . '_pict',
    'PICT_MODAL' => $strMainID . '_pict_modal',
    'PICT_FLY' => $strMainID . '_pict_fly',
    'DISCOUNT_PICT_ID' => $strMainID . '_dsc_pict',
    'STICKER_ID' => $strMainID . '_sticker',
    'BIG_SLIDER_ID' => $strMainID . '_big_slider',
    'BIG_IMG_CONT_ID' => $strMainID . '_bigimg_cont',
    'SLIDER_CONT_ID' => $strMainID . '_slider_cont',
    'SLIDER_LIST' => $strMainID . '_slider_list',
    'SLIDER_LEFT' => $strMainID . '_slider_left',
    'SLIDER_RIGHT' => $strMainID . '_slider_right',
    'OLD_PRICE' => $strMainID . '_old_price',
    'PRICE' => $strMainID . '_price',
    'DSC_PERC' => $strMainID . '_dsc_perc',
    'DISCOUNT_PRICE' => $strMainID . '_price_discount',
    'SLIDER_CONT_OF_ID' => $strMainID . '_slider_cont_',
    'SLIDER_MODAL_CONT_OF_ID' => $strMainID . '_slider_modal_cont_',
    'SLIDER_CONT_OF_INNER_ID' => $strMainID . '_slider_cont_of_inner_id',
    'SLIDER_CONT_OF_MODAL_INNER_ID' => $strMainID . '_slider_cont_of_modal_inner_id',
    'SLIDER_LIST_OF_ID' => $strMainID . '_slider_list_',
    'SLIDER_LEFT_OF_ID' => $strMainID . '_slider_left_',
    'SLIDER_RIGHT_OF_ID' => $strMainID . '_slider_right_',
    'QUANTITY' => $strMainID . '_quantity',
    'QUANTITY_DOWN' => $strMainID . '_quant_down',
    'QUANTITY_UP' => $strMainID . '_quant_up',
    'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
    'QUANTITY_LIMIT' => $strMainID . '_quant_limit',
    'BASKET_ACTIONS' => $strMainID . '_basket_actions',
    'AVAILABLE_INFO' => $strMainID . '_avail_info',
    'BUY_LINK' => $strMainID . '_buy_link',
    'BUY_ONECLICK' => $strMainID . '_buy_oneclick',
    'ADD_BASKET_LINK' => $strMainID . '_add_basket_link',
    'COMPARE_LINK' => $strMainID . '_compare_link',
    'FAVORITE_LINK' => $strMainID . '_favorite_link',
    'REQUEST_LINK' => $strMainID . '_request_link',
    'PROP' => $strMainID . '_prop_',
    'PROP_DIV' => $strMainID . '_skudiv',
    'DISPLAY_PROP_DIV' => $strMainID . '_sku_prop',
    'OFFER_GROUP' => $strMainID . '_set_group_',
    'BASKET_PROP_DIV' => $strMainID . '_basket_prop',
    'ARTICUL' => $strMainID . '_articul',
    'PRICE_ADDITIONAL' => $strMainID . '_price_additional',
    'PRICE_ACTIONS' => $strMainID . '_price_actions',
    'PRICE_BONUS' => $strMainID . '_price_bonus',
    'SUBSCRIBE_BTN' => $strMainID . '_subscribe_btn',

    //SKU
    'SKU_TABLE' => $strMainID . '_sku_table',
    'AVAIBILITY_EXPANDED' => 'catalog_store_amount_div_detail_' . $arResult['ID'],
);
$arItemCLASSes = array(
    'LINK' => $strMainID . '_link',
);
$strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['strObName'] = $strObName;

$strTitle = (
!empty($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"])
    ? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
    : $arResult['NAME']
);
$strAlt = (
!empty($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"])
    ? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
    : $arResult['NAME']
);

$bUseBrands = ('Y' == $arParams['BRAND_USE']);

if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
    $arOffer = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']];
    $availableOnRequest = (empty($arOffer['MIN_PRICE']) || $arOffer['MIN_PRICE']['VALUE'] <= 0);
    $canBuy = $arOffer['CAN_BUY'];
    unset($arOffer);
} else {
    $availableOnRequest = (empty($arResult['MIN_PRICE']) || $arResult['MIN_PRICE']['VALUE'] <= 0);
    $availableOnRequest = $arResult['bOffers'] && empty($arResult['OFFERS']) ? false : $availableOnRequest;
    $canBuy = (!$availableOnRequest && $arResult['CAN_BUY']);
}

$productTitle = (
isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
    ? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
    : $arResult["NAME"]
);

$articul = (
$arResult['bOffers'] && $arResult['bSkuExt'] && !empty($arResult['JS_OFFERS'][$arResult['OFFERS_SELECTED']]['ARTICUL'])
    ? $arResult['JS_OFFERS'][$arResult['OFFERS_SELECTED']]['ARTICUL']
    : (
is_array($arResult['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])
    ? implode(' / ', $arResult['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])
    : $arResult['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']
)
);
if ('N' == $arParams['SHOW_ARTICLE']) {
    $articul = '';
}

$availableClass = (
!$canBuy && !$availableOnRequest
    ? 'out-of-stock'
    : (
$arResult['FOR_ORDER'] || $availableOnRequest
    ? 'available-for-order'
    : ''
)
);

$strAvailable = (
isset($availableOnRequest) && !$availableOnRequest
    ? (
$canBuy
    ? ($arResult['FOR_ORDER'] ? 'PreOrder' : 'InStock')
    : 'OutOfStock'
)
    : ''
);

$bCatchbuy = ($arParams['SHOW_CATCHBUY'] && $arResult['CATCHBUY']);
$bDiscountShow = (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF'] && $arParams['SHOW_OLD_PRICE'] == 'Y');
$bEmptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
$bBuyProps = ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$bEmptyProductProperties);
$bStores = $arParams["USE_STORE"] == "Y" && Bitrix\Main\ModuleManager::isModuleInstalled("catalog");
$bShowStore = $bStores; // for show stores popup on available status
$bExpandedStore = $arParams['PRODUCT_AVAILABILITY_VIEW'] == 'expanded';
$bTabsStore = $arParams['PRODUCT_AVAILABILITY_VIEW'] == 'tabs' && !$arParams['QUICK_VIEW'];
$bDifferentCharsAndDesc = $arParams['USE_DIFFERENT_TABS_FOR_CHARS'];
$bHasDescription = strlen(trim($arResult['DETAIL_TEXT'])) > 0;
$arParams['MANUAL_PROP'] = empty($arParams['MANUAL_PROP']) ? 'MANUAL' : $arParams['MANUAL_PROP'];
$bShowDocs = is_array($arResult["PROPERTIES"][$arParams['MANUAL_PROP']]['VALUE']);
$bShowVideo = is_array($arResult["PROPERTIES"]['VIDEO']['VALUE']);
$bShowVideoInSlider = $arParams['DETAIL_SHOW_VIDEO_IN_SLIDER'] && is_array($arResult["PROPERTIES"][$arParams['DETAIL_PROP_FOR_VIDEO_IN_SLIDER']]['VALUE']);
$bShowOneClick = $arParams['DISPLAY_ONECLICK'] && $arResult['CATALOG'] && (!$arResult['bOffers'] || $arResult['bSkuExt']);
$bShowEdost = CModule::IncludeModule('edost.catalogdelivery') && ($canBuy || $arResult['bOffers']);
$bReviewsItem = !empty($arResult['PROPERTIES'][$arParams['PROP_FOR_REVIEWS_ITEM']]['VALUE']) && !empty($arParams['IBLOCK_REVIEWS_ID']) && $arParams['SHOW_REVIEW_ITEM'];
$arResult['bTabs'] = $arResult['bTechTab']
    || $arParams['USE_REVIEW'] == 'Y'
    || $bShowVideo
    || $bShowDocs;

$bShowComplects = $arParams['DETAIL_SHOW_COMPLEKTS'] && !empty($arResult['ITEMS_IN_SET']);
?>
<?
if ($arParams['QUICK_VIEW']):
    ?>
    <div id="<?= $arItemIDs['ID'] ?>">
    <div class="title-h2"><?= $productTitle ?></div>
    <?
else:
    ?>
    <main class="container product-page" itemscope itemtype="http://schema.org/Product" data-page="product-page" id="<? echo $arItemIDs['ID']; ?>">
    <div class="main-header">
    <? if ($arParams['SHOW_PRINT']): ?>
    <a href="#" onclick="print()"
       class="print-link pseudolink-bd link-black pull-right flaticon-sheet"><?= GetMessage('BITRONIC2_PRINT') ?></a>
<? endif ?>
    <h1 itemprop="name"><?= $productTitle ?></h1>
    <?
endif;

$id = 'bxdinamic_BITRONIC2_detail_rating_' . $arResult['ID'];
?>
    </div>
    <div class="short-info-top actions">
        <div id="<?= $id ?>" class="info rating rating-w-comments" itemprop="aggregateRating" itemscope
             itemtype="http://schema.org/AggregateRating">
            <? $frame = $this->createFrame($id, false)->begin($compositeLoader);
            $frame->setAssetMode(\Bitrix\Main\Page\AssetMode::STANDARD) ?>
            <? if ($arParams['SHOW_STARS'] == 'Y'): ?>
                <? $APPLICATION->IncludeComponent("bitrix:iblock.vote", "detail", array(
                    "IBLOCK_TYPE" => $arResult['IBLOCK_TYPE_ID'],
                    "IBLOCK_ID" => $arResult['IBLOCK_ID'],
                    "ELEMENT_ID" => $arResult['ID'],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "MAX_VOTE" => "5",
                    "VOTE_NAMES" => array("1", "2", "3", "4", "5"),
                    "SET_STATUS_404" => "N",
                    "GAMIFICATION" => $arParams["SHOW_GAMIFICATION"]
                ),
                    $component,
                    array("HIDE_ICONS" => "Y")
                ); ?>
            <? endif ?>
            <? if ($arParams['USE_REVIEW'] != "N" && $arParams['USE_OWN_REVIEW'] != "N"): ?>

                <a href="<?= $arParams['QUICK_VIEW'] ? $arResult['DETAIL_PAGE_URL'] : '' ?>#form_comment"
                   class="comments write-review_top">
				<span class="read-feedback <? if (!$arParams['QUICK_VIEW']): ?>pseudo<? endif ?>link-bd link-black">
					<?= GetMessage('BITRONIC2_READ_REVIEWS') ?>

				</span><?
                    /* TODO (review posting doesn't clear cache on detail page)
                    <span itemprop="reviewCount"><?= $arResult['REVIEW_COUNT'] ?></span>
                    <?= \Yenisite\Core\Tools::rusQuantity($arResult['REVIEW_COUNT'], GetMessage('BITRONIC2_REVIEW')) ?>
                    (<span class="positive">+5</span>/<span class="negative">-3</span>)
                    */
                    ?><? if ($arParams['SHOW_GAMIFICATION']): ?>

                        <span class="be-first pseudolink-bd"><?= GetMessage('BITRONIC2_TOP_BE_FIRST') ?></span>
                    <? endif ?>

                </a>
            <? endif ?>
            <? $frame->end(); ?>
        </div><!-- /.info.rating -->
        <? if (\Yenisite\Core\Tools::isComponentExist('bitrix:asd.share.buttons') && $arParams['SHOW_SOCIAL_BUTTONS']):

            $arURL = array($arResult['~DETAIL_PAGE_URL'], $arResult['MORE_PHOTO'][0]['SRC_SMALL']);
            foreach ($arURL as &$url) {
                if (substr($url, 0, 1) == '/' && substr($url, 1, 1) != '/') {
                    $serverName = (defined('SITE_SERVER_NAME') && strlen(SITE_SERVER_NAME) > 0)
                        ? SITE_SERVER_NAME
                        : COption::GetOptionString('main', 'server_name', $GLOBALS['SERVER_NAME']);
                    $serverName = trim($serverName, '/');
                    $url = '//' . $serverName . $url;
                }
                if (substr($url, 0, 2) == '//') {
                    $url = 'http' . (\CMain::IsHTTPS() ? 's:' : ':') . $url;
                }
            }
            unset($url, $serverName);

            $templateData['OG']['image'] = $arURL[1];
            $templateData['OG']['image:width'] = 400;
            $templateData['OG']['image:height'] = 400;
            $templateData['OG']['description'] = TruncateText(strip_tags($arResult['~PREVIEW_TEXT'] ?: $arResult['~DETAIL_TEXT']), 250);

            $APPLICATION->IncludeComponent(
                "bitrix:asd.share.buttons",
                "detail",
                array(
                    "COMPONENT_TEMPLATE" => "detail",
                    "ASD_ID" => "",
                    "ASD_TITLE" => $arResult['~NAME'],
                    "ASD_URL" => $arURL[0],
                    "ASD_PICTURE" => $arURL[1],
                    "ASD_TEXT" => $arResult['~PREVIEW_TEXT'],
                    'TYPE_SHOW' => $arParams['TYPE_SHOW'],
                    //"ASD_LINK_TITLE" => GetMessage("RZ_RASSHARIT_V") . " #SERVICE#", //uncomment if you want to set this param from bitrix:catalog
                    "ASD_SITE_NAME" => "",
                    "ASD_INCLUDE_SCRIPTS" => array()
                ),
                $component,
                array("HIDE_ICONS" => "Y")
            ); ?>
        <? endif ?>
        <? if ($arParams['SHOW_ARTICLE'] !== 'N'): ?>
            <span class="info art<?= (empty($articul) ? ' hidden' : '') ?>"
                  id="<?= $arItemIDs['ARTICUL'] ?>"><?= $arResult['PROPERTIES'][$arParams['ARTICUL_PROP']]['NAME'] ?>
                : <strong<? if (!empty($articul)): ?> itemprop="productID"<? endif ?>><?= $articul ?></strong></span>
        <? endif ?>
    </div><!-- /.short-info.actions -->
    <div class="row">
        <div class="col-xs-12 product-main" data-product-availability="<?= $arParams['PRODUCT_AVAILABILITY_VIEW'] ?>">
            <div class="product-photos<?= ($bCatchbuy ? ' has-timer' : '') ?> <?=$arResult['MORE_PHOTO_COUNT'] > 1 ? '' : 'no-thumbs' ?>" id="photo-block">
                <div class="stickers">
                    <? if (is_array($arResult['BRAND_LOGO']) && !empty($arResult['BRAND_LOGO']['IMG']['src'])): ?>
                        <a itemscope itemtype="http://schema.org/ImageObject" href="<?= $arResult['BRAND_LOGO']['URL'] ?>" class="brand">
                            <meta itemprop="brand" content="<?= $arResult['BRAND_LOGO']['ALT'] ?>"/>
                            <img itemprop="contentUrl" title="<?= $arResult['BRAND_LOGO']['ALT'] ?>" class="lazy"
                                 data-original="<?= $arResult['BRAND_LOGO']['IMG']['src'] ?>"
                                 src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                 alt="<?= $arResult['BRAND_LOGO']['ALT'] ?>">
                        </a>
                    <? endif ?>
                    <?$frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader());?>
                        <?= $arResult['yenisite:stickers'] ?>
                    <?$frame->end()?>
                </div>
                <?
                /* TODO
                <div class="info-popups">
                    ................
                </div>
                */
                ?>
                <?
                include 'include/gallery.php';
                if (!empty($arResult['PROPERTIES']['ID_3D_MODEL']['VALUE']) && $arParams['SHOW_VIEW3D'] && !$arParams['QUICK_VIEW']):?>

                    <div class="hide view3d">
                        <? if ($arParams['TYPE_3D'] != 'MEGAVIZER' && $arParams['TYPE_SEARCH_BY'] == 'NAME_PRODUCT' && $arParams['TYPE_SEARCH'] == 'AUTO') {
                            $arResult["PROPERTIES"]["ID_3D_MODEL"]["VALUE"] = $arResult['NAME'];
                        } ?>
                        <? $APPLICATION->IncludeComponent("yenisite:bitronic.3Dmodel", ".default", array(
                            "BUT_OR_PLAY" => "BUTTON",
                            "ID_REVIEW" => $arResult["PROPERTIES"]["ID_3D_MODEL"]["VALUE"],
                            "ID" => $arResult["PROPERTIES"]["ID_3D_MODEL"]["VALUE"],
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "360000",
                            "FULLSCREEN" => "Y",
                            "ZOOM" => "Y",
                            "ANAGLYPH" => "Y",
                            "AUTOPLAY" => "Y",
                            "SIZE" => "BIG",
                            "HEIGHT" => "",
                            "WIDTH" => "",
                            "DESIGN" => "1",
                            "BUTTON_TEXT" => "",
                            "FULLSCREEN2" => "Y",
                            "ZOOM2" => "Y",
                            "ANAGLYPH2" => "Y",
                            'TYPE_SERVICE' => $arParams['TYPE_3D'] ? $arParams['TYPE_3D'] : 'REVIEW3',
                            'TYPE_AUTO_SEARCH' => $arParams['TYPE_SEARCH_BY'],
                            'TYPE_SEARCH_ITEM' => $arParams['TYPE_SEARCH'],
                        ),
                            $component
                        ); ?>

                    </div>
                    <button type="button" class="btn-silver view3d disabled">
                        <span class="text">3D<? //=GetMessage('BITRONIC2_3DVIEW')
                            ?></span>
                    </button>
                <? endif ?>
                <? if ($arParams['QUICK_VIEW']): ?>
                    <a href="<?= $arResult['DETAIL_PAGE_URL'] ?>" class="link go2detailed <?= $arItemCLASSes['LINK'] ?>"
                       title="<?= GetMessage('BITRONIC2_GO_TO_DETAIL_TITLE') ?>">
                        <span class="text"><?= GetMessage('BITRONIC2_GO_TO_DETAIL') ?></span>
                    </a>
                <? endif ?>

            </div><!-- /.product-photos -->
            <? if (!empty($arResult['ACTION_DATA'])): ?>
                <? foreach ($arResult['ACTION_DATA'] as $arAction) {
                    include 'action_banner.php';
                } ?>
            <? endif ?>
            <?
            if (!$arParams['QUICK_VIEW'] && $arParams['SHOW_SHORT_INFO_UNDER_IMAGE']) include 'short_info.php';
            ?>

            <div class="buy-block-origin">
                <!-- to switch between "in-stock" and "out-of-stock" modes, add or remove class
                 "out-of-stock" on this wrap -->
                <div class="buy-block-wrap">
                    <div class="buy-block-main<? if (!($bShowEdost && !$arParams['EDOST_PREVIEW'])): ?> __slim<? endif ?>">
                        <div class="buy-block-content">
                            <div class="product-name" itemprop="name"><?= $productTitle ?></div>
                            <div itemscope itemtype="http://schema.org/ImageObject" class="product-main-photo">
                                <img itemprop="contentUrl"
                                    id="<?= $arItemIDs['PICT_FLY'] ?>"
                                     src="<?= CResizer2Resize::ResizeGD2($arResult['MORE_PHOTO'][0]['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_FLY_BLOCK']) ?>"
                                     alt="<?= $strAlt ?>" title="<?= $strTitle ?>">
                            </div>
                            <div class="move">
                                <form action="#" method="post" class="product-options"
                                      id="<? echo $arItemIDs['PROP_DIV']; ?>">
                                    <? if ($bBuyProps) {
                                        include 'buy_props.php';
                                    } //@var $emptyProductProperties?>
                                    <? if ($arResult['bSkuExt']) {
                                        include 'sku_extended.php';
                                    } ?>
                                    <? if (!$arResult['bSkuSimple']): ?>
                                        <div class="quantity-counter">
                                            <?
                                            $availableID = &$arItemIDs['AVAILABLE_INFO'];
                                            $availableFrame = true;
                                            $availableForOrderText = &$arResult['PROPERTIES']['RZ_FOR_ORDER_TEXT']['VALUE'];
                                            $availableItemID = &$arResult['ID'];
                                            $availableMeasure = &$arResult['CATALOG_MEASURE_NAME'];
                                            $availableQuantity = &$arResult['CATALOG_QUANTITY'];
                                            $availableStoresPostfix = 'detail';
                                            $availableSubscribe = $arResult['CATALOG_SUBSCRIBE'];
                                            $bShowEveryStatus = ($arResult['bOffers'] && $arResult['bSkuExt']);
                                            include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/availability_info.php';

                                            if (empty($availableOnRequest) && 'Y' == $arParams['USE_PRODUCT_QUANTITY']):
                                                ?>
                                                <div class="inner-quan-wrap">
                                                <span data-tooltip
                                                      data-placement="right"
                                                      title="<?= $arResult['CATALOG_MEASURE_NAME'] ?>">
													<!-- parent must have class .quantity-counter! -->
													<button type="button"
                                                            class="btn-silver quantity-change decrease disabled"
                                                            id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>"><span
                                                                class="minus"></span></button>
													<input type="text" class="quantity-input textinput"
                                                           id="<? echo $arItemIDs['QUANTITY']; ?>"
                                                           value="<?= $arResult['CATALOG_MEASURE_RATIO'] ?>">
													<button type="button" class="btn-silver quantity-change increase"
                                                            id="<? echo $arItemIDs['QUANTITY_UP']; ?>"><span
                                                                class="plus"></span></button>
												</span>
                                                </div><?
                                            endif ?>

                                        </div>
                                    <? endif ?>
                                </form><!-- /.product-options -->
                                <div class="price-wrap<?= (empty($availableOnRequest) ? '' : ' hide') ?>"<?
                                if (!$arResult['bOffers']):?> itemprop="offers" itemscope itemtype="http://schema.org/Offer"<?
                                endif ?>>
                                    <div class="price-values">
                                        <?$frame = $this->createFrame()->begin($compositeLoader);?>
                                        <? if ($arParams['SHOW_PRICE_UPDATED']): ?>
                                        <div class="price-update" data-tooltip="" data-placement="bottom"
                                             title="<?= GetMessage('BITRONIC2_UPDATE_DATE') ?> <?= $arResult["DISPLAY_UPDATE_DATE"] ?>">
                                            <? endif ?>
                                            <span class="text"><?
                                                echo GetMessage('BITRONIC2_PRICE');
                                                ?><span class="price-old" id="<?= $arItemIDs['OLD_PRICE'] ?>"><?
                                                    if ($bDiscountShow):?><?= CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['MIN_PRICE']['CURRENCY'], $arResult['MIN_PRICE']['VALUE'], $arResult['MIN_PRICE']['PRINT_VALUE']) ?><? endif ?><?
                                                    ?></span><?
                                                ?></span>
                                            <div class="price" id="<? echo $arItemIDs['PRICE']; ?>">
                                                <? if (!$arResult['bOffers']): ?>
                                                    <meta itemprop="price"
                                                          content="<?= $arResult['MIN_PRICE']['DISCOUNT_VALUE'] ?>">
                                                    <meta itemprop="priceCurrency"
                                                          content="<?= $arResult['MIN_PRICE']['CURRENCY'] ?>"><?
                                                    if (!empty($strAvailable)): ?>

                                                        <link itemprop="availability"
                                                              href="http://schema.org/<?= $strAvailable ?>"><?
                                                    endif;
                                                endif ?>

                                                <?= ($arResult['bOffers'] && $arResult['bSkuSimple'] && $arResult['bOffersNotEqual']) ? GetMessage('BITRONIC2_OFFERS_FROM') : '' ?>

                                                <?= CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['MIN_PRICE']['CURRENCY'], $arResult['MIN_PRICE']['DISCOUNT_VALUE'], $arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE']) ?>

                                                <? if (!$arResult['bOffers']): ?>
                                                    <meta itemprop="price"
                                                          content="<?= $arResult['MIN_PRICE']['DISCOUNT_VALUE'] ?>">
                                                    <meta itemprop="priceCurrency"
                                                          content="<?= $arResult['MIN_PRICE']['CURRENCY'] ?>"><?
                                                    if (!empty($strAvailable)): ?>

                                                        <link itemprop="availability"
                                                              href="http://schema.org/<?= $strAvailable ?>"><?
                                                    endif;
                                                endif ?>
                                            </div>
                                            <? if (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF'] && 'Y' == $arParams['SHOW_DISCOUNT_PERCENT'] && 0 < Round($arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'])): ?>
                                                <div class="sticker price-w-discount">
												<span class="text">
													-<?= Round($arResult['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']) ?>%
												</span>
                                                </div>
                                            <? endif;
                                            if ($arParams['SHOW_PRICE_UPDATED']): ?>
                                        </div>
                                    <? endif ?>
                                        <? $frame->end() ?>
                                        <div id="<?= $arItemIDs['PRICE_ADDITIONAL'] ?>"
                                             class="additional-price-container">
                                            <? $frame = $this->createFrame($arItemIDs['PRICE_ADDITIONAL'], false)->begin(CRZBitronic2Composite::insertCompositLoader()) ?>
                                            <?
                                            $arItemPrices = $arResult['PRICES'];
                                            $minPriceId = $arResult['MIN_PRICE']['PRICE_ID'];
                                            $arItemMatrix = $arResult['PRICE_MATRIX'];
                                            $measureRatio = $arResult['CATALOG_MEASURE_RATIO'];
                                            $measureName = $arResult['CATALOG_MEASURE_NAME'];
                                            include 'additional_prices.php';
                                            unset($arItemMatrix, $arItemPrices, $minPriceId, $measureName, $measureRatio);
                                            ?>
                                            <? $frame->end() ?>
                                        </div>
                                        <?
                                        $frame = $this->createFrame()->begin('');
                                        if (is_array($arResult['PROPERTIES']['SERVICE'])
                                            && !empty($arResult['PROPERTIES']['SERVICE']['VALUE'])
                                            && !$arParams['QUICK_VIEW']
                                        ):
                                            ?>
                                            <div class="additionals-price">
                                                <span class="text"><?= GetMessage('BITRONIC2_ADDITIONALS_PRICE') ?></span>
                                                <div class="price additional">
                                                    <?= CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['MIN_PRICE']['CURRENCY'], 0, '0') ?>
                                                </div>
                                            </div>
                                        <? endif;
                                        $frame->end();
                                        ?>
                                    </div>
                                    <? if ($arParams['VBC_BONUS']): ?>
                                        <div class="bonus" id="<?= $arItemIDs['PRICE_BONUS'] ?>">
                                            <?
                                            $frame = $this->createFrame($arItemIDs['PRICE_BONUS'], false)->begin($compositeLoader);
                                            $APPLICATION->IncludeComponent(
                                                "vbcherepanov:vbcherepanov.bonuselement",
                                                "element",
                                                array(
                                                    "CACHE_TIME" => "0",
                                                    "CACHE_TYPE" => "N",
                                                    "ELEMENT" => $arResult,
                                                    "PREFIX" => $bShowFrom ? GetMessage('BITRONIC2_OFFERS_FROM') . ' ' : '',
                                                    "ONLY_NUM" => "Y"
                                                ),
                                                $component
                                            );
                                            $frame->end();
                                            ?>
                                        </div>
                                    <? endif ?>
                                </div><!-- .price-wrap -->
                                <div class="actions-with-count actions"><?
                                    if ($arParams['DISPLAY_FAVORITE'] && !$arResult['bSkuSimple']):?>

                                    <button
                                            type="button"
                                            class="action favorite with-icon toggleable"
                                            id="<?= $arItemIDs['FAVORITE_LINK'] ?>"
                                            data-favorite-id="<?= $arResult['ID'] ?>"
                                    >
                                        <i class="flaticon-heart3" id="bxdinamic_detail_favorite_count">#DETAIL_PRODUCT_FAVORITE_COUNT#</i>
                                        <span class="text when-not-toggled"><?= GetMessage('BITRONIC2_ADD_FAVORITE') ?></span>
                                        <span class="text when-toggled"><?= GetMessage('BITRONIC2_ADDED_FAVORITE') ?></span>
                                        </button><?
                                    endif;

                                    if ($arParams['DISPLAY_COMPARE_SOLUTION']):?>

                                    <button
                                            type="button"
                                            class="action compare with-icon toggleable"
                                            id="<?= $arItemIDs['COMPARE_LINK'] ?>"
                                            data-compare-id="<?= $arResult['ID'] ?>"
                                    >
                                        <i class="flaticon-balance3"></i>
                                        <span class="text when-not-toggled"><?= GetMessage('BITRONIC2_ADD_COMPARE') ?></span>
                                        <span class="text when-toggled"><?= GetMessage('BITRONIC2_ADDED_COMPARE') ?></span>
                                        </button><?
                                    endif ?>

                                </div>
                                <div class="price-action" id="<?= $arItemIDs['PRICE_ACTIONS'] ?>">
                                    <? $frame = $this->createFrame($arItemIDs['PRICE_ACTIONS'], false)->begin($compositeLoader) ?>
                                    <? if (!$arParams['QUICK_VIEW'] && \Bitrix\Main\Loader::IncludeModule("yenisite.feedback") && (!$arResult['bOffers'] || $arResult['bSkuExt']) && $arResult['CATALOG']): // TODO LITE ?>
                                        <? if ($arParams['PRICE_LOWER'] != 'N' && !$availableOnRequest): ?>
                                            <div class="price-action__info">
                                                <a class="inform-when-price-drops pseudolink-bd link-black"
                                                   data-toggle="modal"
                                                   data-target="#modal_inform-when-price-drops"
                                                   data-product="<?= $arResult['ID'] ?>"
                                                   data-price="<?= $arResult['MIN_BUY_PRICE']['DISCOUNT_VALUE'] ?>"
                                                   data-price_type="<?= $arResult['MIN_BUY_PRICE']['PRICE_ID'] ?>"
                                                   data-currency="<?= $arResult['MIN_BUY_PRICE']['CURRENCY'] ?>"
                                                >
                                                    <span class="svg-wrap">
                                                       <svg>
							                                <use xlink:href="#discount"></use>
						                                </svg>
                                                    </span>
                                                    <span class="text"><?= GetMessage("RZ_SOOBSHIT_O_SNIZHENII_TCENI") ?></span>
                                                </a>
                                            </div>
                                        <? endif ?>
                                        <? if ($arParams['FOUND_CHEAP'] != 'N' && !$availableOnRequest): ?>
                                            <div class="price-action__info">
                                                <a class="cry-for-price pseudolink-bd link-black" data-toggle="modal"
                                                   data-product="<?= $arResult['ID'] ?>"
                                                   data-price="<?= $arResult['MIN_BUY_PRICE']['DISCOUNT_VALUE'] ?>"
                                                   data-price_type="<?= $arResult['MIN_BUY_PRICE']['PRICE_ID'] ?>"
                                                   data-currency="<?= $arResult['MIN_BUY_PRICE']['CURRENCY'] ?>"
                                                   data-target="#modal_cry-for-price">
                                                    <span class="svg-wrap">
                                                        <svg>
                                                            <use xlink:href="#complain"></use>
                                                        </svg>
                                                    </span>
                                                    <span class="text"><?= GetMessage("RZ_POZHALOVATSYA_NA_TCENU") ?></span>
                                                </a>
                                            </div>
                                        <? endif ?>
                                    <? endif ?>
                                    <? $frame->end(); ?>
                                </div>
                                <div class="buy-buttons-wrap" id="<? echo $arItemIDs['BASKET_ACTIONS']; ?>">
                                    <? $frame = $this->createFrame($arItemIDs['BASKET_ACTIONS'], false)->begin(CRZBitronic2Composite::insertCompositLoader());
                                    /* TODO
                                    include '_/buttons/action_to-wait.html';
                                    */ ?>
                                    <div class="buy-buttons-wrap<? if ($availableOnRequest && (!$arResult['bOffers'] || $arResult['bSkuExt'])): ?> on-request<? endif ?>">
                                        <button type="button" class="btn-big buy on-request btn-main"
                                                id="<?= $arItemIDs['REQUEST_LINK']; ?>" data-toggle="modal"
                                                data-target="#modal_contact_product"
                                                data-product-id="<?= $arResult['ID'] ?>"<?= ($arResult['bOffers'] && $arResult['bSkuExt'] ? ' data-offer-id="' . $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'] . '"' : '') ?>
                                                data-measure-name="<?= ($arResult['bOffers'] && $arResult['bSkuExt'] ? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['CATALOG_MEASURE_NAME'] : $arResult['CATALOG_MEASURE_NAME']) ?>">
                                            <i class="flaticon-speech90"></i>
                                            <span class="text"><?= COption::GetOptionString($moduleId, 'button_text_request') ?></span>
                                        </button>
                                        <? if (($arResult['CATALOG_SUBSCRIBE'] == 'Y' && $arParams['ELEMENT_EXIST'] == 'Y' && $availableClass == 'out-of-stock') || ($arResult['CATALOG_SUBSCRIBE'] == 'Y' && !empty($arResult['OFFERS']) && !$arResult['bSkuSimple'])): ?>
                                            <button id="<?= $arItemIDs['SUBSCRIBE_BTN']; ?>" type="button" class="btn-big to-waitlist btn-silver"
                                                    data-product="<?= $arResult['ID'] ?>" data-toggle="modal"
                                                    data-target="#modal_subscribe_product">
                                                <i class="flaticon-mail9"></i>
                                                <span class="text"><?= COption::GetOptionString($moduleId, 'button_text_subscribe', GetMessage('B2_BUTTON_TEXT_SUBSCRIBE')) ?></span>
                                            </button>
                                        <?endif?>
                                        <? if($arResult['CAN_BUY'] || !empty($arResult['OFFERS'])): ?>
                                            <button type="button"
                                                    class="btn-big buy btn-main <?= ($canBuy || !empty($arResult['OFFERS']) ? '' : ' action disabled') ?>"
                                                    id="<?= $arItemIDs['BUY_LINK']; ?>"
                                                    <? if (!$bBuyProps || $emptyProductProperties): ?>data-product-id="<?= $arResult['ID'] ?>"<? endif ?>
                                                <?= ($arResult['bOffers'] && $arResult['bSkuExt'] ? ' data-offer-id="' . $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'] . '"' : '') ?>>
                                                <i class="flaticon-shopping109"></i>
                                                <span class="text"><?= COption::GetOptionString($moduleId, 'button_text_buy') ?></span>
                                                <span class="text in-cart"><?= COption::GetOptionString($moduleId, 'button_text_incart') ?></span>
                                            </button>
                                        <? endif ?>
                                        <? if ($bShowOneClick && !$availableOnRequest && !$bBuyProps): ?>
                                            <div class="one-click-wrap">
                                                <span class="text"><?= GetMessage('BITRONIC2_OR') ?></span>
                                                <button id="<?= $arItemIDs['BUY_ONECLICK'] ?>" type="button"
                                                        class="action one-click-buy <?= ($canBuy || !empty($arResult['OFFERS']) ? '' : ' disabled') ?>"
                                                        data-toggle="modal" data-target="#modal_quick-buy"
                                                        data-id="<?= $arResult['ID'] ?>"
                                                        data-props="<?= \Yenisite\Core\Tools::GetEncodedArParams($arParams['OFFER_TREE_PROPS']) ?>">
                                                    <i class="flaticon-shopping220"></i>
                                                    <span class="text"><?= GetMessage('BITRONIC2_ONECLICK') ?></span>
                                                </button>
                                            </div>
                                        <? endif ?>
                                        <?
                                        /* TODO
                                        <button type="button" class="btn-big to-waitlist btn-silver">
                                            ................
                                        </button>
                                        */
                                        ?>
                                    </div>
                                    <? $frame->end() ?>
                                </div>
                                <? if ($bExpandedStore && $bShowStore): ?>
                                    <? if ($availableClass == 'in-stock' || empty($availableClass) || $arResult['bOffers']): ?>
                                        <div class="availability <?= empty($availableClass) ? '' : $availableClass ?>"
                                             id="catalog_store_amount_div_<?= $availableStoresPostfix ?>_<?= $arResult['ID'] ?>">
                                            <script type="text/javascript">
                                                require(['init/initMaps'], function () {
                                                    b2.init.maps();
                                                });
                                                function initExpandedStores() {
                                                    if (typeof RZB2_initCommonHandlers != 'undefined') {
                                                        RZB2_initCommonHandlers.GetStoreContent('#<?=$arItemIDs['AVAIBILITY_EXPANDED']?>',<?=$arResult['ID']?>, '<?=$availableStoresPostfix?>');
                                                    } else {
                                                        setTimeout(initExpandedStores, 1000);
                                                    }
                                                }
                                                ;
                                                setTimeout(initExpandedStores, 600);
                                            </script>
                                        </div><!-- /.availability -->
                                    <? endif ?>
                                <? endif; ?>
                            </div><!-- /.move -->
                        </div><!-- /.buy-block-content -->
                        <?
                        if ($arParams['QUICK_VIEW']) {
                            echo '
							</div><!-- /.buy-block-main -->
						</div><!-- .buy-block-wrap -->
					</div><!-- .buy-block-origin -->
				</div><!-- /.col-xs-12 -->
			</div><!-- /.row -->'; ?>
                            <? if ($arParams['QUICK_SHOW_CHARS'] == 'Y'): ?>
                                <div class="row characteristics">
                                    <div class="col-xs-12">
                                        <? include 'characteristics.php' ?>
                                    </div>
                                </div>
                            <? endif ?>
                            <?
                            if ($arResult['bSkuSimple']) {
                                include 'sku_simple.php';
                            }
                            echo '</div><!-- /#' . $arItemIDs['ID'] . ' -->';

                            include 'js_params.php';
                            return;
                        }
                        ?>
                        <div class="buy-block-footer"
                             id="<?= ($id = 'buy-block-footer') ?>"><? $frame = $this->createFrame($id, false)->begin('') ?>

                            <? if ($bShowEdost && !$arParams['EDOST_PREVIEW']): ?>
                                <button type="button" class="action calc-delivery"
                                        <? //TODO data-toggle="modal" data-target="#modal_calc-delivery"
                                        ?>data-id="<?= (isset($arResult['OFFERS'][0]['ID']) ? $arResult['OFFERS'][0]['ID'] : $arResult['ID']) ?>"
                                        data-name="<?= str_replace(array('"', "'"), '&quot;', $arResult['NAME']) ?>"
                                >
                                    <i class="flaticon-calculator2"></i>
                                    <span class="text"><?= GetMessage('BITRONIC2_CALC_DELIVERY') ?></span>
                                </button>
                            <? endif ?>
                            <?
                            /* TODO
                            <button type="button" class="action use-credit">
                                ................
                            </button>
                            */
                            ?><? $frame->end() ?>
                        </div>
                    </div><!-- /.buy-block-main -->
                    <?if ($bShowComplects):?>
                        <?include "complects.php"?>
                    <?endif?>
                    <?
                    $frame = $this->createFrame()->begin('');

                    if ($bShowEdost && $arParams['EDOST_PREVIEW']):?>

                        <div class="buy-block-additional edost">
                            <span id="edost_catalogdelivery_inside_city_head"><?= GetMessage('BITRONIC2_DELIVERY') ?> <?= GetMessage('BITRONIC2_IN') ?></span>
                            <span id="edost_catalogdelivery_inside_city"></span>
                            <div id="edost_catalogdelivery_inside"
                                 data-id="<?= (isset($arResult['OFFERS'][0]['ID']) ? $arResult['OFFERS'][0]['ID'] : $arResult['ID']) ?>"
                                 data-name="<?= str_replace(array('"', "'"), '&quot;', $arResult['NAME']) ?>">
                            </div>
                            <div id="edost_catalogdelivery_inside_detailed" class="hidden-xs"></div>
                            <script type="application/javascript">BX.ready(function () {
                                    var spinner = new RZB2.ajax.spinner($('#edost_catalogdelivery_inside'));
                                    spinner.Start();
                                });</script>
                        </div>
                        <?
                    endif;

                    if (is_array($arResult['PROPERTIES']['SERVICE']) && !empty($arResult['PROPERTIES']['SERVICE']['VALUE']) && ($canBuy || $arResult['bOffers'])):
                        global $arrServiceFilter;
                        $arrServiceFilter = array('ID' => $arResult['PROPERTIES']['SERVICE']['VALUE']);
                        ?>
                        <? $APPLICATION->IncludeComponent('bitrix:catalog.section', 'services',
                        array(
                            "SHOW_ALL_WO_SECTION" => "Y",
                            "FILTER_NAME" => 'arrServiceFilter',
                            "PAGE_ELEMENT_COUNT" => 0,
                            "IBLOCK_TYPE" => 'REFERENCES',
                            "IBLOCK_ID" => $arResult['PROPERTIES']['SERVICE']['LINK_IBLOCK_ID'],
                            "ADD_SECTIONS_CHAIN" => "N",
                            "DISPLAY_COMPARE_SOLUTION" => $arParams["USE_COMPARE"],
                            "PRICE_CODE" => $arParams["PRICE_CODE"],
                            "USE_PRICE_COUNT" => 'N',
                            "SHOW_PRICE_COUNT" => '1',
                            "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                            "PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
                            "USE_PRODUCT_QUANTITY" => "N",
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                            "CACHE_FILTER" => $arParams["CACHE_FILTER"],

                            "SECTION_ID" => 0,
                            'CONVERT_CURRENCY' => "Y",
                            'CURRENCY_ID' => $arParams['CONVERT_CURRENCY'] == 'Y' ? $arParams['CURRENCY_ID'] : $arResult['MIN_PRICE']['CURRENCY'],
                            'HIDE_NOT_AVAILABLE' => 'N',

                            // paginator:
                            'PAGER_SHOW_ALWAYS' => 'N',
                            'PAGER_DESC_NUMBERING' => 'N',
                            'PAGER_SHOW_ALL' => 'N',
                            'DISPLAY_TOP_PAGER' => 'N',
                            'DISPLAY_BOTTOM_PAGER' => 'N',
                            'PAGER_TITLE' => '',

                        ),
                        $component); ?>
                    <? endif;
                    $frame->end() ?>
                </div><!-- /.buy-block-wrap -->
            </div><!-- /.buy-block-origin -->
            <? $APPLICATION->IncludeComponent('bitrix:main.include', '', array("PATH" => SITE_DIR . "include_areas/catalog/benefits.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), $component, false) ?>
            <? if ($arResult['bTabs']): ?>
                <div id="product-info-sections"
                     class="product-info-sections combo-blocks <?= ($arParams['DETAIL_INFO_MODE'] == 'tabs') ? 'tabs' : 'full' ?>"
                     data-product-info-mode="<?= ($arParams['DETAIL_INFO_MODE'] == 'tabs') ? 'tabs' : 'full' ?>"
                     data-product-info-mode-def-expanded="<?= ($arParams['DETAIL_INFO_MODE'] == 'full' && $arParams['DETAIL_INFO_FULL_EXPANDED'] == 'Y') ? 'true' : 'false' ?>"
                >
                    <div class="combo-links">
                        <div class="links-wrap">
                            <? if ($bDifferentCharsAndDesc && $bHasDescription): ?>
                                <a href="#description" class="combo-link drag-section sPrInfDescription" data-order="<?=$arParams['ORDER_DETAIL_BLOCKS']['order-sPrInfDescription']?>">
                                    <i class="flaticon-newspapre"></i>
                                    <span class="text"><?= $arParams['TITLE_TAB_DESCRIPTION'] ?: GetMessage('BITRONIC2_DESCRIPTION') ?></span>
                                </a>
                            <? endif ?>

                            <? if ($arResult['bTechTab']): ?>
                                <a href="#characteristics" class="combo-link drag-section sPrInfCharacteristics" data-order="<?=$arParams['ORDER_DETAIL_BLOCKS']['order-sPrInfCharacteristics']?>">
                                    <i class="flaticon-newspapre"></i>
                                    <span class="text"><?= $arParams['TITLE_TAB_CHARACTERISTICS'] ?: GetMessage('BITRONIC2_CHARACTERISTICS') ?></span>
                                </a>
                            <? endif ?>

                            <? if ($arParams['USE_REVIEW'] == 'Y'): ?>
                                <a href="#comments" class="combo-link drag-section sPrInfComments" data-order="<?=$arParams['ORDER_DETAIL_BLOCKS']['order-sPrInfComments']?>">
                                    <i class="flaticon-speech90"></i>
                                    <span class="text"><?= $arParams['TITLE_TAB_REVIEWS'] ?: GetMessage('BITRONIC2_REVIEWS') ?></span><sup id="bxdinamic_detail_reviews_count">#COUNT_REVIEWS#</sup>
                                </a>
                            <? endif ?>
                            <? if ($bShowVideo): ?>
                                <a href="#videos" class="combo-link drag-section sPrInfVideos" data-order="<?=$arParams['ORDER_DETAIL_BLOCKS']['order-sPrInfVideos']?>">
                                    <i class="flaticon-movie16"></i>
                                    <span class="text"><?= $arParams['TITLE_TAB_VIDEO'] ?: GetMessage('BITRONIC2_VIDEO_REVIEWS') ?></span><sup><?= count($arResult["PROPERTIES"]['VIDEO']['VALUE']) ?></sup>
                                </a>
                            <? endif ?>
                            <? if ($bShowDocs): ?>
                                <a href="#documentation" class="combo-link drag-section sPrInfDocumentation" data-order="<?=$arParams['ORDER_DETAIL_BLOCKS']['order-sPrInfDocumentation']?>">
                                    <i class="flaticon-folded11"></i>
                                    <span class="text"><?= $arParams['TITLE_TAB_DOCUMENTATION'] ?: GetMessage('BITRONIC2_DOCUMENTATION') ?></span><sup><?= count($arResult["PROPERTIES"][$arParams['MANUAL_PROP']]['VALUE']) ?></sup>
                                </a>
                            <? endif ?>
                            <? if ($bShowStore && $bTabsStore): ?>
                                <? if ($availableClass == 'in-stock' || empty($availableClass) || $arResult['bOffers']): ?>
                                    <a href="#catalog_store_amount_div_<?= $availableStoresPostfix ?>_<?= $arResult['ID'] ?>"
                                       class="combo-link tab-store drag-section sPrInfAvailability" data-order="<?=$arParams['ORDER_DETAIL_BLOCKS']['order-sPrInfAvailability']?>">
                                        <i class="flaticon-folded11"></i>
                                        <span class="text"><?= $arParams['TITLE_TAB_STORES'] ?: GetMessage('BITRONIC2_STORES') ?></span><? //TODO <sup>3</sup> ?>
                                    </a>
                                <? endif ?>
                            <? endif ?>
                            <? if ($bReviewsItem): ?>
                                <a href="#review" class="combo-link drag-section sPrInfReview" data-order="<?=$arParams['ORDER_DETAIL_BLOCKS']['order-sPrInfReview']?>">
                                    <i class="flaticon-folded11"></i>
                                    <span class="text"><?= $arParams['TITLE_TAB_REVIEWS_ITEM'] ?: GetMessage('BITRONIC2_REVIEWS_ITEM') ?></span><sup><?= count($arResult['PROPERTIES'][$arParams['PROP_FOR_REVIEWS_ITEM']]['VALUE']) ?></sup>
                                </a>
                            <? endif ?>
                        </div>
                    </div>
                    <div class="tab-targets combo-content">
                        <? if ($bDifferentCharsAndDesc && $bHasDescription): ?>
                            <div class="combo-target shown description wow fadeIn drag-section sPrInfDescription" data-order="<?=$arParams['ORDER_DETAIL_BLOCKS']['order-sPrInfDescription']?>" id="description">
                                <div class="combo-header">
                                    <i class="flaticon-newspapre"></i>
                                    <span class="text"><?= $arParams['TITLE_TAB_DESCRIPTION'] ?: GetMessage('BITRONIC2_DESCRIPTION') ?></span>
                                </div>
                                <div class="combo-target-content">
                                    <? include 'include/description.php' ?>
                                </div><!-- .combo-target-content -->
                            </div><!-- /.tab-target#characteristics -->
                        <? endif ?>

                        <? if ($arResult['bTechTab']): ?>
                            <div class="combo-target shown characteristics wow fadeIn drag-section sPrInfCharacteristics" data-order="<?=$arParams['ORDER_DETAIL_BLOCKS']['order-sPrInfCharacteristics']?>" id="characteristics">
                                <div class="combo-header">
                                    <i class="flaticon-newspapre"></i>
                                    <span class="text"><?= $arParams['TITLE_TAB_CHARACTERISTICS'] ?: GetMessage('BITRONIC2_CHARACTERISTICS') ?></span>
                                </div>
                                <div class="combo-target-content">
                                    <? include 'characteristics.php' ?>
                                </div><!-- .combo-target-content -->
                            </div><!-- /.tab-target#characteristics -->
                        <? endif ?>

                        <? if ($arParams['USE_REVIEW'] == 'Y'): ?>
                            <div class="combo-target wow fadeIn comments<?= ($arParams['DETAIL_INFO_MODE'] == 'full' && $arParams['DETAIL_INFO_FULL_EXPANDED'] == 'Y') ? ' shown' : '' ?> drag-section sPrInfComments" data-order="<?=$arParams['ORDER_DETAIL_BLOCKS']['order-sPrInfComments']?>"
                                 id="comments">
                                <div class="combo-header">
                                    <i class="flaticon-speech90"></i>
                                    <span class="text"><?= $arParams['TITLE_TAB_REVIEWS'] ?: GetMessage('BITRONIC2_REVIEWS') ?></span><? // TODO <sup>3</sup>?>
                                </div>
                                <div class="combo-target-content">
                                    <? include 'own_reviews.php' ?>
                                    #DETAIL_RW_YM_API# <? // mask replace in /../../element.php ?>
                                </div><!-- .combo-target-content -->
                            </div><!-- /.tab-target#comments -->
                        <? endif ?>

                        <? if ($bShowVideo): ?>
                            <div class="combo-target wow fadeIn videos<?= ($arParams['DETAIL_INFO_MODE'] == 'full' && $arParams['DETAIL_INFO_FULL_EXPANDED'] == 'Y') ? ' shown' : '' ?> drag-section sPrInfVideos" data-order="<?=$arParams['ORDER_DETAIL_BLOCKS']['order-sPrInfVideos']?>"
                                 id="videos">
                                <div class="combo-header">
                                    <i class="flaticon-movie16"></i>
                                    <span class="text"><?= $arParams['TITLE_TAB_VIDEO'] ?: GetMessage('BITRONIC2_VIDEO_REVIEWS') ?></span><sup><?= count($arResult["PROPERTIES"]['VIDEO']['VALUE']) ?></sup>
                                </div>
                                <div class="combo-target-content">
                                    <? foreach ($arResult["PROPERTIES"]['VIDEO']['VALUE'] as $value):
                                        $value = is_numeric($value) ? array('path' => CFile::GetPath($value)) : $value;
                                        ?>
                                        <div class="video">
                                            <? if (isset($value['path']) && is_array($value)): ?>
                                                <? $APPLICATION->IncludeComponent("bitrix:player", "", Array(
                                                    "PATH" => $value['path'],
                                                    "PROVIDER" => "",
                                                    "WIDTH" => "800",
                                                    "HEIGHT" => "500",
                                                    "AUTOSTART" => "N",
                                                    "REPEAT" => "none",
                                                    "VOLUME" => "90",
                                                    "ADVANCED_MODE_SETTINGS" => "N",
                                                    "PLAYER_TYPE" => "auto",
                                                    "USE_PLAYLIST" => "N",
                                                    "STREAMER" => "",
                                                    "PREVIEW" => "",
                                                    "FILE_TITLE" => "",
                                                    "FILE_DURATION" => "",
                                                    "FILE_AUTHOR" => "",
                                                    "FILE_DATE" => "",
                                                    "FILE_DESCRIPTION" => "",
                                                    "MUTE" => "N",
                                                    "PLUGINS" => array(
                                                        0 => "",
                                                        1 => "",
                                                    ),
                                                    "ADDITIONAL_FLASHVARS" => "",
                                                    "PLAYER_ID" => "",
                                                    "BUFFER_LENGTH" => "10",
                                                    "ALLOW_SWF" => "N",
                                                    "SKIN_PATH" => "/bitrix/components/bitrix/player/mediaplayer/skins",
                                                    "SKIN" => "",
                                                    "CONTROLBAR" => "bottom",
                                                    "WMODE" => "opaque",
                                                    "LOGO" => "",
                                                    "LOGO_LINK" => "",
                                                    "LOGO_POSITION" => "none"
                                                ), $component); ?>
                                            <? else:
                                                //YouTube
                                                preg_match('#(\.be/|/embed/|/v/|/watch\?v=)([A-Za-z0-9_-]{5,11})#', $value, $matches); ?>
                                                <iframe src="//www.youtube.com/embed/<?= $matches[2] ?>"
                                                        allowfullscreen></iframe>
                                            <? endif; ?>
                                        </div><!-- /.video -->
                                    <? endforeach; ?>
                                </div>
                            </div><!-- /.tab-target#videos -->
                        <? endif ?>
                        <? if ($bShowDocs):
                            $arMimeFile = array(
                                "DOC" => array(
                                    "application/vnd.oasis.opendocument.text",
                                    "application/msword",
                                    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                                ),
                                "PDF" => array(
                                    "application/pdf",
                                ),
                            );
                            $arIconClass = array(
                                "DOC" => "flaticon-doc",
                                "PDF" => "flaticon-pdf17",
                                'DEFAULT' => 'flaticon-newspapre',
                            );
                            ?>
                            <div class="combo-target wow fadeIn documentation<?= ($arParams['DETAIL_INFO_MODE'] == 'full' && $arParams['DETAIL_INFO_FULL_EXPANDED'] == 'Y') ? ' shown' : '' ?> drag-section sPrInfDocumentation" data-order="<?=$arParams['ORDER_DETAIL_BLOCKS']['order-sPrInfDocumentation']?>"
                                 id="documentation">
                                <div class="combo-header">
                                    <i class="flaticon-folded11"></i>
                                    <span class="text"><?= $arParams['TITLE_TAB_DOCUMENTATION'] ?: GetMessage('BITRONIC2_DOCUMENTATION') ?></span><sup><?= count($arResult["PROPERTIES"][$arParams['MANUAL_PROP']]['VALUE']) ?></sup>
                                </div>
                                <div class="combo-target-content">
                                    <? foreach ($arResult["PROPERTIES"][$arParams['MANUAL_PROP']]['VALUE'] as $key => $value):
                                        $arFile = CFile::GetFileArray($value);
                                        $icoClass = '';
                                        foreach ($arMimeFile as $type => $arMime) {
                                            if (in_array($arFile['CONTENT_TYPE'], $arMime)) {
                                                $icoClass = $arIconClass[$type];
                                                break;
                                            }
                                        }
                                        $icoClass = !empty($icoClass) ? $icoClass : $arIconClass['DEFAULT'];
                                        ?>
                                        <div class="document-link">
                                            <a target="_blank" href="<?= htmlspecialcharsbx($arFile["SRC"]) ?>"
                                               class="link">
                                                <i class="<?= $icoClass ?>"></i>
                                                <span class="text"><?= (!empty($arFile['DESCRIPTION'])) ? $arFile['DESCRIPTION'] : $arFile['ORIGINAL_NAME'] ?></span>
                                                (<?= CFile::FormatSize($arFile['FILE_SIZE']) ?>)
                                            </a>
                                        </div>
                                    <? endforeach ?>
                                </div>
                            </div><!-- /.tab-target#documentation -->
                        <?endif;
                        if ($bShowStore && $bTabsStore):?>
                            <? if ($availableClass == 'in-stock' || empty($availableClass) || $arResult['bOffers']): ?>
                                <div class="combo-target availability markets-combo <?//wow?> fadeIn <?= ($arParams['DETAIL_INFO_MODE'] == 'full' && $arParams['DETAIL_INFO_FULL_EXPANDED'] == 'Y') ? ' shown' : '' ?>
<?= $arParams['DETAIL_INFO_MODE'] == 'tabs' ? ' tabs' : '' ?> <?= empty($availableClass) ? '' : $availableClass ?> drag-section sPrInfAvailability" data-order="<?=$arParams['ORDER_DETAIL_BLOCKS']['order-sPrInfAvailability']?>">
                                    <div class="combo-header">
                                        <i class="flaticon-folded11"></i>
                                        <span class="text"><?= $arParams['TITLE_TAB_STORES'] ?: GetMessage('MARKETS') ?></span><? //:TODO<sup>3</sup>
                                        ?>
                                    </div>
                                    <div class="combo-target-content"
                                         id="catalog_store_amount_div_<?= $availableStoresPostfix ?>_<?= $arResult['ID'] ?>">
                                        <script type="text/javascript">
                                            require(['init/initMaps'], function () {
                                                b2.init.maps();
                                            });
                                            function initTabStores() {
                                                if (typeof RZB2_initCommonHandlers != 'undefined' && typeof RZB2_initCommonHandlers.initTabStores == 'undefined') {
                                                    RZB2_initCommonHandlers.GetStoreContent('#<?=$arItemIDs['AVAIBILITY_EXPANDED']?>',<?=$arResult['ID']?>, '<?=$availableStoresPostfix?>');
                                                    RZB2_initCommonHandlers.initTabStores = true;
                                                } else {
                                                    if(typeof RZB2_initCommonHandlers == 'undefined' || typeof RZB2_initCommonHandlers.initTabStores == 'undefined') {
                                                        setTimeout(initTabStores, 1000);
                                                    }
                                                }
                                            }
                                            ;
                                            <?if ($arParams['DETAIL_INFO_MODE'] == 'full' && $arParams['DETAIL_INFO_FULL_EXPANDED'] == 'Y'):?>
                                            setTimeout(initTabStores, 600);
                                            <?elseif ($arParams['DETAIL_INFO_MODE'] == 'full' || $arParams['DETAIL_INFO_MODE'] == 'tabs'):?>
                                            $('.links-wrap .tab-store, .combo-target.availability.markets-combo .combo-header').click(initTabStores);
                                            <?endif?>
                                        </script>
                                    </div>
                                </div><!-- /.availability -->
                            <? endif; ?>
                        <? endif ?>
                        <? if ($bReviewsItem): ?>
                            <? include 'reviews.php' ?>
                        <? endif ?>
                    </div><!-- .tab-targets -->
                </div><!-- /.product-info-sections -->
            <? endif ?>

            <? // BIG DATA similar_sell
            if ($arParams['HIDE_ACCESSORIES'] != 'Y') {
                $frame = $this->createFrame()->begin("");
                include 'accessories.php';
                $frame->end();
            }
            ?>
        </div><!-- /.col-xs-12 -->
    </div><!-- /.row -->

<? if ($arResult['bSkuSimple']) include 'sku_simple.php'; ?>

<? if ($arResult['CATALOG']): ?>
    <?
    $frame = $this->createFrame()->begin($compositeLoader);

    if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])) {
        if ($arResult['OFFER_GROUP']) {
            foreach ($arResult['OFFER_GROUP_VALUES'] as $offerID) {
                ?>
                <div id="<? echo $arItemIDs['OFFER_GROUP'] . $offerID; ?>" style="display: none;">
                    <? $APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
                        "bitronic2",
                        array(
                            "IBLOCK_ID" => $arResult["OFFERS_IBLOCK"],
                            "ELEMENT_ID" => $offerID,
                            "PRICE_CODE" => $arParams["PRICE_CODE"],
                            "BASKET_URL" => $arParams["BASKET_URL"],
                            "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                            "CACHE_TIME" => $arParams["CACHE_TIME"],
                            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                            "TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME'],
                            "CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
                            "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                            "RESIZER_SET_CONTRUCTOR" => $arParams["RESIZER_SETS"]["RESIZER_SET_CONTRUCTOR"],
                            //PARAMS FOR HIDE ITEMS
                            'HIDE_ITEMS_NOT_AVAILABLE' => $arParams['HIDE_ITEMS_NOT_AVAILABLE'],
                            'HIDE_ITEMS_ZER_PRICE' => $arParams['HIDE_ITEMS_ZER_PRICE'],
                            'HIDE_ITEMS_WITHOUT_IMG' => $arParams['HIDE_ITEMS_WITHOUT_IMG'],
                            'ORDER_DETAIL_BLOCKS' => $arParams['ORDER_DETAIL_BLOCKS'],
                            'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                            'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
                        ),
                        $component
                    //array("HIDE_ICONS" => "Y")
                    ); ?><?
                    ?>
                </div>
                <?
            }
        }
    } else {
        if ($arResult['MODULES']['catalog'] && $arResult['OFFER_GROUP']) {
            ?><? $APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
                "bitronic2",
                array(
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "ELEMENT_ID" => $arResult["ID"],
                    "PRICE_CODE" => $arParams["PRICE_CODE"],
                    "BASKET_URL" => $arParams["BASKET_URL"],
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                    "CONVERT_CURRENCY" => $arParams['CONVERT_CURRENCY'],
                    "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                    "RESIZER_SET_CONTRUCTOR" => $arParams["RESIZER_SETS"]["RESIZER_SET_CONTRUCTOR"],
                    //PARAMS FOR HIDE ITEMS
                    'HIDE_ITEMS_NOT_AVAILABLE' => $arParams['HIDE_ITEMS_NOT_AVAILABLE'],
                    'HIDE_ITEMS_ZER_PRICE' => $arParams['HIDE_ITEMS_ZER_PRICE'],
                    'HIDE_ITEMS_WITHOUT_IMG' => $arParams['HIDE_ITEMS_WITHOUT_IMG'],
                    'ORDER_DETAIL_BLOCKS' => $arParams['ORDER_DETAIL_BLOCKS'],
                    'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                    'OFFERS_CART_PROPERTIES' => $arParams['OFFERS_CART_PROPERTIES'],
                ),
                $component
            //array("HIDE_ICONS" => "Y")
            ); ?><?
        }
    }
    $frame->end();
    ?>
<? endif ?>

<?
include 'gifts.php';
?>
    #DETAIL_BANNER_0#
<? // BIG DATA similar_view
$frame = $this->createFrame()->begin("");
if ($arParams['HIDE_SIMILAR_VIEW'] != 'Y') {
    include 'similar_view.php';
}
?>
    #DETAIL_BANNER_1#
<? // BIG DATA similar
if ($arParams['HIDE_SIMILAR'] != 'Y') {
    include 'similar.php';
}
?>
    #DETAIL_BANNER_2#
<? // PRICE similar
if ($arParams['HIDE_SIMILAR_PRICE'] !== 'Y') {
    include 'similar_price.php';
}
?>
    #DETAIL_BANNER_3#
<? // RECOMMENDED products
if ($arParams['HIDE_RECOMMENDED'] != 'Y') {
    include 'recommended.php';
}
?>
    #DETAIL_BANNER_4#
<? // VIEWED products
if ($arParams['HIDE_VIEWED'] != 'Y') {
    echo '#DETAIL_RW_VIEWED_PRODUCTS#'; //include 'viewed_products.php';
}
$frame->end();
?>
    #DETAIL_BANNER_5#
<? // JS PARAMS
include 'js_params.php';
?>
    </main>
<?
/* TODO
<? include '_/modals/modal_calc-delivery.html'; ?>
*/
?>

    <!-- MODALS -->
<?
include 'include/modal_gallery.php';

if (\Bitrix\Main\Loader::IncludeModule("yenisite.feedback")) {
    $this->SetViewTarget('bitronic2_modal_detail');
    if ($arParams['PRICE_LOWER'] != 'N') {
        \Yenisite\Core\Tools::IncludeArea('catalog', 'modal_price_drops', array(), true);
    }
    if ($arParams['FOUND_CHEAP'] != 'N') {
        \Yenisite\Core\Tools::IncludeArea('catalog', 'modal_price_cry', array(), true);
    }
    $this->EndViewTarget();
}
$templateData["CANONICAL_PAGE_URL"] = $arResult["CANONICAL_PAGE_URL"];