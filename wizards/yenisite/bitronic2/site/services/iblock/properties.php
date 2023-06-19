<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule("iblock")) return;

$bCatalog = CModule::IncludeModule("catalog");

//@var $moduleId
//@var $moduleCode
//@var $brandsCount
include WIZARD_ABSOLUTE_PATH.'/include/moduleCode.php';

if (!CModule::IncludeModule($moduleId)) die('module is not installed');

$bPro = CRZBitronic2Settings::isPro();

if (CModule::IncludeModule('yenisite.core')){
    \Yenisite\Core\Userprops\Main::loadUserProp('DISCOUNT_CLASS');
    if ($bPro){
        \Yenisite\Core\Userprops\Main::loadUserProp('GEO_STORE_CLASS');
    }
}

$wizard = &$this->GetWizard();
$install_type = $wizard->GetVar("install_type");

$sliderCode = "{$moduleCode}_banner";
$sliderType = "services";
$iblockCode = "{$moduleCode}_catalog";
$iblockType = "catalog";
$offersCode = "{$moduleCode}_offers";
$offersType = "offers";
$newsCode = "{$moduleCode}_news";
$newsType = "news";
$reviewsCode = "{$moduleCode}_reviews";
$reviewType = "news";
$feedbackType = strtolower($moduleCode) . "_feedback";
$cheapCode = "found_cheap_" . WIZARD_SITE_ID;
$lowerCode = 'price_lower_' . WIZARD_SITE_ID;
$iblockServicesCode = "{$moduleCode}_services";
$iblockServicesType = "references";

$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockServicesCode, "TYPE" => $iblockServicesType));
$IBLOCK_SERVICES_ID = false;
if ($arIBlock = $rsIBlock->Fetch())
{
    $IBLOCK_SERVICES_ID = $arIBlock["ID"];
}

if ($_SESSION["WIZARD_ACTIONS_IBLOCK_ID"])
{
	$IBLOCK_ACTIONS_ID = $_SESSION["WIZARD_ACTIONS_IBLOCK_ID"];
	unset($_SESSION["WIZARD_ACTIONS_IBLOCK_ID"]);
}

$arProps = array(
	'BRANDS_REF' => array(
		'TYPE' => 'S',
		'LIST_TYPE' => 'L',
		'USER_TYPE' => 'directory',
		'USER_TYPE_SETTINGS' => array(
			'size' => 1,
			'width' => 0,
			'group' => 'N',
			'multiple' => 'N',
			'TABLE_NAME' => 'rz_'.strtolower($moduleCode).'_brand_reference'
		),
		'SMART_FILTER' => 'Y',
		'DISPLAY_TYPE' => 'F'
	),
	'BLOG_POST_ID' => array(
		'TYPE' => 'N'
	),
	'BLOG_COMMENTS_CNT' => array(
		'TYPE' => 'N'
	),
	'MANUAL' => array(
		'MULTIPLE' => 'Y',
		'TYPE' => 'F',
		'SORT' => '1000',
	),
	'RECOMMEND' => array(
		'MULTIPLE' => 'Y',
		'TYPE' => 'E',
		'LIST_TYPE' => 'L',
		'MULTIPLE_CNT' => '5',
		'SORT' => '1000',
	),
	'TURBO_YANDEX_LINK' => array(
		'MULTIPLE' => 'N',
		'TYPE' => 'S',
		'SORT' => '12000',
	),
	'VIDEO' => array(
		'MULTIPLE' => 'Y',
		'TYPE' => 'S',
		'SORT' => '1000',
	),
    'VIDEO_IN_SLIDER' => array(
        'MULTIPLE' => 'Y',
        'TYPE' => 'S',
        'SORT' => '500',
    ),
	'RZ_CREDIT' => array(
		'TYPE' => 'S',
		'HINT' => GetMessage('RZ_CREDIT-HINT')
	),
	'RZ_DELIVERY' => array(
		'TYPE' => 'S',
		'HINT' => GetMessage('RZ_DELIVERY-HINT')
	),
	'RZ_GUARANTEE' => array(
		'TYPE' => 'S',
		'WITH_DESCRIPTION' => 'Y',
		'HINT' => GetMessage('RZ_GUARANTEE-HINT')
	),
	'RZ_CREDIT_HINT' => array(
		'TYPE' => 'S'
	),
	'RZ_DELIVERY_HINT' => array(
		'TYPE' => 'S'
	),
	'RZ_GUARANTEE_HINT' => array(
		'TYPE' => 'S'
	),
	'RZ_FOR_ORDER_TEXT' => array(
		'TYPE' => 'S'
	),
	iRZProp::STICKERS => array(
		'NAME' => GetMessage('RZ_CUSTOM_STICKERS'),
		'TYPE' => 'L',
		'MULTIPLE' => 'Y',
		'SMART_FILTER' => 'Y',
		'VALUES' => array(
			array('XML_ID' => 'bg-danger flaticon-trash29', 'VALUE' => GetMessage('STICKER_STOP_PRODUCTION')),
			array('XML_ID' => 'bg-success flaticon-43-2',   'VALUE' => GetMessage('STICKER_SOON')),
			array('XML_ID' => 'bg-info flaticon-23',        'VALUE' => GetMessage('STICKER_EXCLUSIVE')),
		)
	),
	 iRZProp::VIP => array(
		'NAME' => GetMessage('RZ_VIP'),
		'TYPE' => 'L',
		'LIST_TYPE' => 'C',
		'SECTION_PROPERTY' => 'Y',
		'SMART_FILTER' => 'N',
		'VALUES' => array(array('VALUE' => 'Y'))
	),
	'ID_3D_MODEL' => array(
		'TYPE' => 'S'
	)
);

