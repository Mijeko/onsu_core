<?php
namespace Yenisite\Core\Orm\Mod;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Hydrate {
	protected $type;

	function __construct($type = 'array') {
		$this->type = $type;
	}

	public function exec(array &$arData) {
		if ($this->type == 'array') {
			$arrFields = array();
			foreach ($arData as $key => $value) {
				if(strpos($key, '[]') !== false) {
					$arField = explode('[]',$key);
					if(count($arField) > 1) {
						$arrFields[$arField[0]][$arField[1]] = $value;
						unset($arData[$key]);
					}
				}
			}
			$arData += $arrFields;
		}
	}
}