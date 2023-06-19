<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// @var $moduleId
include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/module_code.php';

CModule::IncludeModule($moduleId);

if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>

<div class="account-page">
	<div id="order-list">

	<?if(!empty($arResult['ITEMS'])):?>

		<table class="table_order-history">
			<thead>
				<tr class="head-row">
					<th class="order-code"><?=GetMessage('COL_DATE')?></th>
					<th class="order-code"><?=GetMessage('COL_NUMBER')?></th>
					<th class="order-sum"><?=GetMessage('COL_PRICE')?></th>
					<th class="order-status"><?=GetMessage('COL_STATUS')?></th>
				</tr>
			</thead>
			<tbody>
			<?foreach($arResult["ITEMS"] as $arItem):?>
				<tr>
					<td class="order-date"><?=$arItem["DATE_CREATE"]?></td>
					<td class="order-code">
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="link" title="<?=GetMessage('SPOL_ORDER')?> <?=GetMessage('SPOL_NUM_SIGN')?><?=$arItem["ID"]?> <?=GetMessage('PO')?> <?=$arItem["DATE_CREATE"]?>">
							<span class="text"><?=$arItem["ID"]?></span>
						</a>
					</td>
					<td class="order-sum"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(false, $arItem["DISPLAY_PROPERTIES"]['AMOUNT']["VALUE"])?></td>
					<td class="order-status"><?=$arItem["DISPLAY_PROPERTIES"]['STATUS']["DISPLAY_VALUE"];?></td>
				</tr>
			<?endforeach?>

			</tbody>
		</table>
		
		<?if(strlen($arResult['NAV_STRING'])):?>
			<?=$arResult['NAV_STRING']?>
		<?endif?>

	<?else:?>
		<?=GetMessage('SPOL_NO_ORDERS')?>
	<?endif?>
    </div>
</div>