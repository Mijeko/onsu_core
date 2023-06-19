<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!IsModuleInstalled("highloadblock") && file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/highloadblock/"))
{
	$installFile = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/highloadblock/install/index.php";
	if (!file_exists($installFile))
		return false;

	include_once($installFile);

	$moduleIdTmp = str_replace(".", "_", "highloadblock");
	if (!class_exists($moduleIdTmp))
		return false;

	$module = new $moduleIdTmp;
	if (!$module->InstallDB())
		return false;
	$module->InstallEvents();
	if (!$module->InstallFiles())
		return false;
}

if (!CModule::IncludeModule("highloadblock"))
	return;

use Bitrix\Highloadblock as HL;

include WIZARD_ABSOLUTE_PATH.'/include/moduleCode.php'; //@var $moduleCode

$wizard = &$this->GetWizard();
$install_type = $wizard->GetVar("install_type");

$prefix = strtoupper($moduleCode).'_';
$HLprefix = ucfirst(strtolower($moduleCode));

if (WIZARD_INSTALL_DEMO_DATA):

$dbHblock = HL\HighloadBlockTable::getList(
	array(
		"filter" => array("NAME" => $HLprefix."ColorReference")
	)
);
if (!$dbHblock->Fetch())
{
	$data = array(
		'NAME' => $HLprefix.'ColorReference',
		'TABLE_NAME' => 'rz_'.strtolower($moduleCode).'_color_reference',
	);

	$result = HL\HighloadBlockTable::add($data);
	$ID = $result->getId();
	if(intval($ID) > 0)
	{
	$_SESSION[$prefix."HBLOCK_COLOR_ID"] = $ID;

	$hldata = HL\HighloadBlockTable::getById($ID)->fetch();
	$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

	//adding user fields
	$arUserFields = array (
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_NAME',
			'USER_TYPE_ID' => 'string',
			'XML_ID' => 'UF_COLOR_NAME',
			'SORT' => '100',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'Y',
		),
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_FILE',
			'USER_TYPE_ID' => 'file',
			'XML_ID' => 'UF_COLOR_FILE',
			'SORT' => '200',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'Y',
		),
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_LINK',
			'USER_TYPE_ID' => 'string',
			'XML_ID' => 'UF_COLOR_LINK',
			'SORT' => '300',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'Y',
		),
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_SORT',
			'USER_TYPE_ID' => 'double',
			'XML_ID' => 'UF_COLOR_SORT',
			'SORT' => '400',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'N',
		),
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_DEF',
			'USER_TYPE_ID' => 'boolean',
			'XML_ID' => 'UF_COLOR_DEF',
			'SORT' => '500',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'N',
		),
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_XML_ID',
			'USER_TYPE_ID' => 'string',
			'XML_ID' => 'UF_XML_ID',
			'SORT' => '600',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'Y',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'N',
		)
	);
	$arLanguages = Array();
	$rsLanguage = CLanguage::GetList($by, $order, array());
	while($arLanguage = $rsLanguage->Fetch())
		$arLanguages[] = $arLanguage["LID"];

	$obUserField  = new CUserTypeEntity;
	foreach ($arUserFields as $arFields)
	{
		$dbRes = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID" => $arFields["ENTITY_ID"], "FIELD_NAME" => $arFields["FIELD_NAME"]));
		if ($dbRes->Fetch())
			continue;

		$arLabelNames = Array();
		foreach($arLanguages as $languageID)
		{
			WizardServices::IncludeServiceLang("references.php", $languageID);
			$arLabelNames[$languageID] = GetMessage($arFields["FIELD_NAME"]);
		}

		$arFields["EDIT_FORM_LABEL"] = $arLabelNames;
		$arFields["LIST_COLUMN_LABEL"] = $arLabelNames;
		$arFields["LIST_FILTER_LABEL"] = $arLabelNames;

		$ID_USER_FIELD = $obUserField->Add($arFields);
	}
	}
}

endif; //if (WIZARD_INSTALL_DEMO_DATA)

/* ======= BRANDS ======= */

if (WIZARD_INSTALL_DEMO_DATA || $install_type == 'update'):

$dbHblock = HL\HighloadBlockTable::getList(
	array(
		"filter" => array("NAME" => $HLprefix."BrandReference")
	)
);

