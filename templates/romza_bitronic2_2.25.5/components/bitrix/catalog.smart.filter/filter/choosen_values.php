<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="filter-chosen">
    <div class="title-h2"><?= GetMessage('BITORNIC2_SHOOSEN_HEADER') ?></div>
    <div class="filter-section">
        <ul class="chosen-list">
            <? foreach ($arResult['VALUES_CHECKED'] as $arItem): ?>
                <? foreach ($arItem['VALUES'] as $arValue): ?>
                    <li class="chosen-item">
                        <span><?=$arValue['VALUE'];?></span>
                        <i data-control-radio="<?= $arValue['CONTROL_NAME_ALT'] ?>" data-control-select="<?= $arValue['CONTROL_NAME_ALT'] ?>" data-control-input="<?= $arValue['CONTROL_ID'] ?>" class="choose-delete btn-close-chosen flaticon-close47"></i>
                    </li>
                <? endforeach; ?>
            <? endforeach; ?>
        </ul>
    </div>
</div>

