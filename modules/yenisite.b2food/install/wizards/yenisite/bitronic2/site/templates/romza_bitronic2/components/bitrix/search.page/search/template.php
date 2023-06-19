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
include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/include/debug_info.php';

$Asset = \Bitrix\Main\Page\Asset::getInstance();
$Asset->addJs(SITE_TEMPLATE_PATH . '/js/back-end/catalog/search-page.js');

$catalogCategory = 'iblock_' . $arParams['IBLOCK_TYPE'];
$catalogFolder = CRZBitronic2CatalogUtils::getCatalogFolder();
?>
    <main class="container search-results-page" data-page="search-results-page">
        <h1><? $APPLICATION->ShowTitle() ?></h1>

        <? if (isset($arResult["REQUEST"]["ORIGINAL_QUERY"])): ?>
            <p>
                <? echo GetMessage("BITRONIC2_CT_BSP_KEYBOARD_WARNING", array("#query#" => '<strong><a href="' . $arResult["ORIGINAL_QUERY_URL"] . '">' . $arResult["REQUEST"]["ORIGINAL_QUERY"] . '</a></strong>')) ?>
            </p>
        <?
        elseif (isset($arResult["REQUEST"]["QUERY"]) && !empty($arResult["REQUEST"]["QUERY"])): ?>
            <p><?= GetMessage('BITRONIC2_SEARCH_BY_QUERY') ?><strong><?= $arResult["REQUEST"]["QUERY"] ?></strong></p>
        <?endif; ?>

        <? if ($arResult["REQUEST"]["QUERY"] === false && $arResult["REQUEST"]["TAGS"] === false): ?>
        <?
        elseif ($arResult["ERROR_CODE"] != 0): ?>
            <p><?= GetMessage("BITRONIC2_SEARCH_ERROR") ?></p>
            <? ShowError($arResult["ERROR_TEXT"]); ?>
            <p><?= GetMessage("BITRONIC2_SEARCH_CORRECT_AND_CONTINUE") ?></p>
            <br/><br/>
            <p><?= GetMessage("BITRONIC2_SEARCH_SINTAX") ?><br/><b><?= GetMessage("BITRONIC2_SEARCH_LOGIC") ?></b></p>
            <table border="0" cellpadding="5">
                <tr>
                    <td align="center" valign="top"><?= GetMessage("BITRONIC2_SEARCH_OPERATOR") ?></td>
                    <td valign="top"><?= GetMessage("BITRONIC2_SEARCH_SYNONIM") ?></td>
                    <td><?= GetMessage("BITRONIC2_SEARCH_DESCRIPTION") ?></td>
                </tr>
                <tr>
                    <td align="center" valign="top"><?= GetMessage("BITRONIC2_SEARCH_AND") ?></td>
                    <td valign="top">and, &amp;, +</td>
                    <td><?= GetMessage("BITRONIC2_SEARCH_AND_ALT") ?></td>
                </tr>
                <tr>
                    <td align="center" valign="top"><?= GetMessage("BITRONIC2_SEARCH_OR") ?></td>
                    <td valign="top">or, |</td>
                    <td><?= GetMessage("BITRONIC2_SEARCH_OR_ALT") ?></td>
                </tr>
                <tr>
                    <td align="center" valign="top"><?= GetMessage("BITRONIC2_SEARCH_NOT") ?></td>
                    <td valign="top">not, ~</td>
                    <td><?= GetMessage("BITRONIC2_SEARCH_NOT_ALT") ?></td>
                </tr>
                <tr>
                    <td align="center" valign="top">( )</td>
                    <td valign="top">&nbsp;</td>
                    <td><?= GetMessage("BITRONIC2_SEARCH_BRACKETS_ALT") ?></td>
                </tr>
            </table>
        <?
        else: ?>
			<?if($arResult["REQUEST"]["WHERE"] !== $catalogCategory):?>
            <div class="sort-n-view no-justify">
                <span class="text"><?= GetMessage('BITRONIC2_SEARCH_SORT_BY') ?></span>
                <form action="<?= $APPLICATION->GetCurPageParam() ?>" method="get">
                    <select name="sort-by" id="sort-by" class="sort-by-select">
                        <option value="<?= $arResult["URL"] ?><? echo $arResult["REQUEST"]["WHERE"] ? '&amp;where=' . $arResult["REQUEST"]["WHERE"] : 'ALL' ?>&amp;how=r" <?= !empty($_GET['how']) && $_GET['how'] == 'r' ? 'selected' : '' ?>><?= GetMessage('BITRONIC2_SEARCH_SORT_BY_RANK') ?></option>
                        <option value="<?= $arResult["URL"] ?><? echo $arResult["REQUEST"]["WHERE"] ? '&amp;where=' . $arResult["REQUEST"]["WHERE"] : 'ALL' ?>&amp;how=d" <?= !empty($_GET['how']) && $_GET['how'] == 'd' ? 'selected' : '' ?>><?= GetMessage('BITRONIC2_SEARCH_SORT_BY_DATE') ?></option>
                    </select>
                </form>
            </div>
			<?endif;?>
            <? if ($arParams["SHOW_WHERE"]): ?>
                <div class="sort-n-view no-sort no-justify">
                    <span class="text"><?= GetMessage('BITRONIC2_SEARCH_BY_CATEGORY') ?></span>
                    <form action="<?= $APPLICATION->GetCurPageParam() ?>" method="get">
                        <select name="sort-by" id="search-in-category" class="sort-by-select">
                            <? foreach ($arResult['DROPDOWN'] as $key => $value):
                                ?>
                                <option value="<?= $APPLICATION->GetCurPageParam('where=' . $key, array('where'), false) ?>" <?= ($arResult["REQUEST"]["WHERE"] === $key ? ' selected' : '') ?>>
                                    <?= ($key == $catalogCategory ? GetMessage('BITRONIC2_SEARCH_PRODUCTS') : $value) ?>
                                </option>
                            <? endforeach; ?>
                            <option value="<?= $APPLICATION->GetCurPageParam((CSite::InDir($catalogFolder) ? 'where=ALL' : ''), array('where'), false) ?>" <?= (!array_key_exists($arResult["REQUEST"]["WHERE"], $arResult['DROPDOWN']) ? ' selected' : '') ?>><?= GetMessage("BITRONIC2_SEARCH_ALL") ?></option>
                        </select>
                    </form>
                </div>
            <? endif ?>
            <? if (count($arResult["SEARCH"]) > 0): ?>
                <? $APPLICATION->ShowViewContent('hide_class_open_tag') ?>
                <div itemscope itemtype="http://schema.org/ImageObject" class="search-robot-wrap">
                    <img itemprop="contentUrl" src="<?= SITE_TEMPLATE_PATH ?>/img/bg/search-robot.png"
                         alt="<?= GetMessage('BITRONIC2_SEARCH_SATISFIED_ROBOT') ?>">
                </div>
                <? $APPLICATION->ShowViewContent('hide_class_close_tag') ?>

                <? if ($arParams["DISPLAY_TOP_PAGER"] != "N") {
                    $APPLICATION->ShowViewContent('hide_class_open_tag');
                    echo $arResult["NAV_STRING"];
                    $APPLICATION->ShowViewContent('hide_class_close_tag');
                } ?>
                <? if (CSite::InDir($catalogFolder)): ?>
                    <? if ($arResult["REQUEST"]["WHERE"] == $catalogCategory): ?>
                        <div class="search-results-other" id="search-results-other">
                            <? foreach ($arResult["SEARCH"] as $arItem): ?>
                                <? if ($arItem['MODULE_ID'] != 'iblock' || strpos($arItem['ITEM_ID'], 'S') !== 0) continue ?>
                                <div class="search-results-item">
                                    <div class="link-wrap">
                                        <a href="<? echo $arItem["URL"] ?>" class="link"><span
                                                    class="text"><? echo $arItem["TITLE_FORMATED"] ?></span></a>
                                    </div>
                                    <div class="desc">
                                        <? echo $arItem["BODY_FORMATED"] ?>
                                    </div>
                                    <div class="date"><?= GetMessage("BITRONIC2_SEARCH_MODIFIED") ?> <?= $arItem["DATE_CHANGE"] ?></div>
                                    <? if ($arItem["CHAIN_PATH"]): ?>
                                        <div class="path">
                                            <span class="text"><?= GetMessage("BITRONIC2_SEARCH_PATH") ?></span>
                                            <?= $arItem["CHAIN_PATH"] ?>
                                        </div>
                                    <? endif ?>
                                </div>
                            <? endforeach ?>
                        </div><!-- /#search-results-other -->
                    <? endif ?>
                    <? if (CSite::InDir($catalogFolder) && $arResult["REQUEST"]["WHERE"] == $catalogCategory): ?>
                        <div class="search-results-catalog" id="search-results-catalog">
                            <? $APPLICATION->ShowViewContent('sections_elements_of_search') ?>
                            <? $APPLICATION->ShowViewContent('catalog_search') ?>
                        </div>
                    <? endif ?>
                <? endif ?>
                <? if (!CSite::InDir($catalogFolder) || $arResult["REQUEST"]["WHERE"] != $catalogCategory): ?>
                    <div class="search-results-other" id="search-results-other">
                        <? foreach ($arResult["SEARCH"] as $arItem): ?>
                            <div class="search-results-item">
                                <div class="link-wrap">
                                    <a href="<? echo $arItem["URL"] ?>" class="link"><span
                                                class="text"><? echo $arItem["TITLE_FORMATED"] ?></span></a>
                                </div>
                                <div class="desc">
                                    <? echo $arItem["BODY_FORMATED"] ?>
                                </div>
                                <div class="date"><?= GetMessage("BITRONIC2_SEARCH_MODIFIED") ?> <?= $arItem["DATE_CHANGE"] ?></div>
                                <? if ($arItem["CHAIN_PATH"]): ?>
                                    <div class="path">
                                        <span class="text"><?= GetMessage("BITRONIC2_SEARCH_PATH") ?></span>
                                        <?= $arItem["CHAIN_PATH"] ?>
                                    </div>
                                <? endif; ?>
                            </div>
                        <? endforeach; ?>
                    </div><!-- /#search-results-other -->
                <? endif ?>

                <? $APPLICATION->ShowViewContent('hide_class_open_tag') ?>
                <div class="clearfix"></div>
                <? $APPLICATION->ShowViewContent('hide_class_close_tag') ?>

                <? if ($arParams["DISPLAY_BOTTOM_PAGER"] != "N") {
                    $APPLICATION->ShowViewContent('hide_class_open_tag');
                    echo $arResult["NAV_STRING"];
                    $APPLICATION->ShowViewContent('hide_class_close_tag');
                } ?>
            <?
            else: ?>
                <? ShowNote(GetMessage("BITRONIC2_SEARCH_NOTHING_TO_FOUND")); ?>
            <?endif; ?>
        <?endif; ?>
        <? $APPLICATION->ShowViewContent('hide_class_open_tag') ?>
        <? $APPLICATION->ShowViewContent('search_sliders') ?>
        <? $APPLICATION->ShowViewContent('hide_class_close_tag') ?>
    </main>

<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