if ($IBLOCK_SERVICES_ID) {
	$arProps['SERVICE'] = array(
		'MULTIPLE' => 'Y',
		'TYPE' => 'E',
		'LINK_IBLOCK_ID' => $IBLOCK_SERVICES_ID,
		'LIST_TYPE' => 'L',
		'MULTIPLE_CNT' => '5',
		'SORT' => '1000',
	);
}

$arOfferProps = array(
	'ARTICLE' => array(
		'TYPE' => 'S',
	),
    'VIDEO_IN_SLIDER' => array(
        'MULTIPLE' => 'Y',
        'TYPE' => 'S',
        'SORT' => '500',
    ),
);

$arCheapProps = array(
	'PRICE_TYPE_ID' => array(
		"NAME" => GetMessage("PRICE_TYPE_ID"),
		"ACTIVE" => "Y",
		"SORT" => "330",
		"TYPE" => "N",
		"IS_REQUIRED" => "Y",
	),
	"FIO" => array(
		"NAME" => GetMessage("FIO"),
		"ACTIVE" => "Y",
		"SORT" => "500",
		"TYPE" => "S",
		"IS_REQUIRED" => "N",
	),
);

$arReviewsProps = array(
	'RZ_SHOW_ON_MAIN' => array(
		"NAME" => GetMessage("RZ_SHOW_ON_MAIN"),
        'ACTIVE' => 'Y',
        'TYPE' => 'L',
        'LIST_TYPE' => 'C',
        'SORT' => '500',
        'VALUES' => array(
            array('VALUE' => GetMessage('YES'))
        )
	)
);

if ($bPro){
    $arReviewsProps['RZ_GEO_STORE_ITEM'] = array(
        'NAME' => GetMessage('RZ_GEO_STORE_ITEM'),
        'ACTIVE' => 'Y',
        'SMART_FILTER' => 'N',
        'MULTIPLE' => 'N',
        'TYPE' => 'geo_store_element',
        'USER_TYPE' => 'geo_store_element',
        'SORT' => '500',
    );
    $arActionsProps['RZ_GEO_STORE_ITEM'] = array(
        'NAME' => GetMessage('RZ_GEO_STORE_ITEM'),
        'ACTIVE' => 'Y',
        'SMART_FILTER' => 'N',
        'MULTIPLE' => 'N',
        'TYPE' => 'geo_store_element',
        'USER_TYPE' => 'geo_store_element',
        'SORT' => '500',
    );

    $arNewsProps['RZ_GEO_STORE_ITEM'] = array(
        'NAME' => GetMessage('RZ_GEO_STORE_ITEM'),
        'ACTIVE' => 'Y',
        'SMART_FILTER' => 'N',
        'MULTIPLE' => 'N',
        'TYPE' => 'geo_store_element',
        'USER_TYPE' => 'geo_store_element',
        'SORT' => '500',
    );
}

