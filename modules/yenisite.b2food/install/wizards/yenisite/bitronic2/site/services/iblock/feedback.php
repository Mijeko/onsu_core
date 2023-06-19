<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

CModule::IncludeModule("iblock");
$tempDir = $_SERVER["DOCUMENT_ROOT"] . BX_PERSONAL_ROOT . "/templates/" . WIZARD_TEMPLATE_ID;

function SetIBlockAdminListDisplaySettings($IBlockID, $arIBlockListAdminColumns, $orderByColumnName, $orderDirection, $pageSize, $isToAllUsers = TRUE) {
	$IBlockType = CIBlock::GetArrayByID($IBlockID, 'IBLOCK_TYPE_ID');
	if (FALSE == $IBlockType) {
		return FALSE;
	}

	$arPropertyCode = array();
	$obProperties = CIBlockProperty::GetList(array("sort" => "asc"), array("IBLOCK_ID" => $IBlockID));
	while ($arProp = $obProperties->GetNext(true, false)) {
		$arPropertyCode[$arProp['CODE']] = $arProp['ID'];
	}

	$arColumnList = array();
	foreach ($arIBlockListAdminColumns as $columnCode) {
		if (TRUE == array_key_exists($columnCode, $arPropertyCode)) {
			$arColumnList[] = 'PROPERTY_' . $arPropertyCode[$columnCode];
		} else {
			$arColumnList[] = $columnCode;
		}
	}
	$columnSettings = implode(',', $arColumnList);

	$arOptions[] = array(
		'c' => 'list',
		'n' => "tbl_iblock_list_" . md5($IBlockType . "." . $IBlockID),
		'v' => array(
			'columns' => strtoupper($columnSettings),
			'by' => strtoupper($orderByColumnName),
			'order' => strtoupper($orderDirection),
			'page_size' => $pageSize
		),
	);
	if (TRUE == $isToAllUsers) {
		$arOptions[0]['d'] = 'Y';
	}

	CUserOptions::SetOptionsFromArray($arOptions);
}

/**
 * Config edit form
 *
 * @param integer $IBlockID � IBlock ID
 * @param string $arIBlockEditAdminFields � CODEs for IBlock's properties and fields
 * @param boolean $isToAllUsers - for all users or not
 * @return boolean
 */
