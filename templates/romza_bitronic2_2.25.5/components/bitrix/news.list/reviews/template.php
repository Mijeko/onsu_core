<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

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
$this->setFrameMode(true);
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';

if (empty($arResult['ITEMS'])) return;
\Bitrix\Main\Localization\Loc::loadMessages($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/lang/' . LANGUAGE_ID . '/header.php'); ?>

<a href="<?= $arResult['ITEMS'][0]['LIST_PAGE_URL'] ?>"
   class="title-h3 link-black"><?= GetMessage('BITRONIC2_REVIEWS') ?></a>
<div class="items-wrapper">
    <? foreach ($arResult['ITEMS'] as $key => $arItem): ?>
        <?
        $this->AddEditAction($this->__name . $arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($this->__name . $arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"),
            array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <? include 'item.php' ?>
    <? endforeach; ?>
</div>
<a href="<?= $arResult['ITEMS'][0]['LIST_PAGE_URL'] ?>" class="link more-content">
    <div class="bullets">
        <span class="bullet">&bullet;</span>
        <span class="bullet">&bullet;</span>
        <span class="bullet">&bullet;</span>
    </div>
    <span class="text"><?= GetMessage('BITRONIC2_ALL_REVIEWS') ?></span>
</a>