$arCheapProps_update = array(
	'PRICE' => array(
		"NAME" => GetMessage("PRICE_ON_DATE"),
		"TYPE" => "N",
		"IS_REQUIRED" => "Y",
	)
);

$arLowerProps_update = array(
	'PRICE' => array(
		"NAME" => GetMessage("PRICE_DESIRED"),
		"TYPE" => "N",
		"IS_REQUIRED" => "Y",
	)
);

/**
 * Update fields of required property
 *
 * @param int $iblock_id - id of iblock where required property should present
 * @param string $code - Required property symbolic code
 * @param array $param - arFields for required property to update with CIBlockProperty
 *
 * @return bool
 */
function RZ_UpdateProperty($iblock_id, $code, $param)
{
	$res = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $iblock_id, "CODE" => $code));

	if (!$ar_res = $res->GetNext()) return false;

	$param["PROPERTY_TYPE"] = in_array($param["TYPE"], array("S", "N", "L", "F", "G", "E")) ? $param["TYPE"] : "S";
	unset($param['TYPE']);

	if (strlen($param["NAME"]) <= 0) {
		$param["NAME"] = GetMessage($code) ?: $code;
	}
	$param['CODE'] = $code;
	$param['IBLOCK_ID'] = $iblock_id;

	$ibp = new CIBlockProperty;

	if (!$ibp->Update($ar_res['ID'], $param)) {
		echo GetMessage('RZ_IBPROP_UPDATE_ERROR', array(
			'#ERROR_TEXT#' => $ibp->LAST_ERROR,
			'#IBLOCK_ID#' => $iblock_id,
			'#PROP_CODE#' => $code
		));
		return false;
	}
	return true;
}

/**
 * Check required property and create if needed
 *
 * @param int $iblock_id - id of iblock where required property should present
 * @param string $code - Required property symbolic code
 * @param array $param - arFields for required property to create with CIBlockProperty
 *
 * @return int|bool
 * - id of created property
 * - TRUE if already exists
 * - FALSE if creation failed
 */
function RZ_CheckProperty($iblock_id, $code, $param)
{
	$res = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $iblock_id, "CODE" => $code));

	if ($ar_res = $res->GetNext()) return true;

	$param["MULTIPLE"] = in_array($param["MULTIPLE"], array("Y", "N")) ? $param["MULTIPLE"] : "N";
	$param['SMART_FILTER'] = in_array($param['SMART_FILTER'], array('Y', 'N')) ? $param['SMART_FILTER'] : 'N';
	$param["PROPERTY_TYPE"] = in_array($param["TYPE"], array("S", "N", "L", "F", "G", "E")) ? $param["TYPE"] : "S";
	unset($param['TYPE']);

	if (strlen($param["NAME"]) <= 0) {
		$param["NAME"] = GetMessage($code) ?: $code;
	}
	$param["SORT"] = (intval($param["SORT"]) > 0) ? $param["SORT"] : '20000';
	$param['CODE'] = $code;
	$param['ACTIVE'] = 'Y';
	$param['IBLOCK_ID'] = $iblock_id;

	if (isset($param['VALUES'])) {
		$arValues = $param['VALUES'];
		unset($param['VALUES']);
	}

	$ibp = new CIBlockProperty;
	$PropID = $ibp->Add($param);

	if (intval($PropID) <= 0) {
		return false;
	}

	if ($param['PROPERTY_TYPE'] == 'L' && is_array($arValues)) {
		foreach ($arValues as $arEnumFields) {
			$arEnumFields['PROPERTY_ID'] = $PropID;
			CIBlockPropertyEnum::Add($arEnumFields);
		}
	}

	return $PropID;
}

