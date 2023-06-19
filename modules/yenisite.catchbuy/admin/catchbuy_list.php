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
if (!Loader::includeModule('catalog')) {
	die();
}
$sTableID = 'tbl_catchbuy_list';
$oSort = new CAdminSorting($sTableID, 'ID', 'asc');
$lAdmin = new CAdminList($sTableID, $oSort);
$by = strtoupper($by);
$order = strtoupper($order);
$arOrder = ($by === 'ID' ? array($by => $order) : array($by => $order, 'ID' => 'asc'));
// ******************************************************************** //
//                           FILTER                                     //
// ******************************************************************** //

// ������ �������� �������
$FilterArr = Array(
	'find_id',
	'find_lid',
	'find_product_id',
	'find_active',
	'find_discount_id',
);
// �������������� ������

// *********************** CheckFilter ******************************** //
function CheckFilter() {
	global $lAdmin;
	// � ������ ������ ��������� ������.
	// � ����� ������ ����� ��������� �������� ���������� $find_���
	// � � ������ �������������� ������ ���������� �� �����������
	// ����������� $lAdmin->AddFilterError('�����_������').
	return count($lAdmin->arFilterErrors) == 0; // ���� ������ ����, ������ false;
}

// *********************** /CheckFilter ******************************* //
$lAdmin->InitFilter($FilterArr);
// ���� ��� �������� ������� ���������, ���������� ���
if (CheckFilter()) {
	$arFilter = array(
		'ID' => $find_id,
		'LID' => $find_lid,
		'ACTIVE' => $find_active,
		'PRODUCT_ID' => $find_product_id,
		'DISCOUNT_ID' => $find_discount_id
	);
	foreach ($arFilter as $ID => $value) {
		if (empty($value)) {
			unset($arFilter[$ID]);
		}
	}

}
// ******************************************************************** //
//                LIST ACTIONS                                          //
// ******************************************************************** //

// SAVE
if ($lAdmin->EditAction() && $POST_RIGHT >= 'F') {
	// ������� �� ������ ���������� ���������
	foreach ($FIELDS as $ID => $arFields) {
		if (!$lAdmin->IsUpdated($ID))
			continue;

		// �������� ��������� ������� ��������
		$DB->StartTransaction();
		$ID = intval($ID);
		$cData = new Yenisite\Catchbuy\Catchbuy;
		if (($rsData = $cData->GetByID($ID)) && ($arData = $rsData->Fetch())) {
			foreach ($arFields as $key => $value)
				$arData[$key] = $value;
			$result = $cData->updateFromAdmin($ID, $arData);
			if (!$result) {
				$lAdmin->AddGroupError(GetMessage($MODULE_ID . '_error_save') . ' ' . implode('<br>', $cData->getErrors()), $ID);
				$DB->Rollback();
			}
		} else {
			$lAdmin->AddGroupError(GetMessage($MODULE_ID . '_error_save') . ' ' . GetMessage($MODULE_ID . '_error_not_found_id'), $ID);
			$DB->Rollback();
		}
		$DB->Commit();
		$cData->clearTagCacheCatchbuy($ID);
	}
}

// ��������� ��������� � ��������� ��������
if (($arID = $lAdmin->GroupAction()) && $POST_RIGHT >= 'F') {
	// ���� ������� '��� ���� ���������'
	if ($_REQUEST['action_target'] == 'selected') {
		$cData = new Yenisite\Catchbuy\Catchbuy();
		$rsData = $cData->GetList(
			array(
				'filter' => $arFilter,
				'order' => $arOrder
			)
		);
		while ($arRes = $rsData->Fetch())
			$arID[] = $arRes['ID'];
	}

	// ������� �� ������ ���������
	foreach ($arID as $ID) {
		if (strlen($ID) <= 0)
			continue;
		$ID = intval($ID);

		// ��� ������� �������� �������� ��������� ��������
		switch ($_REQUEST['action']) {
			// ��������
			case 'delete':
				@set_time_limit(0);
				$DB->StartTransaction();
				$result = Yenisite\Catchbuy\Catchbuy::Delete($ID);
				Yenisite\Catchbuy\Catchbuy::clearTagCacheCatchbuy($ID);
				if (!$result->isSuccess()) {
					$DB->Rollback();
					$lAdmin->AddGroupError(GetMessage($MODULE_ID . '_error_delete'), $ID);
				}
				$DB->Commit();
				break;

			// ���������/�����������
			case 'activate':
			case 'deactivate':
				$cData = new Yenisite\Catchbuy\Catchbuy();
				$cData->clearTagCacheCatchbuy($ID);
				if (($rsData = $cData->GetByID($ID)) && ($arFields = $rsData->Fetch())) {
					$arFields['ACTIVE'] = ($_REQUEST['action'] == 'activate' ? 'Y' : 'N');
					$result = $cData->updateFromAdmin($ID, $arFields);
					if (!$result)
						$lAdmin->AddGroupError(GetMessage($MODULE_ID . '_error_save') . implode('<br>', $cData::getErrors()), $ID);
				} else
					$lAdmin->AddGroupError(GetMessage($MODULE_ID . '_error_save') . ' ' . GetMessage($MODULE_ID . '_error_not_found_id'), $ID);
				break;
		}
	}
}
// ******************************************************************** //
//                ������� ��������� ������                              //
// ******************************************************************** //

