<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitronic2\Mobile;
use Bitrix\Main\Loader;
use Yenisite\Core\Tools;


// ##### FOR AJAX
// @var $moduleCode
// @var $moduleId
include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/module_code.php';

if ($bCore = Loader::IncludeModule('yenisite.core')) {
	\Yenisite\Core\Ajax::saveParams($this,
		array_merge($arParams, array('PATH_TO_VIEW' => $arResult['PATH_TO_VIEW'])),
		$addId = ($arParams['CUSTOM_CACHE_KEY'] ?: '')
	);

	$brandsFolder = $arParams['SEF_MODE'] == 'Y' ? $arParams['SEF_FOLDER'] : dirname($_SERVER['SCRIPT_NAME']) . '/';
	$curValue = COption::GetOptionString($moduleId, 'brands_folder', false, SITE_ID);

	if($curValue != $brandsFolder) {
		COption::SetOptionString($moduleId, 'brands_folder', $brandsFolder, false, SITE_ID);
	}
}

if (strtolower($_REQUEST['rz_update_brands_parameters_cache']) === 'y') {
	$APPLICATION->RestartBuffer();
	die('update');
}