function prop2SmartFilter($iblock_id, $code, array $param = array())
{
	static $arPropLinks = array();
	if (!array_key_exists($iblock_id, $arPropLinks)) {
        if(CIBlock::GetArrayByID($iblock_id, "SECTION_PROPERTY") !== "Y") {
            $ib = new CIBlock;
            $ib->Update($iblock_id, array("SECTION_PROPERTY" => "Y"));
        }
		$arPropLinks[$iblock_id] = CIBlockSectionPropertyLink::GetArray($iblock_id);
	}
	$res = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$iblock_id, "CODE"=>$code));
	while ($arProp = $res->GetNext()) {
		$PropID = $arProp['ID'];
		if (array_key_exists($PropID, $arPropLinks[$iblock_id]))
		if ($arPropLinks[$iblock_id][$PropID]['SMART_FILTER'] === 'Y') continue;

		$arFields = Array(
			"IBLOCK_ID" => $iblock_id,
			"SMART_FILTER" => "Y",
			"DISPLAY_EXPANDED" => ( isset($param['DISPLAY_EXPANDED']) ? $param['DISPLAY_EXPANDED'] : "Y")
		);
		if (isset($param['DISPLAY_TYPE'])) {
			$arFields['DISPLAY_TYPE'] = $param['DISPLAY_TYPE'];
		}
		$ibp = new CIBlockProperty;
		$ibp->Update($PropID, $arFields);
	}
}

// ========== GET IBLOCK ID ==========

$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockCode, "TYPE" => $iblockType));
$IBLOCK_CATALOG_ID = false;
if ($arIBlock = $rsIBlock->Fetch())
{
	$IBLOCK_CATALOG_ID = $arIBlock["ID"];
}

$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $offersCode, "TYPE" => $offersType));
$IBLOCK_OFFERS_ID = false;
if ($arIBlock = $rsIBlock->Fetch())
{
	$IBLOCK_OFFERS_ID = $arIBlock['ID'];
}

$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $newsCode, "TYPE" => $newsType));
$IBLOCK_NEWS_ID = false;
if ($arIBlock = $rsIBlock->Fetch())
{
	$IBLOCK_NEWS_ID = $arIBlock["ID"];
}
$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $reviewsCode, "TYPE" => $reviewType));
$IBLOCK_REVIEWS_ID = false;
if ($arIBlock = $rsIBlock->Fetch())
{
    $IBLOCK_REVIEWS_ID = $arIBlock["ID"];
}

$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $sliderCode, "TYPE" => $sliderType));
$IBLOCK_BANNER_ID = false;
if ($arIBlock = $rsIBlock->Fetch()) {
	$IBLOCK_BANNER_ID = $arIBlock["ID"];
}

$rsIBlock = CIBlock::GetList(array(), array("CODE" => $cheapCode, "TYPE" => $feedbackType));
$IBLOCK_CHEAP_ID = false;
if ($arIBlock = $rsIBlock->Fetch()) {
	$IBLOCK_CHEAP_ID = $arIBlock["ID"];
}

$rsIBlock = CIBlock::GetList(array(), array("CODE" => $lowerCode, "TYPE" => $feedbackType));
$IBLOCK_LOWER_ID = false;
if ($arIBlock = $rsIBlock->Fetch()) {
	$IBLOCK_LOWER_ID = $arIBlock["ID"];
}
unset($arIBlock, $rsIBlock);

$rsIBlock = CIBlock::GetList(array(), array("CODE" => $lowerCode, "TYPE" => $feedbackType));
$IBLOCK_LOWER_ID = false;
if ($arIBlock = $rsIBlock->Fetch()) {
	$IBLOCK_LOWER_ID = $arIBlock["ID"];
}
unset($arIBlock, $rsIBlock);



