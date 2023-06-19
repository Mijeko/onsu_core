<?
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;

$MESS["BITRONIC2_RUB_CHAR"] = defined(RZ_B2_MODULE_FULL_NAME) && Loader::includeModule(RZ_B2_MODULE_FULL_NAME) ? Option::get(CRZBitronic2Settings::getModuleId(),'rub_lang','Р',SITE_ID) : 'Р';

$MESS['BITRONIC2_REPLACE_LOGIN'] = 'логин';
$MESS['BITRONIC2_DELETE_LOGIN'] = 'с логином ';
$MESS["BITRONIC2_CATALOG_ITEM"] = "товар";
$MESS["BITRONIC2_CATALOG_ITEMSA"] = "товара";
$MESS["BITRONIC2_CATALOG_ITEMS"] = "товаров";
?>
