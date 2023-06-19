<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arAuthServices = $arPost = array();
if (is_array($arParams["~AUTH_SERVICES"])) {
	$arAuthServices = $arParams["~AUTH_SERVICES"];
}
foreach ($arAuthServices as &$service) {
	$service['FORM_HTML'] = str_replace('name=""', 'name="submit"', $service['FORM_HTML']);
}
$arResult['SERVICES'] = $arAuthServices;