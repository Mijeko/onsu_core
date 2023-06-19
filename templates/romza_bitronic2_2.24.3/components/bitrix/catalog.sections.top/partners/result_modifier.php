<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (empty($arParams['RESIZER_SET'])) $arParams['RESIZER_SET'] = '4';

if (!is_array($arResult['SECTIONS'])) return;

$bResizer = (Bitrix\Main\Loader::includeModule('yenisite.resizer2'));

foreach ($arResult["SECTIONS"] as &$arSection) {
	if (!is_array($arSection['ITEMS'])) continue;

	foreach ($arSection["ITEMS"] as &$arItem) {
		$arItem['PICTURE'] = is_array($arItem['PREVIEW_PICTURE'])
			? $arItem['PREVIEW_PICTURE']
			: (is_array($arItem['DETAIL_PICTURE'])
				? $arItem['DETAIL_PICTURE']
				: false
			);
		if ($bResizer) {
			if (is_array($arItem['PICTURE'])) {
				$arItem['PICTURE']['SRC'] = CResizer2Resize::ResizeGD2($arItem['PICTURE']['SRC'], $arParams['RESIZER_SET']);
			} else {
				$arItem['PICTURE'] = array(
					'ALT' => $arItem['NAME'],
					'SRC' => CResizer2Resize::ResizeGD2('', $arParams['RESIZER_SET'])
				);
			}
		}
	}
}