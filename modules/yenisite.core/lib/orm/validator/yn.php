<?
namespace Yenisite\Core\Orm\Validator;

use Bitrix\Main\Entity;
use Bitrix\Main\Entity\EntityError;
use Bitrix\Main\Entity\Field;
use Bitrix\Main\Entity\Validator\Base;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Yn extends Base {
	protected $errorPhraseCode = 'RMZ_VALIDATOR_ERROR_FIELD_Y_N';

	/**
	 * @param       $value
	 * @param       $primary
	 * @param array $row
	 * @param Field $field
	 *
	 * @return string|boolean|EntityError
	 */
	public function validate($value, $primary, array $row, Field $field) {
		if ($value == 'Y' || $value == 'N') {
			return true;
		} else {
			return self::getErrorMessage($value, $field);
		}
	}


}