<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule("iblock"))
	return;

//@var $moduleCode
//@var $moduleId
include WIZARD_ABSOLUTE_PATH.'/include/moduleCode.php';

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/bitronic2_reviews.xml"; 
$iblockCode = "{$moduleCode}_reviews";
$iblockType = "news"; 

$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockCode, "TYPE" => $iblockType));
$iblockID = false; 
if ($arIBlock = $rsIBlock->Fetch())
{
	$iblockID = $arIBlock["ID"]; 
	if (WIZARD_INSTALL_DEMO_DATA)
	{
		CIBlock::Delete($arIBlock["ID"]); 
		$iblockID = false; 
	}
}

if($iblockID == false)
{
	$permissions = Array(
			"1" => "X",
			"2" => "R"
		);
	$dbGroup = CGroup::GetList($by = "", $order = "", Array("STRING_ID" => "content_editor"));
	if($arGroup = $dbGroup -> Fetch())
	{
		$permissions[$arGroup["ID"]] = 'W';
	};
	$iblockID = WizardServices::ImportIBlockFromXML(
		$iblockXMLFile,
		$iblockCode,
		$iblockType,
		WIZARD_SITE_ID,
		$permissions
	);

	if ($iblockID < 1)
		return;
	
	//IBlock fields
	$iblock = new CIBlock;
	$arFields = Array(
		"ACTIVE" => "Y",
		"FIELDS" => array ( 'IBLOCK_SECTION' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'ACTIVE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'Y', ), 'ACTIVE_FROM' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '=today', ), 'ACTIVE_TO' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'SORT' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'NAME' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => '', ), 'PREVIEW_PICTURE' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => array ( 'FROM_DETAIL' => 'N', 'SCALE' => 'N', 'WIDTH' => '', 'HEIGHT' => '', 'IGNORE_ERRORS' => 'N', 'METHOD' => 'resample', 'COMPRESSION' => 95, 'DELETE_WITH_DETAIL' => 'N', 'UPDATE_WITH_DETAIL' => 'N', ), ), 'PREVIEW_TEXT_TYPE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'text', ), 'PREVIEW_TEXT' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'DETAIL_PICTURE' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => array ( 'SCALE' => 'N', 'WIDTH' => '', 'HEIGHT' => '', 'IGNORE_ERRORS' => 'N', 'METHOD' => 'resample', 'COMPRESSION' => 95, ), ), 'DETAIL_TEXT_TYPE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'text', ), 'DETAIL_TEXT' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'XML_ID' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'CODE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => array ( 'UNIQUE' => 'Y', 'TRANSLITERATION' => 'Y', 'TRANS_LEN' => 100, 'TRANS_CASE' => 'L', 'TRANS_SPACE' => '_', 'TRANS_OTHER' => '_', 'TRANS_EAT' => 'Y', 'USE_GOOGLE' => 'Y', ), ), 'TAGS' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'SECTION_NAME' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => '', ), 'SECTION_PICTURE' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => array ( 'FROM_DETAIL' => 'N', 'SCALE' => 'N', 'WIDTH' => '', 'HEIGHT' => '', 'IGNORE_ERRORS' => 'N', 'METHOD' => 'resample', 'COMPRESSION' => 95, 'DELETE_WITH_DETAIL' => 'N', 'UPDATE_WITH_DETAIL' => 'N', ), ), 'SECTION_DESCRIPTION_TYPE' => array ( 'IS_REQUIRED' => 'Y', 'DEFAULT_VALUE' => 'text', ), 'SECTION_DESCRIPTION' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'SECTION_DETAIL_PICTURE' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => array ( 'SCALE' => 'N', 'WIDTH' => '', 'HEIGHT' => '', 'IGNORE_ERRORS' => 'N', 'METHOD' => 'resample', 'COMPRESSION' => 95, ), ), 'SECTION_XML_ID' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '', ), 'SECTION_CODE' => array ( 'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => array ( 'UNIQUE' => 'N', 'TRANSLITERATION' => 'N', 'TRANS_LEN' => 100, 'TRANS_CASE' => 'L', 'TRANS_SPACE' => '_', 'TRANS_OTHER' => '_', 'TRANS_EAT' => 'Y', 'USE_GOOGLE' => 'N', ), ), ),
		"CODE" => $iblockCode,
		"XML_ID" => $iblockCode,
		//"NAME" => "[".WIZARD_SITE_ID."] ".$iblock->GetArrayByID($iblockID, "NAME")
	);
	
	$iblock->Update($iblockID, $arFields);
}
else
{
	$arSites = array(); 
	$db_res = CIBlock::GetSite($iblockID);
	while ($res = $db_res->Fetch())
		$arSites[] = $res["LID"]; 
	if (!in_array(WIZARD_SITE_ID, $arSites))
	{
		$arSites[] = WIZARD_SITE_ID;
		$iblock = new CIBlock;
		$iblock->Update($iblockID, array("LID" => $arSites));
	}
}

