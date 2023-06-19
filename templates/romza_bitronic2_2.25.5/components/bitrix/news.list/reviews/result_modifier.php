<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$resizer = array('WIDTH' => 370, 'HEIGHT' => 300, 'SET_ID' => $arParams['RESIZER_ITEM']);

foreach ($arResult['ITEMS'] as &$arItem) {
    $arItem['PICTURE'] = CRZBitronic2CatalogUtils::GetResizedImg($arItem, $resizer, 'svg', true);
    $arItem['TEXT'] = $arItem['PREVIEW_TEXT'] ? $arItem['PREVIEW_TEXT'] : $arItem['DETAIL_TEXT'];
    $arItem['DATE'] = $arItem['DISPLAY_ACTIVE_FROM'];
}
unset($arItem);