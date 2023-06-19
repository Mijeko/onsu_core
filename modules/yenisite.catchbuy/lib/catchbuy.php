<?php
namespace Yenisite\Catchbuy;

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\Result;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Type\DateTime;
use Yenisite\Catchbuy\Validator;
use Yenisite\Catchbuy\Mod;

Loc::loadMessages(__FILE__);

/**
 * Class CatchbuyTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> PRODUCT_ID int mandatory
 * <li> DISCOUNT_ID int mandatory
 * <li> SITE_ID string(2) optional
 * <li> ACTIVE int optional
 * <li> ACTIVE_FROM datetime optional
 * <li> ACTIVE_TO datetime optional
 * <li> MAX_USES int mandatory
 * <li> COUNT_USES int mandatory
 * <li> TIMESTAMP_X datetime optional default 'CURRENT_TIMESTAMP'
 * <li> CREATED_BY int optional
 * <li> MODIFIED_BY int optional
 * </ul>
 *
 * @package Yenisite\Catchbuy
 **/
class CatchbuyTable extends Entity\DataManager {
	private static $arFields;

	//const for cahce data and list sales of catchbuy
	const cacheIDForList = 'catchbuy_list';
	const cacheDirForList = '/romza/catchbuy/list';
	const cacheIDForAllData = 'catchbuy_data_';
	const cacheDirForData = '/romza/catchbuy/data';
	const cacheTime = 2764800;

	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName() {
		return 'yns_catchbuy';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap() {
		return array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
				'title' => Loc::getMessage('CATCHBUY_ENTITY_ID_FIELD'),
				'default_in_list' => true,
				'admin_edit' => false,
				'default' => NULL,
			),
			'PRODUCT_ID' => array(
				'data_type' => 'integer',
				'required' => true,
				'validation' => array(__CLASS__, 'validateProductId'),
				'title' => Loc::getMessage('CATCHBUY_ENTITY_PRODUCT_ID_FIELD'),
				'default_in_list' => true,
				'admin_edit' => Tools::U_EDIT,
				'default' => NULL,
				'input' => 'product',
			),
			'DISCOUNT_ID' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('CATCHBUY_ENTITY_DISCOUNT_ID_FIELD'),
				'default_in_list' => true,
				'admin_edit' => Tools::U_EDIT,
				'default' => NULL,
				'input' => 'discount',
			),
			'DISCOUNT' => array(
				'data_type' => 'Bitrix\Catalog\DiscountTable',
				'reference' => array('=this.DISCOUNT_ID' => 'ref.ID'),
			),
			'LID' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateLID'),
				'title' => Loc::getMessage('CATCHBUY_ENTITY_LID_FIELD'),
				'default_in_list' => true,
				'admin_edit' => Tools::U_EDIT,
				'default' => NULL,
				'input' => 'select',
			),
			'ACTIVE' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateActive'),
				'title' => Loc::getMessage('CATCHBUY_ENTITY_ACTIVE_FIELD'),
				'default_in_list' => true,
				'admin_edit' => Tools::U_EDIT,
				'default' => 'Y',
				'input' => 'checkbox',
			),
			'ACTIVE_FROM' => array(
				'data_type' => 'datetime',
				'title' => Loc::getMessage('CATCHBUY_ENTITY_ACTIVE_FROM_FIELD'),
				'default_in_list' => false,
				'admin_edit' => Tools::U_EDIT,
				'default' => NULL,
				'save_data_modification' => array(__CLASS__, 'BeforeSaveDateTime'),
				'input' => 'date',
			),
			'ACTIVE_TO' => array(
				'data_type' => 'datetime',
				'title' => Loc::getMessage('CATCHBUY_ENTITY_ACTIVE_TO_FIELD'),
				'default_in_list' => false,
				'admin_edit' => Tools::U_EDIT,
				'default' => NULL,
				'save_data_modification' => array(__CLASS__, 'BeforeSaveDateTime'),
				'input' => 'date',
			),
			'MAX_USES' => array(
				'data_type' => 'integer',
				'required' => false,
				'title' => Loc::getMessage('CATCHBUY_ENTITY_MAX_USES_FIELD'),
				'admin_edit' => Tools::U_EDIT,
				'input' => 'text',
				'default_value' => 0,
			),
			'COUNT_USES' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('CATCHBUY_ENTITY_COUNT_USES_FIELD'),
				'admin_edit' => Tools::U_SHOW,
				'default_value' => 0,
			),
			'TIMESTAMP_X' => array(
				'data_type' => 'datetime',
				'title' => Loc::getMessage('CATCHBUY_ENTITY_TIMESTAMP_X_FIELD'),
				'default_in_list' => true,
				'admin_edit' => Tools::U_SHOW,
				'default' => NULL,
			),
			'CREATED_BY' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('CATCHBUY_ENTITY_CREATED_BY_FIELD'),
				'default_in_list' => false,
				'admin_edit' => Tools::U_SHOW,
				'default' => NULL,
				'input' => 'user',
			),
			'MODIFIED_BY' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('CATCHBUY_ENTITY_MODIFIED_BY_FIELD'),
				'default_in_list' => false,
				'admin_edit' => Tools::U_SHOW,
				'default' => NULL,
				'input' => 'user',
			),
		);
	}


	public static function getList(array $parameters = array()) {
		if (!Loader::includeModule('catalog')) {
			global $APPLICATION;
			$APPLICATION->ThrowException(GetMessage('RZ_ERROR_MODULE_CATALOG_NOT_INSTALLED'));
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
				'DISCOUNT[]' => 'DISCOUNT.*',
			));
		}

		if (isset($parameters['filter']['ACTIVE'])
			&& $parameters['filter']['ACTIVE'] == 'Y'
			&& empty($parameters['filter']['ACTIVE_TO'])
			&& empty($parameters['filter']['ACTIVE_FROM'])
		) {
			$NOW = DateTime::createFromPhp(new \DateTime());
			$parameters['filter'][] = array(
				'LOGIC' => 'OR',
				'>=ACTIVE_TO' => $NOW,
				'ACTIVE_TO' => false,
			);
			$parameters['filter'][] = array(
				'LOGIC' => 'OR',
				'<=ACTIVE_FROM' => $NOW,
				'ACTIVE_FROM' => false,
			);
		}
		$result = parent::getList($parameters);
		$result->addFetchDataModifier(array(new Mod\Hydrate(), 'exec'));
		return $result;
	}

	public static function getFields() {
		if (!empty(self::$arFields)) {
			return self::$arFields;
		}
		$arSites = array();
		$arSitesDescr = array();
		$rs = \CSite::GetList($by = "sort", $order = "asc");
		while ($ar = $rs->Fetch()) {
			$arSites[] = $ar['ID'];
			$arSitesDescr[] = $ar['NAME'];
		}

		$arMap = self::getMap();
		$arMap['LID']['values'] = $arSites;
		$arMap['LID']['values_descr'] = $arSitesDescr;

		$arDiscount = array();
		$arDiscountDescr = array();
		$rs = \CCatalogDiscount::GetList();
		while ($ar = $rs->Fetch()) {
			$arDiscount[] = $ar['ID'];
			$arDiscountDescr[] = $ar['NAME'];
		}
		$arMap['DISCOUNT_ID']['values'] = $arDiscount;
		$arMap['DISCOUNT_ID']['values_descr'] = $arDiscountDescr;

		self::$arFields = $arMap;
		return $arMap;
	}

	/**
	 * Returns validators for LID field.
	 *
	 * @return array
	 */
	public static function validateLID() {
		return array(
			new Entity\Validator\Length(null, 2),
		);
	}

	public static function validateActive() {
		return array(
			new Entity\Validator\Length(null, 1),
			new Validator\Yn(),
		);
	}

	public static function validateProductId() {
		return array(
			new Validator\Product(),
			new Entity\Validator\Unique(),
		);
	}

	public static function BeforeSaveDateTime() {
		return array(
			array(new Mod\Datetime, 'ConverToDateTime'),
		);
	}
}

