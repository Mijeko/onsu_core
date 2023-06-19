<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';?>

<a href="<?=$arResult['URL_TO_LIST']?>" class="btn-return">
	<i class="flaticon-shopping109"></i>
	<span class="text"><?=GetMessage('BITRONIC2_SPOD_CUR_ORDERS')?></span>
</a>
<?if(strlen($arResult["ERROR_MESSAGE"]))
{
	echo CRZBitronic2CatalogUtils::ShowMessage(Array("MESSAGE" => $arResult["ERROR_MESSAGE"], "TYPE" => "ERROR"));
	return;
}?>

<div class="title-h2"><?=GetMessage('BITRONIC2_SPOD_ORDER')?> <?=$arResult['ACCOUNT_NUMBER']?></div>
<?if($arResult["CANCELED"] == "Y"):?>
	<?=GetMessage('BITRONIC2_SPOD_CANCELED')?>
	<?if(strlen($arResult["DATE_CANCELED_FORMATED"])):?>
		(<?=GetMessage('BITRONIC2_SPOD_FROM')?> <?=$arResult["DATE_CANCELED_FORMATED"]?>)
	<?endif?>
	<?if(strlen($arResult["REASON_CANCELED"])):?>
		<p><?=GetMessage('BITRONIC2_SPOD_ORDER_CANCELED_REASON')?>: <?=$arResult["REASON_CANCELED"]?></p>
	<?endif?>
<?elseif($arResult["CAN_CANCEL"] == "Y"):?>
	<a href="<?=$arResult["URL_TO_CANCEL"]?>" class="link"><span class="text"><?=GetMessage('BITRONIC2_SPOD_ORDER_CANCEL')?></span></a>
<?endif?>

<div class="order-info-section">
	<header>
		<span class="text"><?=GetMessage('BITRONIC2_SPOD_ORDER')?> <?=GetMessage('BITRONIC2_SPOD_NUM_SIGN')?><?=$arResult['ACCOUNT_NUMBER']?> <?=GetMessage('BITRONIC2_SPOD_FROM')?> <?=$arResult['DATE_INSERT']?></span>
	</header>
	<div class="main-content">
		<table>
			<?if($arResult["CANCELED"] !== "Y"):?>
			<tr>
				<td class="desc"><?=GetMessage('BITRONIC2_SPOD_ORDER_STATUS')?></td>
				<td class="value"><?=$arResult['STATUS']['NAME']?> (<?=GetMessage('BITRONIC2_SPOD_FROM')?> <?=$arResult['DATE_STATUS']?>)</td>
			</tr>
			<?endif?>
			<tr>
				<td class="desc"><?=GetMessage('BITRONIC2_SPOD_ORDER_PRICE')?></td>
				<td class="value"><?=$arResult['PRICE_FORMATED']?>
					<?if(floatval($arResult["SUM_PAID"])):?>
						(<?=GetMessage('BITRONIC2_SPOD_ALREADY_PAID')?>:&nbsp;<?=$arResult["SUM_PAID_FORMATED"]?>)
					<?endif?>
				</td>
			</tr>
		</table>
	</div><!-- /.main-content -->
</div><!-- /.order-info-section -->

<div class="order-info-section expandable allow-multiple-expanded">
	<header>
		<span class="text-wrap"><?=GetMessage('BITRONIC2_SPOD_ACCOUNT')?></span>
	</header>
	<div class="main-content expand-content">
		<table>
			<tr>
				<td class="desc"><?=GetMessage('BITRONIC2_SPOD_LOGIN')?></td>
				<td class="value"><?=$arResult['USER']['LOGIN']?></td>
			</tr>
			<tr>
				<td class="desc"><?=GetMessage('BITRONIC2_SPOD_USER_NAME')?></td>
				<td class="value"><?=$arResult['USER']['NAME']?></td>
			</tr>
			<tr>
				<td class="desc"><?=GetMessage('BITRONIC2_SPOD_USER_LAST_NAME')?></td>
				<td class="value"><?=$arResult['USER']['LAST_NAME']?></td>
			</tr>
			<tr>
				<td class="desc"><?=GetMessage('BITRONIC2_SPOD_EMAIL')?></td>
				<td class="value"><?=$arResult['USER']['EMAIL']?></td>
			</tr>
			<tr>
				<td class="desc"><?=GetMessage('BITRONIC2_SPOD_USER_PHONE')?></td>
				<td class="value"><?=$arResult['USER']['PERSONAL_PHONE']?></td>
			</tr>
		</table>
	</div><!-- /.main-content.expand-content -->
