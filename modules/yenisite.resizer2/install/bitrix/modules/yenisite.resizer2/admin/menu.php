<?
/* Меню модуля yenisite.resizer2 */
	IncludeModuleLangFile(__FILE__);
        CModule::IncludeModule("iblock");
	$aMenu = array(
		"parent_menu" => "global_menu_services",
		"text" => GetMessage("MODULE_NAME"),		
		"icon" => "resizer2_menu_icon",
                "page_icon" => "resizer2_page_icon",		
		"url"  => "",
		"items_id" => "yenisite_resizer2",
		"items" => array(
			array(
				"text" => GetMessage("SETS"),
				"url" => "/bitrix/admin/yci_resizer2_sets.php?lang=".LANG,
 				"more_url" => array(
                                        "yci_resizer2_set_edit.php",
                                ),
			),
			array(
				"text" => GetMessage("WM"),				
                                "url" => "/bitrix/admin/yci_resizer2_wm.php?lang=".LANG,

			),
                        array(
				"text" => GetMessage("CACHE"),
				"url" => "/bitrix/admin/yci_resizer2_cache.php?lang=".LANG,                                
                        ),
                        
                        array(
				"text" => GetMessage("F1"),
				"url" => "/bitrix/admin/yci_resizer2_f1.php?lang=".LANG,
                                
                        ),


			),

			

	);
	return($aMenu);	
?>
