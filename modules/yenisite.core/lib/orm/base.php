<?php
namespace Yenisite\Core\Orm;

use Bitrix\Main\Entity\DataManager;

interface Entity {
	public function getFields();

	public function getErrors();

	public function addError($msg);
}

abstract class Base extends DataManager implements Entity {
	protected static $arFields = array();
	protected static $arErrors = array();

	public function getFields() {
		if (self::$arFields) {
			return self::$arFields;
		} else {
			return self::getMap();
		}
	}

	public function addError($msg) {
		if (is_array($msg)) {
			self::$arErrors += $msg;
		} else {
			self::$arErrors[] = $msg;
		}
	}

	public function getErrors() {
		return self::$arErrors;
	}
}