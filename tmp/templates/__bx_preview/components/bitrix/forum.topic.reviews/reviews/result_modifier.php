<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if($_REQUEST['rz_ajax'] != 'Y')
{
	// ##### FOR AJAX
	// @var $moduleCode
	include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/module_code.php';
	
	$save_param = new CPHPCache();
	if($save_param->InitCache(86400*14, SITE_ID."_catalog_review_forum".$arParams['ELEMENT_ID'], "/{$moduleCode}/ajax/catalog/review/forum"))
		if($arParams != $save_param->GetVars())
			CPHPCache::Clean(SITE_ID."_catalog_review_forum", "/{$moduleCode}/ajax/catalog/review/forum");
	if($save_param->StartDataCache()):
		$save_param->EndDataCache($arParams);
	endif;
	unset($save_param);
}