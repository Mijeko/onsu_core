<?
use Bitrix\Main\Loader;
use Yenisite\Core\Tools;
use Bitrix\Main\Localization\Loc;
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

Loader::includeModule('yenisite.core');

Loc::loadMessages(__FILE__);
$arResult = array(
	'success' => false,
	'msg' => array(GetMessage('RZ_CORE_AJAX_NO_ACTION_PROVIDED')),
);
switch ($_REQUEST['ACTION']) {
	case 'GET_DISOUNT_BY_ID':
		Loader::includeModule('catalog');
		$ID = intval($_REQUEST['ID']);
		if ($ID > 0) {
			/**
			 * ID - ��� ������;
			 * SITE_ID - ����;
			 * ACTIVE - ���� ����������;
			 * NAME - �������� ������;
			 * COUPON - ��� ������;
			 * SORT - ������ ����������;
			 * MAX_DISCOUNT - ������������ �������� ������;
			 * TIMESTAMP_X - ���� ���������� ��������� ������;
			 * VALUE_TYPE - ��� ������ (P - � ���������, F - ������������� ��������);
			 * VALUE - �������� ������;
			 * CURRENCY - ������;
			 * RENEWAL - ���� "������ �� ���������";
			 * ACTIVE_FROM - ���� ������ �������� ������;
			 * ACTIVE_TO - ���� ��������� �������� ������.
			 */
			/** @noinspection PhpDynamicAsStaticMethodCallInspection */
			$ar = \CCatalogDiscount::GetByID($ID);
			if ($ar) {
				$arResult['success'] = true;
				$arResult['msg'] = array(
					'LID' => $ar['SITE_ID'],
					'MAX_USES' => $ar['MAX_USES'],
					'COUNT_USES' => $ar['COUNT_USES'],
					'MAX_DISCOUNT' => $ar['MAX_DISCOUNT'],
					'VALUE_TYPE' => $ar['VALUE_TYPE'],
					'VALUE' => $ar['VALUE'],
					'CURRENCY' => $ar['CURRENCY'],
					'MIN_ORDER_SUM' => $ar['MIN_ORDER_SUM'],
					'ACTIVE_FROM' => $ar['MIN_ORDER_SUM'],
					'ACTIVE_TO' => $ar['MIN_ORDER_SUM'],
				);
			} else {
				$arResult['msg'] = GetMessage('RZ_NO_DISCOUNT_WITH_ID');
			}
			break;
		}
}
$isNeedEnc = (($curEnc = Tools::getLogicalEncoding()) != 'utf-8');
if ($isNeedEnc) {
	Tools::encodeArray($arResult, $curEnc, 'utf-8');
}
echo json_encode($arResult);
die();
