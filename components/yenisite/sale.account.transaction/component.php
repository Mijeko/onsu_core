<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule("sale"))
{
	ShowError(GetMessage("SAP_MODULE_NOT_INSTALL"));
	return;
}
if (!CModule::IncludeModule("currency"))
{
	ShowError(GetMessage("SAP_MODULE_CURRENCY_NOT_INSTALL"));
	return;
}
//if (!CBXFeatures::IsFeatureEnabled('SaleAccounts'))
//	return;

global $USER;

/*************************************************************************
	Processing of received parameters
*************************************************************************/
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 1800;
if(empty($arParams["USER_ID"]))
	$arParams["USER_ID"] = $USER->GetID();	
$baseCurrency = CCurrency::GetBaseCurrency();
$arParams["ACCOUNT_CURRENCY"] = strlen($arParams["ACCOUNT_CURRENCY"])>0 ? trim($arParams["ACCOUNT_CURRENCY"]): $baseCurrency;

/*************************************************************************
	Start caching
*************************************************************************/
if($this->StartResultCache())
{
	$arResult = array();
	$res = CSaleUserTransact::GetList(Array("TIMESTAMP_X" => "DESC"), array("USER_ID" => $arParams["USER_ID"], 'CURRENCY' => $baseCurrency));
	while ($arFields = $res->Fetch())
	{
		$arResult[] = $arFields;
	}
	$this->IncludeComponentTemplate();

	$this->EndResultCache();
}
?>