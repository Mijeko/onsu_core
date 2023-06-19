<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(CModule::IncludeModule('yenisite.bitronic'))
	$sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode");

CModule::IncludeModule('yenisite.resizer2');

// FOR SKU as SelectBox
if (!empty($arResult['ITEMS']))
{
	$boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);
	if ($arResult['MODULES']['catalog'])
	{
		if (!$boolConvert)
			$strBaseCurrency = CCurrency::GetBaseCurrency();
		if(intval($arParams['IBLOCK_ID'])>0)
			$arIblock = array(intval($arParams['IBLOCK_ID']));
		elseif(is_array($arResult['FILTER_IBLOCK_ID']))
			$arIblock = $arResult['FILTER_IBLOCK_ID'];
		$arSKUPropList = array();
		$arSKUPropIDs = array();
		$arSKUPropKeys = array();
		foreach($arIblock as $iblock)
		{
			$arSKU = CCatalogSKU::GetInfoByProductIBlock($iblock);
			$boolSKU = !empty($arSKU) && is_array($arSKU);

			if ($boolSKU && !empty($arParams['OFFER_TREE_PROPS']) && 'SB' == $arParams['PRODUCT_DISPLAY_MODE'])
			{
				$arSKUPropListOne = CIBlockPriceTools::getTreeProperties(
					$arSKU,
					$arParams['OFFER_TREE_PROPS'],
					array(
						'PICT' => $arEmptyPreview,
						//'NAME' => '-'
					)
				);
					$arNeedValues = array();
					
				CIBlockPriceTools::getTreePropertyValues($arSKUPropListOne, $arNeedValues);
				$arSKUPropIDsOne = array_keys($arSKUPropListOne);
				
				if (empty($arSKUPropIDsOne))
				{
					// $arParams['PRODUCT_DISPLAY_MODE'] = 'N';
				}
				else
					$arSKUPropKeysOne = array_fill_keys($arSKUPropIDsOne, false);
					
				$arSKUPropList = array_merge($arSKUPropList, $arSKUPropListOne);
				$arSKUPropIDs = array_merge($arSKUPropIDs, $arSKUPropIDsOne);
				$arSKUPropKeys = array_merge($arSKUPropKeys, $arSKUPropKeysOne);
			}
			
		}
	}
	
	$arResult['SKU_PROPS'] = $arSKUPropList;
}

