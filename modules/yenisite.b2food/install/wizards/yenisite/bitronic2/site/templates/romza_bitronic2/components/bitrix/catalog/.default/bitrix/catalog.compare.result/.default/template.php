<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

\Bitrix\Main\Localization\Loc::loadMessages(SITE_TEMPLATE_PATH . '/header.php');

// @var $moduleId
// @var $moduleCode
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';
if ($arParams['INCLUDE_MOBILE_PAGE']){
    include 'mobile_page.php';
    return;
}
?>

	<div class="compare-outer-wrapper">
		<div class="scroller">
			<div class="scroll-content">
				<table class="compare-table" id="compare-table">
	<thead>
		<tr class="compare-items">
			<th class="corner-top">
				<?
				/* TODO
				compare in category
				*/
				?>
				<a class="compare-switch<? echo (!$arResult["DIFFERENT"] ? ' active' : ''); ?>" href="<? echo str_replace($arParams['ACTION_VARIABLE'], '', $arResult['COMPARE_URL_TEMPLATE']).'DIFFERENT=N'; ?>" rel="nofollow"><?=GetMessage("BITRONIC2_CATALOG_ALL_CHARACTERISTICS")?></a>
				<a class="compare-switch<? echo ($arResult["DIFFERENT"] ? ' active' : ''); ?>" href="<? echo str_replace($arParams['ACTION_VARIABLE'], '', $arResult['COMPARE_URL_TEMPLATE']).'DIFFERENT=Y'; ?>" rel="nofollow"><?=GetMessage("BITRONIC2_CATALOG_ONLY_DIFFERENT")?></a>
				
				<div class="desc">
					<?=GetMessage('BITRONIC2_CATALOG_COMPARE_NOTE')?>
				</div>
			</th>
			<?foreach($arResult['ITEMS'] as &$arItem):
				$strMainID = $this->GetEditAreaId('compare_'.$arItem['ID']);
				$arItemIDs = array(
					'ID' => $strMainID,
					'PICT' => $strMainID.'_pict',
					'SECOND_PICT' => $strMainID.'_secondpict',
					//'STICKER_ID' => $strMainID.'_sticker',
					//'SECOND_STICKER_ID' => $strMainID.'_secondsticker',
					'BUY_LINK' => $strMainID.'_buy_link',
					'BUY_ONECLICK' => $strMainID.'_buy_oneclick',
					'BASKET_ACTIONS' => $strMainID.'_basket_actions',
					'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
					'SUBSCRIBE_LINK' => $strMainID.'_subscribe',
					'COMPARE_LINK' => $strMainID.'_compare_link',
					'FAVORITE_LINK' => $strMainID.'_favorite_link',
					'REQUEST_LINK' => $strMainID.'_request_link',

					'OLD_PRICE' => $strMainID.'_old_price',
					'PRICE' => $strMainID.'_price',
					'DSC_PERC' => $strMainID.'_dsc_perc',
					'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',
					//'PROP_DIV' => $strMainID.'_sku_tree',
					//'PROP' => $strMainID.'_prop_',
					//'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
					'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
					'AVAILABILITY' => $strMainID . '_availability',
				);
				$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
				$imgTitle = (
					!empty($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
					? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
					: $arItem['NAME']
				);
				$arItem['strMainID'] = $strMainID;
				$arItem['arItemIDs'] = $arItemIDs;
				$arItem['strObName'] = $strObName;

				$bEmptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
				$bBuyProps = ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$bEmptyProductProperties);
				?>
				<th class="compare-item-wrapper">
					<div class="compare-item" id="<?=$arItemIDs['ID']?>">
						<a class="btn-close" href="<?=$arItem['~DELETE_URL']?>" data-id="<?=$arItem['ID']?>">
							<span class="btn-text"><?=GetMessage("BITRONIC2_CATALOG_REMOVE_PRODUCT")?></span>
							<i class="flaticon-close47"></i>
						</a>
						<div itemscope itemtype="http://schema.org/ImageObject" class="photo">
							<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link">
								<img itemprop="contentUrl" class="lazy" data-original="<?=$arItem['PICTURE_PRINT']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['PICTURE_PRINT']['ALT']?>" title="<?= $imgTitle ?>">
							</a>
							<div class="stickers clearfix">
							</div><!-- /.stickers -->
						</div>
						<!-- <div class="name">
							<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link"><span class="text"><?=$arItem['NAME']?></span></a>
						</div> -->
						<div class="art-rate clearfix">
							<? if ($arParams['SHOW_ARTICLE'] == 'Y'):?>
								<?if(!empty($arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])):?>
									<span class="art"><?=$arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['NAME']?>: <strong><?=is_array($arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) ? implode(' / ', $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) : $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']?></strong></span>
								<?endif?>
							<? endif ?>
							<? if ($arParams['SHOW_STARS'] == 'Y'): ?>
								<? $APPLICATION->IncludeComponent("bitrix:iblock.vote", "stars", array(
									"IBLOCK_TYPE" => $arItem['IBLOCK_TYPE_ID'],
									"IBLOCK_ID" => $arItem['IBLOCK_ID'],
									"ELEMENT_ID" => $arItem['ID'],
									"CACHE_TYPE" => $arParams["CACHE_TYPE"],
									"CACHE_TIME" => $arParams["CACHE_TIME"],
									"MAX_VOTE" => "5",
									"VOTE_NAMES" => array("1", "2", "3", "4", "5"),
									"SET_STATUS_404" => "N",
								),
									$component, array("HIDE_ICONS" => "Y")
								); ?>
							<? endif ?>
						</div>
						<div class="prices<?=($arItem['MIN_PRICE']['DISCOUNT_VALUE'] > 0 ? '' : ' invisible')?>">
							<?if($arItem['MIN_PRICE']['DISCOUNT_DIFF'] > 0):?>
								<span class="price-old" id="<?=$arItemIDs['OLD_PRICE']?>"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['VALUE'], $arItem['MIN_PRICE']['PRINT_VALUE']);?></span>
							<?endif?>
							<span class="price" id="<?=$arItemIDs['PRICE']?>">
								<?=($arItem['bOffers']) ? GetMessage('BITRONIC2_LIST_FROM') : ''?>
								<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);?>
							</span>
						</div>
						<div class="action-buttons" id="<?=$arItemIDs['BASKET_ACTIONS']?>">
