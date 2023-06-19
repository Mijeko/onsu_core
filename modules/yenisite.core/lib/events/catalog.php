<?php
namespace Yenisite\Core\Events;

use Bitrix\Iblock\PropertyIndex\Manager;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Yenisite\Core\IBlock;

/**
 * Class Catalog
 * Class for events associated with the catalog of products
 * @package Yenisite\Events
 */
class Catalog
{
	const MODULE_ID = 'yenisite.core';
	static public $_sortProps = array(
		'MIN' => 'MINIMUM_PRICE',
		'MAX' => 'MAXIMUM_PRICE'
	);
	static protected $arDefProps = array(
		'PRODUCT' => 'PRODUCT',
		'PRICE' => 'PRICE',
		'EMAIL' => 'EMAIL',
		'PRICE_TYPE_ID' => 'PRICE_TYPE_ID',
	);

	static private $onRequestCache = array();

	/**
	 * Events for the creation of properties MINIMUM_PRICE, MAXIMUM_PRICE for catalog IBLOCKS wuth SKU
	 * To work correctly, the event should be added to the:
	 * iblock OnAfterIBlockElementAdd
	 * iblock OnAfterIBlockElementUpdate
	 * catalog OnPriceAdd
	 * catalog OnPriceUpdate
	 * @param array|int $arg1
	 * @param array|bool|false $arg2
	 * @throws \Bitrix\Main\LoaderException
	 */
	public static function MinimumMaximumPriceAfterSave($arg1, $arg2 = false, $SITE_ID) {
		$ELEMENT_ID = false;
		$IBLOCK_ID = false;
		$OFFERS_IBLOCK_ID = false;
		$OFFERS_PROPERTY_ID = false;
		$strDefaultCurrency = false;
		$arProductID = array();
		if (Loader::IncludeModule('currency'))
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			/** @noinspection PhpUndefinedClassInspection */
			$strDefaultCurrency = \CCurrency::GetBaseCurrency();

		//Check for catalog event
		if (is_array($arg2) && $arg2["PRODUCT_ID"] > 0) {
			//Get iblock element
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$rsPriceElement = \CIBlockElement::GetList(
				array(),
				array(
					"ID" => $arg2["PRODUCT_ID"],
				),
				false,
				false,
				array("ID", "IBLOCK_ID")
			);
			if ($arPriceElement = $rsPriceElement->Fetch()) {
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				$arCatalog = \CCatalog::GetByID($arPriceElement["IBLOCK_ID"]);
				if (is_array($arCatalog)) {
					//Check if it is offers iblock
					if ($arCatalog["OFFERS"] == "Y") {
						//Find product element
						$rsElement = \CIBlockElement::GetProperty(
							$arPriceElement["IBLOCK_ID"],
							$arPriceElement["ID"],
							"sort",
							"asc",
							array("ID" => $arCatalog["SKU_PROPERTY_ID"])
						);
						$arElement = $rsElement->Fetch();
						if ($arElement && $arElement["VALUE"] > 0) {
							$ELEMENT_ID = $arElement["VALUE"];
							$IBLOCK_ID = $arCatalog["PRODUCT_IBLOCK_ID"];
							$OFFERS_IBLOCK_ID = $arCatalog["IBLOCK_ID"];
							$OFFERS_PROPERTY_ID = $arCatalog["SKU_PROPERTY_ID"];
						}
					} //or iblock which has offers
					elseif ($arCatalog["OFFERS_IBLOCK_ID"] > 0) {
						$ELEMENT_ID = $arPriceElement["ID"];
						$IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
						$OFFERS_IBLOCK_ID = $arCatalog["OFFERS_IBLOCK_ID"];
						$OFFERS_PROPERTY_ID = $arCatalog["OFFERS_PROPERTY_ID"];
					} //or it's regular catalog
					else {
						$ELEMENT_ID = $arPriceElement["ID"];
						$IBLOCK_ID = $arPriceElement["IBLOCK_ID"];
						$OFFERS_IBLOCK_ID = false;
						$OFFERS_PROPERTY_ID = false;
					}
				}
			}
		} //Check for iblock event
		elseif (is_array($arg1) && $arg1["ID"] > 0 && $arg1["IBLOCK_ID"] > 0) {
            $ELEMENT_ID = $arg1["ID"];
            $IBLOCK_ID = $arg1["IBLOCK_ID"];
			//Check if iblock has offers
			$arOffers = \CIBlockPriceTools::GetOffersIBlock($arg1["IBLOCK_ID"]);
			if (is_array($arOffers)) {
				$OFFERS_IBLOCK_ID = $arOffers["OFFERS_IBLOCK_ID"];
				$OFFERS_PROPERTY_ID = $arOffers["OFFERS_PROPERTY_ID"];
			}
		} // check for catalogProductUpdate event
		elseif (intval($arg1) > 0) {
			$IBLOCK_ID = \CIBlockElement::GetIBlockByID(intval($arg1));
			if ($IBLOCK_ID > 0) {
				//Check if iblock has offers
				$arOffers = \CIBlockPriceTools::GetOffersIBlock($IBLOCK_ID);
				if (is_array($arOffers)) {
					$ELEMENT_ID = intval($arg1);
					$OFFERS_IBLOCK_ID = $arOffers["OFFERS_IBLOCK_ID"];
					$OFFERS_PROPERTY_ID = $arOffers["OFFERS_PROPERTY_ID"];
				}
			}
		}

		if ($ELEMENT_ID && Loader::includeModule('catalog')) {
			static $arPropCache = array();
			if(isset($SITE_ID))
			{
				static $arIblockCache = array();
				if (!array_key_exists($IBLOCK_ID, $arIblockCache)) {
					$rsSites = \CIBlock::GetSite($IBLOCK_ID);
					while ($arSite = $rsSites->Fetch())
						$arIblockCache[$IBLOCK_ID]['SITES'][] = $arSite["SITE_ID"];

					unset($rsSites, $arSite);
				}
				if (!in_array($SITE_ID, $arIblockCache[$IBLOCK_ID]['SITES'])) return;
			}

			if (!array_key_exists($IBLOCK_ID, $arPropCache)) {
				//Check for MINIMAL_PRICE property
				$rsProperty = \CIBlockProperty::GetByID(self::$_sortProps['MIN'], $IBLOCK_ID);
				$arProperty = $rsProperty->Fetch();
				if ($arProperty)
					$arPropCache[$IBLOCK_ID] = $arProperty["ID"];
				else
				{
					$arFields = Array(
						"NAME" => self::$_sortProps['MIN'],
						"ACTIVE" => "Y",
						"SORT" => "100000",
						"CODE" => self::$_sortProps['MIN'],
						"PROPERTY_TYPE" => "N",
						"IBLOCK_ID" => $IBLOCK_ID
					);

					$ibp = new \CIBlockProperty;
					$arPropCache[$IBLOCK_ID] = $ibp->Add($arFields);

					unset($ibp);
				}
			}

			if ($arPropCache[$IBLOCK_ID]) {
				//Compose elements filter
				if ($OFFERS_IBLOCK_ID) {
					/** @noinspection PhpDynamicAsStaticMethodCallInspection */
					$rsOffers = \CIBlockElement::GetList(
						array(),
						array(
							"IBLOCK_ID" => $OFFERS_IBLOCK_ID,
							"PROPERTY_" . $OFFERS_PROPERTY_ID => $ELEMENT_ID,
						),
						false,
						false,
						array("ID")
					);
					while ($arOffer = $rsOffers->Fetch())
						$arProductID[] = $arOffer["ID"];

					if (empty($arProductID))
						$arProductID = array($ELEMENT_ID);
				} else
					$arProductID = array($ELEMENT_ID);

				$minPrice = false;
				$maxPrice = false;
				//Get prices
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				$rsPrices = \CPrice::GetList(
					array(),
					array(
						"PRODUCT_ID" => $arProductID,
					)
				);
				while ($arPrice = $rsPrices->Fetch()) {
					if (0 >= $arPrice["PRICE"]) continue;

					if (Loader::IncludeModule('currency') && $strDefaultCurrency != $arPrice['CURRENCY'])
						/** @noinspection PhpDynamicAsStaticMethodCallInspection */
						/** @noinspection PhpUndefinedClassInspection */
						$arPrice["PRICE"] = \CCurrencyRates::ConvertCurrency($arPrice["PRICE"], $arPrice["CURRENCY"], $strDefaultCurrency);

					$PRICE = $arPrice["PRICE"];

					if ($minPrice === false || $minPrice > $PRICE)
						$minPrice = $PRICE;

					if ($maxPrice === false || $maxPrice < $PRICE)
						$maxPrice = $PRICE;
				}
				//Save found minimal price into property
				$arSaveValues = array();
				if ($minPrice !== false) {
					$arSaveValues += array(
						self::$_sortProps['MIN'] => $minPrice,
						self::$_sortProps['MAX'] => $maxPrice,
					);
				}
				if(!empty($arSaveValues))
				{
					\CIBlockElement::SetPropertyValuesEx(
						$ELEMENT_ID,
						$IBLOCK_ID,
						$arSaveValues
					);
				}
			}
		}
	}

