<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$bDefaultColumns = $arResult["GRID"]["DEFAULT_COLUMNS"];
$colspan = ($bDefaultColumns) ? count($arResult["GRID"]["HEADERS"]) : count($arResult["GRID"]["HEADERS"]) - 1;
$bPropsColumn = false;
$bUseDiscount = false;
$bPriceType = false;
$bShowNameWithPicture = ($bDefaultColumns) ? true : false; // flat to show name and picture column in one column
?>
<div class="title-h3"><?=GetMessage("BITRONIC2_SALE_PRODUCTS_SUMMARY");?></div>
<table class="items-table">
	<thead>
		<tr>
			<th colspan="2"><?=GetMessage("BITRONIC2_SOA_GOOD");?></th>
			<th class="availability"><?=GetMessage("BITRONIC2_SOA_QUANTITY");?></th>
			<th class="price"><?=GetMessage("BITRONIC2_SOA_PRICE_BY_1");?></th>
			<th class="sum"><?=GetMessage("BITRONIC2_SOA_SUMMA");?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="5">
				<div class="totals">
					<table class="table_totals">
						<?
						$orderTotalSum = isset($arResult['ORDER_TOTAL_PRICE'])
						               ? $arResult['ORDER_TOTAL_PRICE']
						               : $arResult["ORDER_PRICE"] + $arResult["DELIVERY_PRICE"] + $arResult["TAX_PRICE"] - $arResult["DISCOUNT_PRICE"];
						
						if (strlen($arResult["PAYED_FROM_ACCOUNT_FORMATED"]) > 0)
						{
							$arResult["PAYED_FROM_ACCOUNT"] = $orderTotalSum - $arResult['ORDER_TOTAL_LEFT_TO_PAY'];
						}
						if (floatval($arResult['ORDER_WEIGHT']) > 0):
						?>
						<tr>
							<td class="text" colspan="<?=$colspan?>" class="itog"><?=GetMessage("BITRONIC2_SOA_TEMPL_SUM_WEIGHT_SUM")?></td>
							<td class="value" class="price"><?=$arResult["ORDER_WEIGHT_FORMATED"]?></td>
						</tr>
						<?
						endif;
						?>
						<tr>
							<td class="text" colspan="<?=$colspan?>" class="itog"><?=GetMessage("BITRONIC2_SOA_TEMPL_SUM_SUMMARY")?></td>
							<td class="value" class="price"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult["BASE_LANG_CURRENCY"], $arResult["ORDER_PRICE"], $arResult['ORDER_PRICE_FORMATED'])?></td>
						</tr>
						<?
						if (round($arResult['PRICE_WITHOUT_DISCOUNT_VALUE'], 4) != round($arResult["ORDER_PRICE"], 4)):
						?>
						<tr>
							<td colspan="<?=$colspan?>"></td>
							<td class="value">
								<span class="price-old" style="font-weight:normal; font-size:0.8125em;"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult["BASE_LANG_CURRENCY"], $arResult["PRICE_WITHOUT_DISCOUNT_VALUE"], $arResult["PRICE_WITHOUT_DISCOUNT"])?></span>
							</td>
						</tr>
						<?
						endif
						?>
						<?
						if (doubleval($arResult["DISCOUNT_PRICE"]) > 0)
						{
							?>
							<tr>
								<td class="text" colspan="<?=$colspan?>" class="itog"><?=GetMessage("BITRONIC2_SOA_TEMPL_SUM_DISCOUNT")?><?if (strLen($arResult["DISCOUNT_PERCENT_FORMATED"])>0):?> (<?echo $arResult["DISCOUNT_PERCENT_FORMATED"];?>)<?endif;?>:</td>
								<td class="value" class="price"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult["BASE_LANG_CURRENCY"], $arResult["DISCOUNT_PRICE"], $arResult['DISCOUNT_PRICE_FORMATED'])?></td>
							</tr>
							<?
						}
						if(!empty($arResult["TAX_LIST"]))
						{
							foreach($arResult["TAX_LIST"] as $val)
							{
								?>
								<tr>
									<td class="text" colspan="<?=$colspan?>" class="itog"><?=$val["NAME"]?> <?=$val["VALUE_FORMATED"]?>:</td>
									<td class="value" class="price"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult["BASE_LANG_CURRENCY"], $val["VALUE_MONEY"], $val['VALUE_MONEY_FORMATED'])?></td>
								</tr>
								<?
							}
						}
						if (is_numeric($arResult["DELIVERY_PRICE"]))
						{
							?>
							<tr>
								<td class="text" colspan="<?=$colspan?>" class="itog"><?=GetMessage("BITRONIC2_SOA_TEMPL_SUM_DELIVERY")?></td>
								<td class="value" class="price"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult["BASE_LANG_CURRENCY"], $arResult["DELIVERY_PRICE"], $arResult['DELIVERY_PRICE_FORMATED'])?></td>
							</tr>
							<?
						}
						if (($arResult['USE_BONUS'] && $arResult['MINUS']) || (!$arResult['USE_BONUS'] && strlen($arResult["PAYED_FROM_ACCOUNT_FORMATED"]) > 0)) {
							?>
							<tr>
								<td class="text" colspan="<?=$colspan?>" class="itog"><?=GetMessage("BITRONIC2_SOA_TEMPL_SUM_IT")?></td>
								<td class="value" class="price">
									<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult["BASE_LANG_CURRENCY"], $arResult["ORDER_TOTAL_PRICE_FORMATED"], $arResult['ORDER_TOTAL_PRICE_FORMATED'])?>
								</td>
							</tr>
							<?
							if ($arResult['USE_BONUS']) {
								if (strlen($arResult["PAYED_FROM_ACCOUNT_FORMATED"]) > 0) {
								?>
								<tr>
									<td class="text" colspan="<?=$colspan?>" class="itog"><?=GetMessage("VBCHBB_SALE_ORDER_AJAX_P9")?></td>
									<td class="value" class="price">
										<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult["BASE_LANG_CURRENCY"], $arResult["PAYED_FROM_ACCOUNT_FORMATED"], $arResult['PAYED_FROM_ACCOUNT_FORMATED'])?>
									</td>
								</tr>
								<?
								}
								if (strlen($arResult["PAYED_FROM_BONUS_FORMATED"]) > 0) {
								?>
								<tr>
									<td class="text" colspan="<?=$colspan?>" class="itog"><?=GetMessage("VBCHBB_SALE_ORDER_AJAX_P3")?></td>
									<td class="value" class="price">
										<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult["BASE_LANG_CURRENCY"], $arResult["PAYED_FROM_BONUS_FORMATED"], $arResult['PAYED_FROM_BONUS_FORMATED'])?>
									</td>
								</tr>
								<?
								}
							} else {?>
							<tr>
								<td class="text" colspan="<?=$colspan?>" class="itog"><?=GetMessage("BITRONIC2_SOA_TEMPL_SUM_PAYED")?></td>
								<td class="value" class="price">
									<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult["BASE_LANG_CURRENCY"], $arResult["PAYED_FROM_ACCOUNT"], $arResult['PAYED_FROM_ACCOUNT_FORMATED'])?>
								</td>
							</tr>
							<? } ?>
							<tr>
								<td class="text fwb" colspan="<?=$colspan?>" class="itog"><?=GetMessage("BITRONIC2_SOA_TEMPL_SUM_LEFT_TO_PAY")?></td>
								<td class="value fwb" class="price">
									<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult["BASE_LANG_CURRENCY"], $arResult["ORDER_TOTAL_LEFT_TO_PAY_FORMATED"], $arResult['ORDER_TOTAL_LEFT_TO_PAY_FORMATED'])?>
								</td>
							</tr>
							<?
						} else {
							?>
							<tr>
								<td class="text fwb" colspan="<?=$colspan?>" class="itog"><?=GetMessage("BITRONIC2_SOA_TEMPL_SUM_IT")?></td>
								<td class="value fwb" class="price">
									<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult["BASE_LANG_CURRENCY"], $arResult["ORDER_TOTAL_PRICE_FORMATED"], $arResult['ORDER_TOTAL_PRICE_FORMATED'])?>
								</td>
							</tr>
							<?
						}
						if ($arResult['USE_BONUS']): ?>
						<tr>
							<?$APPLICATION->IncludeComponent("vbcherepanov:vbcherepanov.bonusfororder","order",
							array(
								"TYPE" => "ORDER",
								"RESULT" => $arResult,
								"CACHE_TYPE" => "A",
								"CACHE_TIME" => "360",
								"colspan" => $colspan,
							),
							false
						);?>
						</tr>
						<? endif ?>
					</table>
				</div>
			</td>
		</tr>
	</tfoot>
	<tbody>
		<?foreach ($arResult["GRID"]["ROWS"] as $k => $arData):
			$arItem = $arData["data"];?>
				<tr class="table-item">
					<td itemscope itemtype="http://schema.org/ImageObject"  class="photo">
						<a href="<?=$arItem["DETAIL_PAGE_URL"] ?>">
							<img itemprop="contentUrl" src="<?=$arItem['PICTURE_PRINT']['SRC']?>" alt="<?=$arItem["NAME"]?>">
						</a>
					</td>
					<td class="name">
						<a href="<?=$arItem["DETAIL_PAGE_URL"] ?>" class="link"><span class="text"><?=$arItem["NAME"]?></span></a>
						<div>
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
						</div>
					</td>
					<td class="availability">
						x <?=$arItem["QUANTITY"] ?> <?=$arItem["MEASURE_TEXT"] ?>
					</td>
					<td class="price">
						<span class="price-new"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['CURRENCY'], $arItem['PRICE'], $arItem['PRICE_FORMATED'])?></span>
						<? if (doubleval($arItem["DISCOUNT_PRICE"]) > 0):
							$priceOld = $arItem["PRICE"] + $arItem["DISCOUNT_PRICE"];
						?>
						<div>
							<span class="price-old"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['CURRENCY'], $priceOld, $priceOld)?></span>
						</div>
						<? endif ?>
					</td>
					<td class="sum"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['CURRENCY'], $arItem['PRICE']*$arItem["QUANTITY"], $arItem['SUM'])?></td>
				</tr>
		<?endforeach;?>
	</tbody>
</table>
<div class="title-h3"><?=GetMessage('BITRONIC2_SOA_ADITIONAL_INFO')?></div>
<textarea class="textinput" name="ORDER_DESCRIPTION" id="ORDER_DESCRIPTION"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea>
