<?
use Bitrix\Main\Loader;
use Yenisite\Core\Tools;
use Bitrix\Main\Localization\Loc;
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

Loader::IncludeModule('yenisite.core');

Loc::loadMessages(__FILE__);
$arResult = array(
	'success' => false,
	'msg' => array(GetMessage('RZ_CORE_AJAX_NO_ACTION_PROVIDED')),
);
switch ($_REQUEST['ACTION']) {
	case 'HLBLOCK_FILL_COLORS':
		$ID = intval($_REQUEST['hlblock']);
		if ($ID < 1) {
			$arResult['msg'] = 'invalid hlblock id';
			break;
		}
		$arLanguages = Array();
		$rsLanguage = CLanguage::GetList($by, $order, array());
		while($arLanguage = $rsLanguage->Fetch()) {
			$arLanguages[] = $arLanguage["LID"];
		}
		$arLabelNames = Array();
		foreach($arLanguages as $languageID)
		{
			$arLabelNames[$languageID] = GetMessage('UF_RGB');
		}
		$arFields = array(
			'ENTITY_ID' => 'HLBLOCK_'.$ID,
			'FIELD_NAME' => 'UF_RGB',
			'USER_TYPE_ID' => 'string',
			'XML_ID' => 'UF_COLOR_RGB',
			'SORT' => '1000',
			'MULTIPLE' => 'N',
			'MANDATORY' => 'N',
			'SHOW_FILTER' => 'N',
			'SHOW_IN_LIST' => 'Y',
			'EDIT_IN_LIST' => 'Y',
			'IS_SEARCHABLE' => 'N',
			'EDIT_FORM_LABEL' => $arLabelNames,
			'LIST_COLUMN_LABEL' => $arLabelNames,
			'LIST_FILTER_LABEL' => $arLabelNames,
			'SETTINGS' => array(
				'DEFAULT_VALUE' => 'NULL'
			),
		);
		$dbRes = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID" => $arFields["ENTITY_ID"], "FIELD_NAME" => $arFields["FIELD_NAME"]));
		if ($arField = $dbRes->Fetch()) {
			$ID_USER_FIELD = $arField['ID'];
		} else {
			$obUserField   = new CUserTypeEntity;
			$ID_USER_FIELD = $obUserField->Add($arFields);
		}
		if ($ID_USER_FIELD < 1) {
			$arResult['msg'] = 'Could not create required user field UF_RGB';
			break;
		}
		$obColorGuess = new Yenisite\Core\ColorGuess($ID);
		$arResult['success'] = $obColorGuess->initColorReference(true);
		$arResult['msg'] = 'colors filled';
	break;

	case 'GET_IBLOCK_DIRECTORY_PROPS':
		if (!Loader::IncludeModule('iblock')) {
			$arResult['msg'] = 'Module "iblock" is not installed!';
			break;
		}
		$arIBlockID = $_REQUEST['iblock_id'];
		if (!is_array($arIBlockID)) {
			$arIBlockID = array($arIBlockID);
		}
		foreach ($arIBlockID as $key => $iblockID) {
			$iblockID = intval($iblockID);
			if ($iblockID < 1) {
				unset($arIBlockID[$key]);
			} else {
				$arIBlockID[$key] = $iblockID;
			}
		}
		$arIBlocks = array();
		if (!empty($arIBlockID)) {
			$arFilter = array('ID' => $arIBlockID);
			$dbRes = CIBlock::GetList(array(), $arFilter);
			while ($arIBlock = $dbRes->Fetch()) {
				$arIBlocks[] = $arIBlock;
			}
		}
		$tableName = trim($_REQUEST['reference_table']);
		$arFilter = array('ACTIVE' => 'Y', 'USER_TYPE' => 'directory');
		$arResult['msg'] = '';
		foreach ($arIBlocks as $arIBlock) {
			$arResult['msg'] .= '<tr class="colorProps"><td valign="middle" class="adm-detail-content-cell-l">
			<label for="color_guess_prop_'.$arIBlock['ID'].'">'.GetMessage('CORE_COLOR_PROPERTY', array('#IBLOCK_NAME#' => $arIBlock['NAME'])).':</label></td>
			<td valign="bottom" class="adm-detail-content-cell-r"><select id="color_guess_prop_'.$arIBlock['ID'].'" name="color_guess_prop_'.$arIBlock['ID'].'">';

			$propId = intval($_REQUEST['prop_id'][$arIBlock['ID']]);
			$arFilter['IBLOCK_ID'] = $arIBlock['ID'];
			$dbRes = CIBlockProperty::GetList(array(), $arFilter);
			while ($arProp = $dbRes->Fetch()) {
				if ($arProp['USER_TYPE_SETTINGS']['TABLE_NAME'] != $tableName) continue;
				$bSelected = ($propId == $arProp['ID']);
				$arResult['msg'] .= '<option value="'.$arProp['ID'].($bSelected?'" selected="selected':'').'">'.$arProp['CODE'].' :: '.$arProp['NAME'].'</option>';
			}
			$arResult['msg'] .= '</select></td></tr>';
		}
		$arResult['msg'] .= '<tr class="colorEnd"><td></td><td></td></tr>';
		$arResult['success'] = true;
	break;
	default:
	break;
}
$isNeedEnc = (($curEnc = Tools::getLogicalEncoding()) != 'utf-8');
if ($isNeedEnc) {
	Tools::encodeArray($arResult, $curEnc, 'utf-8');
}
echo json_encode($arResult);
die();
