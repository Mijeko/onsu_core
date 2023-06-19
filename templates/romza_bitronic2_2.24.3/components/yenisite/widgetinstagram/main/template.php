<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$this->setFrameMode(true);
if (empty($arResult['ITEMS']) && empty($arResult['USER'])) return;

$idOfPhotoWrap = 'photo_instagram_wrap_' . $this->randString();

$arUserData = $arResult['USER'];
$nameOfUser = $arUserData['USER_NAME'] ? $arUserData['USER_NAME'] : $arUserData['FULL_NAME'] ?>
<div class="social instagram">
    <div class="social-header">
        <div class="main-photo_wrap">
            <span class="icon">
                <svg>
                    <use xlink:href="#instagram"></use>
                </svg>
            </span>
            <img data-original="<?= $arUserData['IMAGE'] ?>" src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                 alt="<?= $nameOfUser ?>" class="lazy main_photo">
            <span class="login"><?= $nameOfUser ?></span>&nbsp;
            <span class="text"><?= isset($arParams['NAME_OF_WIDGET']) ? $arParams['NAME_OF_WIDGET'] : GetMessage('NAME_OF_WIDGET') ?></span>
        </div>
        <a href="https://www.instagram.com/<?= $arUserData['USER_NAME'] ?>/"
           class="btn-silver btn-subscribe"><?= GetMessage('SUBSCRIBE') ?></a>
    </div>
    <div class="social-content">
        <div id="<?= $idOfPhotoWrap ?>" class="photos-wrapper">
            <? $frame = $this->createFrame($idOfPhotoWrap, false)->begin(); ?>
            <? foreach ($arResult['ITEMS'] as $arItem): ?>
                <div class="photo-wrap">
                    <a target="_blank" class="photo_link" href="<?= $arItem['URL'] ?>"></a>
                    <img data-original="<?= $arItem['IMAGE'] ?>" src="<?= ConsVar::showLoaderWithTemplatePath() ?>"
                         alt="photo" class="lazy photo_img">
                    <div class="hover_block">
                         <span class="like">
                            <svg>
                                <use xlink:href="#like"></use>
                            </svg>
                            <span class="text"><?= $arItem['CNT_LIKES'] ?></span>
                         </span>
                        <span class="comments">
                            <svg>
                                <use xlink:href="#icon-comments"></use>
                            </svg>
                            <span class="text"><?= $arItem['CNT_COMMENT'] ?></span>
                        </span>
                    </div>
                </div>
            <? endforeach; ?>
            <? if (empty($arResult['ITEMS'])): ?>
                <p><?= GetMessage('USER_DOESNT_HAVE_IMAGES') ?></p>
            <? endif ?>
            <? $frame->end(); ?>
        </div>
    </div>
</div>

