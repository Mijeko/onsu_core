<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!$arResult['bSkuExt']): ?>
    <?
    if ($bCatchbuy):?>

        <div class="countdown">
        <div class="timer-wrap"><?

            if (!empty($arResult['CATCHBUY']['ACTIVE_TO'])): ?>

            <div class="timer"
                 data-until="<?= str_replace('XXX', 'T', ConvertDateTime($arResult['CATCHBUY']['ACTIVE_TO'], 'YYYY-MM-DDXXXhh:mm:ss')) ?>"></div><?

            endif ?>

            <div class="already-sold">
                <div class="value countdown-amount"><?= intVal($arResult['CATCHBUY']['PERCENT']) ?>%
                </div>
                <div class="countdown-period"><?= GetMessage('BITRONIC2_SOLD') ?></div>
            </div>
            <div class="already-sold__track">
                <div class="bar"
                     style="width: <?= floatval($arResult['CATCHBUY']['PERCENT']) ?>%"></div>
            </div>
        </div>
        </div><?

    endif ?>
    <div class="gallery-carousel carousel slide" data-interval="0"
         id="<? echo $arItemIDs['SLIDER_CONT_ID']; ?>" style="height:100%; width: 100%">
        <div class="carousel-inner product-photo">
            <? if ($arResult['MORE_PHOTO_COUNT'] > 1): ?>
                <? foreach ($arResult['MORE_PHOTO'] as $key => $arPhoto): ?>
                    <? if (strval($key) == 'VIDEO') continue; ?>
                    <div class="item <?= $key == 0 ? 'active' : '' ?>">
                        <div itemscope itemtype="http://schema.org/ImageObject" class="img-wrap">
                            <img class="lazy-sly"
                                 data-zoom="<?= $arPhoto['SRC_BIG'] ?>"
                                 id="<?= $arItemIDs['PICT'].$key.'_inner' ?>"
                                 src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                 data-src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                 data-original="<?= $arPhoto['SRC_SMALL'] ?>"
                                 data-big-src="<?= $arPhoto['SRC_BIG'] ?>"
                                 alt="<?= $arPhoto['ALT'] ?: $strAlt ?>"
                                 title="<?= $arPhoto['TITLE'] ?: $strTitle ?>"
                                 itemprop="image contentUrl">
                        </div>
                    </div>
                <? endforeach ?>
                <? if (!empty($arResult['MORE_PHOTO']['VIDEO']) && $bShowVideoInSlider): ?>
                    <? foreach ($arResult['MORE_PHOTO']['VIDEO'] as $video): ?>
                        <div class="item has-video">
                            <div class="video-wrap-outer">
                                <div class="video-wrap-inner">
                                    <div class="video" data-src="<?= $video; ?>"></div>
                                </div>
                            </div>
                        </div>
                    <? endforeach ?>
                <? endif ?>
                <?
            else: ?>
                <div class="item active">
                    <div itemscope itemtype="http://schema.org/ImageObject" class="img-wrap">
                        <img
                            data-zoom="<?= $arResult['MORE_PHOTO'][0]['SRC_BIG'] ?>"
                            class="lazy-sly"
                            id="<?= $arItemIDs['PICT'].'_inner' ?>"
                            src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                            data-original="<?= $arResult['MORE_PHOTO'][0]['SRC_SMALL'] ?>"
                            data-src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                            data-big-src="<?= $arResult['MORE_PHOTO'][0]['SRC_BIG'] ?>"
                            alt="<?= $strAlt ?>"
                            title="<?= $strTitle ?>"
                            itemprop="image contentUrl">
                    </div>
                </div>
                <? if (!empty($arResult['MORE_PHOTO']['VIDEO']) && $bShowVideoInSlider): ?>
                    <? foreach ($arResult['MORE_PHOTO']['VIDEO'] as $video): ?>
                        <div class="item has-video">
                            <div class="video-wrap-outer">
                                <div class="video-wrap-inner">
                                    <div class="video" data-src="<?= $video; ?>"></div>
                                </div>
                            </div>
                        </div>
                    <? endforeach ?>
                <? endif ?>
            <? endif ?>
        </div>

        <? if ($arResult['MORE_PHOTO_COUNT'] > 1): ?>
            <div class="thumbnails-wrap active">
                <button type="button" class="thumb-control prev btn-silver">
                    <i class="flaticon-key22 arrow-up"></i>
                    <i class="flaticon-arrow133 arrow-left"></i>
                </button>
                <button type="button" class="thumb-control next btn-silver">
                    <i class="flaticon-arrow128 arrow-down"></i>
                    <i class="flaticon-right20 arrow-right"></i>
                </button>
                <div class="thumbnails-frame">
                    <div class="thumbnails-slidee">
                        <? foreach ($arResult['MORE_PHOTO'] as $key => $arPhoto): ?>
                            <? if (strval($key) == 'VIDEO') continue; ?>
                            <div itemscope itemtype="http://schema.org/ImageObject" class="thumb <?= $key == 0 ? 'active' : '' ?>">
                                <img    itemprop="contentUrl"
                                        class="lazy-sly"
                                        data-original="<?= $arPhoto['SRC_ICON'] ?>"
                                        data-src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                        src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                        alt="<?= $arPhoto['ALT'] ?: $strAlt ?>"
                                        title="<?= $arPhoto['TITLE'] ?: $strTitle ?>"
                                        data-med-src="<?= $arPhoto['SRC_SMALL'] ?>"
                                        data-big-src="<?= $arPhoto['SRC_BIG'] ?>"
                                >
                            </div>
                        <? endforeach ?>
                        <? if (!empty($arResult['MORE_PHOTO']['VIDEO']) && $bShowVideoInSlider): ?>
                            <? foreach ($arResult['MORE_PHOTO']['VIDEO'] as $video): ?>
                                <div class="thumb has-video">
                                    <i class="flaticon-movie16"
                                       data-video="<?= $video ?>"
                                    ></i>
                                </div>
                            <? endforeach ?>
                        <? endif ?>
                    </div><!-- .thumbnails-slidee -->
                </div><!-- .thumbnails-frame -->
            </div><!-- /.thumbnails -->
        <? endif ?>
    </div>
    <?
