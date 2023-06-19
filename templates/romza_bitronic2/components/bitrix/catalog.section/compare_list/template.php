<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

$templateData = $arResult['TEMPLATE_DATA'];

$bStores = $arParams["SHOW_AMOUNT_STORE"] == "Y" && Bitrix\Main\ModuleManager::isModuleInstalled("catalog");
$bExpandedStore = false;
$itemCount = count($arResult['ITEMS']);
$id = 'bxdinamic_bitronic2_compare_list_'.$arParams['IBLOCK_ID'];
?>
<div class="top-line-item compare">
	<a href="<?=$arParams['COMPARE_URL']?>" class="btn-compare pseudolink with-icon rz-no-pointer" data-popup="#popup_compare" id="compare-toggler">
		<i class="flaticon-balance3"></i>
		<span class="items-inside" id="<?=$id?>">
			<?
			$frame = $this->createFrame($id, false)->begin(CRZBitronic2Composite::insertCompositLoader());
			?>
			<?=$itemCount?>
			<?$frame->end();?>
		</span>
		<span class="link-text"><?=GetMessage('BITRONIC2_COMPARE_TITLE')?></span>
	</a>
	<div class="top-line-popup popup_compare" id="popup_compare" data-darken>
		<button class="btn-close" data-popup="#popup_compare">
			<span class="btn-text"><?=GetMessage('BITRONIC2_MODAL_CLOSE')?></span>
			<i class="flaticon-close47"></i>
		</button>
		<div class="popup-header">
			<?$frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader());?>
			<span class="header-text">
				<?=GetMessage('BITRONIC2_COMPARE_IN_LIST')?> <?=$itemCount?> <?=\Yenisite\Core\Tools::rusQuantity($itemCount, GetMessage('BITRONIC2_COMPARE_GOODS'))?>:
			</span>
			<?$frame->end();?>
		</div>
		<div class="table-wrap">
			<div class="scroller scroller_v">
				<?$frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader());?>
				<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';?>
				<?if($itemCount > 0):?>
				<table class="items-table">
					<thead>
						<tr>
							<th colspan="2"><?=GetMessage('BITRONIC2_COMPARE_GOOD')?></th>
							<th class="availability"></th>
							<th class="price"><?=GetMessage('BITRONIC2_COMPARE_PRICE')?></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?foreach($arResult['ITEMS'] as $arItem):
							$imgTitle = (
								!empty($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'])
								? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
								: $arItem['NAME']
							);
							$bShowStore = $bStores && !$arItem['bOffers'];
							$availableOnRequest = $arItem['ON_REQUEST'];
							$availableClass = (
								!$arItem['CAN_BUY'] && !$availableOnRequest
								? 'out-of-stock'
								: (
									$arItem['FOR_ORDER'] || $availableOnRequest
									? 'available-for-order'
									: 'in-stock'
								)
							);
							if ($availableOnRequest) $arItem['CAN_BUY'] = false;
							?>
							<tr class="table-item popup-table-item  <?=$availableClass?>">
								<td itemscope itemtype="http://schema.org/ImageObject" class="photo">
									<img itemprop="contentUrl" class="lazy" data-original="<?=$arItem['PICTURE_PRINT']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['PICTURE_PRINT']['ALT']?>" title="<?= $imgTitle ?>">
								</td>
								<td class="name">
									<input type="hidden" name="quantity" value="<?=($arItem['CAN_BUY']?$arItem['CATALOG_MEASURE_RATIO']:0)?>" data-id="<?=$arItem['ID']?>">
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link"><span class="text"><?=$arItem['NAME']?></span></a>
										<?
										if($arParams['SHOW_VOTING'] == 'Y')
										{
											$APPLICATION->IncludeComponent("bitrix:iblock.vote", "stars", array(
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
											);
										}?>
								</td>
								<td class="availability">
									<?
									$availableID = false;
									$availableClass = '';
									$availableFrame = false;
									$availableForOrderText = &$arItem['PROPERTIES']['RZ_FOR_ORDER_TEXT']['VALUE'];
									$availableItemID = &$arItem['ID'];
									$availableMeasure = &$arItem['CATALOG_MEASURE_NAME'];
									$availableQuantity = &$arItem['CATALOG_QUANTITY'];
									$availableStoresPostfix = 'compare';
									$availableSubscribe = $arItem['CATALOG_SUBSCRIBE'];
									$bShowEveryStatus = true;
                                    $bExpandedStore = false;
									include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/availability_info.php';
									?>
								</td>
								
								
								<td class="price">
								<?if(!$arItem['ON_REQUEST']):?>
									<span class="price-new">
									<?=($arItem['bOffers'] && $arItem['bOffersNotEqual']) ? GetMessage('BITRONIC2_COMPARE_FROM') : ''?>
									<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);?></span>
									<div>
										<?if($arItem['MIN_PRICE']['DISCOUNT_DIFF'] > 0):?>
										<span class="price-old"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['VALUE'], $arItem['MIN_PRICE']['PRINT_VALUE']);?></span>
										<?endif?>
									</div>
								<?endif?>
								</td>
								
								<td class="actions">
									<button class="btn-delete pseudolink with-icon" data-tooltip title="<?=GetMessage('BITRONIC2_CATALOG_DELETE')?>" data-placement="bottom" data-id="<?=$arItem['ID']?>">
										<i class="flaticon-trash29"></i>
										<span class="btn-text"><?=GetMessage('BITRONIC2_COMPARE_DELETE')?></span>
									</button>
								</td>
							</tr>
						<?endforeach;?>
					</tbody>
				</table>
				<?endif?>

				<script>
					RZB2.ajax.Compare.ElementsList = <?=CUtil::PhpToJSObject($arParams['COMPARE_LIST'], false, true, true)?>;
					RZB2.ajax.Compare.Params = {actionVar: '<?=$arParams['ACTION_CATALOG_VARIABLE']?>', productVar: '<?=$arParams['PRODUCT_ID_CATALOG_VARIABLE']?>'};
				</script>
				<?$frame->end();?>

				<div class="scroller__track scroller__track_v">
					<div class="scroller__bar scroller__bar_v"></div>
				</div>
			</div>
		</div>
		<div class="popup-footer" id="<?=$id?>_footer"><?
			$frame = $this->createFrame($id.'_footer', false)->begin();?>
			<button class="btn-delete pseudolink with-icon">
				<i class="flaticon-trash29"></i>
				<span class="btn-text"><?=GetMessage('BITRONIC2_COMPARE_CLEAR')?></span>
			</button>
			<form action="<?=$arResult['COMPARE_URL']?>" class="inline">
				<button class="btn-main"><?=GetMessage('BITRONIC2_COMPARE_COMPARE')?></button>
			</form><?
			$frame->beginStub();?>

			<button class="btn-delete pseudolink with-icon">
				<i class="flaticon-trash29"></i>
				<span class="btn-text"><?=GetMessage('BITRONIC2_COMPARE_CLEAR')?></span>
			</button>
			<button class="btn-main disabled"><?=GetMessage('BITRONIC2_COMPARE_COMPARE')?></button><?
			$frame->end();?>

		</div>
	</div>
</div>
<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
