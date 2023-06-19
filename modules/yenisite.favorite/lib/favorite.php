<?php
namespace Yenisite\Favorite;

use Bitrix\Main\DB\ArrayResult;
use Bitrix\Main\Entity;
use Bitrix\Main\Entity\Result;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Yenisite\Core\Orm\AdminHelper;
use Yenisite\Core\Orm\Mod;
use Yenisite\Core\Orm\Validator;

Loc::loadMessages(__FILE__);

/**
 * Class FavoriteTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> PRODUCT_ID int mandatory
 * <li> IBLOCK_ID int mandatory
 * <li> USER_ID int mandatory
 * <li> SITE_ID string(2) mandatory
 * <li> TIMESTAMP_X datetime optional default 'CURRENT_TIMESTAMP'
 * </ul>
 *
 * @package Yenisite\Favorite
 **/
class FavoriteTable extends Entity\DataManager {
	static protected $sessionName = 'YNS_USER_FAVORITE';
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName() {
		return 'yns_favorite';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap() {
		if(!Loader::includeModule('yenisite.core')) die('yenisite.core not installed!');
		return array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
				'title' => Loc::getMessage('FAVORITE_ENTITY_ID_FIELD'),
				'default_in_list' => true,
				'admin_edit' => false,
				'default' => NULL,
			),
			'PRODUCT_ID' => array(
				'data_type' => 'integer',
				'required' => true,
				//'validation' => array(__CLASS__, 'validateProductId'),
				'title' => Loc::getMessage('FAVORITE_ENTITY_PRODUCT_ID_FIELD'),
				'default_in_list' => true,
				'admin_edit' => AdminHelper::U_EDIT,
				'default' => NULL,
				'input' => 'product',
			),
			'PRODUCT' => array(
				'data_type' => '\Bitrix\Iblock\ElementTable',
				'reference' => array('=this.PRODUCT_ID' => 'ref.ID'),
			),
			'IBLOCK_ID' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('FAVORITE_ENTITY_IBLOCK_ID_FIELD'),
				'default_in_list' => true,
				'admin_edit' => AdminHelper::U_EDIT,
				'default' => NULL,
			),
			'USER_ID' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('FAVORITE_ENTITY_DISCOUNT_ID_FIELD'),
				'default_in_list' => true,
				'admin_edit' => AdminHelper::U_EDIT,
				'default' => NULL,
				'input' => 'discount',
			),
			'USER' => array(
				'data_type' => '\Bitrix\Main\UserTable',
				'reference' => array('=this.USER_ID' => 'ref.ID'),
			),
			'SITE_ID' => array(
				'data_type' => 'text',
				'title' => Loc::getMessage('FAVORITE_ENTITY_SITE_ID_FIELD'),
				'default_in_list' => true,
				'admin_edit' => AdminHelper::U_EDIT,
				'default' => NULL,
			),
			'TIMESTAMP_X' => array(
				'data_type' => 'datetime',
				'title' => Loc::getMessage('FAVORITE_ENTITY_TIMESTAMP_X_FIELD'),
				'default_in_list' => true,
				'admin_edit' => AdminHelper::U_SHOW,
				'default' => NULL,
			),
		);
	}

	public static function getList(array $parameters = array()) {
		if (!Loader::includeModule('iblock')) {
			global $APPLICATION;
			$APPLICATION->ThrowException(GetMessage('RZ_ERROR_MODULE_IBLOCK_NOT_INSTALLED'));
			return false;
		}
		if (empty($parameters['select'])) {
			$arSelect = array();
			$arMap = self::getMap();
			foreach ($arMap as $ID => $ar) {
				if (!isset($ar['reference'])) {
					$arSelect[] = $ID;
				}
			}
			$parameters['select'] = array_merge($arSelect, array(
				'USER[]' => 'USER.*',
				'PRODUCT[]' => 'PRODUCT.*',
			));
		}
		$result = parent::getList($parameters);
		$result->addFetchDataModifier(array(new Mod\Hydrate(), 'exec'));
		return $result;
	}

	public static function getFields() {
		return self::getMap();
	}

	public static function validateProductId() {
		return array(
			new Validator\Product(),
		);
	}
}

