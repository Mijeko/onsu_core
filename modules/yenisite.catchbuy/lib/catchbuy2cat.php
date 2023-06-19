<?php
namespace Yenisite\Catchbuy2cat;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class Catchbuy2catTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> CATCH_ID int mandatory
 * <li> CATALOG_GROUP_ID int mandatory
 * </ul>
 *
 * @package Bitrix\Catchbuy2cat
 **/
class Catchbuy2catTable extends Entity\DataManager {
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName() {
		return 'yns_catchbuy2cat';
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
				'title' => Loc::getMessage('CATCHBUY2CAT_ENTITY_ID_FIELD'),
			),
			'CATCH_ID' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('CATCHBUY2CAT_ENTITY_CATCH_ID_FIELD'),
			),
			'CATALOG_GROUP_ID' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('CATCHBUY2CAT_ENTITY_CATALOG_GROUP_ID_FIELD'),
			),
		);
	}
}