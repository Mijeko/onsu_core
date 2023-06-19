<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
		
$arResult["ELEMENT"]["PICTURE_PRINT"]['SRC'] = CRZBitronic2CatalogUtils::getElementPictureById($arResult["ELEMENT"]['ID'], $arParams['RESIZER_SET_CONTRUCTOR']);
$arResult["ELEMENT"]["PRICES"] = CIBlockPriceTools::GetItemPrices($arParams["IBLOCK_ID"], $arResult["PRICES"], $arResult["ELEMENT"], $arParams['PRICE_VAT_INCLUDE'], $arResult['CONVERT_CURRENCY']);
$arResult["ELEMENT"] = CRZBitronic2CatalogUtils::processItemCommon($arResult["ELEMENT"]);
CRZBitronic2CatalogUtils::catalogSetConstruction($arResult["ELEMENT"]);

$arParams['HIDE_ITEMS_ZER_PRICE'] = true;
$arParams['HIDE_ITEMS_NOT_AVAILABLE'] = true;
$arResult["SET_ITEMS"]["PRICE_NOT_FORMATED"] =
$arResult["SET_ITEMS"]["OLD_PRICE_NOT_FORMATED"] =
$arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE_NOT_FORMATED"] = 0;

$arResult["SET_ITEMS"]["PRICE_NOT_FORMATED"] += $arResult["ELEMENT"]["PRICE_DISCOUNT_VALUE"];
$arResult["SET_ITEMS"]["OLD_PRICE_NOT_FORMATED"] += $arResult["ELEMENT"]["PRICE_VALUE"];
$arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE_NOT_FORMATED"] += $arResult["ELEMENT"]["PRICE_DISCOUNT_DIFFERENCE_VALUE"];

$arDefaultSetIDs = array($arResult["ELEMENT"]["ID"]);

foreach (array("DEFAULT", "OTHER") as $type)
{
	foreach ($arResult["SET_ITEMS"][$type] as $key=>$arItem)
	{
		$arItem['PRICES'] = CIBlockPriceTools::GetItemPrices($arParams["IBLOCK_ID"], $arResult["PRICES"], $arItem, $arParams['PRICE_VAT_INCLUDE'], $arResult['CONVERT_CURRENCY']);

		CRZBitronic2CatalogUtils::setOffersPropsCodes($arItem,$arParams);
		$arItem = CRZBitronic2CatalogUtils::processItemCommon($arItem);

        if (CRZBitronic2CatalogUtils::checkAvPrFotoForElement($arItem, $arParams)){
            unset($arResult["SET_ITEMS"][$type][$key]);
            continue;
        }

		CRZBitronic2CatalogUtils::catalogSetConstruction($arItem);
		$arItem["PICTURE_PRINT"]['SRC'] = CRZBitronic2CatalogUtils::getElementPictureById($arItem['ID'], $arParams['RESIZER_SET_CONTRUCTOR']);
	
		if ($type == "DEFAULT")
		{
			$arDefaultSetIDs[] = $arItem["ID"];
			$arResult["SET_ITEMS"]["PRICE_NOT_FORMATED"] += $arItem["PRICE_DISCOUNT_VALUE"];
			$arResult["SET_ITEMS"]["OLD_PRICE_NOT_FORMATED"] += $arItem["PRICE_VALUE"];
			$arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE_NOT_FORMATED"] += $arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"];
		}

		$arResult["SET_ITEMS"][$type][$key] = $arItem;		
	}
}
CRZBitronic2CatalogUtils::setOffersPropsCodes($arResult["ELEMENT"],$arParams);

$arResult["DEFAULT_SET_IDS"] = $arDefaultSetIDs;