class Favorite extends FavoriteTable {
	static private $errors = array();

	public static function getErrors() {
		return self::$errors;
	}

	public static function checkFields(Result $result, $primary, array &$data) {
		parent::checkFields($result, $primary, $data);
	}

	public static function update($primary, array $data) {
		return false;
	}

	public static function flush($userID = false, $siteId = false) {
		global $DB;
		if (empty($userID)) {
			global $USER;
			$userID = $USER->GetID();
			if ((int)$userID == 0 && !$USER->IsAuthorized()) {
				$arFavorite = &$_SESSION[self::$sessionName];
				$arFavorite = array();
				return true;
			}
		}
		if (empty($siteId)) {
			$siteId = SITE_ID;
		}
		$sqlQuery = "DELETE FROM " . self::getTableName() . " WHERE `USER_ID` = " . intval($userID);
		if ($siteId != LANGUAGE_ID || !defined('ADMIN_SECTION') || ADMIN_SECTION !== true) {
			$sqlQuery .= " AND `SITE_ID` LIKE '" . substr($siteId, 0, 2) . "'";
		}
		if (!$DB->Query($sqlQuery)) {
			self::$errors[] = $DB->GetErrorMessage();
		}
		if (count(self::$errors) > 0) {
			return false;
		} else {
			return true;
		}
	}

	public static function add($productID, $userID = false, $siteId = false) {
		if (!intval($productID) > 0) {
			self::$errors[] = GetMessage('FAVORITE_ERROR_FIELD_PRODUCT_ID_MUST_BE_INT');
			return false;
		}
		if (empty($userID)) {
			global $USER;
			$userID = $USER->GetID();
		}
		if (empty($siteId)) {
			$siteId = SITE_ID;
		}
		if (!$userID) {
			$bAllowGuest = (\COption::GetOptionString('yenisite.favorite', 'allow_guest' , 'Y')) == 'Y';
			if(!$bAllowGuest) {
				self::$errors[] = GetMessage('FAVORITE_ERROR_USER_MUST_BE_REGISTER');
				return false;
			} else {
				$arFavorite = &$_SESSION[self::$sessionName];
				if(!is_array($arFavorite)) {
					$arFavorite = array();
				}
				if (in_array($productID, $arFavorite)) {
					self::$errors[] = GetMessage('FAVORITE_ERROR_USER_ALREADY_HAVE_PRODUCT');
					return false;
				}
				$arFavorite[] = $productID;
				return true;
			}
		}

		$rs = self::getList(array('filter' => array('USER_ID' => $userID, 'PRODUCT_ID' => $productID, 'SITE_ID' => $siteId)));
		if ($rs->fetch()) {
			self::$errors[] = GetMessage('FAVORITE_ERROR_USER_ALREADY_HAVE_PRODUCT');
			return false;
		}
		if (!Loader::includeModule('iblock')) {
			self::$errors[] = GetMessage('RZ_ERROR_MODULE_IBLOCK_NOT_INSTALLED');
			return false;
		}
		$result = parent::add(array(
			'USER_ID' => $userID,
			'PRODUCT_ID' => $productID,
			'IBLOCK_ID' => \CIBlockElement::GetIBlockByID($productID),
			'SITE_ID' => $siteId
		));
		if (!$result->isSuccess()) {
			$err = $result->getErrors();
			foreach ($err as $item) {
				self::$errors[] = $item->getMessage();
			}
		}
		if (count(self::$errors) > 0) {
			return false;
		} else {
			return $result->getId();
		}
	}