class Catchbuy extends CatchbuyTable {
	static private $errors = array();

	public static function getErrors() {
		return self::$errors;
	}

	public static function discountUpdateCreate(&$arData) {
		$result = false;
		$arConditions = array(
			'CLASS_ID' => 'CondGroup',
			'DATA' => array('All' => 'OR', 'True' => 'True'),
			'CHILDREN' => array()
		);
		if ($arData['ACTIVE'] == 'Y') {
			$arConditions['CHILDREN'][] = array(
				'CLASS_ID' => 'CondIBElement',
				'DATA' => array(
					'logic' => 'Equal',
					'value' => $arData['PRODUCT_ID'],
				)
			);
		}
		$arFields = array(
			'SITE_ID' => $arData['LID'],
			'ACTIVE' => 'Y',
			'ACTIVE_FROM' => $arData['ACTIVE_FROM'],
			'ACTIVE_TO' => $arData['ACTIVE_TO'],
			'VALUE_TYPE' => $arData['DISCOUNT']['VALUE_TYPE'],
			'VALUE' => $arData['DISCOUNT']['VALUE'],
			'CURRENCY' => $arData['DISCOUNT']['CURRENCY'],
			'CONDITIONS' => $arConditions,
		);

		if (intval($arData['DISCOUNT_ID']) > 0) {
			//get condition of discount

			$rsDiscount = \CCatalogDiscount::GetList(array(),array('ID' => $arData['DISCOUNT_ID']), false,false, array());
			while($arDiscount = $rsDiscount->Fetch()){
				$condition = unserialize($arDiscount['CONDITIONS']);
			}

			// update discount
			$rs = \CCatalogDiscount::GetDiscountProductsList(array(), array('DISCOUNT_ID' => $arData['DISCOUNT_ID']));
			while ($ar = $rs->Fetch()) {
				if ($ar['PRODUCT_ID'] != $arData['PRODUCT_ID'] && !empty($condition['CHILDREN'])) {
					$arConditions['CHILDREN'][] = array(
						'CLASS_ID' => 'CondIBElement',
						'DATA' => array(
							'logic' => 'Equal',
							'value' => $ar['PRODUCT_ID'],
						)
					);
				}
			}
			$arFields['CONDITIONS'] = $arConditions;

			$res = \CCatalogDiscount::update($arData['DISCOUNT_ID'], $arFields);
			if (!$res) {
				global $APPLICATION;
				$ex = $APPLICATION->GetException();
				self::$errors[] = $ex->GetString();
			}

			$rsDiscount = \CCatalogDiscount::GetList(array(),array('ID' => $arData['DISCOUNT_ID']), false,false, array());
			while($arDiscount = $rsDiscount->Fetch()){
				$condition = unserialize($arDiscount['CONDITIONS']);
			}

			if(empty($condition['CHILDREN'])){
				$arFields = array(
					'SITE_ID' => $arData['LID'],
					'ACTIVE' => 'N');

				$res = \CCatalogDiscount::update($arData['DISCOUNT_ID'], $arFields);
				if (!$res) {
					global $APPLICATION;
					$ex = $APPLICATION->GetException();
					self::$errors[] = $ex->GetString();
				}
			}

		} else {
			//create discount
			$dValue = ($arData['DISCOUNT']['VALUE_TYPE'] == 'P')
				? round($arData['DISCOUNT']['VALUE'], 2) . '%'
				: \CCurrencyLang::CurrencyFormat($arData['DISCOUNT']['VALUE'], $arData['DISCOUNT']['CURRENCY']);
			$arFields['NAME'] = GetMessage('CATCHBUY_ENTITY_DEFAULT_DISCOUNT_NAME', array('#VALUE#' => $dValue));
			$res = \CCatalogDiscount::Add($arFields);
			if (!$res) {
				global $APPLICATION;
				$ex = $APPLICATION->GetException();
				self::$errors[] = $ex->GetString();
			} else {
				$arData['DISCOUNT_ID'] = $res;
			}
		}
		return $result;
	}

