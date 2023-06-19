<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die(); 

if(intval($arParams['RESIZER_SECTION_ICON']) <= 0 )
{
	$arParams['RESIZER_SECTION_ICON'] = 6;
	$arParams['RESIZER_SECTION_LARGE'] = 4;
	$arParams['RESIZER_SECTION_BIG'] = 2;
}

$arParams['PROP_OF_BIG_IMG'] = $arParams['PROP_OF_BIG_IMG'] ? : 'UF_IMG_BLOCK_FOTO';

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
        $smallImg = $arItem["PICTURE"]['SRC'];
        $arItem["PICTURE"]['SRC'] = CResizer2Resize::ResizeGD2($smallImg, $arParams['RESIZER_SECTION_ICON']);
    }

    if ($bResizer && !empty($arItem[$arParams['PROP_OF_BIG_IMG']])){
        $bigImg = CFile::GetPath($arItem[$arParams['PROP_OF_BIG_IMG']]);
        $arItem["PICTURE_BIG_IMG"]['SRC'] = CResizer2Resize::ResizeGD2($bigImg, $arParams['RESIZER_SECTION_BIG']);
    }
    if ($bResizer && !empty($arItem["DETAIL_PICTURE"])){
        $largeImg = CFile::GetPath($arItem['DETAIL_PICTURE']);
        $arItem["PICTURE_LARGE"]['SRC'] = CResizer2Resize::ResizeGD2($largeImg, $arParams['RESIZER_SECTION_LARGE']);
    }

    $arItem["PICTURE"]['SRC'] = $arItem["PICTURE"]['SRC'] ? $arItem["PICTURE"]['SRC'] :  $arItem["PICTURE_LARGE"]['SRC'];
    $arItem["PICTURE"]['SRC'] = $arItem["PICTURE"]['SRC'] ?  $arItem["PICTURE"]['SRC'] : $arItem["PICTURE_BIG_IMG"]['SRC'];

    $arItem["PICTURE_LARGE"]['SRC'] = $arItem["PICTURE_LARGE"]['SRC'] ? $arItem["PICTURE_LARGE"]['SRC'] : $arItem["PICTURE_BIG_IMG"]['SRC'];
    $arItem["PICTURE_LARGE"]['SRC'] = $arItem["PICTURE_LARGE"]['SRC'] ? $arItem["PICTURE_LARGE"]['SRC'] : $arItem["PICTURE"]['SRC'];

$arItem["PICTURE_BIG_IMG"]['SRC'] = $arItem["PICTURE_BIG_IMG"]['SRC'] ? $arItem["PICTURE_BIG_IMG"]['SRC'] : $arItem["PICTURE_LARGE"]['SRC'];
$arItem["PICTURE_BIG_IMG"]['SRC'] = $arItem["PICTURE_BIG_IMG"]['SRC'] ? $arItem["PICTURE_BIG_IMG"]['SRC'] : $arItem["PICTURE"]['SRC'];

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
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
