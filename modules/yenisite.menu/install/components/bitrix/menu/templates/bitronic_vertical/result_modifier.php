<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if ($arParams["RUB_SIGN"] != "N") $arParams["RUB_SIGN"] = "Y";
if ($arParams["VIEW_HIT"] == "Y") {
	$obCache = new CPHPCache;
	$lifeTime = $arParams['MENU_CACHE_TIME'] ? IntVal($arParams['MENU_CACHE_TIME']) : 604800;
	$cacheId = "menu_hits_vertical";

	if ($obCache->InitCache($lifeTime, $cacheId, "/ys-hits")) {
		$vars = $obCache->GetVars();

		if (is_array($vars['MENU_HITS_VERTICAL']) && count($vars['MENU_HITS_VERTICAL']) > 0)
			$arResult['HITS'] = $vars['MENU_HITS_VERTICAL'];
	}

	if (!is_array($arResult['HITS']))
	{
		$arHits = array();

		if (CModule::IncludeModule('catalog')) {
			$arPrice = CCatalogGroup::GetList(array(), array("NAME" => $arParams["PRICE_CODE"]),
				false, false, array("ID"))->Fetch();
		}

		foreach ($arResult as $key => &$arItem) {
			if ($key !== 'HITS') {

				$arSelect = array(
					"ID",
					"NAME",
					"IBLOCK_ID",
					"IBLOCK_SECTION_ID",
					"DETAIL_PAGE_URL",
					"DETAIL_PICTURE",
					"PREVIEW_PICTURE",
					"PROPERTY_MORE_PHOTO",
					"CATALOG_GROUP_".$arPrice["ID"]
				);

				if ($arItem['DEPTH_LEVEL'] == 1) {
					$arFilter = $arItem['PARAMS']['FILTER'];
					
					if (!is_array($arFilter) || empty($arFilter)) {
						$arFilter = array("ACTIVE" => "Y", "CATALOG_AVAILABLE" => "Y");
						$arType = CIBlockType::GetList(array(), array('NAME' => $arItem["TEXT"]))->Fetch();

						if (!empty($arType)) {
							$arFilter += array("IBLOCK_TYPE" => $arType["ID"]);
						} else {
							$arIb = CIBlock::GetList(array(), array('NAME' => $arItem["TEXT"], 'SITE_ID' => SITE_ID, 'ACTIVE' => 'Y'))->Fetch();

							if (!empty($arIb)) {
								$arFilter += array("IBLOCK_ID" => $arIb["ID"]);
							} else {
								$arSec = CIBlockSection::GetList(array(), array("NAME" => $arItem["TEXT"], "DEPTH_LEVEL" => "1", 'ACTIVE' => 'Y'))->Fetch();
								$arFilter += array("SECTION_ID" => $arSec["ID"], "INCLUDE_SUBSECTIONS" => "Y");
							}
						}
					}

					$resEl = CIBlockElement::GetList(array("PROPERTY_WEEK_COUNTER" => "desc"), $arFilter, false, false, $arSelect);
					$arEls = array();
					while ($arEl = $resEl->GetNext()) {
						$arEls[] = $arEl;
					}
				}

				if (!$arItem['IS_PARENT']) {
					$arFilter = array('NAME' => $arItem["TEXT"]);
					if (intval($arItem['PARAMS']['ITEM_IBLOCK_ID']) > 0) {
						$arFilter['ID'] = intval($arItem['PARAMS']['ITEM_IBLOCK_ID']);
					}
					$arIb = CIBlock::GetList(array(), $arFilter)->Fetch();

					if (!empty($arIb)) {
						$to = 'iblock';
					} else {
						$arSec = CIBlockSection::GetList(array(), $arFilter)->GetNext();
						$to = 'section';
					}

					foreach ($arEls as $k => $arElem) {
						if ( ($to == 'iblock' &&  $arElem['IBLOCK_ID'] == $arIb["ID"])
							|| ($to == 'section' &&  $arElem['IBLOCK_SECTION_ID'] == $arSec["ID"]) ) {

							$arEl = $arElem;
							
							if (function_exists('yenisite_GetPicSrc'))
							{
								$path = yenisite_GetPicSrc($arEl, 'DETAIL_PICTURE');
							}
							else
							{
								$path = (!empty($arEl['DETAIL_PICTURE'])) ? $arEl['DETAIL_PICTURE'] : $arEl['PREVIEW_PICTURE'];
								if(empty($path))
								{
									$path = $arEl["PROPERTY_MORE_PHOTO_VALUE"];
								}
							}
							if (CModule::IncludeModule('yenisite.resizer2')) {
								$path = CFile::GetPath($path);
								$path = CResizer2Resize::ResizeGD2($path, 3);
							}
							else
							{
								$path = CFile::ResizeImageGet($path, array('width'=>150, 'height'=>150), BX_RESIZE_IMAGE_PROPORTIONAL, false);
								$path = $path['src'];
							}
							
							if (CModule::IncludeModule('catalog')) {
								$arResultPrices = CIBlockPriceTools::GetCatalogPrices($arEl["IBLOCK_ID"], array($arParams["PRICE_CODE"]));
								$arProduct = CCatalogProduct::GetByID($arFields["ID"]);
								$arProduct['VAT_INCLUDED'] = ($arProduct['VAT_INCLUDED']=='Y') ? true : false;
								$arPrices = CIBlockPriceTools::GetItemPrices($arEl["IBLOCK_ID"], $arResultPrices,
									$arEl, $arProduct['VAT_INCLUDED'], array("CURRENCY_ID" => $arParams["CURRENCY"]));
							}

							$arHits[$key] = array(
								"NAME" => $arEl["NAME"],

								"SECTION" => $arSec["NAME"],
								"SECTION_PAGE_URL" => $arSec["SECTION_PAGE_URL"],

								"DETAIL_PAGE_URL" => $arEl["DETAIL_PAGE_URL"],
								"PHOTO" => $path,
								"PRICE" => $arPrices,
							);

							$arResult['HITS'][$key] = $arHits[$key];
						}
					}
				} // if(!$arItem['IS_PARENT'])

			} // if($arParams["VIEW_HIT"] == "Y" && !$arItem['IS_PARENT'] && $key !== 'HITS')

		} // foreach($arResult as $key => &$arItem)

		if ($obCache->StartDataCache($lifeTime, $cacheId, "/ys-hits")) {
			$obCache->EndDataCache(array("MENU_HITS_VERTICAL" =>$arHits));
		}
		
	} // if(!is_array($arResult['HITS']))

	unset($obCache);
}

?>