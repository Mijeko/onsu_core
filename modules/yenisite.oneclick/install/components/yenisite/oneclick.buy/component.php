<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) { // isAjax
		if (isset($_REQUEST['URL'])) { // hack SITE_ID and SITE_TEMPLATE_PATH
			$_SERVER["REQUEST_URI"] = $_REQUEST['URL'];
		}
		require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
		global $APPLICATION;
		if (!CModule::IncludeModule('yenisite.oneclick')) {
			die(GetMessage('RZ_ERR_NO_YENISITE_ONECLICK_MODULE_INSTALLED'));
		}
		\CRZ\OneClick\Tools::encodeAjaxRequest($_REQUEST);
		$APPLICATION->IncludeComponent('yenisite:oneclick.buy', $_REQUEST['template'], \CRZ\OneClick\Tools::GetDecodedArParams($_REQUEST['arparams']));
		die();
	} else {
		die();
	}
}

use Bitrix\Main,
    Bitrix\Main\Loader,
    Bitrix\Main\Config\Option,
    Bitrix\Sale,
    Bitrix\Sale\Order,
    Bitrix\Main\Application,
    Bitrix\Sale\DiscountCouponsManager;

if (!CModule::IncludeModule('sale')) die(GetMessage('RZ_ERR_NO_SALE_MODULE_INSTALLED'));
if (!CModule::IncludeModule('catalog')) die(GetMessage('RZ_ERR_NO_CATALOG_MODULE_INSTALLED'));
if (!CModule::IncludeModule('iblock')) die(GetMessage('RZ_ERR_NO_IBLOCK_MODULE_INSTALLED'));

require_once ("functions.php");

global $USER, $APPLICATION;

$arParams['FORM_ID'] = (empty($arParams['FORM_ID'])) ? $this->randString() : $arParams['FORM_ID'];

$arParams['MESSAGE_OK'] = (!empty($arParams['MESSAGE_OK'])) ? $arParams['MESSAGE_OK'] : GetMessage('RZ_MESSAGE_OK');
$arParams['ALLOW_AUTO_REGISTER'] = ($arParams['ALLOW_AUTO_REGISTER'] == 'Y') ? 'Y' : 'N';
$arParams['SEND_REGISTER_EMAIL'] = ($arParams['SEND_REGISTER_EMAIL'] == 'Y') ? 'Y' : 'N';
$arParams['USE_CAPTCHA'] = ($arParams['USE_CAPTCHA'] == 'Y') ? 'Y' : 'N';

$arParams['SITE_ID'] = trim($arParams['SITE_ID']);
if (strlen($arParams['SITE_ID']) == 0 || strlen($arParams['SITE_ID']) > 2) {
	$arParams['SITE_ID'] = SITE_ID;
}

$arParams['USER_REGISTER_EVENT_NAME'] = (empty($arParams['USER_REGISTER_EVENT_NAME'])) ? 'USER_INFO' : $arParams['USER_REGISTER_EVENT_NAME'];

$arParams['ELEMENT_ID'] = $arParams['IBLOCK_ELEMENT_ID'] = !isset($_REQUEST['BUY_SUBMIT']) && strlen($_REQUEST['BUY_SUBMIT']) <= 0 && !empty($_REQUEST['ELEMENT_ID']) ? $_REQUEST['ELEMENT_ID'] : $arParams['IBLOCK_ELEMENT_ID'];

$ELEMENT_ID = $arParams['IBLOCK_ELEMENT_ID'] = intval($arParams['IBLOCK_ELEMENT_ID']);
$arParams['QUANTITY'] = (intval($arParams['QUANTITY']) > 0) ? $arParams['QUANTITY'] : 1;

$bFirstUseCaptcha = false;
if (isset($arParams['USE_CAPTCHA_FORCE'])){
    if ($arParams['ALLOW_AUTO_REGISTER'] == 'Y' && !$USER->IsAuthorized()){
        $bFirstUseCaptcha = COption::GetOptionString('main', 'captcha_registration', 'N') === 'Y' ? !$USER->IsAuthorized() : $arParams['USE_CAPTCHA_FORCE'] == 'Y';
    }else {
        $bFirstUseCaptcha = $arParams['USE_CAPTCHA_FORCE'] == 'Y';
    }
} else{
    $bFirstUseCaptcha = !$USER->IsAuthorized();
}

