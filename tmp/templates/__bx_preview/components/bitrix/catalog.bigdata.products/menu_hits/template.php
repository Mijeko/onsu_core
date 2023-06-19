<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

include $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/include/debug_info.php';
include $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/header.php';

$injectId = 'bigdata_menu_hits_'.rand();

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
	<div class="scroll-slider-wrap no-scroll" id="<?=$injectId?>"></div>

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
			
			(<?=$injectId?>.loadItems = function(){
				if (!$('#' + <?=$injectId?>.containerId).parent().is(':visible')) {
					setTimeout(function(){
						<?=$injectId?>.loadItems();
					}, 1000);
					return;
				}
				BX.ajax({
					'method': 'GET',
					'dataType': 'json',
					'url': url,
					'timeout': 3,
					'onsuccess': BX.delegate(<?=$injectId?>.SendRequest, <?=$injectId?>),
					'onfailure': BX.delegate(<?=$injectId?>.SendRequest, <?=$injectId?>),
				});
			})();
		});
	</script>

	<?
	return;
}

if(empty($arResult['ITEMS']))
	return;
	
?>
<button type="button" class="show-hide-hits shown">
	<span class="hide-hits">
		<span class="text"><?=GetMessage('BITRONIC2_MENU_CATALOG_HITS_HIDE')?></span>
		<i class="flaticon-close47"></i>
	</span>
	<span class="show-hits">
		<span class="text"><?=GetMessage('BITRONIC2_MENU_CATALOG_HITS_SHOW')?></span>
	</span>
</button>
<!--<div class="scroll-slider-wrap" id="<?=$injectId?>_items">-->
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
		<div class="slider-content slidee"><?
			foreach($arResult['ITEMS'] as $arItem)
			{
				$strMainID = $this->GetEditAreaId('menu_catalog_hit'.$arItem['ID']);
				$arItemIDs = array(
					'PRICE_DIV' => $strMainID.'_price_wrap',
				);
				$imgTitle = (
					!empty($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'])
					? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
					: $arItem['NAME']
				);
				?><div class="slider-item">
					<a itemscope itemtype="http://schema.org/ImageObject" class="photo" href="<?=$arItem['DETAIL_PAGE_URL']?>" title="<?= $imgTitle ?>">
						<span data-picture data-alt="<?=$arItem['PICTURE_PRINT']['ALT']?>">
							<span data-itemprop="contentUrl" data-title="<?= $imgTitle ?>" data-src="<?=$arItem['PICTURE_PRINT']['SRC']?>"></span>
							<span data-src="" data-media="(max-width: 767px)"></span>
							<!-- Fallback content for non-JS browsers. Same img src as the initial, unqualified source element. -->
							<noscript>
									<img itemprop="contentUrl" data-title="<?= $imgTitle ?>" class="lazy" data-original="<?=$arItem['PICTURE_PRINT']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['PICTURE_PRINT']['ALT']?>">
							</noscript>
						</span>	
					</a>
					<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="name link"><span class="text"><?=$arItem['NAME']?></span></a>
					<div class="price-wrap" id="<?=$arItemIDs['PRICE_DIV']?>">
					<? $frame = $this->createFrame($arItemIDs['PRICE_DIV'], false)->begin(CRZBitronic2Composite::insertCompositLoader()) ?>
					<? if($arItem['ON_REQUEST']): ?>
						<span class="price"><?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_ON_REQUEST')?></span>
					<? else: ?>
						<?if($arItem['MIN_PRICE']['DISCOUNT_DIFF'] > 0):?>
							<span class="price-old"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['VALUE'], $arItem['MIN_PRICE']['PRINT_VALUE']);?></span>
						<?endif?>
						<span class="price">
							<?=($arItem['bOffers'] && $arItem['bOffersNotEqual']) ? GetMessage('BITRONIC2_MENU_CATALOG_HITS_FROM') : ''?>
							<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);?>
						</span>
					<? endif ?>
					<? $frame->end() ?>
					</div>
				</div><?
			}
		?></div><!-- /.slider-content -->
	</div><!-- /.scroll-slider -->	
<?
// echo "<pre style='text-align:left;'>";print_r($arResult['ITEMS']);echo "</pre>";