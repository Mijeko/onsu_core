<?
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global array $FIELDS */
use Bitrix\Main\Loader;

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
global $MODULE_ID;
$MODULE_ID = 'yenisite.catchbuy';
IncludeModuleLangFile(__FILE__);

// Check rights
$POST_RIGHT = $APPLICATION->GetGroupRight($MODULE_ID);
if ($POST_RIGHT == 'D')
	$APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
?>
<?
// BUSINESS LOGIC
Loader::includeModule($MODULE_ID);

$sTableID = 'tbl_catchbuy_list';
$oSort = new CAdminSorting($sTableID, 'ID', 'asc');
$lAdmin = new CAdminList($sTableID, $oSort);
$by = strtoupper($by);
$order = strtoupper($order);
$arOrder = ($by === 'ID' ? array($by => $order) : array($by => $order, 'ID' => 'asc'));

$APPLICATION->SetTitle(GetMessage($MODULE_ID . "_title"));
?>
<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php'); // второй общий пролог
?>
<h1>WORK IN PROGRESS...</h1>
<?
?>
<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php'); ?>