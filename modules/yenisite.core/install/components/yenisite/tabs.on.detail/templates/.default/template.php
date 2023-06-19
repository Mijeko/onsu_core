<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die;
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);

if (empty($arResult['SECTION']) || empty($arResult['ELEMENTS'])):?>
        <div class="alert alert-warning">
            <div class="row">
                <div class="col-xs-12 alert-content">
                    <i class="icon fa fa-exclamation-triangle"></i>
                    <div> <?= $arParams['WARNING'] ? : GetMessage('WARNING') ?></div>
                </div>
            </div>
        </div>
    <?return;?>
<?endif?>

<?if (!$arParams['HIDE_HEADER']):?>
    <div class="h2 section__header"><?= $arResult['SECTION']['NAME'] ?></div>
<?endif?>
<?if (!$arParams['JUST_TABS']):?>
<div class="tabs-component-yenisite section__content">
    <div class="mar-t-30">
        <div class="collapsible-items-wrap">
<?endif?>
            <? foreach ($arResult['ELEMENTS'] as $key => $arElement): ?>
            <div class="collapsible-item<?= $key == 0 ? '' : ' collapsed' ?>">
                <header class="collapsible-header">
                    <i class="fa fa-chevron-up"></i>
                    <? if (!empty($arElement['PROPERTIES'])): ?>
                        <div class="additional-wrap">
                    <? endif; ?>
                        <? if (!empty($arElement['PROPERTIES']['RZ_HIT']['VALUE'])): ?>
                            <span class="important"><?=GetMessage('HIT')?></span>
                        <?endif?>
                            <?=$arParams['NAME_SUF']?> <?if($arParams['IN_QUOTES']):?><q><?endif?><?= $arElement['NAME']?><?if($arParams['IN_QUOTES']):?></q><?endif?>
                        <? if (!empty($arElement['PROPERTIES'])): ?>
                        <span class="additional<?= $arElement['PROPERTIES']['PRICE']['VALUE'] ? ' important' : ''; ?>"><?= $arElement['PROPERTIES']['PRICE']['VALUE'].' '.$arElement['PROPERTIES']['CURRENCY']['VALUE'] ?>
                            </span>
                        </div>
                <? endif; ?>
                </header>
                <div class="collapse<?= $key == 0 ? ' in' : '' ?>">
                    <div class="content">
                        <?= $arElement['DETAIL_TEXT'] ? $arElement['DETAIL_TEXT'] : $arElement['PREVIEW_TEXT'] ?>
                    </div>
                </div>
            </div>
            <? endforeach; ?>
<?if (!$arParams['JUST_TABS']):?>
        </div>
    </div>
</div>
<?endif?>
<? if ($arParams['NOT_INCLUDE_JS'] != 'Y') {
    $Asset = \Bitrix\Main\Page\Asset::getInstance();
    $Asset->addJs($templateFolder . '/script_tabs.js');
} ?>