	/**
	 * Creates a property STORE_AMOUNT_BOOL for products where records sign of availability at store
	 * To work correctly, the event should be added to the:
	 * catalog OnStoreProductUpdate
	 * @param array|int $id
	 * @param array|bool|false $arFields require "PRODUCT_ID" && "STORE_ID" && "AMOUNT" keys to be filled
	 * @param string $PROP_CODE - string code for used property
	 * @return bool
	 */
	public static function StoreInProperties($id, $arFields, $PROP_CODE = 'STORE_AMOUNT_BOOL', $SITE_ID = NULL) {
		static $arCache;
		$return = true;
		// cache IBLOCK_ID by PRODUCT_ID
		if (($IBLOCK_ID = (int)$arCache['PRODUCTS'][$arFields['PRODUCT_ID']]['IBLOCK_ID']) <= 0) {
			$arCache['PRODUCTS'][$arFields['PRODUCT_ID']]['IBLOCK_ID'] = $IBLOCK_ID = \CIBlockElement::GetIBlockByID($arFields['PRODUCT_ID']);
		}
		if(isset($SITE_ID))
		{
			static $arIblockCache = array();
			if (!array_key_exists($IBLOCK_ID, $arIblockCache))
			{
				$rsSites = \CIBlock::GetSite($IBLOCK_ID);
				while($arSite = $rsSites->Fetch())
					$arIblockCache[$IBLOCK_ID]['SITES'][] = $arSite["SITE_ID"];
				
				unset($rsSites, $arSite);
			}
			if(!in_array($SITE_ID, $arIblockCache[$IBLOCK_ID]['SITES'])) return;
		}
		
		// if is SKU - update parent product
		if (($arParent = \CCatalogSku::GetProductInfo($arFields['PRODUCT_ID'])) !== false) {
			// if parent product already get SKU in cache - refresh it
			if (isset($arCache['PRODUCT_SKU'][$arParent['ID']][$arFields['PRODUCT_ID']])) {
				$arCache['PRODUCT_SKU'][$arParent['ID']][$arFields['PRODUCT_ID']][$arFields['STORE_ID']] = $arFields['AMOUNT'];
			}
			return self::StoreInProperties(false,
				array(
					'PRODUCT_ID' => $arParent['ID'],
					'STORE_ID' => $arFields['STORE_ID'],
					'AMOUNT' => $arFields['AMOUNT']
				),
				$PROP_CODE
			);
		}
		// cache and create if not exist PROP_ID
		if (($PROP_ID = (int)$arCache['IBLOCKS'][$IBLOCK_ID]['ID']) <= 0) {
			$rsProperty = \CIBlockProperty::GetByID($PROP_CODE, $IBLOCK_ID);
			$arProperty = $rsProperty->Fetch();
			if ($arProperty) {
				$PROP_ID = $arProperty["ID"];
			} else {
				loc::loadMessages(__FILE__);
				$arFieldsProp = array(
					'NAME' => GetMessage('RZ_NALICHIE_NA_SKLADAH'),
					'ACTIVE' => 'Y',
					'SORT' => '100000',
					'PROPERTY_TYPE' => 'L',
					'MULTIPLE' => 'Y',
					'CODE' => $PROP_CODE,
					'IBLOCK_ID' => $IBLOCK_ID,
				);

				$ibp = new \CIBlockProperty;
				$PROP_ID = $ibp->Add($arFieldsProp);
				unset($ibp);
			}
			$arCache['IBLOCKS'][$IBLOCK_ID]['ID'] = $PROP_ID;
		}
		// get + cache store's fields
		$arStore = $arCache['STORES'][$arFields['STORE_ID']];
		if (empty($arStore)) {
			$rs = \CCatalogStore::GetList(array(), array('ID' => $arFields['STORE_ID'], 'ACTIVE' => 'Y'));
			$arCache['STORES'][$arFields['STORE_ID']] = $arStore = $rs->Fetch();
		}
		$xmlId = md5($arStore['ID']);
		// get + cache target STORE property value id
		$PROP_VALUE_ID = $arCache['PROPS'][$PROP_ID][$xmlId];
		if (empty($PROP_VALUE_ID)) {
			$rs = \CIBlockPropertyEnum::GetList(array(), array('XML_ID' => $xmlId, 'PROPERTY_ID' => $PROP_ID));
			$ar = $rs->Fetch();
			if (!$ar) {
				$ibpenum = new \CIBlockPropertyEnum;
				$PROP_VALUE_ID = $ibpenum->Add(array(
					'PROPERTY_ID' => $PROP_ID,
					'VALUE' => $arStore['TITLE'],
					'XML_ID' => $xmlId,
				));
				unset($ibpenum);
			} else {
				$PROP_VALUE_ID = $ar['ID'];
			}
			$arCache['PROPS'][$PROP_ID][$xmlId] = $PROP_VALUE_ID;
		}
		$arPropValues = array($PROP_CODE => array());
		$arStorePropVal = $arCache['PRODUCTS'][$arFields['PRODUCT_ID']]['STORE_PROP_VALS'];
		if (empty($arStorePropVal)) {
			$arStorePropVal = array();
			$rs = \CIBlockElement::GetProperty($IBLOCK_ID, $arFields['PRODUCT_ID'], array(), array('CODE' => $PROP_CODE));
			while ($ar = $rs->GetNext(false, false)) {
				if (!empty($ar['VALUE'])) {
					$arStorePropVal[$ar['VALUE_XML_ID']] = $ar['VALUE'];
				}
			}
		}
		$AMOUNT = (int)$arFields['AMOUNT'];
		$arSKU = $arCache['PRODUCT_SKU'][$arFields['PRODUCT_ID']];
		if (!isset($arSKU)) {
			$arSKU = \CCatalogSKU::getOffersList(array($arFields['PRODUCT_ID']), $IBLOCK_ID);
			if (!empty($arSKU[$arFields['PRODUCT_ID']])) {
				$arSKU = array_keys($arSKU[$arFields['PRODUCT_ID']]);
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				$rs = \CCatalogStoreProduct::GetList(array(), array('@PRODUCT_ID' => $arSKU));
				$arSKU = array();
				while ($ar = $rs->GetNext(false, false)) {
					$arSKU[$ar['PRODUCT_ID']][$ar['STORE_ID']] = $ar['AMOUNT'];
				}
			} else {
				$arSKU = array();
			}
			$arCache['PRODUCT_SKU'][$arFields['PRODUCT_ID']] = $arSKU;
		}
		if (!empty($arSKU)) {
			$bHas = false;
			foreach ($arSKU as &$arProduct) {
				if (isset($arProduct[$arStore['ID']]) && $arProduct[$arStore['ID']] > 0) {
					$bHas = true;
					break;
				}
			}
			unset($arProduct, $arSKU);
			$AMOUNT = $bHas ? 1 : 0;
		}
		if ($AMOUNT == 0) {
			if (isset($arStorePropVal[$xmlId])) {
				unset($arStorePropVal[$xmlId]);
			}
		} else {
			if (!isset($arStorePropVal[$xmlId])) {
				$arStorePropVal[$xmlId] = $PROP_VALUE_ID;
			}
		}
		if (count($arStorePropVal) == 0) {
			$arStorePropVal[0] = null;
		}
		$arPropValues[$PROP_CODE] = $arStorePropVal;
		$arCache['PRODUCTS'][$arFields['PRODUCT_ID']]['STORE_PROP_VALS'] = $arStorePropVal;
		\CIBlockElement::SetPropertyValuesEx($arFields['PRODUCT_ID'], $IBLOCK_ID, $arPropValues);
		Manager::updateElementIndex($IBLOCK_ID, $arFields['PRODUCT_ID']);
		return $return;
	}
	