$arResult = array();
$arResult['USE_CAPTCHA'] = ($bFirstUseCaptcha && ($arParams['USE_CAPTCHA'] == 'Y' || COption::GetOptionString('main', 'captcha_registration', 'N') === 'Y')) ? 'Y' : 'N';

$arParams['FIELD_CLASS'] = trim(htmlspecialcharsbx(strip_tags($arParams['FIELD_CLASS'])));
$arParams['FIELD_PLACEHOLDER'] = ($arParams['FIELD_PLACEHOLDER'] == 'Y') ? 'Y' : 'N';
$arParams['FIELD_QUANTITY'] = ($arParams['FIELD_QUANTITY'] == 'Y') ? 'Y' : 'N';

if (strlen($arParams['FIELD_CLASS']) > 0) {
	$fieldClass = ' class="' . $arParams['FIELD_CLASS'] . '"';
}

$bSetEmail = !empty($arParams['AS_EMAIL']) && $USER->IsAuthorized();
$bSetName = !empty($arParams['AS_NAME']) && $USER->IsAuthorized();
$fieldEmail = '';

$rsFields = CSaleOrderProps::GetList(array(), array('PERSON_TYPE_ID' => $arParams['PERSON_TYPE_ID']));
$arFields = array();
while ($ar = $rsFields->Fetch()) {
    $ar['CODE'] = $ar['CODE'] ? : $ar['ID'];
	$arFields[$ar['CODE']] = $ar;
	if (in_array($ar['CODE'], $arParams['SHOW_FIELDS'])) {
		$arResult['FIELDS'][$ar['CODE']] = $ar;
		if (in_array($ar['CODE'], $arParams['REQ_FIELDS'])) {
			$arResult['FIELDS'][$ar['CODE']]['REQ'] = true;
		}
		if ($ar['CODE'] == $arParams['AS_EMAIL'] || $ar['IS_EMAIL'] == 'Y') {
			$fieldEmail = $ar['CODE'];
		}
		$val = '';
		$placeHolder = '';
		// set user email as default
		if ($bSetEmail && $ar['CODE'] == $arParams['AS_EMAIL']) {
			$val = $USER->GetEmail();
		}
		if ($bSetName && $ar['CODE'] == $arParams['AS_NAME']) {
			$val = $USER->GetFormattedName();
		}
		if ($arParams['FIELD_PLACEHOLDER'] == 'Y') {
			$placeHolder = ' placeholder="' . $ar['NAME'] . '"';
		}
		if (isset($_REQUEST['FIELDS'][$ar['CODE']]) && strlen($_REQUEST['FIELDS'][$ar['CODE']]) > 0) {
			$val = $_REQUEST['FIELDS'][$ar['CODE']];
		}
		$arResult['FIELDS_VAL'][$ar['ID']] = $val;

		if (strlen($val) == 0) {
			$val = $ar['DEFAULT_VALUE'];
		}
		switch ($ar['TYPE']) {
			case 'NUMBER':
				$html = '<input type="number" name="FIELDS[' . $ar['CODE'] . ']"' . $fieldClass . $placeHolder . ' value="' . $val . '"/>';
				break;
			case 'TEXT':
			default:
				$html = '<input type="text" name="FIELDS[' . $ar['CODE'] . ']"' . $fieldClass . $placeHolder . ' value="' . $val . '"/>';
				break;
		}
		$arResult['FIELDS'][$ar['CODE']]['HTML_VALUE'] = htmlspecialcharsbx($val);
		$arResult['FIELDS'][$ar['CODE']]['HTML'] = $html;
	}
}

$arResult['HIDDEN_FIELDS'] = array();

