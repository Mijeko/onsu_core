<?if (!empty($arItem['ADDRESS']) || !empty($arItem['COORDINATES']) || !empty($arItem['PHONE']) || !empty($arItem['SCHEDULE']) || !empty($arItem['IMAGE_ID']) || !empty($arItem['EMAIL']) || !empty($arItem['DESCRIPTION'])):?>
<div class="popup_map">
    <button class="btn-close" data-popup="^">
        <span class="btn-text"><?=GetMessage('CLOSE')?></span>
        <i class="flaticon-close47"></i>
    </button>
    <?if (!empty($arItem['TITLE'])):?>
        <div class="text">
                <strong class="title-h4"><?=$arItem['TITLE']?></strong>
        </div>
    <?endif?>
    <div class="map-wrap">
        <?if (!empty($arItem['COORDINATES'])):?>
            <div class="map-block">
                <div class="map" data-latlng="<?=$arItem['COORDINATES']['GPS_N']?> <?=$arItem['COORDINATES']['GPS_S']?>"></div>
            </div>
        <?endif?>
    <?if (!empty($arItem['ADDRESS'])|| !empty($arItem['PHONE']) || !empty($arItem['SCHEDULE']) || !empty($arItem['IMAGE_ID']) || !empty($arItem['EMAIL']) || !empty($arItem['DESCRIPTION'])):?>
        <div class="info-wrap hidden-xs">
            <?if (!empty($arItem['IMAGE_ID'])):?>
                <?$file = CFile::GetPath($arItem['IMAGE_ID']);
                $arParams['RESIZER_IMG_STORE'] = $arParams['RESIZER_IMG_STORE'] ? : 1;
                ?>
                <div class="line-wrap img-wrap" style="background-image: url('<?=CResizer2Resize::ResizeGD2($file,$arParams['RESIZER_IMG_STORE'])?>');"> </div>
            <?endif?>
            <?if (!empty($arItem['ADDRESS'])):?>
                <div class="line-wrap">
                    <span class="line with-icon">
                        <i class="flaticon-location4"></i>
                        <span class="text"><?=$arItem['ADDRESS']?></span>
                    </span>
                </div>
            <?endif?>
            <?if (!empty($arItem['PHONE'])):?>
                <div class="line-wrap">
                    <span class="line with-icon">
                        <i class="flaticon-phone12"></i>
                        <span class="text"><?=$arItem['PHONE']?></span>
                    </span>
                </div>
            <?endif?>
            <?if (!empty($arItem['EMAIL'])):?>
                <div class="line-wrap">
                    <a href="mailto:manager@romza.ru" class="line with-icon">
                        <i class="flaticon-mail9"></i>
                        <span class="pseudolink-bd link-black"><?=$arItem['EMAIL']?></span>
                    </a>
                </div>
            <?endif?>
            <?if (!empty($arItem['SCHEDULE'])):?>
                <div class="line-wrap">
                    <span class="line with-icon">
                        <i class="flaticon-clock4"></i>
                        <span class="text"><?=$arItem['SCHEDULE']?></span>
                    </span>
                </div>
            <?endif?>
            <?if (!empty($arItem['DESCRIPTION'])):?>
                <div class="line-wrap">
                    <span class="line with-icon">
                        <i class="flaticon-folded11"></i>
                        <!-- <i class="flaticon-speech90"></i> -->
                        <span class="text"><?=$arItem['DESCRIPTION']?></span>
                    </span>
                </div>
            <?endif?>
        </div>
    <?endif?>
    </div>
</div>
<?endif?>