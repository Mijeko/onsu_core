<?php
namespace Yenisite\Resizer2;

use \Bitrix\Main as BXMain;
use \Bitrix\Main\Localization\Loc;

/**
 * Class SetFileTable
 *
 * Fields:
 * <ul>
 * <li> KEY string(32) mandatory
 * <li> SET_DIR string mandatory
 * <li> FILE_ID int mandatory
 * </ul>
 *
 **/
class SetFileTable extends BXMain\Entity\DataManager
{
	/**
	 * @var array $arCache Static cache for table rows
	 */
	private static $arCache = array();

	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'yen_resizer2_set_file';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			'KEY' => array(
				'data_type' => 'string',
				'primary' => true,
				'title' => Loc::getMessage('RESIZER_SET_FILE_ENTITY_KEY_FIELD'),
			),
			'SET_DIR' => array(
				'data_type' => 'string',
				'primary' => true,
				'title' => Loc::getMessage('RESIZER_SET_FILE_ENTITY_SET_DIR_FIELD'),
			),
			'FILE_ID' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('RESIZER_SET_FILE_ENTITY_FILE_ID_FIELD'),
			),
		);
	}

	/**
	 * Delete all files and links for given set
	 *
	 * @param int $setDir Resizer set cache directory
	 */
	public static function deleteBySet($setDir)
	{
		$arPrimaryKey = array_flip(static::getEntity()->getPrimaryArray());

		$rs = self::getList(array(
			'filter' => array(
				'LOGIC' => 'OR',
				'SET_DIR' => $setDir,
				'%=SET_DIR' => $setDir . '\\_%'
			)
		));
		while ($arSetFile = $rs->Fetch()) {
			$arPrimary = array_intersect_key($arSetFile, $arPrimaryKey);

			\CFile::Delete($arSetFile['FILE_ID']);
			static::delete($arPrimary);
		}
		// clear static cache
		foreach (self::$arCache as $key => &$ar) {
			if (strpos($key, $setDir . '_') !== 0) continue;
			unset(self::$arCache[$key]);
		}
		if (isset($ar)) {
			unset($ar);
		}
	}

	/**
	 * Fetch all rows for given set.
	 *
	 * Query all records from database and write to cache.
	 * Read from cache on subsequent calls.
	 *
	 * @param int $setDir Resizer set cache directory
	 *
	 * @return int[] Array of file links ('KEY' => FILE_ID)
	 */
	public static function getAllBySet($setDir)
	{
		if (!isset(self::$arCache[$setDir])) {
			$arSet =& self::$arCache[$setDir];
			$arSet = array();

			$rs = self::getList(array(
				'filter' => array('SET_DIR' => $setDir)
			));
			while ($arSetFile = $rs->Fetch()) {
				$arSet[$arSetFile['KEY']] = $arSetFile['FILE_ID'];
			}
			unset($arSet);
		}
		return self::$arCache[$setDir];
	}

	/**
	 * Get id for b_file by SET_DIR and KEY
	 *
	 * @param int $setDir Resizer set cache directory
	 * @param string $key Original image md5 hash
	 *
	 * @return int|NULL File id or NULL if file is not registered
	 */
	public static function getFile($setDir, $key)
	{
		$arSetFile = self::getAllBySet($setDir);

		return $arSetFile[$key];
	}

	/**
	 * Add new row to the table.
	 *
	 * @param array $data (KEY, SET_DIR, FILE_ID)
	 *
	 * @return \Bitrix\Main\Entity\AddResult
	 */
	public static function add(array $data)
	{
		$result = parent::add($data);

		if (
			$result->isSuccess() &&
			isset(self::$arCache[$data['SET_DIR']])
		) {
			self::$arCache[$data['SET_DIR']][$data['KEY']] = $data['FILE_ID'];
		}

		return $result;
	}
}