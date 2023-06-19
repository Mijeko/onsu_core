<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

$bNoIndex = $arParams['NOINDEX'] == 'Y';
if (empty($arParams['FILE']) && !\Yenisite\Core\Tools::isEditModeOn()) return;

$id = 'bxdinamic_banner_'.$arResult['BANNER_PROPERTIES']['IMAGE_ID'].'_'.$this->randString();
?>


<? include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/include/debug_info.php' ?>
<? if (!empty($arResult['BANNERS_PROPERTIES']) && is_array($arResult['BANNERS_PROPERTIES']) || (\Yenisite\Core\Tools::isEditModeOn())):
	$arParams['QUANTITY'] = min(10, (int)$arParams['QUANTITY']);
    $arParams['QUANTITY'] = empty($arParams['QUANTITY']) && \Yenisite\Core\Tools::isEditModeOn() ? 2 : $arParams['QUANTITY'] ;
	$bannerCount = count($arResult['BANNERS_PROPERTIES']);
    $bannerCount = !$bannerCount && \Yenisite\Core\Tools::isEditModeOn() ? $arParams['QUANTITY'] : $bannerCount;
	?>
	<div id="<?=$id?>" class="banners-place drag-section <?= $arParams['PLACE_CLASS'] ?: '' ?>" data-order="<?=$arParams['ORDER_BANNER']?>">
        <? $frame = $this->createFrame($id, false)->begin(CRZBitronic2Composite::insertCompositLoader()) ?>
	<? for ($i = 0; $i < $arParams['QUANTITY'] && $i < $bannerCount; $i++):
		$arBannerProp = $arResult['BANNERS_PROPERTIES'][$i];
        $arBannerProp["IMAGE_URL"] = empty($arBannerProp["IMAGE_URL"]) && \Yenisite\Core\Tools::isEditModeOn() ? SITE_TEMPLATE_PATH.'/img/def-banner.png' : $arBannerProp["IMAGE_URL"];
		?>

		<div class="banner-<?= $arParams['QUANTITY'] ?>">
			<? if ($bNoIndex): ?><!--noindex--><? endif ?>

			<a itemscope itemtype="http://schema.org/ImageObject" href="<?=$arBannerProp["URL_TARGET"]?>" <?
				if (strlen(trim($arBannerProp["URL_TARGET"]))>0):
				?> target="<?=$arBannerProp['URL_TARGET']?>"<?
				endif
				?><?= ($bNoIndex? ' rel="nofollow"':'') ?>>
				<img itemprop="contentUrl" class="lazy" data-original="<?=$arBannerProp["IMAGE_URL"]?>"
                     src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=
					htmlspecialcharsEx(trim($arBannerProp["IMAGE_ALT"]))
				?>"
                     title="<?=htmlspecialcharsEx(trim($arBannerProp["IMAGE_ALT"]))
				?>">
			</a>
			<? if ($bNoIndex): ?><!--/noindex--><? endif ?>

		</div>
	<? endfor ?>
        <? if ('Y' === $arParams['FILTER']): ?>
            <script>
                jQuery(function($){
                    //fix for composite mode
                    var $bannerPlace = $('#filter-at-side > div > .banners-place, #filter-at-top > div > .banners-place');
                    if ($bannerPlace.length < 1) return;

                    $bannerPlace.removeClass('banners-place')
                        .parent().addClass('banners-place')
                        .find('script').remove();
                });
            </script>
        <? endif ?>
        <? $frame->end() ?>
	</div>
<? endif ?>



