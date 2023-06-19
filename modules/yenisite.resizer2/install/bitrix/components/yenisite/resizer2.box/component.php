<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
{
	return;
}

include_once(dirname(__FILE__)."/functions.php");
/*************************************************************************
	Processing of received parameters
*************************************************************************/
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 3600;

unset($arParams["IBLOCK_TYPE"]); //was used only for IBLOCK_ID setup with Editor
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);
$arParams["CACHE_FILTER"] = $arParams["CACHE_FILTER"]=="Y";
if(!$arParams["CACHE_FILTER"] && count($arrFilter)>0)
	$arParams["CACHE_TIME"] = 0;

/*************************************************************************
	Start caching
*************************************************************************/

if($this->StartResultCache(false, array($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups(), $arrFilter)))
{
	
	if($arParams['PROPERTY_CODE'] && $arParams["ELEMENT_ID"]){		
		
		$arResult = GetElementsImage($arParams["ELEMENT_ID"], $arParams);
		if(empty($arResult['PATH']) && intval($arParams["PARENT_ELEMENT_ID"]) > 0)
		{
			$arResult = GetElementsImage($arParams["PARENT_ELEMENT_ID"], $arParams);		
		}
	}
	
	if(!$arResult['PATH']){
		//API v shablone ne raspoznaet takoi pyt' i vstavit zaglyshky
		$arResult['PATH'][0]="no_images";
	}
	
	$this->IncludeComponentTemplate();
	
	$this->EndResultCache();
}


?>