<?
							// ***************************************
							// *********** BUY WITH PROPS ************
							// ***************************************
							if ($bBuyProps):
?>
							<div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>">
<?
								if (!empty($arItem['PRODUCT_PROPERTIES_FILL']))
								{
									foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
									{
?>
								<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
<?
										if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
											unset($arItem['PRODUCT_PROPERTIES'][$propID]);
									}
								}
								$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
?>
							</div><?

							endif
							?><? 
							/* TODO 
							include '_/buttons/btn-action_to-fav.html'; //not with props
							*/?>

							<div class="btn-buy-wrap text-only <?=(!$arItem['CAN_BUY'] && !$arItem['ON_REQUEST'] && !$arItem['bOffers']) ? 'out-of-stock' : ''?>">
							<?if($arItem['bOffers'] || ($bBuyProps && !$emptyProductProperties && $arItem['CAN_BUY'])):?>
								<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="btn-action buy when-in-stock">
									<i class="flaticon-open12"></i>
									<span class="text"><?=COption::GetOptionString($moduleId, 'button_text_offers')?></span>
								</a>
							<?else:?>
								<?if($arItem['ON_REQUEST']):?>
								<button type="button" class="btn-action buy when-in-stock" id="<?=$arItemIDs['REQUEST_LINK']?>"
									data-product-id="<?=$arItem['ID']?>" data-measure-name="<?=$arItem['CATALOG_MEASURE_NAME']?>" data-toggle="modal" data-target="#modal_contact_product">
									<i class="flaticon-speech90"></i>
									<span class="text"><?=COption::GetOptionString($moduleId, 'button_text_request')?></span>
								</button>
								<?else:?>
								<button type="button" class="btn-action buy when-in-stock" id="<?=$arItemIDs['BUY_LINK']?>" data-product-id="<?=$arItem['ID']?>">
									<i class="flaticon-shopping109"></i>
									<span class="text"><?=COption::GetOptionString($moduleId, 'button_text_buy')?></span>
									<span class="text in-cart"><?=COption::GetOptionString($moduleId, 'button_text_incart')?></span>
								</button>
								<?endif?>
                                <?$availableSubscribe = $arItem['bOffers'] ? 'N' : $arItem['CATALOG_SUBSCRIBE'];
                                    $vailableItemID = $arItem['ID'];?>
                                <span class="when-out-of-stock"
                                    <? if ($availableSubscribe == 'Y'): ?>
                                        title="<?=GetMessage('BITRONIC2_PRODUCT_SUBSCRIBE')?>"
                                        data-tooltip
                                        data-product="<?= $availableItemID ?>"
                                        data-placement="bottom"
                                        data-toggle="modal"
                                        data-target="#modal_subscribe_product"
                                    <? endif ?>>
                                        <?= COption::GetOptionString($moduleId, 'button_text_na') ?>
                                    </span>
							<?endif?>
							</div>
						</div>
						<div class="drag-handle"></div>
					</div><!-- /compare-item -->
				</th>
			<?endforeach; unset($arItem);?>
		</tr>
		<tr class="footer">
			<th class="corner-footer"><?=GetMessage('BITRONIC2_CATALOG_COMPARE_PARAMS')?></th>
			<?foreach($arResult['ITEMS'] as $arItem):?>
				<td class="compare-item-name">
					<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link"><span class="text"><?=$arItem['NAME']?></span></a>
				</td>
			<?endforeach?>
		</tr>
	</thead>
	<tbody class="group-common">
		<?if ($arResult['HAS_GROUPS'] === true):?>

		<tr class="section-header">
			<th>
				<button type="button" class="section-toggle shown">
					<span class="text"><?=GetMessage('BITRONIC2_COMMON_PROPERTIES')?></span>
				</button>
			</th>
			<?=str_repeat('<td></td>', count($arResult["ITEMS"]))?> 
		</tr>
		<?endif;
		
