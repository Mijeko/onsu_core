<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

//Needs for iblock.vote to not break composite
IncludeAJAX();

global $arPagination;
$arPagination = $arResult['NAV_PAGINATION'];

do {
	if (empty($templateData['jsFile'])) break;

	$filePath = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . $templateData['jsFile'];
	if (!file_exists($filePath)) break;

	if (\Yenisite\Core\Tools::isAjax()) {
		$jsString = file_get_contents($filePath);
		echo '<script type="application/javascript">', $jsString, '</script>';
	} else {
		echo '<script type="application/javascript" src="', $templateData['jsFile'], '?', @filemtime($filePath), '"></script>';
	}
} while (0);
