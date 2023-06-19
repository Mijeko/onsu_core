<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 

if (empty($arResult['SECTIONS']))
	return;

$arParams['SHOW_ICONS'] = ($arParams['SHOW_ICONS'] !== 'N');

if(intval($arParams['RESIZER_SECTION_LVL0']) <= 0 )
{
	$arParams['RESIZER_SECTION_LVL0'] = 6;
}
	
$arMenuItemsIDs = array();
$arAllItems = array();
$bResizer = Bitrix\Main\Loader::IncludeModule("yenisite.resizer2");
foreach($arResult['SECTIONS'] as $key=>$arItem)
{
	if (isset($arResult['SECTIONS'][$key+1]["DEPTH_LEVEL"]))
	{
		$arItem["IS_PARENT"] = $arItem["DEPTH_LEVEL"] < $arResult['SECTIONS'][$key+1]["DEPTH_LEVEL"];
	}
	
	if($bResizer && !empty($arItem["PICTURE"]['SRC']))
	{
		$arItem["PICTURE"]['SRC_OLD'] = $arItem["PICTURE"]['SRC'];
		$arItem["PICTURE"]['SRC'] = CResizer2Resize::ResizeGD2($arItem["PICTURE"]['SRC'], $arParams['RESIZER_SECTION_LVL0']);
	}
	if($arItem["DEPTH_LEVEL"] > $arParams["TOP_DEPTH"])
	{
		unset($arResult['SECTIONS'][$key]);
		continue;
	}

	if ($arItem["DEPTH_LEVEL"] == "1")
	{
		$arMenuItemsIDs[$arItem['ID']] = array();
		if ($arItem["IS_PARENT"])
		{
			$curItemLevel_1 = $arItem['ID'];
		}
		$arAllItems[$arItem["ID"]] = $arItem;
	}
	elseif($arItem["DEPTH_LEVEL"] == "2")
	{
		$arMenuItemsIDs[$curItemLevel_1][$arItem['ID']] = array();
		if ($arItem["IS_PARENT"])
		{
			$curItemLevel_2 = $arItem['ID'];
		}
		$arAllItems[$arItem['ID']] = $arItem;
	}
	elseif($arItem["DEPTH_LEVEL"] == "3")
	{
		$arMenuItemsIDs[$curItemLevel_1][$curItemLevel_2][] = $arItem['ID'];
		$arAllItems[$arItem['ID']] = $arItem;
	}
}

$arResult = array();
$arResult["ALL_ITEMS"] = $arAllItems;
$arResult["ALL_ITEMS_ID"] = $arMenuItemsIDs;
