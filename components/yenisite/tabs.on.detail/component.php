<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader;

if (!Loader::includeModule('iblock')) return;

if ($arParams['USE_PARAMS_OR_DATA'] == 'DATA') {
    if ($arParams['USE_SECTION_OR_ELEMENT'] == 'SECTION' && !empty($arParams['IBLOCK_ID']) && !empty($arParams['SECTION_ID'])) {
        if ($this->StartResultCache()) {
            if ($arParams['SECTION_ID'] != 'ALL') {
                $dbSection = CIBlockSection::GetByID($arParams['SECTION_ID']);
                if ($Section = $dbSection->GetNext())
                    $arSection = $Section;
                $arFilter = array('IBLOCK_ID' => $arSection['IBLOCK_ID'], 'SECTION_ID' => $arSection['ID'], 'ACTIVE' => 'Y', 'INCLUDE_SUBSECTIONS' => 'Y');
            } else{
                $arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'INCLUDE_SUBSECTIONS' => 'Y');
                $arSection = array('SOME_INFO');
            }

            $dbElements = CIBlockElement::getList(array(), $arFilter);
            $arElements = array();

            while ($arElement = $dbElements->Fetch()) {
                $buttons = CIBlock::GetPanelButtons(
                    $this->arParams['IBLOCK_ID'],
                    $arElement['ID'],
                    $arParams['SECTION_ID']
                );
                $arElement["EDIT_LINK"] = $buttons["edit"]["edit_element"]["ACTION_URL"];
                $arElement["DELETE_LINK"] = $buttons["edit"]["delete_element"]["ACTION_URL"];
                $arElements[] = $arElement;
            }
            $arResult = array(
                "SECTION" => $arSection,
                'ELEMENTS' => $arElements
            );


            if (empty($arResult['SECTION']) || empty($arResult['ELEMENTS']))
                $this->AbortResultCache();
        }
    } elseif ($arParams['USE_SECTION_OR_ELEMENT'] == 'ELEMENT' && !empty($arParams['IBLOCK_ID'])){
        if ($this->StartResultCache()) {

            $arFilter = array("IBLOCK_ID" => $arParams['IBLOCK_ID'],'ACTIVE' => 'Y');
            if (!empty($arParams['ELEMENTS_ID'])){
                $arFilter['ID'] = array($arParams['ELEMENTS_ID']);
            }

            $dbElements = CIBlockElement::GetList(array(),$arFilter,false,array(),array());
            $arElements = array();

            while ($rsElement = $dbElements->GetNextElement()) {
                $arProps = $rsElement->GetProperties();
                $arElement = $rsElement->GetFields();

                $buttons = CIBlock::GetPanelButtons(
                    $this->arParams['IBLOCK_ID'],
                    $arElement['ID'],
                    $arElement['IBLOCK_SECTION_ID']
                );
                $arElement["EDIT_LINK"] = $buttons["edit"]["edit_element"]["ACTION_URL"];
                $arElement["DELETE_LINK"] = $buttons["edit"]["delete_element"]["ACTION_URL"];

                if (!empty($arProps['RZ_HIT'])) $arElement['PROPERTIES']['RZ_HIT'] = $arProps['RZ_HIT'];
                if (!empty($arProps['PRICE'])) $arElement['PROPERTIES']['PRICE'] = $arProps['PRICE'];
                if (!empty($arProps['CURRENCY'])) $arElement['PROPERTIES']['CURRENCY'] = $arProps['CURRENCY'];
                $arElements[] = $arElement;
            }
            $arResult = array(
                "SECTION" => array('NAME' => $arParams['HEADER_OF_TABS']),
                'ELEMENTS' => $arElements
            );


            if (empty($arResult['SECTION']) || empty($arResult['ELEMENTS']))
                $this->AbortResultCache();
        }
    }
} elseif ($arParams['USE_PARAMS_OR_DATA'] == 'PARAMS') {
    $this->IncludeComponentTemplate();
    return;
}
$this->IncludeComponentTemplate();
