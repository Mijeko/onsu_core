<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

// @var $moduleId
// @var $moduleCode
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/header.php';

if(empty($arResult['ITEMS']))
	return;

$arJsCache = CRZBitronic2CatalogUtils::getJSCache($component);
	
$strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
$strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
$arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
?>

<div class="cool-slider drag-section sCoolSlider" data-order="<?=$arParams['SLIDER_ORDER']?>" id="cool-slider" data-coolslider-enabled="true"
	 data-coolslider-names-enabled="<?= ($arParams['DISPLAY_NAMES'] != 'Y' ? 'false' : 'true') ?>">
	<div class="container">
		<div class="slides">
			<?foreach($arResult['ITEMS'] as $arItem):
				$this->AddEditAction('cool-slider-'.$arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
				$this->AddDeleteAction('cool-slider-'.$arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
				$strMainID = $this->GetEditAreaId('cool-slider-'.$arItem['ID']);
				$arItemIDs = array(
					'ID' => $strMainID,
					'PICT' => $strMainID.'_pict',
					'SECOND_PICT' => $strMainID.'_secondpict',
					'STICKER_ID' => $strMainID.'_sticker',
					'SECOND_STICKER_ID' => $strMainID.'_secondsticker',
					'QUANTITY' => $strMainID.'_quantity',
					'QUANTITY_DOWN' => $strMainID.'_quant_down',
					'QUANTITY_UP' => $strMainID.'_quant_up',
					'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
					'BUY_LINK' => $strMainID.'_buy_link',
					'BASKET_ACTIONS' => $strMainID.'_basket_actions',
					'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
					'SUBSCRIBE_LINK' => $strMainID.'_subscribe',
					'COMPARE_LINK' => $strMainID.'_compare_link',

					'PRICE' => $strMainID.'_price',
					'PRICE_DIV' => $strMainID.'_price_wrap',
					'DSC_PERC' => $strMainID.'_dsc_perc',
					'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',
					'PROP_DIV' => $strMainID.'_sku_tree',
					'PROP' => $strMainID.'_prop_',
					'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
					'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
				);
				$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

				$productTitle = (
					isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])&& $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
					? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
					: $arItem['NAME']
				);
				$imgTitle = (
					!empty($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'])
					? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
					: $arItem['NAME']
				);

				$bEmptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
				$bBuyProps = ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$bEmptyProductProperties);
				?>
				<div class="slide <?=($arItem['bFirst']) ? 'active' : ''?>" id="<?=$arItemIDs['ID']?>">
					<a itemscope itemtype="http://schema.org/ImageObject" href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="cool-img" title="<?= $imgTitle ?>">
						<span data-picture data-alt="<?=$arItem['PICTURE_PRINT_BIG']['ALT']?>">
							<span data-itemprop="contentUrl" data-title="<?= $imgTitle ?>" data-src="<?=$arItem['PICTURE_PRINT_BIG']['SRC']?>"></span>
							<span data-src="" data-media="(max-width: 991px)"></span>
							
							<!-- Fallback content for non-JS browsers. Same img src as the initial, unqualified source element. -->
							<noscript>
									<img itemprop="contentUrl" title="<?= $imgTitle ?>" class="lazy" data-original="<?=$arItem['PICTURE_PRINT_BIG']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['PICTURE_PRINT_BIG']['ALT']?>">
							</noscript>
						</span>
					</a>
					<a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="header">
						<span class="link-bd link-black"><?= $productTitle ?></span>
					</a>
					<? if ($arParams['SHOW_STICKERS'] == 'Y'): ?>
					<?$APPLICATION->IncludeComponent("yenisite:stickers", "cool-slider", array(
						"ELEMENT" => $arItem,
						"STICKER_NEW" => $arParams['STICKER_NEW'],
						"STICKER_HIT" => $arParams['STICKER_HIT'],
						"TAB_PROPERTY_NEW" => $arParams['TAB_PROPERTY_NEW'],
						"TAB_PROPERTY_HIT" => $arParams['TAB_PROPERTY_HIT'],
						"TAB_PROPERTY_SALE" => $arParams['TAB_PROPERTY_SALE'],
						"TAB_PROPERTY_BESTSELLER" => $arParams['TAB_PROPERTY_BESTSELLER'],
						"MAIN_SP_ON_AUTO_NEW" => $arParams['MAIN_SP_ON_AUTO_NEW'],
						"SHOW_DISCOUNT_PERCENT" => $arParams['SHOW_DISCOUNT_PERCENT'],
						"CUSTOM_STICKERS" => $arItem['PROPERTIES'][iRZProp::STICKERS]
						),
						$component
					);?>
					<? endif ?>
					<ul class="techdata">
						<?foreach($arItem['DISPLAY_PROPERTIES'] as $arProp):?>
							<li><span class="text">
                                    <?if (empty($arProp['DISPLAY_VALUE'])):?>
                                        <?=(is_array($arProp['VALUE']) ? implode(' / ',$arProp['VALUE']) : $arProp['VALUE'])?>
                                    <?else:?>
                                        <?=$arProp['DISPLAY_VALUE']?>
                                    <?endif?>
                                </span>
                            </li>
						<?endforeach?>
					</ul>
					<div class="desc"><?=$arItem['PREVIEW_TEXT']?></div>
					<div class="prices-wrap">
						<span id="<?=$arItemIDs['PRICE_DIV']?>"><?
							$frame = $this->createFrame($arItemIDs['PRICE_DIV'], false)->begin(CRZBitronic2Composite::insertCompositLoader());
							if (!$arItem['ON_REQUEST'] && $arItem['MIN_PRICE']['DISCOUNT_DIFF'] > 0):
								?>

							<span class="price-old"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['VALUE'], $arItem['MIN_PRICE']['PRINT_VALUE']);?></span><?

							endif;
							$frame->end();
							?>

						</span>
						<span class="price" id="<?=$arItemIDs['PRICE']?>">
							<? $frame = $this->createFrame($arItemIDs['PRICE'], false)->begin(CRZBitronic2Composite::insertCompositLoader()) ?>
							<? if($arItem['ON_REQUEST']): ?>
								<?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_ON_REQUEST')?>
							<? else: ?>
								<?=($arItem['bOffers'] && $arItem['bOffersNotEqual']) ? GetMessage('BITRONIC2_COOL_SLIDER_FROM') : ''?>
								<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);?>
							<? endif ?>
							<? $frame->end() ?>
						</span>
					</div>
					<div class="btn-buy-wrap" id="<?=$arItemIDs['BASKET_ACTIONS']?>">
						<?$frame = $this->createFrame($arItemIDs['BASKET_ACTIONS'], false)->begin(CRZBitronic2Composite::insertCompositLoader())?><?
						// ***************************************
						// *********** BUY WITH PROPS ************
						// ***************************************
						if ($bBuyProps):?>

						<div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>"><?

							if (!empty($arItem['PRODUCT_PROPERTIES_FILL']))
							{
								foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
								{
									?>

							<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>"><?

									if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
										unset($arItem['PRODUCT_PROPERTIES'][$propID]);
								}
							}
							$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
							?>

						</div><?
						
						endif ?>

						<?if($arItem['bOffers'] || ($bBuyProps && !$emptyProductProperties && $arItem['CAN_BUY'])):?>
							<form action="<?=$arItem['DETAIL_PAGE_URL']?>">
								<button type="submit" class="btn-buy">
									<span class="text"><?=COption::GetOptionString($moduleId, 'button_text_offers')?></span>
								</button>
							</form>
						<?else:?>
							<?if($arItem['CAN_BUY']):?>
								<button type="button" class="btn-buy buy" id="<?=$arItemIDs['BUY_LINK']?>" data-product-id="<?=$arItem['ID']?>">
									<span class="text"><?=GetMessage('BITRONIC2_COOL_SLIDER_ADD_TO_BASKET')?></span>
									<span class="text in-cart"><?=COption::GetOptionString($moduleId, 'button_text_incart')?></span>
								</button>
							<?elseif($arItem['ON_REQUEST']):?>
								<button type="button" class="btn-buy request" data-product-id="<?=$arItem['ID']?>" data-measure-name="<?=$arItem['CATALOG_MEASURE_NAME']?>"
									data-toggle="modal" data-target="#modal_contact_product">
									<span class="text request"><?=COption::GetOptionString($moduleId, 'button_text_request')?></span>
								</button>
							<?endif?>
						<?endif?>
						<? $frame->end() ?>
					</div>
				<?
				$arJSParams = array(
					'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
					'SHOW_QUANTITY' => false,
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
					'DEFAULT_PICTURE' => array(
						'PICTURE' => $arItem['PRODUCT_PREVIEW'],
						'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
					),
					'VISUAL' => array(
						'ID' => $arItemIDs['ID'],
						'PICT_ID' => $arItemIDs['PICT'],
						'SECOND_PICT_ID' => $arItemIDs['SECOND_PICT'],
						'QUANTITY_ID' => $arItemIDs['QUANTITY'],
						'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
						'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
						'QUANTITY_MEASURE' => $arItemIDs['QUANTITY_MEASURE'],
						'PRICE_ID' => $arItemIDs['PRICE'],
						'TREE_ID' => $arItemIDs['PROP_DIV'],
						'TREE_ITEM_ID' => $arItemIDs['PROP'],
						'BUY_ID' => $arItemIDs['BUY_LINK'],
						'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
						'DSC_PERC' => $arItemIDs['DSC_PERC'],
						'SECOND_DSC_PERC' => $arItemIDs['SECOND_DSC_PERC'],
						'DISPLAY_PROP_DIV' => $arItemIDs['DISPLAY_PROP_DIV'],
						'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
						'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV'],
						'NOT_AVAILABLE_MESS' => $arItemIDs['NOT_AVAILABLE_MESS'],
						'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK']
					),
					'BASKET' => array(
						'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
						'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
						'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
						'EMPTY_PROPS' => $bEmptyProductProperties,
						'SKU_PROPS' => $arItem['OFFERS_PROP_CODES'],
						'BASKET_URL' => $arParams['BASKET_URL'],
						'ADD_URL_TEMPLATE' => $arResult['ADD_URL_TEMPLATE'],
						'BUY_URL_TEMPLATE' => $arResult['BUY_URL_TEMPLATE']
					),
					'PRODUCT' => array(
						'ID' => $arItem['ID'],
						'IBLOCK_ID' => $arItem['IBLOCK_ID'],
						'NAME' => $productTitle,
						'CAN_BUY' => $arItem['CAN_BUY'],
					),
					'OFFERS' => array(),
					'OFFER_SELECTED' => 0,
					'TREE_PROPS' => array(),
					'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
				);
				$jsString = ($jsString ?: '') . 'var ' . $strObName . ' = new JCCatalogItem('. CUtil::PhpToJSObject($arJSParams, false, true) .');';
				?>

				</div><!-- /.slide -->
			<?endforeach?>

		</div><!-- /.slides -->
		<div class="slider-controls-wrap">
			<div class="slider-controls">
			<?if(count($arResult['ITEMS']) > 1):?>
				<div class="scroller__track scroller__track_h">
					<div class="scroller__bar scroller__bar_h"></div>
				</div>
				<div class="track">
					<?foreach($arResult['ITEMS'] as $arItem):?>
						<div class="ctrl-item <?=($arItem['bFirst']) ? 'active' : ''?>">
							<span class="bar-wrap"><span class="bar"></span></span>
							<i class="dot"></i>
							<div class="content" data-tooltip data-container="body" title="<?=$arItem['NAME']?>">
								<div itemscope itemtype="http://schema.org/ImageObject" class="img-wrap">
									<span data-picture data-alt="<?=$arItem['PICTURE_PRINT_SMALL']['ALT']?>">
										<span  data-itemprop="contentUrl" data-title="<?=$arItem['NAME']?>" data-src="<?=$arItem['PICTURE_PRINT_SMALL']['SRC']?>"></span>
										<span data-src="" data-media="(max-width: 991px)"></span>
										
										<!-- Fallback content for non-JS browsers. Same img src as the initial, unqualified source element. -->
										<noscript>
												<img itemprop="contentUrl" title="<?=$arItem['NAME']?>" class="lazy" data-original="<?=$arItem['PICTURE_PRINT_SMALL']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['PICTURE_PRINT_SMALL']['ALT']?>">
										</noscript>
									</span>
								</div>
								<div class="name"><?=$arItem['NAME']?></div>
							</div>
						</div>
					<?endforeach?>
				</div>
				
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
			<?endif?>
			</div>
		</div>
	</div><!-- /.container -->
</div><!-- /.cool-slider -->

<?
$frame = $this->createFrame()->begin('');
if ($arJsCache['file']):
	$bytes = fwrite($arJsCache['file'], $jsString);
	if ($bytes === false || $bytes != mb_strlen($jsString, 'windows-1251')) {
		fclose($arJsCache['file']);
		$arJsCache['file'] = false;
	}
endif;
if(!$arJsCache['file']):?>
<script type="text/javascript">
	<?=$jsString?>
</script>
<?endif;
$frame->end();

if ($arJsCache['file']) {
	$templateData['jsFile'] = $arJsCache['path'].'/'.$arJsCache['idJS'];
	fclose($arJsCache['file']);
}