	protected static function _getGeoStore()
	{
		static $arCache = array();
		if (!isset($arCache['GEO_STORE']) && Loader::includeModule('yenisite.geoipstore')) {
			$arCache['GEO_STORE'] = array();
			//fetch all geoipstore items
			$dbRes = \CYSGeoIPStore::GetList('item', array(), array(), array('ID', 'NAME', 'SITE_ID'));
			while ($arGeoItem = $dbRes->Fetch()) {
				$arGeoItem['STORES'] = array();
				$arCache['GEO_STORE'][$arGeoItem['ID']] = $arGeoItem;
			}
			//fetch all geoipstore item2store links
			$dbRes = \CYSGeoIPStore::GetList('store');
			while ($arGeoStore = $dbRes->Fetch()) {
				$arCache['GEO_STORE'][$arGeoStore['ITEM_ID']]['STORES'][$arGeoStore['ID']] = $arGeoStore['STORE_ID'];
			}
			//fetch all geoipstore item2prices links
			$dbRes = \CYSGeoIPStore::GetList('price');
			while ($arGeoPrice = $dbRes->Fetch()) {
				$arCache['GEO_STORE'][$arGeoPrice['ITEM_ID']]['PRICES'][$arGeoPrice['PRICE_ID']] = $arGeoPrice['PRICE_CODE'];
			}
			unset($dbRes, $arGeoStore, $arGeoItem, $arGeoPrice);
		}
		return $arCache['GEO_STORE'];
	}

	/**
	 * Creates a property for store product availability
	 * To work correctly, the event should be added to the:
	 * catalog OnProductUpdate
	 * catalog OnProductAdd
	 * iblock OnIblockElementUpdate
	 * @param int|array $ID
	 * @param array $arFields
	 * @param array $arCodes
	 * @param string $propCode
	 * @param string $SITE_ID
	 * @param bool $isPro
	 * @return bool
	 * @throws \Bitrix\Main\LoaderException
	 */

	public static function updateElements($ar,$arIblockElements){
        global $USER;
        $userId = $USER->GetID();
        $obE = new \CIBlockElement();
        if(!$obE->Update($ar['ID'], array('MODIFIED_BY' => $userId,'ACTIVE' => $arIblockElements[$ar['ID']]), true))
        {
            return false;
        } else{
            return true;
        }

    }