// ========== CREATE NEW PROPERTIES ==========
if($IBLOCK_CATALOG_ID !== false)
{
	if(CIBlock::GetArrayByID($IBLOCK_CATALOG_ID, "SECTION_PROPERTY") !== "Y") {
		$ib = new CIBlock;
		$ib->Update($IBLOCK_CATALOG_ID, array("SECTION_PROPERTY" => "Y"));
		unset($ib);
	}
	$arProps['RECOMMEND']['LINK_IBLOCK_TYPE_ID'] = $iblockType;
	$arProps['RECOMMEND']['LINK_IBLOCK_ID'] = $IBLOCK_CATALOG_ID;
	if (!$bCatalog) {
		unset($arProps['RECOMMEND']);
	}
	foreach($arProps as $code => $params)
	{
		RZ_CheckProperty($IBLOCK_CATALOG_ID, $code, $params);
	}

    $arProps['RECOMMEND']['LINK_IBLOCK_TYPE_ID'] = $reviewType;
    $arProps['RECOMMEND']['LINK_IBLOCK_ID'] = $IBLOCK_REVIEWS_ID;

    $res = RZ_CheckProperty($IBLOCK_CATALOG_ID, 'RELATED_REVIEWS', $arProps['RECOMMEND']);

    $arProps['RECOMMEND']['LINK_IBLOCK_TYPE_ID'] = $iblockType;
    $arProps['RECOMMEND']['LINK_IBLOCK_ID'] = $IBLOCK_CATALOG_ID;

	if($install_type == 'update') {
		prop2SmartFilter($IBLOCK_CATALOG_ID, 'RZ_AVAILABLE');
		$arMacros = Array("CATALOG_IBLOCK_ID" => $IBLOCK_CATALOG_ID);
		WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH.'catalog/', $arMacros);
		WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH.'include_areas/', $arMacros);
		WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."/pricelist/", $arMacros);
	}
}
if($IBLOCK_OFFERS_ID !== false)
{
	foreach ($arOfferProps as $code => $arParam) {
		RZ_CheckProperty($IBLOCK_OFFERS_ID, $code, $arParam);
	}
}
if($IBLOCK_CATALOG_ID !== false && $IBLOCK_NEWS_ID !== false && $bCatalog)
{
	$res = RZ_CheckProperty($IBLOCK_NEWS_ID, 'RELATED_ITEMS', $arProps['RECOMMEND']);
	if (is_numeric($res)) {
		$arForm = CUserOptions::GetOption("form", "form_element_".$IBLOCK_NEWS_ID);
		$arForm['tabs'] = substr($arForm['tabs'], 0, -3);
		$arForm['tabs'] .= ',--PROPERTY_' . $res . '--#--' . GetMessage('RELATED_ITEMS') . '--;--';
		CUserOptions::SetOption("form", "form_element_".$IBLOCK_NEWS_ID, $arForm);
	}
}
if($IBLOCK_CATALOG_ID !== false && $IBLOCK_REVIEWS_ID !== false && $bCatalog)
{
    $res = RZ_CheckProperty($IBLOCK_REVIEWS_ID, 'RELATED_ITEMS', $arProps['RECOMMEND']);
    if (is_numeric($res)) {
        $arForm = CUserOptions::GetOption("form", "form_element_".$IBLOCK_REVIEWS_ID);
        $arForm['tabs'] = substr($arForm['tabs'], 0, -3);
        $arForm['tabs'] .= ',--PROPERTY_' . $res . '--#--' . GetMessage('RELATED_ITEMS') . '--;--';
        CUserOptions::SetOption("form", "form_element_".$IBLOCK_REVIEWS_ID, $arForm);
    }
}
if ($IBLOCK_BANNER_ID !== false) {
	if($install_type == 'update') {
		$arMacros = Array("BANNERS_IBLOCK_ID" => $IBLOCK_BANNER_ID);
		WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH.'include_areas/', $arMacros);
	}
}
if (!empty($IBLOCK_ACTIONS_ID)){
    foreach ($arActionsProps as $code => $arParams) {
        RZ_CheckProperty($IBLOCK_ACTIONS_ID, $code, $arParams);
    }
    $arMacros = Array("ACTIONS_IBLOCK_ID" => $IBLOCK_ACTIONS_ID);
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH.'include_areas/', $arMacros);
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH.'actions/', $arMacros);
}
if (!empty($IBLOCK_REVIEWS_ID)){
    foreach ($arReviewsProps as $code => $arParams) {
        RZ_CheckProperty($IBLOCK_REVIEWS_ID, $code, $arParams);
    }
    $arMacros = Array("REVIEWS_IBLOCK_ID" => $IBLOCK_REVIEWS_ID);
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH.'catalog/', $arMacros);
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH.'include_areas/', $arMacros);
}
if (!empty($IBLOCK_NEWS_ID)){
    foreach ($arNewsProps as $code => $arParams) {
        RZ_CheckProperty($IBLOCK_NEWS_ID, $code, $arParams);
    }
}
if ($IBLOCK_CHEAP_ID !== false && $install_type == 'update') {
	foreach ($arCheapProps as $code => $arParam) {
		RZ_CheckProperty($IBLOCK_CHEAP_ID, $code, $arParam);
	}
	if (COption::GetOptionString(CRZBitronic2Settings::getModuleId(), 'update_2.20.0', 'N', WIZARD_SITE_ID) === 'Y') {
		foreach ($arCheapProps_update as $code => $arParam) {
			RZ_UpdateProperty($IBLOCK_CHEAP_ID, $code, $arParam);
		}
	}
}
if ($IBLOCK_LOWER_ID !== false && $install_type == 'update') {
	if (COption::GetOptionString(CRZBitronic2Settings::getModuleId(), 'update_2.20.0', 'N', WIZARD_SITE_ID) === 'Y') {
		foreach ($arLowerProps_update as $code => $arParam) {
			RZ_UpdateProperty($IBLOCK_LOWER_ID, $code, $arParam);
		}
	}
}

