<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// #### DEF PARAMS
if(intval($arParams['RESIZER_SET_1200']) <= 0)
{
	$arParams['RESIZER_SET_1200'] = 7;
}
if(intval($arParams['RESIZER_SET_991']) <= 0)
{
	$arParams['RESIZER_SET_991'] = 2;
}
if($arParams['USE_RESIZER_SET_FROM_1200'] === 'Y')
{
	$arResult['RESIZER']['bUseFrom1200'] = true;
	if(intval($arParams['RESIZER_SET_FROM_1200']) <= 0)
	{
		$arParams['RESIZER_SET_FROM_1200'] = 1;
	}
}

$arParams['MENU_CATALOG'] = ($arParams['MENU_CATALOG'] ? $arParams['MENU_CATALOG'] : false);
$arParams['SLIDER_WIDTH'] = ($arParams['SLIDER_WIDTH'] ? $arParams['SLIDER_WIDTH'] : 'full');

// if menu in side
if (!empty($arParams['MENU_CATALOG'])) {
	$arParams["SLIDER_WIDTH"] = 'narrow';
}

// youtube
if (!isset($arParams['YOUTUBE_PARAMETERS'])) {
	$arParams['YOUTUBE_PARAMETERS'] = '';
}

if('Y' == $arParams['DEMOSWITCHER']) {
	$f = 1;
	foreach ($arResult['ITEMS'] as &$arItem) {
		if (empty($arItem['PREVIEW_PICTURE']['SRC']) && !empty($arItem['DETAIL_PICTURE']['SRC'])
			|| $arItem['PROPERTIES']['DEMOSWITCHER']['VALUE'] == 'Y') {

			if($arItem['PROPERTIES']['DEMOSWITCHER']['VALUE'] == 'Y') {
				if (!empty($arItem['PREVIEW_PICTURE']['SRC']) && empty($arItem['DETAIL_PICTURE']['SRC'])) {
					$arItem['DETAIL_PICTURE'] = $arItem['PREVIEW_PICTURE'];
				}
			}
			$arItem['PREVIEW_PICTURE'] = $arItem['DETAIL_PICTURE'];
			$arItem['PROPERTIES']['VIDEO_FULL_WIDTH']['VALUE'] = true;
			if ($f) {
				$prop = number_format($arItem['PREVIEW_PICTURE']['HEIGHT'] / $arItem['PREVIEW_PICTURE']['WIDTH'] * 100, 2);
				$arParams['bs_height'] = $prop . '%';
				$f = 0;
			}
		}
	}
	unset($arItem);
}