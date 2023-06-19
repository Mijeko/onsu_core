<?

if (!\Bitrix\Main\Loader::includeModule('yenisite.catchbuy')) {
	die('RZ_ERROR_CATCHBUY_MODULE_OT_INSTALLED');
}
global $APPLICATION;

if (empty($arParams['FILTER_NAME'])) {
	$arParams['FILTER_NAME'] = 'arrCatchbuyList';
}
$arParams['BY_LINK'] = 'Y';
$globalNameConvert = empty($arParams['CONVERT_GLOBAL_NAME']) ? 'rz_b2_options' : $arParams['CONVERT_GLOBAL_NAME'];

global ${$globalNameConvert};
$arGlobalConvert = &${$globalNameConvert};
if (isset($arGlobalConvert['currency-switcher'])
	&& $arGlobalConvert['currency-switcher'] == 'Y'
	&& isset($arGlobalConvert['active-currency'])
) {
	$arParams['CONVERT_CURRENCY'] = 'Y';
	$arParams['CURRENCY_ID'] = $arGlobalConvert['active-currency'];
}

$arParams['CATALOG_TEMPLATE'] = empty($arParams['CATALOG_TEMPLATE']) ? 'catchbuy_list' : $arParams['CATALOG_TEMPLATE'];
unset($arParams['CONVERT_GLOBAL_NAME']);

global ${$arParams['FILTER_NAME']};
$arrFilter = &${$arParams['FILTER_NAME']};
$arrFilter = array();

$arProductID = array();
$rs = \Yenisite\Catchbuy\Catchbuy::getList(
	array(
		'filter' => array(
			'ACTIVE' => 'Y',
			'LID' => SITE_ID
		)
	)
);
$arCatchBuy = array();
while ($ar = $rs->fetch()) {
	$arProductID[] = $ar['PRODUCT_ID'];
	$arCatchBuy[$ar['PRODUCT_ID']] = $ar;
}

if (!empty($arProductID)) {
    $arrFilter['ID'] = $arProductID;
    $arParams['IBLOCK_ID'] = CIBlockElement::GetIBlockByID(reset($arProductID));
} else{
    $rsIblocks = CIBlock::GetList(array(), array('SITE_ID' => SITE_ID, 'TYPE' => '%catalog'), false);
    $arIblock = $rsIblocks->Fetch();
    $arParams['IBLOCK_ID'] = $arIblock['ID'];
    $arrFilter['=ACTIVE'] = 'N';
}

if(isset($arGlobalConvert['DEMO_CONTENT']['CATALOG'])) {
	$arParams['IBLOCK_ID'] = $arGlobalConvert['DEMO_CONTENT']['CATALOG'];
}

$arParams['CATCHBUY'] = $arCatchBuy;
$arParams['HIDE_NOT_AVAILABLE'] = 'Y';
$this->IncludeComponentTemplate('template');