foreach($arResult["ITEMS"] as &$item) {
	$item['CATALOG_AVAILABLE'] = yenisite_CATALOG_AVAILABLE($item) ;
    if($item["DETAIL_PICTURE"]["ID"])
    	$item["PROPERTIES"]["MORE_PHOTO"]["VALUE"][] = $item["DETAIL_PICTURE"]["ID"];
	if(!CModule::IncludeModule('catalog')){
		CModule::IncludeModule('yenisite.market');
		$prices = CMarketPrice::GetItemPriceValues($item["ID"]);
		foreach($prices as $k=>$pr){
			$item["PRICES"][$k]["VALUE"] = $pr;
			$item["PRICES"][$k]["PRINT_VALUE"] = $pr." <span class='rubl'>".GetMessage('RUB')."</span>";
		}
	}

	if($sef == "Y" && !isset($arResult["URL2MAIN"]))
	{
		$url = $item["BUY_URL"];
		$tmpAr = explode('/', $url);
		$tmpAr = array_slice($tmpAr, 0, 2);
		$url = implode('/', $tmpAr);
		$arResult["URL2MAIN"] = $url.'/';
		unset($tmpAr);
	}
	
	
	// FOR SKU as SelectBox
	if ($arResult['MODULES']['catalog'])
	{
		$item['CATALOG'] = true;
		if (!isset($item['CATALOG_TYPE']))
			$item['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
		if (
			(CCatalogProduct::TYPE_PRODUCT == $item['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $item['CATALOG_TYPE'])
			&& !empty($item['OFFERS'])
		)
		{
			$item['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
		}
		switch ($item['CATALOG_TYPE'])
		{
			case CCatalogProduct::TYPE_SET:
				$item['OFFERS'] = array();
				$item['CATALOG_MEASURE_RATIO'] = 1;
				$item['CATALOG_QUANTITY'] = 0;
				$item['CHECK_QUANTITY'] = false;
				break;
			case CCatalogProduct::TYPE_SKU:
				break;
			case CCatalogProduct::TYPE_PRODUCT:
			default:
				$item['CHECK_QUANTITY'] = ('Y' == $item['CATALOG_QUANTITY_TRACE'] && 'N' == $item['CATALOG_CAN_BUY_ZERO']);
				break;
		}
	}
	else
	{
		$item['CATALOG_TYPE'] = 0;
		$item['OFFERS'] = array();
	}
	// FOR SKU as SelectBox
	if (isset($item['OFFERS']) && !empty($item['OFFERS']))
	{
		if ('SB' == $arParams['PRODUCT_DISPLAY_MODE'])
		{
			$arMatrixFields = $arSKUPropKeys;
			$arMatrix = array();

			$arNewOffers = array();
			$boolSKUDisplayProperties = false;
			$item['OFFERS_PROP'] = false;

			$arDouble = array();
			foreach ($item['OFFERS'] as $keyOffer => $arOffer)
			{
				$arOffer['ID'] = intval($arOffer['ID']);
				if (isset($arDouble[$arOffer['ID']]))
					continue;
				$arRow = array();
				foreach ($arSKUPropIDs as $propkey => $strOneCode)
				{
					$arCell = array(
						'VALUE' => 0,
						'SORT' => PHP_INT_MAX,
						'NA' => true
					);
					if (isset($arOffer['DISPLAY_PROPERTIES'][$strOneCode]))
					{
						$arMatrixFields[$strOneCode] = true;
						$arCell['NA'] = false;
						if ('directory' == $arSKUPropList[$strOneCode]['USER_TYPE'])
						{
							$intValue = $arSKUPropList[$strOneCode]['XML_MAP'][$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']];
							$arCell['VALUE'] = $intValue;
						}
						elseif ('L' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE'])
						{
							$arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE_ENUM_ID']);
						}
						elseif ('E' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE'])
						{
							$arCell['VALUE'] = intval($arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']);
						}
						$arCell['SORT'] = $arSKUPropList[$strOneCode]['VALUES'][$arCell['VALUE']]['SORT'];
					}
					$arRow[$strOneCode] = $arCell;
				}
				
				$arMatrix[$keyOffer] = $arRow;
				
				
				// $offerPictures = CIBlockPriceTools::getDoublePicturesForItem($arOffer, $arParams['OFFER_ADD_PICT_PROP']);
				$offerPictures['PICT'] = yenisite_GetPicSrc($arOffer);
				$arOffer['OWNER_PICT'] = empty($offerPictures['PICT']);
				$arOffer['PREVIEW_PICTURE'] = false;
				$arOffer['PREVIEW_PICTURE_SMALL'] = false;
				$arOffer['SECOND_PICT'] = true;
				if (!$arOffer['OWNER_PICT'])
				{
					if (empty($offerPictures['SECOND_PICT']))
						$offerPictures['SECOND_PICT'] = $offerPictures['PICT'];
					if(CModule::IncludeModule('yenisite.resizer2'))
					{
						$arOffer['PREVIEW_PICTURE'] = CResizer2Resize::ResizeGD2(CFile::GetPath($offerPictures['PICT']),$arParams['IMAGE_SET_BIG']);
						$arOffer['PREVIEW_PICTURE_SMALL'] = CResizer2Resize::ResizeGD2(CFile::GetPath($offerPictures['PICT']),$arParams['IMAGE_SET']);
					}
					else
					{
						$arOffer['PREVIEW_PICTURE'] = CFile::ResizeImageGet(CFile::GetFileArray($offerPictures['PICT']), Array("width" => 200, "height" => 200));
						
						$arOffer['PREVIEW_PICTURE_SMALL'] = CFile::ResizeImageGet(CFile::GetFileArray($offerPictures['PICT']), Array("width" => 150, "height" => 150));
						$arOffer['PREVIEW_PICTURE'] = $arOffer['PREVIEW_PICTURE']['src'];
						$arOffer['PREVIEW_PICTURE_SMALL'] = $arOffer['PREVIEW_PICTURE_SMALL']['src'];
					}
				}
				$arDouble[$arOffer['ID']] = true;
				$arNewOffers[$keyOffer] = $arOffer;
			}
			
			$item['OFFERS'] = $arNewOffers;
			
			$arUsedFields = array();
			$arSortFields = array();
	
			foreach ($arSKUPropIDs as $propkey => $strOneCode)
			{
				$boolExist = $arMatrixFields[$strOneCode];
				foreach ($arMatrix as $keyOffer => $arRow)
				{
					if ($boolExist)
					{
						if (!isset($item['OFFERS'][$keyOffer]['TREE']))
							$item['OFFERS'][$keyOffer]['TREE'] = array();
						$item['OFFERS'][$keyOffer]['TREE']['PROP_'.$arSKUPropList[$strOneCode]['ID']] = $arMatrix[$keyOffer][$strOneCode]['VALUE'];
						$item['OFFERS'][$keyOffer]['SKU_SORT_'.$strOneCode] = $arMatrix[$keyOffer][$strOneCode]['SORT'];
						$arUsedFields[$strOneCode] = true;
						$arSortFields['SKU_SORT_'.$strOneCode] = SORT_NUMERIC;
					}
					else
					{
						unset($arMatrix[$keyOffer][$strOneCode]);
					}
				}
			}
			
			$item['OFFERS_PROP'] = $arUsedFields;
			$item['OFFERS_PROP_CODES'] = (!empty($arUsedFields) ? base64_encode(serialize(array_keys($arUsedFields))) : '');

			\Bitrix\Main\Type\Collection::sortByColumn($item['OFFERS'], $arSortFields);

			$arMatrix = array();
			$intSelected = -1;
			$item['MIN_PRICE'] = false;
			foreach ($item['OFFERS'] as $keyOffer => $arOffer)
			{
				if (empty($item['MIN_PRICE']) && $arOffer['CAN_BUY'])
				{
					$intSelected = $keyOffer;
					$item['MIN_PRICE'] = (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']);
				}
				$arSKUProps = false;
				if (!empty($arOffer['DISPLAY_PROPERTIES']))
				{
					$boolSKUDisplayProperties = true;
					$arSKUProps = array();
					foreach ($arOffer['DISPLAY_PROPERTIES'] as &$arOneProp)
					{
						if ('F' == $arOneProp['PROPERTY_TYPE'])
							continue;
						$arSKUProps[] = array(
							'NAME' => $arOneProp['NAME'],
							'VALUE' => $arOneProp['DISPLAY_VALUE']
						);
					}
					unset($arOneProp);
				}

				$arOneRow = array(
					'ID' => $arOffer['ID'],
					'NAME' => $arOffer['~NAME'],
					'TREE' => $arOffer['TREE'],
					'DISPLAY_PROPERTIES' => $arSKUProps,
					'PRICE' => (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']),
					'SECOND_PICT' => $arOffer['SECOND_PICT'],
					'OWNER_PICT' => $arOffer['OWNER_PICT'],
					'PREVIEW_PICTURE' => $arOffer['PREVIEW_PICTURE'],
					'PREVIEW_PICTURE_SMALL' => $arOffer['PREVIEW_PICTURE_SMALL'],
					'CHECK_QUANTITY' => $arOffer['CHECK_QUANTITY'],
					'MAX_QUANTITY' => $arOffer['CATALOG_QUANTITY'],
					'STEP_QUANTITY' => $arOffer['CATALOG_MEASURE_RATIO'],
					'QUANTITY_FLOAT' => is_double($arOffer['CATALOG_MEASURE_RATIO']),
					'MEASURE' => $arOffer['~CATALOG_MEASURE_NAME'],
					'CAN_BUY' => $arOffer['CAN_BUY'],
					'BUY_URL' => $arOffer['~BUY_URL'],
					'ADD_URL' => $arOffer['~ADD_URL'],
				);
				$arMatrix[$keyOffer] = $arOneRow;
			}
			if (-1 == $intSelected)
				$intSelected = 0;
			if (!$arMatrix[$intSelected]['OWNER_PICT'])
			{
				$item['PREVIEW_PICTURE'] = $arMatrix[$intSelected]['PREVIEW_PICTURE'];
				$item['PREVIEW_PICTURE_SMALL'] = $arMatrix[$intSelected]['PREVIEW_PICTURE_SMALL'];
			}
			$item['JS_OFFERS'] = $arMatrix;
			$item['OFFERS_SELECTED'] = $intSelected;
			$item['OFFERS_PROPS_DISPLAY'] = $boolSKUDisplayProperties;
		}
		else
		{
			$item['MIN_PRICE'] = CIBlockPriceTools::getMinPriceFromOffers(
				$item['OFFERS'],
				$boolConvert ? $arResult['CONVERT_CURRENCY']['CURRENCY_ID'] : $strBaseCurrency
			);
		}
	}
}

$arParams['STICKER_NEW'] = $arParams['STICKER_NEW'] ? IntVal($arParams['STICKER_NEW']) : 14 ;
$arParams['STICKER_NEW_DELTA_TIME']  = 86400 * $arParams['STICKER_NEW'] ; // 86400 - 1 day in seconds
$arParams['STICKER_NEW_START_TIME'] = time() - $arParams['STICKER_NEW_DELTA_TIME'] ;
?>