$wizard = &$this->GetWizard();
$install_type = $wizard->GetVar("install_type");

if (COption::GetOptionString($moduleId, 'update_2.15.0', 'N', WIZARD_SITE_ID) === 'Y') {
	COption::RemoveOption($moduleId, 'update_2.15.0', WIZARD_SITE_ID);
	$install_type = 'install';
}

if ($install_type !== 'update') {
	$dbSite = CSite::GetByID(WIZARD_SITE_ID);
	if($arSite = $dbSite -> Fetch())
		$lang = $arSite["LANGUAGE_ID"];
	if(strlen($lang) <= 0)
		$lang = "ru";

	//SET MESSAGES
	$arMessages = array(
		'ELEMENT_NAME' 		=> GetMessage('REVIEWS-ELEMENT_NAME'),
		'ELEMENTS_NAME' 	=> GetMessage('REVIEWS-ELEMENTS_NAME'),
		'ELEMENT_ADD' 		=> GetMessage('REVIEWS-ELEMENT_ADD'),
		'ELEMENT_EDIT' 		=> GetMessage('REVIEWS-ELEMENT_EDIT'),
		'ELEMENT_DELETE' 	=> GetMessage('REVIEWS-ELEMENT_DELETE'),
		'SECTION_NAME' 		=> GetMessage('REVIEWS-SECTION_NAME'),
		'SECTIONS_NAME' 	=> GetMessage('REVIEWS-SECTIONS_NAME'),
		'SECTION_ADD' 		=> GetMessage('REVIEWS-SECTION_ADD'),
		'SECTION_EDIT' 		=> GetMessage('REVIEWS-SECTION_EDIT'),
		'SECTION_DELETE' 	=> GetMessage('REVIEWS-SECTION_DELETE'),
	);
	CIBlock::SetMessages($iblockID, $arMessages);

	WizardServices::IncludeServiceLang("reviews.php", $lang);
	CUserOptions::SetOption("form", "form_element_".$iblockID, array ( 'tabs' => 'edit1--#--'.GetMessage("WZD_OPTION_NEWS_1").'--,--ACTIVE--#--'.GetMessage("WZD_OPTION_NEWS_2").'--,--ACTIVE_FROM--#--'.GetMessage("WZD_OPTION_NEWS_3").'--,--NAME--#--'.GetMessage("WZD_OPTION_NEWS_5").'--,--CODE--#--'.GetMessage("WZD_OPTION_NEWS_6").'--,--PREVIEW_PICTURE--#--'.GetMessage("WZD_OPTION_NEWS_11").'--,--DETAIL_PICTURE--#--'.GetMessage("WZD_OPTION_NEWS_12").'--,--PREVIEW_TEXT--#--'.GetMessage("WZD_OPTION_NEWS_8").'--,--DETAIL_TEXT--#--'.GetMessage("WZD_OPTION_NEWS_10").'--;--', ));

	CUserOptions::SetOption("list", "tbl_iblock_list_".md5($iblockType.".".$iblockID), array ( 'columns' => 'NAME,ACTIVE,DATE_ACTIVE_FROM', 'by' => 'timestamp_x', 'order' => 'desc', 'page_size' => '20', ));
}
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/reviews/index.php", array("REVIEWS_IBLOCK_ID" => $iblockID));
//CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include_areas/index/news.php", array("NEWS_IBLOCK_ID" => $iblockID));

?>