    public static function SetAvailableStatus($ID, $arFields = array(), $arCodes = array(), $propCode = 'IS_AVAILABLE', $SITE_ID = null, $isPro = false, $geoItem = false)
    {
        static $bAllMod, $arCache;
        if (!isset($bAllMod)) {
            $bAllMod = Loader::includeModule('iblock') && Loader::includeModule('catalog');
        }
        if (!$bAllMod) return false;
        if (!is_array($ID) && (!isset($arFields['QUANTITY']) || !isset($arFields['CAN_BUY_ZERO']) || !isset($arFields['QUANTITY_TRACE']))) {
            $ID = array('ID' => $ID);
        }
        if (is_array($ID)) {
            if (!isset($arCache['FROM_IBLOCK_FIELDS'][$ID['ID']])) {
                if (\CCatalogProduct::IsExistProduct($ID['ID'])) {
                    /** @noinspection PhpDynamicAsStaticMethodCallInspection */
                    $arFields = \CCatalogProduct::GetByID($ID['ID']);
                    $arCache['FROM_IBLOCK_FIELDS'][$ID['ID']] = $arFields;
                } else {
                    $arFields = NULL;
                    $arCache['FROM_IBLOCK_FIELDS'][$ID['ID']] = NULL;
                }
            } else {
                $arFields = array_merge($arFields, $arCache['FROM_IBLOCK_FIELDS'][$ID['ID']]);
            }
            $ID = $ID['ID'];
        } else if (isset($arCache['FROM_IBLOCK_FIELDS'][$ID])) {
            // already changed as IBLOCK_ELEMENT
            if ($arFields['CAN_BUY_ZERO'] == $arCache['FROM_IBLOCK_FIELDS'][$ID]['CAN_BUY_ZERO_ORIG']
                && $arFields['QUANTITY'] == $arCache['FROM_IBLOCK_FIELDS'][$ID]['QUANTITY']
                && $arFields['QUANTITY_TRACE'] == $arCache['FROM_IBLOCK_FIELDS'][$ID]['QUANTITY_TRACE_ORIG']
            ) {
                return true;
            }
        }
        if (empty($arFields)) {
            return true;
        }
        // if is SKU - update parent product
        if (($arParent = \CCatalogSku::GetProductInfo($ID)) !== false) {
            // if parent product already get SKU in cache - refresh it
            if (isset($arCache['PRODUCT_SKU'][$arParent['ID']][$ID])) {
                $arCache['PRODUCT_SKU'][$arParent['ID']][$ID] = $arFields;
            }
            return self::SetAvailableStatus(array('ID' => $arParent['ID']), array(), $arCodes, $propCode);
        }
        if (!isset($arCache['DEFAULT_CAN_BUY_ZERO'])) {
            $arCache['DEFAULT_CAN_BUY_ZERO'] = \COption::GetOptionString("catalog", "default_can_buy_zero");
        }
        if (!isset($arCache['DEFAULT_QUANTITY_TRACE'])) {
            $arCache['DEFAULT_QUANTITY_TRACE'] = \COption::GetOptionString("catalog", "default_quantity_trace");
        }
        if ($arFields['CAN_BUY_ZERO'] == 'D') {
            $arFields['CAN_BUY_ZERO'] = $arCache['DEFAULT_CAN_BUY_ZERO'];
        }
        if ($arFields['QUANTITY_TRACE'] == 'D') {
            $arFields['QUANTITY_TRACE'] = $arCache['DEFAULT_QUANTITY_TRACE'];
        }
        //DO NOT CACHE!
        $IBLOCK_ID = IBlock::getIdByElement($ID, $SITE_ID);
        if ($IBLOCK_ID == false) return false;

        $arSKU = $arCache['PRODUCT_SKU'][$ID];
        if (!isset($arSKU)) {
            $arSKU = \CCatalogSKU::getOffersList(array($ID), $IBLOCK_ID);
            if (!empty($arSKU[$ID])) {
                $arSKU = array_keys($arSKU[$ID]);
                /** @noinspection PhpDynamicAsStaticMethodCallInspection */
                $rs = \CCatalogProduct::GetList(array(), array('@ID' => $arSKU));
                $arSKU = array();
                while ($ar = $rs->GetNext(false, false)) {
                    $arSKU[$ar['ID']] = $ar;
                }
            } else {
                $arSKU = array();
            }
            $arCache['PRODUCT_SKU'][$ID] = $arSKU;
        }
        if ($isPro) {
            $arGeoStore = self::_getGeoStore();
            foreach ($arGeoStore as $geoId => $geoItem) {
                if (isset($SITE_ID) && $geoItem['SITE_ID'] != $SITE_ID) continue;
                if (empty($geoItem['STORES'])) continue;
                self::_SetAvailableStatus($ID, $IBLOCK_ID, $propCode . '_' . $geoId, $arCodes, $arFields, $arSKU, $geoItem);
            }
            unset($geoId, $geoItem);
        } else {
            self::_SetAvailableStatus($ID, $IBLOCK_ID, $propCode, $arCodes, $arFields, $arSKU, $geoItem);
        }
        Manager::updateElementIndex($IBLOCK_ID, $ID);
        return true;
    }

