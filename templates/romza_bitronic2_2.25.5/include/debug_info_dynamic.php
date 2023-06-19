<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// @var $moduleId
include 'module_code.php';

if(CModule::IncludeModule($moduleId)) {
	if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin('');
	echo CRZBitronic2CatalogUtils::insertServiceInfo();
	if(method_exists($this, 'createFrame')) $frame->end();
}
