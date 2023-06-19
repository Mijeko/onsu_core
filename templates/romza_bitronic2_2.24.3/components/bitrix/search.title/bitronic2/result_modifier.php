<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

include_once $_SERVER['DOCUMENT_ROOT'].SITE_DIR."ajax/include_module.php";

global $rz_b2_options;
//update parameters to set needed currency
if ($rz_b2_options['convert_currency']) {
    $arParams['CONVERT_CURRENCY'] = 'Y';
    $arParams['CURRENCY_ID'] = $rz_b2_options['active-currency'];
}

$arParams["PRICE_VAT_INCLUDE"] = $arParams["PRICE_VAT_INCLUDE"] !== "N";

$arCatalogs = array();
if ($bCatalog = CModule::IncludeModule("catalog"))
{
    $rsCatalog = CCatalog::GetList(array(
        "sort" => "asc",
    ));
    while ($ar = $rsCatalog->Fetch())
    {
        if ($ar["PRODUCT_IBLOCK_ID"])
            $arCatalogs[$ar["PRODUCT_IBLOCK_ID"]] = 1;
        else
            $arCatalogs[$ar["IBLOCK_ID"]] = 1;
    }
    $arDefaultMeasure = CCatalogMeasure::getDefaultMeasure(true, true);
    $arMeasureMap = array();
}
elseif (CModule::IncludeModule('yenisite.market')) {
    $rsCatalog = CMarketCatalog::GetList();
    while ($ar = $rsCatalog->Fetch()) {
        $arCatalogs[$ar['iblock_id']] = $ar['use_quantity'];
    }
}

$arResult["ELEMENTS"] = array();
$arResult["SEARCH"] = array();

foreach ($arResult["CATEGORIES"] as $category_id => &$arCategory) {

    if (is_array($arCategory["ITEMS"])) {

        usort($arCategory['ITEMS'], function($a, $b) {
            return substr($a['ITEM_ID'], 0, 1) === 'S' ? -1 : 1;
        });

        foreach ($arCategory["ITEMS"] as $i => $arItem) {

            if (isset($arItem["ITEM_ID"])) {
                $arResult["SEARCH"][] = &$arResult["CATEGORIES"][$category_id]["ITEMS"][$i];

                if (
                    $arItem["MODULE_ID"] == "iblock"
                    && array_key_exists($arItem["PARAM2"], $arCatalogs)
                    && substr($arItem["ITEM_ID"], 0, 1) !== "S"
                ) {
                    $arResult["ELEMENTS"][$arItem["ITEM_ID"]] = $arItem["ITEM_ID"];
                }
            }
            elseif($category_id !== 'all') {
                unset($arResult["CATEGORIES"][$category_id]["ITEMS"][$i]);
            }
        }
        if ($category_id !== 'all') {
            $arResult["CATEGORIES"][$category_id]["ITEMS"][] = array(
                'NAME' => GetMessage('BITRONIC2_ALL_RESULTS'),
                'URL' => $arResult['FORM_ACTION'] . '?q='.urlencode($arResult['query']) . '&where='.urlencode($arParams['CATEGORY_'.$category_id][0])
            );
        }
    }
}
if (isset($arCategory)) {
    unset($arCategory);
}

