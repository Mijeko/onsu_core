<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @var bool $bShowStore - do we need to show stores popup
 * @var float $availableQuantity - CATALOG_QUANTITY
 * @var int $availableItemID - iblock element id
 * @var string $availableClass - add text inside class attribute
 * @var string $availableForOrderText - text for span.when-available-for-order[title]
 * @var string $availableMeasure - CATALOG_MEASURE_NAME
 * @var string $availableStoresPostfix - postfix to add to stores container id attribute
 * @var string $availableSubscribe - Y if can subscribe
 **/

global $rz_b2_options;
global $rz_b2_storeCount;

if ($headerLangIncluded !== true) {
	\Bitrix\Main\Localization\Loc::loadMessages(SITE_TEMPLATE_PATH . '/header.php');
	$headerLangIncluded = true;
}

if ($bShowStore) {
	if (!isset($rz_b2_storeCount)) {
		CModule::IncludeModule('catalog');

		$filter = array(
			"ACTIVE" => "Y",
			"+SITE_ID" => SITE_ID,
			"ISSUING_CENTER" => 'Y'
		);

		$rz_b2_storeCount = CCatalogStore::GetList(
			array('TITLE' => 'ASC', 'ID' => 'ASC'),
			$filter,
			array() // to fetch only count of stores
		);
	}
	$bShowStore = ($rz_b2_storeCount > 0);
}
?>
<? if($availableClass == 'in-stock' || empty($availableClass)): ?>
<span class="avail-dot when-in-stock" data-placement="right" data-position="centered bottom" data-tooltip<?
	if($bShowStore && $rz_b2_options['stores'] !== 'disabled'):?>
	title="<?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_STORES')?>"
	data-popup=">.store-info"<?
	else:?>
	title="<?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_TRUE')?><?
		if($rz_b2_options['show-stock'] == 'Y' && floatval($availableQuantity)>0):
			?> (<?=$availableQuantity?> <?=$availableMeasure?>)<?
		endif?>"<?
		if($bShowStore):?>
	data-was-tooltip="true" data-orig-title="<?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_STORES')?>"
	data-orig-popup=">.store-info"<?
		endif;
	endif?>
	data-how-much="<?if(floatval($availableQuantity)>0):?> (<?=$availableQuantity?> <?=$availableMeasure?>)<?endif?>"
	data-text="<?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_TRUE')?>">
	<?if($bShowStore):?>
	<span class="store-info notification-popup" data-id="<?=$availableItemID?>" data-postfix="<?=$availableStoresPostfix?>">
		<span class="content" id="catalog_store_amount_div_<?=$availableStoresPostfix?>_<?=$availableItemID?>">
		</span>
	</span><!-- .store-info.notification-popup -->
	<?endif?>
</span>
<? endif ?>
<? if($availableClass == 'out-of-stock'): ?>
<span class="avail-dot when-out-of-stock"
	<? if ($availableSubscribe == 'Y'): ?>
	title="<?=GetMessage('BITRONIC2_PRODUCT_SUBSCRIBE')?>"
	data-tooltip
	data-product="<?= $availableItemID ?>"
	data-placement="bottom"
	data-toggle="modal"
	data-target="#modal_subscribe_product"
	<? endif ?>
	data-text="<?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_FALSE')?>"></span>
<? endif ?>
<? if($availableClass == 'available-for-order' || $availableClass == 'available-on-request'): ?>
<span class="avail-dot when-available-for-order"<? /* TODO
	title="You can order this product. Shipment is up to 10 work days" data-tooltip
	data-placement="bottom"
	data-toggle="modal"
	data-target="#modal_place-order"*/?>
	title="<?=$availableForOrderText ? $availableForOrderText : GetMessage('BITRONIC2_PRODUCT_AVAILABLE_FOR_ORDER')?>"
	data-tooltip data-placement="bottom"
	data-text="<?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_FOR_ORDER') ?>"></span>
<? endif ?>
<?unset($availableForOrderText, $availableItemID, $availableMeasure, $availableQuantity);