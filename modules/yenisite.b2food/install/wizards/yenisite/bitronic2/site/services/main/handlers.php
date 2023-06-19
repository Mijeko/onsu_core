<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!defined("WIZARD_SITE_ID") || !defined("WIZARD_ABSOLUTE_PATH"))
	return;

// @var string $moduleCode
// @var string $moduleId
include WIZARD_ABSOLUTE_PATH.'/include/moduleCode.php';
UnRegisterModuleDependences('main','OnGetStaticCacheProvider', $moduleId, 'CacheProvider', 'getObject');
UnRegisterModuleDependences("main", "OnBeforeUserUpdate",   $moduleId, "CRZBitronic2Handlers", "OnBeforeUserRegisterHandler", "", array(WIZARD_SITE_ID));

UnRegisterModuleDependences("iblock", "OnAfterIBlockElementUpdate", $moduleId, "CRZBitronic2Handlers", "SetAvailableStatus");
UnRegisterModuleDependences("catalog", "OnProductUpdate",           $moduleId, "CRZBitronic2Handlers", "SetAvailableStatus");
UnRegisterModuleDependences("catalog", "OnProductAdd",              $moduleId, "CRZBitronic2Handlers", "SetAvailableStatus");

UnRegisterModuleDependences("iblock", "OnAfterIBlockElementUpdate", $moduleId, "CRZBitronic2Handlers", "DoIBlockAfterSave");
UnRegisterModuleDependences("iblock", "OnAfterIBlockElementAdd",    $moduleId, "CRZBitronic2Handlers", "DoIBlockAfterSave");
UnRegisterModuleDependences("catalog", "OnPriceAdd",                $moduleId, "CRZBitronic2Handlers", "DoIBlockAfterSave");
UnRegisterModuleDependences("catalog", "OnPriceUpdate",             $moduleId, "CRZBitronic2Handlers", "DoIBlockAfterSave");
UnRegisterModuleDependences("catalog", "OnProductUpdate",           $moduleId, "CRZBitronic2Handlers", "DoIBlockAfterSave");
UnRegisterModuleDependences("catalog", "OnStoreProductUpdate",      $moduleId, "CRZBitronic2Handlers", "StoreInProperties");
UnRegisterModuleDependences("sale", "OnOrderAdd", 					$moduleId, "CRZBitronic2Handlers", "setGeoStoreToOrder");

// 404
RegisterModuleDependences("main",    "OnEpilog",                   $moduleId, "CRZBitronic2Handlers", "Redirect404", 100, "", array(WIZARD_SITE_ID));
// User email as login
RegisterModuleDependences("main",    "OnBeforeUserRegister",       $moduleId, "CRZBitronic2Handlers", "OnBeforeUserRegisterHandler", 100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("main",    "OnBeforeUserAdd",            $moduleId, "CRZBitronic2Handlers", "OnBeforeUserRegisterHandler", 100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("main",    "OnBeforeUserUpdate",         $moduleId, "CRZBitronic2Handlers", "OnBeforeUserUpdateHandler",   100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("main",    "OnAfterUserRegister",        $moduleId, "CRZBitronic2Handlers", "OnAfterUserRegisterHandler",  100, "", array(WIZARD_SITE_ID));
// feedback forms
RegisterModuleDependences("catalog", "OnPriceUpdate",              $moduleId, "CRZBitronic2Handlers", "SendEmailByLowerPrice", 100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("catalog", "OnProductUpdate",            $moduleId, "CRZBitronic2Handlers", "SendEmailByAvailable",  100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("catalog", "OnDiscountAdd",              $moduleId, "CRZBitronic2Handlers", "OnDiscountAddUpdate", 100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("catalog", "OnDiscountUpdate",           $moduleId, "CRZBitronic2Handlers", "OnDiscountAddUpdate", 100, "", array(WIZARD_SITE_ID));
// fill RZ_AVAILABLE
RegisterModuleDependences("iblock",  "OnAfterIBlockElementUpdate", $moduleId, "CRZBitronic2Handlers", "SetAvailableStatus",  100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("catalog", "OnProductUpdate",            $moduleId, "CRZBitronic2Handlers", "SetAvailableStatus",  100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("catalog", "OnProductAdd",               $moduleId, "CRZBitronic2Handlers", "SetAvailableStatus",  100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("catalog", "OnBeforePriceDelete",        $moduleId, "CRZBitronic2Handlers", "OnBeforePriceDelete", 100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("catalog", "OnPriceAdd",                 $moduleId, "CRZBitronic2Handlers", "OnPriceChangeCheckOnRequest", 100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("catalog", "OnPriceUpdate",              $moduleId, "CRZBitronic2Handlers", "OnPriceChangeCheckOnRequest", 100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("catalog", "OnPriceDelete",              $moduleId, "CRZBitronic2Handlers", "OnPriceDeleteCheckOnRequest", 100, "", array(WIZARD_SITE_ID));

//RegisterModuleDependences("sale",    "OnOrderAdd",                 $moduleId, "CRZBitronic2Handlers", "setGeoStoreToOrder"); already registered in install/index.php

// for SKU fill props with MIN_PRICE & MAX_PRICE
RegisterModuleDependences("iblock",  "OnAfterIBlockElementUpdate", $moduleId, "CRZBitronic2Handlers", "DoIBlockAfterSave", 100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("iblock",  "OnAfterIBlockElementAdd",    $moduleId, "CRZBitronic2Handlers", "DoIBlockAfterSave", 100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("catalog", "OnPriceAdd",                 $moduleId, "CRZBitronic2Handlers", "DoIBlockAfterSave", 100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("catalog", "OnPriceUpdate",              $moduleId, "CRZBitronic2Handlers", "DoIBlockAfterSave", 100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("catalog", "OnProductUpdate",            $moduleId, "CRZBitronic2Handlers", "DoIBlockAfterSave", 100, "", array(WIZARD_SITE_ID));
RegisterModuleDependences("catalog", "OnStoreProductUpdate",       $moduleId, "CRZBitronic2Handlers", "StoreInProperties", 100, "", array(WIZARD_SITE_ID));

//for composite
RegisterModuleDependences('main','OnGetStaticCacheProvider', $moduleId, 'CacheProvider', 'getObject', 100, "", array(WIZARD_SITE_ID));

//for change currency of order
RegisterModuleDependences('sale','OnSaleComponentOrderResultPrepared', $moduleId, 'CRZBitronic2Handlers', 'changeCurrencyOrder', 100, "", array(WIZARD_SITE_ID));
//change event for add comment
RegisterModuleDependences("sale", "OnOrderSave", $moduleId, "CRZBitronic2Handlers", "setGeoStoreToOrder");
