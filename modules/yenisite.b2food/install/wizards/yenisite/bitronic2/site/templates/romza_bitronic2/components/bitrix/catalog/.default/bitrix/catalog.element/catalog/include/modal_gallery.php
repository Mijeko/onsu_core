<? // MORE_PHOTO
if ($arResult['MORE_PHOTO_COUNT'] > 0 || $arResult['bSkuExt']):?>
    <div class="modal modal_big-img <?= $arResult['MORE_PHOTO_COUNT'] == 1 ? ' single-img' : '' ?>" id="modal_big-img"
         role="dialog"
         tabindex="-1" data-view-type="<?= $arParams['DETAIL_GALLERY_TYPE'] ?>">
        <button class="btn-close" data-toggle="modal" data-target="#modal_big-img">
            <i class="flaticon-close47"></i>
        </button>
        <? if (!$arResult['bSkuExt']): ?>
            <div class="gallery-carousel carousel slide" data-interval="0" id="modal-gallery">
                <div class="carousel-inner bigimg-wrap"
                     data-bigimg-desc="<?= $arParams['DETAIL_GALLERY_DESCRIPTION'] ?>">
                    <button type="button" class="img-control prev disabled">
                        <i class="flaticon-arrow133 arrow-left"></i>
                    </button>
                    <? if ($arResult['MORE_PHOTO_COUNT'] > 1): ?>
                        <? foreach ($arResult['MORE_PHOTO'] as $key => $arPhoto): ?>
                            <? if (strval($key) == 'VIDEO') continue; ?>
                            <div class="item <?= $key == 0 ? 'active' : '' ?>">
                                <div itemscope itemtype="http://schema.org/ImageObject" class="img-wrap">
                                    <img class="big-img lazy-sly"
                                         id="<?= $arItemIDs['PICT'].$key.'_modal' ?>"
                                         data-original="<?= $arPhoto['SRC_BIG'] ?>"
                                         src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                         data-src="<?= $arPhoto['SRC_BIG'] ?>"
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
                                    class="big-img lazy-sly"
                                    id="<?= $arItemIDs['PICT'].'_modal' ?>"
                                    data-original="<?=  $arResult['MORE_PHOTO'][0]['SRC_BIG'] ?>"
                                    src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                    data-src="<?= $arResult['MORE_PHOTO'][0]['SRC_BIG'] ?>"
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
                    <button type="button" class="img-control next">
                        <i class="flaticon-right20 arrow-right"></i>
                    </button>
                    <div class="img-desc" style="font-size: 18px">
                        <?= $arResult['MORE_PHOTO'][0]['DESCRIPTION'] ?: $arResult['NAME'] ?>

                    </div>
                    <button class="btn-close">
                        <i class="flaticon-close47"></i>
                    </button>
                </div>
                <div class="bigimg-thumbnails-wrap">
                    <? if ($arResult['MORE_PHOTO_COUNT'] > 1): ?>
                        <div class="thumbnails-frame bigimg-thumbs active" id="bigimg-thumbnails-frame">
                            <div class="thumbnails-slidee" id="bigimg-thumbnails-slidee">
                                <? foreach ($arResult['MORE_PHOTO'] as $key => $arPhoto): ?>
                                    <? if (strval($key) == 'VIDEO') continue; ?>
                                    <?
                                    $descr = $arPhoto['DESCRIPTION'];
                                    if (empty($descr)) {
                                        $descr = $arResult['NAME'];
                                    }
                                    ?>
                                    <div itemscope itemtype="http://schema.org/ImageObject" class="thumb">
                                        <img class="lazy-sly"
                                             alt="<?= $arPhoto['ALT'] ?: $strAlt ?>"
                                             title="<?= $arPhoto['TITLE'] ?: $strTitle ?>"
                                             data-original="<?= $arPhoto['SRC_ICON'] ?>"
                                             src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                             data-src="<?= $arPhoto['SRC_ICON'] ?>"
                                             data-med-src="<?= $arPhoto['SRC_SMALL'] ?>"
                                             data-big-src="<?= $arPhoto['SRC_BIG'] ?>"
                                             data-img-desc="<?= htmlspecialcharsEx($descr) ?>"
                                             itemprop="contentUrl">
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
                            </div><!-- #bigimg-thumbnails-slidee -->
                        </div><!-- #bigimg-thumbnails-frame -->
                    <? endif ?>
                </div><!-- /.thumbnails -->
            </div>
        <? else: ?>
            <? foreach ($arResult['OFFERS'] as $arOffer): ?>
                <div class="gallery-carousel carousel slide" data-interval="0"
                     id="<? echo $arItemIDs['SLIDER_CONT_OF_MODAL_INNER_ID'] . $arOffer['ID']; ?>"
                     style="display:none">
                    <div class="carousel-inner bigimg-wrap"
                         data-bigimg-desc="<?= $arParams['DETAIL_GALLERY_DESCRIPTION'] ?>">
                        <button type="button" class="img-control prev disabled">
                            <i class="flaticon-arrow133 arrow-left"></i>
                        </button>
                        <? if ($arOffer['MORE_PHOTO_COUNT'] > 1): ?>
                            <? $indexMorePhoto = 0; ?>
                            <? foreach ($arOffer['MORE_PHOTO'] as $key => $arPhoto): ?>
                                <? if (strval($key) == 'VIDEO') continue; ?>
                                <div class="item <?= $indexMorePhoto == 0 ? 'active' : '' ?>">
                                    <div itemscope itemtype="http://schema.org/ImageObject" class="img-wrap">
                                        <img class="big-img lazy-sly"
                                             data-original="<?= $arPhoto['SRC_BIG'] ?>"
                                             src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                             data-big-src="<?= $arPhoto['SRC_BIG'] ?>"
                                             data-src="<?= $arPhoto['SRC_SMALL'] ?>"
                                             alt="<?= $arPhoto['ALT'] ?: $strAlt ?>"
                                             title="<?= $arPhoto['TITLE'] ?: $strTitle ?>"
                                             itemprop="image contentUrl">
                                    </div>
                                </div>
                                <? $indexMorePhoto++; ?>
                            <? endforeach ?>
                            <?
                        else: ?>
                            <? $arPhoto = array_shift($arOffer['MORE_PHOTO']) ?>
                            <div class="item active">
                                <div itemscope itemtype="http://schema.org/ImageObject" class="img-wrap">
                                    <img
                                        class="big-img lazy-sly"
                                        id="<?= $arItemIDs['PICT_MODAL'].$arOffer['ID'] ?>"
                                        data-original="<?= $arPhoto['SRC_BIG'] ?>"
                                        src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                        data-big-src="<?= $arPhoto['SRC_BIG'] ?>"
                                        data-src="<?= $arPhoto['SRC_SMALL'] ?>"
                                        alt="<?= $strAlt ?>"
                                        title="<?= $strTitle ?>"
                                        itemprop="image contentUrl">
                                </div>
                            </div>
                        <? endif ?>
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
                        <button type="button" class="img-control next">
                            <i class="flaticon-right20 arrow-right"></i>
                        </button>
                        <div class="img-desc" style="font-size: 18px">
                            <?= $arOffer['MORE_PHOTO'][0]['DESCRIPTION'] ?: $arOffer['NAME'] ?>

                        </div>
                        <button class="btn-close">
                            <i class="flaticon-close47"></i>
                        </button>
                    </div>
                    <? if ($arOffer['MORE_PHOTO_COUNT'] > 1): ?>
                        <div class="bigimg-thumbnails-wrap"
                             id="<? echo $arItemIDs['SLIDER_MODAL_CONT_OF_ID'] . $arOffer['ID']; ?>"
                             style="display:none">
                            <div class="thumbnails-frame bigimg-thumbs active">
                                <div class="thumbnails-slidee">
                                    <? foreach ($arOffer['MORE_PHOTO'] as $key => $arPhoto):
                                        $descr = $arPhoto['DESCRIPTION'];
                                        if (empty($descr)) {
                                            $descr = $arResult['NAME'];
                                        }
                                        ?>
                                        <? if (strval($key) == 'VIDEO') continue; ?>
                                        <div itemscope itemtype="http://schema.org/ImageObject" class="thumb">
                                            <img class="lazy-sly"
                                                 alt="<?= $arPhoto['ALT'] ?: $strAlt ?>"
                                                 title="<?= $arPhoto['TITLE'] ?: $strTitle ?>"
                                                 data-original="<?= $arPhoto['SRC_ICON'] ?>"
                                                 src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                                                 data-src="<?= $arPhoto['SRC_ICON'] ?>"
                                                 data-med-src="<?= $arPhoto['SRC_SMALL'] ?>"
                                                 data-big-src="<?= $arPhoto['SRC_BIG'] ?>"
                                                 data-img-desc="<?= htmlspecialcharsEx($descr) ?>"
                                                 itemprop="contentUrl">
                                        </div>
                                    <? endforeach ?>
                                    <? if (!empty($arOffer['MORE_PHOTO']['VIDEO'])): ?>
                                        <? foreach ($arOffer['MORE_PHOTO']['VIDEO'] as $video): ?>
                                            <div class="thumb has-video">
                                                <i class="flaticon-movie16"
                                                   data-video="<?= $video ?>"
                                                ></i>
                                            </div>
                                        <? endforeach ?>
                                    <? endif ?>
                                </div><!-- #bigimg-thumbnails-slidee -->
                            </div><!-- #bigimg-thumbnails-frame -->
                        </div>
                    <? endif ?>
                </div>
            <? endforeach; ?>
        <? endif ?>
    </div>
<? endif ?>