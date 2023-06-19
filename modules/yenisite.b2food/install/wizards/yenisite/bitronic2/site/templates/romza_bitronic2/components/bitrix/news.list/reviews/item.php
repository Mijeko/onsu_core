<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<a id="<?= $this->GetEditAreaId($this->__name . $arItem['ID']) ?>" href="<?= $arItem['DETAIL_PAGE_URL']; ?>"
   class="item">
    <div class="review-item-img-wrap">
        <div class="review-item-img">
            <img class="lazy" src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                 data-original="<?= $arItem['PICTURE'] ?>" alt="<?= $arItem['NAME']; ?>">
        </div>
        <? if (!empty($arItem['DATE'])): ?>
            <div class="date-wrap">
                <div class="svg-wrap">
                    <svg>
                        <use xlink:href="#calendar"></use>
                    </svg>
                </div>
                <div class="date"><?= $arItem['DATE'] ?></div>
            </div>
        <? endif ?>
    </div>
    <div class="content">
        <div class="link"><span class="text"><?= $arItem['NAME'] ?></span></div>
        <? if (!empty($arItem['TEXT'])): ?>
            <div class="desc">
                <?= $arItem['TEXT']; ?>
            </div>
        <? endif; ?>
    </div><!-- /.content -->
</a><!-- /.item-->