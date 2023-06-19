<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div id="nalist-big" class="basket-big-nalist tab-target">
	<table class="items-table">
		<thead>
			<tr>
				<th colspan="2"><?=GetMessage('BITRONIC2_SALE_NAME')?></th>
				<th class="availability"><?=GetMessage('BITRONIC2_SALE_QUANTITY')?></th>
				<th class="price"><?=GetMessage('BITRONIC2_SALE_PRICE')?></th>
				<th></th>
			</tr>
		</thead>

		<tbody>
			<?
			foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):
				if (isset($arItem["NOT_AVAILABLE"]) && $arItem["NOT_AVAILABLE"] == true):
			?>
				<tr class="table-item out-of-stock" id="<?=$arItem["ID"]?>">
					<td itemscope itemtype="http://schema.org/ImageObject" class="photo">
						<?if(!empty($arItem['DETAIL_PAGE_URL'])):?>
						<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
						<?endif?>
							<img itemprop="contentUrl" class="lazy" data-original="<?=$arItem['PICTURE_PRINT']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['NAME']?>">
						<?if(!empty($arItem['DETAIL_PAGE_URL'])):?>
						</a>
						<?endif?>
					</td>
					<td class="name">
						<?if(!empty($arItem['DETAIL_PAGE_URL'])):?>
						<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link">
						<?endif?>
							<span class="text"><?=$arItem['NAME']?></span>
						<?if(!empty($arItem['DETAIL_PAGE_URL'])):?>
						</a>
						<?endif?>
						<? if ($arParams['SHOW_ARTICLE'] && !empty($arItem['PROPERTY_'.$catalogParams['ARTICUL_PROP'].'_VALUE'])): ?>
						<div>
							<span class="art">
								<?=GetMessage('BITRONIC2_SALE_ARTICUL')?>:
								<strong><?=htmlspecialcharsbx($arItem['PROPERTY_'.$catalogParams['ARTICUL_PROP'].'_VALUE'])?></strong>
							</span>
						</div>
						<? endif ?>
						<?
						foreach ($arItem["PROPS"] as $val):
							if (is_array($arItem["SKU_DATA"]))
							{
								$bSkip = false;
								foreach ($arItem["SKU_DATA"] as $propId => $arProp)
								{
									if ($arProp["CODE"] == $val["CODE"])
									{
										$bSkip = true;
										break;
									}
								}
								if ($bSkip)
									continue;
							}
							?>
							<div><?=$val["NAME"]?>: <strong><?=$val["VALUE"]?></strong></div>
						<?endforeach;?>
						
						<?if (is_array($arItem["SKU_DATA"]) && !empty($arItem["SKU_DATA"])):?>
							<? // TODO SKU ?>
						<?endif?>
					</td>
					<td class="availability ">
						<div class="quantity-counter"><?=$arItem["QUANTITY"]?> <?=$arItem['MEASURE_TEXT']?></div>
						<div class="availability-info">
							<div class="when-out-of-stock">
								<div class="info-tag" <?/* TODO SUBSCRIBE
									title="<?=GetMessage('BITRONIC2_SALE_SUBSCRIBE')?>"
									data-tooltip
									data-placement="right"
									data-toggle="modal"
									data-target="#modal_inform-when-in-stock"*/?>
									>
									<span class="text"><?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_FALSE')?></span>
								</div><!-- .info-tag -->
							</div>
						</div><!-- .availability-info -->
						
						<input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" />
						<input type="hidden" name="DELAY_<?=$arItem["ID"]?>" id="DELAY_<?=$arItem["ID"]?>" value="N"/>
						<input type="hidden" name="DELETE_<?=$arItem["ID"]?>" id="DELETE_<?=$arItem["ID"]?>" value="N"/>
					</td>
					<td class="price">
						<?$bDiscountShow = ($arItem['DISCOUNT_PRICE'] > 0)?>
						<span class="price-new"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['CURRENCY'], $arItem["PRICE"], $arResult["PRICE_FORMATED"], array('ID'=> 'current_price_'.$arItem["ID"]))?></span>
						<div>
							<span class="price-old <?=(!$bDiscountShow) ? 'hide' : ''?>"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['CURRENCY'], $arItem["FULL_PRICE"], $arResult["FULL_PRICE_FORMATED"], array('ID'=> 'old_price_'.$arItem["ID"]))?></span>
						</div>
					</td>
					<td class="actions">
						<a href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delete"])?>" class="btn-delete pseudolink with-icon" data-action="delete" data-id="<?=$arItem['ID']?>" data-tooltip title="<?=GetMessage('BITRONIC2_SALE_DELETE')?>" data-placement="bottom">
							<i class="flaticon-trash29"></i>
							<span class="btn-text"><?=GetMessage('BITRONIC2_SALE_DELETE')?></span>
						</a>
					</td>
				</tr>
				<?
				endif;
			endforeach;
			?>
		</tbody>

	</table>
</div>
<?
