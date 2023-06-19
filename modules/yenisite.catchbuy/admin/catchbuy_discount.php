<?
use Bitrix\Main\Loader;
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
Loader::includeModule('catalog');
$ID = intval($_REQUEST['ID']);
if ($ID > 0) {
	$arResult = array(
		'success' => false,
		'msg' => array(),
	);
	/**
	 * ID - код записи;
	 * SITE_ID - сайт;
	 * ACTIVE - флаг активности;
	 * NAME - название скидки;
	 * COUPON - код купона;
	 * SORT - индекс сортировки;
	 * MAX_DISCOUNT - максимальная величина скидки;
	 * TIMESTAMP_X - дата последнего изменения записи;
	 * VALUE_TYPE - тип скидки (P - в процентах, F - фиксированная величина);
	 * VALUE - величина скидки;
	 * CURRENCY - валюта;
	 * RENEWAL - флаг "Скидка на продление";
	 * ACTIVE_FROM - дата начала действия скидки;
	 * ACTIVE_TO - дата окончания действия скидки.
	 */
	$ar = \CCatalogDiscount::GetByID($ID);
	if($ar) {
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
	echo json_encode($arResult);
	die();
}
