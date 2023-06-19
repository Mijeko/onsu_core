<?
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;

$MESS['RZ_CURRENCY'] = 'Валюта';
$MESS["BITRONIC2_RUB_CHAR"] = defined(RZ_B2_MODULE_FULL_NAME) && Loader::includeModule(RZ_B2_MODULE_FULL_NAME) ? Option::get(CRZBitronic2Settings::getModuleId(),'rub_lang','Р',SITE_ID) : 'Р';
?>