if (!$resHblock = $dbHblock->Fetch())
{
	$data = array(
		'NAME' => $HLprefix.'BrandReference',
		'TABLE_NAME' => 'rz_'.strtolower($moduleCode).'_brand_reference',
	);

	$result = HL\HighloadBlockTable::add($data);
	$ID = $result->getId();
	
	if(intval($ID) > 0)
	{

	$_SESSION[$prefix."HBLOCK_BRAND_ID"] = $ID;
	$HLBLOCK_BRAND_ID = $ID;
	
	$hldata = HL\HighloadBlockTable::getById($ID)->fetch();
	$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

	//adding user fields
	$arUserFields = array (
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_NAME',
			'USER_TYPE_ID' => 'string',
			'XML_ID' => 'UF_BRAND_NAME',
			'SORT' => '100',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'Y',
		),
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_FILE',
			'USER_TYPE_ID' => 'file',
			'XML_ID' => 'UF_BRAND_FILE',
			'SORT' => '200',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'Y',
		),
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_LINK',
			'USER_TYPE_ID' => 'string',
			'XML_ID' => 'UF_BRAND_LINK',
			'SORT' => '300',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'Y',
		),
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_DESCRIPTION',
			'USER_TYPE_ID' => 'string',
			'XML_ID' => 'UF_BRAND_DESCR',
			'SORT' => '400',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'Y',
		),
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_FULL_DESCRIPTION',
			'USER_TYPE_ID' => 'string',
			'XML_ID' => 'UF_BRAND_FULL_DESCR',
			'SORT' => '500',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'Y',
		),
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_SORT',
			'USER_TYPE_ID' => 'double',
			'XML_ID' => 'UF_BRAND_SORT',
			'SORT' => '600',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'N',
		),
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_EXTERNAL_CODE',
			'USER_TYPE_ID' => 'string',
			'XML_ID' => 'UF_BRAND_EXTERNAL_CODE',
			'SORT' => '700',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'N',
		),
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_XML_ID',
			'USER_TYPE_ID' => 'string',
			'XML_ID' => 'UF_XML_ID',
			'SORT' => '800',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'Y',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'N',
		),
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_DATE_FOUNDATION',
			'USER_TYPE_ID' => 'string',
			'XML_ID' => 'UF_XML_ID',
            'SORT' => '900',
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'Y',
		),
		array (
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_COUNTRY',
			'USER_TYPE_ID' => 'string',
			'XML_ID' => 'UF_XML_ID',
            'SORT' => '590',
            'MULTIPLE' => 'N',
            'MANDATORY' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_IN_LIST' => 'Y',
            'EDIT_IN_LIST' => 'Y',
            'IS_SEARCHABLE' => 'Y',
		)
	);
	$arLanguages = Array();
	$rsLanguage = CLanguage::GetList($by, $order, array());
	while($arLanguage = $rsLanguage->Fetch())
		$arLanguages[] = $arLanguage["LID"];

	$obUserField  = new CUserTypeEntity;
	foreach ($arUserFields as $arFields)
	{
		$dbRes = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID" => $arFields["ENTITY_ID"], "FIELD_NAME" => $arFields["FIELD_NAME"]));
		if ($dbRes->Fetch())
			continue;

		$arLabelNames = Array();
		foreach($arLanguages as $languageID)
		{
			WizardServices::IncludeServiceLang("references.php", $languageID);
			$arLabelNames[$languageID] = GetMessage($arFields["FIELD_NAME"]);
		}

		$arFields["EDIT_FORM_LABEL"] = $arLabelNames;
		$arFields["LIST_COLUMN_LABEL"] = $arLabelNames;
		$arFields["LIST_FILTER_LABEL"] = $arLabelNames;

		$ID_USER_FIELD = $obUserField->Add($arFields);
	}
	}
}
else
{
	$HLBLOCK_BRAND_ID = $resHblock['ID'];
}

CWizardUtil::ReplaceMacrosRecursive(WIZARD_SITE_PATH."/brands/", array("HLBLOCK_BRAND_ID" => $HLBLOCK_BRAND_ID));

endif; //if (WIZARD_INSTALL_DEMO_DATA || $install_type == 'update')
?>