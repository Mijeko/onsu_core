<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
foreach ($arResult['PROFILES'] as &$arProfile) {
	$arProfile['PERSON_TYPE']['ACRONYM'] = '';
	$token = strtok($arProfile['PERSON_TYPE']['NAME'], ' ');
	do {
		if (0 >= strlen($token)) continue;
		$arProfile['PERSON_TYPE']['ACRONYM'] .= ToUpper(substr($token, 0, 1));
	} while (($token = strtok(' ')) !== false);
}
if (isset($arProfile)) {
	unset($arProfile);
}