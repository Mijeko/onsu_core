<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (is_array($arResult['ITEMS'])) {
	$arUnsetKeys = array();
	foreach ($arResult['ITEMS'] as $key => &$arItem) {
		$itemSite = $arItem['DISPLAY_PROPERTIES']['SITE_ID']['VALUE'];
		if (!empty($itemSite) && $itemSite != SITE_ID) {
			$arUnsetKeys[] = $key;
			continue;
		}
		$arItem['DATE_CREATE'] = date('d.m.Y', MakeTimeStamp($arItem['DATE_CREATE']));
	}
	unset($arItem);
	foreach ($arUnsetKeys as $key) {
		unset($arResult['ITEMS'][$key]);
	}
}
?>