	public static function updateDiscountStatus(&$arData){
		$arConditions = array(
			'CLASS_ID' => 'CondGroup',
			'DATA' => array('All' => 'OR', 'True' => 'True'),
			'CHILDREN' => array()
		);

		$arFields = array(
			'CONDITIONS' => $arConditions,
		);

		$rsDiscount = \CCatalogDiscount::GetList(array(),array('ID' => $arData['DISCOUNT_ID']), false,false, array());
		while($arDiscount = $rsDiscount->Fetch()){
			$condition = unserialize($arDiscount['CONDITIONS']);
		}

		// update discount
		$rs = \CCatalogDiscount::GetDiscountProductsList(array(), array('DISCOUNT_ID' => $arData['DISCOUNT_ID']));
		while ($ar = $rs->Fetch()) {
			if ($ar['PRODUCT_ID'] != $arData['PRODUCT_ID'] && !empty($condition['CHILDREN'])) {
				$arConditions['CHILDREN'][] = array(
					'CLASS_ID' => 'CondIBElement',
					'DATA' => array(
						'logic' => 'Equal',
						'value' => $ar['PRODUCT_ID'],
					)
				);
			}
		}

		$arFields['CONDITIONS'] = $arConditions;

		$res = \CCatalogDiscount::update($arData['DISCOUNT_ID'], $arFields);
		if (!$res) {
			global $APPLICATION;
			$ex = $APPLICATION->GetException();
			self::$errors[] = $ex->GetString();
		}

		$rsDiscount = \CCatalogDiscount::GetList(array(),array('ID' => $arData['DISCOUNT_ID']), false,false, array());
		while($arDiscount = $rsDiscount->Fetch()){
			$condition = unserialize($arDiscount['CONDITIONS']);
		}

		if(empty($condition['CHILDREN'])){
			$arFields = array(
				'SITE_ID' => $arData['LID'],
				'ACTIVE' => 'N');

			$res = \CCatalogDiscount::update($arData['DISCOUNT_ID'], $arFields);
			if (!$res) {
				global $APPLICATION;
				$ex = $APPLICATION->GetException();
				self::$errors[] = $ex->GetString();
			}
		}
	}