else: ?>
    <? foreach ($arResult['OFFERS'] as $arOffer): ?>
        <div id="<?= $arItemIDs['SLIDER_CONT_OF_INNER_ID'] ?><?= $arOffer['ID'] ?>"
             class="gallery-carousel carousel slide" data-interval="0" style="display: none;">
            <? if ($arParams['SHOW_CATCHBUY'] && $arOffer['CATCHBUY']): ?>
                <div class="countdown" id="<?= $arItemIDs['ID'] ?>_countdown_<?= $arOffer['ID'] ?>"
                     style="display:none">
                    <div class="timer-wrap">
                        <div class="timer"
                             data-until="<?= str_replace('XXX', 'T', ConvertDateTime($arOffer['CATCHBUY']['ACTIVE_TO'], 'YYYY-MM-DDXXXhh:mm:ss')) ?>"></div>
                        <div class="already-sold">
                            <div class="value countdown-amount"><?= intVal($arOffer['CATCHBUY']['PERCENT']) ?>
                                %
                            </div>
                            <div class="countdown-period"><?= GetMessage('BITRONIC2_SOLD') ?></div>
                        </div>
                        <div class="already-sold__track">
                            <div class="bar"
                                 style="width: <?= floatval($arOffer['CATCHBUY']['PERCENT']) ?>%"></div>
                        </div>
                    </div>
                </div>
            <? endif; ?>
            <? if ($arOffer['MORE_PHOTO_COUNT'] > 1): ?>
                <div class="carousel-inner product-photo">
                    <? $indexMorePhoto = 0; ?>
                    <? foreach ($arOffer['MORE_PHOTO'] as $key => $arPhoto): ?>
                        <? if (strval($key) == 'VIDEO') continue; ?>
                        <div class="item <?= $indexMorePhoto == 0 ? 'active' : '' ?>">
                            <div itemscope itemtype="http://schema.org/ImageObject" class="img-wrap">
                                <img
                                    class="lazy-sly"
                                    data-original="<?= $arPhoto['SRC_SMALL'] ?>"
                                    src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                    data-zoom="<?= $arPhoto['SRC_BIG'] ?>"
                                    data-src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                    data-big-src="<?= $arPhoto['SRC_BIG'] ?>"
                                    alt="<?= $arPhoto['ALT'] ?: $strAlt ?>"
                                    title="<?= $arPhoto['TITLE'] ?: $strTitle ?>"
                                    itemprop="image contentUrl">
                            </div>
                        </div>
                        <? $indexMorePhoto++ ?>
                    <? endforeach; ?>
                    <? if (!empty($arOffer['MORE_PHOTO']['VIDEO'])): ?>
                        <? foreach ($arOffer['MORE_PHOTO']['VIDEO'] as $video): ?>
                            <div class="item has-video">
                                <div class="video-wrap-outer">
                                    <div class="video-wrap-inner">
                                        <div class="video" data-src="<?= $video; ?>"></div>
                                    </div>
                                </div>
                            </div>
                        <? endforeach ?>
                    <? endif ?>
                </div>
            <? else: ?>
                <? $arPhoto = array_shift($arOffer['MORE_PHOTO']) ?>
                <div class="carousel-inner product-photo">
                    <div class="item active">
                        <div itemscope itemtype="http://schema.org/ImageObject" class="img-wrap">
                            <img
                                class="lazy-sly"
                                data-original="<?= $arPhoto['SRC_SMALL']  ?>"
                                src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                data-zoom="<?= $arPhoto['SRC_BIG'] ?>"
                                id="<?= $arItemIDs['PICT'].$arOffer['ID'] ?>"
                                data-src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                data-big-src="<?= $arPhoto['SRC_BIG'] ?>"
                                alt="<?= $arPhoto['ALT'] ?: $strAlt ?>"
                                title="<?= $arPhoto['TITLE'] ?: $strTitle ?>"
                                itemprop="image contentUrl">
                        </div>
                    </div>
                    <? if (!empty($arOffer['MORE_PHOTO']['VIDEO'])): ?>
                        <? foreach ($arOffer['MORE_PHOTO']['VIDEO'] as $video): ?>
                            <div class="item has-video">
                                <div class="video-wrap-outer">
                                    <div class="video-wrap-inner">
                                        <div class="video" data-src="<?= $video; ?>"></div>
                                    </div>
                                </div>
                            </div>
                        <? endforeach ?>
                    <? endif ?>
                </div>
            <? endif ?>
            <?
            if ($arOffer['MORE_PHOTO_COUNT'] > 1):?>
                <div class="thumbnails-wrap active">
                    <button type="button" class="thumb-control prev btn-silver">
                        <i class="flaticon-key22 arrow-up"></i>
                        <i class="flaticon-arrow133 arrow-left"></i>
                    </button>
                    <button type="button" class="thumb-control next btn-silver">
                        <i class="flaticon-arrow128 arrow-down"></i>
                        <i class="flaticon-right20 arrow-right"></i>
                    </button>
                    <div class="thumbnails-frame">
                        <div class="thumbnails-slidee">
                            <? foreach ($arOffer['MORE_PHOTO'] as $key => $arPhoto): ?>
                                <? if (strval($key) == 'VIDEO') continue; ?>
                                <div itemscope itemtype="http://schema.org/ImageObject" class="thumb">
                                    <img class="lazy-sly"
                                         data-original="<?= $arPhoto['SRC_ICON'] ?>"
                                         src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                         data-src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                         alt="<?= $arPhoto['ALT'] ?: $strAlt ?>"
                                         title="<?= $arPhoto['TITLE'] ?: $strTitle ?>"
                                         data-med-src="<?= $arPhoto['SRC_SMALL'] ?>"
                                         data-big-src="<?= $arPhoto['SRC_BIG'] ?>"
                                         itemprop="contentUrl"
                                    >
                                </div>
                            <? endforeach ?>
                            <? if (!empty($arOffer['MORE_PHOTO']['VIDEO'])): ?>
                                <? foreach ($arOffer['MORE_PHOTO']['VIDEO'] as $video): ?>
                                    <div class="thumb has-video">
                                        <i class="flaticon-movie16"></i>
                                    </div>
                                <? endforeach ?>
                            <? endif ?>
                        </div><!-- .thumbnails-slidee -->
                    </div><!-- .thumbnails-frame -->
                </div><!-- /.thumbnails -->
            <? endif; ?>
        </div>
    <? endforeach ?>
<?endif;