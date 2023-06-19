<?php
namespace Yenisite\Core\Orm\Mod;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Explode
{
	protected $delimiter;
	protected $fieldKey;

	/**
	 * ToArray constructor.
	 * @param string|array $fieldKey - field(s) to process
	 * @param string $delimiter
	 */
	function __construct($fieldKey, $delimiter = '|')
	{
		$this->delimiter = $delimiter;
		if (!is_array($fieldKey)) {
			$fieldKey = array($fieldKey);
		}
		$this->fieldKey = $fieldKey;
	}

	public function exec(array &$arData)
	{
		foreach ($this->fieldKey as $fieldKey) {
			if (!empty($arData[$fieldKey])) {
				$arValues = explode($this->delimiter, $arData[$fieldKey]);
				foreach ($arValues as &$val) {
					$val = trim($val);
				}
				$arData[$fieldKey] = $arValues;
			}
		}
	}
}