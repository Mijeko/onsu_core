<?
include_once "include_stop_statistic.php";
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

/**
 * @var string $moduleId
 * @var string $moduleCode
 */
include_once "include_module.php";

//\Yenisite\Core\Tools::encodeAjaxRequest($_REQUEST);
//\Yenisite\Core\Tools::encodeAjaxRequest($_POST);

global $APPLICATION,
       $USER;

$arReturn = array('status' => 'success', 'message' => 'Подписка на товар удалена.');
$strError = '';

do {
	if (!$USER->IsAuthorized()) {
		$strError = 'Для удаления подписки необходимо авторизоваться на сайте.';
		break;
	}
	$feedbackId = (int)$_REQUEST['id'];
	if (1 > $feedbackId) {
		$strError = 'Не указан идентификатор подписки.';
		break;
	}
	if (!CModule::IncludeModule('iblock')) {
		$strError = 'Не установлен модуль "Информационные блоки".';
		break;
	}
	$arElement = CIBlockElement::GetList(array('sort' => 'asc'), array('ID' => $feedbackId, ''))->Fetch();

	if (!is_array($arElement) || $arElement['ID'] != $feedbackId) {
		$strError = 'Этой заявки не существует. Пожалуйста, обновите страницу.';
		break;
	}
	if ($arElement['IBLOCK_TYPE_ID'] != strtolower($moduleCode) . '_feedback') {
		$strError = 'Попытка удаления элемента инфоблока, не являющегося подпиской на товар.';
		break;
	}
	if ($arElement['CREATED_BY'] != $USER->GetId()) {
		$strError = 'Можно удалять только собственные подписки.';
		break;
	}

	$result = CIBlockElement::Delete($feedbackId);
	if (!$result) {
		$strError = 'Не удалось удалить подписку.';
	}

} while (0);

if (!empty($strError)) {
	$arReturn['status'] = 'fail';
	$arReturn['message'] = $strError;
}

if (($enc = Yenisite\Core\Tools::getLogicalEncoding()) !== 'utf-8') {
	$arReturn['message'] = $APPLICATION->ConvertCharset($arReturn['message'], $enc, 'utf-8');
}

echo json_encode($arReturn);
