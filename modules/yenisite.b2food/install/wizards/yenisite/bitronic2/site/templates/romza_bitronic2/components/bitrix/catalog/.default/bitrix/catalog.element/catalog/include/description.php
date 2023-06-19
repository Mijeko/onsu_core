<div class="general-info<?= ($arParams['DETAIL_TEXT_DEFAULT'] == 'open') ? ' toggled' : '' ?>">
    <div class="desc" itemprop="description">
        <?= $arResult['DETAIL_TEXT'] ?>
    </div>
    <div class="pseudolink">
        <span class="link-text when-closed"><?= GetMessage('BITRONIC2_DESC_SHOW_FULL') ?></span>
        <span class="link-text when-opened"><?= GetMessage('BITRONIC2_DESC_HIDE_FULL') ?></span>
    </div>
    <? if ($arResult['CATALOG_WEIGHT'] > 0): ?>
        <div class="info weight flaticon-two328">
            <?= GetMessage('BITRONIC2_ITEM_WEIGHT') ?><?= $arResult['CATALOG_WEIGHT'] ?> <?= GetMessage('BITRONIC2_ITEM_WEIGHT_GRAMM') ?>
        </div>
    <? endif ?>
    <?
    if(0 < intval($arResult['CATALOG_LENGTH'])
        || 0 < intval($arResult['CATALOG_WIDTH'])
        || 0 < intval($arResult['CATALOG_HEIGHT'])
    ):
        ?>
        <div class="info dimensions flaticon-increase10">
            <?= GetMessage('BITRONIC2_ITEM_DIMENSIONS') ?>:
            <?=$arResult['CATALOG_LENGTH']?> x <?=$arResult['CATALOG_WIDTH']?> x <?=$arResult['CATALOG_HEIGHT']?>
            <?= GetMessage('BITRONIC2_ITEM_DIMENSIONS_MM') ?>
        </div>
    <? endif ?>
    <? if(!empty($arResult['TAGS']) && is_array($arResult['TAGS'])): ?>
        <div class="general-info-tags">
            <? foreach($arResult['TAGS'] as $tag): ?>
                <span class="label label-default"><?=$tag?></span>
            <? endforeach ?>
        </div>
    <? endif ?>
</div>