// ========== SECTION USER FIELDS ==========

do {
    if (!$IBLOCK_CATALOG_ID) break;

    $userField = 'UF_IMG_BLOCK_FOTO';

    //check existance
    $dbRes = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID" => 'IBLOCK_'.$IBLOCK_CATALOG_ID.'_SECTION', "FIELD_NAME" => $userField));
    if ($dbRes->Fetch()) break;

    //set new services user field
    $arFields = array(
        'ENTITY_ID' => 'IBLOCK_'.$IBLOCK_CATALOG_ID.'_SECTION',
        'FIELD_NAME' => $userField,
        'USER_TYPE_ID' => 'file',
        'XML_ID' => $userField,
        'SORT' => 100,
        'MULTIPLE' => 'N',
        'MANDATORY' => 'N',
        'SHOW_FILTER' => 'N',
        'SHOW_IN_LIST' => 'Y',
        'EDIT_IN_LIST' => 'Y',
        'IS_SEARCHABLE' => 'N',
        'SETTINGS' => Array
        (
            'DISPLAY' => 'LIST',
            'SIZE' => 200,
            'MAX_SHOW_SIZE' => 0,
            'MAX_ALLOWED_SIZE' => 0,
            'ACTIVE_FILTER' => 'Y',
        )
    );

    $arLanguages = Array();
    $rsLanguage = CLanguage::GetList($by, $order, array());
    while($arLanguage = $rsLanguage->Fetch())
        $arLanguages[] = $arLanguage["LID"];

    $arLabelNames = Array();
    foreach($arLanguages as $languageID)
    {
        WizardServices::IncludeServiceLang("property_names.php", $languageID);
        $arLabelNames[$languageID] = GetMessage($userField);
    }

    $arFields["EDIT_FORM_LABEL"] = $arLabelNames;
    $arFields["LIST_COLUMN_LABEL"] = $arLabelNames;
    $arFields["LIST_FILTER_LABEL"] = $arLabelNames;

    $obUTE = new CUserTypeEntity;
    $obUTE->Add($arFields);

} while (0);

