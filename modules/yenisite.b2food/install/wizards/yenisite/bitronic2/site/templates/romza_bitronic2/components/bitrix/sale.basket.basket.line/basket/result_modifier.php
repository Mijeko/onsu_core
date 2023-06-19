<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader;
global $rz_b2_options;
$totalSum = 0;
$totalCount = 0;
$currency = false;
$bDifferentCurrency = false;
CModule::IncludeModule("iblock");
$arParams['SHOW_ARTICUL'] = ($rz_b2_options["block_show_article"] !== 'N');
$arParams['SHOW_ONECLICK'] = ($rz_b2_options["block_show_oneclick"] !== "N" && CModule::IncludeModule("yenisite.oneclick"));
//update parameters to set needed currency (only after CPHPCache check, changes through cookies)
if ($rz_b2_options['convert_currency']) {
	$arParams['CURRENCY_ID'] = $rz_b2_options['active-currency'];
}

if(intval($arParams['RESIZER_BASKET_ICON']) <= 0)
{
	$arParams['RESIZER_BASKET_ICON'] = 6;
}
$bConvertCurrency = ($arParams['CONVERT_CURRENCY'] == 'Y' && !empty($arParams['CURRENCY_ID']) && $item['CURRENCY'] != $arParams['CURRENCY_ID']);
$siteCurrency = CSaleLang::GetLangCurrency(SITE_ID);


if ($arParams['SHOW_ARTICUL'] !== 'N' && Loader::IncludeModule('yenisite.core')) {
	$catalogParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
}

// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
if(!empty($arResult["CATEGORIES"]["READY"]))
{
	//get item measure_ratios
	$arResult["CATEGORIES"]["READY"] = getRatio($arResult["CATEGORIES"]["READY"]);

	foreach($arResult["CATEGORIES"]["READY"] as $key=>$item){
		if($bConvertCurrency)
		{
			$item["PRICE"] = CCurrencyRates::ConvertCurrency($item["PRICE"], $item['CURRENCY'], $arParams['CURRENCY_ID']);
			$item["DISCOUNT_PRICE"] = CCurrencyRates::ConvertCurrency($item["DISCOUNT_PRICE"], $item['CURRENCY'], $arParams['CURRENCY_ID']);
			$item['CURRENCY'] = $arParams['CURRENCY_ID'];
		}
		// if ($item["SUBSCRIBE"] == "Y")
		// {
			// unset($arResult["CATEGORIES"]["READY"][$key]);
			// continue;
		// }
		if ($item["CAN_BUY"] == "Y"){
			$totalSum += $item["QUANTITY"] * $item["PRICE"];
			$totalCount++;

			if($currency && $currency!=$item["CURRENCY"])
			{
				$bDifferentCurrency = true;
			}
			$currency = $item["CURRENCY"];
		}
		$arResult["CATEGORIES"]["READY"][$key]['MAIN_PHOTO'] = CRZBitronic2CatalogUtils::getElementPictureById($item["PRODUCT_ID"], $arParams['RESIZER_BASKET_ICON']);
		
		$arResult["CATEGORIES"]["READY"][$key]['PRICE_FMT'] = CRZBitronic2CatalogUtils::getElementPriceFormat($item['CURRENCY'], $item["PRICE"], $item['PRICE_FMT']);
		$arResult["CATEGORIES"]["READY"][$key]['FULL_PRICE'] = CRZBitronic2CatalogUtils::getElementPriceFormat($item['CURRENCY'], $item["PRICE"]+$item["DISCOUNT_PRICE"], $item['FULL_PRICE']);
		
		//props
		$arResult["CATEGORIES"]["READY"][$key]['PROPS'] = array();
		$basketIds[$item['ID']] = &$arResult["CATEGORIES"]["READY"][$key];

		//articul
		if (!empty($catalogParams['ARTICUL_PROP'])) {
			$cacheDir = '/bitronic2/basket/';
			$obCache = new CPHPCache;
			if ($obCache->InitCache(86400, $item['PRODUCT_ID'], $cacheDir)) {
				$vars = $obCache->GetVars();
				$arResult['CATEGORIES']['READY'][$key]['ARTICUL'] = $vars['articul'];
			} else {
				$arProp = CIBlockElement::GetProperty(CIBlockElement::GetIBlockByID($item['PRODUCT_ID']), $item['PRODUCT_ID'], array(), array('CODE' => $catalogParams['ARTICUL_PROP']))->GetNext();
				$arResult['CATEGORIES']['READY'][$key]['ARTICUL'] = $arProp['VALUE'];
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
		$arResult['CATEGORIES']['READY'][$key]['MEASURE_RATIO'] = $item['MEASURE_RATIO'];
	}
	
	$propsIterator = CSaleBasket::GetPropsList(
		array('BASKET_ID' => 'ASC', 'SORT' => 'ASC', 'ID' => 'ASC'),
		array('BASKET_ID' => array_keys($basketIds))
	);
	while ($property = $propsIterator->GetNext())
	{
		$property['CODE'] = (string)$property['CODE'];
		if ($property['CODE'] == 'CATALOG.XML_ID' || $property['CODE'] == 'PRODUCT.XML_ID')
			continue;
		if (!isset($basketIds[$property['BASKET_ID']]))
			continue;
		$basketIds[$property['BASKET_ID']]['PROPS'][] = $property;
	}
	unset($property, $propsIterator, $basketIds);
}
else
{
	$currency = $bConvertCurrency ? $arParams['CURRENCY_ID'] : $siteCurrency;
}

if($bDifferentCurrency)
{
	$arResult["TOTAL_SUM_FORMATTED"] =  $arResult["TOTAL_PRICE"];
	$arResult["TOTAL_SUM"] = (float)(str_replace(' ','',$arResult["TOTAL_PRICE"]));
}
else
{
	$arResult["TOTAL_SUM_FORMATTED"] =  CRZBitronic2CatalogUtils::getElementPriceFormat($currency, $totalSum, $arResult["TOTAL_PRICE"]);
	$arResult["TOTAL_SUM"] = $totalSum;
}
$arResult["TOTAL_COUNT"] = $totalCount;

$arResult['CURRENCIES'] = CRZBitronic2CatalogUtils::getCurrencyArray();
CJSCore::Init(Array('currency'));?>
<script type="text/javascript">
	BX.Currency.setCurrencies(<?=CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true)?>);
	RZB2.ajax.BasketSmall.basketCurrency = <?=CUtil::PhpToJSObject($currency)?>;
	RZB2.ajax.BasketSmall.addType = <?=CUtil::PhpToJSObject($rz_b2_options['addbasket_type'])?>;
</script>
<?
if (empty($arResult['CATEGORIES']['READY']) && !empty($arResult['CATEGORIES']['DELAY'])){
    $arResult["TOTAL_COUNT"] = count($arResult['CATEGORIES']['DELAY']);
    $arResult["GO_TO_BASKET"] = true;
}
?>