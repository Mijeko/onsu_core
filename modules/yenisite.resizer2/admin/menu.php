<?
global $USER;
if(!$USER->IsAdmin())
	return;
	
/* menu of module yenisite.resizer2 */
	IncludeModuleLangFile(__FILE__);
	CModule::IncludeModule("iblock");
	$aMenu = array(
		"parent_menu" => "global_menu_services",
		"text" => GetMessage("RESIZER_MODULE_NAME"),
		"icon" => "resizer2_menu_icon",
		"page_icon" => "resizer2_page_icon",
		"url"  => "",
		"items_id" => "yenisite_resizer2",
		"items" => array(
			array(
				"text" => GetMessage("SETS"),
				"url" => "/bitrix/admin/yci_resizer2_sets.php?".bitrix_sessid_get()."&lang=".LANG,
 				"more_url" => array(
					"yci_resizer2_set_edit.php",
				),
			),
			array(
				"text" => GetMessage("WM"),
				"url" => "/bitrix/admin/yci_resizer2_wm.php?".bitrix_sessid_get()."&lang=".LANG,
			),
			array(
				"text" => GetMessage("CACHE"),
				"url" => "/bitrix/admin/yci_resizer2_cache.php?".bitrix_sessid_get()."&lang=".LANG,
			),
			array(
				"text" => GetMessage("F1"),
				"url" => "/bitrix/admin/yci_resizer2_f1.php?".bitrix_sessid_get()."&lang=".LANG,
			),
		),
	);
	return($aMenu);
?>
