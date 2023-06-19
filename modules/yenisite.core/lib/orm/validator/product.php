<?
namespace Yenisite\Core\Orm\Validator;

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\EntityError;
use Bitrix\Main\Entity\Field;
use Bitrix\Main\Entity\Validator\Base;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Product extends Base {
	protected $errorPhraseCode = 'RMZ_VALIDATOR_ERROR_FIELD_NOT_PRODUCT';
	protected $hasIblockModule = false;
	protected $hasCatalogModule = false;

	public function __construct($errorPhrase = null) {
		$this->hasIblockModule = Loader::includeModule('iblock');
		$this->hasCatalogModule = Loader::includeModule('catalog');
		parent::__construct($errorPhrase);
	}

	/**
	 * @param       $value
	 * @param       $primary
	 * @param array $row
	 * @param Field $field
	 *
	 * @return string|boolean|EntityError
	 */
	public function validate($value, $primary, array $row, Field $field) {
		if (!$this->hasIblockModule) {
			return Loc::getMessage('RMZ_VALIDATOR_ERROR_IBLOCK_MODULE_NOT_INSTALLED');
		}
		if (!$this->hasCatalogModule) {
			return Loc::getMessage('RMZ_VALIDATOR_ERROR_CATALOG_MODULE_NOT_INSTALLED');
		}
		$value = intval($value);
		if ($value == 0) {
			return self::getErrorMessage($value, $field);
		}
		if (\CCatalogProduct::IsExistProduct($value)) {
			return true;
		} else {
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$IBLOCK_ID = \CIBlockElement::GetIBlockByID($value);
			if (!$IBLOCK_ID) {
				$this->errorPhraseCode = 'RMZ_VALIDATOR_ERROR_FIELD_NOT_EXIST';
			} else {
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				if(\CCatalog::GetByID($IBLOCK_ID)) {
					$this->errorPhraseCode = 'RMZ_VALIDATOR_ERROR_FIELD_NOT_CATALOG';
				};
			}
			return self::getErrorMessage($value, $field);
		}
	}

}