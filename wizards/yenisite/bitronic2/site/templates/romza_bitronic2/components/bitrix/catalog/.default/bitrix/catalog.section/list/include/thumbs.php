<? if (!$bSkuExt):?>
    <? if ($arItem['SHOW_SLIDER']):?>
        <div class="photo-thumbs">
            <div class="slidee">
                <? foreach ($arItem['MORE_PHOTO'] as $arPhoto):
                    ?>
                    <div itemscope itemtype="http://schema.org/ImageObject" class="photo-thumb">
                    <img    itemprop="contentUrl"
                            class="lazy-sly"
                            data-original="<?= CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams['RESIZER_SECTION_ICON']) ?>"
                            src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                            title="<?= strlen($arPhoto['DESCRIPTION']) > 0 ? $arPhoto['DESCRIPTION'] : $arItem['PICTURE_PRINT']['ALT'] ?>"
                            alt="<?= strlen($arPhoto['DESCRIPTION']) > 0 ? $arPhoto['DESCRIPTION'] : $arItem['PICTURE_PRINT']['ALT'] ?>"
                            data-medium-image="<?= CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams['RESIZER_SECTION']) ?>"
                    >
                    </div><?
                endforeach; ?>
            </div>
            <? if ($arItem['MORE_PHOTO_COUNT'] > 4):?>
                <div class="carousel-dots"></div>
            <?endif ?>
        </div>
    <?endif ?>
    <?
else:?>
    <? foreach ($arItem['OFFERS'] as $arOffer):?>
        <? if ($arOffer['SHOW_SLIDER']):?>
            <div class="photo-thumbs" id="<? echo $arItemIDs['SLIDER_CONT_OF_ID'] . $arOffer['ID']; ?>"
                 style="display:none">
                <div class="slidee">
                    <? foreach ($arOffer['MORE_PHOTO'] as $arPhoto):
                        ?>
                        <div itemscope itemtype="http://schema.org/ImageObject" class="photo-thumb">
                        <img    itemprop="contentUrl"
                                class="lazy-sly"
                                data-original="<?= CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams['RESIZER_SECTION_ICON']) ?>"
                                src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                data-src="<?= CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams['RESIZER_SECTION_ICON']) ?>"
                                alt="<?= strlen($arPhoto['DESCRIPTION']) > 0 ? $arPhoto['DESCRIPTION'] : $arOffer['PICTURE_PRINT']['ALT'] ?>"
                                title="<?= strlen($arPhoto['DESCRIPTION']) > 0 ? $arPhoto['DESCRIPTION'] : $arOffer['PICTURE_PRINT']['ALT'] ?>"
                                data-medium-image="<?= CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams['RESIZER_SECTION']) ?>"
                        >
                        </div><?
                    endforeach; ?>
                </div>
                <? if ($arOffer['MORE_PHOTO_COUNT'] > 4):?>
                    <div class="carousel-dots"></div>
                <?endif ?>
            </div>
        <?endif ?>
    <?endforeach ?>
<?endif ?>