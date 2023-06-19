<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$resizer = array('WIDTH' => 102, 'HEIGHT' => 120, 'SET_ID' => $arParams['RESIZER_ITEM']);
$arNewArrOfSections = array();
$arCurSection = &$arResult['SECTION'];

foreach ($arResult['SECTIONS'] as $key => &$arItem) {
	$arItem['PICTURE'] = CRZBitronic2CatalogUtils::GetResizedImg($arItem['PICTURE']['ID'], $resizer);
    $arNewArrOfSections[$arItem['ID']] = $arItem;
}
if ($arCurSection['DEPTH_LEVEL'] == 1){
    CRZBitronic2CatalogUtils::setSectionsOnOneLvlUp($arResult['SECTIONS']);
}
$arNewSortItems = CRZBitronic2CatalogUtils::sortSectionsByLvl($arResult['SECTIONS'],$arParams['TOP_DEPTH']);
$arResult['SECTIONS'] = $arNewArrOfSections;
$arResult['SECTIONS_SORT'] = $arNewSortItems;
unset($arItem,$resizer);