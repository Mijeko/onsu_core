<?
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global array $FIELDS */
use Bitrix\Main\Loader;
use Yenisite\Catchbuy;

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
CJSCore::Init(array("jquery"));
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
if (!Loader::includeModule('catalog')) {
	die();
}
// ���������� ������ ��������
$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage($MODULE_ID . "_TAB_MAIN"), "ICON" => "main_user_edit", "TITLE" => GetMessage($MODULE_ID . "_TAB_MAIN")),
	array("DIV" => "edit2", "TAB" => GetMessage($MODULE_ID . "_TAB_DISCOUNT"), "ICON" => "main_user_edit", "TITLE" => GetMessage($MODULE_ID . "_TAB_DISCOUNT")),
);
$tabControl = new CAdminForm("tabControl", $aTabs);

$ID = intval($ID);        // ������������� ������������� ������
$message = null;        // ��������� �� ������
$bVarsFromForm = false; // ���� "������ �������� � �����", ������������, ��� ��������� ������ �������� � �����, � �� �� ��.

$Entity = new Catchbuy\Catchbuy();
$arMap = $Entity->getMap(); // ���� ������� ��������
// ******************************************************************** //
//                ��������� ��������� �����                             //
// ******************************************************************** //

if (
	$REQUEST_METHOD == "POST" // �������� ������ ������ ��������
	&&
	($save != "" || $apply != "") // �������� ������� ������ "���������" � "���������"
	&&
	$POST_RIGHT >= "F"          // �������� ������� ���� �� ������ ��� ������
	&&
	check_bitrix_sessid()     // �������� �������������� ������
) {
	$arFields = array();
	// ��������� ������ �����
	foreach ($arMap as $key => $value) {
		if ($value['admin_edit'] & Catchbuy\Tools::U_EDIT) {
			if (isset($$key)) {
				$arFields[$key] = $$key;
			}
		}
	}
	if (isset($DISCOUNT)) {
		$arFields['DISCOUNT'] = $DISCOUNT;
	}
	// ���������� ������
	if ($ID > 0) {
		$result = $Entity->updateFromAdmin($ID, $arFields);
		$Entity->clearTagCacheCatchbuy($ID);
	} else {
		$result = $Entity->add($arFields);
	}
	if ($result) {
		// ���� ���������� ������ ������ - ������������ �� ����� ��������
		// (� ����� ������ �� ��������� �������� ����� �������� ������ "��������" � ��������)
		if ($apply != "")
			// ���� ���� ������ ������ "���������" - ���������� ������� �� �����.
			LocalRedirect("/bitrix/admin/catchbuy_edit.php?ID=" . $result . "&mess=ok&lang=" . LANG . "&" . $tabControl->ActiveTabParam());
		else
			// ���� ���� ������ ������ "���������" - ���������� � ������ ���������.
			LocalRedirect("/bitrix/admin/catchbuy_list.php?lang=" . LANG);
	} else {
		// ���� � �������� ���������� �������� ������ - �������� ����� ������ � ������ ��������������� ����������
		$message = new CAdminMessage(implode('<br>', $Entity->getErrors()));
		$bVarsFromForm = true;
	}
}
// ******************************************************************** //
//                ������� � ���������� ������ �����                     //
// ******************************************************************** //

// ������� ������
$arEntity = false;
if ($ID > 0) {
	$result = $Entity::GetByID($ID);
	if (!($arEntity = $result->fetch())) {
		$ID = 0;
	}
}
// ******************************************************************** //
//                ����� �����                                           //
// ******************************************************************** //

// ��������� ��������� ��������
$APPLICATION->SetTitle(($ID > 0 ? GetMessage($MODULE_ID . "_TITLE_EDIT", array('#ID#' => $ID)) : GetMessage($MODULE_ID . "_TITLE_ADD")));

// �� ������� ��������� ���������� ������ � �����
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

// ������������ ����������������� ����
$aMenu = array(
	array(
		"TEXT" => GetMessage($MODULE_ID . "_MENU_LIST"),
		"TITLE" => GetMessage($MODULE_ID . "_MENU_LIST_TITLE"),
		"LINK" => "catchbuy_list.php?lang=" . LANG,
		"ICON" => "btn_list",
	)
);

if ($ID > 0) {
	$aMenu[] = array("SEPARATOR" => "Y");
	$aMenu[] = array(
		"TEXT" => GetMessage($MODULE_ID . "_MENU_ADD"),
		"TITLE" => GetMessage($MODULE_ID . "_MENU_ADD"),
		"LINK" => "catchbuy_edit.php?lang=" . LANG,
		"ICON" => "btn_new",
	);
	$aMenu[] = array(
		"TEXT" => GetMessage($MODULE_ID . "_MENU_DELETE"),
		"TITLE" => GetMessage($MODULE_ID . "_MENU_DELETE"),
		"LINK" => "javascript:if(confirm('" . GetMessage($MODULE_ID . "_MENU_DELETE_CONFIRM") . "'))window.location='catchbuy_list.php?ID=" . $ID . "&action=delete&lang=" . LANG . "&" . bitrix_sessid_get() . "';",
		"ICON" => "btn_delete",
	);
}