if (!empty($arResult["SHOW_FIELDS"]))
{
	foreach ($arResult["SHOW_FIELDS"] as $code => $arProp)
	{
		$arSkipArray = array("NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE");
		
		if(in_array($code, $arSkipArray))
			continue;
		$showRow = true;
		if (!isset($arResult['FIELDS_REQUIRED'][$code]) || $arResult['DIFFERENT'])
		{
			$arCompare = array();
			foreach($arResult["ITEMS"] as &$arItem)
			{
				$arPropertyValue = $arItem["FIELDS"][$code];
				if (is_array($arPropertyValue))
				{
					sort($arPropertyValue);
					$arPropertyValue = implode(" / ", $arPropertyValue);
				}
				$arCompare[] = $arPropertyValue;
			}
			unset($arItem);
			$showRow = (count(array_unique($arCompare)) > 1);
		}
		if ($showRow)
		{
		?><tr>
				<th class="property">
					<div class="property-name"><?=GetMessage("BITRONIC2_IBLOCK_FIELD_".$code)?>
						<a class="remove-property btn-close" href="<?=$arProperty["ACTION_LINK"]?>">
							<i class="flaticon-close47"></i>
						</a>
					</div>
				</th><?
			foreach($arResult["ITEMS"] as &$arItem)
			{
			?>
				<td><?=$arItem["FIELDS"][$code];?></td>
			<?
			}
			unset($arItem);
			?>
		</tr>
		<?
		}
	}
}

