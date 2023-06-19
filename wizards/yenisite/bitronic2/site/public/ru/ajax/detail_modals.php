<?
include_once "include_stop_statistic.php";

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

include_once "include_module.php";
include_once "include_options.php";

global $rz_b2_options;

$arAjax = \Yenisite\Core\Tools::GetDecodedArParams($_REQUEST['ajax']);
if(empty($arAjax)) {
    die('[ajax died] cannot get ajax data');
}
$arParams = \Yenisite\Core\Ajax::getParams($arAjax['CMP'], $arAjax['TMPL'], $arAjax['PAGE']);
$arParams['AJAX_MODE'] = "";

\Yenisite\Core\Tools::encodeAjaxRequest($_REQUEST);
\Yenisite\Core\Tools::encodeAjaxRequest($_POST);
if(!empty($_POST[$_POST['FORM_CODE']]['PRICE'])) {
    $_POST[$_POST['FORM_CODE']]['PRICE'] = str_replace(' ', '', $_POST[$_POST['FORM_CODE']]['PRICE']);
}
if('Y' == $_POST['CONVERT_CURRENCY'] && !empty($_POST['FORM_CODE'])) {
    $arForm = &$_POST[$_POST['FORM_CODE']];
    if (!empty($arForm['CURRENCY']) && !empty($arForm['PRICE'])) {
        \Bitrix\Main\Loader::includeModule('currency');
        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        $baseC = CCurrency::GetBaseCurrency();
        if ($baseC != $arForm['CURRENCY']) {
            /** @noinspection PhpUndefinedClassInspection */
            $arForm['PRICE'] = CCurrencyRates::ConvertCurrency($arForm['PRICE'], $arForm['CURRENCY'], $baseC);
        }
    }
}
if (isset($_REQUEST['FORM_CODE'])) unset($_REQUEST['FORM_CODE'], $_POST['FORM_CODE'], $_GET['FORM_CODE']);
unset($_REQUEST['ajax'], $_POST['ajax'], $_GET['ajax']);

$_REQUEST = array_merge($_REQUEST, $_POST);

if (!empty($_REQUEST['rz_option_name'])){
    $arParams['USE_CAPTCHA'] = $rz_b2_options[$_REQUEST['rz_option_name']] && $rz_b2_options['use_google_captcha'] == 'N' ? $rz_b2_options[$_REQUEST['rz_option_name']] : 'N';

    $arParams['USE_GOOGLE_CAPTCHA'] = $rz_b2_options[$_REQUEST['rz_option_name']] && $rz_b2_options['use_google_captcha'] == 'Y' ? $rz_b2_options[$_REQUEST['rz_option_name']] : 'N';
}

$APPLICATION->IncludeComponent($arAjax['CMP'], $arAjax['TMPL'], $arParams);
