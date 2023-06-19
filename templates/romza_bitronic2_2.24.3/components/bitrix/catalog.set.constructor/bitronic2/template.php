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
$this->setFrameMode(true);

$modalId = 'modal_custom-collection' . $arResult['ELEMENT']['ID'];
?>
<div class="collection-wrap row wow fadeIn drag-section sPrCollection" data-order="<?=$arParams['ORDER_DETAIL_BLOCKS']['order-sPrCollection']?>">
	<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';?>
	<div class="col-xs-12">
		<div class="main-header">
			<div class="content">
				<span class="text"><?=GetMessage('BITRONIC2_CATALOG_SET_TITLE')?>:</span>
				<i class="flaticon-shopping63"></i>
				<span class="sign plus">+</span>
				<i class="flaticon-shopping63"></i>
				<span class="sign equal">=</span>
				<i class="flaticon-sale sale"></i>
			</div>
		</div>
		<div class="sub-header">
			<span class="text"><?=GetMessage('BITRONIC2_CATALOG_SET_SUBTITLE')?>:</span>
			<a href="#" class="custom-collection" data-toggle="modal" data-target="#<?=$modalId?>" data-id="<?=$arResult['ELEMENT']['ID']?>">
				<span class="sign plus">
					<i class="flaticon-shopping63"></i>
					+
					<i class="flaticon-shopping63"></i>
				</span>				
				<span class="text"><?=GetMessage('BITRONIC2_CATALOG_SET_CONSTRUCT')?></span>
			</a>
		</div>
			
		<!-- table wrapper needed to set z-index for table block
		otherwise, opened SKU select hides somewhere under the footer
		which follows the table -->
		<div class="table-wrapper">
			<div class="col-wrap">
				<div class="collection">
					<?foreach($arResult["SET_ITEMS"]["DEFAULT"] as $key => $arItem):?>
						<div class="item" <?=$arItem['OFFERS_PROP_CODES'] ? 'data-props="'.$arItem['OFFERS_PROP_CODES'].'"' : ''?> data-item-id="<?=$arItem['ID']?>">
							<div itemscope itemtype="http://schema.org/ImageObject" class="photo">
								<img itemprop="contentUrl" class="lazy" data-original="<?=$arItem['PICTURE_PRINT']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">
							</div>
							<div class="main-data">
								<a href="<?=$arItem['DETAIL_PAGE_URL']?>"><span class="text"><?=$arItem['NAME']?></span></a>
								<? if ($arParams['SHOW_STARS'] == 'Y'): ?>
									<div class="rating">
										<?$APPLICATION->IncludeComponent("bitrix:iblock.vote", "stars", array(
											"IBLOCK_TYPE" => $arItem['IBLOCK_TYPE_ID'],
											"IBLOCK_ID" => $arItem['IBLOCK_ID'],
											"ELEMENT_ID" => $arItem['ID'],
											"CACHE_TYPE" => $arParams["CACHE_TYPE"],
											"CACHE_TIME" => $arParams["CACHE_TIME"],
											"MAX_VOTE" => "5",
											"VOTE_NAMES" => array("1", "2", "3", "4", "5"),
											"SET_STATUS_404" => "N",
											),
											$component, array("HIDE_ICONS"=>"Y")
										);?>
									</div><!-- /.rating -->
								<? endif ?>
								<div class="price-wrap">
									<?if($arItem['PRICE_DISCOUNT_DIFFERENCE_VALUE'] > 0):?>
										<span class="price-old"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['PRICE_CURRENCY'], $arItem['PRICE_VALUE'], $arItem['PRICE_PRINT_VALUE']);?></span>
									<?endif?>
									<span class="price"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['PRICE_CURRENCY'], $arItem['PRICE_DISCOUNT_VALUE'], $arItem['PRICE_PRINT_DISCOUNT_VALUE']);?></span>
								</div>
							</div><!-- /.main-data -->
						</div><!-- /.item -->
						<div class="sign">+</div>
					<?endforeach?>
					
					<div class="item main-item" <?=$arResult['OFFERS_PROP_CODES'] ? 'data-props="'.$arResult['OFFERS_PROP_CODES'].'"' : ''?> data-item-id="<?=$arResult['ELEMENT']['ID']?>">
						<div class="buy-block-wrap in-collection">
							<div class="buy-block-main __slim">
								<div class="buy-block-content">
									<div class="product-name">
										<?=$arResult['ELEMENT']['NAME']?>
									</div>
									<div itemscope itemtype="http://schema.org/ImageObject" class="product-main-photo">
										<img itemprop="contentUrl" class="lazy" data-original="<?=$arResult['ELEMENT']['PICTURE_PRINT']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" title="<?=$arResult['ELEMENT']['NAME']?>" alt="<?=$arResult['ELEMENT']['NAME']?>">
									</div>
									<div class="price-wrap">
										<div class="price-values">
											<span class="text">
												<?=GetMessage('BITRONIC2_CATALOG_SET_PRICE')?>: 
												<?if($arResult['ELEMENT']['PRICE_DISCOUNT_DIFFERENCE_VALUE'] > 0):?>
													<span class="price-old"><?= CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['ELEMENT']['PRICE_CURRENCY'], $arResult['ELEMENT']['PRICE_VALUE'], $arResult['ELEMENT']['PRICE_PRINT_VALUE']) ?></span>
												<?endif?>
											</span>
											<div class="price">
												<?= CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['ELEMENT']['PRICE_CURRENCY'], $arResult['ELEMENT']['PRICE_DISCOUNT_VALUE'], $arResult['ELEMENT']['PRICE_PRINT_DISCOUNT_VALUE']) ?>

											</div>
										</div>
									</div><!-- .price-wrap -->
								</div><!-- /.buy-block-content -->
							</div><!-- /.buy-block-main -->
						</div><!-- /.buy-block-wrap -->
					</div>
				</div><!-- /.collection -->
			</div>
		</div>

		<footer class="clearfix">
			<i class="flaticon-shopping158"></i>
			<button type="button" class="btn-main"><?=GetMessage('BITRONIC2_CATALOG_SET_BUY')?></button>
			<div class="total">
				<span class="total-number"><?=count($arResult['DEFAULT_SET_IDS'])?> <?=GetMessage('BITRONIC2_CATALOG_SET_GOODS')?></span>
				<span class="text"><?=GetMessage('BITRONIC2_CATALOG_SET_ON_SUMM')?></span>
				<span class="price"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['ELEMENT']['PRICE_CURRENCY'], $arResult["SET_ITEMS"]["PRICE_NOT_FORMATED"], $arResult["SET_ITEMS"]["PRICE"]);?></span>
			</div>
			<?if($arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE_NOT_FORMATED"] > 0):?>
			<div class="total-full">
				<span class="text"><?=GetMessage('BITRONIC2_CATALOG_SET_WITHOUT_DISCOUNT')?>:</span>
				<span class="price-old"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['ELEMENT']['PRICE_CURRENCY'], $arResult["SET_ITEMS"]["OLD_PRICE_NOT_FORMATED"], $arResult["SET_ITEMS"]["OLD_PRICE"]);?></span>
				<span class="text">(<?=GetMessage('BITRONIC2_CATALOG_SET_ECONOM')?> <strong><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['ELEMENT']['PRICE_CURRENCY'], $arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE_NOT_FORMATED"], $arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE"]);?></strong>)</span>
			</div>
			<?endif?>
		</footer>
	</div><!-- /.col-xs-12 -->
