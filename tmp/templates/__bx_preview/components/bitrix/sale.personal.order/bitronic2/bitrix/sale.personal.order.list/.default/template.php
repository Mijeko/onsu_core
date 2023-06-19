<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';?>
<?
CModule::IncludeModule('yenisite.core');
$isAjax = \Yenisite\Core\Tools::isAjax();
if ($isAjax) {
	$APPLICATION->RestartBuffer();
}
?>
<?if (!$isAjax):?>
	<form action="<?=$APPLICATION->GetCurPage(false)?>" method="post" class="form_order-filter" name="form_filter">
		<? if (!empty($arResult['ORDERS'])): ?>
			<div class="main-content">
				<label class="order-code">
					<span class="text"><?=GetMessage("RZ_KOD_ZAKAZA")?>:</span>
					<input type="text" name="filter_id" id="filter_id" class="textinput" value="<?= htmlspecialcharsbx($_REQUEST['filter_id']) ?>">
				</label>
				<div class="order-dates">
					<span class="text"><?=GetMessage("RZ_DATA_ZAKAZA")?>:</span>
					<?
					$APPLICATION->IncludeComponent(
						'bitrix:main.calendar',
						'',
						array(
							'SHOW_INPUT' => 'Y',
							'FORM_NAME' => 'form_filter',
							'INPUT_NAME' => 'filter_date_from',
							'INPUT_VALUE' => $_REQUEST['filter_date_from'],
							'SHOW_TIME' => 'N',
							'INPUT_ADDITIONAL_ATTR' => 'class="textinput masked-date"'
						),
						null,
						array('HIDE_ICONS' => 'Y')
					);
					?>
					<span class="text">&mdash;</span>
					<?
					$APPLICATION->IncludeComponent(
						'bitrix:main.calendar',
						'',
						array(
							'SHOW_INPUT' => 'Y',
							'FORM_NAME' => 'form_filter',
							'INPUT_NAME' => 'filter_date_to',
							'INPUT_VALUE' => $_REQUEST['filter_date_to'],
							'SHOW_TIME' => 'N',
							'INPUT_ADDITIONAL_ATTR' => 'class="textinput masked-date"'
						),
						null,
						array('HIDE_ICONS' => 'Y')
					);
					?>
				</div>
				<button type="submit" class="btn-main order-filter-submit">
					<span class="text"><?=GetMessage("RZ_POKAZAT")?></span>
				</button>
			</div>
		<? endif ?>
			<footer>
				<button type="button" class="action order-filter-reset disabled" disabled>
					<i class="flaticon-close47"></i>
					<span class="text"><?=GetMessage("RZ_SBROSIT")?><span class="hidden-xs"> <?=GetMessage("RZ_VSE_PARAMETRI")?></span></span>
				</button>
				<button type="button" class="order-filter-small active show_all">
					<span class="text"><?=GetMessage("RZ_VSE")?></span>
				</button>
				<? foreach ($arResult['INFO']['STATUS'] as $ID => $arStatus): ?>
					<button type="button" class="order-filter-small" data-status-id="<?=$ID?>">
						<span class="text"><?= $arStatus['NAME'] ?></span>
					</button>
				<? endforeach ?>
				<button type="button" class="order-filter-small" data-status-id="canceled">
					<span class="text"><?=GetMessage("RZ_OTMENENNIE")?></span>
				</button>
			</footer>
	</form><!-- /.form_order-filter -->
	
	<div id="order-list">
<?endif?>
<?if(!empty($arResult['ERRORS']['FATAL'])):?>
	<?=CRZBitronic2CatalogUtils::ShowMessage(Array("MESSAGE" => $arResult['ERRORS']['FATAL'], "TYPE" => "ERROR"));?>
