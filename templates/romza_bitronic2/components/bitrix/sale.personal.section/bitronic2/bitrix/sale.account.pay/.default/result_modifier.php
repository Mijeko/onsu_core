<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/module_code.php';

\Bitrix\Main\Loader::includeModule('yenisite.core');
\Bitrix\Main\Loader::includeModule($moduleId);

foreach ($arResult['PAYSYSTEMS_LIST'] as &$arPaySystem){
    if (strlen($arPaySystem['LOGOTIP']) <= 2){
        $arPaySystem['LOGOTIP'] = \Yenisite\Core\Resize::GetResizedImg(SITE_TEMPLATE_PATH . '/img/no-photo.gif',array("SET_ID" => $arParams['PAYMENT_RESIZER_SET'], "WIDTH" => 150, "HEIGHT" => 150));
    }
}

$arResult['FORMATED_CURRENCY'] = '# '.CRZBitronic2CatalogUtils::getCurrencyTemplate($arParams['SELL_CURRENCY']);