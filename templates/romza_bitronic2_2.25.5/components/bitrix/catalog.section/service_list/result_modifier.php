<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

foreach ($arResult['ITEMS'] as &$arItem) {
	$arItem['PRINT_PRICE'] = false;
	if (is_array($arItem['MIN_PRICE']) && !empty($arItem['MIN_PRICE']['VALUE'])) {
		$arItem['PRINT_PRICE'] = CRZBitronic2CatalogUtils::getElementPriceFormat(
			$arItem['MIN_PRICE']['CURRENCY'],
			$arItem['MIN_PRICE']['DISCOUNT_VALUE'],
			$arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']
		);
	} elseif (!empty($arItem["DISPLAY_PROPERTIES"]["PRICE"])) {
		$arItem['PRINT_PRICE'] = $arItem["DISPLAY_PROPERTIES"]["PRICE"]["VALUE"];
	}
}
if (isset($arItem)) {
	unset($arItem);
}
