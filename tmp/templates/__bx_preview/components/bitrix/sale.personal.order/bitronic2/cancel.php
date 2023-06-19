<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<div class="account-order-page">
	<?
	$arDetParams = array(
		"PATH_TO_LIST" => $arResult["PATH_TO_LIST"],
		"PATH_TO_DETAIL" => $arResult["PATH_TO_DETAIL"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"ID" => $arResult["VARIABLES"]["ID"],

		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],

		"CUSTOM_SELECT_PROPS" => $arParams["CUSTOM_SELECT_PROPS"],
		"RESIZER_BASKET_ICON" => $arParams["RESIZER_BASKET_ICON"],
		"HIDE_PRICE_TYPE" => $arParams["HIDE_PRICE_TYPE"]
	);
	foreach ($arParams as $key => $val) {
		if (strpos($key, "PROP_") !== false)
			$arDetParams[$key] = $val;
	}
	$APPLICATION->IncludeComponent(
		"bitrix:sale.personal.order.cancel",
		"",
		$arDetParams,
		$component
	);
	?>
</div>