<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/module_code.php';

\Bitrix\Main\Loader::includeModule('yenisite.core');
global $APPLICATION;
$fileSrc = $APPLICATION->GetFileContent($_SERVER['DOCUMENT_ROOT'].SITE_DIR.'personal/index.php');
$arComponents = PHPParser::ParseScript($fileSrc);

$setId = false;
for ($i = 0, $cnt = count($arComponents); $i < $cnt; $i++) {
    if (!empty($arComponents[$i]['DATA']['PARAMS']['PAYMENT_RESIZER_SET'])) {
        $setId = $arComponents[$i]['DATA']['PARAMS']['PAYMENT_RESIZER_SET'];
    }
}

foreach ($arResult['PAYSYSTEMS_LIST'] as &$arPaySystem){
    if (strlen($arPaySystem['LOGOTIP']) <= 2){
        $arPaySystem['LOGOTIP'] = \Yenisite\Core\Resize::GetResizedImg(SITE_TEMPLATE_PATH . '/img/no-photo.gif',array("SET_ID" => $setId, "WIDTH" => 150, "HEIGHT" => 150));
    }
}