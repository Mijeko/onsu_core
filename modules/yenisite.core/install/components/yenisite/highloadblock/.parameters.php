<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

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

$bXmlId = isset($arFields['UF_XML_ID']);

unset($hlblock, $entity);


$arComponentParameters = array(
	"GROUPS" => array(
		"RESIZER" => array(
			"NAME" => GetMessage("RZ_HLB_GROUP_RESIZER"),
			"SORT" => "500",
		),
	),
	"PARAMETERS" => array(
		"VARIABLE_ALIASES" => array(
			"ID" => array(
				"NAME" => GetMessage('RZ_HLB_ALIAS_ID')
			)
		),
		"SEF_MODE" => array(
			"list" => array(
				"NAME" => GetMessage("RZ_HLB_LIST"),
				"DEFAULT" => "",
				"VARIABLES" => array()
			),
			"view" => array(
				"NAME" => GetMessage("RZ_HLB_VIEW"),
				"DEFAULT" => "#ID#/",
				"VARIABLES" => array("ID", "XML_ID")
			)
		),
		"BLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage('RZ_HLB_BLOCK_ID'),
			"TYPE" => "TEXT"
		),
		"NAV_TEMPLATE" => array(
			"NAME" => GetMessage("RZ_HLB_NAV_TEMPLATE"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
			"PARENT" => "ADDITIONAL_SETTINGS",
		),
		"SET_TITLE" => array(),
		"SET_BROWSER_TITLE" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("RZ_HLB_COMPONENT_SET_BROWSER_TITLE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
			"REFRESH" => "Y"
		),
		"BROWSER_TITLE" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("RZ_HLB_COMPONENT_BROWSER_TITLE"),
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
		"ADD_ELEMENT_CHAIN" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("RZ_HLB_COMPONENT_ADD_ELEMENT_CHAIN"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y"
		),
        "SET_404" => array(
            "PARENT" => "ADDITIONAL_SETTINGS",
            "NAME" => GetMessage("SET_404"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y"
        ),
	)
);

if ($arCurrentValues["SEF_MODE"] == "Y") {
	$arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"] = array(
		'ID' => array(
			'NAME' => GetMessage('RZ_HLB_ALIAS_ID'),
			'TEMPLATE' => '#ID#'
		)
	);
}

if ($bXmlId) {
	$arComponentParameters['PARAMETERS']['SEF_MODE']['view']['VARIABLES'][] = 'XML_ID';

	if ($arCurrentValues['SEF_MODE'] == 'Y') {
		$arComponentParameters["PARAMETERS"]["VARIABLE_ALIASES"]['XML_ID'] = array(
			'NAME' => GetMessage('RZ_HLB_ALIAS_XML_ID'),
			'TEMPLATE' => '#XML_ID#'
		);
	}
}