if (!empty($arResult["ELEMENTS"]) && CModule::IncludeModule("iblock"))
{
    //************************
    //* BUY WITH PROPS CHECK *
    //************************
    $catalogParams = array();
    if (CModule::IncludeModule('yenisite.core')) {
        $catalogParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
    }
    $arParams['BASKET_URL'] = $catalogParams['BASKET_URL'];
    $catalogParams['ADD_PROPERTIES_TO_BASKET'] = (isset($catalogParams['ADD_PROPERTIES_TO_BASKET']) && $catalogParams['ADD_PROPERTIES_TO_BASKET'] == 'N' ? 'N' : 'Y');
    if ('N' == $catalogParams['ADD_PROPERTIES_TO_BASKET'])
    {
        $catalogParams["PRODUCT_PROPERTIES"] = array();
        $catalogParams["OFFERS_CART_PROPERTIES"] = array();
    }
    $catalogParams['PARTIAL_PRODUCT_PROPERTIES'] = (isset($catalogParams['PARTIAL_PRODUCT_PROPERTIES']) && $catalogParams['PARTIAL_PRODUCT_PROPERTIES'] === 'Y' ? 'Y' : 'N');
    if(!is_array($catalogParams["PRODUCT_PROPERTIES"]))
        $catalogParams["PRODUCT_PROPERTIES"] = array();
    foreach($catalogParams["PRODUCT_PROPERTIES"] as $k=>$v)
        if($v==="")
            unset($catalogParams["PRODUCT_PROPERTIES"][$k]);

    if (!is_array($catalogParams["OFFERS_CART_PROPERTIES"]))
        $catalogParams["OFFERS_CART_PROPERTIES"] = array();
    foreach($catalogParams["OFFERS_CART_PROPERTIES"] as $i => $pid)
        if ($pid === "")
            unset($catalogParams["OFFERS_CART_PROPERTIES"][$i]);
    $bGetProductProperties = ($catalogParams['ADD_PROPERTIES_TO_BASKET'] == 'Y'  && !empty($catalogParams["PRODUCT_PROPERTIES"]));

    $arParams['HIDE_ITEMS_NOT_AVAILABLE'] = $catalogParams['HIDE_ITEMS_NOT_AVAILABLE'];
    $arParams['HIDE_ITEMS_ZER_PRICE'] = $catalogParams['HIDE_ITEMS_ZER_PRICE'];
    $arParams['HIDE_ITEMS_WITHOUT_IMG'] = $catalogParams['HIDE_ITEMS_WITHOUT_IMG'];
    if(!is_array($catalogParams['PRICE_CODE'])) {
        $arParams['PRICE_CODE'] = array($catalogParams['PRICE_CODE']);
    }
    else {
        $arParams['PRICE_CODE'] = $catalogParams['PRICE_CODE'];
    }

    $arPropFilter = array(
        'ID' => $arResult["ELEMENTS"],
        'IBLOCK_ID' => $catalogParams['IBLOCK_ID']
    );
    //************************
    //* BUY WITH PROPS CHECK *
    //************************


    $arConvertParams = array();
    if ('Y' == $arParams['CONVERT_CURRENCY'])
    {
        if (!CModule::IncludeModule('currency'))
        {
            $arParams['CONVERT_CURRENCY'] = 'N';
            $arParams['CURRENCY_ID'] = '';
        }
        else
        {
            $arCurrencyInfo = CCurrency::GetByID($arParams['CURRENCY_ID']);
            if (!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo)))
            {
                $arParams['CONVERT_CURRENCY'] = 'N';
                $arParams['CURRENCY_ID'] = '';
            }
            else
            {
                $arParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
                $arConvertParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
            }
        }
    }

    $obParser = new CTextParser;

    if (is_array($arParams["PRICE_CODE"]))
        $arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices(0, $arParams["PRICE_CODE"]);
    else
        $arResult["PRICES"] = array();

    $arSelect = array(
        "ID",
        "IBLOCK_ID",
        "PREVIEW_TEXT",
        "PREVIEW_PICTURE",
        "DETAIL_PICTURE",
    );
    $arFilter = array(
        "IBLOCK_LID" => SITE_ID,
        "IBLOCK_ACTIVE" => "Y",
        "ACTIVE_DATE" => "Y",
        "ACTIVE" => "Y",
        "CHECK_PERMISSIONS" => "Y",
        "MIN_PERMISSION" => "R",
    );

    if(!empty($catalogParams["VALUE_RZ_AVAILABLE"]))
        unset($catalogParams["VALUE_RZ_AVAILABLE"]);

    CRZBitronic2CatalogUtils::setFilterAvPrFoto($arFilter, $catalogParams);

    foreach($arResult["PRICES"] as $value)
    {
        $arSelect[] = $value["SELECT"];
        $arFilter["CATALOG_SHOP_QUANTITY_".$value["ID"]] = 1;
    }
    $arFilter["=ID"] = $arResult["ELEMENTS"];
    $rsElements = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
    while($arItem = $rsElements->Fetch())
    {
        $arItem["PRICES"] = CIBlockPriceTools::GetItemPrices($arItem["IBLOCK_ID"], $arResult["PRICES"], $arItem, $arParams['PRICE_VAT_INCLUDE'], $arConvertParams);
        foreach($arItem["PRICES"] as $arPrice)
        {
            if($arPrice['MIN_PRICE'] == 'Y')
            {
                $arItem['MIN_PRICE'] = $arPrice;
                break;
            }
        }
        $arItem["CAN_BUY"] = CIBlockPriceTools::CanBuy($arItem["IBLOCK_ID"], $arResult["PRICES"], $arItem);

        //Prices for MARKET
        if (CModule::IncludeModule('yenisite.bitronic2lite') && CModule::IncludeModule('yenisite.market')) {
            $prices = CMarketPrice::GetItemPriceValues($arItem['ID'], $arItem['PRICES']);
            if (count($prices) > 0) {
                unset($arItem['PRICES']);
            }
            $minPrice = false;
            foreach ($prices as $k => $pr) {
                $pr = floatval($pr);
                $arItem['PRICES'][$k]['VALUE'] = $pr;
                $arItem['PRICES'][$k]['PRINT_VALUE'] = $pr;
                if ((empty($minPrice) || $minPrice > $pr) && $pr > 0) {
                    $minPrice = $pr;
                }
            }
            if ($minPrice !== false) {
                $arItem['MIN_PRICE']['VALUE'] = $minPrice;
                $arItem['MIN_PRICE']['PRINT_VALUE'] = $minPrice;
                $arItem['MIN_PRICE']['DISCOUNT_VALUE'] = $minPrice;
                $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE'] = $minPrice;
                $arItem['CATALOG_MEASURE_RATIO'] = 1;
                $arItem['CAN_BUY'] = true;
            }
            $arItem['CHECK_QUANTITY'] = ($arCatalogs[$arItem['IBLOCK_ID']] == 1);
            $arItem['CATALOG_QUANTITY'] = CMarketCatalogProduct::GetQuantity($arItem['ID'], $arItem['IBLOCK_ID']);

            if ($arItem['CHECK_QUANTITY'] && $arItem['CATALOG_QUANTITY'] <= 0) {
                $arItem['CAN_BUY'] = false;
            }
            $arItem['CATALOG_TYPE'] = 1; //simple product
        }
        //end Prices for MARKET

        $arItem["FOR_ORDER"] = ('Y' == $arItem['CATALOG_QUANTITY_TRACE'] && 'Y' == $arItem['CATALOG_CAN_BUY_ZERO'] && 0 >= $arItem['CATALOG_QUANTITY']);
        $arItem['ON_REQUEST'] = (empty($arItem['MIN_PRICE']) || $arItem['MIN_PRICE']['VALUE'] <= 0);

        if ($arItem['ON_REQUEST']) {
            $arItem['CAN_BUY'] = false;
        }

        if($arParams["PREVIEW_TRUNCATE_LEN"] > 0)
            $arItem["PREVIEW_TEXT"] = $obParser->html_cut($arItem["PREVIEW_TEXT"], $arParams["PREVIEW_TRUNCATE_LEN"]);

        //---MEASURES---
        if (!isset($arItem["CATALOG_MEASURE_RATIO"]))
            $arItem["CATALOG_MEASURE_RATIO"] = 1;
        if (!isset($arItem['CATALOG_MEASURE']))
            $arItem['CATALOG_MEASURE'] = 0;
        $arItem['CATALOG_MEASURE'] = (int)$arItem['CATALOG_MEASURE'];
        if (0 > $arItem['CATALOG_MEASURE'])
            $arItem['CATALOG_MEASURE'] = 0;
        if (!isset($arItem['CATALOG_MEASURE_NAME']))
            $arItem['CATALOG_MEASURE_NAME'] = '';

        if ($bCatalog) {
            $arItem['CATALOG_MEASURE_NAME'] = $arDefaultMeasure['SYMBOL_RUS'];
            $arItem['~CATALOG_MEASURE_NAME'] = $arDefaultMeasure['~SYMBOL_RUS'];
            if (0 < $arItem['CATALOG_MEASURE'])
            {
                if (!isset($arMeasureMap[$arItem['CATALOG_MEASURE']]))
                    $arMeasureMap[$arItem['CATALOG_MEASURE']] = array();
                $arMeasureMap[$arItem['CATALOG_MEASURE']][] = $arItem['ID'];
            }
            //---MEASURES-END---
            $arItem["bOffers"] = CCatalogSKU::IsExistOffers($arItem["ID"], $arItem["IBLOCK_ID"]);
        }
        if (!isset($arItem['PROPERTIES'])) {
            $arItem['PROPERTIES'] = array();
        }
        $arItem = CRZBitronic2CatalogUtils::processItemCommon($arItem);
        $arResult["ELEMENTS"][$arItem["ID"]] = $arItem;
    }

    if ($bGetProductProperties) {
        CIBlockElement::GetPropertyValuesArray($arResult['ELEMENTS'], $catalogParams["IBLOCK_ID"], $arPropFilter);

        foreach ($arResult['ELEMENTS'] as &$arItem) {
            $arItem["PRODUCT_PROPERTIES"] = CIBlockPriceTools::GetProductProperties(
                $catalogParams["IBLOCK_ID"],
                $arItem["ID"],
                $catalogParams["PRODUCT_PROPERTIES"],
                $arItem["PROPERTIES"]
            );/*
			if (!empty($arItem["PRODUCT_PROPERTIES"]))
			{
				$arItem['PRODUCT_PROPERTIES_FILL'] = CIBlockPriceTools::getFillProductProperties($arItem['PRODUCT_PROPERTIES']);
			}*/
        }
        if (isset($arItem))
            unset($arItem);
    }

    if (CModule::IncludeModule('catalog')) {
        //---MEASURES-2---
        if (!empty($arResult["ELEMENTS"]))
        {
            $rsRatios = CCatalogMeasureRatio::getList(
                array(),
                array('PRODUCT_ID' => array_keys($arResult["ELEMENTS"])),
                false,
                false,
                array('PRODUCT_ID', 'RATIO')
            );
            while ($arRatio = $rsRatios->Fetch())
            {
                $arRatio['PRODUCT_ID'] = (int)$arRatio['PRODUCT_ID'];
                if (isset($arResult['ELEMENTS'][$arRatio['PRODUCT_ID']]))
                {
                    $intRatio = (int)$arRatio['RATIO'];
                    $dblRatio = doubleval($arRatio['RATIO']);
                    $mxRatio = ($dblRatio > $intRatio ? $dblRatio : $intRatio);
                    if (CATALOG_VALUE_EPSILON > abs($mxRatio))
                        $mxRatio = 1;
                    elseif (0 > $mxRatio)
                        $mxRatio = 1;
                    $arResult['ELEMENTS'][$arRatio['PRODUCT_ID']]['CATALOG_MEASURE_RATIO'] = $mxRatio;
                }
            }
        }
        if (!empty($arMeasureMap))
        {
            $rsMeasures = CCatalogMeasure::getList(
                array(),
                array('@ID' => array_keys($arMeasureMap)),
                false,
                false,
                array('ID', 'SYMBOL_RUS')
            );
            while ($arMeasure = $rsMeasures->GetNext())
            {
                $arMeasure['ID'] = (int)$arMeasure['ID'];
                if (isset($arMeasureMap[$arMeasure['ID']]) && !empty($arMeasureMap[$arMeasure['ID']]))
                {
                    foreach ($arMeasureMap[$arMeasure['ID']] as &$itemId)
                    {
                        $arResult['ELEMENTS'][$itemId]['CATALOG_MEASURE_NAME'] = $arMeasure['SYMBOL_RUS'];
                        $arResult['ELEMENTS'][$itemId]['~CATALOG_MEASURE_NAME'] = $arMeasure['~SYMBOL_RUS'];
                    }
                    unset($itemId);
                }
            }
        }
        //---MEASURES-2---
    }
}

foreach($arResult["SEARCH"] as $i=>$arItem)
{
    switch($arItem["MODULE_ID"])
    {
        case "iblock":
            $arResult["SEARCH"][$i]["PICTURE"] = CRZBitronic2CatalogUtils::getElementPictureById($arItem["ITEM_ID"], $arParams['RESIZER_SEARCH_TITLE']);
            break;
    }
}

if (isset($arResult['CATEGORIES']['all'])) {
    $arResult['CATEGORIES']['all']['ITEMS'][0]['URL'] .= '&where=ALL';
}