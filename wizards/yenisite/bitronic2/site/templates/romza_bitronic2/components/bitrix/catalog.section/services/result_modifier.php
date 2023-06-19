<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */

foreach($arResult['ITEMS'] as &$arItem) {
	$arItem['HELP'] = empty($arItem['DETAIL_TEXT']) ? $arItem['~PREVIEW_TEXT'] : $arItem['~DETAIL_TEXT'];
	$arItem['HELP'] = htmlspecialcharsBx($arItem['HELP']);
}