// ������� ������ ��������
$cData = new Yenisite\Catchbuy\Catchbuy();
$rsData = $cData->GetList(
	array(
		'order' => $arOrder,
		'filter' => $arFilter
	)
);
// ����������� ������ � ��������� ������ CAdminResult
$rsData = new CAdminResult($rsData, $sTableID);

// ���������� CDBResult �������������� ������������ ���������.
$rsData->NavStart();

// �������� ����� ������������� ������� � �������� ������ $lAdmin
$lAdmin->NavText($rsData->GetNavPrint(GetMessage($MODULE_ID . '_title_navigation')));

// ******************************************************************** //
//                ���������� ������ � ������                            //
// ******************************************************************** //
$arFields = $cData->getMap();
$arAdmFields = array();
foreach ($arFields as $fID => $fVal) {
	if(isset($fVal['admin_edit'])) {
		$arAdmFields[] = array(
			'id' => $fID,
			'content' => $fVal['title'],
			'sort' => $fID,
			'default' => $fVal['default_in_list'],
		);
	}
}
$lAdmin->AddHeaders($arAdmFields);
$visibleHeadersColumns = $lAdmin->GetVisibleHeaderColumns();

while ($arRes = $rsData->NavNext()):

	// ������� ������. ��������� - ��������� ������ CAdminListRow
	$row = &$lAdmin->AddRow($arRes['ID'], $arRes);
	if (in_array('CREATED_BY', $visibleHeadersColumns)) {
		\Yenisite\Catchbuy\Tools::LinkListProp($row, 'CREATED_BY', array('CUser', 'GetByID'), array(), 'LOGIN, NAME, LAST_NAME', 'user_edit.php');
	}
	if (in_array('MODIFIED_BY', $visibleHeadersColumns)) {
		\Yenisite\Catchbuy\Tools::LinkListProp($row, 'MODIFIED_BY', array('CUser', 'GetByID'), array(), 'LOGIN, NAME, LAST_NAME', 'user_edit.php');
	}
	if (in_array('DISCOUNT_ID', $visibleHeadersColumns)) {
		\Yenisite\Catchbuy\Tools::LinkListProp($row, 'DISCOUNT_ID', array('CCatalogDiscount', 'GetByID'), array(), 'NAME', 'cat_discount_edit.php');
	}
	if (in_array('PRODUCT_ID', $visibleHeadersColumns)) {
		\Yenisite\Catchbuy\Tools::LinkListProp($row, 'PRODUCT_ID', array('CIBlockElement', 'GetByID'), array(), 'NAME',
			'iblock_element_edit.php?IBLOCK_ID=#IBLOCK_ID#&type=catalog&find_section_section=#IBLOCK_SECTION_ID#');
	}
	// �������� ID ����� ������������ �������
	$row->AddViewField('ID', '<a href="catchbuy_edit.php?ID=' . $arRes['ID'] . '&lang=' . LANG . '">' . $arRes['ID'] . '</a>');
	// �������� LID ����� ��������������� � ���� ����������� ������ ������
	$row->AddEditField('LID', Csite::SelectBox('FIELDS[' . $arRes['ID'] . '][LID]', $arRes['LID']));
	// ����� ACTIVE
	$row->AddCheckField('ACTIVE');
	// �������� AUTO ����� ������������ � ���� '��' ��� '���', ���������� ��� ��������������
	//$row->AddViewField('AUTO', $f_AUTO == 'Y' ? GetMessage('POST_U_YES') : GetMessage('POST_U_NO'));
	//$row->AddEditField('AUTO', '<b>' . ($f_AUTO == 'Y' ? GetMessage('POST_U_YES') : GetMessage('POST_U_NO')) . '</b>');

	// ���������� ����������� ����
	$arActions = array();
	// �������������� ��������
	$arActions[] = array(
		'ICON' => 'edit',
		'DEFAULT' => true,
		'TEXT' => GetMessage($MODULE_ID . '_action_edit'),
		'ACTION' => $lAdmin->ActionRedirect('catchbuy_edit.php?ID=' . $arRes['ID'])
	);

	// �������� ��������
	if ($POST_RIGHT >= 'F') {
		$arActions[] = array(
			'ICON' => 'delete',
			'TEXT' => GetMessage($MODULE_ID . '_action_delete'),
			'ACTION' => 'if(confirm("' . GetMessage($MODULE_ID . '_action_delete_confirm') . '")) ' . $lAdmin->ActionDoGroup($arRes['ID'], 'delete'),
		);
	}
	// ������� �����������
	$arActions[] = array('SEPARATOR' => true);

	// ���� ��������� ������� - �����������, �������� �����.
	if (is_set($arActions[count($arActions) - 1], 'SEPARATOR'))
		unset($arActions[count($arActions) - 1]);
	// �������� ����������� ���� � ������
	$row->AddActions($arActions);

