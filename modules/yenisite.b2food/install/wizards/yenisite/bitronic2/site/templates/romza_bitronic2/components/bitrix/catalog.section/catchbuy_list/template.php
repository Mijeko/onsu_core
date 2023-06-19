<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

// @var $moduleId
// @var $moduleCode
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';

if(empty($arResult['ITEMS'])) return;

$arJsCache = CRZBitronic2CatalogUtils::getJSCache($component);
$bHoverMode = $arParams['HOVER-MODE'] == 'detailed-expand';
$arParams['COLOR_HEADING'] = $arParams['COLOR_HEADING'] == 'Y';
$jsString = '';?>

<div class="hurry container wow fadeIn drag-section sHurry" data-order="<?=$arParams['HURRY_ORDER']?>">
	<?
	if($arParams['COLOR_HEADING']) {
		\Yenisite\Core\Tools::IncludeArea('index/catchbuy','heading', array(), false);
	} else {
		?>
		<div class="simple_heading text-content">
		<?
		\Yenisite\Core\Tools::IncludeArea('index/catchbuy','simple_heading', array(), false);
		?>
		</div>
		<?
	}
	?>
	<div id="hurry-carousel" class="catalog blocks hurry-carousel wow fadeIn"<?if($bHoverMode):?> data-hover-effect="detailed-expand"<?endif?>>
		<div class="content">
			<? foreach ($arResult['ITEMS'] as $arItem): 
				$this->AddEditAction($templateName.'-'.$arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
				$this->AddDeleteAction($templateName.'-'.$arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
				$strMainID = $this->GetEditAreaId($templateName.'-'.$arItem['ID']);
				$arItemIDs = array(
					'ID' => $strMainID,
					'QUANTITY' => $strMainID.'_quantity',
					'QUANTITY_DOWN' => $strMainID.'_quant_down',
					'QUANTITY_UP' => $strMainID.'_quant_up',
					'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
					'BUY_LINK' => $strMainID.'_buy_link',
					'BASKET_ACTIONS' => $strMainID.'_basket_actions',
					'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
					'COMPARE_LINK' => $strMainID.'_compare_link',
					'FAVORITE_LINK' => $strMainID.'_favorite_link',
					
					'OLD_PRICE' => $strMainID.'_old_price',
					'PRICE' => $strMainID.'_price',
					'DISCOUNT_DIFF' => $strMainID.'_discount_diff',
				);
				$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
				$imgTitle = (
					!empty($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'])
					? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
					: $arItem['NAME']
				);
                $bShowBlocksIfEmptySKU = ($arItem['bOffers'] && !empty($arItem['OFFERS'])) || !$arItem['bOffers'];
                $arParams['DISPLAY_FAVORITE'] = !$bShowBlocksIfEmptySKU ? $bShowBlocksIfEmptySKU : $arParams['DISPLAY_FAVORITE'];
				$arCatchBuy = $arParams['CATCHBUY'][$arItem['ID']];
				$bTimer = !empty($arCatchBuy['ACTIVE_TO']);
				$bProgressBar = $arCatchBuy['MAX_USES'] > 0;
				$arCatchBuy['PERCENT'] = ($bProgressBar) ? $arCatchBuy['COUNT_USES']/$arCatchBuy['MAX_USES'] * 100 : 0;
				?><div class="catalog-item-wrap active" id="<?=$arItemIDs['ID']?>">
					<div class="catalog-item hurry-item">
						<div class="photo-wrap">
							<div itemscope itemtype="http://schema.org/ImageObject" class="photo">
								<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
									<img itemprop="contentUrl"  class="lazy" data-original="<?=$arItem['PICTURE_PRINT']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['PICTURE_PRINT']['ALT']?>" title="<?= $imgTitle ?>">
								</a>
                                <?$frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader());?>
                                    <?$APPLICATION->IncludeComponent("yenisite:stickers", "section", array(
                                        "ELEMENT" => $arItem,
                                        "STICKER_NEW" => $arParams['STICKER_NEW'],
                                        "STICKER_HIT" => $arParams['STICKER_HIT'],
                                        "STICKER_CATCHBUY" => 'N',
                                        "SHOW_DISCOUNT_PERCENT" => $arParams['SHOW_DISCOUNT_PERCENT'],
                                        "CUSTOM_STICKERS" => $arItem['PROPERTIES'][iRZProp::STICKERS],
                                        ),
                                        $component, array("HIDE_ICONS"=>"Y")
                                    );?>
                                <?$frame->end()?>
							</div>
						</div><!-- /.photo-wrap -->
						<div class="main-data">
							<div class="name">
								<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link"><span class="text"><?=$arItem['NAME']?></span></a>
							</div>
							<div class="art-rate">
								<? if ($arParams['SHOW_ARTICLE'] == 'Y'): ?>
									<?if(!empty($arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])):?>
										<span class="art"><?=$arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['NAME']?>: <strong><?=is_array($arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) ? implode(' / ', $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) : $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']?></strong></span>
									<?endif?>
								<? endif ?>
								<? if ($arParams['SHOW_STARS'] == 'Y'): ?>
									<? $APPLICATION->IncludeComponent("bitrix:iblock.vote", "stars", array(
										"IBLOCK_TYPE" => $arItem['IBLOCK_TYPE_ID'],
										"IBLOCK_ID" => $arItem['IBLOCK_ID'],
										"ELEMENT_ID" => $arItem['ID'],
										"CACHE_TYPE" => $arParams["CACHE_TYPE"],
										"CACHE_TIME" => $arParams["CACHE_TIME"],
										"MAX_VOTE" => "5",
										"VOTE_NAMES" => array("1", "2", "3", "4", "5"),
										"SET_STATUS_404" => "N",
									),
										$component, array("HIDE_ICONS" => "Y")
									); ?>
								<? endif ?>
							</div>
							<div class="prices">
								<span class="price-old" id="<?=$arItemIDs['OLD_PRICE']?>">
<? $frame = $this->createFrame($arItemIDs['OLD_PRICE'], false)->begin('') ?>
<? if($arItem['MIN_PRICE']['DISCOUNT_DIFF'] > 0): ?>
									<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['VALUE'], $arItem['MIN_PRICE']['PRINT_VALUE']);?>

