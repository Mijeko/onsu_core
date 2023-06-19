<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule("iblock"))
	return;

if(!WIZARD_INSTALL_DEMO_DATA)
	return;

//catalog iblock import
$shopLocalization = $wizard->GetVar("shopLocalization");

include WIZARD_ABSOLUTE_PATH.'/include/moduleCode.php'; //@var $moduleCode

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/{$moduleCode}_catalog.xml";
if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $iblockXMLFile) && file_exists($_SERVER['DOCUMENT_ROOT'] . WIZARD_SERVICE_RELATIVE_PATH . "/xml/" . LANGUAGE_ID . "/catalog.xml")) {
	$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH . "/xml/" . LANGUAGE_ID . "/catalog.xml";
}
// $iblockXMLFilePrices = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/{$moduleCode}_catalog.xml";


$iblockCode = "{$moduleCode}_catalog";
$iblockType = "catalog";

$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockCode, "TYPE" => $iblockType));
$IBLOCK_CATALOG_ID = false;
if ($arIBlock = $rsIBlock->Fetch())
{
	$IBLOCK_CATALOG_ID = $arIBlock["ID"];
}

// DELETE OLD IBLOCKS
if (WIZARD_INSTALL_DEMO_DATA && $IBLOCK_CATALOG_ID)
{
	$boolFlag = true;
	if (CModule::IncludeModule("catalog")) {
		$arSKU = CCatalogSKU::GetInfoByProductIBlock($IBLOCK_CATALOG_ID);
		if (!empty($arSKU))
		{
			$boolFlag = CCatalog::UnLinkSKUIBlock($IBLOCK_CATALOG_ID);
			if (!$boolFlag)
			{
				$strError = "";
				if ($ex = $APPLICATION->GetException())
				{
					$strError = $ex->GetString();
				}
				else
				{
					$strError = "Couldn't unlink iblocks";
				}
				//die($strError);
			}
			$boolFlag = CIBlock::Delete($arSKU['IBLOCK_ID']);
			if (!$boolFlag)
			{
				$strError = "";
				if ($ex = $APPLICATION->GetException())
				{
					$strError = $ex->GetString();
				}
				else
				{
					$strError = "Couldn't delete offers iblock";
				}
				//die($strError);
			}
		}
	}
	if ($boolFlag)
	{
		$boolFlag = CIBlock::Delete($IBLOCK_CATALOG_ID);
		if (!$boolFlag)
		{
			$strError = "";
			if ($ex = $APPLICATION->GetException())
			{
				$strError = $ex->GetString();
			}
			else
			{
				$strError = "Couldn't delete catalog iblock";
			}
			//die($strError);
		}
	}
	if ($boolFlag)
	{
		$IBLOCK_CATALOG_ID = false;
	}
}


/*//$dbResultList = CCatalogGroup::GetList(Array(), Array("CODE" => "BASE"));
$dbResultList = CCatalogGroup::GetList(Array(), Array("BASE" => "Y"));
if(!($dbResultList->Fetch()))
{
	$arFields = Array();
	$rsLanguage = CLanguage::GetList($by, $order, array());
	while($arLanguage = $rsLanguage->Fetch())
	{
		WizardServices::IncludeServiceLang("catalog.php", $arLanguage["ID"]);
		$arFields["USER_LANG"][$arLanguage["ID"]] = GetMessage("WIZ_PRICE_NAME");
	}
	$arFields["BASE"] = "Y";
	$arFields["SORT"] = 100;
	$arFields["NAME"] = "BASE";
	$arFields["USER_GROUP"] = Array(1, 2);
	$arFields["USER_GROUP_BUY"] = Array(1, 2);
	CCatalogGroup::Add($arFields);
}*/

// IMPORT NEW IBLOCK
if($IBLOCK_CATALOG_ID == false)
{
	$permissions = Array(
			"1" => "X",
			"2" => "R"
		);
	$dbGroup = CGroup::GetList($by = "", $order = "", Array("STRING_ID" => "sale_administrator"));
	if($arGroup = $dbGroup -> Fetch())
	{
		$permissions[$arGroup["ID"]] = 'W';
	}
	$dbGroup = CGroup::GetList($by = "", $order = "", Array("STRING_ID" => "content_editor"));
	if($arGroup = $dbGroup -> Fetch())
	{
		$permissions[$arGroup["ID"]] = 'W';
	}
	$IBLOCK_CATALOG_ID = WizardServices::ImportIBlockFromXML(
		$iblockXMLFile,
		"{$moduleCode}_catalog",
		$iblockType,
		WIZARD_SITE_ID,
		$permissions
	);
	// $IBLOCK_CATALOG_ID1 = WizardServices::ImportIBlockFromXML(
		// $iblockXMLFilePrices,
		// "{$moduleCode}_catalog",
		// $iblockType."_prices",
		// WIZARD_SITE_ID,
		// $permissions
	// );

	if ($IBLOCK_CATALOG_ID < 1)
		return;

	$_SESSION["WIZARD_CATALOG_IBLOCK_ID"] = $IBLOCK_CATALOG_ID;
}
else // SETTING CURRENT IBLOCK
{
	$arSites = array();
	$db_res = CIBlock::GetSite($IBLOCK_CATALOG_ID);
	while ($res = $db_res->Fetch())
		$arSites[] = $res["LID"];
	if (!in_array(WIZARD_SITE_ID, $arSites))
	{
		$arSites[] = WIZARD_SITE_ID;
		$iblock = new CIBlock;
		$iblock->Update($IBLOCK_CATALOG_ID, array("LID" => $arSites));
	}
}
?>