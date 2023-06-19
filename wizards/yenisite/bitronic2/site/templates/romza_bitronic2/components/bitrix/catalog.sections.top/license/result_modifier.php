<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (empty($arParams['RESIZER_SET_SMALL'])) $arParams['RESIZER_SET_SMALL'] = '4';
if (empty($arParams['RESIZER_SET_BIG'])) $arParams['RESIZER_SET_BIG'] = '1';

if (!is_array($arResult['SECTIONS'])) return;
if (!Bitrix\Main\Loader::includeModule('yenisite.resizer2')) return;

foreach ($arResult["SECTIONS"] as &$arSection) {
	if (!is_array($arSection['ITEMS'])) continue;

	foreach ($arSection["ITEMS"] as &$arItem) {
		if (is_array($arItem['DETAIL_PICTURE'])) {
			if (!is_array($arItem['PREVIEW_PICTURE'])) {
				$arItem['PREVIEW_PICTURE'] = $arItem['DETAIL_PICTURE'];
			}
			$arItem['DETAIL_PICTURE']['SRC'] = CResizer2Resize::ResizeGD2($arItem['DETAIL_PICTURE']['SRC'], $arParams['RESIZER_SET_BIG']);
		}
		if (is_array($arItem['PREVIEW_PICTURE'])) {
			$arItem['PREVIEW_PICTURE']['SRC'] = CResizer2Resize::ResizeGD2($arItem['PREVIEW_PICTURE']['SRC'], $arParams['RESIZER_SET_SMALL']);
		}
	}
}