function SetIBlockAdminEditSettings($IBlockID, $arIBlockEditAdminFields, $isToAllUsers = TRUE) {

	$arFields = CIBlock::GetFields($IBlockID);

	//Get info about IBlock
	$res = CIBlock::GetByID($IBlockID);
	$IBlockInfo = $res->GetNext();
	$IBlockType = $IBlockInfo['IBLOCK_TYPE_ID'];

	if ($IBlockType == FALSE) {
		return FALSE;
	}

	$arPropertyCode = array();
	$obProperties = CIBlockProperty::GetList(array("sort" => "asc"), array("IBLOCK_ID" => $IBlockID));
	while ($arProp = $obProperties->GetNext(true, false)) {
		$arPropertyCode[$arProp['CODE']] = array(
			'ID' => $arProp['ID'],
			'NAME' => $arProp['NAME'],
			'IS_REQUIRED' => $arProp['IS_REQUIRED'],
		);
	}

	$arColumnList2 = array();
	foreach ($arIBlockEditAdminFields as $tab) {
		$fields = array();
		foreach ($tab as $key => $val) {
			$arColumnList1 = array();

			if (strpos($key, 'edit') !== FALSE) {
				$arColumnList1[0] = $key;
				if (preg_match("/^#(\w*)#/", $val, $match)) {
					if ($IBlockInfo[$match[1]] === NULL)
						$arColumnList1[1] = GetMessage('ELEMENT_NAME');
					else
						$arColumnList1[1] = $IBlockInfo[$match[1]];
				} else
					$arColumnList1[1] = $val;
			} elseif ($key == "IBLOCK_ELEMENT_PROP_VALUE" && preg_match("/^--\w*/", $val)) {
				$arColumnList1[0] = $key;
				$arColumnList1[1] = $val;
			} else {
				$_val = "";
				//                if ($val === "")
				//                {
				if (array_key_exists($key, $arPropertyCode) == TRUE) {
					if ($val == "")
						$name = $arPropertyCode[$key]['NAME'];
					else
						$name = $val;

					$name = ($arPropertyCode[$key]['IS_REQUIRED'] == 'Y') ? "*" . $name : $name;
					$_val = $name;

					$arColumnList1[0] = "PROPERTY_" . $arPropertyCode[$key]['ID'];
				} elseif (array_key_exists($key, $arPropertyCode) == FALSE && array_key_exists($key, $arFields) == TRUE) {
					if ($val == "")
						$name = $arFields[$key]['NAME'];
					else
						$name = $val;

					$name = ($arFields[$key]['IS_REQUIRED'] == 'Y') ? "*" . $name : $name;
					$_val = $name;

					$arColumnList1[0] = $key;
				}
				//                }

				if ($_val !== "")
					$arColumnList1[1] = $_val;

			}

			if ($arColumnList1[1] != "")
				$fields[] = implode('--#--', $arColumnList1);
		}
		$formTab[] = implode('--,--', $fields);
	}
	$arColumnList2 = implode('--;--', $formTab) . "--;--";
	$columnSettings = $arColumnList2;
	//    AddMessage2Log($columnSettings);
	$arOptions[] = array(
		'c' => 'form',
		'n' => "form_element_" . $IBlockID,
		'v' => array(
			'tabs' => $columnSettings,
		),
	);

	if (TRUE == $isToAllUsers) {
		$arOptions[0]['d'] = 'Y';
	}

	CUserOptions::SetOptionsFromArray($arOptions);
}

include WIZARD_ABSOLUTE_PATH . '/include/moduleCode.php'; //@var $moduleCode

$FILES = Array(
	"callme" => WIZARD_SITE_PATH . 'include_areas/footer/callme.php',
	"feedback" => WIZARD_SITE_PATH . 'include_areas/footer/feedback.php',
	"element_exist" => WIZARD_SITE_PATH . 'include_areas/footer/modal_subscribe.php',
	"element_contact" => WIZARD_SITE_PATH . 'include_areas/footer/modal_contact.php',
	"found_cheap" => WIZARD_SITE_PATH . 'include_areas/catalog/modal_price_cry.php',
	"price_lower" => WIZARD_SITE_PATH . 'include_areas/catalog/modal_price_drops.php',
);

$EVENTS = Array(
	"CALLME" => '#DEFAULT_EMAIL_FROM#',
	"FEEDBACK" => '#DEFAULT_EMAIL_FROM#',
	'FOUND_CHEAP' => '#DEFAULT_EMAIL_FROM#',
	'PRICE_LOWER' => '#EMAIL#',
	'ELEMENT_EXIST' => '#EMAIL#',
	'ELEMENT_CONTACT' => '#DEFAULT_EMAIL_FROM#'
);

$FIELDS['callme'] = Array(
	array(
		"NAME" => GetMessage("NAME"),
		"ACTIVE" => "Y",
		"SORT" => "100",
		"CODE" => "NAME",
		"PROPERTY_TYPE" => "S",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("PHONE"),
		"ACTIVE" => "Y",
		"SORT" => "110",
		"CODE" => "PHONE",
		"PROPERTY_TYPE" => "S",
		"IS_REQUIRED" => "Y",
	),
);