    public static function CheckOnRequestStatus($action, $arPrice, $isPro = false, $arPriceCodes = array(), $SITE_ID = null, $propCode = 'IS_AVAILABLE')
    {
        static $arCache = array();
        static $bAllMod;
        if (!isset($bAllMod)) {
            $bAllMod = Loader::includeModule('iblock') && Loader::includeModule('catalog');
        }
        if (!$bAllMod) return;

        $ID = $arPrice['PRODUCT_ID'];
        $IBLOCK_ID = IBlock::getIdByElement($ID, $SITE_ID);
        if ($IBLOCK_ID == false) return;

        $geoItemId = 0;
        if ($isPro) {
            // find changed price type amongst geoip prices
            $arGeoStore = self::_getGeoStore();
            foreach ($arGeoStore as $geoId => $geoItem) {
                if (isset($SITE_ID) && $geoItem['SITE_ID'] != $SITE_ID) continue;
                if (empty($geoItem['PRICES'])) continue;
                if (!array_key_exists($arPrice['CATALOG_GROUP_ID'], $geoItem['PRICES'])) continue;
                $geoItemId = $geoId;
                $arPriceTypes = array_keys($geoItem['PRICES']);
                break;
            }
            unset($geoId, $geoItem);
            if ($geoItemId < 1) return;

            $propCode .= '_' . $geoItemId;
        } else {
            // find changed price type amongst bitrix:catalog PRICE_CODE
            $priceCodeHash = md5(serialize($arPriceCodes));
            if (!isset($arCache['CODE_2_PRICE']) || !array_key_exists($priceCodeHash, $arCache['CODE_2_PRICE'])) {
                $arCache['CODE_2_PRICE'][$priceCodeHash] = array();
                $dbRes = \CCatalogGroup::GetList(array(), array("NAME" => $arPriceCodes));
                while ($arPriceType = $dbRes->Fetch()) {
                    $arCache['CODE_2_PRICE'][$priceCodeHash][$arPriceType['ID']] = $arPriceType['ID'];
                }
            }
            $arPriceTypes = $arCache['CODE_2_PRICE'][$priceCodeHash];
            if (!array_key_exists($arPrice['CATALOG_GROUP_ID'], $arPriceTypes)) {
                return;
            }
        }

        $bCache = true;
        if (!isset(self::$onRequestCache[$ID])) self::$onRequestCache[$ID] = array();
        if (!isset(self::$onRequestCache[$ID][$geoItemId])) {
            $dbRes = \CPrice::GetList(array(), array("PRODUCT_ID" => $ID, "@CATALOG_GROUP_ID" => $arPriceTypes, ">PRICE" => 0));
            self::$onRequestCache[$ID][$geoItemId] = ($dbRes->SelectedRowsCount() < 1);
            $bCache = false;
        }

        if (self::$onRequestCache[$ID][$geoItemId]) {
            // CURRENT STATUS is ON_REQUEST
            if ($bCache) {
                if ($action == 'delete') return;
                if ($arPrice['PRICE'] <= 0) return;
                // WE HAVE NEW POSITIVE PRICE
                // SET STANDART STATUS RELATED TO CATALOG PRODUCT INFORMATION
                self::$onRequestCache[$ID][$geoItemId] = false;
                self::SetAvailableStatus($ID, array(), array(), $propCode, $SITE_ID, false, ($isPro ? $arGeoStore[$geoItemId] : false));
                return;
            }
            // DEFINED FOR THE FIRST TIME - CHANGE PROPERTY TO STATUS ON_REQUEST
        } else {
            if ($bCache == false) {
                // STATUS IS ALREADY SET BY self::SetAvailableStatus()
                return;
            }
            if ($action == 'edit' && $arPrice['PRICE'] > 0) {
                // THERE IS NO RELEVANT CHANGES
                return;
            }
            $dbRes = \CPrice::GetList(array(), array("PRODUCT_ID" => $ID, "@CATALOG_GROUP_ID" => $arPriceTypes, ">PRICE" => 0));
            if ($dbRes->SelectedRowsCount() > 0) {
                // THERE ARE STILL NON-EMPTY PRICES
                return;
            }
        }
        // SET STATUS ON_REQUEST
        $ar = array();
        self::$onRequestCache[$ID][$geoItemId] = true;
        self::_SetAvailableStatus($ID, $IBLOCK_ID, $propCode, $ar, $ar, $ar, $ar, true);
        Manager::updateElementIndex($IBLOCK_ID, $ID);
        return;
    }

    protected static function _SetAvailableStatus($ID, $IBLOCK_ID, $propCode, $arCodes, &$arFields, &$arSKU, $arGeoItem = array(), $onRequest = false)
    {
        foreach (GetModuleEvents('is.core', "OnStartSetAvailableStatus", true) as $arEvent)
            ExecuteModuleEventEx($arEvent, array(&$ID, &$IBLOCK_ID, &$propCode, &$arCodes, &$arFields, &$arSKU, &$arGeoItem, &$onRequest));
        static $arCache = array();
        if (!isset($arCache[$IBLOCK_ID])) {
            $arCache[$IBLOCK_ID] = array();
        }
        $arDefCodes = array(
            'AVAILABLE' => 'AVAILABLE',
            'FOR_ORDER' => 'FOR_ORDER',
            'ON_REQUEST' => 'ON_REQUEST',
            'NOT_AVAILABLE' => 'NOT_AVAILABLE',
        );
        $arCodes = array_merge($arDefCodes, $arCodes);

        if (!isset($arCache[$IBLOCK_ID][$propCode]['ID'])) {
            $rs = \CIBlockProperty::GetByID($propCode, $IBLOCK_ID);
            $ar = $rs->Fetch();
            if ($ar) {
                $arCache[$IBLOCK_ID][$propCode]['ID'] = $ar["ID"];
            } else {
                Loc::loadMessages(__FILE__);
                $arNewPropFields = array(
                    "NAME" => GetMessage('IS_AVAILABLE_NAME'),
                    "ACTIVE" => "Y",
                    "SORT" => "100000",
                    "CODE" => $propCode,
                    "PROPERTY_TYPE" => "L",
                    "DISPLAY_TYPE" => "F",
                    "IBLOCK_ID" => $IBLOCK_ID,
                    "SMART_FILTER" => "Y",
                    "DISPLAY_EXPANDED" => "Y",
                    "VALUES" => array(
                        0 => array(
                            "VALUE" => GetMessage('IS_AVAILABLE_Y'),
                            "DEF" => "Y",
                            "SORT" => "100",
                            'XML_ID' => $arCodes['AVAILABLE'],
                        ),
                        1 => array(
                            "VALUE" => GetMessage('IS_AVAILABLE_Z'),
                            "DEF" => "N",
                            "SORT" => "200",
                            'XML_ID' => $arCodes['FOR_ORDER'],
                        ),
                        2 => array(
                            "VALUE" => GetMessage('IS_AVAILABLE_N'),
                            "DEF" => "N",
                            "SORT" => "300",
                            'XML_ID' => $arCodes['NOT_AVAILABLE'],
                        ),
                        3 => array(
                            "VALUE" => GetMessage('IS_AVAILABLE_R'),
                            "DEF" => "N",
                            "SORT" => "250",
                            'XML_ID' => $arCodes['ON_REQUEST'],
                        )
                    ),
                );
                if (!empty($arGeoItem)) {
                    $arNewPropFields['HINT'] = $arGeoItem['NAME'] . ' (' . $arGeoItem['ID'] . ')';
                }
                $ibp = new \CIBlockProperty;
                $arCache[$IBLOCK_ID][$propCode]['ID'] = $ibp->Add($arNewPropFields);
                unset($ibp);
            }
        }

        if ($onRequest) {
            $available = $arCodes['ON_REQUEST'];
        } else {
            $available = $arCodes["NOT_AVAILABLE"];
            if (!empty($arSKU)) {
                $bHasForOrder = false;
                foreach ($arSKU as &$arItem) {
                    $quantity = $arItem['QUANTITY'];
                    if (!empty($arGeoItem)) {
                        // todo: replace geoipstore
                        $arQuantity =  \Yenisite\Geoipstore\CatalogTools::getStoresAmount($arItem['ID'], $arGeoItem['STORES']);
                        if (!empty($arQuantity) && is_array($arQuantity)) {
                            $quantity = min(array_sum($arQuantity), $quantity);
                        }
                    }
                    if ($arItem['QUANTITY_TRACE'] == 'N' || $quantity > 0) {
                        $available = $arCodes['AVAILABLE'];
                        break;
                    } else if ($quantity <= 0 && $arItem['CAN_BUY_ZERO'] == 'Y') {
                        $bHasForOrder = true;
                    }
                }
                if ($available != $arCodes['AVAILABLE'] && $bHasForOrder) {
                    $available = $arCodes['FOR_ORDER'];
                }
                unset($arItem);
            } else {
                $quantity = $arFields['QUANTITY'];
                if (!empty($arGeoItem)) {
                    // todo: replace geoipstore
                    $arQuantity =  \Yenisite\Geoipstore\CatalogTools::getStoresAmount($ID, $arGeoItem['STORES']);
                    if (!empty($arQuantity) && is_array($arQuantity)) {
                        $quantity = min(array_sum($arQuantity), $quantity);
                    }
                }
                if ($arFields['QUANTITY_TRACE'] == 'N' || $quantity > 0) {
                    $available = $arCodes['AVAILABLE'];
                } else if ($quantity <= 0 && $arFields['CAN_BUY_ZERO'] == 'Y') {
                    $available = $arCodes['FOR_ORDER'];
                }
            }
        }
        if (!isset($arCache[$IBLOCK_ID][$propCode]['VALUES'])) {
            $arCache[$IBLOCK_ID][$propCode]['VALUES'] = array();

            $arNeedCodes = array_flip($arCodes);
            $rs = \CIBlockPropertyEnum::GetList(array(), array(
                'IBLOCK_ID' => $IBLOCK_ID,
                'CODE' => $propCode,
            ));
            while ($ar = $rs->GetNext(false, false)) {
                if (isset($arNeedCodes[$ar['XML_ID']])) {
                    $arCache[$IBLOCK_ID][$propCode]['VALUES'][$ar['XML_ID']] = $ar['ID'];
                }
            }
        }
        $arSave = array($propCode => $arCache[$IBLOCK_ID][$propCode]['VALUES'][$available]);
        \CIBlockElement::SetPropertyValuesEx(
            $ID,
            $IBLOCK_ID,
            $arSave
        );
    }

