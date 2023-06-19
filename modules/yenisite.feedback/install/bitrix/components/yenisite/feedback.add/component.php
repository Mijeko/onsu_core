<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(empty($arParams['AJAX_REDIRECT'])){
	if((!AJAX_REDIRECT_ENABLE) || (AJAX_REDIRECT_ENABLE == true))
		$arParams['AJAX_REDIRECT'] = "Y";
	elseif(AJAX_REDIRECT_ENABLE == false)
		$arParams['AJAX_REDIRECT'] = "N";
}

if(!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 300;

$arParams["IBLOCK_TYPE"] = trim($arParams["IBLOCK_TYPE"]);
$IBLOCK_ID = intval($arParams['IBLOCK']);

if (!CModule::IncludeModule("yenisite.feedback"))
{
    $this->AbortResultCache();
    ShowError(GetMessage("FEEDBACK_MODULE_NOT_INSTALLED"));
    return;
}
if(!CModule::IncludeModule("iblock"))
{
    $this->AbortResultCache();
    ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
    return;
}
    
if ($IBLOCK_ID > 0) {
    CYSFeedBack::$_IBLOCK_ID = $IBLOCK_ID;
}

//$code = CIBlock::GetArrayByID($IBLOCK_ID, "CODE");
$code = 'romza_feedback';
$element_add = (empty($arParams['TITLE'])) ? CIBlock::GetArrayByID($IBLOCK_ID, "ELEMENT_ADD") : $arParams['TITLE'];
$cachePath = serialize(array($this->__templateName, $arParams));
$fields = CYSFeedBack::getPropertiesArray($arParams['PRINT_FIELDS'], $cachePath);

//var_dump($fields);
$addOk = ($_REQUEST['add'] == 'ok') ? TRUE : FALSE;
//Insert element (message)
if (!empty($_POST[$code]))
{
    //Check CAPTCHA
    $CAPTCHA_OK = TRUE;
    if($arParams["USE_CAPTCHA"] == "Y")
    {
        $CAPTCHA_OK = CYSFeedBack::checkCAPTCHA($_POST["captcha_word"], $_POST["captcha_code"]);
        if (!$CAPTCHA_OK)
            $arResult["ERROR_MESSAGE"] = GetMessage("POSTM_CAPTCHA_IS_BAD");
    }

    if ($arParams['USE_GOOGLE_CAPTCHA'] == 'Y' && empty($_REQUEST['yenisite_google_captcha_success']) && $_REQUEST['yenisite_google_captcha_success'] != 'Y'){
        $CAPTCHA_OK = false;
        $arResult["ERROR"] = GetMessage("POSTM_GOOGLE_CAPTCHA_IS_BAD");
    }

    if(!empty($_REQUEST['privacy_policy']) && $_REQUEST['privacy_policy'] != 'on'){
        if ($_REQUEST['privacy_policy'] != 'Y') {
            $CAPTCHA_OK = false;
            $arResult["ERROR"] = GetMessage("NEED_ACCEPT_RULES");
        }
    }

    if ($CAPTCHA_OK)
    {
       	$sectionFields['CODE'] = (!empty($arParams['SECTION_CODE']) || $arParams['SHOW_SECTIONS'] != 'Y') ? $arParams['SECTION_CODE'] : $_POST[$code]['section'];
				$sectionFields['NAME'] = (!empty($arParams['SECTION_NAME'])) ? $arParams['SECTION_NAME'] : $sectionFields['CODE'];



        $mess = CYSFeedBack::addMessage($_POST, $code, $_FILES, $element_add, $fields, $arParams, $USER->GetID(), $sectionFields);
        if ($mess === TRUE)
        {
            $arResult['SUCCESS_TEXT'] = $arParams['SUCCESS_TEXT'];
            $arResult['SUCCESS'] = TRUE;
            if ($arParams['AJAX_MODE'] == 'N' && $arParams['AJAX_REDIRECT'] != 'N')
            {
//                $path = $APPLICATION->GetCurPageParam("add=ok", array('add'));
                $path = $APPLICATION->GetCurPageParam();
                LocalRedirect($path);
                exit();
            }
            //var_dump($path);
        }
        else
        {
            $arResult['DATA'] = $_POST[$code];
            if ($mess == 'ERR_TEXT_EMPTY')
                $arResult['ERROR'] = GetMessage('ERR_TEXT_EMPTY');
            elseif ($mess == 'ERR_EMAIL_INVALID')
                $arResult['ERROR'] = GetMessage('ERR_EMAIL_INVALID');
            else
                $arResult['ERROR'] = $mess;
            
            //$arResult['ERROR'] = $mess;
            
//                var_dump($arResult['ERROR']);
            $addOk = FALSE;
        }
    }
    else
    {
        $arResult['DATA'] = $_POST[$code];
        $arResult['ERROR'] = $arResult['ERROR'] ? : GetMessage("POSTM_CAPTCHA_IS_BAD");
        $addOk = FALSE;
        //echo "<span style='color: red;'>" . GetMessage("POSTM_CAPTCHA_IS_BAD") ."</span>";
    }

}

//if($this->StartResultCache(false))
//{
    //if ($addOk)
    //    $arResult['SUCCESS'] = $arParams['SUCCESS_TEXT'];

    $arResult['CODE'] = $code;
    $arResult['ELEMENT_ADD'] = $element_add;
    $arResult['FIELDS'] = $fields;
    
    $arResult['SECTIONS'] = FALSE;
    
    if ($arParams['SHOW_SECTIONS'] == 'Y' && empty($arParams['SECTION_CODE']))
    {
        $haveSections = (CIBlockSection::GetCount(array('IBLOCK_ID' => $IBLOCK_ID))) ? TRUE : FALSE;
        $arResult['SECTIONS'] = ($haveSections) ? CYSFeedBack::getSections() : FALSE;
    }
    //var_dump($fields);

    //Generate CAPTCHA
    $arResult["CAPTCHA_CODE"] = "";
    if ($arParams["USE_CAPTCHA"] == "Y")
        $arResult["CAPTCHA_CODE"] = CYSFeedBack::generateCAPTCHA();

    $colorScheme = CYSFeedBack::getBitronicColorScheme();
    $arResult['COLOR_SCHEME'] = ($colorScheme === FALSE)? $arParams['COLOR_SCHEME'] : $colorScheme ;
    $arResult['BITRONIC_EXIST'] = CYSFeedBack::bitronicExist();
    
    global $USER;

    if ($USER->isAuthorized())
        $arResult['EMAIL'] = $USER->GetEmail();
    else
        $arResult['EMAIL'] = FALSE;

    $this->IncludeComponentTemplate();
//}
?>
