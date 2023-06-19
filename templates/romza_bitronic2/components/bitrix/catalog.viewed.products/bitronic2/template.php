<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);

// @var $moduleId
// @var $moduleCode
include $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/include/debug_info.php';
include $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/header.php';

if (empty($arResult['ITEMS']))
{
	return;
}

//$arJsCache = CRZBitronic2CatalogUtils::getJSCache($component); - not need because this component not cached template
$bHoverMode = $arParams['HOVER-MODE'] == 'detailed-expand';
?>
<div class="viewed-products<?= $bHoverMode ? ' __expanded' : '' ?> <?=$arParams['ORDER_VIEWED_PRODUCTS'] ? 'drag-section sPrViewedProducts' : ''?>" <?=$arParams['ORDER_VIEWED_PRODUCTS'] ? "data-order=\"{$arParams['ORDER_VIEWED_PRODUCTS']}\"" : ''?> id="viewed-products-user">
	<header><?=htmlspecialcharsBx($arParams['HEADER_TEXT'])?></header>
	<div class="scroll-slider-wrap shown">
		<div class="ctrl-arrow-wrap prev">
			<button type="button" class="ctrl-arrow prev">
				<i class="flaticon-arrow133"></i>
			</button>
		</div>
		<div class="ctrl-arrow-wrap next">
			<button type="button" class="ctrl-arrow next">
				<i class="flaticon-right20"></i>
			</button>
		</div>
		<div class="sly-scroll horizontal">
			<div class="sly-bar"></div>
		</div>
		<div class="scroll-slider frame">
			<div class="slider-content slidee catalog"><?
				foreach($arResult['ITEMS'] as $arItem):
					$strMainID = $this->GetEditAreaId($arItem['ID'].rand());
					$arItemIDs = array(
						'ID' => $strMainID,
						'BUY_LINK' => $strMainID.'_buy_link',
						'BUY_ONECLICK' => $strMainID.'_buy_oneclick',
						'BASKET_ACTIONS' => $strMainID.'_basket_actions',
						'COMPARE_LINK' => $strMainID.'_compare_link',
						'FAVORITE_LINK' => $strMainID.'_favorite_link',
						'REQUEST_LINK' => $strMainID.'_request_link',
						'PRICE' => $strMainID.'_price',
						'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
					);
					$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID).'_viewed';
					$imgTitle = (
						!empty($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'])
						? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
						: $arItem['NAME']
					);
					$bShowOneClick = ($arParams["DISPLAY_ONECLICK"] && !$arItem['bOffers']);

					$bEmptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
					$bBuyProps = ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$bEmptyProductProperties);
				?><div class="slider-item<?=(!$arItem['CAN_BUY'] && !$arItem['ON_REQUEST'] ? ' out-of-stock' : '')?> catalog-item" id="<?=$arItemIDs['ID']?>">
					<a itemscope itemtype="http://schema.org/ImageObject" class="photo" href="<?=$arItem['DETAIL_PAGE_URL']?>" title="<?= $imgTitle ?>">
						<span data-picture data-alt="<?=$arItem['PICTURE_PRINT']['ALT']?>">
							<span data-itemprop="contentUrl" data-title="<?= $imgTitle ?>" data-src="<?=$arItem['PICTURE_PRINT']['SRC']?>"></span>
							<!-- Fallback content for non-JS browsers. Same img src as the initial, unqualified source element. -->
							<noscript>
									<img itemprop="contentUrl" title="<?= $imgTitle ?>" class="lazy" data-original="<?=$arItem['PICTURE_PRINT']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['PICTURE_PRINT']['ALT']?>">
							</noscript>
						</span>	
					</a>
					<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="name link bx_rcm_view_link" data-product-id="<?=$arItem['ID']?>"><span class="text"><?=$arItem['NAME']?></span></a>
					<div class="price-wrap">
					<?if($arItem['ON_REQUEST']):?>
						<span class="price" id="<?=$arItemIDs['PRICE']?>"><?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_ON_REQUEST')?></span>
					<?else:?>
						<?if($arItem['MIN_PRICE']['DISCOUNT_DIFF'] > 0 && $arParams['SHOW_OLD_PRICE'] == 'Y'):?>
							<span class="price-old"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['VALUE'], $arItem['MIN_PRICE']['PRINT_VALUE']);?></span>
						<?endif?>
						<span class="price" id="<?=$arItemIDs['PRICE']?>">
							<?=($arItem['bOffers'] && $arItem['bOffersNotEqual']) ? GetMessage('BITRONIC2_BIGDATA_FROM') : ''?>
							<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);?>
						</span>
					<?endif?>
					</div>
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
					</div>