// �������� ���������� ������ ����������������� ����
$context = new CAdminContextMenu($aMenu);
// ����� ����������������� ����
$context->Show();
?>
<?
// ���� ���� ��������� �� ������� ��� �� �������� ���������� - ������� ��.
if ($_REQUEST["mess"] == "ok" && $ID > 0)
	CAdminMessage::ShowMessage(array("MESSAGE" => GetMessage($MODULE_ID . "_ACTION_SAVED"), "TYPE" => "OK"));
if ($message) {
	echo $message->Show();
} elseif (count($Entity->getErrors()) > 0) {
	CAdminMessage::ShowMessage(implode('<br>', $Entity->getErrors()));
}
?>
<?

$tabControl->BeginEpilogContent();
echo bitrix_sessid_post();
$tabControl->EndEpilogContent();
// ��������� ��������� ��������

$tabControl->Begin(array(
	"FORM_ACTION" => "/bitrix/admin/catchbuy_edit.php?lang=" . LANGUAGE_ID . (($ID > 0) ? '&ID=' . $ID : ''),
));
?>
<?
//********************
// ������ ��������
//********************
$tabControl->BeginNextFormTab();
?>
<?
$obAdmin = new Catchbuy\Tools;
$obAdmin->InitAdminHelper($tabControl, $Entity, $arEntity);
$obAdmin->AddDetailField('ACTIVE');
$obAdmin->AddDetailField('ACTIVE_FROM');
$obAdmin->AddDetailField('ACTIVE_TO');
$obAdmin->AddDetailField('LID');
$obAdmin->AddDetailField('PRODUCT_ID');
$obAdmin->AddDetailField('MAX_USES');
$obAdmin->AddDetailField('COUNT_USES');

$obAdmin->AddDetailField('CREATED_BY');
$obAdmin->AddDetailField('MODIFIED_BY');


?>
<?
//********************
// ������ ��������
//********************
$tabControl->BeginNextFormTab();
$obAdmin->AddDetailField('DISCOUNT_ID');

$arDiscount = array();
if ($arEntity['DISCOUNT_ID'] > 0) {
	$ar = CCatalogDiscount::GetByID($arEntity['DISCOUNT_ID']);
	$arDiscount = array(
		'LID' => $ar['SITE_ID'],
		'MAX_USES' => $ar['MAX_USES'],
		'COUNT_USES' => $ar['COUNT_USES'],
		'MAX_DISCOUNT' => $ar['MAX_DISCOUNT'],
		'VALUE_TYPE' => $ar['VALUE_TYPE'],
		'VALUE' => $ar['VALUE'],
		'CURRENCY' => $ar['CURRENCY'],
		'MIN_ORDER_SUM' => $ar['MIN_ORDER_SUM'],
		'ACTIVE_FROM' => $ar['MIN_ORDER_SUM'],
		'ACTIVE_TO' => $ar['MIN_ORDER_SUM'],
	);
}
$tabControl->AddDropDownField('DISCOUNT[VALUE_TYPE]', GetMessage($MODULE_ID . '_DISCOUNT_VALUE_TYPE') . ':', true,
	array('P' => GetMessage($MODULE_ID . '_DISCOUNT_VALUE_TYPE_PERCENT'), 'F' => GetMessage($MODULE_ID . '_DISCOUNT_VALUE_TYPE_FIXED')),
	$arDiscount['VALUE_TYPE']
);
$tabControl->AddEditField('DISCOUNT[VALUE]', GetMessage($MODULE_ID . '_DISCOUNT_VALUE') . ':', true, array(), $arDiscount['VALUE']);

$arCurrency = array();
$rs = \CCurrency::GetList(
	$by = 'sort',
	$order = 'asc',
	LANGUAGE_ID
);
while ($ar = $rs->Fetch()) {
	$arCurrency[$ar['CURRENCY']] = $ar['CURRENCY'];
}
$tabControl->AddDropDownField('DISCOUNT[CURRENCY]', GetMessage($MODULE_ID . '_DISCOUNT_CURRENCY') . ':', true,
	$arCurrency, $arDiscount['CURRENCY']);
?>
<?
// ���������� ����� - ����� ������ ���������� ���������
$tabControl->Buttons(
	array(
		"disabled" => ($POST_RIGHT < "F"),
		"back_url" => "catchbuy_list.php?lang=" . LANG,
	)
);
?>
<?
$tabControl->Show();
?>
<?
// �������������� ����������� �� ������� - ����� ������ ����� ����, � ������� �������� ������
$tabControl->ShowWarnings($tabControl->GetFormName(), $message);
?>
<?
// �������������: ������������ ���������� ��������, ���� ���������.
?>
	<script language="javascript">
		if ('DISCOUNT_ID' in document.tabControl_form && document.tabControl_form.DISCOUNT_ID.value.length > 0)
			tabControl.EnableTab('edit2');
		else
			tabControl.DisableTab('edit2');
	</script>

<?= BeginNote(); ?>
	<span class="required">*</span><?= GetMessage("REQUIRED_FIELDS") ?>
<?= EndNote(); ?>
<?
// ���������� ��������
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
?>