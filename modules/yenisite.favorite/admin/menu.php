<?
/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CAdminMenu $this
 */
use \Bitrix\Main\Localization\Loc;
$MODULE_ID = 'yenisite.favorite';

if ($APPLICATION->GetGroupRight($MODULE_ID) > 'D') {
	if (\Bitrix\Main\ModuleManager::isModuleInstalled($MODULE_ID)) {
		IncludeModuleLangFile(__FILE__);
		$aMenu = array(
			'parent_menu' => 'global_menu_store',
			'sort' => 100,
			'text' => Loc::GetMessage($MODULE_ID . '_MENU_TEXT'),
			'icon' => str_replace('.', '_', $MODULE_ID) . '_menu_icon',
			'page_icon' => str_replace('.', '_', $MODULE_ID) . '_page_icon',
			'url' => '',
			'module_id' => $MODULE_ID,
			'items_id' => $MODULE_ID,
			'items' => array(
				array(
					'text' => Loc::GetMessage($MODULE_ID . '_MENU_ITEMS'),
					'url' => '/bitrix/admin/yns_favorite_list.php?lang=' . LANG,
					'more_url' => array(),
				),
			)
		);
		return $aMenu;
	}
}
return false;