if (!empty($arResult["SHOW_OFFER_FIELDS"]))
{
	foreach ($arResult["SHOW_OFFER_FIELDS"] as $code => $arProp)
	{
		$showRow = true;
		if ($arResult['DIFFERENT'])
		{
			$arCompare = array();
			foreach($arResult["ITEMS"] as &$arItem)
			{
				$Value = $arItem["OFFER_FIELDS"][$code];
				if(is_array($Value))
				{
					sort($Value);
					$Value = implode(" / ", $Value);
				}
				$arCompare[] = $Value;
			}
			unset($arItem);
			$showRow = (count(array_unique($arCompare)) > 1);
		}
		if ($showRow)
		{
		?>
		<tr>
			<th class="property">
				<div class="property-name"><?=GetMessage("BITRONIC2_IBLOCK_OFFER_FIELD_".$code)?>
					<a class="remove-property btn-close" href="<?=$arProperty["ACTION_LINK"]?>">
						<i class="flaticon-close47"></i>
					</a>
				</div>
			</th>
			<?foreach($arResult["ITEMS"] as &$arItem)
			{
			?>
			<td>
				<?=(is_array($arItem["OFFER_FIELDS"][$code])? implode("/ ", $arItem["OFFER_FIELDS"][$code]): $arItem["OFFER_FIELDS"][$code])?>
			</td>
			<?
			}
			unset($arItem);
			?>
		</tr>
		<?
		}
	}
}
?>
<?/*
<tr class="catalog-compare-price">
	<th class="property">
		<div class="property-name"><?=GetMessage('BITRONIC2_CATALOG_COMPARE_PRICE');?>
			<a class="remove-property btn-close" href="<?=$arProperty["ACTION_LINK"]?>">
				<i class="flaticon-close47"></i>
			</a>
		</div>
	</th>
	<?
	foreach ($arResult["ITEMS"] as &$arItem)
	{
		if (isset($arItem['MIN_PRICE']) && is_array($arItem['MIN_PRICE']))
		{
			?><td><? echo $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']; ?></td><?
		}
		else
		{
			?><td>&nbsp;</td><?
		}
	}
	unset($arItem);
	?>
</tr>*/?>
<?
if (!empty($arResult["SHOW_PROPERTIES"]))
{
	foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty)
	{
		if ($arResult['HAS_GROUPS'] === true && !empty($arProperty['GROUP_NAME'])) {
			?>
			</tbody>
			<tbody class="group-<?=$code?>">
				<tr class="section-header">
					<th>
						<button type="button" class="section-toggle shown">
							<span class="text"><?=$arProperty['GROUP_NAME']?></span>
						</button>
					</th>
					<?=str_repeat('<td></td>', count($arResult["ITEMS"]))?> 
				</tr>
			<?
			continue;
		}
		?>
			<tr data-class="property-<?=$code?>" class="property-<?=$code?>">
				<th class="property">
					<div class="property-name"><?=$arProperty["NAME"]?>
						<a class="remove-property btn-close" href="<?=$arProperty["ACTION_LINK"]?>">
							<i class="flaticon-close47"></i>
						</a>
					</div>
				</th>
				<?foreach($arResult["ITEMS"] as &$arItem)
				{
					?>
					<td>
						<?=(is_array($arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
					</td>
				<?
				}
				unset($arItem);
				?>
			</tr>
		<?
	}
}

if (!empty($arResult["SHOW_OFFER_PROPERTIES"]))
{
	foreach($arResult["SHOW_OFFER_PROPERTIES"] as $code=>$arProperty)
	{
		$showRow = true;
		if ($arResult['DIFFERENT'])
		{
			$arCompare = array();
			foreach($arResult["ITEMS"] as &$arItem)
			{
				$arPropertyValue = $arItem["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
				if(is_array($arPropertyValue))
				{
					sort($arPropertyValue);
					$arPropertyValue = implode(" / ", $arPropertyValue);
				}
				$arCompare[] = $arPropertyValue;
			}
			unset($arItem);
			$showRow = (count(array_unique($arCompare)) > 1);
		}
		if ($showRow)
		{
		?>
		<tr>
			<th class="property">
				<div class="property-name"><?=$arProperty["NAME"]?>
					<a class="remove-property btn-close" href="<?=$arProperty["ACTION_LINK"]?>">
						<i class="flaticon-close47"></i>
					</a>
				</div>
			</th>
			<?foreach($arResult["ITEMS"] as &$arItem)
			{
			?>
			<td>
				<?=(is_array($arItem["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arItem["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arItem["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
			</td>
			<?
			}
			unset($arItem);
			?>
		</tr>
		<?
		}
	}
}
	?>
	</tbody>
</table><!-- /compare-table -->
			</div>
			<div class="scroller__track scroller__track_h">
				<div class="scroller__bar scroller__bar_h"></div>
			</div>
		</div>
	</div>
	<div class="deleted-properties">
		<?if(!empty($arResult['DELETED_PROPERTIES'])):?>
			<span class="text"><?=GetMessage('BITRONIC2_CATALOG_DELETED_PROPS')?></span>
			<?foreach($arResult['DELETED_PROPERTIES'] as $arProperty):?>
				<span class="deleted-property" data-tooltip data-placement="left" title="<?=GetMessage('BITRONIC2_CATALOG_ADD_PROP')?>">
					<span class="text"><a href="<?=$arProperty['ACTION_LINK']?>"><?=$arProperty['NAME']?></a></span>
				</span>
			<?endforeach?>
		<?endif?>
	</div>
	<?
	/* TODO
	<div class="container">
		<div class="best-choice">
			.............................
		</div>
	</div>
	*/
	?>

<script type="text/javascript">
	$(window).on('b2ready', function(){
	<?if(isset($_GET['action']) && !empty($_GET['action']) && ($_GET['action'] == 'DELETE_FROM_COMPARE_RESULT' || $_GET['action'] == 'DELETE_FROM_COMPARE_LIST')):?>
		// refresh small compare list (in header)
		RZB2.ajax.Compare.Refresh();
	<?endif?>
	// var CatalogCompareObj = new BX.Iblock.Catalog.CompareClass("compare-table");
	<?foreach ($arResult['ITEMS'] as $arItem) {
		$strMainID = $arItem['strMainID'];
		$arItemIDs = $arItem['arItemIDs'];
		$strObName = $arItem['strObName'];
		include 'js_params.php';
	}?>
	});
</script>

<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
?>