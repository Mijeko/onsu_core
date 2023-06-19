<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
$arResult = $templateData;
if (!empty($arResult["PROPERTIES"]["RELATED_ITEMS"]) || $arParams['ACTIONS_USE']) {
	$arResult['CATALOG_ID'] = $arResult["PROPERTIES"]["RELATED_ITEMS"]["LINK_IBLOCK_ID"];
	$arResult['CATALOG_PARAMS'] = array();
    $arResult['DISCOUNT_DATA'] = $templateData['DISCOUNT_DATA'];
	$arMainParams = $arParams;

	$arParams = array();
	if (Loader::IncludeModule('yenisite.core')) {
		$arParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
	}

	// @var $arPrepareParams
	include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/prepare_params_element.php';

	$arResult['CATALOG_PARAMS'] = $arPrepareParams;
	$arParams = $arMainParams;
	unset($arMainParams);
}
?>

<?$frame = new \Bitrix\Main\Page\FrameBuffered("news_epilog_related");?>
<?$frame->begin('');?>
<?if (!empty($arResult["CATALOG_ID"]) && Loader::includeModule('catalog') && !$arParams['ACTIONS_USE']):?>
<?$APPLICATION->IncludeComponent("bitrix:catalog.recommended.products", "bitronic2", array(
	"HEADER_TEXT" => $arParams["RELATED_HEADER_TEXT"],
	"DISPLAY_COMPARE_SOLUTION" => $arResult['CATALOG_PARAMS']['DISPLAY_COMPARE_SOLUTION'],
	"DISPLAY_FAVORITE" => $arResult['CATALOG_PARAMS']["DISPLAY_FAVORITE"],
	"DISPLAY_ONECLICK" => $arResult['CATALOG_PARAMS']["DISPLAY_ONECLICK"],
	"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"ID" => $arResult['ID'],
	"PROPERTY_LINK" => "RELATED_ITEMS",
	"SHOW_PRODUCTS_".$arResult["CATALOG_ID"] => "Y",///////////
	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"BASKET_URL" => $arResult['CATALOG_PARAMS']["BASKET_URL"],
	"ACTION_VARIABLE" => $arResult['CATALOG_PARAMS']["ACTION_VARIABLE"],
	"PRODUCT_ID_VARIABLE" => $arResult['CATALOG_PARAMS']["PRODUCT_ID_VARIABLE"],
	"PRODUCT_QUANTITY_VARIABLE" => $arResult['CATALOG_PARAMS']["PRODUCT_QUANTITY_VARIABLE"],
	"ADD_PROPERTIES_TO_BASKET" => (isset($arResult['CATALOG_PARAMS']["ADD_PROPERTIES_TO_BASKET"]) ? $arResult['CATALOG_PARAMS']["ADD_PROPERTIES_TO_BASKET"] : ''),
	"PRODUCT_PROPS_VARIABLE" => $arResult['CATALOG_PARAMS']["PRODUCT_PROPS_VARIABLE"],
	"PARTIAL_PRODUCT_PROPERTIES" => (isset($arResult['CATALOG_PARAMS']["PARTIAL_PRODUCT_PROPERTIES"]) ? $arResult['CATALOG_PARAMS']["PARTIAL_PRODUCT_PROPERTIES"] : ''),
	"PAGE_ELEMENT_COUNT" => $arResult['CATALOG_PARAMS']["PAGE_ELEMENT_COUNT"],
	"SHOW_OLD_PRICE" => $arResult['CATALOG_PARAMS']['SHOW_OLD_PRICE'],
	"SHOW_DISCOUNT_PERCENT" => $arResult['CATALOG_PARAMS']['SHOW_DISCOUNT_PERCENT'],
	"PRICE_CODE" => $arResult['CATALOG_PARAMS']["PRICE_CODE"],
	"SHOW_PRICE_COUNT" => $arResult['CATALOG_PARAMS']["SHOW_PRICE_COUNT"],
	"PRODUCT_SUBSCRIPTION" => 'N',
	"PRICE_VAT_INCLUDE" => $arResult['CATALOG_PARAMS']["PRICE_VAT_INCLUDE"],
	"USE_PRODUCT_QUANTITY" => $arResult['CATALOG_PARAMS']['USE_PRODUCT_QUANTITY'],
	"SHOW_NAME" => "Y",
	"SHOW_IMAGE" => "Y",
	"MESS_BTN_BUY" => $arResult['CATALOG_PARAMS']['MESS_BTN_BUY'],
	"MESS_BTN_DETAIL" => $arResult['CATALOG_PARAMS']["MESS_BTN_DETAIL"],
	"MESS_NOT_AVAILABLE" => $arResult['CATALOG_PARAMS']['MESS_NOT_AVAILABLE'],
	"MESS_BTN_SUBSCRIBE" => $arResult['CATALOG_PARAMS']['MESS_BTN_SUBSCRIBE'],
	"HIDE_NOT_AVAILABLE" => $arResult['CATALOG_PARAMS']["SLIDERS_HIDE_NOT_AVAILABLE"],
	//"OFFER_TREE_PROPS_".$arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"],
	//"OFFER_TREE_PROPS_".$arRecomData['OFFER_IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"],
	"ADDITIONAL_PICT_PROP_".$arResult["CATALOG_ID"] => $arResult['CATALOG_PARAMS']['ADD_PICT_PROP'],
	//"ADDITIONAL_PICT_PROP_".$arRecomData['OFFER_IBLOCK_ID'] => $arResult['CATALOG_PARAMS']['OFFER_ADD_PICT_PROP'],
	//"PROPERTY_CODE_".$arRecomData['OFFER_IBLOCK_ID'] => array(),
	"CONVERT_CURRENCY" => $arResult['CATALOG_PARAMS']["CONVERT_CURRENCY"],
	"CURRENCY_ID" => $arResult['CATALOG_PARAMS']["CURRENCY_ID"],
	"RESIZER_SECTION" => $arResult['CATALOG_PARAMS']['RESIZER_SETS']['RESIZER_SECTION'],
	'HOVER-MODE' => $arParams['HOVER-MODE'],

	//PARAMS FOR HIDE ITEMS
	'HIDE_ITEMS_NOT_AVAILABLE' => $arResult['CATALOG_PARAMS']['HIDE_ITEMS_NOT_AVAILABLE'],
	'HIDE_ITEMS_ZER_PRICE' => $arResult['CATALOG_PARAMS']['HIDE_ITEMS_ZER_PRICE'],
	'HIDE_ITEMS_WITHOUT_IMG' => $arResult['CATALOG_PARAMS']['HIDE_ITEMS_WITHOUT_IMG'],
));?>
<?else:?>
	<?if (!empty($arResult['DISCOUNT_DATA'])):?>
		<?
			global $discountFilter;
				if (!empty($arResult['DISCOUNT_DATA']['IBLOCK_ID'])){
                        foreach ($arResult['DISCOUNT_DATA']['IBLOCK_ID'] as $idIblock => $idAction){
                            $discountFilter['IBLOCK_ID'][] = $idIblock;
                        }
				} elseif(!empty($arResult['DISCOUNT_DATA']['SECTION_ID'])){
                    foreach ($arResult['DISCOUNT_DATA']['SECTION_ID'] as $idSection => $idAction) {
						$discountFilter['SECTION_ID'][] = $idSection;
                    }
				} elseif (!empty($arResult['DISCOUNT_DATA']['PRODUCT_ID'])){
                    foreach ($arResult['DISCOUNT_DATA']['PRODUCT_ID'] as $idProduct => $idAction) {
						$discountFilter['ID'][] = $idProduct;
                    }
				}elseif (!empty($arResult['DISCOUNT_DATA']['UNPACK_ITEM'])){
                    $discountFilter['IBLOCK_ID'][] = $arResult['CATALOG_PARAMS']['IBLOCK_ID'];
                }
            CRZBitronic2CatalogUtils::setFilterAvPrFoto($discountFilter, $arResult['CATALOG_PARAMS']);

            $discountFilter['INCLUDE_SUBSECTIONS'] = 'Y';
            if (!empty($arResult['DISCOUNT_DATA']['UNPACK_ITEM'])){
                $arParams['UNPACK'] = $arResult['DISCOUNT_DATA']['UNPACK_ITEM'];
            }
		?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:catalog.section",
			"detail_slider",
			Array(
				"HEADER_TEXT" => $arParams['ACTION_TEXT'] ? : GetMessage('ACTION_TEXT'),
				"DISPLAY_COMPARE_SOLUTION" => $arResult['CATALOG_PARAMS']['DISPLAY_COMPARE_SOLUTION'],
				"DISPLAY_FAVORITE" => $arResult['CATALOG_PARAMS']["DISPLAY_FAVORITE"],
				"DISPLAY_ONECLICK" => $arResult['CATALOG_PARAMS']["DISPLAY_ONECLICK"],
				"IBLOCK_TYPE" => $arResult['CATALOG_PARAMS']["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arResult['CATALOG_PARAMS']["IBLOCK_ID"],
				"SHOW_ALL_WO_SECTION" => "Y",
				"FILTER_NAME" => "discountFilter",
				"CACHE_TYPE" => $arParams["CACHE_TYPE"],
				"CACHE_TIME" => $arParams["CACHE_TIME"],
				"BASKET_URL" => $arParams["BASKET_URL"],
				"ACTION_VARIABLE" => $arResult['CATALOG_PARAMS']["ACTION_VARIABLE"],
				"PRODUCT_ID_VARIABLE" => $arResult['CATALOG_PARAMS']["PRODUCT_ID_VARIABLE"],
				"PRODUCT_QUANTITY_VARIABLE" => $arResult['CATALOG_PARAMS']["PRODUCT_QUANTITY_VARIABLE"],
				"ADD_PROPERTIES_TO_BASKET" => (isset($arResult['CATALOG_PARAMS']["ADD_PROPERTIES_TO_BASKET"]) ? $arResult['CATALOG_PARAMS']["ADD_PROPERTIES_TO_BASKET"] : ''),
				"PRODUCT_PROPS_VARIABLE" => $arResult['CATALOG_PARAMS']["PRODUCT_PROPS_VARIABLE"],
				"PARTIAL_PRODUCT_PROPERTIES" => (isset($arResult['CATALOG_PARAMS']["PARTIAL_PRODUCT_PROPERTIES"]) ? $arResult['CATALOG_PARAMS']["PARTIAL_PRODUCT_PROPERTIES"] : ''),
				"SHOW_OLD_PRICE" => $arResult['CATALOG_PARAMS']['SHOW_OLD_PRICE'],
				"SHOW_DISCOUNT_PERCENT" => $arResult['CATALOG_PARAMS']['SHOW_DISCOUNT_PERCENT'],
				"PRICE_CODE" => $arResult['CATALOG_PARAMS']["PRICE_CODE"],
				"SHOW_PRICE_COUNT" => $arResult['CATALOG_PARAMS']["SHOW_PRICE_COUNT"],
				"PRODUCT_SUBSCRIPTION" => 'N',
				"DISPLAY_TOP_PAGER" => 'N',
				"DISPLAY_BOTTOM_PAGER" => 'N',
				"PAGE_ELEMENT_COUNT" => $arParams['COUNT_ELEMENT_ACTIONS'] ? : 99999,
				"OFFERS_SORT_FIELD" => "CATALOG_AVAILABLE",
				"OFFERS_SORT_ORDER" => "desc",
				"PRICE_VAT_INCLUDE" => $arResult['CATALOG_PARAMS']["PRICE_VAT_INCLUDE"],
				"USE_PRODUCT_QUANTITY" => $arResult['CATALOG_PARAMS']['USE_PRODUCT_QUANTITY'],
				"SHOW_NAME" => "Y",
				"SHOW_IMAGE" => "Y",
				"MESS_BTN_BUY" => $arResult['CATALOG_PARAMS']['MESS_BTN_BUY'],
				"MESS_BTN_DETAIL" => $arResult['CATALOG_PARAMS']["MESS_BTN_DETAIL"],
				"MESS_NOT_AVAILABLE" => $arResult['CATALOG_PARAMS']['MESS_NOT_AVAILABLE'],
				"MESS_BTN_SUBSCRIBE" => $arResult['CATALOG_PARAMS']['MESS_BTN_SUBSCRIBE'],
				"HIDE_NOT_AVAILABLE" => $arResult['CATALOG_PARAMS']["HIDE_NOT_AVAILABLE"],
				"PRODUCT_PROPERTIES" => $arResult['CATALOG_PARAMS']['PRODUCT_PROPERTIES'],
				// "ADD_PICT_PROP" => $arParams['ADD_PICT_PROP'],
				// 'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
				// 'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
				// "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
				"OFFERS_FIELD_CODE" => array(
					0 => "ID",
				),
				"CONVERT_CURRENCY" => $arResult['CATALOG_PARAMS']["CONVERT_CURRENCY"],
				"CURRENCY_ID" => $arResult['CATALOG_PARAMS']["CURRENCY_ID"],
				"RESIZER_SECTION" => $arResult['CATALOG_PARAMS']['RESIZER_SETS']['RESIZER_SECTION'],
				"HOVER-MODE" => $arResult['CATALOG_PARAMS']["HOVER-MODE"],

                //PARAMS FOR HIDE ITEMS
                'HIDE_ITEMS_NOT_AVAILABLE' => $arResult['CATALOG_PARAMS']['HIDE_ITEMS_NOT_AVAILABLE'],
                'HIDE_ITEMS_ZER_PRICE' => $arResult['CATALOG_PARAMS']['HIDE_ITEMS_ZER_PRICE'],
                'HIDE_ITEMS_WITHOUT_IMG' => $arResult['CATALOG_PARAMS']['HIDE_ITEMS_WITHOUT_IMG'],
                'UNPACK' => $arParams['UNPACK'],
                "INCLUDE_SUBSECTIONS" => "Y",
			),
			$component
		);?>
		<script type="text/javascript">
            b2.init.newsItemPage();
		</script>
	<?endif;?>
<?endif?>
<?$frame->end()?>