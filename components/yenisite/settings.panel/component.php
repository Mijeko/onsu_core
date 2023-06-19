<?
use Bitrix\Main\Loader;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
global $USER;
$method = $USER->IsAuthorized() ? "options" : "cookies";


$arResult['SETTINGS'] = array();
$arParams['SET_MOBILE'] = ($arParams['SET_MOBILE'] == 'Y') ? 'Y' : 'N';
/*************************************************************************
 * Check of received parameters
 *************************************************************************/
if (!Loader::IncludeModule($arParams['SOLUTION'])) {
    ShowError(GetMessage("MODULE_NOT_INCLUDE"));
    return;
}

if (!class_exists($arParams['SETTINGS_CLASS'])) {
    ShowError(GetMessage("CLASS_NOT_EXISTS"));
    return;
}

if (!method_exists($arParams['SETTINGS_CLASS'], 'getModuleId') || $arParams['SETTINGS_CLASS']::getModuleId() != $arParams['SOLUTION']) {
    ShowError(GetMessage("CLASS_NOT_EXISTS"));
    return;
}

if (!method_exists($arParams['SETTINGS_CLASS'], 'getSettingsArray')) {
    ShowError(GetMessage("METHOD_NOT_EXISTS", array('METHOD_NAME' => 'getSettingsArray')));
    return;
} else {
    $arResult['SETTINGS'] = $arParams['SETTINGS_CLASS']::getSettingsArray();
}

if (method_exists($arParams['SETTINGS_CLASS'], 'getGroupsArray')) {
    $arResult['GROUPS'] = $arParams['SETTINGS_CLASS']::getGroupsArray();
}
if (method_exists($arParams['SETTINGS_CLASS'], 'getFieldsetArray')) {
    $arResult['FIELDSET'] = $arParams['SETTINGS_CLASS']::getFieldsetArray();
}
/*************************************************************************
 * Save settings
 *************************************************************************/
if ($_POST["settings_apply"] == "Y") {
    Loader::IncludeModule("iblock");
    if (is_callable($arParams['POST_BEFORE'])) {
        call_user_func($arParams['POST_BEFORE']);
    }
    $arSettings = array();
    $asDefault = ($_POST["SETTINGS"]["SET_DEFAULT"] == "Y") ? TRUE : FALSE;
    foreach ($_POST["SETTINGS"] as $key => $value) {
        if (empty($value)) continue;
        if (!array_key_exists($key, $arResult['SETTINGS'])) continue;
        $arSettings[$key] = $value;

        if (!isset($_POST["SETTINGS"][$key . '_MOBILE'])) continue;
        $arSettings[$key . '_MOBILE'] = $_POST["SETTINGS"][$key . '_MOBILE'];
    }
    \CYSSettingsPanel::setSettings($arSettings, $asDefault);

    if (!empty($_FILES)){
        \CYSSettingsPanel::saveFilesFromServer($_FILES);
    }
    if (is_callable($arParams['POST_AFTER'])) {
        call_user_func($arParams['POST_AFTER']);
    }
    if (isset($_POST["burl"]))
        LocalRedirect($_POST["burl"]);
    else
        LocalRedirect($APPLICATION->GetCurPage());
}
/*************************************************************************
 * Get current settings
 *************************************************************************/
$arResult['CURRENT_SETTINGS'] = \CYSSettingsPanel::getSettings($arResult['SETTINGS'], $arParams['SET_MOBILE'] == 'Y',$arParams['SITE_ID'] ? : SITE_ID);
if (isset($arResult['CURRENT_SETTINGS']['header-version'])) {
    // Dublicate header-version into header-mode for compatibility between Bitronic2.8.0+ class & Bitronic2.7.5- templates
    $arResult['CURRENT_SETTINGS']['header-mode'] = &$arResult['CURRENT_SETTINGS']['header-version'];
}
if (isset($arResult['CURRENT_SETTINGS']['catalog-placement'])) {
    // Dublicate catalog-placement into menu-catalog for compatibility between Bitronic2.8.5+ class & Bitronic2.8.3- templates
    $arResult['CURRENT_SETTINGS']['menu-catalog'] = &$arResult['CURRENT_SETTINGS']['catalog-placement'];
}
if (isset($arResult['CURRENT_SETTINGS']['container-width'])) {
    // Dublicate container-width into work_area for compatibility between Bitronic2.12.0+ class & Bitronic2.11.0- templates
    $arResult['CURRENT_SETTINGS']['work_area'] = &$arResult['CURRENT_SETTINGS']['container-width'];
}
global ${$arParams['GLOBAL_VAR']};
${$arParams['GLOBAL_VAR']} = $arResult['CURRENT_SETTINGS'];

if ($USER->IsAdmin()) {
    $arParams['EDIT_SETTINGS'] = array_keys($arResult['SETTINGS']);
}

$this->IncludeComponentTemplate();