	public static function checkFields(Result $result, $primary, array &$data) {
		parent::checkFields($result, $primary, $data);
	}

	private static function clearData(&$arData) {
		$arMap = self::getMap();
		foreach ($arData as $key => $value) {
			if ($arMap[$key]['admin_edit'] === false || !($arMap[$key]['admin_edit'] & Tools::U_EDIT)) {
				unset($arData[$key]);
			}
		}
	}

	public static function updateFromAdmin($primary, array $data) {
		if($data['DISCOUNT_ID'] !='NULL') {
				self::clearData($data);
		}
		return self::update($primary, $data);
	}

	public static function update($primary, array $data, $eventBuy = false) {
		global $USER;
		$data['MODIFIED_BY'] = $USER->GetID();

		if (isset($data['DISCOUNT_ID'])) {
			self::discountUpdateCreate($data);
			if(!empty($data['DISCOUNT'])) {
				self::clearData($data);
			}
		}
		$result = parent::update($primary, $data);
		if (!$result->isSuccess()) {
			$err = $result->getErrors();
			foreach ($err as $item) {
				self::$errors[] = $item->getMessage();
			}
		}

		if ($eventBuy){
			$rsList = self::getList(array('filter' => array(
				'=PRODUCT_ID' => $data['PRODUCT_ID'],
			)));
			while($arList = $rsList->Fetch()){
				$data['DISCOUNT_ID'] = $arList['DISCOUNT_ID'];
			}
			$arData = $result->getData();
			if ($arData['ACTIVE'] == 'N') self::updateDiscountStatus($data);
		}

		if (count(self::$errors) > 0) {
			return false;
		} else {
			self::clearTagCache($data['PRODUCT_ID']);
			return $result->getId();
		}
	}

	/**
	 * Deletes row in entity table by primary key
	 *
	 * @param mixed $primary
	 *
	 * @return Entity\DeleteResult
	 *
	 * @throws \Exception
	 */
	public static function delete($primary) {
		$rs = self::getList(array(
			'filter' => array(
				'ID' => $primary
			),
			'select' => array(
				'PRODUCT_ID'
			)
		));
		if ($ar = $rs->fetch()) {
			$result = parent::delete($primary);
			if ($result->isSuccess()) {
				self::clearTagCache($ar['PRODUCT_ID']);
			}
		} else {
			$result = new Entity\DeleteResult();
			$result->addError(new Entity\EntityError(Loc::getMessage('RZ_ERROR_DELETE_NOT_EXIST', array('#ID#' => $primary))));
		}
		return $result;
	}

