<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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

$injectId = 'sale_gift_main_products_'.rand();
use Bitrix\Main\Loader;

$templateData['JS_OBJ'] = "BX.Sale['GiftMainProductsClass_{$component->getComponentId()}']";

include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/module_code.php';

if(Loader::includeModule($moduleId) && Loader::IncludeModule('yenisite.core')) {
    $arParamsCatalog = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
}


// component parameters
$signer = new \Bitrix\Main\Security\Sign\Signer;
$signedParameters = $signer->sign(
	base64_encode(serialize($arResult['_ORIGINAL_PARAMS'])),
	'bx.sale.gift.main.products'
);
$signedTemplate = $signer->sign($arResult['RCM_TEMPLATE'], 'bx.sale.gift.main.products');

?>
<div id="<?= $injectId ?>" class="bx_sale_gift_main_products">
<?
if($arResult['HAS_MAIN_PRODUCTS'])
{
	global $searchFilter;
	$searchFilter = array();
	if($arResult['MAIN_ELEMENT_IDS'])
	{
		$searchFilter = array(
			"=ID" => $arResult['MAIN_ELEMENT_IDS'],
		);
	}
    CRZBitronic2CatalogUtils::setFilterAvPrFoto($searchFilter, $arParamsCatalog);
	?>
	<?
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"detail_slider",
		array(
			"HEADER_TEXT" => ($arParams['BLOCK_TITLE'] ?: GetMessage('SLB_TPL_TITLE_GIFT')),
			"DISPLAY_FAVORITE" => $arParams["DISPLAY_FAVORITE"],
			"DISPLAY_ONECLICK" => $arParams["DISPLAY_ONECLICK"],
			"RESIZER_SECTION" => $arParams['RESIZER_SECTION'],
			"STICKER" => "WITH_GIFT",
			"SUFFIX" => "_w_gift",

			"CUSTOM_CURRENT_PAGE" => $arParams["SGMP_CUR_BASE_PAGE"],
			"AJAX_MODE" => $arParams["AJAX_MODE"],
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],

			'SECTION_ID' => reset($arResult['MAIN_SECTION_IDS']),

			"ELEMENT_SORT_FIELD" => 'ID',
			"ELEMENT_SORT_ORDER" => 'DESC',
			//		"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
			//		"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
			"FILTER_NAME" => 'searchFilter',
			"SECTION_URL" => $arParams["SECTION_URL"],
			"DETAIL_URL" => $arParams["DETAIL_URL"],
			"BASKET_URL" => $arParams["BASKET_URL"],
			"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
			"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
			"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],

			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],

			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"SET_TITLE" => $arParams["SET_TITLE"],
			"PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
			"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
			"TEMPLATE_THEME" => (isset($arParams["TEMPLATE_THEME"]) ? $arParams["TEMPLATE_THEME"] : ""),

			"ADD_PICT_PROP" => (isset($arParams["ADD_PICT_PROP"]) ? $arParams["ADD_PICT_PROP"] : ""),

			"LABEL_PROP" => (isset($arParams["LABEL_PROP"]) ? $arParams["LABEL_PROP"] : ""),
			"OFFER_ADD_PICT_PROP" => (isset($arParams["OFFER_ADD_PICT_PROP"]) ? $arParams["OFFER_ADD_PICT_PROP"] : ""),
			"OFFER_TREE_PROPS" => (isset($arParams["OFFER_TREE_PROPS"]) ? $arParams["OFFER_TREE_PROPS"] : ""),
			"SHOW_DISCOUNT_PERCENT" => (isset($arParams["SHOW_DISCOUNT_PERCENT"]) ? $arParams["SHOW_DISCOUNT_PERCENT"] : ""),
			"SHOW_OLD_PRICE" => (isset($arParams["SHOW_OLD_PRICE"]) ? $arParams["SHOW_OLD_PRICE"] : ""),
			"MESS_BTN_BUY" => (isset($arParams["MESS_BTN_BUY"]) ? $arParams["MESS_BTN_BUY"] : ""),
			"MESS_BTN_ADD_TO_BASKET" => (isset($arParams["MESS_BTN_ADD_TO_BASKET"]) ? $arParams["MESS_BTN_ADD_TO_BASKET"] : ""),
			"MESS_BTN_DETAIL" => (isset($arParams["MESS_BTN_DETAIL"]) ? $arParams["MESS_BTN_DETAIL"] : ""),
			"MESS_NOT_AVAILABLE" => (isset($arParams["MESS_NOT_AVAILABLE"]) ? $arParams["MESS_NOT_AVAILABLE"] : ""),
			'ADD_TO_BASKET_ACTION' => (isset($arParams["ADD_TO_BASKET_ACTION"]) ? $arParams["ADD_TO_BASKET_ACTION"] : ""),
			'SHOW_CLOSE_POPUP' => (isset($arParams["SHOW_CLOSE_POPUP"]) ? $arParams["SHOW_CLOSE_POPUP"] : ""),
			'DISPLAY_COMPARE_SOLUTION' => (isset($arParams['DISPLAY_COMPARE_SOLUTION']) ? $arParams['DISPLAY_COMPARE_SOLUTION'] : ''),
			'COMPARE_PATH' => (isset($arParams['COMPARE_PATH']) ? $arParams['COMPARE_PATH'] : ''),

			"OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
			"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],

			//self
			"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
			"CACHE_FILTER" => $arParams["CACHE_FILTER"],
			"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
			"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
			"BY_LINK" => "N",
			"DISPLAY_TOP_PAGER" => 'N',
			"DISPLAY_BOTTOM_PAGER" => 'Y',
			"PAGER_TITLE" => $arParams["PAGER_TITLE"],
			"PAGER_TEMPLATE" => 'round',
			"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
			"PRODUCT_DISPLAY_MODE" => 'Y',
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_BASE_LINK_ENABLE" => "Y",
			"HIDE_SECTION_DESCRIPTION" => "Y",
			"SHOW_ALL_WO_SECTION" => "Y",
			"PAGER_BASE_LINK" => "/bitrix/components/bitrix/sale.gift.main.products/ajax.php",
			"HOVER-MODE" => $arParams["HOVER-MODE"],

            //PARAMS FOR HIDE ITEMS
            'HIDE_ITEMS_NOT_AVAILABLE' => $arParamsCatalog['HIDE_ITEMS_NOT_AVAILABLE'],
            'HIDE_ITEMS_ZER_PRICE' => $arParamsCatalog['HIDE_ITEMS_ZER_PRICE'],
            'HIDE_ITEMS_WITHOUT_IMG' => $arParamsCatalog['HIDE_ITEMS_WITHOUT_IMG'],
            'ORDER_DETAIL_BLOCKS' => $arParams['ORDER_DETAIL_BLOCKS']['order-sPrGiftProducts'],
            'NAME_ORDER_BLOCK' => 'sPrGiftProducts',
            "DISPLAY_IS_RELATED" => true,
            "ELEMENT_ID" => $arParams['ELEMENT_ID']
		),
		$component,
		array('HIDE_ICONS' => 'Y')
	);
}
?>
</div>
<script type="text/javascript">
BX(function () {
	BX.Sale['GiftMainProductsClass_<?= $component->getComponentId() ?>'] = new BX.Sale.GiftMainProductsClass({
		contextAjaxData:  {
			parameters:'<?=CUtil::JSEscape($signedParameters)?>',
			template: '<?=CUtil::JSEscape($signedTemplate)?>',
			site_id: '<?=CUtil::JSEscape(SITE_ID)?>'
		},
		injectId:  '<?= $injectId ?>',
		mainProductState:  '<?= $arResult['MAIN_PRODUCT_STATE'] ?>',
		isGift:  <?= $arResult['HAS_MAIN_PRODUCTS']? 'true' : 'false'; ?>,
		productId:  <?= $arParams['ELEMENT_ID']?: 'null'; ?>,
		offerId: <?= $arParams['OFFER_ID']?: 'null'; ?>
	});
	if (BX.Sale['GiftMainProductsClass_<?= $component->getComponentId() ?>'].isGift) {
		var $stickerwrap = $('#photo-block div.stickers-wrap');
		var $amGift = $stickerwrap.find('.i-am-gift');
		if ($amGift.length < 1) {
			$amGift = $('<div class="sticker i-am-gift flaticon-6-2"><?=GetMessage("BITRONIC2_GIFTS_STICKER_TEXT")?></div>');
			$amGift.appendTo($stickerwrap);
		}
	}
});
BX.message({});
</script>
