<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
if (!empty($arResult['ERROR']))
{
	echo $arResult['ERROR'];
	return false;
}

global $brandFilter, $rz_b2_options;

$listUrl = str_replace('#BLOCK_ID#', intval($arParams['BLOCK_ID']),	$arParams['LIST_URL']);
$brandFilter = array('PROPERTY_' . $arResult['CATALOG_PARAMS']['BRAND_PROP_CODE'] => $arResult['row']['UF_XML_ID']);

$arParamsCatalog = array();
if (Loader::IncludeModule('yenisite.core')) {
    $arParamsCatalog = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
}
if (!empty($rz_b2_options)){
    $arParamsForFilterByAvPrFoto = CRZBitronic2CatalogUtils::getParamsForHideItemsInCatalog();
    $arParamsForFilterByAvPrFoto['VALUE_RZ_AVAILABLE'] = $arParamsCatalog['VALUE_RZ_AVAILABLE'];
} else{
    $arParamsForFilterByAvPrFoto = $arParamsCatalog;
}
CRZBitronic2CatalogUtils::setFilterAvPrFoto($brandFilter,$arParamsForFilterByAvPrFoto);

$this->setFrameMode(true);
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';
?>

		<div class="images-row">
			<?=$arResult['row']['UF_FILE']?>
		</div>
		<p>
		<?if(strlen($arResult['row']["UF_FULL_DESCRIPTION"])>0):?>
			<?echo $arResult['row']["UF_FULL_DESCRIPTION"];?>
		<?else:?>
			<?echo $arResult['row']["UF_DESCRIPTION"];?>
		<?endif?>
		</p>
        <?if (!empty($arParams['SHOW_PROPS_OF_HLB'])):?>
            <?foreach ($arParams['SHOW_PROPS_OF_HLB'] as $key => $value):?>
                <?if ($value != $arParams['PROP_FOR_LINK_COMPONY'] && !empty($arResult['row'][$value]) && !empty($arResult['fields'][$value]['EDIT_FORM_LABEL'])):?>
                    <p><?=$arResult['fields'][$value]['EDIT_FORM_LABEL']?>: <?=$arResult['row'][$value]?></p>
                <?endif?>
            <?endforeach;?>
        <?endif?>

        <?if (!empty($arParams['PROP_FOR_LINK_COMPONY']) && !empty($arResult['row'][$arParams['PROP_FOR_LINK_COMPONY']]) && !empty($arResult['fields'][$arParams['PROP_FOR_LINK_COMPONY']]['EDIT_FORM_LABEL'])):?>
            <p><?=$arResult['fields'][$arParams['PROP_FOR_LINK_COMPONY']]['EDIT_FORM_LABEL']?>: <a href="<?=$arResult['row'][$arParams['PROP_FOR_LINK_COMPONY']]?>" class="link-bd link-std"><?=$arResult['row'][$arParams['PROP_FOR_LINK_COMPONY']]?></a></p>
        <?endif?>

		<?$frame = $this->createFrame()->begin('')?>
		<?$APPLICATION->IncludeComponent(
			"bitrix:catalog.section",
			"detail_brand_slider",
			Array(
				"HEADER_TEXT" => GetMessage('SLIDER_HEADER_TEXT', array('#BRAND_NAME#' => $arResult['row']['UF_NAME'], '#LINK#' => $arResult['LINK'])),
				"BRAND_NAME" => $arResult['row']['UF_NAME'],
				"PROP_FILTER_ID" => $arResult['PROP_CONTROL_ID'],
				"CATALOG_PATH" => $arParams['CATALOG_PATH'],
				"CATALOG_FILTER_NAME" => $arResult['CATALOG_PARAMS']['FILTER_NAME'],
				"DISPLAY_COMPARE_SOLUTION" => $arResult["CATALOG_PARAMS"]['DISPLAY_COMPARE_SOLUTION'],
				"DISPLAY_FAVORITE" => $arResult["CATALOG_PARAMS"]["DISPLAY_FAVORITE"],
				"DISPLAY_ONECLICK" => $arResult["CATALOG_PARAMS"]["DISPLAY_ONECLICK"],
				"IBLOCK_TYPE" => $arResult["CATALOG_PARAMS"]["IBLOCK_TYPE"],
				"IBLOCK_ID" => $arResult["CATALOG_PARAMS"]["IBLOCK_ID"],
				"SECTION_ID" => 0,
				"SHOW_ALL_WO_SECTION" => "Y",
				"FILTER_NAME" => "brandFilter",
				"BRAND_PROP_CODE" => $arResult['CATALOG_PARAMS']['BRAND_PROP_CODE'],
				"BRAND_PROP_VALUE" => $arResult['row']['UF_XML_ID'],
				"CACHE_TYPE" => $arResult["CATALOG_PARAMS"]["CACHE_TYPE"],
				"CACHE_TIME" => $arResult["CATALOG_PARAMS"]["CACHE_TIME"],
				"CACHE_FILTER" => "Y",
				"BASKET_URL" => $arResult["CATALOG_PARAMS"]["BASKET_URL"],
				"ACTION_VARIABLE" => $arResult["CATALOG_PARAMS"]["ACTION_VARIABLE"],
				"PRODUCT_ID_VARIABLE" => $arResult["CATALOG_PARAMS"]["PRODUCT_ID_VARIABLE"],
				"PRODUCT_QUANTITY_VARIABLE" => $arResult["CATALOG_PARAMS"]["PRODUCT_QUANTITY_VARIABLE"],
				"ADD_PROPERTIES_TO_BASKET" => (isset($arResult["CATALOG_PARAMS"]["ADD_PROPERTIES_TO_BASKET"]) ? $arResult["CATALOG_PARAMS"]["ADD_PROPERTIES_TO_BASKET"] : ''),
				"PRODUCT_PROPS_VARIABLE" => $arResult["CATALOG_PARAMS"]["PRODUCT_PROPS_VARIABLE"],
				"PARTIAL_PRODUCT_PROPERTIES" => (isset($arResult["CATALOG_PARAMS"]["PARTIAL_PRODUCT_PROPERTIES"]) ? $arResult["CATALOG_PARAMS"]["PARTIAL_PRODUCT_PROPERTIES"] : ''),
				"PAGE_ELEMENT_COUNT" => $arParams["ELEMENT_COUNT"],
				"SHOW_OLD_PRICE" => $arResult["CATALOG_PARAMS"]['SHOW_OLD_PRICE'],
				"SHOW_DISCOUNT_PERCENT" => $arResult["CATALOG_PARAMS"]['SHOW_DISCOUNT_PERCENT'],
				"PRICE_CODE" => $arResult["CATALOG_PARAMS"]["PRICE_CODE"],
				"SHOW_PRICE_COUNT" => $arResult["CATALOG_PARAMS"]["SHOW_PRICE_COUNT"],
				"PRODUCT_SUBSCRIPTION" => 'N',
				"PRICE_VAT_INCLUDE" => $arResult["CATALOG_PARAMS"]["PRICE_VAT_INCLUDE"],
				"USE_PRODUCT_QUANTITY" => $arResult["CATALOG_PARAMS"]['USE_PRODUCT_QUANTITY'],
				"SHOW_NAME" => "Y",
				"SHOW_IMAGE" => "Y",
				"MESS_BTN_BUY" => $arResult["CATALOG_PARAMS"]['MESS_BTN_BUY'],
				"MESS_BTN_DETAIL" => $arResult["CATALOG_PARAMS"]["MESS_BTN_DETAIL"],
				"MESS_NOT_AVAILABLE" => $arResult["CATALOG_PARAMS"]['MESS_NOT_AVAILABLE'],
				"MESS_BTN_SUBSCRIBE" => $arResult["CATALOG_PARAMS"]['MESS_BTN_SUBSCRIBE'],
				"HIDE_NOT_AVAILABLE" => $arResult["CATALOG_PARAMS"]["HIDE_NOT_AVAILABLE"],
				"PRODUCT_PROPERTIES" => $arResult["CATALOG_PARAMS"]['PRODUCT_PROPERTIES'],
				"ADD_PICT_PROP" => $arResult["CATALOG_PARAMS"]['ADD_PICT_PROP'],
				'OFFER_TREE_PROPS' => $arResult["CATALOG_PARAMS"]['OFFER_TREE_PROPS'],
				'OFFER_ADD_PICT_PROP' => $arResult["CATALOG_PARAMS"]['OFFER_ADD_PICT_PROP'],
				"OFFERS_CART_PROPERTIES" => $arResult["CATALOG_PARAMS"]["OFFERS_CART_PROPERTIES"],
				"OFFERS_FIELD_CODE" => array(
					0 => "ID",
				),
				"CONVERT_CURRENCY" => $arResult["CATALOG_PARAMS"]["CONVERT_CURRENCY"],
				"CURRENCY_ID" => $arResult["CATALOG_PARAMS"]["CURRENCY_ID"],
				"RESIZER_SECTION" => $arResult["CATALOG_PARAMS"]['RESIZER_SETS']['RESIZER_SECTION'],
				"HOVER-MODE" => $arParams['HOVER-MODE'],

                'HIDE_ITEMS_NOT_AVAILABLE' => $arParamsCatalog['HIDE_ITEMS_NOT_AVAILABLE'],
                'HIDE_ITEMS_ZER_PRICE' => $arParamsCatalog['HIDE_ITEMS_ZER_PRICE'],
                'HIDE_ITEMS_WITHOUT_IMG' => $arParamsCatalog['HIDE_ITEMS_WITHOUT_IMG'],
                'SEF_MODE' => $arParamsCatalog['SEF_MODE'],
                'SEF_FILTER' => $arParamsCatalog['SEF_FOLDER']. $arParamsCatalog['SEF_URL_TEMPLATES']['smart_filter'],
                'PROP_DATA' => $arResult['PROP_DATA'],
			),
			$component
		);?>
		<?$frame->end()?>

		<div class="pull-left visible-xs"><a class="link-bd link-std" href="<?= $arResult['LINK'] ?>"><?= GetMessage('SLIDER_ALL_ITEMS_TEXT') ?></a></div>
		<div class="text-right"><a class="link-bd link-std" href="<?=$arParams['LIST_URL']?>"><?=GetMessage('HLBLOCK_ROW_VIEW_BACK_TO_LIST')?></a></div>
