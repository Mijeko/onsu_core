<?
global $MESS;
$strPath2Lang = str_replace('\\', '/', __FILE__);

$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));
include($strPath2Lang."/install/version.php");

Class yenisite_menu extends CModule 																								// <------------ HERE------------- CLASS NAME MUST BE CHANGED
{
        var $MODULE_ID = 'yenisite.menu';																							
        var $MODULE_VERSION;
        var $MODULE_VERSION_DATE;
        var $MODULE_NAME;
        var $MODULE_DESCRIPTION;
		
		var $arComponents = array(
						'menu.ext'
						);
		
        function yenisite_menu()																												// <------------ HERE------------- CONSTRUCTOR NAME MUST BE CHANGED
        {        		
                $arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("yenisite.menu_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("yenisite.menu_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("yenisite.menu_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("yenisite.menu_PARTNER_URI");

		return true;
        }
		

        function DoInstall(){		
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.menu/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
		RegisterModule($this->MODULE_ID);
        }

        function DoUninstall(){           
		foreach($this->arComponents as $comp)
			if($comp)
				self::removeDirRec($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/yenisite/{$comp}");
		UnRegisterModule($this->MODULE_ID);
        }

	function removeDirRec($dir)
	{
		if($objs = glob($dir."/*")) {
			foreach($objs as $obj) {
				is_dir($obj) ? self::removeDirRec($obj) : unlink($obj);
			}
		}
		rmdir($dir);
	}

}

?>