$FIELDS['feedback'] = array(
	array(
		"NAME" => GetMessage("YOUR_MESSAGE"),
		"ACTIVE" => "Y",
		"SORT" => "100",
		"CODE" => "text",
		"PROPERTY_TYPE" => "S",
		"USER_TYPE" => "HTML",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("ORDER_NUMBER"),
		"ACTIVE" => "Y",
		"SORT" => "200",
		"CODE" => "ORDER_NUMBER",
		"PROPERTY_TYPE" => "S",
		"IS_REQUIRED" => "N",
	),
	array(
		"NAME" => GetMessage("YOUR_NAME"),
		"ACTIVE" => "Y",
		"SORT" => "300",
		"CODE" => "NAME",
		"PROPERTY_TYPE" => "S",
		"IS_REQUIRED" => "N",
	),
	array(
		"NAME" => GetMessage("EMAIL"),
		"ACTIVE" => "Y",
		"SORT" => "400",
		"CODE" => "EMAIL",
		"PROPERTY_TYPE" => "S",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("PHONE"),
		"ACTIVE" => "Y",
		"SORT" => "500",
		"CODE" => "PHONE",
		"PROPERTY_TYPE" => "S",
		"IS_REQUIRED" => "N",
	),

);
$FIELDS['found_cheap'] = array(
	array(
		"NAME" => GetMessage("PRODUCT"),
		"ACTIVE" => "Y",
		"SORT" => "100",
		"CODE" => "PRODUCT",
		"PROPERTY_TYPE" => "E",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("EMAIL"),
		"ACTIVE" => "Y",
		"SORT" => "200",
		"CODE" => "EMAIL",
		"PROPERTY_TYPE" => "S",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("PRICE_ON_DATE"),
		"ACTIVE" => "Y",
		"SORT" => "300",
		"CODE" => "PRICE",
		"PROPERTY_TYPE" => "N",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("PRICE_TYPE_ID"),
		"ACTIVE" => "Y",
		"SORT" => "330",
		"CODE" => "PRICE_TYPE_ID",
		"PROPERTY_TYPE" => "N",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("PRICE_OTHER"),
		"ACTIVE" => "Y",
		"SORT" => "350",
		"CODE" => "PRICE_OTHER",
		"PROPERTY_TYPE" => "N",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("URL"),
		"ACTIVE" => "Y",
		"SORT" => "400",
		"CODE" => "URL",
		"PROPERTY_TYPE" => "S",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("FIO"),
		"ACTIVE" => "Y",
		"SORT" => "500",
		"CODE" => "FIO",
		"PROPERTY_TYPE" => "S",
		"IS_REQUIRED" => "N",
	),
	array(
		"NAME" => GetMessage("PHONE"),
		"ACTIVE" => "Y",
		"SORT" => "600",
		"CODE" => "PHONE",
		"PROPERTY_TYPE" => "S",
		"IS_REQUIRED" => "N",
	),
);
$FIELDS['price_lower'] = array(
	array(
		"NAME" => GetMessage("PRODUCT"),
		"ACTIVE" => "Y",
		"SORT" => "100",
		"CODE" => "PRODUCT",
		"PROPERTY_TYPE" => "E",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("EMAIL"),
		"ACTIVE" => "Y",
		"SORT" => "200",
		"CODE" => "EMAIL",
		"PROPERTY_TYPE" => "S",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("PRICE_DESIRED"),
		"ACTIVE" => "Y",
		"SORT" => "300",
		"CODE" => "PRICE",
		"PROPERTY_TYPE" => "N",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("PRICE_TYPE_ID"),
		"ACTIVE" => "Y",
		"SORT" => "400",
		"CODE" => "PRICE_TYPE_ID",
		"PROPERTY_TYPE" => "N",
		"IS_REQUIRED" => "Y",
	),

);
$FIELDS['element_exist'] = array(
	array(
		"NAME" => GetMessage("PRODUCT"),
		"ACTIVE" => "Y",
		"SORT" => "100",
		"CODE" => "PRODUCT",
		"PROPERTY_TYPE" => "E",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("EMAIL"),
		"ACTIVE" => "Y",
		"SORT" => "200",
		"CODE" => "EMAIL",
		"PROPERTY_TYPE" => "S",
		"IS_REQUIRED" => "Y",
	),
);
$FIELDS['element_contact'] = array(
	array(
		"NAME" => GetMessage("PRODUCT"),
		"ACTIVE" => "Y",
		"SORT" => "10",
		"CODE" => "PRODUCT",
		"PROPERTY_TYPE" => "E",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("YOUR_NAME"),
		"ACTIVE" => "Y",
		"SORT" => "30",
		"CODE" => "NAME",
		"PROPERTY_TYPE" => "S",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("EMAIL"),
		"ACTIVE" => "Y",
		"SORT" => "40",
		"CODE" => "EMAIL",
		"PROPERTY_TYPE" => "S",
		"IS_REQUIRED" => "N",
	),
	array(
		"NAME" => GetMessage("PHONE"),
		"ACTIVE" => "Y",
		"SORT" => "50",
		"CODE" => "PHONE",
		"PROPERTY_TYPE" => "S",
		"IS_REQUIRED" => "Y",
	),
	array(
		"NAME" => GetMessage("QUANTITY"),
		"ACTIVE" => "Y",
		"SORT" => "60",
		"CODE" => "QUANTITY",
		"PROPERTY_TYPE" => "N",
		"IS_REQUIRED" => "N",
	),
	array(
		"NAME" => GetMessage("COMMENT"),
		"ACTIVE" => "Y",
		"SORT" => "70",
		"CODE" => "COMMENT",
		"PROPERTY_TYPE" => "S",
		"USER_TYPE" => "HTML",
		"IS_REQUIRED" => "N",
	)
);
//-------------------------------
//IBlock Type
$res_tib = strtolower($moduleCode) . "_feedback";

