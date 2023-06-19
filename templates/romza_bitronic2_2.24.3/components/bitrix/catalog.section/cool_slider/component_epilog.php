<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Page\Asset;

CJSCore::Init(array('rz_b2_bx_catalog_item'));
Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/libs/UmCoolSlider.js");

do {
	if (empty($templateData['jsFile'])) break;

	$filePath = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . $templateData['jsFile'];
	if (!file_exists($filePath)) break;

	if (\Yenisite\Core\Tools::isAjax()) {
		$jsString = file_get_contents($filePath);
		echo '<script>', $jsString, '</script>';
	} else {
		echo '<script src="', $templateData['jsFile'], '?', @filemtime($filePath), '"></script>';
	}
} while (0);