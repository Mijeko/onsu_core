<?
/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CAdminMenu $this
 */
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\ModuleManager as BxMM;
use Yenisite\Core\Tools;
use \Bitrix\Main\Loader;

$MODULE_FULL_NAME = 'yenisite.core';

if (Loader::includeModule($MODULE_FULL_NAME)){
    $dirBitronic = Tools::findNeedDir('yenisite.bitronic2', $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules');
    $dirBitronic = $dirBitronic ? : Tools::findNeedDir('yenisite.b2', $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/modules') ;
}

if (!empty($dirBitronic)) {
    include __DIR__ . '/../../' . $dirBitronic . '/constants.php';
}

if (defined('RZ_B2_MODULE_FULL_NAME') && !empty($dirBitronic)) {
    if ($APPLICATION->GetGroupRight(RZ_B2_MODULE_FULL_NAME) > 'D') {
        if (\Bitrix\Main\ModuleManager::isModuleInstalled(RZ_B2_MODULE_FULL_NAME)) {
            Loc::loadMessages(__FILE__);
            $arMenu[] = array(
                'parent_menu' => 'global_menu_settings',
                'sort' => 100,
                'text' => Loc::GetMessage('RZ_MENU_TEXT'),
                'icon' => str_replace('.', '_', RZ_B2_MODULE_FULL_NAME) . '_menu_icon',
                'page_icon' => str_replace('.', '_', RZ_B2_MODULE_FULL_NAME) . '_page_icon',
                'url' => '',
                'module_id' => RZ_B2_MODULE_FULL_NAME,
                'items_id' => RZ_B2_MODULE_FULL_NAME,
                'items' => array(
                    array(
                        'text' => Loc::GetMessage('RZ_MENU_ITEM_AVAILABLE_REINDEX'),
                        'url' => '/bitrix/admin/b2_reindex_available.php?lang=' . LANG,
                        'more_url' => array(),
                    ),
                )
            );
        }
    }
}

if ($APPLICATION->GetGroupRight($MODULE_FULL_NAME) > 'D') {
    if (BxMM::isModuleInstalled($MODULE_FULL_NAME)) {
        if (BxMM::isModuleInstalled('yenisite.bitronic2pro')/*
&&  !BxMM::isModuleInstalled('yenisite.shinmarketpro')
&&  !BxMM::isModuleInstalled('yenisite.apparelpro')*/
        ) {

            Loc::loadMessages(__FILE__);
            $arMenu[] = array(
                'parent_menu' => 'global_menu_services',
                'sort' => 10,
                'text' => Loc::GetMessage('RZ_CORE_MENU_TEXT'),
                'icon' => str_replace('.', '_', $MODULE_FULL_NAME) . '_menu_icon',
                'page_icon' => str_replace('.', '_', $MODULE_FULL_NAME) . '_page_icon',
                'url' => '',
                'module_id' => $MODULE_FULL_NAME,
                'items_id' => $MODULE_FULL_NAME,
                'items' => array(
                    array(
                        'text' => Loc::GetMessage('RZ_MENU_ITEM_COLOR_GUESSER'),
                        'url' => '/bitrix/admin/ys_core_color_guesser.php?lang=' . LANG,
                        'more_url' => array(),
                    ),
                )
            );
        }
    }
}
if (!empty($arMenu)){
   return $arMenu;
}else{
    return false;
}
return $aMenu;