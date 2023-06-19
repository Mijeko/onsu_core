<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (isset($_REQUEST[$arParams['ACTION_VARIABLE']]))
{
	switch ($_REQUEST[$arParams['ACTION_VARIABLE']])
	{
		case 'DELETE_ALL_COMPARE_LIST':
			unset($_SESSION[$arParams["NAME"]][$arParams["IBLOCK_ID"]]["ITEMS"]);
			$arResult = array();
	}
}
if(intval($arParams['RESIZER_SET_COMPARE']) <= 0)
{
	$arParams['RESIZER_SET_COMPARE'] = 6;
}

$arParams['COMPARE_LIST'] = array();

foreach($arResult as $key => $arItem)
{
	$arParams['COMPARE_LIST'][$key] = $key;
}
?>