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

if (empty($arResult['SECTIONS'])) {
    return false;
}
?>
<ul class="nav nav-pills" role="tablist" id="goodsTab">
    <? foreach ($arResult['SECTIONS'] as $idItem_1 => $arItem_1): ?>
        <li role="presentation" <?=$arItem_1['SECTION_PAGE_URL'] == $arParams['CURR_PAGE'] ? 'class="active"' : ''?>>
            <a href="<?= $arResult['SECTIONS'][$idItem_1]['SECTION_PAGE_URL'] ?>" aria-controls="news" role="tab" data-toggle="tab" aria-expanded="true"><?= $arResult['SECTIONS'][$idItem_1]['NAME'] ?></a>
        </li>
        <?if ($arParams['SET_TITLE'] && $arItem_1['SECTION_PAGE_URL'] == $arParams['CURR_PAGE']){
            $templateData['NAME'] = $arResult['SECTIONS'][$idItem_1]['NAME'];
        }?>
    <?endforeach;?>
</ul>
<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";