	public static function delete($productID, $userID = false, $siteId = false, $bCheckSKU = true) {
		if (!intval($productID) > 0) {
			self::$errors[] = GetMessage('FAVORITE_ERROR_FIELD_PRODUCT_ID_MUST_BE_INT');
			return false;
		}
		if (!$userID) {
			global $USER;
			$userID = $USER->GetID();
		}
		if (empty($siteId)) {
			$siteId = SITE_ID;
		}
		if ($bCheckSKU && Loader::includeModule('catalog')) {
			$arOffersList = \CCatalogSKU::getOffersList($productID);
			if (!empty($arOffersList)) {
				foreach ($arOffersList as $arOffers) {
					foreach ($arOffers as $offerID => $arOffer) {
						self::delete($offerID, $userID, $siteId, false);
					}
				}
				return (1 > count(self::$errors));
			}
		}
		if ((int)$userID == 0 && !$USER->IsAuthorized()) {
			$arFavorite = &$_SESSION[self::$sessionName];
			if (!is_array($arFavorite)) {
				$arFavorite = array();
			}
			if (($sKey = array_search($productID, $arFavorite)) !== false) {
				unset($arFavorite[$sKey]);
			}
			return true;
		}
		$rs = self::getList(array('filter' => array('=PRODUCT_ID' => $productID, '=USER_ID' => $userID, 'SITE_ID' => $siteId)));
		$ar = $rs->fetch();
		if ($ar) {
			$result = parent::delete($ar['ID']);
			if (!$result->isSuccess()) {
				$err = $result->getErrors();
				foreach ($err as $item) {
					self::$errors[] = $item->getMessage();
				}
			}
			return (1 > count(self::$errors));
		}
		return false;
	}

	public static function getList(array $parameters = array()) {
		global $USER;
		if (empty($parameters['filter']['USER_ID']) && !$USER->IsAuthorized()) {
			$arFavorite = &$_SESSION[self::$sessionName];
			if(!is_array($arFavorite)) {
				$arFavorite = array();
			}
			$ar = array();
			foreach ($arFavorite as $val) {
				$ar[]['PRODUCT_ID'] = $val;
			}
			$rs = new ArrayResult($ar);
			return $rs;
		} else {
		    if (empty($parameters['filter']['USER_ID'])){
		        global $USER;
                $parameters['filter']['USER_ID'] = $USER->GetID();
            }
            if (empty($parameters['filter']['SITE_ID'])){
                $parameters['filter']['SITE_ID'] = SITE_ID;
            }
			return parent::getList($parameters);
		}
	}

	public static function getProducts($userID = false, $siteId = false) {
		if (!$userID) {
			global $USER;
			$userID = $USER->GetID();
		}
		if (!$siteId) {
			$siteId = SITE_ID;
		}
		$arResult = array();
		$rs = self::getList(array('filter' => array('USER_ID' => $userID, 'SITE_ID' => $siteId), 'select' => array('PRODUCT_ID')));
		while ($ar = $rs->fetch()) {
			$arResult[] = $ar['PRODUCT_ID'];
		}
		return $arResult;
	}

	public static function getProductsArray($userID = false, $siteId = false) {
		if (!$userID) {
			global $USER;
			$userID = $USER->GetID();
		}
		if (!$siteId) {
			$siteId = SITE_ID;
		}
		$arResult = array();
		if (!Loader::includeModule('iblock')) {
			self::$errors[] = GetMessage('RZ_ERROR_MODULE_IBLOCK_NOT_INSTALLED');
			return $arResult;
		}
		$rs = self::getList(array('filter' => array('USER_ID' => $userID, 'SITE_ID' => $siteId), 'select' => array('PRODUCT_ID')));
		while ($ar = $rs->fetch()) {
			if (empty($ar['IBLOCK_ID'])) {
				$ar['IBLOCK_ID'] = \CIBlockElement::GetIBlockByID($ar['PRODUCT_ID']);
			}
			$arResult[] = $ar;
		}
		return $arResult;
	}

	public static function getCountWithProduct($productId, $siteId = false) {
		$productId = intval($productId);
		if ($productId <= 0) return 0;

		if ($siteId == false) {
			$siteId = SITE_ID;
		}

		$rs = parent::getList(array(
			'select' => array(
				new Entity\ExpressionField('CNT', 'COUNT(*)')
			),
			'filter' => array('PRODUCT_ID' => $productId)
		))->Fetch();

		if (!$GLOBALS['USER']->IsAuthorized()) {
			if (is_array($_SESSION[self::$sessionName])
			&& in_array($productId, $_SESSION[self::$sessionName])) {
				$rs['CNT']++;
			}
		}
		return $rs['CNT'];
	}
}
