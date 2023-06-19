<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$requiredModules = array('highloadblock');

foreach ($requiredModules as $requiredModule)
{
	if (!CModule::IncludeModule($requiredModule))
	{
		ShowError(GetMessage("F_NO_MODULE"));
		return 0;
	}
}

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

global $USER_FIELD_MANAGER;

// hlblock info
$hlblock_id = $arParams['BLOCK_ID'];

if (empty($hlblock_id))
{
	ShowError(GetMessage('HLBLOCK_VIEW_NO_ID'));
	return 0;
}

$hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();

if (empty($hlblock))
{
	ShowError('404');
	return 0;
}

$entity = HL\HighloadBlockTable::compileEntity($hlblock);

$arFilter = $entity->hasField('UF_XML_ID')
	? array(
		'LOGIC' => 'OR',
		'=ID' => $arParams['ROW_ID'],
		'=UF_XML_ID' => $arParams['ROW_XML_ID']
	)
	: array('=ID' => $arParams['ROW_ID']);

// row data
$main_query = new Entity\Query($entity);
$main_query->setSelect(array('*'));
$main_query->setFilter($arFilter);

$result = $main_query->exec();
$result = new CDBResult($result);
$row = $result->Fetch();

$fields = $USER_FIELD_MANAGER->getUserFieldsWithReadyData('HLBLOCK_'.$hlblock['ID'], $row, LANGUAGE_ID);

if (empty($row))
{
    if ($arParams['SET_404'] != 'N'){
        \Bitrix\Iblock\Component\Tools::process404(
            ""
            ,($arParams["SET_404"] != 'N')
            ,($arParams["SET_404"] != 'N')
            ,($arParams["SHOW_404"] === "Y")
            ,$arParams["FILE_404"]
        );
    } else{
        ShowError(sprintf(GetMessage('HLBLOCK_VIEW_NO_ROW'), $arParams['ROW_ID']));
    }
	return 0;
}

$arResult['fields'] = $fields;
$arResult['row'] = $row;

// fill meta data
$arTitleOptions = array('COMPONENT_NAME' => $this->getName());

$name = $row['UF_NAME'] ?: ($row['UF_XML_ID'] ?: GetMessage('HLBLOCK_RECORD_ID', array('#ID#' => $row['ID'])));

if ('N' !== $arParams['SET_TITLE']) {
	$APPLICATION->SetTitle($name, $arTitleOptions);
}
unset($arResult['row']['ID']);
$keyFields = array_keys($arResult['fields']);
$valueFields = array_values($arResult['row']);

if ('N' !== $arParams['SET_BROWSER_TITLE']) {
    if (!empty($arParams['STR_FOR_BROWSER'])){
        $title = str_replace($keyFields,$valueFields,$arParams['STR_FOR_BROWSER']);
        $title = str_replace('#','',$title);
    }else {
        $title = $APPLICATION->GetDirProperty('title') ?: '';
        if (!empty($title)) $title .= ' :: ';
        $title .= ($row[$arParams['BROWSER_TITLE']] ?: $name);
    }
	$APPLICATION->SetPageProperty('title', $title, $arTitleOptions);
}
if ('N' !== $arParams['SET_DESCRIPTION_PAGE']) {
    $arParams['STR_FOR_DESCRIPTION'] = $arParams['STR_FOR_DESCRIPTION'] ? : GetMessage('DEF_STR_FOR_DESCRIPTION');
    $description = str_replace($keyFields,$valueFields,$arParams['STR_FOR_DESCRIPTION']);
    $description = str_replace('#','',$description);
    $APPLICATION->SetPageProperty('description', $description, $arTitleOptions);
}
if ('N' !== $arParams['SET_KEYWORDS_PAGE']) {
    $arParams['STR_FOR_KEY_WORDS'] = $arParams['STR_FOR_KEY_WORDS'] ? : GetMessage('DEF_STR_FOR_KEYWORDS');
    $keywords = str_replace($keyFields,$valueFields,$arParams['STR_FOR_KEY_WORDS']);
    $keywords = str_replace('#','',$keywords);
    $APPLICATION->SetPageProperty('keywords', $keywords, $arTitleOptions);
}
if ('Y' === $arParams['ADD_ELEMENT_CHAIN']) {
	$APPLICATION->AddChainItem($name);
}

// template
$this->IncludeComponentTemplate();