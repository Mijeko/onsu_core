<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule("iblock"))
	return;

$wizard =& $this->GetWizard();
$UPDATE = $wizard->GetVar("install_type") == 'update';

// if(!WIZARD_INSTALL_DEMO_DATA)
	// return;

include WIZARD_ABSOLUTE_PATH.'/include/moduleCode.php'; //@var $moduleCode

$arTypes = Array(
	Array(
		"ID" => "news",
		"SECTIONS" => "N",
		"IN_RSS" => "Y",
		"SORT" => 300,
		"LANG" => Array(),
	),
	Array(
		"ID" => "services",
		"SECTIONS" => "N",
		"IN_RSS" => "N",
		"SORT" => 400,
		"LANG" => Array(),
	),
    Array(
        "ID" => strtolower($moduleCode)."_actions",
        "SECTIONS" => "N",
        "IN_RSS" => "N",
        "SORT" => 700,
        "LANG" => Array(),
    ),
	Array(
		"ID" => "references",
		"SECTIONS" => "N",
		"IN_RSS" => "N",
		"SORT" => 600,
		"LANG" => Array(),
	),
	Array(
		"ID" => "catalog",
		"SECTIONS" => "Y",
		"IN_RSS" => "N",
		"SORT" => 100,
		"LANG" => Array(),
	),
	Array(
		"ID" => "offers",
		"SECTIONS" => "Y",
		"IN_RSS" => "N",
		"SORT" => 200,
		"LANG" => Array(),
	),
	array(
		"ID" => "content",
		"SECTIONS" => "Y",
		"IN_RSS" => "N",
		"SORT" => 500,
		"LANG" => Array(),
	),
	
	Array(
		"ID" => strtolower($moduleCode)."_feedback",
		"SECTIONS" => "Y",
		"IN_RSS" => "N",
		"SORT" => 500,
		"LANG" => Array(),
	),
);

$arLanguages = Array();
$rsLanguage = CLanguage::GetList($by, $order, array());
while($arLanguage = $rsLanguage->Fetch())
	$arLanguages[] = $arLanguage["LID"];

$iblockType = new CIBlockType;
foreach($arTypes as $arType)
{
	$dbType = CIBlockType::GetList(Array(),Array("=ID" => $arType["ID"]));
	if($dbType->Fetch())
		continue;

	foreach($arLanguages as $languageID)
	{
		WizardServices::IncludeServiceLang("type.php", $languageID);

		$code = strtoupper( str_replace( strtolower($moduleCode).'_', '', $arType['ID'] ) ) . '_';
		$arType["LANG"][$languageID]["NAME"] = GetMessage($code. "TYPE_NAME");
		$arType["LANG"][$languageID]["ELEMENT_NAME"] = GetMessage($code. "ELEMENT_NAME");

		if ($arType["SECTIONS"] == "Y")
			$arType["LANG"][$languageID]["SECTION_NAME"] = GetMessage($code. "SECTION_NAME");
	}

	$res = $iblockType->Add($arType);
	// if(!$res)
	// {
		// echo 'Error: '.$iblockType->LAST_ERROR.'<br>';
	// }
	
	}
if(!$UPDATE)
	COption::SetOptionString('iblock','combined_list_mode','Y');
?>