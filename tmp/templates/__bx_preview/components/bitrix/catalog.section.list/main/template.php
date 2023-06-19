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
if (method_exists($this, 'setFrameMode')) $this->setFrameMode(true);

include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/include/debug_info_dynamic.php';

if (empty($arResult['ALL_ITEMS'])) {
    return false;
}
?>
    <div id="categories" class="categories hidden-xs drag-section sCategories"
         data-order="<?= $arParams['CATEGORIES_ORDER'] ?>" data-categories-enabled="true">
        <div class="container">
            <div class="wrapper scroll-slider-wrap">
                <div class="sly-scroll horizontal">
                    <div class="sly-bar"></div>
                </div>
                <div class="scroll-slider frame">
                    <div class="slider-content slidee slides">
                        <? foreach ($arResult['ALL_ITEMS_ID'] as $idItem_1 => $arItem_1): ?>
                            <div class="slide <?= is_array($arItem_1) && count($arItem_1) > 0 ? 'with-sub' : '' ?>">
                                <? if (!empty($arResult['ALL_ITEMS'][$idItem_1]["PICTURE_BIG_IMG"]['SRC']) || !empty($arResult['ALL_ITEMS'][$idItem_1]["PICTURE_LARGE"]['SRC'])): ?>
                                    <div itemscope itemtype="http://schema.org/ImageObject" class="block-img-wrap">
                                        <div class="opacity-wrap"></div>
                                        <div class="category-img"
                                             style="background-image: url('<?= $arResult['ALL_ITEMS'][$idItem_1]["PICTURE_BIG_IMG"]['SRC'] ? $arResult['ALL_ITEMS'][$idItem_1]["PICTURE_BIG_IMG"]['SRC'] : $arResult['ALL_ITEMS'][$idItem_1]["PICTURE_LARGE"]['SRC'] ?>')"></div>
                                        <img itemprop="contentUrl" class="lazy"
                                             src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                             data-original="<?= $arResult['ALL_ITEMS'][$idItem_1]["PICTURE_LARGE"]['SRC'] ? $arResult['ALL_ITEMS'][$idItem_1]["PICTURE_LARGE"]['SRC'] : $arResult['ALL_ITEMS'][$idItem_1]["PICTURE_BIG_IMG"]['SRC'] ?>"
                                             alt="<?= $arResult['ALL_ITEMS'][$idItem_1]['PICTURE']['ALT'] ?>"
                                             title="<?= $arResult['ALL_ITEMS'][$idItem_1]['PICTURE']['TITLE'] ?>">
                                    </div>
                                <? endif ?>
                                <div class="block-main-wrap">
                                    <a class="main-wrap"
                                       href="<?= $arResult['ALL_ITEMS'][$idItem_1]['SECTION_PAGE_URL'] ?>">
                                        <div itemscope itemtype="http://schema.org/ImageObject" class="img-wrap">
                                            <? if (!empty($arResult['ALL_ITEMS'][$idItem_1]["PICTURE"]['SRC'])): ?>
                                                <span
                                                        data-itemprop="contentUrl"
                                                        data-picture
                                                        data-alt="<?= $arResult['ALL_ITEMS'][$idItem_1]['PICTURE']['ALT'] ?>"
                                                        data-title="<?= $arResult['ALL_ITEMS'][$idItem_1]['PICTURE']['TITLE'] ?>">
                                                    <span data-itemprop="contentUrl"
                                                          data-src="<?= $arResult['ALL_ITEMS'][$idItem_1]["PICTURE"]['SRC'] ?>"
                                                          data-title="<?= $arResult['ALL_ITEMS'][$idItem_1]['PICTURE']['TITLE'] ?>"></span>
                                                    <span data-src="" data-media="(max-width: 767px)"></span>

                                                    <!-- Fallback content for non-JS browsers. Same img src as the initial, unqualified source element. -->
                                                    <noscript>
                                                        <img itemprop="contentUrl"
                                                             src="<?= $arResult['ALL_ITEMS'][$idItem_1]["PICTURE"]['SRC'] ?>"
                                                             alt="<?= $arResult['ALL_ITEMS'][$idItem_1]['PICTURE']['ALT'] ?>"
                                                             title="<?= $arResult['ALL_ITEMS'][$idItem_1]['PICTURE']['TITLE'] ?>">
                                                    </noscript>
                                                </span>
                                            <? endif ?>
                                        </div>
                                        <div class="category-name">
                                            <span class="text"><?= $arResult['ALL_ITEMS'][$idItem_1]['NAME'] ?></span>
                                            <? if (!empty($arResult['ALL_ITEMS'][$idItem_1]['ELEMENT_CNT'])): ?>
                                                <sup class="i-number"><?= intval($arResult['ALL_ITEMS'][$idItem_1]['ELEMENT_CNT']) ?></sup>
                                            <? endif ?>
                                            <span class="btn-expand"></span>
                                        </div>
                                    </a>
                                    <? if (is_array($arItem_1) && count($arItem_1) > 0): ?>
                                        <div class="category-sub">
                                            <ul>
                                                <? foreach ($arItem_1 as $idItem_2 => $arItem_2): ?>
                                                    <li>
                                                        <a class="link-sub"
                                                           href="<?= $arResult["ALL_ITEMS"][$idItem_2]['SECTION_PAGE_URL'] ?>">
                                                            <span class="text"> <?= $arResult["ALL_ITEMS"][$idItem_2]['NAME'] ?></span>
                                                            <? if (!empty($arResult['ALL_ITEMS'][$idItem_2]['ELEMENT_CNT'])): ?>
                                                                <sup class="i-number"><?= intval($arResult['ALL_ITEMS'][$idItem_2]['ELEMENT_CNT']) ?></sup>
                                                            <? endif ?>
                                                        </a>
                                                        <? if (is_array($arItem_2) && count($arItem_2) > 0): ?>
                                                            <ul>
                                                                <? foreach ($arItem_2 as $idItem_3 => $arItem_3): ?>
                                                                    <li>
                                                                        <a class="link-sub"
                                                                           href="<?= $arResult["ALL_ITEMS"][$arItem_3]['SECTION_PAGE_URL'] ?>">
                                                                            <span class="text"> <?= $arResult["ALL_ITEMS"][$arItem_3]['NAME'] ?></span>
                                                                            <? if (!empty($arResult['ALL_ITEMS'][$arItem_3]['ELEMENT_CNT'])): ?>
                                                                                <sup class="i-number"><?= intval($arResult['ALL_ITEMS'][$idItem_1]['ELEMENT_CNT']) ?></sup>
                                                                            <? endif ?>
                                                                        </a>
                                                                    </li>
                                                                <? endforeach; ?>
                                                            </ul>
                                                        <? endif ?>
                                                    </li>
                                                <? endforeach; ?>
                                            </ul>
                                        </div>
                                    <? endif ?>
                                </div>
                            </div>
                        <? endforeach ?>
                    </div>
                </div><!-- /.slides -->
            </div><!-- /.wrapper -->
        </div><!-- /.container -->
    </div><!-- /.categories -->


<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";