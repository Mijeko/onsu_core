<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$arTemplateParameters = array(
	'BUTTON_TYPE' => array(
		'PARENT' => 'VISUAL',
		'NAME' => GetMessage("RZ_TIP_KNOPKI"),
		'TYPE' => 'LIST',
		'DEFAULT' => 'BUTTON',
		'VALUES' => array('BUTTON' => GetMessage("RZ_KNOPKA"), 'LINK' => GetMessage("RZ_SSILKA"), 'NULL' => GetMessage('RZ_NULL_CLASS')),
	),
);