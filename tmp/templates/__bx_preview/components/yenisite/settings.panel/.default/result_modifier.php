<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

foreach($arResult['SETTINGS'] as $key => $arItem)
{
	if(!isset($arItem['group']) || empty($arItem['group']))
	{
		$arItem['group'] = 'general';
	}
	
	$arResult['GROUPS'][$arItem['group']]['SETTINGS'][] = $key;
	$arResult['SETTINGS'][$key]['CODE'] = $key;
}

foreach($arResult['GROUPS'] as $key => $arItem)
{
	// delete empty groups
	if(count(array_intersect($arItem['SETTINGS'], $arParams["EDIT_SETTINGS"])) <= 0)
	{
		unset($arResult['GROUPS'][$key]);
	}
}