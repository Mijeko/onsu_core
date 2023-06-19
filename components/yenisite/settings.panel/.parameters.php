<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arInstalledModules = array();

$rsInstalledModules = CModule::GetList();
while ($ar = $rsInstalledModules->Fetch())
{
    $arInstalledModules[$ar['ID']] = $ar['ID'];
}


$arComponentParameters = array(
    "PARAMETERS" => array(
        "SOLUTION" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("SOLUTION"),
            "TYPE" => "LIST",
            "MULTIPLE" => "N",
            "VALUES" => $arInstalledModules,
        ),
        "SETTINGS_CLASS" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("SETTINGS_CLASS"),
            "TYPE" => "STRING",
            "DEFAULT" => "CYSSettings",
        ),
        "GLOBAL_VAR" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("GLOBAL_VAR"),
            "TYPE" => "STRING",
            "DEFAULT" => "ys_options",
        ),


    ),
);

if( !empty($arCurrentValues["SOLUTION"]) &&
    CModule::IncludeModule($arCurrentValues["SOLUTION"] ) &&
    !empty($arCurrentValues["SETTINGS_CLASS"]) &&
    class_exists($arCurrentValues['SETTINGS_CLASS']) &&
    method_exists($arCurrentValues['SETTINGS_CLASS'],'getSettingsArray') &&
    is_array($arCurrentValues['SETTINGS_CLASS']::getSettingsArray())
)
{
    $arSettingsList = array();
    foreach($arCurrentValues['SETTINGS_CLASS']::getSettingsArray() as $key=>$arSetting) {
        if ($arSetting['admin'] === 'Y') continue;
        $arSettingsList[$key] = $arSetting['name'] ? : GetMessage('SERVICE_PARAM',array('#KEY#' => $key));
    }
    $arComponentParameters["PARAMETERS"]["EDIT_SETTINGS"] = array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("EDIT_SETTINGS"),
        "TYPE" => "LIST",
        "MULTIPLE" => "Y",
        "SIZE" => "8",
        "VALUES" => $arSettingsList,
    );
}
?>