</div><!-- /.collection-wrap -->

<!-- MODALS -->
<div class="modal fade modal_custom-collection-wrap" id="<?=$modalId?>" tabindex="-1">
	<div class="modal-dialog modal_custom-collection">
		<button class="btn-close" data-dismiss="modal">
			<span class="btn-text"><?=GetMessage('BITRONIC2_MODAL_CLOSE')?></span>
			<i class="flaticon-close47"></i>
		</button>
		<div class="content"></div>
	</div>
</div>

<?
$popupParams["ELEMENT"] = $arResult["ELEMENT"];
$popupParams["SET_ITEMS"] = $arResult["SET_ITEMS"];
$popupParams["DEFAULT_SET_IDS"] = $arResult["DEFAULT_SET_IDS"];
$popupParams["ITEMS_RATIO"] = $arResult["ITEMS_RATIO"];
?>
<script type="text/javascript">
require(['back-end/ajax/catalog_set_constructor'], function(){
	RZB2.ajax.SetConstructor<?=$arResult['ELEMENT']['ID']?> = new RZB2.ajax.CatalogSetConstructor.Popup({
		ID: '<?=$modalId?>',
		AJAX_FILE: SITE_DIR + "ajax/catalog_set_constructor.php",
		PARAMS: <?echo CUtil::PhpToJSObject($popupParams)?>,
	});
});
</script>

<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
?>