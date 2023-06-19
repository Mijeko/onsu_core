<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
__IncludeLang($_SERVER["DOCUMENT_ROOT"].$templateFolder."/lang/".LANGUAGE_ID."/template.php");
$arParams['DETAIL_BASKET_POPUP'] = true;
?>
	<div class="catalog-hits">
		<?
		$arResult['CATALOG_VAT_INCLUDED'] = $templateData['CATALOG_VAT_INCLUDED'];
		$arResult['MIN_PRICE'] = $templateData['MIN_PRICE'];
        $arParams['POPUP'] = true;
		switch ($arParams['SLIDER_TYPE']) {
			case 'similar':
			case 'similar_price':
			case 'similar_view':
			case 'recommended':
				include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/bitrix/catalog.element/catalog/'.$arParams['SLIDER_TYPE'].'.php';
				break;
			case 'viewed':
				$arPrepareParams = $arParams;
				include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/viewed_products.php';
				break;
			case 'similar_sell':
			default:
				include 'similar_sell.php';
				break;
		}
		?>
	</div>
	<div class="actions">
		<a href="#" class="btn-return" data-toggle="modal"
			data-target="#modal_basket">
			<span class="text"><?=GetMessage('BITRONIC2_POPUP_ITEM_CONTINUE')?></span>
		</a>
		<a href="<?=$arParams['BASKET_URL']?>" class="btn-continue">
			<span class="text"><?=GetMessage('BITRONIC2_POPUP_ITEM_MAKE_ORDER')?></span>
		</a>
	</div>