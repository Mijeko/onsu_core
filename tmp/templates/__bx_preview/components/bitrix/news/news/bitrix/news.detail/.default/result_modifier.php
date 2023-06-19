<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (isset($arResult['DISPLAY_PROPERTIES']['RELATED_ITEMS']) && !in_array('RELATED_ITEMS', $arParams['PROPERTY_CODE_ORIG'])) {
	unset($arResult['DISPLAY_PROPERTIES']['RELATED_ITEMS']);
}
if (!empty($arResult['PROPERTIES'][$arParams['PROP_FOR_DISCOUNT']]) && CRZBitronic2Settings::getEdition() != 'LITE'){
    if (!is_array($arResult['PROPERTIES'][$arParams['PROP_FOR_DISCOUNT']]['VALUE'])){
        $arResult['PROPERTIES'][$arParams['PROP_FOR_DISCOUNT']]['VALUE'] = array($arResult['PROPERTIES'][$arParams['PROP_FOR_DISCOUNT']]['VALUE']);
    }
    $arResult['DISCOUNT_DATA'] = CRZBitronic2CatalogUtils::getElementsListByDiscount($arResult['PROPERTIES'][$arParams['PROP_FOR_DISCOUNT']]['VALUE'], $arResult['ID'],$arParams);
}