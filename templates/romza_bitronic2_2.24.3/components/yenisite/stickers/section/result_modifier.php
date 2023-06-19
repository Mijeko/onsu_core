<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arResult['CUSTOM'] = array();

if (is_array($arParams['CUSTOM_STICKERS']['VALUE'])) {
	foreach ($arParams['CUSTOM_STICKERS']['VALUE'] as $key => $value) {
		$arResult['CUSTOM'][] = array(
			'CLASS' => $arParams['CUSTOM_STICKERS']['VALUE_XML_ID'][$key],
			'TEXT' => $value
		);
	}
} elseif (!empty($arParams['CUSTOM_STICKERS']['VALUE'])) {
	$arResult['CUSTOM'][0] = array(
		'CLASS' => $arParams['CUSTOM_STICKERS']['VALUE_XML_ID'],
		'TEXT'  => $arParams['CUSTOM_STICKERS']['VALUE']
	);
}
