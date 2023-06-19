<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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

// if(empty($arResult['SECTIONS']))
// {
// return false;
// }
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';
$arDesc = explode('#DELIMETER#', $arResult['SECTION']['DESCRIPTION']);
if (!$arParams['IS_BOTTOM']) {
    $desc = $arDesc[0];
} elseif (count($arDesc) > 1) {
    $desc = $arDesc[1];
}else{
    $desc = $arDesc[0];
}
?>
<?if ($arParams['SHOW_DESCRIPTION'] == 'Y'): ?>
    <div class="category-description general-info <? if ($arParams['IS_BOTTOM']): ?>rz_category_desc_bottom<? else: ?>rz_category_desc_top<? endif ?>">
        <div class="desc">
            <? if (\Bitrix\Main\Loader::includeModule('yenisite.seofilter')): ?>
                <? if ($arParams['IS_BOTTOM']): ?>
                    #RZ_SEO_BOT#
                <? else: ?>
                    #RZ_SEO_TOP#
                <? endif ?>
            <? else: ?>
                <?= $desc?>
            <? endif ?>
        </div>
        <div class="pseudolink">
            <span class="link-text when-closed"><?= GetMessage('BITRONIC2_DESC_SHOW_FULL') ?></span>
            <span class="link-text when-opened"><?= GetMessage('BITRONIC2_DESC_HIDE_FULL') ?></span>
        </div>
    </div>
<? endif ?>
<?if ($arParams['SHOW_SUBSECTIONS'] == 'Y'): ?>
    <div class="sub-categories<?= (count($arResult['SECTIONS']) >= $arParams['SECTIONS_START_COLUMNS']) ? ' columns' : '' ?>">
        <? foreach ($arResult['SECTIONS'] as $arSection): ?>
            <a itemscope itemtype="http://schema.org/ImageObject" href="<?= $arSection['SECTION_PAGE_URL'] ?>" class="link">
                <? if($arParams['VIEW_MODE'] != 'TEXT' && !empty($arSection['PICTURE'])): ?>
                    <img itemprop="contentUrl" data-original="<?=$arSection['PICTURE']['SRC']?>" src="<?=consVar::showLoaderWithTemplatePath()?>" alt="<?=$arSection['PICTURE']['ALT']?>" title="<?=$arSection['PICTURE']['TITLE']?>" class="lazy subcategory-img">
                <? endif ?>
                <? if($arParams['VIEW_MODE'] != 'PICTURE'): ?>
                    <span class="text"><?= $arSection['NAME'] ?></span>
                <? endif ?>
                <sup><?= $arSection['ELEMENT_CNT'] ?></sup>
            </a>
        <? endforeach ?>
    </div>
<? endif ?>
<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";