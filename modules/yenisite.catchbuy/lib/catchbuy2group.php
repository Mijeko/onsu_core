<?php
namespace Bitrix\Catchbuy2group;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class Catchbuy2groupTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> CATCH_ID int mandatory
 * <li> GROUP_ID int mandatory
 * </ul>
 *
 * @package Bitrix\Catchbuy2group
 **/
class Catchbuy2groupTable extends Entity\DataManager {
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName() {
		return 'yns_catchbuy2group';
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
				'title' => Loc::getMessage('CATCHBUY2GROUP_ENTITY_ID_FIELD'),
			),
			'CATCH_ID' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('CATCHBUY2GROUP_ENTITY_CATCH_ID_FIELD'),
			),
			'GROUP_ID' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('CATCHBUY2GROUP_ENTITY_GROUP_ID_FIELD'),
			),
		);
	}
}