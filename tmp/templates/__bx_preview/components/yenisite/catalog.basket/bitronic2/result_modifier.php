<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Loader;
use Bitrix\Currency\CurrencyTable;

if($arParams['INCLUDE_JQUERY'] != 'N')
	CJSCore::Init(array("jquery"));
	
CModule::IncludeModule('yenisite.apparellite');

$catalogParams = array();
if (Loader::IncludeModule('yenisite.core')) {
	$catalogParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
}

if (array_key_exists('ITEMS', $arResult) && is_array($arResult['ITEMS']))
foreach ($arResult["ITEMS"] as $k => &$arItem) {
	$arItem["CAN_BUY"] = "Y";
	$arItem['PRODUCT_ID'] = $arItem['ID'];
	$arItem['PRODUCT_PICTURE_SRC'] = CRZBitronic2CatalogUtils::getElementPictureById($arItem['PRODUCT_ID'], $arParams['RESIZER_BASKET_PHOTO']);
	$arItem['SUM_NOT_FORMATED'] = $arItem['COUNT'] * ((intval($arItem['DISCOUNT_PRICE']) > 0) ? $arItem['DISCOUNT_PRICE'] : $arItem['MIN_PRICE']);
	//articul
	if (!empty($arResult['PROPERTIES'][$arItem['ID']][$catalogParams['ARTICUL_PROP']]['VALUE'])) {
		$arItem['ARTICUL'] = htmlspecialcharsbx($arResult['PROPERTIES'][$arItem['ID']][$catalogParams['ARTICUL_PROP']]['VALUE']);
	}
}
unset($arItem);

$arResult['CURRENCIES'] = array();

if (IsModuleInstalled('currency')) {
	Loader::includeModule('currency');
	$currencyIterator = CurrencyTable::getList(array(
		'select' => array('CURRENCY')
	));
	while ($currency = $currencyIterator->fetch())
	{
		$currencyFormat = CCurrencyLang::GetFormatDescription($currency['CURRENCY']);
		$arResult['CURRENCIES'][] = array(
			'CURRENCY' => $currency['CURRENCY'],
			'FORMAT' => array(
				'FORMAT_STRING' => $currencyFormat['FORMAT_STRING'],
				'DEC_POINT' => $currencyFormat['DEC_POINT'],
				'THOUSANDS_SEP' => $currencyFormat['THOUSANDS_SEP'],
				'DECIMALS' => $currencyFormat['DECIMALS'],
				'THOUSANDS_VARIANT' => $currencyFormat['THOUSANDS_VARIANT'],
				'HIDE_ZERO' => $currencyFormat['HIDE_ZERO']
			)
		);
	}
	unset($currencyFormat, $currency, $currencyIterator);
}
?>