    protected static function getRegularCurrencies($siteId)
	{
		static $arCurrency = array();
		static $bCurrency, $bSale;

		if (!isset($bCurrency)) $bCurrency = Loader::includeModule('currency');
		if (!isset($bSale)) $bSale = Loader::includeModule('sale');
		if (!isset($arCurrency['BASE']) && $bCurrency) {
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			/** @noinspection PhpUndefinedClassInspection */
			$arCurrency['BASE'] = \CCurrency::GetBaseCurrency();
		}
		if (!isset($arCurrency['SALE']) && $bSale && $siteId) {
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$arCurrency['SALE'] = \CSaleLang::GetLangCurrency($siteId);
		}
		return $arCurrency;
	}

	protected static function getSaleCurrencyFormat($price, $siteId)
	{
		$arCurrency = self::getRegularCurrencies($siteId);
		if ($siteId && isset($arCurrency['SALE']) && ($arCurrency['BASE'] != $arCurrency['SALE'])) {
			/** @noinspection PhpUndefinedClassInspection */
			$price = \CCurrencyRates::ConvertCurrency($price, $arCurrency['BASE'], $arCurrency['SALE']);
			$price = \CCurrencyLang::CurrencyFormat($price, $arCurrency['SALE'], true);
		}
		else {
			$price = \CCurrencyLang::CurrencyFormat($price, $arCurrency['BASE'], true);
		}
		return $price;
	}

	/**
	 * Check changed discount and start agent to cycle through all lower price forms
	 *
	 * This handler should be attached to:
	 * sale OnDiscountAdd
	 * sale OnDiscountUpdate
	 *
	 * @param int $id - discount id
	 * @param array $arFields - discount fields
	 * @param string|int $iblockCode - iblock id or iblock code
	 * @param string|false $siteId
	 * @param array $arProps - prop codes ('PRODUCT' => '', 'EMAIL' => '', 'PRICE' => '', 'PRICE_TYPE_ID' => '')
	 * @param string $eventName - mail event type name
	 */
	public static function checkLowerPriceForDiscount(
		$id, $arFields, $iblockCode = 'price_lower', $siteId = false, $arProps = array(), $eventName = 'PRICE_LOWER'
	){
		if (!array_key_exists('ACTIVE_FROM', $arFields)
		||  !array_key_exists('ACTIVE_TO', $arFields)
		||  !isset($arFields['ACTIVE'])
		||  !isset($arFields['SITE_ID'])
		){
			if (!Loader::includeModule('catalog')) return;
			$arFields = \CCatalogDiscount::GetByID($id);
		}

		if ($arFields['ACTIVE'] != 'Y') return;
		if ($arFields['SITE_ID'] != $siteId && $siteId) return;
		if (isset($arFields['ACTIVE_FROM'])
		||  isset($arFields['ACTIVE_TO'])
		){
			$ts = time();
			$actFrom = \MakeTimeStamp($arFields['ACTIVE_FROM']);
			$actTo = \MakeTimeStamp($arFields['ACTIVE_TO']);
			if (0 < $actFrom && $ts < $actFrom) return;
			if (0 < $actTo   && $ts > $actTo) return;
		}

		$iblockId = IBlock::getIdByCode($iblockCode);
		if ($iblockId < 1) return;

		$arSites = IBlock::getSites($iblockId);
		if ($siteId !== false && !in_array($siteId, $arSites)) {
			return;
		}

		$agentNameStart = "Yenisite\Core\Events\Catalog::checkLowerPriceAgent({$iblockId}, ";
		$rsAgent = \CAgent::GetList(array('ID' => 'DESC'), array('NAME' => $agentNameStart . '%', 'MODULE_ID' => self::MODULE_ID));
		while ($arAgent = $rsAgent->Fetch()) {
			\CAgent::Delete($arAgent['ID']);
		}
		$agentName = $agentNameStart . '0, '
		           . var_export($siteId,    1) . ', '
		           . var_export($arProps,   1) . ', '
		           . var_export($eventName, 1) . ');';
		\CAgent::AddAgent($agentName, self::MODULE_ID, 'N', 5);
	}

