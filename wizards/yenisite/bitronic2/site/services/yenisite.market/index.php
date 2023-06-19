<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (CModule::IncludeModule('yenisite.market') && CModule::IncludeModule('iblock')) {
	$obIBlock = CIBlock::GetList(array(), array("CODE" => "YENISITE_MARKET_ORDER"));
	if ($arIBlock = $obIBlock->Fetch()) {
		$arSites = array();
		$db_res = CIBlock::GetSite($arIBlock['ID']);
		while ($res = $db_res->Fetch())
			$arSites[] = $res["LID"];
		if (!in_array(WIZARD_SITE_ID, $arSites))
		{
			$arSites[] = WIZARD_SITE_ID;
			$iblock = new CIBlock;
			$iblock->Update($arIBlock['ID'], array("LID" => $arSites));
		}
		
		CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."personal/orders/index.php", Array("MARKET_IBLOCK_ID" => $arIBlock['ID']));
	}

	foreach (array('SALE_ORDER', 'SALE_ORDER_ADMIN') as $typeId) {

		$rsMess = CEventMessage::GetList($by='', $order='', array('TYPE_ID' => $typeId));

		if ($arMessage = $rsMess->Fetch()) {
			$arSiteID = array();
			$rsSite = CEventMessage::GetSite($arMessage['ID']);
			while($arSite = $rsSite->Fetch()) {
				$arSiteID[] = $arSite['SITE_ID'];
			}
			if (!in_array(WIZARD_SITE_ID, $arSiteID)) {
				$arSiteID[] = WIZARD_SITE_ID;
				$em = new CEventMessage;
				$em->Update($arMessage['ID'], array('LID' => $arSiteID));
			}
		}
	}
}
?>