if ($arResult['USE_CAPTCHA'] == 'Y') {
	$arResult['CAPTCHA_CODE'] = htmlspecialcharsbx($APPLICATION->CaptchaGetCode());
}
// proceed Request
if (isset($_REQUEST['BUY_SUBMIT']) && strlen($_REQUEST['BUY_SUBMIT']) > 0) {

	do {
		if (!empty($_REQUEST['FORM_ID']) && $arParams['FORM_ID'] != $_REQUEST['FORM_ID']) {
			break;
		}
		$arParams['QUANTITY'] = (intval($_REQUEST['QUANTITY']) > 0) ? $_REQUEST['QUANTITY'] : 1;
		if (intval($_REQUEST['PAY_SYSTEM_ID']) > 0) {
			$arParams['PAY_SYSTEM_ID'] = $_REQUEST['PAY_SYSTEM_ID'];
		}
		if (intval($_REQUEST['DELIVERY_ID']) > 0) {
			$arParams['DELIVERY_ID'] = $_REQUEST['DELIVERY_ID'];
		}
		if (!empty($_REQUEST['FIELDS'][$fieldEmail])) {
			$arParams['AS_EMAIL'] = $fieldEmail;
		}

		//validate
		$arResult['ERROR'] = array();
		if (isset($_REQUEST['FIELDS']) && count($_REQUEST['FIELDS']) > 0) {
			foreach ($_REQUEST['FIELDS'] as $code => $val) {
				$val = trim($val);
				//if field is req
				if (in_array($code, $arParams['REQ_FIELDS']) && strlen($val) == 0) {
					$arResult['ERROR'][$code] = GetMessage('RZ_FIELD_IS_REQ', array('#NAME#' => $arFields[$code]['NAME']));
				}
			}
		} elseif (count($arParams['REQ_FIELDS']) > 0) {
			foreach ($arParams['REQ_FIELDS'] as $code) {
				$arResult['ERROR'][$code] = GetMessage('RZ_FIELD_IS_REQ', array('#NAME#' => $arFields[$code]['NAME']));
			}
		}

		$tmpUser = array();

        if (isset($arParams['USE_CAPTCHA_FORCE']) && empty($arResult['ERROR']) && $arResult['USE_CAPTCHA'] == 'Y'){
            $CAPTCHA_OK = true;
            if ($USER->IsAuthorized()) {
                $CAPTCHA_OK = checkCAPTCHA($_REQUEST["captcha_word"], $_REQUEST["captcha_sid"]);
            } elseif (COption::GetOptionString('main', 'captcha_registration', 'N') !== 'Y'){
                $CAPTCHA_OK = checkCAPTCHA($_REQUEST["captcha_word"], $_REQUEST["captcha_sid"]);
            }
            if (!$CAPTCHA_OK)
                $arResult["ERROR"] = array(GetMessage("RZ_CAPTCHA_WRONG"));
        }

		while (empty($arResult['ERROR']) && !$USER->IsAuthorized()) {
			if ($arParams['ALLOW_AUTO_REGISTER'] == 'Y') {
				$arResult['AUTH']['USER_LOGIN'] = ((strlen($_REQUEST['USER_LOGIN']) > 0) ? htmlspecialcharsbx($_REQUEST['USER_LOGIN']) : htmlspecialcharsbx(${COption::GetOptionString('main', 'cookie_name', 'BITRIX_SM') . '_LOGIN'}));
				$arResult['POST'] = array();

				if (check_bitrix_sessid()) {
					$rnd = randString(6, '0123456789');
					if (!empty($arParams['AS_EMAIL']) && strlen($_REQUEST['FIELDS'][$arParams['AS_EMAIL']]) > 0) {
						if (check_email($_REQUEST['FIELDS'][$arParams['AS_EMAIL']])) {
							$EMAIL = $_REQUEST['FIELDS'][$arParams['AS_EMAIL']];
						} else {
							$arResult['ERROR'][] = GetMessage('RZ_ERROR_REG_BAD_EMAIL');
						}
						$EMAIL = $_REQUEST['FIELDS'][$arParams['AS_EMAIL']];
					} else {
						$EMAIL = 'buyer' . $rnd . '@' . SITE_SERVER_NAME;
					}

					if (empty($arResult['ERROR'])) {
						if (strlen($EMAIL) <= 0)
							$arResult['ERROR'][] = GetMessage('RZ_FIELD_IS_REQ', array('#NAME#' => 'Email'));
						elseif (!check_email($EMAIL))
							$arResult['ERROR'][] = GetMessage('RZ_ERROR_REG_BAD_EMAIL');

						$arResult['AUTH']['NEW_EMAIL'] = $EMAIL;
					}
					if (empty($arResult['ERROR'])) {
						if ('Y' === COption::GetOptionString("main", "new_user_email_uniq_check", "N")) {
							$tmpUser = CUser::GetList($by, $order, array('EMAIL' => $EMAIL))->fetch();
							if (!$tmpUser) {
								$tmpUser = array();
							} else {
								$registeredUserID = $tmpUser['ID'];
								break;
							}
						}

						$arResult['AUTH']['NEW_LOGIN'] = $EMAIL;

						$pos = strpos($arResult['AUTH']['NEW_LOGIN'], '@');
						if ($pos !== false)
							$_REQUEST['NEW_LOGIN'] = substr($arResult['AUTH']['NEW_LOGIN'], 0, $pos);

						if (strlen($arResult['AUTH']['NEW_LOGIN']) > 47)
							$_REQUEST['NEW_LOGIN'] = substr($arResult['AUTH']['NEW_LOGIN'], 0, 47);

						while (strlen($arResult['AUTH']['NEW_LOGIN']) < 3) {
							$arResult['AUTH']['NEW_LOGIN'] .= '_';
						}

						$dbUserLogin = CUser::GetByLogin($arResult['AUTH']['NEW_LOGIN']);
						if ($arUserLogin = $dbUserLogin->Fetch()) {
							$newLoginTmp = $arResult['AUTH']['NEW_LOGIN'];
							$uind = 0;
							do {
								$uind++;
								if ($uind == 10) {
									$arResult['AUTH']['NEW_LOGIN'] = $arResult['AUTH']['NEW_EMAIL'];
									$newLoginTmp = $arResult['AUTH']['NEW_LOGIN'];
								} elseif ($uind > 10) {
									$arResult['AUTH']['NEW_LOGIN'] = 'buyer' . time() . GetRandomCode(2);
									$newLoginTmp = $arResult['AUTH']['NEW_LOGIN'];
									break;
								} else {
									$newLoginTmp = $arResult['AUTH']['NEW_LOGIN'] . $uind;
								}
								$dbUserLogin = CUser::GetByLogin($newLoginTmp);
							} while ($arUserLogin = $dbUserLogin->Fetch());
							$arResult['AUTH']['NEW_LOGIN'] = $newLoginTmp;
						}

						$def_group = COption::GetOptionString('main', 'new_user_registration_def_group', '');
						if ($def_group != '') {
							$GROUP_ID = explode(',', $def_group);
							$arPolicy = $USER->GetGroupPolicy($GROUP_ID);
						} else {
							$arPolicy = $USER->GetGroupPolicy(array());
						}

						$password_min_length = intval($arPolicy['PASSWORD_LENGTH']);
						if ($password_min_length <= 0)
							$password_min_length = 6;
						$password_chars = array(
							'abcdefghijklnmopqrstuvwxyz',
							'ABCDEFGHIJKLNMOPQRSTUVWXYZ',
							'0123456789',
						);
						if ($arPolicy['PASSWORD_PUNCTUATION'] === 'Y')
							$password_chars[] = ",.<>/?;:'\"[]{}\|`~!@#\$%^&*()-_+=";
						$arResult['AUTH']['NEW_PASSWORD'] = $arResult['AUTH']['NEW_PASSWORD_CONFIRM'] = randString($password_min_length + 2, $password_chars);
					}

					if (empty($arResult['ERROR'])) {
						if (!empty($arParams['AS_NAME'])) {
							$arName = explode(' ', trim($_REQUEST['FIELDS'][$arParams['AS_NAME']]));

							if (count($arName) > 1) { // if FIO field F[1] = LAST_NAME, I[0] = NAME
								list($_REQUEST['NEW_LAST_NAME'], $_REQUEST['NEW_NAME']) = $arName;
							} else { // if NAME only
								$_REQUEST['NEW_NAME'] = trim($_REQUEST['FIELDS'][$arParams['AS_NAME']]);
							}
						}
						$arAuthResult = $USER->Register($arResult['AUTH']['NEW_LOGIN'], $_REQUEST['NEW_NAME'], $_REQUEST['NEW_LAST_NAME'], $arResult['AUTH']['NEW_PASSWORD'], $arResult['AUTH']['NEW_PASSWORD_CONFIRM'], $arResult['AUTH']['NEW_EMAIL'], LANG, $_REQUEST['captcha_word'], $_REQUEST['captcha_sid']);
						if ($arAuthResult != false && $arAuthResult['TYPE'] == 'ERROR')
							$arResult['ERROR'][] = GetMessage('RZ_ERROR_REG') . ((strlen($arAuthResult['MESSAGE']) > 0) ? ': ' . $arAuthResult['MESSAGE'] : '');
						else {
							if (!$USER->IsAuthorized()) {
								$arResult['ERROR'][] = GetMessage('RZ_ERROR_REG_CONFIRM');
							}
						}
					}
					if ('Y' == $arParams['SEND_REGISTER_EMAIL']) {
						CUser::SendUserInfo($USER->GetID(), SITE_ID, GetMessage("RZ_USER_REGISTER_EVENT_TITLE"), true, $arParams['USER_REGISTER_EVENT_NAME']);
					}
					$arResult['AUTH']['~NEW_LOGIN'] = $arResult['AUTH']['NEW_LOGIN'];
					$arResult['AUTH']['NEW_LOGIN'] = htmlspecialcharsEx($arResult['AUTH']['NEW_LOGIN']);
					$arResult['AUTH']['~NEW_NAME'] = $_REQUEST['NEW_NAME'];
					$arResult['AUTH']['NEW_NAME'] = htmlspecialcharsEx($_REQUEST['NEW_NAME']);
					$arResult['AUTH']['~NEW_LAST_NAME'] = $_REQUEST['NEW_LAST_NAME'];
					$arResult['AUTH']['NEW_LAST_NAME'] = htmlspecialcharsEx($_REQUEST['NEW_LAST_NAME']);
					$arResult['AUTH']['~NEW_EMAIL'] = $arResult['AUTH']['NEW_EMAIL'];
					$arResult['AUTH']['NEW_EMAIL'] = htmlspecialcharsEx($arResult['AUTH']['NEW_EMAIL']);
				} else {
					$arResult['ERROR'][] = GetMessage('RZ_CSRF_ERROR');
				}
			} else {
				$arResult['ERROR'][] = GetMessage('RZ_ONLY_AUTH_USERS');
			}
			break; // while (empty($arResult['ERROR']) && !$USER->IsAuthorized())
		}
		//proceed
		if (empty($arResult['ERROR'])) {
			$arParams['PERSON_TYPE_ID'] = intval($arParams['PERSON_TYPE_ID']);
			$currencyCode = Option::get('sale', 'default_currency', 'RUB');

			if (empty($registeredUserID)) {
				$registeredUserID = $USER->GetID();
			}

			DiscountCouponsManager::init();

			$userBasket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), SITE_ID);

			if ($_REQUEST['RZ_BASKET'] === 'Y' || $arParams['BUY_IN_BASKET'] === 'Y') {
				$basket = $userBasket;
				if ($basket->count() <= 0){
				    $arResult['ERROR']['EMPTY_BASKET'] = GetMessage('RZ_BASKET_EMPTY');
                }
			} else {
				$ELEMENT_ID = (intval($_REQUEST['ELEMENT_ID']) > 0) ? $_REQUEST['ELEMENT_ID'] : $arParams['IBLOCK_ELEMENT_ID'];
				if (!$ELEMENT_ID > 0) {
					$arResult['ERROR']['ELEMENT_ID'] = GetMessage('RZ_IBLOCK_ELEMENT_ID_EMPTY');
					break;
				}
				$arProduct = CCatalogProduct::GetByIDEx($ELEMENT_ID);
				if (!$arProduct) {
					$arResult['ERROR']['ELEMENT_ID'] = GetMessage('RZ_IBLOCK_ELEMENT_NOT_PRODUCT');
					break;
				}

				$arItemsBasketIds = array();
                foreach ($userBasket->getOrderableItems() as $itemBasket){
				    $arItemsBasketIds[$itemBasket->getProductId()] =  $itemBasket->getQuantity();
                };


				$basket = Sale\Basket::create(SITE_ID);

				$bHasCore = \Bitrix\Main\Loader::includeModule('yenisite.core');
				if ($bHasCore) {
					$arCheckResult = \Yenisite\Core\Catalog::checkoutProductPurchase($ELEMENT_ID, $arParams['QUANTITY'], 'Y' == $arParams['BOOL_SHOW_QUANT']);
					if (!$arCheckResult['success']) {
						$arResult['ERROR'] = array_merge($arResult['ERROR'], $arCheckResult['result']);
						if ($arParams['BOOL_SHOW_QUANT']) {
							$arResult['MAX_Q_FOR_ITEM'][$item->getProductId()] = $arCheckResult['q'];
						}
					}
				}
			
				if (empty($arResult['ERROR'])) {
					
					$arProps = array();
					$strIBlockXmlID = (string)CIBlock::GetArrayByID($arProduct['IBLOCK_ID'], 'XML_ID');
					if ($strIBlockXmlID !== '')
					{
						$arProps[] = array(
							'NAME' => 'Catalog XML_ID',
							'CODE' => 'CATALOG.XML_ID',
							'VALUE' => $strIBlockXmlID
						);
					}
					$arParent = CCatalogSku::GetProductInfo($arProduct['ID'], $arProduct['IBLOCK_ID']);
					if($arParent)
					{
						$arParent = CIBlockElement::GetByID($arParent['ID'])->GetNext();
						$arProduct["XML_ID"] = $arParent["XML_ID"].'#'.$arProduct["XML_ID"];
					}
					$arProps[] = array(
						"NAME" => "Product XML_ID",
						"CODE" => "PRODUCT.XML_ID",
						"VALUE" => $arProduct["XML_ID"]
					);
				
					$item = $basket->createItem('catalog', $ELEMENT_ID);
					$item->setFields(array(
						'QUANTITY' => $arParams['QUANTITY'],
						'CURRENCY' => \Bitrix\Currency\CurrencyManager::getBaseCurrency(),
						'LID' => SITE_ID,
						'PRODUCT_PROVIDER_CLASS' => 'CCatalogProductProvider',
						"PRODUCT_XML_ID" => $arProduct["~XML_ID"],
						"CATALOG_XML_ID" => $strIBlockXmlID,
					));
					$item->getPropertyCollection()->setProperty($arProps);
				}

				/* SEEMS LIKE THIS CODE IS NO LONGER NEEDED
				// if is SKU
				if (is_array(\CCatalogSku::GetProductInfo($ELEMENT_ID)) && !empty($arParams['OFFER_PROPS'])) {
					$arParams['OFFER_PROPS'] = array_flip($arParams['OFFER_PROPS']);

					$IBLOCK_ID = \CIBlockElement::GetIBlockByID($ELEMENT_ID);
					$rs = CIBlockElement::GetProperty($IBLOCK_ID, $ELEMENT_ID);
					$arBasketProps = array();

					while ($arProp = $rs->GetNext()) {
						if (isset($arParams['OFFER_PROPS'][$arProp['CODE']])) {
							$arProp = \CIBlockFormatProperties::GetDisplayValue($arProduct, $arProp, 'catalog_out');
							$arBasketProps[] = array(
								'ID' => $arProp['ID'],
								'NAME' => $arProp['NAME'],
								'VALUE' => !empty($arProp['VALUE_ENUM']) ? $arProp['VALUE_ENUM'] : $arProp['DISPLAY_VALUE'],
								'SORT' => $arProp['SORT'],
							);
						}
					}
					$item->getPropertyCollection()->setProperty($arBasketProps);
				}
				*/
			}

			if (empty($arResult['ERROR'])) {
				$order = Order::create(SITE_ID, $registeredUserID);
				$order->setPersonTypeId($arParams['PERSON_TYPE_ID']);
				$basket = $basket->getOrderableItems();

				$order->setBasket($basket);

				/*Shipment*/
				$arDelivery = \CSaleDelivery::GetByID($arParams['DELIVERY_ID']);

				$shipmentCollection = $order->getShipmentCollection();
				$shipment = $shipmentCollection->createItem();
				$shipment->setFields(array(
					'DELIVERY_ID' => $arParams['DELIVERY_ID'],
					'DELIVERY_NAME' => $arDelivery ? $arDelivery['NAME'] : '',
					'CURRENCY' => $order->getCurrency(),
				));

				$shipmentItemCollection = $shipment->getShipmentItemCollection();

				foreach ($order->getBasket() as $item) {
					$shipmentItem = $shipmentItemCollection->createItem($item);
					$shipmentItem->setQuantity($item->getQuantity());
				}

				/*Payment*/
				$arPayment = \CSalePaySystem::GetByID($arParams['PAY_SYSTEM_ID'], $arParams['PERSON_TYPE_ID']);

				$paymentCollection = $order->getPaymentCollection();
				$extPayment = $paymentCollection->createItem();
				$extPayment->setFields(array(
					'PAY_SYSTEM_ID' => $arParams['PAY_SYSTEM_ID'],
					'PAY_SYSTEM_NAME' => $arPayment ? $arPayment['NAME'] : '',
					'SUM' => $order->getPrice()
				));

				/**/
				$order->doFinalAction(true);

				$order->setField('CURRENCY', $currencyCode);
				$order->setField('COMMENTS', $arParams['COMMENTS'] ?: GetMessage('RZ_ORDER_BY_ONECLICK'));

				// set order fields
				$propertyCollection = $order->getPropertyCollection();
				foreach ($propertyCollection as $property)
				{
					if ($property->isUtil())
						continue;

					$arProperty = $property->getProperty();

					if ($arProperty['USER_PROPS'] == 'Y')
					{
						if (isset($arResult['FIELDS_VAL'][$arProperty['ID']]))
						{
							$curVal = $arResult['FIELDS_VAL'][$arProperty['ID']];
						}
						else
						{
							$curVal = '';
						}
					}
					else
					{
						$curVal = $arResult['FIELDS_VAL'][$arProperty['ID']];
					}

					if (empty($curVal))
					{
						if (!empty($arProperty["DEFAULT_VALUE"]))
						{
							$curVal = $arProperty["DEFAULT_VALUE"];
						}
					}

					if ($arProperty["TYPE"] == 'LOCATION')
					{
						if (($_REQUEST['PROFILE_ID'] === '0')
							&& $_REQUEST['location_type'] != 'code'
						)
						{
							$curVal = CSaleLocation::getLocationCODEbyID($curVal);
						}
						if (Loader::includeModule('yenisite.geoip')){
                            $locationId = CYSGeoIP::getLocationId();
                            $curVal = CSaleLocation::getLocationCODEbyID($locationId);
                        }
					}

					$arUserResult['ORDER_PROP'][$arProperty["ID"]] = $curVal;

				}		
				$res = $propertyCollection->setValuesFromPost(array('PROPERTIES' => $arUserResult['ORDER_PROP']), array());

				$result = $order->save();

				if ($result->isSuccess()) {
					$arResult['ORDER_ID'] = $order->GetId();
					$arResult["ACCOUNT_NUMBER"] = ($order->getField("ACCOUNT_NUMBER") ?: $arResult["ORDER_ID"]);
					$arResult['SUCCESS'] = str_replace('#ID#', $arResult['ACCOUNT_NUMBER'], $_REQUEST['MESSAGE_OK']);
				} else {
					$arResult['ERROR'] = $result->getErrorMessages();
				}

                // delete this product from current basket
                if (!empty($arItemsBasketIds)) {
                    foreach ($arItemsBasketIds as $productId => $quantity){
                        if ($productId == $ELEMENT_ID) continue;
                        Add2BasketByProductID($productId,$quantity);
                    }
                }
			}

			// mail message
			if (empty($arResult["ERROR"]) && !empty($arParams['AS_EMAIL']) && !empty($_REQUEST['FIELDS'][$arParams['AS_EMAIL']])) {
				$EMAIL = $_REQUEST['FIELDS'][$arParams['AS_EMAIL']];
				$strOrderList = "";
				$arBasketList = array();
				$dbBasketItems = CSaleBasket::GetList(
					array("ID" => "ASC"),
					array("ORDER_ID" => $arResult["ORDER_ID"]),
					false,
					false,
					array("ID", "PRODUCT_ID", "NAME", "QUANTITY", "PRICE", "CURRENCY", "TYPE", "SET_PARENT_ID")
				);
				while ($arItem = $dbBasketItems->Fetch()) {
					if (CSaleBasketHelper::isSetItem($arItem)) continue;

					$arBasketList[] = $arItem;
				}

				$arBasketList = getMeasures($arBasketList);

				if (!empty($arBasketList) && is_array($arBasketList)) {
					foreach ($arBasketList as $arItem) {
						$measureText = (isset($arItem["MEASURE_TEXT"]) && strlen($arItem["MEASURE_TEXT"])) ? $arItem["MEASURE_TEXT"] : GetMessage("SOA_SHT");

						$strOrderList .= $arItem["NAME"] . " - " . $arItem["QUANTITY"] . " " . $measureText . ": " . SaleFormatCurrency($arItem["PRICE"], $arItem["CURRENCY"]);
						$strOrderList .= "\n";
					}
				}
				global $DB;
				$arFields = array(
					"ORDER_ID" => $arResult['ACCOUNT_NUMBER'],
					"ORDER_DATE" => Date($DB->DateFormatToPHP(\CLang::GetDateFormat("SHORT", SITE_ID))),
					"ORDER_USER" => $tmpUser ? CUser::FormatName(CSite::GetNameFormat(false), $tmpUser, ture, true) : $USER->GetFormattedName(false),
					"PRICE" => SaleFormatCurrency($order->getPrice(), $order->getField('CURRENCY')),
					"BCC" => COption::GetOptionString("sale", "order_email", "order@" . $_SERVER['HTTP_HOST']),
					"EMAIL" => $EMAIL,
					"ORDER_LIST" => $strOrderList,
					"SALE_EMAIL" => COption::GetOptionString("sale", "order_email", "order@" . $_SERVER['HTTP_HOST']),
					"DELIVERY_PRICE" => SaleFormatCurrency($order->getDeliveryPrice(), $order->getField('CURRENCY')),
				);

				$eventName = "SALE_NEW_ORDER";

				$bSend = true;
				foreach (GetModuleEvents("sale", "OnOrderNewSendEmail", true) as $arEvent)
					if (ExecuteModuleEventEx($arEvent, array($arResult["ORDER_ID"], &$eventName, &$arFields)) === false)
						$bSend = false;

				if ($bSend) {
					$event = new CEvent;
					$event->Send($eventName, SITE_ID, $arFields, "N");
				}

				CSaleMobileOrderPush::send("ORDER_CREATED", array("ORDER_ID" => $arResult["ACCOUNT_NUMBER"]));
			}

        }
	} while (0);
}
if ($ELEMENT_ID == 0 && $_REQUEST['RZ_BASKET'] !== 'Y' && $arParams['BUY_IN_BASKET'] !== 'Y') {
	$arResult['ERROR']['ELEMENT_ID'] = GetMessage('RZ_IBLOCK_ELEMENT_ID_EMPTY');
}
$arResult['QUANTITY'] = $arParams['QUANTITY'];

if (intval($arParams['PAY_SYSTEM_ID']) > 0) {
	$arResult['HIDDEN_FIELDS']['PAY_SYSTEM_ID'] = array(
		'VALUE' => $arParams['PAY_SYSTEM_ID'],
		'CODE' => 'PAY_SYSTEM_ID',
		'HTML' => '<input type="hidden" name="PAY_SYSTEM_ID" value="' . $arParams['PAY_SYSTEM_ID'] . '"/>'
	);
}
if (intval($arParams['DELIVERY_ID']) > 0) {
	$arResult['HIDDEN_FIELDS']['DELIVERY_ID'] = array(
		'VALUE' => $arParams['DELIVERY_ID'],
		'CODE' => 'DELIVERY_ID',
		'HTML' => '<input type="hidden" name="DELIVERY_ID" value="' . $arParams['DELIVERY_ID'] . '"/>'
	);
}

$this->IncludeComponentTemplate();