</div><!-- /.order-info-section -->

<div class="order-info-section expandable allow-multiple-expanded">
	<header>
		<span class="text-wrap"><?=GetMessage('BITRONIC2_SPOD_ORDER_PROPERTIES')?></span>
	</header>
	<div class="main-content expand-content">
		<table>
			<tr>
				<td class="desc"><?=GetMessage('BITRONIC2_SPOD_ORDER_PERS_TYPE')?></td>
				<td class="value"><?=$arResult['PERSON_TYPE']['NAME']?></td>
			</tr>
		</table>
	</div><!-- /.main-content.expand-content -->
</div><!-- /.order-info-section -->

<?foreach($arResult['PROPS_BY_GROUP'] as $arGroupProps):
	if(empty($arGroupProps) || !is_array($arGroupProps)) continue;?>
	
	<div class="order-info-section expandable allow-multiple-expanded">
		<header>
			<span class="text-wrap"><?=$arGroupProps[0]['GROUP_NAME']?></span>
		</header>
		<div class="main-content expand-content">
			<table>
				<?foreach($arGroupProps as $arProp):?>
					<tr>
						<td class="desc"><?=$arProp['NAME']?>:</td>
						<td class="value"><?=$arProp['VALUE']?></td>
					</tr>
				<?endforeach?>
			</table>
		</div><!-- /.main-content.expand-content -->
	</div><!-- /.order-info-section -->
<?endforeach?>

