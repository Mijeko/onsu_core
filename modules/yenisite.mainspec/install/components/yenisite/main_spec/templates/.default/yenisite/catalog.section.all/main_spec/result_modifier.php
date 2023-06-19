<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// FOR UPDATE INFO ABOUT COUNT IN TABS
if($_REQUEST["ys_ms_ajax_call"] === "y")
	$site_id = htmlspecialchars($_REQUEST["site_id"]);
else
	$site_id = SITE_ID;
	
$save_param = new CPHPCache();
	$lifetime = $arParams['CACHE_TIME'] ? intval($arParams['CACHE_TIME']) : 60*60*24;
if($save_param->InitCache($lifetime, "ys_ms_params_".$site_id, "/"))
{
		$vars = $save_param->GetVars();
		$arResultCache = $vars["arResult"];
		$arFiltersCache = $vars["arFilters"];
		$arParamsCache = $vars["arParams"];
		$site_dirCache = $vars["SITE_DIR"];
		
		if($arResultCache['TABS'][$arParams["TAB_BLOCK"]]['COUNT'] != $arResult['COUNT'])
		{
			$arResultCache['TABS'][$arParams["TAB_BLOCK"]]['COUNT'] = $arResult['COUNT'];
			CPHPCache::Clean("ys_ms_params_".$site_id, "/");
			
			if($save_param->StartDataCache()):
				$save_param->EndDataCache(array(
					"arResult"    => $arResultCache,
					"arFilters"    => $arFiltersCache,
					"arParams"    => $arParamsCache,
					"SITE_DIR"    => $site_dirCache
			)); 
			endif;
		}
}
unset($save_param);
// END

if($_GET["YS"]=="Y")
{
	foreach($arResult["ELEMENTS"] as $i => $id)
	{
		echo count($arResult["ITEMS"][$i]["OFFERS"]);
	}
}
			
			
CModule::IncludeModule('yenisite.resizer2');

if(!function_exists('yenisite_date_to_time'))
{
	function yenisite_date_to_time ($date)
	{
		list($date, $time) = explode(" ", $date); 
		list($day, $month, $year) = explode(".", $date); 
		list($hour, $minute, $second) = explode(":", $time); 
		return mktime($hour, $minute, $second, $month, $day, $year); 
	}
}
if(!function_exists('yenisite_CATALOG_AVAILABLE'))
{
	function yenisite_CATALOG_AVAILABLE ($arProduct)
	{
		if(!count($arProduct['OFFERS']))
		{
			if ($arProduct['CATALOG_QUANTITY_TRACE'] != 'Y') return true;
			if ($arProduct['CATALOG_CAN_BUY_ZERO'] == 'Y')   return true;
			if ($arProduct['CATALOG_QUANTITY'] > 0)          return true;
				return false;
		}
		else
		{
			if($arProduct['CATALOG_QUANTITY'] > 0) return true;

			foreach ($arProduct['OFFERS'] as $arOffer)
			{
				if ($arOffer['CATALOG_QUANTITY_TRACE'] != 'Y') return true;
				if ($arOffer['CATALOG_CAN_BUY_ZERO'] == 'Y')   return true;
				if ($arOffer['CATALOG_QUANTITY'] > 0)          return true;
			}
		}
		return false;
	}
}
require_once("yenisitegetimage.php");

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
	(!is_array($item["PROPERTIES"]["MORE_PHOTO"]["VALUE"]) ? $item["PROPERTIES"]["MORE_PHOTO"]["VALUE"] = array($item["PROPERTIES"]["MORE_PHOTO"]["VALUE"]) : '');

	if(IntVal($item["DETAIL_PICTURE"]["ID"]) > 0) {
		$item["PROPERTIES"]["MORE_PHOTO"]["VALUE"][] = $item["DETAIL_PICTURE"]["ID"];
	}

	$item['CATALOG_AVAILABLE'] = yenisite_CATALOG_AVAILABLE($item) ;
	$res = CIBlockSection::GetByID($item["IBLOCK_SECTION_ID"]);
	if($ar_res = $res->GetNext()){
		$item["IBLOCK_SECTION_NAME"] = $ar_res['NAME'];
		$item["IBLOCK_SECTION_PAGE_URL"] = $ar_res['SECTION_PAGE_URL'];
		}
	else
	{
		$ar_res = CIBlock::GetByID($item["IBLOCK_ID"])->GetNext();
		$item["IBLOCK_SECTION_NAME"] = $ar_res['NAME'];
		$item["IBLOCK_SECTION_PAGE_URL"] = $ar_res['LIST_PAGE_URL'];
	}
	if(!CModule::IncludeModule('catalog') && CModule::IncludeModule('yenisite.market')){		
		$prices = CMarketPrice::GetItemPriceValues($item["ID"]);
		foreach($prices as $k=>$pr){
			$item["PRICES"][$k]["VALUE"] = $pr;
			$item["PRICES"][$k]["PRINT_VALUE"] = $pr." <span class='rubl'>".GetMessage('RUB')."</span>";
		}
	}
	
	if($_REQUEST["ys_ms_ajax_call"] === "y"){
		$item["ADD_URL"] = $_REQUEST["red_url"]."?".$arParams["ACTION_VARIABLE"]."=ADD2BASKET&".$arParams["PRODUCT_ID_VARIABLE"]."=".$item["ID"];
		$item["DETAIL_PAGE_URL"] = SITE_DIR_2.substr($item["DETAIL_PAGE_URL"], 1);
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
unset($item);
$arParams['IMAGE_SET'] = $arParams['IMAGE_SET'] ? IntVal($arParams['IMAGE_SET']) : 3;
$arParams['IMAGE_SET_BIG'] = $arParams['IMAGE_SET_BIG'] ? IntVal($arParams['IMAGE_SET_BIG']) : 4;


?>
