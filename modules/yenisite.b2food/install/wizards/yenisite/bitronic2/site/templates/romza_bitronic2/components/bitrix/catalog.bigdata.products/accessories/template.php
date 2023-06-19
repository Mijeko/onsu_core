<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

// @var $moduleId
// @var $moduleCode
include $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/include/debug_info.php';
include $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/header.php';

\Bitrix\Main\Localization\Loc::loadMessages(SITE_TEMPLATE_PATH . '/header.php');

$injectId = 'bigdata_accessories_products_'.rand();
$bHoverMode = $arParams['HOVER-MODE'] == 'detailed-expand';

// ajax send request
if (isset($arResult['REQUEST_ITEMS']))
{
	CJSCore::Init(array('ajax'));

	// component parameters
	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedParameters = $signer->sign(
		base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
		'bx.bd.products.recommendation'
	);
	$signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'], 'bx.bd.products.recommendation');

	?>
	<div itemprop="isRelatedTo" itemscope itemtype="https://schema.org/Product" class="accessories wow fadeIn" id="<?=$injectId?>"></div>

	<script type="application/javascript">
		BX.ready(function(){

			var params = <?=CUtil::PhpToJSObject($arResult['RCM_PARAMS'])?>;
			var url = 'https://analytics.bitrix.info/crecoms/v1_0/recoms.php';
			var data = BX.ajax.prepareData(params);

			if (data)
			{
				url += (url.indexOf('?') !== -1 ? "&" : "?") + data;
				data = '';
			}
			var <?=$injectId?> = new RZB2.ajax.BigData;
			<?=$injectId?>.containerId = '<?=$injectId?>';
			<?=$injectId?>.parameters = '<?=CUtil::JSEscape($signedParameters)?>';
			<?=$injectId?>.template = '<?=CUtil::JSEscape($signedTemplate)?>';
			BX.cookie_prefix = '<?=CUtil::JSEscape(COption::GetOptionString("main", "cookie_name", "BITRIX_SM"))?>';
			BX.current_server_time = '<?=time()?>';
			
			BX.ajax({
				'method': 'GET',
				'dataType': 'json',
				'url': url,
				'timeout': 3,
				'onsuccess': BX.delegate(<?=$injectId?>.SendRequest, <?=$injectId?>),
				'onfailure': BX.delegate(<?=$injectId?>.SendRequest, <?=$injectId?>),
			});
		});
	</script>

	<?
	return;
}

if (empty($arResult['ITEMS']))
{
	return;
}
$header = $arParams['HEADER_TEXT'] ?: GetMessage('BITRONIC2_ACCESORIES_TITLE');
?>
<header>
	<i class="flaticon-text133"></i>
	<span class="text"><?=htmlspecialcharsbx($header)?>:</span>