endwhile;

// ������ �������
$lAdmin->AddFooter(
	array(
		array('title' => GetMessage('MAIN_ADMIN_LIST_SELECTED'), 'value' => $rsData->SelectedRowsCount()), // ���-�� ���������
		array('counter' => true, 'title' => GetMessage('MAIN_ADMIN_LIST_CHECKED'), 'value' => '0'), // ������� ��������� ���������
	)
);

// ��������� ��������
$lAdmin->AddGroupActionTable(Array(
	'delete' => GetMessage('MAIN_ADMIN_LIST_DELETE'), // ������� ��������� ��������
	'activate' => GetMessage('MAIN_ADMIN_LIST_ACTIVATE'), // ������������ ��������� ��������
	'deactivate' => GetMessage('MAIN_ADMIN_LIST_DEACTIVATE'), // �������������� ��������� ��������
));

// ******************************************************************** //
//                ���������������� ����                                 //
// ******************************************************************** //

// ���������� ���� �� ������ ������ - ����������
$aContext = array(
	array(
		"TEXT" => GetMessage($MODULE_ID . "_action_add"),
		"LINK" => "catchbuy_edit.php?lang=" . LANG,
		"TITLE" => GetMessage($MODULE_ID . "_action_add_title"),
		"ICON" => "btn_new",
	),
);

// � ��������� ��� � ������
$lAdmin->AddAdminContextMenu($aContext);

// ******************************************************************** //
//                �����                                                 //
// ******************************************************************** //

// �������������� �����
$lAdmin->CheckListMode();

// ��������� ��������� ��������
$APPLICATION->SetTitle(GetMessage($MODULE_ID . "_title"));
?>
<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php'); // ������ ����� ������
?>
<?
// ******************************************************************** //
//                FILTER PRINT                                          //
// ******************************************************************** //
$oFilter = new CAdminFilter(
	$sTableID . '_filter',
	array(
		'ID',
		GetMessage($MODULE_ID . '_f_site'),
		GetMessage($MODULE_ID . '_f_active'),
		GetMessage($MODULE_ID . '_f_product_id'),
		GetMessage($MODULE_ID . '_f_discount_id'),
	)
);
?>
	<form name='find_form' method='get' action='<?= $APPLICATION->GetCurPage(); ?>'>
		<? $oFilter->Begin(); ?>
		<tr>
			<td><?= 'ID' ?>:</td>
			<td>
				<input type='text' name='find_id' size='47' value='<?= htmlspecialchars($find_id) ?>'>
			</td>
		</tr>
		<tr>
			<td><?= GetMessage($MODULE_ID . '_f_site') . ':' ?></td>
			<td>
				<?
				$arr = array(
					'reference' => array(),
					'reference_id' => array(),
				);
				$rs = CSite::GetList($by = 'SORT', $order = 'ASC');
				while ($ar = $rs->Fetch()) {
					$arr['reference'][] = $ar['NAME'];
					$arr['reference_id'][] = $ar['LID'];
				}
				echo SelectBoxFromArray('find_lid', $arr, $find_lid, GetMessage($MODULE_ID . '_POST_ALL'), '');
				?>
			</td>
		</tr>
		<tr>
			<td><?= GetMessage($MODULE_ID . '_f_active') ?>:</td>
			<td>
				<?
				$arr = array(
					'reference' => array(
						GetMessage($MODULE_ID . '_POST_YES'),
						GetMessage($MODULE_ID . '_POST_NO'),
					),
					'reference_id' => array(
						'Y',
						'N',
					)
				);
				echo SelectBoxFromArray('find_active', $arr, $find_active, GetMessage($MODULE_ID . '_POST_ALL'), '');
				?>
			</td>
		</tr>
		<tr>
			<td><?= GetMessage($MODULE_ID . '_f_product_id') ?>:</td>
			<td>
				<input type='text' name='find_product_id' size='47' value='<?= htmlspecialchars($find_product_id) ?>'>
			</td>
		</tr>
		<tr>
			<td><?= GetMessage($MODULE_ID . '_f_discount_id') ?>:</td>
			<td>
				<input type='text' name='find_discount_id' size='47' value='<?= htmlspecialchars($find_discount_id) ?>'>
			</td>
		</tr>
		<?
		$oFilter->Buttons(array('table_id' => $sTableID, 'url' => $APPLICATION->GetCurPage(), 'form' => 'find_form'));
		$oFilter->End();
		?>
	</form>
<?
// ������� ������� ������ ���������
$lAdmin->DisplayList();
?>
<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php'); ?>