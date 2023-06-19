<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
global $MOD_PREFIX, $MODULE_ID;
$MOD_PREFIX = strtoupper($MODULE_ID);
use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);

return array(
	$MOD_PREFIX . "_DENIED" => array(
		"title" => Loc::getMessage($MOD_PREFIX . '_TASK_NAME_DENIED'),
	),
	$MOD_PREFIX . "_EDIT" => array(
		"title" => Loc::getMessage($MOD_PREFIX . '_TASK_NAME_EDIT'),
	),
	$MOD_PREFIX . "_FULL_ACCESS" => array(
		"title" => Loc::getMessage($MOD_PREFIX . '_TASK_NAME_FULL_ACCESS'),
	),
);
