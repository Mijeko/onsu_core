<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

if (!Loader::includeModule('iblock'))
	return;
//$boolCatalog = Loader::includeModule('catalog');

// RESIZER
$arResizerSetList = array();
if (Loader::includeModule("yenisite.resizer2")) {
	$arSets = CResizer2Set::GetList();
	while ($ar = $arSets->Fetch()) {
		$arResizerSetList[$ar["id"]] = "[".$ar["id"]."] ".$ar["NAME"];
	}
}

$siteId = $_REQUEST['src_site'];
if (empty($siteId)) $siteId = $_REQUEST['site'];

$siteTemplate = $_REQUEST['siteTemplateId'];
if (empty($siteTemplate)) $siteTemplate = $_REQUEST['site_template'];
if (empty($siteTemplate)) $siteTemplate = $_REQUEST['template_id'];

// @var $moduleCode
// @var $moduleId
include $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/templates/' . $siteTemplate . '/include/module_code.php';

$arIBTypeList = CIBlockParameters::GetIBlockTypes(array("-"=>" "));
$arIBlockFeedbackList = array('-' => GetMessage('FEEDBACK_NO_IBLOCK'));

$arTemplateParameters = array(
	'ORDER_DETAIL_RESIZER_SET' => array(
		'DEFAULT' => 6,
		'NAME' => GetMessage('ORDER_DETAIL_RESIZER_SET'),
		'PARENT' => 'VISUAL',
		'TYPE' => 'LIST',
		'VALUES' => $arResizerSetList,
	),
	'FEEDBACK_RESIZER_SET' => array(
		'DEFAULT' => 5,
		'NAME' => GetMessage('FEEDBACK_RESIZER_SET'),
		'PARENT' => 'VISUAL',
		'TYPE' => 'LIST',
		'VALUES' => $arResizerSetList,
	),
    'PAYMENT_RESIZER_SET' => array(
		'DEFAULT' => 2,
		'NAME' => GetMessage('PAYMENT_RESIZER_SET'),
		'PARENT' => 'VISUAL',
		'TYPE' => 'LIST',
		'VALUES' => $arResizerSetList,
	),
	'FEEDBACK_IBLOCK_TYPE' => array(
		'DEFAULT' => $moduleCode . '_feedback',
		'NAME'    => GetMessage('FEEDBACK_IBLOCK_TYPE'),
		'PARENT'  => 'BASE',
		'REFRESH' => 'Y',
		'TYPE'    => 'LIST',
		'VALUES'  => $arIBTypeList
	)
);

if (!isset($arCurrentValues['FEEDBACK_IBLOCK_TYPE'])) {
	$arCurrentValues['FEEDBACK_IBLOCK_TYPE'] = $arTemplateParameters['FEEDBACK_IBLOCK_TYPE']['DEFAULT'];
}

$rsIBlock = CIBlock::GetList(array("SORT"=>"ASC"), array("SITE_ID"=>$siteId, "TYPE" => $arCurrentValues['FEEDBACK_IBLOCK_TYPE']));

while ($arIBlock = $rsIBlock->Fetch()) {
	$arIBlockFeedbackList[$arIBlock['ID']] = '[' . $arIBlock['ID'] . '] ' . $arIBlock['NAME'];
}

$arFeedbacks = array('ELEMENT_EXIST', 'ELEMENT_CONTACT', 'FOUND_CHEAP', 'PRICE_LOWER');

foreach ($arFeedbacks as $feedbackType) {
	$paramPrefix = 'FEEDBACK_' . $feedbackType;
	$paramIdName = $paramPrefix . '_IBLOCK_ID';

	$arTemplateParameters[$paramIdName] = array(
		'DEFAULT' => '-',
		'NAME'    => GetMessage($paramIdName),
		'PARENT'  => 'BASE',
		'REFRESH' => 'Y',
		'TYPE'    => 'LIST',
		'VALUES'  => $arIBlockFeedbackList
	);
	$iblockId = intval($arCurrentValues[$paramIdName]);
	if (1 > $iblockId) continue;

	$paramTitleName = $paramPrefix . '_TITLE';
	$arTemplateParameters[$paramTitleName] = array(
		'DEFAULT' => GetMessage($paramTitleName . '_DEFAULT'),
		'NAME'    => GetMessage($paramTitleName),
		'PARENT'  => 'BASE',
		'TYPE'    => 'TEXT'
	);

	$arProperty = array();
	$arProperty_LNS = array();
	$rsProp = CIBlockProperty::GetList(array("sort"=>"asc", "name"=>"asc"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>$iblockId));
	while ($arr = $rsProp->Fetch()) {
		$arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
		if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S"))) {
			$arProperty_LNS[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
		}
	}

	for ($i = 1; $i < 3; $i++) {
		$paramTitleName = $paramPrefix . '_PROP_' . $i . '_TITLE';
		$arTemplateParameters[$paramTitleName] = array(
			'DEFAULT' => GetMessage($paramTitleName . '_DEFAULT'),
			'NAME'    => GetMessage($paramTitleName),
			'PARENT'  => 'BASE',
			'TYPE'    => 'TEXT'
		);
		$paramPropName = $paramPrefix . '_PROP_' . $i;
		$arTemplateParameters[$paramPropName] = array(
			'MULTIPLE' => 'Y',
			'NAME'     => GetMessage($paramPropName),
			'PARENT'   => 'BASE',
			'TYPE'     => 'LIST',
			'VALUES'   => $arProperty_LNS
		);
	}
}
