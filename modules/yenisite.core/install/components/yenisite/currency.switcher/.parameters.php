<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (CModule::IncludeModule('currency') || CModule::IncludeModule('sale')) {
	$arCurrencyList = array();
	$db_cur = CCurrency::GetList(($by="sort"), ($order="asc"));
	while($arCur = $db_cur->Fetch())
	{
		$arCurrencyList[$arCur['CURRENCY']] = $arCur['CURRENCY'];
		if ($arCur['BASE'] === 'Y') {
			$baseCurrency = $arCur['CURRENCY'];
		}
	}
} else {
	$arCurrencyList = array('RUB' => 'RUB');
	$baseCurrency = 'RUB';
}

$arChooseList = array('BASE' => GetMessage('BASE_CURRENCY') . ' (' . $baseCurrency . ')');

if (is_array($arCurrentValues) && !empty($arCurrentValues['CURRENCY_LIST'])) {;
    $arCurList = array();
    foreach ($arCurrentValues['CURRENCY_LIST'] as  $currency){
        $arCurList[$currency] = $arCurrencyList[$currency];
    }
	$arChooseList += $arCurList;
} else {
	$arChooseList += $arCurrencyList;
}

$arComponentParameters = array(
	"PARAMETERS" => array(
		"CURRENCY_LIST" => array(
			"PARENT"    => "BASE",
			"NAME"      => GetMessage("CURRENCY_LIST"),
			"TYPE"      => "LIST",
			"MULTIPLE"  => "Y",
			"REFRESH"   => "Y",
			"SIZE"      => count($arCurrencyList),
			"VALUES"    => $arCurrencyList
		),
		"DEFAULT_CURRENCY" => array(
			"PARENT"    => "BASE",
			"NAME"      => GetMessage("DEFAULT_CURRENCY"),
			"TYPE"      => "LIST",
			"DEFAULT"   => "BASE",
			"VALUES"    => $arChooseList
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>360000),
	)
);

if (IsModuleInstalled('yenisite.geoipstore')) {
	$arComponentParameters["PARAMETERS"]["GEOIP_CURRENCY"] = array(
		"PARENT"  => "BASE",
		"NAME"    => GetMessage("GEOIP_CURRENCY"),
		"TYPE"    => "STRING",
		"DEFAULT" => "={IsModuleInstalled(\"yenisite.geoipstore\") ? \$rz_options[\"GEOIP\"][\"CURRENCY_ID\"] : false}",
	);
}
?>