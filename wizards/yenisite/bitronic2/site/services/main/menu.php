<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

	CModule::IncludeModule('fileman');
	$arMenuTypes = GetMenuTypes(WIZARD_SITE_ID);
	
	if(!isset($arMenuTypes['catalog'])) {
		$arMenuTypes['catalog'] =  GetMessage("WIZ_MENU_CATALOG");
	}
	
	if(!isset($arMenuTypes['top_sub'])) {
		$arMenuTypes['top_sub'] =  GetMessage("WIZ_MENU_TOP_SUB");
	}
		
	SetMenuTypes($arMenuTypes, WIZARD_SITE_ID);
	COption::SetOptionInt("fileman", "num_menu_param", 2, false ,WIZARD_SITE_ID);

?>