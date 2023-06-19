<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 300;

if ($arParams['SECTION_ONLY'] != "Y") $arParams['SECTION_ONLY'] = "N";

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
$IBLOCK_ID = intval($arParams['IBLOCK']);

if (!CModule::IncludeModule("yenisite.feedback")) {
    //$this->AbortResultCache();
    ShowError(GetMessage("FEEDBACK_MODULE_NOT_INSTALLED"));
    return;
}
if (!CModule::IncludeModule("iblock")) {
    //$this->AbortResultCache();
    ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
    return;
}

if(!is_array($arParams["FIELD_CODE"]))
    $arParams["FIELD_CODE"] = array();
foreach($arParams["FIELD_CODE"] as $key=>$val)
    if(!$val)
        unset($arParams["FIELD_CODE"][$key]);

if ($IBLOCK_ID > 0) {
    CYSFeedBack::$_IBLOCK_ID = $IBLOCK_ID;
}

//Insert element (answer)
if (!empty($_POST['response_text'])) {
    $element_id = $_POST['element_id'];
    //$PROP['message'] = array('VALUE'=>array('TEXT' => $_POST['response_text'], 'TYPE' => 'html'));
    $text = $_POST['response_text'];

    //    $sectionCode = $_REQUEST['SECTION_CODE'];
    $sectionCode = $arParams['SECTION_CODE'];
    $haveSections = (CIBlockSection::GetCount(array('IBLOCK_ID' => $IBLOCK_ID))) ? TRUE : FALSE;
    $sectionErr = TRUE;

    if (!empty($sectionCode) && $haveSections) {
        $res = CIBlockSection::GetList(array(), array('IBLOCK_ID' => $IBLOCK_ID, 'CODE' => $sectionCode));
        $section = $res->Fetch();
        if ($res->SelectedRowsCount() != 1)
            $sectionErr = TRUE;
        else {
            $sectionErr = FALSE;
            $sectionID = $section['ID'];
        }
    } elseif (!$haveSections) {
        $sectionErr = FALSE;
        $sectionID = FALSE;
    }

    if (!$sectionErr) {
        $el = new CIBlockElement;
        $arLoadProductArray = Array(
            "MODIFIED_BY" => $USER->GetID(),
            "DETAIL_TEXT" => $text,
            //"IBLOCK_SECTION_ID" => $sectionID,
            //"IBLOCK_ID"      => $IBLOCK_ID,
            //"PROPERTY_VALUES"=> $PROP,
            //"NAME"           => $name,
            //"ACTIVE"         => "Y",
        );

        //var_dump ($arLoadProductArray);
        if ($response = $el->Update($element_id, $arLoadProductArray)) {
            //var_dump($PROP);
        } else
            echo "<span style='color: red;'> Error: " . $el->LAST_ERROR . "</span>";
    }
}

//var_dump($_REQUEST);


//    $properties = CIBlock::GetProperties($IBLOCK_ID, Array('sort' => 'asc'));
//    $fields = array();
//    while ($prop_fields = $properties->GetNext())
//            $fields[] = $prop_fields['CODE'];
$pageN = $_GET ? : array('PAGE_N' => 1);
$cacheID = $USER->GetGroups().implode('_',$arParams).implode('_',$pageN);
if ($this->StartResultCache(false, $cacheID)) {
    $cachePath = serialize(array($this->__templateName, $arParams));
    $fields = CYSFeedBack::getPropertiesArray('all_plain', $cachePath);
    $arResult['element_count'] = CIBlock::GetElementCount($IBLOCK_ID);
    $arResult['SECTIONS'] = array();

    $haveSections = (CIBlockSection::GetCount(array('IBLOCK_ID' => $IBLOCK_ID))) ? TRUE : FALSE;
    $sectionExist = FALSE;

    //If have sections (SECTIONS)
    if ($haveSections || $arParams['SECTION_ONLY'] == 'Y') {
        $sectionCode = $arParams['SECTION_CODE'];
        $sections = CYSFeedBack::getSections();

        foreach ($sections as $section) {
            $arResult['SECTIONS'][$section['CODE']] = $section;
            if ($section['CODE'] == $sectionCode)
                $sectionExist = TRUE;
        }
    } else
        $sectionExist = TRUE;

    //Get messages
    $arSelect = array_merge($arParams["FIELD_CODE"], Array(
            'ID',
            'NAME',
            'DATE_CREATE',
            'PREVIEW_TEXT',
            'DETAIL_TEXT',
            'CREATED_BY')
    );

    $file_field = FALSE;
    if (is_array($fields))
        foreach ($fields as $key => $field) {
            $field = strtoupper($field);
            $arSelect[] = 'PROPERTY_' . $field;
            if ($field == 'FILE')
                $file_field = $field;
        }

    $colorScheme = CYSFeedBack::getBitronicColorScheme();
    $arResult['COLOR_SCHEME'] = ($colorScheme === FALSE) ? $arParams['COLOR_SCHEME'] : $arParams['COLOR_SCHEME'];

    $arFilter = Array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y'/*, 'PROPERTY_element' => 0*/);
    if ($sectionExist) {

        $arFilter['SECTION_CODE'] = $sectionCode;
        $arResult['CUR_SECTION_CODE'] = $sectionCode;

        if (empty($arParams['MESS_PER_PAGE']))
            $messagesPerPage = FALSE;
        else
            $messagesPerPage = is_numeric($arParams['MESS_PER_PAGE']) ? $arParams['MESS_PER_PAGE'] : 10;


        $resNav = CIBlockElement::GetList(Array(), $arFilter, false, false, Array('ID'));
        $resNav->NavStart($messagesPerPage);


        $NAV = $resNav->GetPageNavStringEx($navComponentObject, '', '', 'Y');

        $cacheID = serialize(array($arParams['SECTION_CODE'], $NAV));

        CPageOption::SetOptionString("main", "nav_page_in_session", "N");


        $res = CIBlockElement::GetList(Array('DATE_CREATE' => 'desc'), $arFilter, FALSE, FALSE, $arSelect);

        if (is_numeric($messagesPerPage)) {
            $res->NavStart($messagesPerPage);
            $arNavParams = array(
                "nPageSize" => '2',
                "bDescPageNumbering" => '',
                "bShowAll" => 'Y',
            );

            $showPages = ($arParams['ALWAYS_SHOW_PAGES'] == 'Y') ? TRUE : FALSE;

            $NAV = $res->GetPageNavStringEx($navComponentObject, '', '', $showPages);
            $arResult['NAV'] = $NAV;

        }
        while ($rsElement = $res->GetNext()) {
            $fid = $rsElement['PROPERTY_FILE_VALUE'];
            if ($file_field !== FALSE && is_numeric($fid))
                $rsElement['FILE'] = CFile::GetFileArray($fid);

            $arResult['ITEMS'][] = $rsElement;
        }

        $arResult['CAN_RESPONSE'] = ($USER->isAdmin() && $arParams['ALLOW_RESPONSE'] == 'Y') ? TRUE : FALSE;
        $arResult['SHOW_INFO'] = $USER->isAdmin();
    }
    $this->IncludeComponentTemplate();
}