CWizardUtil::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "include_areas/", array("FEEDBACK_IBLOCK_TYPE" => $res_tib));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . 'personal/index.php', array("FEEDBACK_IBLOCK_TYPE" => $res_tib));

//Check IBlocks exist
foreach ($EVENTS as $EVENT_NAME => $EMAIL) {
	$code = strtolower($EVENT_NAME) . '_' . WIZARD_SITE_ID;

	$test_ib = CIBlock::GetList(Array(), Array('TYPE' => $res_tib, 'CODE' => $code . "%"), false);

	$iblock_codes[$code] = TRUE;

	while ($ar_res = $test_ib->Fetch()) {
		$res_ib = $ar_res['ID'];
		$iblock_codes[$code] = FALSE;
	}

	//Create IBlock
	if ($iblock_codes[$code] === TRUE) {
		$ib = new CIBlock;
		$arFields = Array(
			"ACTIVE" => "Y",
			"NAME" => GetMessage($EVENT_NAME) . " (" . WIZARD_SITE_ID . ")",
			"CODE" => $code,
			"LIST_PAGE_URL" => "",
			"DETAIL_PAGE_URL" => "",
			"IBLOCK_TYPE_ID" => $res_tib,
			"INDEX_ELEMENT" => "Y",
			"SITE_ID" => Array(WIZARD_SITE_ID),
			"SORT" => "500",
			"PICTURE" => "",
			"DESCRIPTION" => "",
			"DESCRIPTION_TYPE" => "text",
			"GROUP_ID" => Array("2" => "R"),
		);

		//var_dump($arFields);
		$res_ib = $ib->Add($arFields);

		//        AddMessage2Log($res_ib);

		if (is_numeric($res_ib)) {
			$ibp = new CIBlockProperty;
			$_fields = array();
			$_fields['edit1'] = '#ELEMENT_NAME#';
			$_fields['ACTIVE'] = '';
			$_fields['NAME'] = '';

			foreach ($FIELDS[strtolower($EVENT_NAME)] as $field) {
				$field['IBLOCK_ID'] = $res_ib;
				$ibp->Add($field);
				$_fields[$field['CODE']] = "";
			}
			//            AddMessage2Log(print_r($_fields2, true));

			$arFieldsIP = Array(
				"NAME" => GetMessage("IP"),
				"ACTIVE" => "Y",
				"SORT" => "115",
				"CODE" => "IP",
				"PROPERTY_TYPE" => "S",
				"IBLOCK_ID" => $res_ib,
				"IS_REQUIRED" => "N",
			);

			$ibp->Add($arFieldsIP);
			$_fields['IP'] = "";
			$_fields2 = array($_fields);
			SetIBlockAdminEditSettings($res_ib, $_fields2);

			switch (strtolower($EVENT_NAME)) {
				case 'callme':
					$print_fields = array('ID', 'NAME', 'PHONE', 'IP');
					break;
				case 'feedback':
					$print_fields = array('ID', 'NAME', 'EMAIL', 'ORDER_NUMBER');
					break;
				case 'found_cheap':
					$print_fields = array('PRODUCT','EMAIL','PRICE','PRICE_TYPE_ID');
					break;
				case 'price_lower':
					$print_fields = array('PRODUCT','EMAIL','PRICE','PRICE_TYPE_ID');
					break;
				case 'element_exist':
					$print_fields = array('EMAIL', 'PRODUCT');
					break;
				case 'element_contact':
					$print_fields = array('PRODUCT', 'NAME', 'EMAIL', 'PHONE', 'QUANTITY', 'COMMENT');
					break;
			}
			$print_fields[] = 'timestamp_x';
			$print_fields[] = 'ACTIVE';
			SetIBlockAdminListDisplaySettings($res_ib, $print_fields, 'ID', 'DESC', 20, TRUE);
		}
	}

	CWizardUtil::ReplaceMacros($FILES[strtolower($EVENT_NAME)], Array("IBLOCK_ID" => $res_ib));
	CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . 'personal/index.php', array('FEEDBACK_' . strtoupper($EVENT_NAME) . '_IBLOCK_ID' => $res_ib));
}

