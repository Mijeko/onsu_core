<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

$arFields = array('-' => ' ');

do {
	if (!CModule::IncludeModule('highloadblock')) break;

	// hlblock info
	$hlblock_id = intval($arCurrentValues['BLOCK_ID']);

	if (1 > $hlblock_id) break;

	$hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();

	if (empty($hlblock)) break;

	$entity = HL\HighloadBlockTable::compileEntity($hlblock);

	foreach ($entity->getFields() as $fieldId => $arField) {
		$arFields[$fieldId] = $fieldId;
	}
} while (0);

unset($hlblock, $entity);


$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"BLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage('HLVIEW_COMPONENT_BLOCK_ID_PARAM'),
			"TYPE" => "TEXT",
			"DEFAULT" => '={$_REQUEST[\'BLOCK_ID\']}',
			"REFRESH" => 'Y'
		),
		"ROW_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage('HLVIEW_COMPONENT_ID_PARAM'),
			"TYPE" => "TEXT",
			"DEFAULT" => '={$_REQUEST[\'ID\']}'
		),
		"ROW_XML_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage('HLVIEW_COMPONENT_XML_ID_PARAM'),
			"TYPE" => "TEXT",
			"DEFAULT" => '={$_REQUEST[\'XML_ID\']}'
		),
		"LIST_URL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage('HLVIEW_COMPONENT_LIST_URL_PARAM'),
			"TYPE" => "TEXT"
		),
		"SET_TITLE" => array(),
		"SET_BROWSER_TITLE" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("HLVIEW_COMPONENT_SET_BROWSER_TITLE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y"
		),
		"BROWSER_TITLE" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("HLVIEW_COMPONENT_BROWSER_TITLE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"DEFAULT" => "-",
			"VALUES" => $arFields,
			"HIDDEN" => (isset($arCurrentValues['SET_BROWSER_TITLE']) && $arCurrentValues['SET_BROWSER_TITLE'] == 'N' ? 'Y' : 'N')
		),
        "STR_FOR_BROWSER" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => GetMessage("STR_FOR_BROWSER"),
            "TYPE" => "STRING",
            "DEFAULT" => '',
            "HIDDEN" => (isset($arCurrentValues['SET_BROWSER_TITLE']) && $arCurrentValues['SET_BROWSER_TITLE'] == 'N' ? 'Y' : 'N')
        ),
		"ADD_ELEMENT_CHAIN" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("HLVIEW_COMPONENT_ADD_ELEMENT_CHAIN"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N"
		),
        "SET_DESCRIPTION_PAGE" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => GetMessage("SET_DESCRIPTION_PAGE"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
            "REFRESH" => "Y"
        ),
        "STR_FOR_DESCRIPTION" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => GetMessage("STR_FOR_DESCRIPTION"),
            "TYPE" => "STRING",
            "DEFAULT" => GetMessage("DEF_STR_FOR_DESCRIPTION"),
            "HIDDEN" => (isset($arCurrentValues['SET_DESCRIPTION_PAGE']) && $arCurrentValues['SET_DESCRIPTION_PAGE'] == 'N' ? 'Y' : 'N')
        ),
        "SET_KEYWORDS_PAGE" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => GetMessage("SET_KEYWORDS_PAGE"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
            "REFRESH" => "Y"
        ),
        "STR_FOR_KEY_WORDS" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => GetMessage("STR_FOR_KEY_WORDS"),
            "TYPE" => "STRING",
            "DEFAULT" => GetMessage("DEF_STR_FOR_KEYWORDS"),
            "HIDDEN" => (isset($arCurrentValues['SET_KEYWORDS_PAGE']) && $arCurrentValues['SET_KEYWORDS_PAGE'] == 'N' ? 'Y' : 'N')
        ),
	)
);