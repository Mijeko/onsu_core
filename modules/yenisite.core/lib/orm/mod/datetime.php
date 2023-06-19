<?php
namespace Yenisite\Core\Orm\Mod;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Datetime {
	public function ConverToDateTime($value = '') {
		if (\Bitrix\Main\Type\DateTime::isCorrect($value)) {
			$value = new \Bitrix\Main\Type\DateTime($value);
		} else if (\Bitrix\Main\Type\Date::isCorrect($value)) {
			$value = new \Bitrix\Main\Type\DateTime($value . ' 00:00:00');
		}
		return $value;
	}
}