<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

CModule::IncludeModule('yenisite.resizer2');

foreach ($arResult["rows"] as &$arItem) {
	if (empty($arItem['UF_FILE'])) continue;
	$match = array();
	preg_match('#<img .*src="([^"]+)".*width="([^"]+)".*height="([^"]+)".*#', $arItem['UF_FILE'], $match);
	if (empty($match[1])) continue;
	$arSet = CResizer2Set::GetById($arParams["RESIZER_SET"]);
	if (intval($match[2]) <= $arSet['w']
	&&  intval($match[3]) <= $arSet['h']) continue;
	$arItem['UF_FILE'] = '<img src="' . CResizer2Resize::ResizeGD2($match[1], $arParams["RESIZER_SET"]) . '" alt="' . $arItem['UF_NAME'] . '" title="' . $arItem['UF_NAME'] . '" />';
}
if (isset($arItem)) {
	unset($arItem);
}
