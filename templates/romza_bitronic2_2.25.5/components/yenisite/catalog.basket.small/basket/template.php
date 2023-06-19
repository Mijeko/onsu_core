<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
$this->setFrameMode(true);

$id = 'bxdinamic_bitronic2_basket_string';
$id_header = 'bxdinamic_bitronic2_basket_header';
$id_footer = 'bxdinamic_bitronic2_basket_footer';
?>
<div class="top-line-item basket" id="basket">
	<a id="<?=$id?>" href="<?=$arParams['BASKET_URL']?>" class="btn-main btn-basket rz-no-pointer" data-popup="#popup_basket">
		<?$frame = $this->createFrame($id, false)->begin(CRZBitronic2Composite::insertCompositLoader());?>
			<i class="flaticon-shopping109">
				<span class="basket-items-number-sticker"><?=$arResult['TOTAL_COUNT']?></span>
			</i>
			<strong class="text-info"><?=GetMessage('BITRONIC2_BASKET_IN_BASKET')?></strong>
			<span class="text-content">
				<span class="basket-items-number"><?=$arResult['TOTAL_COUNT']?></span>
				<span class="basket-items-text hidden-xs"><?=$arResult['PRODUCT(S)']?></span>
				<span class="basket-simple-text hidden-xs"><?=GetMessage('BITRONIC2_BASKET_ON_SUMM')?></span>
				<strong class="basket-total-price hidden-xs"><?=$arResult["TOTAL_SUM_FORMATTED"]?></strong>
			</span>
		<?$frame->end();?>
	</a>
	<div class="top-line-popup popup_basket" id="popup_basket" data-darken >
		<button class="btn-close" data-popup="#popup_basket">
			<span class="btn-text"><?=GetMessage('BITRONIC2_MODAL_CLOSE')?></span>
			<i class="flaticon-close47"></i>
		</button>
		<div class="popup-header">
			<div id="<?=$id_header?>" class="header-text">
				<?$frame = $this->createFrame($id_header, false)->begin(CRZBitronic2Composite::insertCompositLoader());?>
				<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';?>
				<div class="basket-content">
					<div class="text"><?=GetMessage('BITRONIC2_BASKET_YOUR_CHOOSE', array('#COUNT#'=>$arResult['TOTAL_COUNT'], '#PRODUCT#'=>$arResult['PRODUCT(S)']))?></div>
					<span class="total-price"><?=$arResult["TOTAL_SUM_FORMATTED"]?></span>
				</div>
				<?$frame->end();?>
				<?
				/* TODO
				<div class="waitlist-content">
					<div><?=GetMessage('BITRONIC2_BASKET_IN_WAIT_LIST', array('#COUNT#'=>$arResult['TOTAL_COUNT']))?></div>
					<span class="total-price">1 000 000</span>
				</div>
				*/
				?>
			</div>
			<?
			/* TODO
			<button class="basket-waitlist-toggle">
				<span class="basket-content">
					<i class="flaticon-back15"></i>
					<span class="btn-text"><?=GetMessage('BITRONIC2_BASKET_WAIT')?><span class="hidden-xs"><?=GetMessage('BITRONIC2_BASKET_WAIT_GOODS')?></span>: <span class="items-in-waitlist">32</span></span>
				</span>
				<span class="waitlist-content">
					<i class="flaticon-shopping109"></i>
					<span class="btn-text"><?=GetMessage('BITRONIC2_BASKET_TO_BASKET')?></span>
				</span>
			</button>
			*/
			?>
		</div>
		<div class="table-wrap basket-small">
			<div class="scroller scroller_v">
				<div class="basket-content">
					<?$frame = $this->createFrame()->begin('');?>
					<table class="items-table">
						<? $arProductId = array();
						if(!empty($arResult["ITEMS"])):?>
							<thead>
								<tr>
									<th colspan="2"><?=GetMessage('BITRONIC2_BASKET_GOOD')?></th>
									<th class="availability"><?=GetMessage('BITRONIC2_BASKET_QUANTITY')?></th>
									<th class="price"><?=GetMessage('BITRONIC2_BASKET_PRICE_FOR_1')?></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?foreach($arResult["ITEMS"] as $arItem):
									$arProductId[$arItem['PRODUCT_ID']] = $arItem['PRODUCT_ID'];
									// echo "<pre style='text-align:left;'>";print_r($arItem);echo "</pre>";
									?>
									<tr class="table-item" data-id="<?=$arItem['ID']?>" data-product-id="<?=$arItem['PRODUCT_ID']?>" data-key="<?=$arItem['KEY']?>">
										<td itemscope itemtype="http://schema.org/ImageObject" class="photo">
											<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
												<img itemprop="contentUrl" class="lazy" data-original="<?=$arItem['MAIN_PHOTO']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['NAME']?>">
											</a>
										</td>
										<td class="name">
											<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link"><span class="text"><?=$arItem['NAME']?></span></a>
											<div>
											<? if($arParams['SHOW_ARTICUL'] !== 'N' && !empty($arItem['ARTICUL'])): ?>

												<span class="art"><?=GetMessage('BITRONIC2_BASKET_ARTICUL')?>: <strong><?=htmlspecialcharsBx($arItem['ARTICUL'])?></strong></span>
											<? endif ?><?
												/* TODO
												<form action="#" method="post" class="sku">
													<select name="sku" class="select-styled">
														<option value="16gb">16 GB</option>
														<option value="8gb">8 GB</option>
														<option value="32gb">32 GB</option>
														<option value="64gb">64 GB</option>
													</select>
												</form>
												*/?>
												<?foreach($arItem['PRODUCT_PROPERTIES'] as $arProp):?>
													<span class="art"><?=$arProp['NAME']?>: <strong><?=$arProp['VALUE']?></strong></span>
												<?endforeach?>
											</div>
										</td>
										<td class="availability">
											<form action="#" method="post" class="quantity-counter"
												data-tooltip
												data-placement="bottom"
												title="<?=$arItem['MEASURE_NAME']?>">
												<?
												$ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 1;
												?><!-- parent must have class .quantity-counter! -->
												<button type="button" class="btn-silver quantity-change decrease<?=($arItem['QUANTITY']<=$ratio?' disabled':'')?>"><span class="minus"></span></button>
												<input type="text" class="quantity-input textinput" name="quantity" value="<?=$arItem['QUANTITY']?>" data-ratio="<?=$arItem['MEASURE_RATIO']?>">
												<button type="button" class="btn-silver quantity-change increase"><span class="plus"></span></button>
											</form>
											<?/* TODO 
											include '_/elements/availability-info.html'; 
											*/?>
										</td>
										<td class="price">
											<span class="price-new"><?=$arItem['PRICE_FMT']?></span>
											<?if($arItem['DISCOUNT_PRICE'] > 0):?>
											<div>
												<span class="price-old"><?=$arItem['FULL_PRICE']?></span>
											</div>
											<?endif?>
										</td>
										<td class="actions">
											<?/* TODO 
											include '_/buttons/btn-action_to-wait.html'; 
											*/?>
											<button class="btn-delete pseudolink with-icon" data-tooltip title="<?=GetMessage('BITRONIC2_BASKET_DELETE')?>" data-placement="bottom">
												<i class="flaticon-trash29"></i>
												<span class="btn-text"><?=GetMessage('BITRONIC2_BASKET_DELETE')?></span>
											</button>
										</td>
									</tr>
								<?endforeach?>
							</tbody>
						<?endif?>
					</table>
					<script type="text/javascript">
						RZB2.ajax.BasketSmall.ElementsList = <?=CUtil::PhpToJSObject($arProductId, false, true, true)?>;
					</script>
					<?$frame->end(); unset($arProductId);?>
				</div>
				<?
				/*
				<div class="waitlist-content">
					<? include '_/elements/items-table-waitlist-demo.html'; ?>
				</div>
				*/
				?>
				<div class="scroller__track scroller__track_v">
					<div class="scroller__bar scroller__bar_v"></div>
				</div>
			</div>
		</div>
		<div class="popup-footer">
			<span id="<?=$id_footer?>" class="total">
				<?$frame = $this->createFrame($id_footer, false)->begin(CRZBitronic2Composite::insertCompositLoader());?>
				<span class="text"><?=GetMessage('BITRONIC2_BASKET_ITOGO')?></span>
				<span class="price" data-total-price="<?=$arResult["TOTAL_SUM"]?>"><?=$arResult["TOTAL_SUM_FORMATTED"]?></span>
				<?$frame->end();?>
			</span>
			<button class="btn-delete pseudolink with-icon">
				<i class="flaticon-trash29"></i>
				<span class="btn-text"><?=GetMessage('BITRONIC2_BASKET_DELETE_ALL')?><span class="hidden-xs"><?=GetMessage('BITRONIC2_BASKET_FROM_BASKET')?></span></span>
			</button>
			<div class="small-basket-buy-wrap">
				<a href="<?=$arParams['BASKET_URL']?>" class="btn-main"><span class="text"><?=GetMessage('BITRONIC2_BASKET_MAKE_ORDER')?></span></a>
			</div>
		</div>
	</div><!-- /.top-line-popup.popup_basket#popup_basket -->
</div>