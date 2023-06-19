<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use \Yenisite\Core\IBlock;

global $rz_b2_options;
$bShowFirstSectionTree = $rz_b2_options['use_lvl_first'] == 'Y';
$bGetParentServices = $arParams['GET_PARENT_SECTION_SERVICES'] == 'Y';

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
}elseif (strlen($arResult['VARIABLES']['ELEMENT_CODE']) > 0) {
    $rs = CIBlockElement::GetList(array(),array_merge($arFilter,array('CODE' => $arResult['VARIABLES']['ELEMENT_CODE'])), false,false, array('ID','IBLOCK_ID','IBLOCK_SECTION_ID'));
    $ar = $rs->Fetch();
    if ($ar) {
        $arFilter["ID"] = $ar['IBLOCK_SECTION_ID'];
    } else {
        $arCurSection = array(0);
    }
} else {
    $arCurSection = array(0);
}


if (empty($arCurSection)) {
    $obCache = new CPHPCache();
    if ($obCache->InitCache(36000, serialize($arFilter).$bShowFirstSectionTree.$bCanonical.$bGetParentServices, "/iblock/catalog")) {
        $arCurSection = $obCache->GetVars();
    } elseif ($obCache->StartDataCache()) {
        $arCurSection = array();
        if (\Bitrix\Main\Loader::includeModule("iblock")) {
            $dbRes = CIBlockSection::GetList(array(), $arFilter, false, array("ID",'DEPTH_LEVEL','LEFT_MARGIN','RIGHT_MARGIN','IBLOCK_ID','SECTION_PAGE_URL','UF_SERVICE'));
            $dbRes->SetUrlTemplates($arResult['URL_TEMPLATES']['element'],$arResult['URL_TEMPLATES']['section'],$arResult['URL_TEMPLATES']['sections']);

            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache("/iblock/catalog");

                if ($arCurSection = $dbRes->GetNext()) {
                    if ($bShowFirstSectionTree){
                        $arSubSections = IBlock::getSubSections($arCurSection);
                        $arCurSection['HAS_SUB_SECTIONS'] = !empty($arSubSections);
                        unset($arSubSections);
                    }
                    if ($bGetParentServices){
                        $arParentServices = CRZBitronic2CatalogUtils::getParnetsSectionsServices($arParams['IBLOCK_ID'],$arCurSection['ID']);
                        if (!empty($arParentServices)){
                            $arCurSection['UF_SERVICE'] = array_merge($arCurSection['UF_SERVICE'],$arParentServices);
                            $arCurSection['UF_SERVICE'] = array_unique($arCurSection['UF_SERVICE']);
                        }
                    }
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