<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$cp = $this->__component;
if (is_object($cp))
{
	CModule::IncludeModule('iblock');

	if(empty($arResult['ERRORS']['FATAL']))
	{

		$hasDiscount = false;
		$hasProps = false;
		$productSum = 0;
		$basketRefs = array();
		$arResult['PROPS_BY_GROUP'] = array();

		foreach($arResult["ORDER_PROPS"] as $prop)
		{
			$arResult['PROPS_BY_GROUP'][$prop['PROPS_GROUP_ID']][] = $prop;
		}
		unset($prop);
		foreach($arResult["BASKET"] as $k => &$prod)
		{
			if(floatval($prod['DISCOUNT_PRICE']))
				$hasDiscount = true;

			// move iblock props (if any) to basket props to have some kind of consistency
			if(isset($prod['IBLOCK_ID']))
			{
				$iblock = $prod['IBLOCK_ID'];
				if(isset($prod['PARENT']))
					$parentIblock = $prod['PARENT']['IBLOCK_ID'];

				foreach($arParams['CUSTOM_SELECT_PROPS'] as $prop)
				{
					$key = $prop.'_VALUE';
					if(isset($prod[$key]))
					{
						// in the different iblocks we can have different properties under the same code
						if(isset($arResult['PROPERTY_DESCRIPTION'][$iblock][$prop]))
							$realProp = $arResult['PROPERTY_DESCRIPTION'][$iblock][$prop];
						elseif(isset($arResult['PROPERTY_DESCRIPTION'][$parentIblock][$prop]))
							$realProp = $arResult['PROPERTY_DESCRIPTION'][$parentIblock][$prop];
						
						if(!empty($realProp))
							$prod['PROPS'][] = array(
								'NAME' => $realProp['NAME'], 
								'VALUE' => htmlspecialcharsEx($prod[$key])
							);
					}
				}
			}

			// if we have props, show "properties" column
			if(!empty($prod['PROPS']))
				$hasProps = true;

			$productSum += $prod['PRICE'] * $prod['QUANTITY'];

			$basketRefs[$prod['PRODUCT_ID']][] =& $arResult["BASKET"][$k];

			$prod['PICTURE']['SRC'] = CRZBitronic2CatalogUtils::getElementPictureById($prod['PRODUCT_ID'], $arParams['RESIZER_BASKET_ICON']);
			$prod['PRICE_FORMATED'] = CRZBitronic2CatalogUtils::getElementPriceFormat($prod["CURRENCY"], $prod["PRICE"], $prod["PRICE_FORMATED"]);
			$prod['PRICE_SUMM_FORMATED'] = CRZBitronic2CatalogUtils::getElementPriceFormat($prod["CURRENCY"], $prod["PRICE"]*$prod["QUANTITY"]);
		}

		$arResult['HAS_DISCOUNT'] = $hasDiscount;
		$arResult['HAS_PROPS'] = $hasProps;

		$arResult['PRICE_FORMATED'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arResult["CURRENCY"], $arResult["PRICE"], $arResult["PRICE_FORMATED"]);
		$arResult['DISCOUNT_VALUE_FORMATED'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arResult["CURRENCY"], $arResult["DISCOUNT_VALUE"], $arResult["DISCOUNT_VALUE_FORMATED"]);
		$arResult['PRODUCT_SUM'] = $productSum;
		$arResult['PRODUCT_SUM_FORMATTED'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arResult["CURRENCY"], $arResult['PRODUCT_SUM']);
		$arResult['PRICE_DELIVERY_FORMATED'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arResult["CURRENCY"], $arResult['PRICE_DELIVERY']);

		if($img = intval($arResult["DELIVERY"]["STORE_LIST"][$arResult['STORE_ID']]['IMAGE_ID']))
		{

			$pict = CFile::ResizeImageGet($img, array(
				'width' => 150,
				'height' => 90
			), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true);

			if(strlen($pict['src']))
				$pict = array_change_key_case($pict, CASE_UPPER);

			$arResult["DELIVERY"]["STORE_LIST"][$arResult['STORE_ID']]['IMAGE'] = $pict;
		}

	}
}
?>