<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;

$bStores = $arParams["SHOW_AMOUNT_STORE"] == "Y" && Bitrix\Main\ModuleManager::isModuleInstalled("catalog");

foreach ($arParams['SUBSCRIBE_LIST'] as $obSubscribe):
	$arItem = $arResult['ITEMS'][(int)$obSubscribe->props['PRODUCT']['VALUE']];
	if (empty($arItem)) continue;

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

							<tr class="table-item <?=$availableClass?>">
								<td itemscope itemtype="http://schema.org/ImageObject" class="photo">
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
										<img itemprop="contentUrl" class="lazy" data-original="<?=$arItem['PICTURE_PRINT']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['PICTURE_PRINT']['ALT']?>" title="<?= $imgTitle ?>">
									</a>
								</td>
								<td class="name">
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link"><span class="text"><?=$arItem['NAME']?></span></a>
									<?
										if ($arParams['SHOW_STARS'] == 'Y') {
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
										}
									?>

									<div><?

									if ($arParams['SHOW_ARTICLE'] !== 'N' && !empty($arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])): ?>

										<span class="art">
											<?= Loc::getMessage('BITRONIC2_SUBSCRIBE_ARTICLE') ?>:
											<strong><?= $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'] ?></strong>
										</span><?

									?><? endif ?><?

									?><? if (!empty($arItem['DISPLAY_PROPERTIES'])): ?><?
										?><? foreach ($arItem['DISPLAY_PROPERTIES'] as $arProp): ?>

										<span class="art"><?= $arProp['NAME'] ?>: <strong><?= $arProp['DISPLAY_VALUE'] ?></strong></span><?

										?><? endforeach ?><?
									?><? endif ?><?

									?><? if (!$availableOnRequest): ?>

										<span class="art">
											<?= Loc::getMessage('BITRONIC2_SUBSCRIBE_PRICE') ?>:
											<strong>
												<?= ($arItem['bOffers'] && $arItem['bOffersNotEqual']) ? Loc::getMessage('BITRONIC2_SUBSCRIBE_FROM') : '' ?>

												<?= CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']) ?>

											</strong>
										</span><?

									?><? endif ?>

									</div>
								</td>
								<td class="info">
									<div class="date"><?= $obSubscribe->fields['DATE_CREATE'] ?></div>
								<? if (!empty($arParams['PROP_1']) && is_array($arParams['PROP_1'])): ?>
									<? foreach ($arParams['PROP_1'] as $propCode):
										if (empty($obSubscribe->props[$propCode])) continue;
										if (empty($obSubscribe->props[$propCode]['VALUE'])) continue;
										?>

									<div>
										<?= $obSubscribe->props[$propCode]['NAME'] ?>:
										<?= $propCode == 'PRICE'
											? CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['baseCurrency'], $obSubscribe->props[$propCode]['VALUE'], $obSubscribe->props[$propCode]['DISPLAY_VALUE'])
											: $obSubscribe->props[$propCode]['DISPLAY_VALUE'] ?>

									</div>
									<? endforeach ?>
								<? endif ?>

								</td>
								<? if (!empty($arParams['PROP_2']) && is_array($arParams['PROP_2'])): ?>

								<td class="info2">
									<? foreach ($arParams['PROP_2'] as $propCode):
										if (empty($obSubscribe->props[$propCode])) continue;
										if (empty($obSubscribe->props[$propCode]['VALUE'])) continue;
										?>

									<div>
										<span class="info2-title"><?= $obSubscribe->props[$propCode]['NAME'] ?>:</span>
										<?= $obSubscribe->props[$propCode]['DISPLAY_VALUE'] ?>

									</div>
									<? endforeach ?>

								</td>
								<? endif ?>

								<td class="actions-btn">
									<button class="btn-delete pseudolink with-icon" data-tooltip title="<?= Loc::getMessage('BITRONIC2_SUBSCRIBE_CANCEL') ?>" data-placement="bottom" data-id="<?= $obSubscribe->fields['ID'] ?>">
										<i class="flaticon-trash29"></i>
										<span class="btn-text"><?= Loc::getMessage('BITRONIC2_SUBSCRIBE_DELETE') ?></span>
									</button>
								</td>
							</tr>
<?
endforeach;
/*
<tr><td colspan="5">
<pre style='text-align:left;'><? print_r($arResult) ?></pre>;
</td></tr>
*/
