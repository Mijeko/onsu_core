<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Sale\DiscountCouponsManager;

global $rz_b2_options;

if (!empty($arResult["ERROR_MESSAGE"]))
	ShowError($arResult["ERROR_MESSAGE"]);

if ($normalCount > 0):
	?>
	<div id="basket-big" class="basket-big tab-target">
		<table class="items-table" id="basket_items">
			<thead>
			<tr>
				<th colspan="<?= ($arResult['COLUMNS']['NAME'] ? 2 : 1) ?>"><?=GetMessage('BITRONIC2_SALE_NAME')?></th>
				<?     if ($arResult['COLUMNS']['QUANTITY']):
					?><th class="availability"><?=GetMessage('BITRONIC2_SALE_QUANTITY')?></th>
				<?
				endif; if ($arResult['COLUMNS']['PRICE']):
					?><th class="price"><?=GetMessage('BITRONIC2_SALE_PRICE')?></th>
				<?
				endif; if ($arResult['COLUMNS']['DISCOUNT']):
					?><th class="discount"><?=GetMessage('BITRONIC2_SALE_DISCOUNT')?></th>
				<?
				endif; if ($arResult['COLUMNS']['SUM']):
					?><th class="sum"><?=GetMessage('BITRONIC2_SALE_SUM')?></th>
				<?
				endif; if ($arResult['COLUMNS']['ACTIONS']):
					?><th></th><?
				endif ?>

			</tr>
			</thead>
			<tfoot>
			<tr>
				<td colspan="<?= 1 + count($arResult['COLUMNS']) ?>">
					<div class="totals">
						<table class="table_totals">
							<?if ($arResult["allWeight"] > 0):?>
								<tr>
									<td class="text"><?=GetMessage('BITRONIC2_SALE_WEIGHT')?></td>
									<td class="value" id="allWeight_FORMATED"><?=$arResult["allWeight_FORMATED"]?></td>
								</tr>
							<?endif;?>
							<?if ($arParams["PRICE_VAT_SHOW_VALUE"] == "Y"):?>
								<tr>
									<td class="text"><?echo GetMessage("BITRONIC2_SALE_VAT_EXCLUDED")?></td>
									<td class="value"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['CURRENCY'], $arResult["allSum"] - $arResult["allVATSum"], $arResult['allSum_wVAT_FORMATED'], array('ID'=>'allSum_wVAT_FORMATED'))?></td>
								</tr>
								<tr>
									<td class="text"><?echo GetMessage("BITRONIC2_SALE_VAT_INCLUDED")?></td>
									<td class="value"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['CURRENCY'], $arResult["allVATSum"], $arResult['allVATSum_FORMATED'], array('ID'=>'allVATSum_FORMATED'))?></td>
								</tr>
							<?endif;?>

							<tr id="DISCOUNT_PRICE_ALL_cont" class="<?=($arResult['DISCOUNT_PRICE_ALL'] > 0)? '':'hide'?>">
								<td class="text"><?=GetMessage('BITRONIC2_SALE_DISCOUNT')?></td>
								<td class="value"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['CURRENCY'], $arResult["DISCOUNT_PRICE_ALL"], $arResult["DISCOUNT_PRICE_ALL_FORMATED"], array('ID'=>'DISCOUNT_PRICE_ALL'))?></td>
							</tr>
							<tr>
								<td class="text"><?=GetMessage("BITRONIC2_SALE_TOTAL", array('#DELIVERY_URL#' => $arParams['DELIVERY_URL']))?></td>
								<td class="value"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['CURRENCY'], $arResult["allSum"], $arResult['allSum_FORMATED'], array('ID'=>'allSum_FORMATED'))?></td>
							</tr>
							<? if ($rz_b2_options['pro_vbc_bonus']): ?>
							<tr>
								<?$APPLICATION->IncludeComponent("vbcherepanov:vbcherepanov.bonusfororder","cartall",
									Array(
										"TYPE" => "CART",
										"RESULT" => $arResult,
										"CACHE_TYPE" => "N",
										"CACHE_TIME" => "3"
									),
									false
								);?>
							</tr>
							<? endif ?>
						</table>
					</div>

					<?// COUPONS
					if ($arParams["HIDE_COUPON"] != "Y")
					{
						if (empty($arResult['COUPON_LIST']) && $_REQUEST['coupon'] !== 'show')
						{
							?>
							<a href="?coupon=show" class="coupon-link pseudolink"><span class="link-text"><?=GetMessage('BITRONIC2_DO_YOU_HAVE_COUPON')?></span></a>
							<?
						}
						?>
						<div class="coupon-outer-wrap">
							<label class="coupon-wrap" id="coupons_block">
								<span class="text"><?=GetMessage('BITRONIC2_SALE_COUPON_CODE')?></span>
								<input type="text" id="coupon" name="COUPON" value="" onchange="enterCoupon();" class="textinput">
							</label>
							<button type="button" class="apply-coupon">
								<i class="flaticon-right10"></i>
							</button>
						</div>
						<?if (!empty($arResult['COUPON_LIST']))
					{
						foreach ($arResult['COUPON_LIST'] as $oneCoupon)
						{
							$couponClass = 'disabled';
							switch ($oneCoupon['STATUS'])
							{
								case DiscountCouponsManager::STATUS_NOT_FOUND:
								case DiscountCouponsManager::STATUS_FREEZE:
									$couponClass = 'not-valid';
									break;
								case DiscountCouponsManager::STATUS_APPLYED:
									$couponClass = 'valid';
									break;
							}
							?><div class="coupon-outer-wrap" data-tooltip data-placement="right" title="<?echo (is_array($oneCoupon['CHECK_CODE_TEXT']) ? implode(",\n", $oneCoupon['CHECK_CODE_TEXT']) : $oneCoupon['CHECK_CODE_TEXT']);?>">
							<label class="coupon-wrap">
								<span class="text"><?=GetMessage('BITRONIC2_SALE_COUPON_CODE')?></span>
								<input class="textinput <? echo $couponClass; ?>" readonly type="text" name="OLD_COUPON[]" value="<?=htmlspecialcharsbx($oneCoupon['COUPON']);?>">
							</label>
							<button type="button" class="apply-coupon <?=$couponClass?>" data-coupon="<? echo htmlspecialcharsbx($oneCoupon['COUPON']); ?>">
								<i class="icon-<?=$couponClass?> flaticon-<?=($couponClass=='valid')?'check33':'x5'?>"></i>
							</button>
							</div>
							<?
						}
						unset($couponClass, $oneCoupon);
					}
						?>
						<div class="coupon-help">
							<?$APPLICATION->IncludeComponent('bitrix:main.include', '', array("PATH" => SITE_DIR."include_areas/basket/coupons.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"))?>
						</div>
						<?
					}
					?>
				</td>
			</tr>
			</tfoot>
			<tbody>
			<?
			foreach ($arResult["GRID"]["ROWS"] as $k => $arItem):
				if ($arItem["DELAY"] == "N" && !isset($arItem["NOT_AVAILABLE"]) && $arItem["NOT_AVAILABLE"] != true):// && $arItem["CAN_BUY"] == "Y"):
					?>
					<tr class="table-item<?if($arItem['CAN_BUY']!='Y'):?> out-of-stock<?elseif($arItem['FOR_ORDER']):?> available-for-order<?endif?>" id="<?=$arItem["ID"]?>">
						<td itemscope itemtype="http://schema.org/ImageObject" class="photo">
							<?if(!empty($arItem['DETAIL_PAGE_URL'])):?>
							<a title="<?=$arItem['NAME']?>" href="<?=$arItem['DETAIL_PAGE_URL']?>">
							<?endif?>
								<img itemprop="contentUrl" title="<?=$arItem['NAME']?>" class="lazy" data-original="<?=$arItem['PICTURE_PRINT']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['NAME']?>">
							<?if(!empty($arItem['DETAIL_PAGE_URL'])):?>
							</a>
							<?endif?>
						</td>
					<? if ($arResult['COLUMNS']['NAME']): ?>
						<td class="name">
						<? if ($bShowName): ?>
							<? if (!empty($arItem['DETAIL_PAGE_URL'])): ?>
							<a title="<?=$arItem['NAME']?>" href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link">
							<? endif ?>
								<span class="text"><?=$arItem['NAME']?></span>
							<? if (!empty($arItem['DETAIL_PAGE_URL'])): ?>
							</a>
							<? endif ?>
						<? endif ?>
						<? if ($bShowProps): ?>
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
						<? endif ?>

							<div>
								<? if ($arParams['SHOW_ARTICLE'] && !empty($arItem['PROPERTY_'.$catalogParams['ARTICUL_PROP'].'_VALUE'])): ?>
								<span class="art">
									<?=GetMessage('BITRONIC2_SALE_ARTICUL')?>:
									<strong><?=htmlspecialcharsbx($arItem['PROPERTY_'.$catalogParams['ARTICUL_PROP'].'_VALUE'])?></strong>
								</span>
								<? endif ?>
								<?
								if (is_array($arItem["SKU_DATA"]) && !empty($arItem["SKU_DATA"])):
									?>
									<div class="sku">
										<?
										foreach ($arItem["SKU_DATA"] as $propId => $arProp):
											?>
											<span class="sku-wrap">
										<span class="text"><?=$arProp["NAME"]?>: </span>
										<select id="prop_<?=$arProp["CODE"]?>_<?=$arItem["ID"]?>" class="select-styled" data-property="<?=$arProp["CODE"]?>">
											<?
											foreach ($arProp["VALUES"] as $valueId => $arSkuValue):

												$selected = "";
												foreach ($arItem["PROPS"] as $arItemProp):
													if ($arItemProp["CODE"] == $arItem["SKU_DATA"][$propId]["CODE"])
													{
														if ($arItemProp["VALUE"] == $arSkuValue["NAME"] || $arItemProp["VALUE"] == $arSkuValue["XML_ID"])
															$selected = ' selected="selected"';
													}
												endforeach;
												?>
												<option value="<?=(!empty($arSkuValue['XML_ID']) ? $arSkuValue['XML_ID'] : $arSkuValue['NAME']); ?>"<?=$selected?>><?=$arSkuValue["NAME"]?></option>
												<?
											endforeach;
											?>
										</select>
									</span>
											<?
										endforeach;
										?>
									</div>
									<?
								endif;
								?>
							</div>
						</td>
					<? endif ?>
					<? if ($arResult['COLUMNS']['QUANTITY']): ?>
						<td class="availability ">
						<span class="quantity-counter basket_quantity_control"
							  data-tooltip
							  data-placement="right"
							  title="<?=$arItem['MEASURE_TEXT']?>">
							<?
							if (!isset($arItem["MEASURE_RATIO"]))
							{
								$arItem["MEASURE_RATIO"] = 1;
							}
							$ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 1;
							$max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"".$arItem["AVAILABLE_QUANTITY"]."\"" : "";
							$useFloatQuantity = ($arParams["QUANTITY_FLOAT"] == "Y") ? true : false;
							$useFloatQuantityJS = ($useFloatQuantity ? "true" : "false");
							?>

							<button
								type="button"
								id="QUANTITY_DOWN_<?=$arItem['ID']?>"
								class="btn-silver quantity-change decrease<?=($arItem['QUANTITY']<=$ratio?' disabled':'')?>"
								data-ratio="<?=$ratio?>"
								>
								<span class="minus"></span>
							</button>
							<input
								type="text"
								size="3"
								maxlength="18"
								id="QUANTITY_INPUT_<?=$arItem["ID"]?>"
								name="QUANTITY_INPUT_<?=$arItem["ID"]?>"
								class="quantity-input textinput"
								value="<?=$arItem["QUANTITY"]?>"
								onchange="updateQuantity('QUANTITY_INPUT_<?=$arItem["ID"]?>', '<?=$arItem["ID"]?>', <?=$ratio?>, <?=$useFloatQuantityJS?>)"
								>
							<button
								type="button"
								class="btn-silver quantity-change increase"
								data-ratio="<?=$ratio?>"
								>
								<span class="plus"></span>
							</button>
						</span>
							<?
							$availableID = false;
							$availableClass = '';
							$availableFrame = false;
							$availableForOrderText = &$arItem['PROPERTY_RZ_FOR_ORDER_TEXT_VALUE'];
							$availableItemID = &$arItem['PRODUCT_ID'];
							$availableMeasure = &$arItem['MEASURE_TEXT'];
							$availableQuantity = &$arItem['AVAILABLE_QUANTITY'];
							$availableStoresPostfix = 'basket_items';
							$availableSubscribe = $arItem['SUBSCRIBE'];
							$bShowEveryStatus = true;
                            $bExpandedStore = false;
							include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/availability_info.php';
							?>

							<input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" />
							<input type="hidden" name="DELAY_<?=$arItem["ID"]?>" id="DELAY_<?=$arItem["ID"]?>" value="N"/>
							<input type="hidden" name="DELETE_<?=$arItem["ID"]?>" id="DELETE_<?=$arItem["ID"]?>" value="N"/>
						</td>
					<? endif ?>
					<? if ($arResult['COLUMNS']['PRICE']): ?>
						<td class="price">
							<?$bDiscountShow = ($arItem['DISCOUNT_PRICE'] > 0)?>
							<span class="price-new"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['CURRENCY'], $arItem["PRICE"], $arResult["PRICE_FORMATED"], array('ID'=> 'current_price_'.$arItem["ID"]))?></span>
							<div>
								<span class="price-old <?=(!$bDiscountShow) ? 'hide' : ''?>"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['CURRENCY'], $arItem["FULL_PRICE"], $arResult["FULL_PRICE_FORMATED"], array('ID'=> 'old_price_'.$arItem["ID"]))?></span>
							</div>
						</td>
					<? endif ?>
					<? if ($arResult['COLUMNS']['DISCOUNT']): ?>
						<td class="discount" id="discount_value_<?=$arItem['ID']?>"><?=$arItem['DISCOUNT_PRICE_PERCENT_FORMATED']?></td>
					<? endif ?>
					<? if ($arResult['COLUMNS']['SUM']): ?>
						<td class="sum"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['CURRENCY'], $arItem["SUM_NOT_FORMATED"], $arResult["SUM"], array('ID'=> 'sum_'.$arItem["ID"]))?></td>
					<? endif ?>
					<? if ($arResult['COLUMNS']['ACTIONS']): ?>
						<td class="actions">
						<? if ($bShowDelay): ?>
							<a href="<?=htmlspecialcharsbx(str_replace("#ID#", $arItem["ID"], $arUrls["delay"]))?>" class="btn-to-waitlist pseudolink with-icon" data-action="delay" data-id="<?=$arItem['ID']?>" data-tooltip title="<?=GetMessage('BITRONIC2_SALE_DELAY')?>" data-placement="bottom">
								<i class="flaticon-back15"></i>
								<span class="btn-text"><?=GetMessage('BITRONIC2_SALE_DELAY')?></span>
							</a>
						<? endif ?>
						<? if ($bShowDelete): ?>
							<a href="<?=htmlspecialcharsbx(str_replace("#ID#", $arItem["ID"], $arUrls["delete"]))?>" class="btn-delete pseudolink with-icon" data-action="delete" data-id="<?=$arItem['ID']?>" data-tooltip title="<?=GetMessage('BITRONIC2_SALE_DELETE')?>" data-placement="bottom">
								<i class="flaticon-trash29"></i>
								<span class="btn-text"><?=GetMessage('BITRONIC2_SALE_DELETE')?></span>
							</a>
						<? endif ?>
						</td>
					<? endif ?>
					</tr>
				<?endif?>
			<?endforeach?>
			</tbody>
		</table>
	</div>
	<?
else:
	?>
	<div id="basket-big" class="basket-big tab-target">
		<table>
			<tbody>
			<tr>
				<td colspan="<?=$numCells?>" style="text-align:center">
					<div class=""><?=GetMessage("BITRONIC2_SALE_NO_ITEMS");?></div>
				</td>
			</tr>
			</tbody>
		</table>
	</div>
	<?
endif;
