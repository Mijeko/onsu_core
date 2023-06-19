<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (empty($arResult))
	return;

$arParams['SHOW_ICONS'] = ($arParams['SHOW_ICONS'] !== 'N');

$arAllItems = array();
$arMenuItemsIDs = array();
$bResizer = CModule::IncludeModule('yenisite.resizer2') && $arParams['SHOW_ICONS'];

foreach($arResult as $key=>$arItem)
{
	if($arItem["DEPTH_LEVEL"] > $arParams["MAX_LEVEL"])
	{
		unset($arResult[$key]);
		continue;
	}
	
	$arItem["PARAMS"]["ITEM_IBLOCK_ID"] = $arItem["PARAMS"]["ITEM_IBLOCK_ID"] ? : "static_".++$i;

	if ($bResizer && !empty($arItem['PARAMS']['PICTURE'])) {
		if (@file_exists($_SERVER['DOCUMENT_ROOT'].$arItem['PARAMS']['PICTURE'])) {
			$arItem['PARAMS']['PICTURE'] = CResizer2Resize::ResizeGD2($arItem['PARAMS']['PICTURE'], $arParams['ICON_RESIZER_SET']);
		} else {
			$arItem['PARAMS']['PICTURE'] = false;
		}
	}

	if ($arItem["DEPTH_LEVEL"] == "1")
	{
		$arMenuItemsIDs[$arItem["PARAMS"]["ITEM_IBLOCK_ID"]] = array();
		if ($arItem["IS_PARENT"])
		{
			$curItemLevel_1 = $arItem["PARAMS"]["ITEM_IBLOCK_ID"];
		}
		$arAllItems[$arItem["PARAMS"]["ITEM_IBLOCK_ID"]] = $arItem;
	}
	elseif($arItem["DEPTH_LEVEL"] == "2")
	{
		$arMenuItemsIDs[$curItemLevel_1][$arItem["PARAMS"]["ITEM_IBLOCK_ID"]] = array();
		if ($arItem["IS_PARENT"])
		{
			$curItemLevel_2 = $arItem["PARAMS"]["ITEM_IBLOCK_ID"];
		}
		$arAllItems[$arItem["PARAMS"]["ITEM_IBLOCK_ID"]] = $arItem;
	}
	elseif($arItem["DEPTH_LEVEL"] == "3")
	{
		$arMenuItemsIDs[$curItemLevel_1][$curItemLevel_2][] = $arItem["PARAMS"]["ITEM_IBLOCK_ID"];
		$arAllItems[$arItem["PARAMS"]["ITEM_IBLOCK_ID"]] = $arItem;
	}
}
// echo "<pre style='text-align:left;'>";print_r($arAllItems);echo "</pre>";
$arResult = array();
$arResult["ALL_ITEMS"] = $arAllItems;
$arResult["ALL_ITEMS_ID"] = $arMenuItemsIDs;