	public static function add(array $data) {
		global $USER;
		$data['CREATED_BY'] = $USER->GetID();
		$data['MODIFIED_BY'] = $USER->GetID();

		self::discountUpdateCreate($data);

		self::clearData($data);
		$result = parent::add($data);
		if (!$result->isSuccess()) {
			$err = $result->getErrors();
			foreach ($err as $item) {
				self::$errors[] = $item->getMessage();
			}
		}

		if (count(self::$errors) > 0) {
			return false;
		} else {
			self::clearTagCache($data['PRODUCT_ID']);
			return $result->getId();
		}
	}

	public static function isProductExist($productID) {
		if (intval($productID) == 0) return false;
		$rs = self::getList(array('filter' => array(
			'=PRODUCT_ID' => $productID,
			'ACTIVE' => 'Y'
		)));
		if ($rs->fetch()) {
			return true;
		}
		return false;
	}

	public static function clearTagCache($PRODUCT_ID) {
		static $arCache;
		if (!isset($arCache[$PRODUCT_ID])) {
			$iblockID = \CIBlockElement::GetIBlockByID($PRODUCT_ID);
		} else {
			$iblockID = $arCache[$PRODUCT_ID];
		}
		\CIBlock::clearIblockTagCache($iblockID);
	}

	//clear cahce of sales and data catchbuy
	public static function clearTagCacheCatchbuy($idCatchbuy){
		if(defined("BX_COMP_MANAGED_CACHE"))
		{
			global $CACHE_MANAGER;
			$CACHE_MANAGER->ClearByTag(self::cacheIDForList);
			$CACHE_MANAGER->ClearByTag(self::cacheIDForAllData.$idCatchbuy);
		}
	}

	//get data from cache for list of catchduy
	public static function getListCatchbuyFromCache ($SITE_ID){
		$obCache = new \CPHPCache();
		$arListCatchBuy = array();
		if ($obCache->InitCache(self::cacheTime, self::cacheIDForList, self::cacheDirForList))
		{
			$arListCatchBuy = $obCache->GetVars();
		}
		elseif ($obCache->StartDataCache())
		{
			if(defined("BX_COMP_MANAGED_CACHE"))
			{
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache(self::cacheDirForList);
			}

			$rs = self::getList(
				array(
					'filter' => array(
						'ACTIVE' => 'Y',
						'LID' => $SITE_ID
					),
					'select' => array(
						'ID',
						'PRODUCT_ID',
					)
				));
			while($arList = $rs -> Fetch()){
				$arListCatchBuy[$arList['PRODUCT_ID']] =  $arList;
			}

			if(defined("BX_COMP_MANAGED_CACHE"))
			{
				$CACHE_MANAGER->RegisterTag(self::cacheIDForList);
				$CACHE_MANAGER->EndTagCache();
			}

			$obCache->EndDataCache($arListCatchBuy);
		}
		return $arListCatchBuy;
	}

	//get data from cache for data of catchduy
	public static function getDataCatchbuyFromCache ($SITE_ID, $ID){
		$obCache = new \CPHPCache();
		$arDataCatchBuy = array();
		if ($obCache->InitCache(self::cacheTime, self::cacheIDForAllData.$ID, self::cacheDirForData))
		{
			$arDataCatchBuy = $obCache->GetVars();
		}
		elseif ($obCache->StartDataCache())
		{
			if(defined("BX_COMP_MANAGED_CACHE"))
			{
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache(self::cacheDirForData);
			}
			$rs = self::getList(
				array(
					'filter' => array(
						'ACTIVE' => 'Y',
						'LID' => $SITE_ID,
						'ID' => $ID
					),
					'select' => array(
						'ID',
						'PRODUCT_ID',
						'MAX_USES',
						'COUNT_USES'
					)
				));
			while($arData = $rs -> Fetch()){
				$arDataCatchBuy[$arData['PRODUCT_ID']] =  $arData;
			}

			if(defined("BX_COMP_MANAGED_CACHE"))
			{
				$CACHE_MANAGER->RegisterTag(self::cacheIDForAllData.$ID);
				$CACHE_MANAGER->EndTagCache();
			}

			$obCache->EndDataCache($arDataCatchBuy);
		}
		return $arDataCatchBuy;
	}
}