	public static function checkLowerPriceAgent($iblockId, $id, $siteId, $arProps, $eventName, $idToCheck = array())
	{
		if (!IBlock::init()) return;
		if (!Loader::includeModule('catalog')) return;
		if (!Loader::includeModule('currency')) return;

		$checksPerCall = 3; // how many feedback forms check on one agent call
		$arEventFields = array(
			'HTTP' => \CMain::IsHTTPS() ? 'https://' : 'http://'
		);
		$arCurrency = self::getRegularCurrencies($siteId);
		$lastId = 0;

		$arProps = array_merge(self::$arDefProps, $arProps);
		$arOrder = array('ID' => 'ASC');
		$arFilter = array('ACTIVE' => 'Y', 'IBLOCK_ID' => $iblockId, '>ID' => $id);
		$arNavStartParams = array('nTopCount' => $checksPerCall);
		$arSelect = array(
			'ID',
			'IBLOCK_ID',
			'CREATED_BY',
			'PROPERTY_' . $arProps['EMAIL'],
			'PROPERTY_' . $arProps['PRICE'],
			'PROPERTY_' . $arProps['PRODUCT'],
			'PROPERTY_' . $arProps['PRICE_TYPE_ID']
		);
		$rsLowerPrice = \CIBlockElement::GetList($arOrder, $arFilter, false, $arNavStartParams, $arSelect);

		while ($arLowerPrice = $rsLowerPrice->GetNext(false, false)) {
			$lastId = $arLowerPrice['ID'];
			//check iblock element on active. If product not active not need to send mail by users
			$arElement = \CIBlockElement::GetList(array(), array('ID'=>$arLowerPrice['PROPERTY_' . $arProps['PRODUCT'] . '_VALUE'], 'ACTIVE'=>'Y', 'ACTIVE_DATE'=>'Y'),false,false,array('ID'))->GetNext(false, false);
			if (!$arElement) continue;
		
			$arFilter = array(
				'PRODUCT_ID' => $arLowerPrice['PROPERTY_' . $arProps['PRODUCT'] . '_VALUE'],
				'CATALOG_GROUP_ID' => $arLowerPrice['PROPERTY_' . $arProps['PRICE_TYPE_ID'] . '_VALUE']
			);
			$arPrice = \CPrice::GetList(array(), $arFilter)->Fetch();
			if (!$arPrice) continue;

			$arUserGroups = \CUser::GetUserGroup($arLowerPrice['CREATED_BY']);
			$arOptimalPrice = \CCatalogProduct::GetOptimalPrice($arFilter['PRODUCT_ID'], 1, $arUserGroups, "N", array($arPrice), $siteId);
			if (is_array($arOptimalPrice)) {
				$arPrice['PRICE'] = $arOptimalPrice['DISCOUNT_PRICE'];
			}
			elseif ($arCurrency['BASE'] != $arPrice['CURRENCY']) {
				//iblock 'price_lower_'.SITE_ID stores all prices in base currency, here is prooflink:
				//https://bitbucket.org/yenisite/bitronic2/src/2183015fb9c8947926b083402c4ac08b9ba3c167/install/wizards/yenisite/bitronic2/site/public/ru/ajax/detail_modals.php?at=master&fileviewer=file-view-default#detail_modals.php-28
				$arPrice['PRICE'] = \CCurrencyRates::ConvertCurrency($arPrice['PRICE'], $arPrice['CURRENCY'], $arCurrency['BASE']);
			}
			if (floatval($arLowerPrice['PROPERTY_'.$arProps['PRICE'].'_VALUE']) < floatval($arPrice['PRICE'])) continue;

			$arEventFields['NEW_PRICE'] = self::getSaleCurrencyFormat($arPrice['PRICE'], $siteId);
			self::sendFeedbackEmail($arLowerPrice, $arProps, $eventName, $arEventFields, $siteId);
		}
		if ($rsLowerPrice->SelectedRowsCount() < $checksPerCall) return;

		$agentName = "Yenisite\Core\Events\Catalog::checkLowerPriceAgent({$iblockId}, {$lastId}, '{$siteId}', "
		           . var_export($arProps, 1) . ", '{$eventName}');";
		return $agentName;
	}

	/**
	 * Sends a Email with the change in price for the product
	 *
	 * To work correctly, the event should be added to the:
	 * catalog OnPriceUpdate
	 *
	 * @param $id - event field
	 * @param $arFields - event field
	 * @param string | int $IBLOCK_CODE - get IBLOCK_ID from it OR it IBLOCK_ID if is a number
	 * @param string $SITE_ID
	 * @param array $arProps - array with prop names ("PRODUCT" => 'TOVAR' , 'EMAIL' => 'PISMO') etc.
	 * @param string $eventName - mail event name
	 * @return bool
	 */
	public static function SendEmailByLowerPrice($id, $arFields, $IBLOCK_CODE, $SITE_ID, $arProps = array(), $eventName = 'PRICE_LOWER')
	{
		static $bAllMod;

		if (!IBlock::init()) return false;
		if (!isset($bAllMod)) {
			$bAllMod = Loader::includeModule('currency')
				&& Loader::includeModule('sale');
		}
		if (!$bAllMod) return false;

		$IBLOCK_ID = IBlock::getIdByCode($IBLOCK_CODE);
		if ($IBLOCK_ID < 1) return;

		$arSites = IBlock::getSites($IBLOCK_ID);
		if (isset($SITE_ID) && !in_array($SITE_ID, $arSites)) {
			return;
		}

		$return = true;
		$arProps = array_merge(self::$arDefProps, $arProps);
		$arCurrency = self::getRegularCurrencies($SITE_ID);

		//It is not necessary for $arFields to contain such keys, but we need them to build correct filter
		if (!isset($arFields['CATALOG_GROUP_ID']) || !isset($arFields['PRODUCT_ID'])) {
			$arFullPrice = \CPrice::GetByID($id);
			$arFields['CATALOG_GROUP_ID'] = $arFullPrice['CATALOG_GROUP_ID'];
			$arFields['PRODUCT_ID']       = $arFullPrice['PRODUCT_ID'];
		}
		
		//check iblock element on active. If product not active not need to send mail by users
		static $arCacheElement;
		if(!isset($arCacheElement[$arFields['PRODUCT_ID']]))
			$arCacheElement[$arFields['PRODUCT_ID']] = !!\CIBlockElement::GetList(array(), array('ID'=>$arFields['PRODUCT_ID'], 'ACTIVE'=>'Y', 'ACTIVE_DATE'=>'Y'),false,false,array('ID'))->GetNext(false, false);
		if (!$arCacheElement[$arFields['PRODUCT_ID']]) return;

		$arOptimalPrice = \CCatalogProduct::GetOptimalPrice($arFields['PRODUCT_ID'], 1, array(2), "N", array($arFields), $SITE_ID);
		if (is_array($arOptimalPrice)) {
			$arFields['PRICE'] = $arOptimalPrice['DISCOUNT_PRICE'];
		}
		elseif ($arCurrency['BASE'] != $arFields['CURRENCY']) {
			//iblock 'price_lower_'.SITE_ID stores all prices in base currency, here is prooflink:
			//https://bitbucket.org/yenisite/bitronic2/src/2183015fb9c8947926b083402c4ac08b9ba3c167/install/wizards/yenisite/bitronic2/site/public/ru/ajax/detail_modals.php?at=master&fileviewer=file-view-default#detail_modals.php-28
			$arFields['PRICE'] = \CCurrencyRates::ConvertCurrency($arFields['PRICE'], $arFields['CURRENCY'], $arCurrency['BASE']);
		}
		$newPrice = self::getSaleCurrencyFormat($arFields['PRICE'], $SITE_ID);

		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$rsNeedSend = \CIBlockElement::GetList(array(), array(
			'IBLOCK_ID' => $IBLOCK_ID,
			'PROPERTY_' . $arProps['PRODUCT'] => $arFields['PRODUCT_ID'],
			'>=PROPERTY_' . $arProps['PRICE'] => $arFields['PRICE'],
			'PROPERTY_' . $arProps['PRICE_TYPE_ID'] => $arFields['CATALOG_GROUP_ID'],
			'ACTIVE' => 'Y'
		),
			false,
			false,
			array(
				'ID',
				'IBLOCK_ID',
				'PROPERTY_' . $arProps['EMAIL'],
				'PROPERTY_' . $arProps['PRICE'],
				'PROPERTY_' . $arProps['PRODUCT'])
		);
		$arEventFields = array(
			'NEW_PRICE' => $newPrice,
			'HTTP' => \CMain::IsHTTPS() ? 'https://' : 'http://'
		);
		while ($arElem = $rsNeedSend->GetNext(false, false)) {
			if (!self::sendFeedbackEmail($arElem, $arProps, $eventName, $arEventFields, $SITE_ID)) {
				$return = false;
			}
		}
		return $return;
	}

