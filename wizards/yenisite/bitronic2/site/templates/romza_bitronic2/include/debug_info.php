<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// @var $moduleId
include 'module_code.php';

if(CModule::IncludeModule($moduleId)) {
	echo CRZBitronic2CatalogUtils::insertServiceInfo();
}
