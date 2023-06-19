<?php

/**
 *
 *
 *
 */
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);
//set constants with module name in MODULE_ROOT
include __DIR__ . '/../../constants.php';

class CRZBitronic2Handlers
{
	static public $_sortProps = array(
		'MIN' => 'MINIMUM_PRICE',
		'MAX' => 'MAXIMUM_PRICE'
	);

	static private $arPriceDeleted = null;

	function Redirect404($siteId = null)
	{
		if (!self::isCore()) return;
		if (SITE_ID == $siteId) {
			\Yenisite\Core\Events\Main::Redirect404();
		}
	}

	static public function DoIBlockAfterSave($SITE_ID = SITE_ID, $arg1, $arg2 = false) {
		$ELEMENT_ID = false;
		$IBLOCK_ID = false;
		$OFFERS_IBLOCK_ID = false;
		$OFFERS_PROPERTY_ID = false;
		if (CModule::IncludeModule('currency'))
			$strDefaultCurrency = CCurrency::GetBaseCurrency();

		//Check for catalog event
		if (is_array($arg2) && $arg2["PRODUCT_ID"] > 0) {
			//Get iblock element
			$rsPriceElement = CIBlockElement::GetList(
				array(),
				array(
					"ID" => $arg2["PRODUCT_ID"],
				),
				false,
				false,
				array("ID", "IBLOCK_ID")
			);
			if ($arPriceElement = $rsPriceElement->Fetch()) {
				$arCatalog = CCatalog::GetByID($arPriceElement["IBLOCK_ID"]);
				if (is_array($arCatalog)) {
					//Check if it is offers iblock
					if ($arCatalog["OFFERS"] == "Y") {
						//Find product element
						$rsElement = CIBlockElement::GetProperty(
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
			$arOffers = CIBlockPriceTools::GetOffersIBlock($arg1["IBLOCK_ID"]);
			if (is_array($arOffers)) {
				$OFFERS_IBLOCK_ID = $arOffers["OFFERS_IBLOCK_ID"];
				$OFFERS_PROPERTY_ID = $arOffers["OFFERS_PROPERTY_ID"];
			}
		} // check for catalogProductUpdate event
		elseif (intval($arg1) > 0) {
			$IBLOCK_ID = CIBlockElement::GetIBlockByID(intval($arg1));
			if ($IBLOCK_ID > 0) {
				//Check if iblock has offers
				$arOffers = CIBlockPriceTools::GetOffersIBlock($IBLOCK_ID);
				if (is_array($arOffers)) {
					$ELEMENT_ID = intval($arg1);
					$OFFERS_IBLOCK_ID = $arOffers["OFFERS_IBLOCK_ID"];
					$OFFERS_PROPERTY_ID = $arOffers["OFFERS_PROPERTY_ID"];
				}
			}
		}

		if ($ELEMENT_ID && Loader::includeModule('catalog')) {
			static $arPropCache = array();
			static $arIblockCache = array();
			if (!array_key_exists($IBLOCK_ID, $arIblockCache))
			{
				$rsSites = CIBlock::GetSite($IBLOCK_ID);
				while($arSite = $rsSites->Fetch())
					$arIblockCache[$IBLOCK_ID]['SITES'][] = $arSite["SITE_ID"];
				
				unset($rsSites, $arSite);
			}
			if(!in_array($SITE_ID, $arIblockCache[$IBLOCK_ID]['SITES'])) return;
			
			if (!array_key_exists($IBLOCK_ID, $arPropCache)) {
				//Check for MINIMAL_PRICE property
				$rsProperty = CIBlockProperty::GetByID(self::$_sortProps['MIN'], $IBLOCK_ID);
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
						
					$ibp = new CIBlockProperty;
					$arPropCache[$IBLOCK_ID] = $ibp->Add($arFields);
					
					unset($ibp);
				}
			}

			if ($arPropCache[$IBLOCK_ID]) {
				//Compose elements filter
				if ($OFFERS_IBLOCK_ID) {
					$rsOffers = CIBlockElement::GetList(
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

					if (!is_array($arProductID))
						$arProductID = array($ELEMENT_ID);
				} else
					$arProductID = array($ELEMENT_ID);

				$minPrice = false;
				$maxPrice = false;
				//Get prices
				$rsPrices = CPrice::GetList(
					array(),
					array(
						"PRODUCT_ID" => $arProductID,
					)
				);
				while ($arPrice = $rsPrices->Fetch()) {
					if (0 >= $arPrice["PRICE"]) continue;

					if (CModule::IncludeModule('currency') && $strDefaultCurrency != $arPrice['CURRENCY'])
						$arPrice["PRICE"] = CCurrencyRates::ConvertCurrency($arPrice["PRICE"], $arPrice["CURRENCY"], $strDefaultCurrency);

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
				CIBlockElement::SetPropertyValuesEx(
					$ELEMENT_ID,
					$IBLOCK_ID,
					$arSaveValues
				);
			}
		}
	}

	// because this handler use on 2 events - OnBeforeUserRegister && OnBeforeUserAdd
	static $encodeRegisterHandlers;

	static public function OnBeforeUserRegisterHandler($SITE_ID, &$arFields)
	{
		if ($SITE_ID != SITE_ID) {
			return true;
		}

		\Bitrix\Main\Loader::includeModule('yenisite.core');
		if (!self::$encodeRegisterHandlers && \Yenisite\Core\Tools::isAjax()) {
			\Bitrix\Main\Loader::includeModule('yenisite.core');
			\Yenisite\Core\Tools::encodeAjaxRequest($arFields);
			self::$encodeRegisterHandlers = true;
		}

		if (empty($arFields['EMAIL'])) {
			global $APPLICATION;
			$APPLICATION->ThrowException('User email is empty');
			return false;
		}

		if ($arFields['LOGIN'] != $arFields['EMAIL']) {
			$arFields['LOGIN']  = $arFields['EMAIL'];
		}

		if (empty($arFields['PERSONAL_PHOTO'])) {
			$arFields['PERSONAL_PHOTO'] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . '/images/' . RZ_B2_MODULE_FULL_NAME . '/avatar.png');
		}
		return true;
	}

	static public function OnBeforeUserUpdateHandler($SITE_ID, &$arFields)
	{
		if ($SITE_ID != SITE_ID) {
			return true;
		}
		if (isset($arFields['LOGIN']) && empty($arFields['EMAIL'])) {
			unset($arFields['LOGIN']);
		} else {
			if (!empty($arFields['EMAIL'])) {
				$arFields['LOGIN'] = $arFields['EMAIL'];
			}
		}
		return true;
	}

	static public function OnAfterUserRegisterHandler($SITE_ID, &$arFields)
	{
		if ($SITE_ID != SITE_ID) {
			return true;
		}

		if ($arFields['RESULT_MESSAGE']['TYPE'] == 'ERROR') {

			$msg = &$arFields['RESULT_MESSAGE']['MESSAGE'];
			IncludeModuleLangFile($_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules/main/classes/general/user.php');

			if (strpos($msg, GetMessage("USER_EXIST", array("#LOGIN#" => htmlspecialcharsbx($arFields["LOGIN"])))) === false) return true;

			$msg .= '<br>' . GetMessage('BITRONIC2_RECOVER_PASSWORD',
					array(
						'#AUTH_URL#' => SITE_DIR . 'personal/?login=yes',
						'#RECOVER_URL#' => SITE_DIR . 'personal/?forgot_password=yes'
					)
				);
			return true;
		}

		if ($_REQUEST['RZ_SUBSCRIBE_ACCEPT'] === 'Y' && CModule::IncludeModule('subscribe')) {
			$arFilter = array(
				"ACTIVE" => "Y",
				"LID" => SITE_ID,
				"VISIBLE" => "Y",
			);

			$rsRubrics = CRubric::GetList(array(), $arFilter);
			$arRubrics = array();
			while ($arRubric = $rsRubrics->GetNext()) {
				$arRubrics[] = $arRubric["ID"];
			}

			$obSubscription = new CSubscription;

			$rsSubscription = $obSubscription->GetList(array(), array("USER_ID" => $arFields['USER_ID']));
			$arSubscription = $rsSubscription->Fetch();

			if (is_array($arSubscription)) {
				$rs = $obSubscription->Update(
					$arSubscription["ID"],
					array(
						"FORMAT" => "html",
						"RUB_ID" => $arRubrics,
					),
					false
				);
			} else {
				$ID = $obSubscription->Add(array(
					"USER_ID" => $arFields['USER_ID'],
					"ACTIVE" => "Y",
					"EMAIL" => $arFields['EMAIL'],
					"FORMAT" => "html",
					"CONFIRMED" => "Y",
					"SEND_CONFIRM" => "N",
					"RUB_ID" => $arRubrics,
				));
			}
		}

		return true;
	}

	public static function StoreInProperties($SITE_ID = SITE_ID, $id, $arFields)
	{
		static $isEnabledByOption;
		if (!isset($isEnabledByOption)) {
			$isEnabledByOption = COption::GetOptionString(RZ_B2_MODULE_FULL_NAME, 'store_prop_sync', 'N') == 'Y';
		}
		if(\Bitrix\Main\Loader::includeModule('yenisite.core') && $isEnabledByOption) {
			\Yenisite\Core\Events\Catalog::StoreInProperties($id, $arFields, 'STORE_AMOUNT_BOOL', $SITE_ID);
		}
	}

	public static function OnModuleUpdate()
	{
		CAdminNotify::Add(array(
			'MESSAGE' => GetMessage('BITRONIC2_UPDATE_MESSAGE') . '<a href="' . BX_ROOT . '/admin/wizard_install.php?lang=ru&wizardName=yenisite:bitronic2&'.bitrix_sessid_get().'">' . GetMessage('BITRONIC2_UPDATE_MASTER') . '</a>.',
			'MODULE_ID' => RZ_B2_MODULE_FULL_NAME,
			'TAG' => 'RZ_BITRONIC2_UPDATE_WIZARD'
		));
	}

	public static function setGeoStoreToOrder($orderID, $arFields, $arOrder, $isNew)
	{
		if ($isNew && \Bitrix\Main\Loader::includeModule('yenisite.geoipstore')) {
			global $rz_b2_options;
			if (!empty($rz_b2_options['GEOIP']['ITEM']['NAME'])) {
				$store = $rz_b2_options['GEOIP']['ITEM']['NAME'];
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				\CSaleOrder::CommentsOrder($orderID, GetMessage('BITRONIC2_GEO_COMMENT') . $store);
			}
		}
	}

	public static function SetAvailableStatus($SITE_ID, $id, $arFields = array()) {
		if (self::isCore()) {
			\Yenisite\Core\Events\Catalog::SetAvailableStatus(
				$id, $arFields, array(), 'RZ_AVAILABLE', $SITE_ID,
				\CRZBitronic2Settings::isPro($geoip = true, $SITE_ID)
			);
		}
	}

	public static function SendEmailByLowerPrice($SITE_ID, $id, $arFields) {
		if (self::isCore()) {
			\Yenisite\Core\Events\Catalog::SendEmailByLowerPrice($id, $arFields, 'price_lower_' . $SITE_ID, $SITE_ID);
		}
	}

	public static function SendEmailByAvailable($SITE_ID, $id, $arFields) {
		if (self::isCore()) {
			\Yenisite\Core\Events\Catalog::SendEmailByAvailable($id, $arFields, 'element_exist_' . $SITE_ID, $SITE_ID);
		}
	}

	protected static function isCore()
	{
		static $isCore;
		if (!isset($isCore)) {
			$isCore = \Bitrix\Main\Loader::includeModule('yenisite.core');
		}
		return $isCore;
	}

	protected static function isPro($SITE_ID)
	{
		static $isPro = array();
		if (!isset($isPro[$SITE_ID])) {
			$isPro[$SITE_ID] = CRZBitronic2Settings::isPro($geoip = true, $SITE_ID);
		}
		return $isPro[$SITE_ID];
	}

	// Process prices for ON_REQUEST status
	public static function OnPriceChangeCheckOnRequest($SITE_ID, $id, $arFields)
	{
		if (!self::isCore()) return;

		static $arPrices = array();
		$isPro = self::isPro($SITE_ID);

		if (!$isPro && !array_key_exists($SITE_ID, $arPrices)) {
			$arParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate($SITE_ID), $SITE_ID);
			$arPrices[$SITE_ID] = $arParams['PRICE_CODE'];
		}
		\Yenisite\Core\Events\Catalog::CheckOnRequestStatus('edit', $arFields, $isPro, $arPrices[$SITE_ID], $SITE_ID);
	}

	public static function OnBeforePriceDelete($SITE_ID, $id)
	{
		self::$arPriceDeleted = \CPrice::GetByID($id);
	}

	public static function OnPriceDeleteCheckOnRequest($SITE_ID, $id)
	{
		if (self::isCore()) {
			static $arPrices = array();
			$isPro = self::isPro($SITE_ID);

			if (!$isPro && !array_key_exists($SITE_ID, $arPrices)) {
				$arParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate(), $SITE_ID);
				$arPrices[$SITE_ID] = $arParams['PRICE_CODE'];
			}
			\Yenisite\Core\Events\Catalog::CheckOnRequestStatus('delete', self::$arPriceDeleted, $isPro, $arPrices[$SITE_ID], $SITE_ID);
		}
		self::$arPriceDeleted = null;
	}

	public static function OnDiscountAddUpdate($SITE_ID, $id, $arFields)
	{
		if (!self::isCore()) return;

		\Yenisite\Core\Events\Catalog::CheckLowerPriceForDiscount($id, $arFields, 'price_lower_' . $SITE_ID, $SITE_ID);
	}

	/**
	 * Empty placeholder to prevent warnings if b2tao is installed
	 */
	public static function adminAfterAjax($SITE_ID) {}

	/**
	 * Empty placeholder to prevent warnings if b2tao is installed
	 */
	public static function processProductsInEvent($SITE_ID, $arFields, $arTemplate) {}

    public static function changeCurrencyOrder($SITE_ID, $order, $arUserResult, $request, $arParams, &$arResult) {
       if ($SITE_ID == SITE_ID){
           CRZBitronic2CatalogUtils::prepareCurrencyOfOrder($arResult);
       }
    }

    public static function removeFromSearchOfElement($arFields){
        \Bitrix\Main\Loader::includeModule('yenisite.bitronic2');
        global $rz_b2_options;
        if ($rz_b2_options['hide-not-available'] == 'Y') {
            $strStatus = CRZBitronic2CatalogUtils::getElementAvailableStatus($arFields['ITEM_ID']);
            if ($strStatus == 'NOT_AVAILABLE') {
                $arFields['BODY'] = '';
                $arFields['TITLE'] = '';
                $arFields['TAGS'] = '';
                return $arFields;
            }
        }
        return $arFields;
    }
}
?>