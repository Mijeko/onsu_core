<?
/**
 * copy of basket items fetching from sale.order.ajax component
 */
function rz_GetBasketItems() {
	$arItems = array();

	if (!CModule::IncludeModule('sale')) return $arItems;

	$bUseCatalog = CModule::IncludeModule('catalog');

	\CSaleBasket::UpdateBasketPrices(\CSaleBasket::GetBasketUserID(), SITE_ID);
	/* Check Values Begin */

	$arSelFields = array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY",
		"CAN_BUY", "PRICE", "WEIGHT", "NAME", "CURRENCY", "CATALOG_XML_ID", "VAT_RATE",
		"NOTES", "DISCOUNT_PRICE", "PRODUCT_PROVIDER_CLASS", "DIMENSIONS", "TYPE", "SET_PARENT_ID", "DETAIL_PAGE_URL"
	);
	$dbBasketItems = \CSaleBasket::GetList(
			array("ID" => "ASC"),
			array(
					"FUSER_ID" => \CSaleBasket::GetBasketUserID(),
					"LID" => SITE_ID,
					"ORDER_ID" => "NULL"
				),
			false,
			false,
			$arSelFields
		);
	while ($arItem = $dbBasketItems->GetNext())
	{
		if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y")
		{
			$arItem["PRICE"] = roundEx($arItem["PRICE"], SALE_VALUE_PRECISION);
			$arItem["QUANTITY"] = DoubleVal($arItem["QUANTITY"]);

			$arItem["WEIGHT"] = DoubleVal($arItem["WEIGHT"]);
			$arItem["VAT_RATE"] = DoubleVal($arItem["VAT_RATE"]);

			$arDim = unserialize($arItem["~DIMENSIONS"]);

			if(is_array($arDim))
			{
				$arItem["DIMENSIONS"] = $arDim;
				unset($arItem["~DIMENSIONS"]);
			}

			if($arItem["VAT_RATE"] > 0 && !\CSaleBasketHelper::isSetItem($arItem))
			{
				//$arItem["VAT_VALUE"] = roundEx((($arItem["PRICE"] / ($arItem["VAT_RATE"] +1)) * $arItem["VAT_RATE"]), SALE_VALUE_PRECISION);
				$arItem["VAT_VALUE"] = roundEx((($arItem["PRICE"] / ($arItem["VAT_RATE"] +1)) * $arItem["VAT_RATE"]), SALE_VALUE_PRECISION);
			}

			if($arItem["DISCOUNT_PRICE"] > 0)
			{
				$arItem["DISCOUNT_PRICE_PERCENT"] = $arItem["DISCOUNT_PRICE"]*100 / ($arItem["DISCOUNT_PRICE"] + $arItem["PRICE"]);
				$arItem["DISCOUNT_PRICE_PERCENT_FORMATED"] = roundEx($arItem["DISCOUNT_PRICE_PERCENT"], 0)."%";
			}

			$arItem["PROPS"] = array();
			$dbProp = \CSaleBasket::GetPropsList(array("SORT" => "ASC", "ID" => "ASC"), array("BASKET_ID" => $arItem["ID"], "!CODE" => array("CATALOG.XML_ID", "PRODUCT.XML_ID")));
			while($arProp = $dbProp -> GetNext())
			{
				if (array_key_exists('BASKET_ID', $arProp))
				{
					unset($arProp['BASKET_ID']);
				}
				if (array_key_exists('~BASKET_ID', $arProp))
				{
					unset($arProp['~BASKET_ID']);
				}

				$arProp = array_filter($arProp, array("CSaleBasketHelper", "filterFields"));

				$arItem["PROPS"][] = $arProp;
			}

			if (!\CSaleBasketHelper::isSetItem($arItem))
			{
				$DISCOUNT_PRICE_ALL += $arItem["DISCOUNT_PRICE"] * $arItem["QUANTITY"];
				$arItem["DISCOUNT_PRICE"] = roundEx($arItem["DISCOUNT_PRICE"], SALE_VALUE_PRECISION);
			}

			if (\CSaleBasketHelper::isSetItem($arItem))
				$arSetParentWeight[$arItem["SET_PARENT_ID"]] += $arItem["WEIGHT"] * $arItem['QUANTITY'];

			$arItems[] = $arItem;
		}

		// count weight for set parent products
		foreach ($arItems as &$arItem)
		{
			if (\CSaleBasketHelper::isSetParent($arItem))
			{
				$arItem["WEIGHT"] = $arSetParentWeight[$arItem["ID"]] / $arItem["QUANTITY"];
			}
		}

		if ($bUseCatalog)
		{
			$arParent = \CCatalogSku::GetProductInfo($arItem["PRODUCT_ID"]);
			if ($arParent)
			{
				$arElementId[] = $arParent["ID"];
				$arSku2Parent[$arItem["PRODUCT_ID"]] = $arParent["ID"];
			}
		}
		unset($arItem);
	}

	if (!empty($arResult["BASKET_ITEMS"]))
	{
		if ($bUseCatalog)
			$arResult["BASKET_ITEMS"] = getMeasures($arResult["BASKET_ITEMS"]); // get measures
	}

	// use later only items not part of the sets
	foreach ($arItems as $id => $arItem)
	{
		if (\CSaleBasketHelper::isSetItem($arItem))
			unset($arItems[$id]);
	}

	return $arItems;
}

function checkCAPTCHA($captcha_word, $captcha_code)
{
    include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");

    $cpt = new CCaptcha();
    if (strlen($captcha_code) > 0)
    {
        $captchaPass = COption::GetOptionString("main", "captcha_password", "");
        if (!$cpt->CheckCodeCrypt($captcha_word, $captcha_code, $captchaPass))
        {
            return FALSE;
        }
    }
    return TRUE;
}
