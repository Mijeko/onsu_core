<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

$bNoIndex = $arParams['NOINDEX'] == 'Y';

$id = 'bxdinamic_banner_'.$arResult['BANNER_PROPERTIES']['CONTRACT_ID'].'_'.$arResult['BANNER_PROPERTIES']['TYPE_SID'].$this->randString();
?>


<? include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/include/debug_info.php' ?>
<? if (!empty($arResult['BANNERS_PROPERTIES']) && is_array($arResult['BANNERS_PROPERTIES'])):
	$arParams['QUANTITY'] = min(10, (int)$arParams['QUANTITY']);
	$bannerCount = count($arResult['BANNERS_PROPERTIES']);
	?>
	<div id="<?=$id?>" class="banners-place drag-section <?= $arParams['PLACE_CLASS'] ?: '' ?>" data-order="<?=$arParams['ORDER_BANNER']?>">
        <? $frame = $this->createFrame($id, false)->begin(CRZBitronic2Composite::insertCompositLoader()) ?>
	<? for ($i = 0; $i < $arParams['QUANTITY'] && $i < $bannerCount; $i++):
		$arBannerProp = $arResult['BANNERS_PROPERTIES'][$i];
		?>

		<div itemscope itemtype="http://schema.org/ImageObject" class="banner-<?= $arParams['QUANTITY'] ?>">
			<? if ($bNoIndex): ?><!--noindex--><? endif ?>

			<a href="<?=
				CAdvBanner::GetRedirectURL(CAdvBanner::PrepareHTML($arBannerProp['URL'], $arBannerProp), $arBannerProp)
				?>"<?
				if (strlen(trim($arBanner["URL_TARGET"]))>0):
				?> target="<?=$arBannerProp['URL_TARGET']?>"<?
				endif
				?><?= ($bNoIndex? ' rel="nofollow"':'') ?>>
				<img itemprop="contentUrl" class="lazy" data-original="<?= CFile::GetPath($arBannerProp["IMAGE_ID"]) ?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=
					htmlspecialcharsEx(CAdvBanner::PrepareHTML(trim($arBannerProp["IMAGE_ALT"]) ?: $arBannerProp['NAME'], $arBanner))
				?>"
                     title="<?=htmlspecialcharsEx(CAdvBanner::PrepareHTML(trim($arBannerProp["IMAGE_ALT"]) ?: $arBannerProp['NAME'], $arBanner))
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