do {
	if (!$IBLOCK_SERVICES_ID || !$IBLOCK_CATALOG_ID) break;

	$userField = 'UF_SERVICE';

	//check existance
	$dbRes = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID" => 'IBLOCK_'.$IBLOCK_CATALOG_ID.'_SECTION', "FIELD_NAME" => $userField));
	if ($arEntityServices = $dbRes->Fetch()){
        $obUTE = new CUserTypeEntity;
        $obUTE->Update($arEntityServices['ID'], array(
            'SETTINGS' => Array
            (
                'DISPLAY' => 'LIST',
                'LIST_HEIGHT' => 5,
                'IBLOCK_ID' => $IBLOCK_SERVICES_ID,
                'ACTIVE_FILTER' => 'Y',
            )
        ));
        break;
    }

	//set new services user field
	$arFields = array(
		'ENTITY_ID' => 'IBLOCK_'.$IBLOCK_CATALOG_ID.'_SECTION',
		'FIELD_NAME' => $userField,
		'USER_TYPE_ID' => 'iblock_element',
		'XML_ID' => $userField,
		'SORT' => 100,
		'MULTIPLE' => 'Y',
		'MANDATORY' => 'N',
		'SHOW_FILTER' => 'N',
		'SHOW_IN_LIST' => 'Y',
		'EDIT_IN_LIST' => 'Y',
		'IS_SEARCHABLE' => 'N',
		'SETTINGS' => Array
			(
				'DISPLAY' => 'LIST',
				'LIST_HEIGHT' => 5,
				'IBLOCK_ID' => $IBLOCK_SERVICES_ID,
				'ACTIVE_FILTER' => 'Y',
			)
	);

	$arLanguages = Array();
	$rsLanguage = CLanguage::GetList($by, $order, array());
	while($arLanguage = $rsLanguage->Fetch())
		$arLanguages[] = $arLanguage["LID"];

	$arLabelNames = Array();
	foreach($arLanguages as $languageID)
	{
		WizardServices::IncludeServiceLang("property_names.php", $languageID);
		$arLabelNames[$languageID] = GetMessage($userField);
	}

	$arFields["EDIT_FORM_LABEL"] = $arLabelNames;
	$arFields["LIST_COLUMN_LABEL"] = $arLabelNames;
	$arFields["LIST_FILTER_LABEL"] = $arLabelNames;

    $dbEntityServices = CUserTypeEntity::GetList(array(), array('ENTITY_ID' => 'IBLOCK_'.$IBLOCK_CATALOG_ID.'_SECTION', 'FIELD_NAME' => $userField,'XML_ID' => $userField));

    $obUTE = new CUserTypeEntity;
    $obUTE->Add($arFields);


} while (0);

// ========== FILL BRANDS ==========

if (!WIZARD_INSTALL_DEMO_DATA || $IBLOCK_CATALOG_ID == false)
	return;

$rubMonth = ' <span class="b-rub">' . GetMessage('RZ_RUB') . '</span>/' . GetMessage('RZ_MONTH_SHORT');

$IBLOCK_ID = $IBLOCK_CATALOG_ID;
$arProps = array(
	'ID_3D_MODEL' => array(
		'bdbdb4f2-fbd8-445f-93b0-034ef0fcde01',
		'e7cbc125-f9dc-4b0e-8044-b1a9d5aed63e',
		'b0722389-343b-4a02-bf93-2595f91d57e6',
		'max' => 2
	),
	'RZ_CREDIT' => array(
		'1 200' . $rubMonth,
		'2 400' . $rubMonth,
		'3 600' . $rubMonth,
		'max' => 2
	),
	'RZ_DELIVERY' => array(
		GetMessage('RZ_TODAY'),
		GetMessage('RZ_TOMORROW'),
		GetMessage('RZ_AFTER_TOMORROW'),
		'max' => 2
	),
	'RZ_GUARANTEE' => array('max' => 4)
);
for ($i = 12; $i <= 60; $i += 12) {
	$arProps['RZ_GUARANTEE'][] = array(
		'VALUE' => $i . ' ' . GetMessage('RZ_MONTH_SHORT'),
		'DESCRIPTION' => $i . ' ' . GetMessage('RZ_MONTH_FULL')
	);
}
if ($moduleCode !== 'yenisite.b2tools') {
	$arProps['BRANDS_REF'] = array('max' => $brandsCount-1);
	for ($i=0; $i<$brandsCount; $i++) {
		$arProps['BRANDS_REF'][$i] = 'company' . ($i + 1);
	}
}

$arFilter = Array(
	"IBLOCK_ID"=>$IBLOCK_ID, 
	"ACTIVE"=>"Y"
	);
$arSelect = array('ID');
/*
$arPropEnums = array();
$arPropEnums['MANUFACTURER'] = array(324,536,537,538,539,540,541,542,543,544);
$arPropEnums['SEASON'] = array(320,321,322,323,343);
*/

$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false, false, $arSelect);
while($ar_fields = $res->GetNext())
{
	foreach($arProps as $propCode => &$arValues)
	{
		//$valueId = $arPropEnums[$propCode][array_rand($arPropEnums[$propCode], 1)]['ID'];
		CIBlockElement::SetPropertyValues($ar_fields['ID'], $IBLOCK_ID, $arValues[mt_rand(0,$arValues['max'])], $propCode);
	}
	unset($arValues);
}