	/**
	 * Send email for one feedback form filled
	 *
	 * @param array $arFeedbackItem - array with price_lower iblock element fields
	 * @param array $arProps - codes of iblock element properties
	 * @param string $eventName - mail event type name
	 * @param array $arEventFields - common fields for event message
	 * @return bool - CEvent::Send() result
	 */
	protected static function sendFeedbackEmail($arFeedbackItem, $arProps, $eventName, $arEventFields = array(), $siteId = false)
	{
		static $arProducts = array();
		static $arSites = array();

		$elId = $arFeedbackItem['PROPERTY_' . $arProps['PRODUCT'] . '_VALUE'];
		$IBLOCK_ID = $arFeedbackItem['IBLOCK_ID'];
		$arSiteId = IBlock::getSites($IBLOCK_ID);
		$siteId = $siteId ?: $arSiteId[0];
		if (!isset($arSites[$siteId])) {
			$arSites[$siteId] = \CSite::GetByID($siteId)->Fetch();
		}
		if (!isset($arProducts[$elId])) {
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$rsItem = \CIBlockElement::GetList(array(),
				array(
					'ID' => $elId,
				),
				false,
				false,
				array(
					'ID',
					'NAME',
					'DETAIL_PAGE_URL'
				)
			);
			$arProducts[$elId] = $rsItem->GetNext();
			unset($rsItem);
		}
		$arSend = $arProducts[$elId];
		$arEventFields += array(
			'EMAIL' => $arFeedbackItem['PROPERTY_' . $arProps['EMAIL'] . '_VALUE'],
			'PRODUCT_URL' => $arSend['DETAIL_PAGE_URL'],
			'PRODUCT_NAME' => $arSend['NAME'],
		);
		$arEventFields['PRODUCT_URL_FULL'] = $arEventFields['HTTP'] . $arSites[$siteId]['SERVER_NAME'] . $arEventFields['PRODUCT_URL'];
		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		if (\CEvent::Send($eventName, $siteId, $arEventFields)) {
			//deactivate
			$el = new \CIBlockElement;
			$el->Update($arFeedbackItem['ID'], array(
				'MODIFIED_BY' => ($GLOBALS['USER'] instanceof \CUser) ? $GLOBALS['USER']->GetID() : 1,
				'ACTIVE' => 'N',
			));
			return true;
		}
		return false;
	}

	/**
	 * Sends a Email with the change in available for the product
	 * To work correctly, the event should be added to the:
	 * catalog OnProductUpdate
	 *
	 * @param $id - event field
	 * @param $arFields - event field
	 * @param string | int $IBLOCK_CODE - get IBLOCK_ID from it OR it IBLOCK_ID if is a number
	 * @param string $SITE_ID
	 * @param array $arProps - array with prop names ("PRODUCT" => 'TOVAR' , 'EMAIL' => 'PISMO') etc.
	 * @param string $eventName - mail event name
	 */
	public static function SendEmailByAvailable($id, $arFields, $IBLOCK_CODE, $SITE_ID, $arProps = array(), $eventName = 'ELEMENT_EXIST')
	{
		if (!IBlock::init()) return;
		$IBLOCK_ID = IBlock::getIdByCode($IBLOCK_CODE);
		if ($IBLOCK_ID < 1) return;

		$arSites = IBlock::getSites($IBLOCK_ID);
		if (isset($SITE_ID) && !in_array($SITE_ID, $arSites)) {
			return;
		}

		$arProps = array_merge(self::$arDefProps, $arProps);

		/** @noinspection PhpDynamicAsStaticMethodCallInspection */
		$dbRes = \CIBlockElement::GetList(array(), array('ID' => $id, 'CATALOG_AVAILABLE' => 'Y'), false, array(), array('ID'));
		if ($arRes = $dbRes->GetNext()) {
			$arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y', '=PROPERTY_' . $arProps['PRODUCT'] => $id);
			$arSelect = array(
				'ID',
				'IBLOCK_ID',
				'NAME',
				'PROPERTY_' . $arProps['EMAIL'],
				'PROPERTY_' . $arProps['PRODUCT'],
			);
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$rs = \CIBlockElement::GetList(Array(), $arFilter, false, array(), $arSelect);

			$arEventFields = array('HTTP' => \CMain::IsHTTPS() ? 'https://' : 'http://');
			while ($arElem = $rs->GetNext()) {
				self::sendFeedbackEmail($arElem, $arProps, $eventName, $arEventFields, $SITE_ID);
			}
		}
	}
}