<?else:?>
	<?if(!empty($arResult['ERRORS']['NONFATAL'])):?>
		<?=CRZBitronic2CatalogUtils::ShowMessage(Array("MESSAGE" => $arResult['ERRORS']['NONFATAL'], "TYPE" => "ERROR"));?>
	<?endif?>
	<?
	if(!empty($arResult['ORDERS'])):?>
		<table class="table_order-history">
			<thead>
				<tr class="head-row">
					<th class="order-code"><?=GetMessage('BITRONIC2_SPOL_ID')?></th>
					<th class="order-sum"><?=GetMessage('BITRONIC2_SPOL_SUM')?></th>
					<th class="order-status"><?=GetMessage('BITRONIC2_SPOL_STATUS')?></th>
					<th class="order-content"><?=GetMessage('BITRONIC2_SPOL_BASKET')?></th>
					<th class="order-payment-n-delivery-types"><?=GetMessage('BITRONIC2_SPOL_PAYSYSTEM_AND_DELIVERY')?></th>
					<th class="order-actions"><?=GetMessage('BITRONIC2_SPOL_ACTIONS')?></th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?foreach($arResult["ORDERS"] as $key => $order):?>
				<tr>
					<td class="order-code">
						<a href="<?=$order["ORDER"]["URL_TO_DETAIL"]?>" class="link"><span class="text"><?=$order["ORDER"]["ACCOUNT_NUMBER"]?></span></a>
						<div class="order-date"><span class="date"><?=GetMessage('BITRONIC2_SPOL_FROM')?> <?=$order["ORDER"]["DATE_INSERT_FORMATED"];?></span></div>
					</td>
          <td class="order-sum">
            <?=$order["ORDER"]["FORMATED_PRICE"]?>
          </td>
					<td class="order-status">
						<span class="<?=$order["ORDER"]['CANCELED'] == 'Y' ? '' : 'when-payed'?>">
							<?if($order["ORDER"]['CANCELED'] == 'Y')
							{
								echo GetMessage('BITRONIC2_SPOL_CANCELLED');
							}
							else
							{
								echo $arResult['INFO']['STATUS'][$order["ORDER"]["STATUS_ID"]]['NAME'];
							}
							?>
						</span>
					</td>
					<td class="order-content">
						<?foreach($order['BASKET_ITEMS'] as $arItem):?>
							<div class="order-content-product"><a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link"><span class="text"><?=$arItem['NAME']?></span></a> - <span class="number"><?=$arItem['QUANTITY']?></span> <?=($arItem['MEASURE_TEXT']) ? $arItem['MEASURE_TEXT'] : $arItem['MEASURE_NAME']?></div>
						<?endforeach?>
					</td>
					<td class="order-payment-n-delivery-types">
						<div class="payment">
							<span class="text"><?=GetMessage('BITRONIC2_SPOL_PAYSYSTEM')?></span>
							<span class="value"><?=$arResult['INFO']['PAY_SYSTEM'][$order["ORDER"]["PAY_SYSTEM_ID"]]['NAME']?></span>
						</div>
						<?if(intval($order["ORDER"]["DELIVERY_ID"] > 0)):?>
							<div class="delivery">
								<span class="text"><?=GetMessage('BITRONIC2_SPOL_DELIVERY')?></span>
								<span class="value"><?=$arResult['INFO']['DELIVERY'][$order["ORDER"]["DELIVERY_ID"]]['NAME']?></span>
							</div>
						<?endif?>
					</td>
					<td class="order-actions">
						<div>
							<?if($order["ORDER"]['CANCELED'] != 'Y'):?>
							<a href="<?=$order["ORDER"]["URL_TO_COPY"]?>" class="action repeat-order">
								<i class="flaticon-refresh"></i>
								<span class="text"><?=GetMessage('BITRONIC2_SPOL_REPEAT_ORDER')?></span>
							</a>							
							<a href="<?=$order["ORDER"]["URL_TO_CANCEL"]?>" class="action cancel-order">
								<i class="flaticon-close47"></i>
								<span class="text"><?=GetMessage('BITRONIC2_SPOL_CANCEL_ORDER')?></span>
							</a>
							<?endif?>
							<div class="switch-order-content">
								<span class="text"><?=GetMessage('BITRONIC2_SPOL_BASKET')?></span>
							</div>
						</div>
					</td>
				</tr>
			<?endforeach?>
			</tbody>
		</table>
		<?if(strlen($arResult['NAV_STRING'])):?>
			<?=$arResult['NAV_STRING']?>
		<?endif?>

	<?else:?>
		<?if(!empty($_REQUEST['filter_date_to'])
			|| !empty($_REQUEST['filter_date_from'])
			|| !empty($_REQUEST['filter_id'])
			|| !empty($_REQUEST['filter_status'])
			|| !empty($_REQUEST['filter_canceled'])
			|| !empty($_REQUEST['filter_history'])
			|| !empty($_REQUEST['filter_payed'])
			):?>
			<p><?=GetMessage('BITRONIC2_SPOL_NO_ORDERS_BY_FILTER')?></p>
			<button type="button" class="action order-filter-reset">
				<i class="flaticon-close47"></i>
				<span class="text"><?=GetMessage("RZ_SBROSIT")?> <?=GetMessage("RZ_VSE_PARAMETRI")?></span>
			</button>
		<? else :?>
			<?=GetMessage('BITRONIC2_SPOL_NO_ORDERS')?>
		<?endif ?>
	<?endif?>
<?endif;?>
<?if (!$isAjax):?>
</div>
<? else : die();?>
<? endif ?>
