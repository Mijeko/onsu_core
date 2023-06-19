<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arFilter = array(
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"ACTIVE" => "Y",
	"GLOBAL_ACTIVE" => "Y",
);
if (0 < intval($arResult["VARIABLES"]["SECTION_ID"])) {
	$arFilter["ID"] = $arResult["VARIABLES"]["SECTION_ID"];
} elseif ('' != $arResult["VARIABLES"]["SECTION_CODE"]) {
	$arFilter["=CODE"] = $arResult["VARIABLES"]["SECTION_CODE"];
} elseif ((int)$arResult['ELEMENT_ID'] > 0) {
	$rs = CIBlockElement::GetElementGroups($arResult['ELEMENT_ID'], true, array('ID'));
	$ar = $rs->Fetch();
	if ($ar) {
		$arFilter["ID"] = $ar['ID'];
	} else {
		$arCurSection = array(0);
	}
} else {
	$arCurSection = array(0);
}


if (empty($arCurSection)) {
	$obCache = new CPHPCache();
	if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/catalog")) {
		$arCurSection = $obCache->GetVars();
	} elseif ($obCache->StartDataCache()) {
		$arCurSection = array();
		if (\Bitrix\Main\Loader::includeModule("iblock")) {
			$dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID"));

			if (defined("BX_COMP_MANAGED_CACHE")) {
				global $CACHE_MANAGER;
				$CACHE_MANAGER->StartTagCache("/iblock/catalog");

				if ($arCurSection = $dbRes->Fetch()) {
					$CACHE_MANAGER->RegisterTag("iblock_id_" . $arParams["IBLOCK_ID"]);
				}
				$CACHE_MANAGER->EndTagCache();
			} else {
				if (!$arCurSection = $dbRes->Fetch())
					$arCurSection = array();
			}
		}
		$obCache->EndDataCache($arCurSection);
	}
}
if (!isset($arCurSection)) {
	$arCurSection = array(0);
}