<div class="order-info-section expandable allow-multiple-expanded expanded">
		<header>
			<span class="text-wrap"><?=GetMessage('BITRONIC2_SPOD_PAYED_AND_DELIVERY')?></span>
		</header>
		<div class="main-content expand-content">
			<table>
			<?if(!empty($arResult['PAYMENT']) && is_array($arResult['PAYMENT'])):?>
				<?foreach ($arResult['PAYMENT'] as $payment):?>
					<tr>
						<td class="desc"><?=GetMessage('BITRONIC2_SPOD_PAY_SYSTEM')?></td>
						<td class="value">
							<?if(intval($payment["PAY_SYSTEM_ID"])):?>
								<?if ($payment['PAY_SYSTEM']):?>
									<?=$payment["PAY_SYSTEM"]["NAME"].' ('.$payment['PRICE_FORMATED'].')'?>
								<?else:?>
									<?=$payment["PAY_SYSTEM_NAME"].' ('.$payment['PRICE_FORMATED'].')';?>
								<?endif;?>
							<?else:?>
								<?=GetMessage("BITRONIC2_SPOD_NONE")?>
							<?endif?>
						</td>
					</tr>
					<tr>
						<td class="desc"><?=GetMessage('BITRONIC2_SPOD_ORDER_PAYED')?></td>
						<td class="value">
							<?if($payment["PAID"] == "Y"):?>
								<?=GetMessage('BITRONIC2_SPOD_YES')?>
								<?if(strlen($payment["DATE_PAID_FORMATED"])):?>
									(<?=GetMessage('BITRONIC2_SPOD_FROM')?> <?=$payment["DATE_PAID_FORMATED"]?>)
								<?endif;?>
							<?else:?>
								<?=GetMessage('BITRONIC2_SPOD_NO')?>
								<?if($payment["CAN_REPAY"]=="Y" && $payment["PAY_SYSTEM"]["PSA_NEW_WINDOW"] == "Y"):?>
									&nbsp;&nbsp;&nbsp;[<a href="<?=$payment["PAY_SYSTEM"]["PSA_ACTION_FILE"]?>" target="_blank"><?=GetMessage("BITRONIC2_SPOD_REPEAT_PAY")?></a>]
								<?endif;?>
							<?endif?>
						</td>
						<?if($payment["CAN_REPAY"]=="Y" && $payment["PAY_SYSTEM"]["PSA_NEW_WINDOW"] != "Y"):?>
							<tr>
								<td colspan="2">
									<?
										if (array_key_exists('ERROR', $payment) && strlen($payment['ERROR']) > 0)
											ShowError($payment['ERROR']);
										elseif (array_key_exists('BUFFERED_OUTPUT', $payment))
											echo $payment['BUFFERED_OUTPUT'];
									?>
								</td>
							</tr>
						<?endif?>
					</tr>
				<?endforeach?>
			<?else:?>
				<tr>
					<td class="desc"><?=GetMessage('BITRONIC2_SPOD_PAY_SYSTEM')?></td>
					<td class="value"><?=$arResult['PAY_SYSTEM']['NAME']?></td>
				</tr>
				<tr>
					<td class="desc"><?=GetMessage('BITRONIC2_SPOD_ORDER_PAYED')?></td>
					<td class="value">
						<?if($arResult["PAYED"] == "Y"):?>
							<?=GetMessage('BITRONIC2_SPOD_YES')?>
							<?if(strlen($arResult["DATE_PAYED_FORMATED"])):?>
								(<?=GetMessage('BITRONIC2_SPOD_FROM')?> <?=$arResult["DATE_PAYED_FORMATED"]?>)
							<?endif?>
						<?else:?>
							<?=GetMessage('BITRONIC2_SPOD_NO')?>
							<?if($arResult["CAN_REPAY"]=="Y" && $arResult["PAY_SYSTEM"]["PSA_NEW_WINDOW"] == "Y"):?>
								&nbsp;&nbsp;&nbsp;[<a href="<?=$arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"]?>" target="_blank"><?=GetMessage("BITRONIC2_SPOD_REPEAT_PAY")?></a>]
							<?endif?>
						<?endif?>
					</td>
				</tr>
				<?if($arResult["CAN_REPAY"]=="Y" && $arResult["PAY_SYSTEM"]["PSA_NEW_WINDOW"] != "Y"):?>
					<tr>
						<td colspan="2">
							<?
								$ORDER_ID = $arResult['ACCOUNT_NUMBER'];

								try
								{
									include($arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"]);
								}
								catch(\Bitrix\Main\SystemException $e)
								{
									if($e->getCode() == CSalePaySystemAction::GET_PARAM_VALUE)
										$message = GetMessage("SOA_TEMPL_ORDER_PS_ERROR");
									else
										$message = $e->getMessage();

									ShowError($message);
								}

							?>
						</td>
					</tr>
				<?endif?>
			<?endif?>
				<tr>
					<td class="desc"><?=GetMessage('BITRONIC2_SPOD_ORDER_DELIVERY')?></td>
					<td class="value">
						<?if(strpos($arResult["DELIVERY_ID"], ":") !== false || intval($arResult["DELIVERY_ID"])):?>
							<?=$arResult["DELIVERY"]["NAME"]?>

							<?if(intval($arResult['STORE_ID']) && !empty($arResult["DELIVERY"]["STORE_LIST"][$arResult['STORE_ID']])):?>

								<?$store = $arResult["DELIVERY"]["STORE_LIST"][$arResult['STORE_ID']];?>
								<div class="bx_ol_store">
									<div class="bx_old_s_row_title">
										<?=GetMessage('BITRONIC2_SPOD_TAKE_FROM_STORE')?>: <b><?=$store['TITLE']?></b>

										<?if(!empty($store['DESCRIPTION'])):?>
											<div class="bx_ild_s_desc">
												<?=$store['DESCRIPTION']?>
											</div>
										<?endif?>

									</div>
									
									<?if(!empty($store['ADDRESS'])):?>
										<div class="bx_old_s_row">
											<b><?=GetMessage('BITRONIC2_SPOD_STORE_ADDRESS')?></b>: <?=$store['ADDRESS']?>
										</div>
									<?endif?>

									<?if(!empty($store['SCHEDULE'])):?>
										<div class="bx_old_s_row">
											<b><?=GetMessage('BITRONIC2_SPOD_STORE_WORKTIME')?></b>: <?=$store['SCHEDULE']?>
										</div>
									<?endif?>

									<?if(!empty($store['PHONE'])):?>
										<div class="bx_old_s_row">
											<b><?=GetMessage('BITRONIC2_SPOD_STORE_PHONE')?></b>: <?=$store['PHONE']?>
										</div>
									<?endif?>

									<?if(!empty($store['EMAIL'])):?>
										<div class="bx_old_s_row">
											<b><?=GetMessage('BITRONIC2_SPOD_STORE_EMAIL')?></b>: <a href="mailto:<?=$store['EMAIL']?>"><?=$store['EMAIL']?></a>
										</div>
									<?endif?>

									<?if(($store['GPS_N'] = floatval($store['GPS_N'])) && ($store['GPS_S'] = floatval($store['GPS_S']))):?>
										
										<div id="bx_old_s_map">

											<div class="bx_map_buttons">
												<a href="javascript:void(0)" class="bx_big bx_bt_button_type_2 bx_cart" id="map-show">
													<?=GetMessage('BITRONIC2_SPOD_SHOW_MAP')?>
												</a>

												<a href="javascript:void(0)" class="bx_big bx_bt_button_type_2 bx_cart" id="map-hide">
													<?=GetMessage('BITRONIC2_SPOD_HIDE_MAP')?>
												</a>
											</div>

											<?ob_start();?>
												<div itemscope itemtype="http://schema.org/ImageObject"><?$mg = $arResult["DELIVERY"]["STORE_LIST"][$arResult['STORE_ID']]['IMAGE'];?>
													<?if(!empty($mg['SRC'])):?><img itemprop="contentUrl" class="lazy" data-original="<?=$mg['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" width="<?=$mg['WIDTH']?>" height="<?=$mg['HEIGHT']?>"><br /><br /><?endif?>
													<?=$store['TITLE']?></div>
											<?$ballon = ob_get_contents();?>
											<?ob_end_clean();?>

											<?
												$mapId = '__store_map';

												$mapParams = array(
												'yandex_lat' => $store['GPS_N'],
												'yandex_lon' => $store['GPS_S'],
												'yandex_scale' => 16,
												'PLACEMARKS' => array(
													array(
														'LON' => $store['GPS_S'],
														'LAT' => $store['GPS_N'],
														'TEXT' => $ballon
													)
												));
											?>

											<div id="map-container">

												<?$APPLICATION->IncludeComponent("bitrix:map.yandex.view", ".default", array(
													"INIT_MAP_TYPE" => "MAP",
													"MAP_DATA" => serialize($mapParams),
													"MAP_WIDTH" => "100%",
													"MAP_HEIGHT" => "200",
													"CONTROLS" => array(
														0 => "SMALLZOOM",
													),
													"OPTIONS" => array(
														0 => "ENABLE_SCROLL_ZOOM",
														1 => "ENABLE_DBLCLICK_ZOOM",
														2 => "ENABLE_DRAGGING",
													),
													"MAP_ID" => $mapId
													),
													false
												);?>

											</div>

											<?CJSCore::Init();?>
											<script>
												new CStoreMap({mapId:"<?=$mapId?>", area: '.bx_old_s_map'});
											</script>

										</div>

									<?endif?>

								</div>

							<?endif?>

						<?else:?>
							<?=GetMessage("BITRONIC2_SPOD_NONE")?>
						<?endif?>
					</td>
				</tr>
				<?if(!empty($arResult["TRACKING_NUMBER"])):?>
					<tr>
						<td><?=GetMessage('BITRONIC2_SPOD_ORDER_TRACKING_NUMBER')?>:</td>
						<td><?=$arResult["TRACKING_NUMBER"]?></td>
					</tr>
				<?endif?>
				<?if(!empty($arResult["USER_DESCRIPTION"])):?>
					<tr>
						<td><?=GetMessage('BITRONIC2_SPOD_ORDER_USER_COMMENT')?>:</td>
						<td><?=$arResult["USER_DESCRIPTION"]?></td>
					</tr>
				<?endif?>
			</table>
		</div><!-- /.main-content.expand-content -->
	</div><!-- /.order-info-section -->

    <div class="title-h3"><?=GetMessage('BITRONIC2_SPOD_ORDER_BASKET')?></div>
	<table class="items-table">
		<thead>
			<tr>
				<th colspan="2"><?=GetMessage('BITRONIC2_SPOD_NAME')?></th>
				<th class="availability"><?=GetMessage('BITRONIC2_SPOD_QUANTITY')?></th>
				<th class="price"><?=GetMessage('BITRONIC2_SPOD_PRICE')?></th>
				<th class="sum"><?=GetMessage('BITRONIC2_SPOD_SUMM')?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					<div class="totals">
						<table class="table_totals">
							<?if($arResult['PRICE'] != $arResult['PRODUCT_SUM']):?>
								<tr>
									<td class="text"><?=GetMessage('BITRONIC2_SPOD_PRODUCT_SUM')?>:</td>
									<td class="value"><?=$arResult['PRODUCT_SUM_FORMATTED']?></td>
								</tr>
							<?endif?>
							<?if($arResult['HAS_DISCOUNT']):?>
								<tr>
									<td class="text"><?=GetMessage('BITRONIC2_SPOD_DISCOUNT')?></td>
									<td class="value"><?=$arResult['DISCOUNT_VALUE_FORMATED']?></td>
								</tr>
							<?endif?>
							<?if(floatval($arResult["ORDER_WEIGHT"])):?>
								<tr>
									<td class="text"><?=GetMessage('BITRONIC2_SPOD_TOTAL_WEIGHT')?></td>
									<td class="value"><?=$arResult['ORDER_WEIGHT_FORMATED']?></td>
								</tr>
							<?endif?>
							<?if($arResult["PRICE_DELIVERY"] > 0):?>
								<tr>
									<td class="text"><?=GetMessage('BITRONIC2_SPOD_DELIVERY')?></td>
									<td class="value"><?=$arResult['PRICE_DELIVERY_FORMATED']?></td>
								</tr>
							<?endif?>
							<tr class="final-total">
								<td class="text"><?=GetMessage('BITRONIC2_SPOD_SUMMARY')?></td>
								<td class="value"><?=$arResult['PRICE_FORMATED']?></td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?foreach($arResult["BASKET"] as $arItem):?>
				<tr class="table-item">
					<td itemscope itemtype="http://schema.org/ImageObject" class="photo">
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
							<img itemprop="contentUrl" class="lazy" data-original="<?=$arItem['PICTURE']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['NAME']?>">
						</a>
					</td>
					<td class="name">
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="link"><span class="text"><?=$arItem['NAME']?></span></a>
						<?if($arResult['HAS_PROPS']):?>
						<div>
							<?foreach($arItem["PROPS"] as $prop):?>
								<span class="sku"><?=$prop["NAME"]?>: <?=$prop["VALUE"]?></span>
							<?endforeach?>
						</div>
						<?endif?>
					</td>
					<td class="availability">
						x <?=$arItem['QUANTITY']?> <?=($arItem['MEASURE_TEXT']) ? $arItem['MEASURE_TEXT'] : $arItem['MEASURE_NAME']?>
					</td>
					<td class="price">
						<span class="price-new"><?=$arItem["PRICE_FORMATED"]?></span>
					</td>
					<td class="sum"><?=$arItem["PRICE_SUMM_FORMATED"]?></td>
				</tr>
			<?endforeach?>
		</tbody>
	</table>
	
<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";