<? endif ?>
<? $frame->end() ?>
								</span>
                                <?if ($bShowBlocksIfEmptySKU):?>
                                    <span class="price" id="<?=$arItemIDs['PRICE']?>">
    <? $frame = $this->createFrame($arItemIDs['PRICE'], false)->begin(CRZBitronic2Composite::insertCompositLoader()) ?>
                                        <?=($arItem['bOffers'] && $arItem['bOffersNotEqual']) ? GetMessage('BITRONIC2_CATCH_BUY_FROM') : ''?>
                                        <?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);?>
    <? $frame->end() ?>
                                    </span>
                                <?endif?>
							</div>
						</div>
						<div class="economy">
							<span class="text"><?=GetMessage('BITRONIC2_CATCH_BUY_ECONOM')?></span>
							<span class="marked" id="<?=$arItemIDs['DISCOUNT_DIFF']?>">
<? $frame = $this->createFrame($arItemIDs['DISCOUNT_DIFF'], false)->begin(CRZBitronic2Composite::insertCompositLoader()) ?>
								<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_DIFF'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_DIFF']);?>
<? $frame->end() ?>
							</span>
						</div>
						<?if($bTimer):?>
						<div class="countdown">
							<span class="text"><?=GetMessage('BITRONIC2_CATCH_BUY_BY_END')?>:</span>
							<div class="timer-wrap">
								<i class="flaticon-stopwatch6 stopwatch"></i>
								<div class="timer" data-until="<?=str_replace('XXX', 'T', ConvertDateTime($arCatchBuy['ACTIVE_TO'], 'YYYY-MM-DDXXXhh:mm:ss'))?>"></div>
							</div>
						</div>
						<?endif?>
						<?if($bProgressBar):?>
						<div class="already-sold">
							<span class="already-sold__track">
								<span class="bar" style="width: <?=$arCatchBuy['PERCENT']?>%"></span>
								<span class="value"><?=intVal($arCatchBuy['PERCENT'])?>%</span>
							</span>
							<span class="text"><?=GetMessage('BITRONIC2_CATCH_BUY_ALREADY_BUY')?></span>
						</div>
						<div class="remaining">
							<span class="text"><?=GetMessage('BITRONIC2_CATCH_BUY_ALREADY_EXIST')?>:</span>
							<span class="value"><?=$arCatchBuy['MAX_USES'] - $arCatchBuy['COUNT_USES']?> <?=$arItem['CATALOG_MEASURE_NAME']?></span>
						</div>
						<?endif?>
						<? if ($bHoverMode):?>
						<div class="description full-view">
						<? endif ?>
							<div class="action-buttons" id="<?=$arItemIDs['BASKET_ACTIONS']?>">
								<?if ($arParams['DISPLAY_FAVORITE']):?>
									<button
										type="button"
										class="btn-action favorite"
										data-favorite-id="<?=$arItem['ID']?>"
										data-tooltip title="<?=GetMessage('BITRONIC2_CATCH_BUY_ADD_TO_FAVORITE')?>"
										id="<?=$arItemIDs['FAVORITE_LINK']?>">
										<i class="flaticon-heart3"></i>
									</button>
								<?endif?>
								<?if ($arParams['DISPLAY_COMPARE_SOLUTION'] && !$arItem['bOffers']):?>
									<button
										type="button"
										class="btn-action compare"
										data-compare-id="<?=$arItem['ID']?>"
										data-tooltip title="<?=GetMessage('BITRONIC2_CATCH_BUY_ADD_TO_COMPARE')?>"
										id="<?=$arItemIDs['COMPARE_LINK']?>">
										<i class="flaticon-balance3"></i>
									</button>
								<?endif?>
								<div class="btn-buy-wrap text-only">
									<button type="button" class="btn-action buy when-in-stock" id=<?=$arItemIDs['BUY_LINK']?> data-product-id="<?=$arItem['ID']?>">
										<i class="flaticon-shopping109"></i>
										<span class="text"><?=COption::GetOptionString($moduleId, 'button_text_buy')?></span>
										<span class="text in-cart"><?=COption::GetOptionString($moduleId, 'button_text_incart')?></span>
									</button>
								</div>
							</div>
						<? if ($bHoverMode):?>
							</div>
						<? endif ?>
					</div>
					<? // JS PARAMS
					include 'js_params.php';
					?>
				</div><!-- /.catalog-item-wrap --><?
			endforeach ?>
		</div>
		<div class="slider-controls-wrap controls">
			<a class="slider-arrow prev">
				<i class="flaticon-arrow133"></i>
				<span class="sr-only">Previous</span>
			</a><!--
			--><div class="dots">
			</div><!--
			--><span class="numeric"></span><!--
			--><a class="slider-arrow next">
				<i class="flaticon-right20"></i>
				<span class="sr-only">Next</span>
			</a> 
		</div><!-- /.slider-controls-wrap -->
		<!-- /.slider-controls-wrap -->
	</div>
	<!-- /.hurry-carousel -->
	<?
	if ($arJsCache['file']) {
		$bytes = fwrite($arJsCache['file'], $jsString);
		if ($bytes === false || $bytes != mb_strlen($jsString, 'windows-1251')) {
			fclose($arJsCache['file']);
			$arJsCache['file'] = false;
		}
	}
	$frame = $this->createFrame()->begin('');
	if (!$arJsCache['file']): ?>

	<script type="text/javascript">
		<?=$jsString?>
	</script>
	<?
	endif;
	$frame->end();

	if ($arJsCache['file']) {
		$templateData['jsFile'] = $arJsCache['path'].'/'.$arJsCache['idJS'];
		fclose($arJsCache['file']);
	} ?>
</div><!-- /.hurry -->

<?
unset($jsString);
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
?>
