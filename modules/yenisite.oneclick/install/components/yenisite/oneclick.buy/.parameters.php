<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
if (!CModule::IncludeModule('sale')) die(GetMessage('RZ_ERR_NO_SALE_MODULE_INSTALLED'));
if (!CModule::IncludeModule('iblock')) die(GetMessage('RZ_ERR_NO_IBLOCK_MODULE_INSTALLED'));

define('MAX_LINES_IN_LIST', 6);
$arPersonTypes = array();
$rsPersonTypes = CSalePersonType::GetList(array(), array('ACTIVE' => 'Y'));
while ($ar = $rsPersonTypes->Fetch()) {
	$arPersonTypes[$ar['ID']] = '[' . $ar['LID'] . '] ' . $ar['NAME'];
}

$defDelivery = 0;
$arDelivery = array();
$rsDelivery = CSaleDelivery::GetList(array(), array('ACTIVE' => 'Y'));
while ($ar = $rsDelivery->Fetch()) {
	$arDelivery[$ar['ID']] = '[' . $ar['LID'] . '] ' . $ar['NAME'];
	if (false !== strpos(strtoupper($arDelivery[$ar['ID']]), strtoupper(GetMessage('RZ_DELIVERY_DEFAULT')))) {
		$defDelivery = $ar['ID'];
	}
}
$rsDelivery = CSaleDeliveryHandler::GetList(array(), array('ACTIVE' => 'Y'));
while ($ar = $rsDelivery->Fetch()) {
	foreach ($ar['PROFILES'] as $profileSID => $arProfile) {
		$arDelivery[$ar['SID'] . ':' . $profileSID] = '[' . $ar['SID'] . ':' . $profileSID . '] ' . $ar['NAME'] . ' : ' . $arProfile['TITLE'];
	}
}
if (empty($arDelivery)) {
	$arDelivery = array(0 => GetMessage('RZ_FIELD_DELIVERY_ID_NOT_SET'));
}
$arComponentParameters = array(
	'GROUPS' => array(
		'USER' => array(
			'NAME' => GetMessage('RZ_GROUP_USER'),
			'SORT' => '200',
		),
		'VISUAL' => array(
			'NAME' => GetMessage('RZ_GROUP_VISUAL'),
			'SORT' => '400',
		)
	),
	'PARAMETERS' => array(
		'IBLOCK_ELEMENT_ID' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('RZ_FIELD_IBLOCK_ELEMENT_ID'),
			'TYPE' => 'STRING',
			'DEFAULT' => '0',
		),
		'PERSON_TYPE_ID' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('RZ_FIELD_PERSON_TYPE_ID'),
			'TYPE' => 'LIST',
			'VALUES' => $arPersonTypes,
			'REFRESH' => 'Y',
			'DEFAULT' => '1',
		),
        'BUY_IN_BASKET' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('RZ_BUY_IN_BASKET'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'N',
		),
		'SHOW_FIELDS' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('RZ_FIELD_SHOW_FIELDS'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'REFRESH' => 'Y',
			'VALUES' => array('NULL' => GetMessage('RZ_FIELD_SHOW_FIELDS_EMPTY')),
			'SIZE' => 2,
		),
		'REQ_FIELDS' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('RZ_FIELD_REQ_FIELDS'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'VALUES' => array('NULL' => GetMessage('RZ_FIELD_REQ_FIELDS_EMPTY')),
			'SIZE' => 2,
		),
		'AS_EMAIL' => array(
			'PARENT' => 'USER',
			'NAME' => GetMessage('RZ_FIELD_AS_EMAIL'),
			'TYPE' => 'LIST',
			'VALUES' => array('NULL' => GetMessage('RZ_FIELD_AS_EMAIL_EMPTY')),
			'REFRESH' => 'Y'
		),
		'AS_NAME' => array(
			'PARENT' => 'USER',
			'NAME' => GetMessage('RZ_FIELD_AS_NAME'),
			'TYPE' => 'LIST',
			'VALUES' => array('NULL' => GetMessage('RZ_FIELD_AS_NAME_EMPTY')),
			'REFRESH' => 'Y'
		),
		'ALLOW_AUTO_REGISTER' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('RZ_ALLOW_AUTO_REGISTER'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
		),
		'USE_CAPTCHA' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('RZ_USE_CAPTCHA'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
		),
		'MESSAGE_OK' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('RZ_MESSAGE_OK'),
			'TYPE' => 'STRING',
			'DEFAULT' => GetMessage('RZ_MESSAGE_OK_DEFAULT'),
		),
		'COMMENTS' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('RZ_COMMENT_TO_ORDER'),
			'TYPE' => 'STRING',
			'DEFAULT' => GetMessage('RZ_COMMENT_TO_ORDER_DEF')
		),
		'PAY_SYSTEM_ID' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('RZ_FIELD_PAY_SYSTEM_ID'),
			'TYPE' => 'LIST',
			'VALUES' => array('NULL' => GetMessage('RZ_FIELD_PAY_SYSTEM_ID_EMPTY')),
			'DEFAULT' => 0,
		),
		'DELIVERY_ID' => array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('RZ_FIELD_DELIVERY_ID'),
			'TYPE' => 'LIST',
			'VALUES' => $arDelivery,
			'DEFAULT' => $defDelivery,
		),
		'FIELD_CLASS' => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('RZ_FIELD_CLASS'),
			'TYPE' => 'STRING',
			'DEFAULT' => 'form-control'
		),
		'FIELD_PLACEHOLDER' => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('RZ_FIELD_PLACEHOLDER'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y'
		),
		'FIELD_QUANTITY' => array(
			'PARENT' => 'VISUAL',
			'NAME' => GetMessage('RZ_FIELD_QUANTITY'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y'
		),

	),
);
$arSaleParams = array();
if (intval($arCurrentValues['PERSON_TYPE_ID']) > 0) {
	/** @noinspection PhpDynamicAsStaticMethodCallInspection */
	$rsSaleParams = CSaleOrderProps::GetList(array(), array('PERSON_TYPE_ID' => $arCurrentValues['PERSON_TYPE_ID']));
	while ($ar = $rsSaleParams->Fetch()) {
		if ('Y' === $ar['REQUIED']) {
			$ar['NAME'] .= ' *';
		}
        $ar['CODE'] = $ar['CODE'] ? : $ar['ID'];
		$arSaleParams[$ar['CODE']] = $ar['NAME'];
	}
	$arComponentParameters['PARAMETERS']['SHOW_FIELDS']['VALUES'] = $arSaleParams;
	$arComponentParameters['PARAMETERS']['SHOW_FIELDS']['SIZE'] = (count($arSaleParams) > MAX_LINES_IN_LIST) ? MAX_LINES_IN_LIST : count($arSaleParams);

	$arPaySystem = array();
	$rsPaySystem = CSalePaySystem::GetList(array(), array('PERSON_TYPE_ID' => $arCurrentValues['PERSON_TYPE_ID'], 'ACTIVE' => 'Y'));
	while ($ar = $rsPaySystem->Fetch()) {
		$arPaySystem[$ar['ID']] = strtok($arPersonTypes[$arCurrentValues['PERSON_TYPE_ID']], ' ') . ' ' . $ar['NAME'];
		if (strpos(strtoupper($ar['NAME']), strtoupper(GetMessage('RZ_PAY_SYSTEM_DEFAULT'))) !== false) {
			$arComponentParameters['PARAMETERS']['PAY_SYSTEM_ID']['DEFAULT'] = $ar['ID'];
		}
	}
	if (empty($arPaySystem)) {
		$arPaySystem = array(0 => GetMessage('RZ_FIELD_PAY_SYSTEM_ID_NOT_SET'));
	}
	$arComponentParameters['PARAMETERS']['PAY_SYSTEM_ID']['VALUES'] = $arPaySystem;
}
if (is_array($arCurrentValues['SHOW_FIELDS'])) {
	foreach ($arCurrentValues['SHOW_FIELDS'] as $key => $val) {
		if (strlen($val) == 0) {
			unset($arCurrentValues['SHOW_FIELDS'][$key]);
		}
	}
}
if (count($arCurrentValues['SHOW_FIELDS']) > 0) {
	$hasVals = true;
	if (strpos($arCurrentValues['SHOW_FIELDS'][0], 'NULL') === 0) {
		$hasVals = false;
	}
	if ($hasVals) {
		global $arValues;
		if (!is_array($arValues['REQ_FIELDS'])) $arValues['REQ_FIELDS'] = array();
		$arSaleParamsREQ = array();

		foreach ($arCurrentValues['SHOW_FIELDS'] as $code) {
			if (substr($arSaleParams[$code], -1, 1) == "*") {
				if (array_search($code, $arValues['REQ_FIELDS']) === false) {
					$arValues['REQ_FIELDS'][] = $code;
				}
			}
			$arSaleParamsREQ[$code] = $arSaleParams[$code];
		}
		$arComponentParameters['PARAMETERS']['REQ_FIELDS']['VALUES'] = $arSaleParamsREQ;
		$arComponentParameters['PARAMETERS']['REQ_FIELDS']['SIZE'] = (count($arSaleParamsREQ) > MAX_LINES_IN_LIST) ? MAX_LINES_IN_LIST : count($arSaleParamsREQ);
		$arComponentParameters['PARAMETERS']['AS_EMAIL']['VALUES'] = array_merge(array( 0 => GetMessage('RZ_FIELD_AS_EMAIL_NOT_USE')), $arSaleParamsREQ);
		$arComponentParameters['PARAMETERS']['AS_NAME']['VALUES'] = array_merge(array( 0 => GetMessage('RZ_FIELD_AS_NAME_NOT_USE')), $arSaleParamsREQ);
	}
}
if (!empty($arCurrentValues['AS_EMAIL']) && $arCurrentValues['AS_EMAIL'] !== 0) {
	$arComponentParameters['PARAMETERS']['SEND_REGISTER_EMAIL'] = array(
		'PARENT' => 'USER',
		'NAME' => GetMessage('RZ_FIELD_SEND_USER_REGISTER_EMAIL'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	);
}
if (!empty($arCurrentValues['SEND_REGISTER_EMAIL']) && $arCurrentValues['SEND_REGISTER_EMAIL'] == 'Y') {
	$arComponentParameters['PARAMETERS']['USER_REGISTER_EVENT_NAME'] = array(
		'PARENT' => 'USER',
		'NAME' => GetMessage('RZ_FIELD_USER_REGISTER_EVENT_NAME'),
		'TYPE' => 'STRING',
		'DEFAULT' => 'USER_INFO',
	);
}
if (COption::GetOptionString('main', 'captcha_registration', 'N') === 'Y') {
	$arComponentParameters['PARAMETERS']['USE_CAPTCHA']['HIDDEN'] = 'Y';
	$arCurrentValues['USE_CAPTCHA'] = 'Y';
}
$debug = 1;