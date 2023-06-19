<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader;
global $rz_b2_options;
$totalSum = 0;
$totalCount = 0;
CModule::IncludeModule("iblock");

if (empty($arParams['CURRENCY_ID'])) {
	$arParams['CURRENCY_ID'] = 'RUB';
}

if(intval($arParams['RESIZER_BASKET_ICON']) <= 0)
{
	$arParams['RESIZER_BASKET_ICON'] = 6;
}
$bConvertCurrency = ($arParams['CONVERT_CURRENCY'] == 'Y' && !empty($arParams['CURRENCY_ID']) && $item['CURRENCY'] != $arParams['CURRENCY_ID']);
$siteCurrency = $arParams['CURRENCY_ID'];

if ($arParams['SHOW_ARTICUL'] !== 'N' && Loader::IncludeModule('yenisite.core')) {
	$catalogParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
}

if (is_array($arResult['ITEMS'])) {
	foreach($arResult["ITEMS"] as $key=>&$item){
		$item["CAN_BUY"] = "Y";
		$item["DELAY"] = "N";
		$item["QUANTITY"] = $item['COUNT'];
		$item['PRICE'] = $item["MIN_PRICE"];
		$item['PRODUCT_ID'] = $item['ID'];

		//$res = CIBlockElement::GetByID($item["PRODUCT_ID"]);

		if(is_array($item['FIELDS'])){
			$arResult["ITEMS"][$key]["NAME"] = $item['FIELDS']['NAME'];
			$arResult["ITEMS"][$key]["DETAIL_PAGE_URL"] = $item['FIELDS']['DETAIL_PAGE_URL'];

			$arProduct = CMarketCatalogProduct::GetByID($item['FIELDS']['ID'], $item['FIELDS']['IBLOCK_ID']);
			if($arProduct['QUANTITY_TRACE'] == 'Y' && $arProduct['CAN_BUY_ZERO'] != 'Y' && $arProduct['QUANTITY'] <= 0) {
				$item['CAN_BUY'] = 'N';
			}
		}

		$item['MAIN_PHOTO'] = CRZBitronic2CatalogUtils::getElementPictureById($item["PRODUCT_ID"], $arParams['RESIZER_BASKET_ICON']);
		$item['PRICE_FMT'] = CRZBitronic2CatalogUtils::getElementPriceFormat($item['CURRENCY'], $item["PRICE"], $item['PRICE_FMT']);
		$item['FULL_PRICE'] = $item['PRICE_FMT'];

		//articul
		if (!empty($catalogParams['ARTICUL_PROP'])) {
			$cacheDir = '/bitronic2/basket/';
			$obCache = new CPHPCache;
			if ($obCache->InitCache(86400, $item['PRODUCT_ID'], $cacheDir)) {
				$vars = $obCache->GetVars();
				$item['ARTICUL'] = $vars['articul'];
			} else {
				$arProp = CIBlockElement::GetProperty($catalogParams['IBLOCK_ID'], $item['PRODUCT_ID'], array(), array('CODE' => $catalogParams['ARTICUL_PROP']))->GetNext();
				$item['ARTICUL'] = $arProp['VALUE'];
				if ($obCache->StartDataCache()) {
					if (defined('BX_COMP_MANAGED_CACHE')) {
						global $CACHE_MANAGER;
						$CACHE_MANAGER->StartTagCache($cacheDir);
						CIBlock::registerWithTagCache($catalogParams['IBLOCK_ID']);
						$CACHE_MANAGER->EndTagCache();
					}
					$obCache->EndDataCache(array(
						"articul" => $arProp['VALUE']
						));
				}
			}
		}
		
		//ratio
		if (!isset($item['MEASURE_RATIO'])) $item['MEASURE_RATIO'] = 1;
		if (0 >=   $item['MEASURE_RATIO'])  $item['MEASURE_RATIO'] = 1;
	}
	unset($item);
}

$arResult["TOTAL_SUM"] = $arResult['COMMON_PRICE'];
$arResult["TOTAL_SUM_FORMATTED"] = CRZBitronic2CatalogUtils::getElementPriceFormat($siteCurrency, $arResult['COMMON_PRICE'], $arResult['COMMON_PRICE']);
$arResult["TOTAL_COUNT"] = $arResult['COMMON_COUNT'];
$arResult['PRODUCT(S)'] = ToLower(\Yenisite\Core\Tools::rusQuantity($arResult['TOTAL_COUNT'], GetMessage('BITRONIC2_BASKET_GOOD')));

$arResult['CURRENCIES'] = array($siteCurrency);

if (IsModuleInstalled('currency')) {
	$arResult['CURRENCIES'] = CRZBitronic2CatalogUtils::getCurrencyArray();
	CJSCore::Init(Array('currency'));
}?>
<script type="text/javascript">
	if(typeof BX.Currency == "object") {
		BX.Currency.setCurrencies(<?=CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true)?>);
	}
	RZB2.ajax.BasketSmall.basketCurrency = <?=CUtil::PhpToJSObject($siteCurrency)?>;
	RZB2.ajax.BasketSmall.addType = <?=CUtil::PhpToJSObject($rz_b2_options['addbasket_type'])?>;
</script>
<?
?>