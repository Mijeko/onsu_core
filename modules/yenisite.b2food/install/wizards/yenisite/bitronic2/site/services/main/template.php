<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

//echo "WIZARD_SITE_ID=".WIZARD_SITE_ID." | ";
//echo "WIZARD_SITE_PATH=".WIZARD_SITE_PATH." | ";
//echo "WIZARD_RELATIVE_PATH=".WIZARD_RELATIVE_PATH." | ";
//echo "WIZARD_ABSOLUTE_PATH=".WIZARD_ABSOLUTE_PATH." | ";
//echo "WIZARD_TEMPLATE_ID=".WIZARD_TEMPLATE_ID." | ";
//echo "WIZARD_TEMPLATE_RELATIVE_PATH=".WIZARD_TEMPLATE_RELATIVE_PATH." | ";
//echo "WIZARD_TEMPLATE_ABSOLUTE_PATH=".WIZARD_TEMPLATE_ABSOLUTE_PATH." | ";
//echo "WIZARD_THEME_ID=".WIZARD_THEME_ID." | ";
//echo "WIZARD_THEME_RELATIVE_PATH=".WIZARD_THEME_RELATIVE_PATH." | ";
//echo "WIZARD_THEME_ABSOLUTE_PATH=".WIZARD_THEME_ABSOLUTE_PATH." | ";
//echo "WIZARD_SERVICE_RELATIVE_PATH=".WIZARD_SERVICE_RELATIVE_PATH." | ";
//echo "WIZARD_SERVICE_ABSOLUTE_PATH=".WIZARD_SERVICE_ABSOLUTE_PATH." | ";
//echo "WIZARD_IS_RERUN=".WIZARD_IS_RERUN." | ";
//die();

if (!defined("WIZARD_TEMPLATE_ID"))
	return;

$defWizTemplId = 'romza_bitronic2';	// CHANGE

include WIZARD_ABSOLUTE_PATH.'/include/moduleInclude.php';

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".CRZBitronic2Settings::getModuleId()."/install/version.php");
$ver = $arModuleVersion["VERSION"];

$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID.'_'.$ver;

CopyDirFiles(
	$_SERVER["DOCUMENT_ROOT"].WizardServices::GetTemplatesPath(WIZARD_RELATIVE_PATH."/site")."/".WIZARD_TEMPLATE_ID,
	$bitrixTemplateDir,
	$rewrite = true,
	$recursive = true, 
	$delete_after_copy = false
	// $exclude = "themes"
);

// DELETE OLD FILES
DeleteDirFilesEx(BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".$ver."/components/bitrix/catalog.store.amount/store/script.js");
DeleteDirFilesEx(BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".$ver."/css/themes/theme_red-skew.min.css");
DeleteDirFilesEx(BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".$ver."/css/themes/theme_violet-skew.min.css");
DeleteDirFilesEx(BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".$ver."/css/themes/theme_yellow-skew.min.css");
DeleteDirFilesEx(BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".$ver."/components/yenisite/yandex.market_reviews_store/.default");

CWizardUtil::ReplaceMacros($bitrixTemplateDir."/header.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros($bitrixTemplateDir."/footer.php", Array("SITE_DIR" => WIZARD_SITE_DIR));

//Attach template to default site
$obSite = CSite::GetList($by = "def", $order = "desc", Array("LID" => WIZARD_SITE_ID));
if ($arSite = $obSite->Fetch())
{
	$arTemplates = Array();
	$found = false;
	$foundEmpty = false;
	$obTemplate = CSite::GetTemplateList($arSite["LID"]);
	while($arTemplate = $obTemplate->Fetch())
	{
		// copy template_styles.css from previous template
		if(strpos($arTemplate["TEMPLATE"], WIZARD_TEMPLATE_ID."_") !== false)
		{
			CopyDirFiles(
				$_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".$arTemplate["TEMPLATE"]."/template_styles.css",
				$_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."_".$ver."/template_styles.css",
				$rewrite = true,
				$recursive = true, 
				$delete_after_copy = false
			);
		}
		if(!$found && strlen(trim($arTemplate["CONDITION"]))<=0)
		{
			$arTemplate["TEMPLATE"] = WIZARD_TEMPLATE_ID."_".$ver;
			$found = true;
		}
		if($arTemplate["TEMPLATE"] == "empty")
		{
			$foundEmpty = true;
			continue;
		}
		$arTemplates[]= $arTemplate;
	}

	if (!$found)
		$arTemplates[]= Array("CONDITION" => "", "SORT" => 150, "TEMPLATE" => WIZARD_TEMPLATE_ID."_".$ver);

	$arFields = Array(
		"TEMPLATE" => $arTemplates,
		"NAME" => $arSite["NAME"],
	);

	$obSite = new CSite();
	$obSite->Update($arSite["LID"], $arFields);
}

// ##### Set LOGO ##### //
$siteLogo = $wizard->GetVar("siteLogo");

if(strlen($siteLogo)>0)
{
	if(IntVal($siteLogo) > 0)
	{
		$ff = CFile::GetPath($siteLogo);
		$strOldFile = str_replace("//", "/", $ff);
		@copy($_SERVER["DOCUMENT_ROOT"].$strOldFile, $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/images/".CRZBitronic2Settings::getModuleId()."/logo.png");
		CFile::Delete($siteLogo);
		$areaStr = '<img src="' . BX_PERSONAL_ROOT . '/images/' . CRZBitronic2Settings::getModuleId() . '/logo.png" alt="logo">';
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . WIZARD_SITE_DIR . 'include_areas/header/logo_icon.php' , $areaStr);
	}
}

$wizrdTemplateId = $wizard->GetVar("wizTemplateID");
if ($wizrdTemplateId != $defWizTemplId) $wizrdTemplateId = $defWizTemplId;
COption::SetOptionString("main", "wizard_template_id", $wizrdTemplateId, false, WIZARD_SITE_ID."_".$ver);
?>
