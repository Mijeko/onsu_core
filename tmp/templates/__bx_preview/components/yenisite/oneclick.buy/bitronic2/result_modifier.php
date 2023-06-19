<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (is_array($arResult['FIELDS'])) {
	foreach ($arResult['FIELDS'] as $key => &$arItem) {
		if ($arItem['IS_EMAIL'] == 'Y' || $key == 'EMAIL') {
			$arItem['ICON'] = 'mail9';
			continue;
		}
		if ($arItem['CODE'] == 'PHONE' || $key == 'PHONE') {
			$arItem['ICON'] = 'phone12';
			continue;
		}
		$arItem['ICON'] = 'user12';
	}
	if (isset($arItem)) {
		unset($arItem);
	}
}

$element = CIBlockElement::GetByID($_REQUEST['id'])->GetNext();
//Получаем минимальный возможный заказ и кратность
$element_zak = CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], ['sort' => 'asc'], ['CODE' => 'MINIMALNYY_ZAKAZ'])->GetNext();
$arResult['MIN_ZAK'] = $element_zak['VALUE'];
$element_krt = CIBlockElement::GetProperty($element['IBLOCK_ID'], $element['ID'], ['sort' => 'asc'], ['CODE' => 'KRATNOST'])->GetNext();
$arResult['KRAT'] = $element_krt['VALUE'];
