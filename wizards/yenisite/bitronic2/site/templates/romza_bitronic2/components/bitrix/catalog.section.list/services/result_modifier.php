<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arDefaultParams = array(
	'SHOW_PARENT_NAME' => 'N',
	'HIDE_SECTION_NAME' => 'N'
);

$arParams = array_merge($arDefaultParams, $arParams);
$arFilter =& $arParams['SECTION_FILTER'];

if ('N' != $arParams['SHOW_PARENT_NAME'])
	$arParams['SHOW_PARENT_NAME'] = 'Y';
if ('Y' != $arParams['HIDE_SECTION_NAME'])
	$arParams['HIDE_SECTION_NAME'] = 'N';

if (0 < $arResult['SECTIONS_COUNT']) {
	foreach ($arResult['SECTIONS'] as $key => $arSection) {
		if (!empty($arFilter)) {
			if (in_array($arSection['ID'], $arFilter)) continue;
		} else {
			if (0 < $arSection['ELEMENT_CNT']) continue;
		}

		unset($arResult['SECTIONS'][$key]);
		$arResult['SECTIONS_COUNT']--;
	}
}
unset($arFilter);