</header>
<div id="<?=$injectId?>_items" class="accessories-list">
	<?foreach($arResult['ITEMS'] as $arItem):
		$strMainID = $this->GetEditAreaId($arItem['ID'].rand());
		$arItemIDs = array(
			'ID' => $strMainID,
			'BUY_LINK' => $strMainID.'_buy_link',
			'BUY_LINK_2' => $strMainID.'_buy_link_alt',
			'BUY_ONECLICK' => $strMainID.'_buy_oneclick',
			'BASKET_ACTIONS' => $strMainID.'_basket_actions',
			'COMPARE_LINK' => $strMainID.'_compare_link',
			'FAVORITE_LINK' => $strMainID.'_favorite_link',
			'REQUEST_LINK' => $strMainID.'_request_link',
			'PRICE' => $strMainID.'_price',
			
			'BIG_DATA_CONTAINER' => $injectId.'_items',
			'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
		);
		$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
		$imgTitle = (
			!empty($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'])
			? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
			: $arItem['NAME']
		);
		$bShowOneClick = ($arParams["DISPLAY_ONECLICK"] && !$arItem['bOffers']);
        $bShowBlocksIfEmptySKU = ($arItem['bOffers'] && !empty($arItem['OFFERS'])) || !$arItem['bOffers'];
        $arParams['DISPLAY_FAVORITE'] = !$bShowBlocksIfEmptySKU ? $bShowBlocksIfEmptySKU : $arParams['DISPLAY_FAVORITE'];
		$bEmptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
		$bBuyProps = ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$bEmptyProductProperties);
	?>
	<!-- add class .out-of-stock to switch buy-btn visibility -->
	<div class="accessory<?= $bHoverMode ?' __hide_buttons':''?>" id="<?=$arItemIDs['ID']?>">
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
		<?if(!$arItem['bOffers'] && $arItem['CAN_BUY'] && (!$bBuyProps || $emptyProductProperties)):?>
			<button type="button"  id=<?=$arItemIDs['BUY_LINK_2']?> class="add-to-order" title="<?=GetMessage('BITRONIC2_ACCESORIES_ADD_TO_ORDER')?>" data-tooltip>
				<span class="text">+</span>
			</button>
		<?endif?>
		
		<div itemscope itemtype="http://schema.org/ImageObject" class="photo">
			<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
			<img itemprop="contentUrl" class="lazy" data-original="<?=$arItem['PICTURE_PRINT']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['PICTURE_PRINT']['ALT']?>" title="<?= $imgTitle ?>">
			</a>
		</div>
		<div class="main-data">
			<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
				<span class="text"><?=$arItem['NAME']?></span>
			</a>
			<? if ($arParams['SHOW_STARS'] == 'Y'):?>
				<div class="rating">
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
				</div><!-- /.rating -->
			<? endif ?>
			<div class="price-wrap clearfix">
                <?if ($bShowBlocksIfEmptySKU):?>
                    <?if($arItem['ON_REQUEST']):?>
                        <span class="price" id="<?=$arItemIDs['PRICE']?>"><?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_ON_REQUEST')?></span>
                    <?else:?>
                        <?if($arItem['MIN_PRICE']['DISCOUNT_DIFF'] > 0 && $arParams['SHOW_OLD_PRICE'] == 'Y'):?>
                            <span class="price-old"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['VALUE'], $arItem['MIN_PRICE']['PRINT_VALUE']);?></span>
                        <?endif?>
                        <span class="price" id="<?=$arItemIDs['PRICE']?>">
                            <?=($arItem['bOffers'] && $arItem['bOffersNotEqual']) ? GetMessage('BITRONIC2_ACCESORIES_FROM') : ''?>
                            <?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);?>
                        </span>
                    <?endif?>
                <?endif?>
			</div>
		</div><!-- /.main-data -->
		<div class="action-buttons" id="<?=$arItemIDs['BASKET_ACTIONS']?>">
			<?if ($arParams['DISPLAY_FAVORITE'] && !$arItem['bOffers']):?>
				<button 
					type="button" 
					class="btn-action favorite" 
					data-favorite-id="<?=$arItem['ID']?>" 
					data-tooltip title="<?=GetMessage('BITRONIC2_ACCESORIES_ADD_TO_FAVORITE')?>"
					id="<?=$arItemIDs['FAVORITE_LINK']?>">
					<i class="flaticon-heart3"></i>
				</button>
			<?endif?>
			<?if ($arParams['DISPLAY_COMPARE_SOLUTION']/* && !$arItem['bOffers']*/):?>
				<button 
					type="button" 
					class="btn-action compare" 
					data-compare-id="<?=$arItem['ID']?>" 
					data-tooltip title="<?=GetMessage('BITRONIC2_ACCESORIES_ADD_TO_COMPARE')?>" 
					id="<?=$arItemIDs['COMPARE_LINK']?>">
						<i class="flaticon-balance3"></i>
				</button>
			<?endif?>
			<div class="btn-buy-wrap <?=($arItem['bOffers'] || ($bBuyProps && !$emptyProductProperties)) ? 'text-only' : 'icon-only'?> "<?if($arItem['ON_REQUEST']):?>
				data-tooltip title="<?=COption::GetOptionString($moduleId, 'button_text_request')?>"<?endif?>>
				<?if($arItem['bOffers'] || ($bBuyProps && !$emptyProductProperties && $arItem['CAN_BUY'])):?>
					<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="btn-action buy when-in-stock">
						<i class="flaticon-shopping109"></i>
						<span class="text"><?=COption::GetOptionString($moduleId, 'button_text_offers')?></span>
					</a>
				<?else:?>
					<?if($arItem['CAN_BUY']):?>
						<button type="button" class="btn-action buy when-in-stock" id="<?=$arItemIDs['BUY_LINK']?>" data-product-id="<?=$arItem['ID']?>">
							<i class="flaticon-shopping109"></i>
							<span class="text"><?=COption::GetOptionString($moduleId, 'button_text_buy')?></span>
							<span class="text in-cart"><?=COption::GetOptionString($moduleId, 'button_text_incart')?></span>
                            <input type="hidden" name="bigdata_recommendation_id" value="<?=$arItem['RID'] ? : $arItem['ID']?>">
						</button>
					<?elseif($arItem['ON_REQUEST']):?>
						<button type="button" class="btn-action buy when-in-stock" id="<?=$arItemIDs['REQUEST_LINK']?>"
							data-product-id="<?=$arItem['ID']?>" data-product-rid="<?=$arItem['RID'] ? : $arItem['ID']?>" data-measure-name="<?=$arItem['CATALOG_MEASURE_NAME']?>"
							data-toggle="modal" data-target="#modal_contact_product">
							<i class="flaticon-speech90"></i>
                            <input type="hidden" name="bigdata_recommendation_id" value="<?=$arItem['RID'] ? : $arItem['ID']?>">
						</button>
					<?else:?>
						<span class="when-out-of-stock"><?= COption::GetOptionString($moduleId, 'button_text_na') ?></span>
					<?endif?>
				<?endif?>
			</div>
		</div><!-- .action-buttons -->
		<?if($bShowOneClick && $arItem['CAN_BUY'] && (!$bBuyProps || $emptyProductProperties)):?> 
			<button id="<?=$arItemIDs['BUY_ONECLICK']?>" type="button" class="action one-click-buy" data-toggle="modal" data-target="#modal_quick-buy" data-id="<?=$arItem['ID']?>">
				<i class="flaticon-shopping220"></i>
				<span class="text"><?=GetMessage('BITRONIC2_ACCESORIES_ONECLICK')?></span>
			</button>
		<?endif?> 
		<?$arJSParams = array(
			'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
			'SHOW_QUANTITY' => ($arParams['USE_PRODUCT_QUANTITY'] == 'Y'),
			'BIG_DATA' => true,
			'ACCESSORS' => true,
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
			'REQUEST_URI' => $_SERVER["REQUEST_URI"],
			'SCRIPT_NAME' => $_SERVER["SCRIPT_NAME"],
			'DEFAULT_PICTURE' => array(
				'PICTURE' => $arItem['PRODUCT_PREVIEW'],
				'PICTURE_SECOND' => $arItem['PRODUCT_PREVIEW_SECOND']
			),
			'VISUAL' => array(
				'ID' => $arItemIDs['ID'],
				'PRICE_ID' => $arItemIDs['PRICE'],
				'BUY_ID' => $arItemIDs['BUY_LINK'],
				'BUY_ID_2' => $arItemIDs['BUY_LINK_2'],
				'BUY_ONECLICK' => $arItemIDs['BUY_ONECLICK'],
				'ADD_BASKET_ID' => $arItemIDs['ADD_BASKET_ID'],
				'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
				'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV'],
				'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK'],
				'FAVORITE_ID' => $arItemIDs['FAVORITE_LINK'],
				'BIG_DATA_CONTAINER' => $arItemIDs['BIG_DATA_CONTAINER'],	
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
			var <? echo $strObName; ?> = new JCCatalogItem(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
		</script>
	</div><!-- /.accessory -->
	<?endforeach?>
</div>

<?
/* TODO
<div class="all-accessories-link">
	<a href="product.php" class="link more-content">
		<div class="bullets">
			<span class="bullet">&bullet;</span><!-- 
			--><span class="bullet">&bullet;</span><!--
			--><span class="bullet">&bullet;</span>
		</div>
		<span class="text">
			<?=GetMessage('BITRONIC2_ACCESORIES_ALL')?>
		</span>
	</a>
</div>
*/
?>
<?
// echo "<pre style='text-align:left;'>";print_r($arResult['ITEMS']);echo "</pre>";