//additional event types do not need their own iblocks
$EVENTS['ELEMENT_EXIST_ADMIN'] = '#DEFAULT_EMAIL_FROM#';
$EVENTS['PRICE_LOWER_ADMIN']   = '#DEFAULT_EMAIL_FROM#';

//Create (update) Event Types
foreach ($EVENTS as $EVENT_NAME => $EMAIL) {
	foreach (array('en', 'ru') as $LID) {
		$arET = CEventType::GetByID($EVENT_NAME, $LID)->Fetch();
		if ($arET === false) {
			CEventType::Add(array(
				"LID" => $LID,
				"EVENT_NAME" => $EVENT_NAME,
				"NAME" => GetMessage($EVENT_NAME),
				"DESCRIPTION" => GetMessage($EVENT_NAME . "_DESC"),
			));
		} else {
			CEventType::Update(
				$arET,
				array('DESCRIPTION' => GetMessage($EVENT_NAME . '_DESC'))
			);
		}
	}
}


//Create Messages
foreach ($EVENTS as $EVENT_NAME => $EMAIL) {
	//Check exist EventMessage
	//If not exist - create new EventMessage
	$arFilter = array("TYPE_ID" => $EVENT_NAME, "SITE_ID" => WIZARD_SITE_ID);
	$rsMess = CEventMessage::GetList($by, $order, $arFilter);
	$mess = $rsMess->Fetch();

	if (!$mess) {
		$arr = Array(
			"ACTIVE" => "Y",
			"EVENT_NAME" => $EVENT_NAME,
			"LID" => Array(WIZARD_SITE_ID),
			"EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
			"EMAIL_TO" => $EMAIL,
			"BCC" => "",
			"SUBJECT" => GetMessage($EVENT_NAME . "_SUBJECT"),
			"BODY_TYPE" => "html",
			"MESSAGE" => GetMessage($EVENT_NAME . "_TEXT"),
		);

		$emess = new CEventMessage;
		$res_etsh = $emess->Add($arr);
	}
	//    AddMessage2Log(print_r($res_etsh, true));
}
?>