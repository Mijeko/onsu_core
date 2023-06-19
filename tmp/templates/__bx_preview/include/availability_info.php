<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * @var bool $availableFrame - do we need to create dynamic frame
 * @var bool $availableOnRequest - is our product available on request only
 * @var bool $bShowEveryStatus - do we need to put every status into html (for SKU switch)
 * @var bool $bShowStore - do we need to show stores popup
 * @var float $availableQuantity - CATALOG_QUANTITY
 * @var int $availableItemID - iblock element id
 * @var string $availableClass - add text inside class attribute
 * @var string $availableForOrderText - text to show instead of 'include_areas/catalog/for_order_text.php'
 * @var string $availableID - add attribute "id" with variable's content
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
//$bExpandedStore = $rz_b2_options['product-availability'] == 'expanded';

$availableFrameID = 'bxdinamic_availability_' . $this->randString();
?>
<? if (false){ ?>
<div class="availability-info"<?if($availableFrame === true):?> id="<?=$availableFrameID?>"<?endif?>>
<?
if ($availableFrame === true) {
	$frame = $this->createFrame($availableFrameID, false)->begin(CRZBitronic2Composite::insertCompositLoader());
}
?>
<div class="availability-status <?=empty($availableClass)?'':$availableClass?>"<?if(!empty($availableID)):?> id="<?=$availableID?>"<?endif?>>
<? if(!empty($bShowEveryStatus) || $availableClass == 'in-stock' || empty($availableClass)): ?>
	<div class="when-in-stock">
		<div class="info-tag" data-placement="right" data-position="centered bottom"
			<?if($bShowStore && $rz_b2_options['stores'] !== 'disabled'):?>
			data-tooltip title="<?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_STORES')?>"
			<?if (!$bExpandedStore && !$bTabsStore):?>data-popup=">.store-info"<?else:?>data-popup-tmp=">.store-info"<?endif?>
			<?elseif($bShowStore):?>
			data-was-tooltip="true" data-orig-title="<?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_STORES')?>"
			data-orig-popup=">.store-info"
			<?endif?>
			>
			<span class="text"<?if(floatval($availableQuantity)>0):?> data-how-much=" (<?=$availableQuantity?><?=($availableMeasure?' '.$availableMeasure:'')?>)"<?endif?>>
				<?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_TRUE')?>
			</span>
			<?if($bShowStore && !$bExpandedStore && !$bTabsStore):?>
			<div class="store-info notification-popup" data-id="<?=$availableItemID?>" data-postfix="<?=$availableStoresPostfix?>">
				<div class="content" id="catalog_store_amount_div_<?=$availableStoresPostfix?>_<?=$availableItemID?>">
				</div>
			</div>
			<?endif?>
		</div><!-- .info-tag -->
	</div><!-- /.when-in-stock -->
<? endif ?>
<? if(!empty($bShowEveryStatus) || $availableClass == 'available-for-order' || $availableClass == 'available-on-request'): ?>
	<div class="when-available-for-order<?if($availableOnRequest):?> when-available-on-request<?endif?>">
		<div class="info-tag" <? /* TODO 
			title="title" 
			data-tooltip
			data-placement="bottom"
			data-toggle="modal"
			data-target="#modal_place-order"*/?>
			>
			<span class="text on-request"><?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_ON_REQUEST')?></span>
			<span class="text  for-order"><?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_FOR_ORDER') ?></span>
		</div>
		<div class="info-info">
		<? if (empty($availableForOrderText)): ?>
			<?$APPLICATION->IncludeComponent('bitrix:main.include', '', array("PATH" => SITE_DIR."include_areas/catalog/for_order_text.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"), $component, array("HIDE_ICONS"=>"Y"))?>
		<? else: ?>
			<?=$availableForOrderText?>
		<? endif ?>
		</div>
	</div>
<? endif ?>
<? if(!empty($bShowEveryStatus) || $availableClass == 'out-of-stock'): ?>
	<div class="when-out-of-stock">
		<div class="info-tag"
			<? if ($availableSubscribe == 'Y'): ?>
			title="<?=GetMessage('BITRONIC2_PRODUCT_SUBSCRIBE')?>"
			data-tooltip
			data-product="<?= $availableItemID ?>"
			data-placement="bottom"
			data-toggle="modal"
			data-target="#modal_subscribe_product"
			<? endif ?>
			>
			<span class="text"><?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_FALSE')?></span>
		</div><!-- .info-tag -->
	</div>
<? endif ?>
</div><!-- .availability-status -->
<? if ($availableFrame === true) $frame->end() ?>
</div><!-- .availability-info -->
    <? } ?>
    <?unset($availableForOrderText, $availableID, $availableItemID, $availableMeasure, $availableQuantity);
