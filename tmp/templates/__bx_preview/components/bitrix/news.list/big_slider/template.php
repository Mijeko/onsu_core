<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

global $rz_b2_options;

$this->setFrameMode(true);
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';

$itemsCount = count($arResult['ITEMS']);
if(empty($arParams['bs_height'])) {
	$arParams['bs_height'] = '24.30%';
}
?>
<div class="big-slider container drag-section sBigSlider <?=$arParams["SLIDER_WIDTH"]?>" id="big-slider-wrap" data-big-slider-width="<?=$arParams["SLIDER_WIDTH"]?>"
 data-order="<?=$arParams['SLIDER_ORDER']?>">
    <div id="catalog-at-side" class="catalog-at-side full">
		<?if(!empty($arParams['MENU_CATALOG'])):?>
			<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php", "PATH" => SITE_DIR."include_areas/header/menu_catalog.php"), false, array("HIDE_ICONS"=>"Y"));?>
		<?endif?>
	</div>
	<div class="container" id="big-slider">
		<?if(empty($arParams['MENU_CATALOG']) || $rz_b2_options["block_home-main-slider"] == 'Y'):?>
		<div class="controls">
			<?if($itemsCount > 1):?>
				<i class="flaticon-arrow133 arrow prev"></i>
				<i class="flaticon-right20 arrow next"></i>
				<div class="dots">
					<?for($i = 0; $i<$itemsCount; $i++):?>
						<i class="dot <?=($i==0) ? 'active' : ''?>"></i>
					<?endfor?>
				<?/*<i class="dot"></i>
					<i class="dot"></i> */?>
				</div>
			<?endif?>
		</div><!-- /.controls -->
		<?endif?>
		<? /**
		* @see https://bitbucket.org/yenisite/front.bitronic2/src/5be4e628b1a86f0f6f724e89363bc39e68a9b7d6/src/_/page-parts/big-slider.html?at=master&fileviewer=file-view-default#big-slider.html-19
		 **/?>
		<div class="content" style="padding-bottom: <?=$arParams['bs_height']?>" data-bs_height="<?=$arParams['bs_height']?>">
			<?if(empty($arParams['MENU_CATALOG']) || $rz_b2_options["block_home-main-slider"] == 'Y'):?>
			<?foreach($arResult['ITEMS'] as $arItem):
				// echo "<pre style='text-align:left;'>";print_r($arItem);echo "</pre>";
				$bFirst = $arItem == $arResult['ITEMS'][0];
				if(!empty($arItem['PREVIEW_PICTURE']['ALT']))
				{
					$imgAlt = $arItem['PREVIEW_PICTURE']['ALT'];
				}
				elseif(!empty($arItem['PREVIEW_PICTURE']['DESCRIPTION']))
				{
					$imgAlt = $arItem['PREVIEW_PICTURE']['DESCRIPTION'];
				}
				else
				{
					$imgAlt = $arItem['NAME'];
				}
				$strLink = $arItem['PROPERTIES']['LINK']['VALUE'];
				$imgTag = empty($strLink) ? 'span' : 'a href="'.$strLink.'"';
				
				$bVideo = (!empty($arItem['PROPERTIES']['VIDEO']['VALUE']));
				$bFullWidth = $arItem['PROPERTIES']['VIDEO_FULL_WIDTH']['VALUE'] != false;
				if($bVideo)
				{
					$videoId   = $arItem['PROPERTIES']['VIDEO']['VALUE'];
					$bAutoPlay = $arItem['PROPERTIES']['AUTOPLAY']['VALUE'] != false;
					$bMute     = $arItem['PROPERTIES']['MUTE']['VALUE']     != false;
					$videoParams = str_replace('%VIDEO_ID%', $videoId, $arParams['YOUTUBE_PARAMETERS']);
				}
				?>
				<div <?if(!$bVideo):?> itemscope itemtype="http://schema.org/ImageObject" <?endif?> class="slide <?=$bFirst ? 'active' : ''?>">
					<?if($bVideo):?>
						<!-- right full-width -->
						<div class="media <?=$bFullWidth ? 'full-width' : ''?>"
							<? if(!$bFullWidth): ?>style="left:<?= $arParams['bs_media_limits_left']?>; right:<?= $arParams['bs_media_limits_right']?><?
								if ('0%' != $arParams['bs_media_limits_top']):?>; top:<?= $arParams['bs_media_limits_top']?><? endif;
								if ('0%' != $arParams['bs_media_limits_bottom']):?>; bottom:<?= $arParams['bs_media_limits_bottom']?><? endif;
							?>"<? endif ?>
							 data-v-align="<?= $arParams['bs_media_v-align'] ?>" data-h-align="<?= $arParams['bs_media_h-align'] ?>">
							<div class="wrap video-wrap-outer">
								<div class="video-wrap-inner">
									<div class="player-here" data-videoid="<?= $videoId ?>" data-parameters="<?= $videoParams ?>"
										<?=$bAutoPlay ? ' data-autoplay' : ''?><?=$bMute ? ' data-muted' : ''?>>
									</div>
								</div><!-- .video-wrap-inner -->
							</div><!-- .inner.video-wrap-outer -->
						</div><!-- .media -->
					<?elseif (!empty($arItem['PREVIEW_PICTURE']['SRC'])):?>
						<!-- right left full-width-->
						<<?=$imgTag?> data-picture data-alt="<?=$imgAlt?>" class="media <?=$bFullWidth ? 'full-width' : ''?>"
							<? if(!$bFullWidth): ?>style="left:<?= $arParams['bs_media_limits_left']?>; right:<?= $arParams['bs_media_limits_right']?><?
								if ('0%' != $arParams['bs_media_limits_top']):?>; top:<?= $arParams['bs_media_limits_top']?><? endif;
								if ('0%' != $arParams['bs_media_limits_bottom']):?>; bottom:<?= $arParams['bs_media_limits_bottom']?><? endif;
							?>" data-v-align="<?= $arParams['bs_media_v-align'] ?>"<? else: ?>data-v-align="center"<? endif ?> data-h-align="<?= $arParams['bs_media_h-align'] ?>">
							<span data-src="<?=($arResult['RESIZER']['bUseFrom1200']) ? CResizer2Resize::ResizeGD2($arItem['PREVIEW_PICTURE']['SRC'], $arParams['RESIZER_SET_FROM_1200']) : $arItem['PREVIEW_PICTURE']['SRC']?>"></span>
							<span data-src="<?=CResizer2Resize::ResizeGD2($arItem['PREVIEW_PICTURE']['SRC'], $arParams['RESIZER_SET_1200'])?>" data-media="(max-width: 1200px)"></span>
							<span data-src="<?=CResizer2Resize::ResizeGD2($arItem['PREVIEW_PICTURE']['SRC'], $arParams['RESIZER_SET_991'])?>" data-media="(max-width: 991px)"></span>
							<span data-src="" data-media="(max-width: 767px)"></span>

							<!-- Fallback content for non-JS browsers. Same img src as the initial, unqualified source element. -->
							<noscript>
								<img itemprop="contentUrl" src="<?=($arResult['RESIZER']['bUseFrom1200']) ? CResizer2Resize::ResizeGD2($arItem['PREVIEW_PICTURE']['SRC'], $arParams['RESIZER_SET_FROM_1200']) : $arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$imgAlt?>">
							</noscript>
						</<?=strtok($imgTag, ' ')?>>
					<?endif?>
					<?if(!$bFullWidth):?>
						<!-- right -->
						<div class="text" style="right: <?= $arParams['bs_text_limits_right'] ?>; left: <?=$arParams['bs_text_limits_left']?><?
								if ('0%' != $arParams['bs_text_limits_top']):?>; top:<?= $arParams['bs_text_limits_top']?><? endif;
								if ('0%' != $arParams['bs_text_limits_bottom']):?>; bottom:<?= $arParams['bs_text_limits_bottom']?><? endif;
							?>" data-h-align="<?= $arParams['bs_text_h-align'] ?>" data-v-align="<?= $arParams['bs_text_v-align'] ?>" data-text-align="<?= $arParams['bs_text_text-align'] ?>">
							<div class="wrap">
								<div class="content">
									<header><?= $arItem['NAME'] ?></header>
									<div class="slogan"><?= $arItem['PREVIEW_TEXT'] ?></div>
									<?= $arItem['DETAIL_TEXT'] ?>
									<? if (!empty($strLink)):?>
										<div class="buttons">
											<a href="<?=$strLink?>" class="btn-view"><span
													class="text"><?= GetMessage('BITRONIC2_BIG_SLIDER_SHOW') ?></span></a>
										</div>
									<?endif ?>
								</div>
							</div>
						</div>
					<?endif?>
				</div><!-- /.slide -->
			<?endforeach?>
			<?endif?>
		</div><!-- /.content -->
	</div><!-- /.container -->
</div><!-- /.big-slider -->
<?
// echo "<pre style='text-align:left;'>";print_r($arResult['ITEMS']);echo "</pre>";