<? endif ?>
					<? if ($bHoverMode):?>
						<div class="description full-view">
					<? endif ?>
						<div class="action-buttons" id="<?=$arItemIDs['BASKET_ACTIONS']?>">
							<?if ($arParams['DISPLAY_FAVORITE'] && !$arItem['bOffers']):?>
								<button
									type="button"
									class="btn-action favorite"
									data-favorite-id="<?=$arItem['ID']?>"
									data-tooltip title="<?=GetMessage('BITRONIC2_BIGDATA_ADD_TO_FAVORITE')?>"
									id="<?=$arItemIDs['FAVORITE_LINK']?>">
									<i class="flaticon-heart3"></i>
								</button>
							<?endif?>
							<?if ($arParams['DISPLAY_COMPARE_SOLUTION']/* && !$arItem['bOffers']*/):?>
								<button
									type="button"
									class="btn-action compare"
									data-compare-id="<?=$arItem['ID']?>"
									data-tooltip title="<?=GetMessage('BITRONIC2_BIGDATA_ADD_TO_COMPARE')?>"
									id="<?=$arItemIDs['COMPARE_LINK']?>">
									<i class="flaticon-balance3"></i>
								</button>
							<?endif?>
							<?if($bShowOneClick && $arItem['CAN_BUY'] && (!$bBuyProps || $emptyProductProperties)):?>
								<button
									type="button"
									class="btn-action one-click-buy"
									data-toggle="modal"
									data-target="#modal_quick-buy"
									data-id="<?=$arItem['ID']?>"
									data-tooltip title="<?=GetMessage('BITRONIC2_BIGDATA_ONECLICK')?>"
									id="<?=$arItemIDs['BUY_ONECLICK']?>">
									<i class="flaticon-shopping220"></i>
								</button>
							<?endif?>
							<div class="btn-buy-wrap text-only">
								<?if($arItem['bOffers'] || ($bBuyProps && !$emptyProductProperties && $arItem['CAN_BUY'])):?>
									<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="btn-action buy when-in-stock bx_rcm_view_link" data-product-id="<?=$arItem['ID']?>">
										<i class="flaticon-shopping109"></i>
										<span class="text"><?=COption::GetOptionString($moduleId, 'button_text_offers')?></span>
									</a>
								<?else:?>
									<?if($arItem['CAN_BUY']):?>
										<button type="button" class="btn-action buy when-in-stock" id=<?=$arItemIDs['BUY_LINK']?> data-product-id="<?=$arItem['ID']?>">
											<i class="flaticon-shopping109"></i>
											<span class="text"><?=COption::GetOptionString($moduleId, 'button_text_buy')?></span>
											<span class="text in-cart"><?=COption::GetOptionString($moduleId, 'button_text_incart')?></span>
										</button>
									<?elseif($arItem['ON_REQUEST']):?>
										<button type="button" class="btn-action buy when-in-stock" id="<?=$arItemIDs['REQUEST_LINK']?>"
											data-product-id="<?=$arItem['ID']?>" data-measure-name="<?=$arItem['CATALOG_MEASURE_NAME']?>" data-toggle="modal" data-target="#modal_contact_product">
											<i class="flaticon-speech90"></i>
											<span class="text"><?=COption::GetOptionString($moduleId, 'button_text_request')?></span>
										</button>
									<?else:?>
										<span class="when-out-of-stock"><?=COption::GetOptionString($moduleId, 'button_text_na')?></span>
									<?endif?>
								<?endif?>
							</div>
						</div>
					<? if ($bHoverMode):?>
						</div>
					<? endif ?>
					<?
			$arJSParams = array(
				'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
				'SHOW_QUANTITY' => ($arParams['USE_PRODUCT_QUANTITY'] == 'Y'),
				'SHOW_ADD_BASKET_BTN' => false,
				'SHOW_BUY_BTN' => false,
				'SHOW_ABSENT' => false,
				'SHOW_SKU_PROPS' => false,
				'SECOND_PICT' => $arItem['SECOND_PICT'],
				'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
				'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
				'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
				'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
				'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE_SOLUTION'],
				'DISPLAY_FAVORITE' => $arParams['DISPLAY_FAVORITE'],
				'REQUEST_URI' => $arItem['DETAIL_PAGE_URL'],
				'SCRIPT_NAME' => BX_ROOT.'/urlrewrite.php',
				'DEFAULT_PICTURE' => array(
					'PICTURE' => $arItem['PRODUCT_PREVIEW'],
					'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
				),
				'VISUAL' => array(
					'ID' => $arItemIDs['ID'],
					'PRICE_ID' => $arItemIDs['PRICE'],
					'BUY_ID' => $arItemIDs['BUY_LINK'],
					'BUY_ONECLICK' => $arItemIDs['BUY_ONECLICK'],
					'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
					'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
					'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV'],
					'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK'],
					'FAVORITE_ID' => $arItemIDs['FAVORITE_LINK'],
				),
				'BASKET' => array(
					'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
					'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
					'EMPTY_PROPS' => $bEmptyProductProperties,
					'BASKET_URL' => $arParams['BASKET_URL'],
					'ADD_URL_TEMPLATE' => $arResult['ADD_URL_TEMPLATE'],
					'BUY_URL_TEMPLATE' => $arResult['BUY_URL_TEMPLATE']
				),
				'PRODUCT' => array(
					'ID' => $arItem['ID'],
					'IBLOCK_ID' => $arItem['IBLOCK_ID'],
					'NAME' => $productTitle,
					'PICT' => ('Y' == $arItem['SECOND_PICT'] ? $arItem['PREVIEW_PICTURE_SECOND'] : $arItem['PREVIEW_PICTURE']),
					'CAN_BUY' => $arItem["CAN_BUY"],
					'BASIS_PRICE' => $arItem['MIN_BASIS_PRICE']
				),
				'OFFERS' => array(),
				'OFFER_SELECTED' => 0,
				'TREE_PROPS' => array(),
				'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
			);
			if ($arParams['DISPLAY_COMPARE_SOLUTION'])
			{
				$arJSParams['COMPARE'] = array(
					'COMPARE_URL_TEMPLATE' => $arResult['COMPARE_URL_TEMPLATE'],
					'COMPARE_URL_TEMPLATE_DEL' => $arResult['COMPARE_URL_TEMPLATE_DEL'],
					'COMPARE_PATH' => $arParams['COMPARE_PATH']
				);
			}
			if ($arParams['DISPLAY_FAVORITE'])
			{
				$arJSParams['FAVORITE'] = array(
					'FAVORITE_URL_TEMPLATE' => $arResult['FAVORITE_URL_TEMPLATE'],
					'FAVORITE_URL_TEMPLATE_DEL' => $arResult['FAVORITE_URL_TEMPLATE_DEL'],
					'FAVORITE_PATH' => $arParams['FAVORITE_PATH']
				);
			}
			?>

			<script type="text/javascript">
				var <?=$strObName?> = new JCCatalogItem(<?=CUtil::PhpToJSObject($arJSParams, false, true)?>);
			</script>
				</div><?
				endforeach;
			?></div><!-- /.slider-content -->
		</div><!-- /.scroll-slider -->	
	</div>
</div>
<?
// echo "<pre style='text-align:left;'>";print_r($arResult['ITEMS']);echo "</pre>";