<?
define("STOP_STATISTICS", true);
define('NO_AGENT_CHECK', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('yenisite.resizer2');
$bStatic = ($_REQUEST['mode'] === 'path');
$file = CResizer2Resize::ResizeGD2(htmlspecialchars($_REQUEST['url']), htmlspecialchars($_REQUEST['set']), 0, 0, $bStatic);
$file = str_replace('http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'], '' ,$file);

if ($bStatic) {
	$GLOBALS['APPLICATION']->RestartBuffer();
	echo $file;
	die();
}

LocalRedirect($file);
?>
