<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

$bStores = $arParams["SHOW_AMOUNT_STORE"] == "Y" && Bitrix\Main\ModuleManager::isModuleInstalled("catalog");
$itemCount = count($arResult['ITEMS']);
$id = 'bxdinamic_bitronic2_favorite_list';?>

<div class="top-line-item favorites">
	<a href="javascript:;" class="btn-favorites pseudolink with-icon rz-no-pointer" data-popup="#popup_favorites" id="favorites-toggler">
		<i class="flaticon-heart3"></i>
		<span class="items-inside" id="<?=$id?>">
			<?
			$frame = $this->createFrame($id, false)->begin(CRZBitronic2Composite::insertCompositLoader());
			?>
			<?=$itemCount?>
			<?$frame->end();?>
		</span>
		<span class="link-text"><?=GetMessage('BITRONIC2_FAVORITES_TITLE')?></span>
	</a>
	<div class="top-line-popup popup_favorites" id="popup_favorites" data-darken>
		<button class="btn-close" data-popup="#popup_favorites">
			<span class="btn-text"><?=GetMessage('BITRONIC2_MODAL_CLOSE')?></span>
			<i class="flaticon-close47"></i>
		</button>
		<div class="popup-header">
			<span class="header-text">
				<?=GetMessage('BITRONIC2_FAVORITES_IN_LIST')?> <?=$itemCount?> <?=\Yenisite\Core\Tools::rusQuantity($itemCount, GetMessage('BITRONIC2_FAVORITES_GOODS'))?>:
			</span>
		</div>
		<div class="table-wrap">
			<div class="scroller scroller_v">
				<?$frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader());?>
				<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';?>
				<?if($itemCount > 0):?>
				<table class="items-table">
					<thead>
						<tr>
							<th colspan="2"><?=GetMessage('BITRONIC2_FAVORITES_GOOD')?></th>
							<th class="availability"></th>
							<th class="price"><?=GetMessage('BITRONIC2_FAVORITES_PRICE')?></th>
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
									$availableStoresPostfix = 'favorites';
									$availableSubscribe = $arItem['CATALOG_SUBSCRIBE'];
									$bShowEveryStatus = true;
									include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/availability_info.php';
									?>
								</td>
								
								
								<td class="price">
								<?if(!$arItem['ON_REQUEST']):?>
									<span class="price-new">
									<?=($arItem['bOffers'] && $arItem['bOffersNotEqual']) ? GetMessage('BITRONIC2_FAVORITES_FROM') : ''?>
									<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);?></span>
									<div>
										<?if($arItem['MIN_PRICE']['DISCOUNT_DIFF'] > 0):?>
										<span class="price-old"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['VALUE'], $arItem['MIN_PRICE']['PRINT_VALUE']);?></span>
										<?endif?>
									</div>
								<?endif?>
								</td>
								
								<td class="actions">
									<button class="btn-delete pseudolink with-icon" data-tooltip title="<?=GetMessage('BITRONIC2_FAVORITES_DELETE')?>" data-placement="bottom" data-id="<?=$arItem['ID']?>">
										<i class="flaticon-trash29"></i>
										<span class="btn-text"><?=GetMessage('BITRONIC2_FAVORITES_DELETE')?></span>
									</button>
								</td>
							</tr>
						<?endforeach;?>
					</tbody>
				</table>
				<?endif?>

				<script>
					RZB2.ajax.Favorite.ElementsList = <?=CUtil::PhpToJSObject($arParams['FAVORITE_LIST'], false, true, true)?>;
					RZB2.ajax.Favorite.Params = {actionVar: 'ACTION', productVar: 'ID'};
				</script>
				<?$frame->end();?>

				<div class="scroller__track scroller__track_v">
					<div class="scroller__bar scroller__bar_v"></div>
				</div>
			</div>
		</div>
		<div class="popup-footer">
			<button class="btn-delete pseudolink with-icon">
				<i class="flaticon-trash29"></i>
				<span class="btn-text"><?=GetMessage('BITRONIC2_FAVORITES_CLEAN')?></span>
			</button>
			<button class="btn-main"><?=GetMessage('BITRONIC2_FAVORITES_ADD_ALL_IN_BASKET')?></button>
		</div>
	</div>
</div>
<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
