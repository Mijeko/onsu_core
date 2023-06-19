<?
IncludeModuleLangFile(__FILE__);
class yenisite_oneclick extends CModule {
	var $MODULE_ID = 'yenisite.oneclick';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;

	var $arComponents = array(
		'oneclick.buy'
	);

	function __construct() {
		$MODULE_ID = $this->MODULE_ID;
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path . "/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage($MODULE_ID . "_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage($MODULE_ID . "_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("RZ_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("RZ_PARTNER_URI");

		return true;
	}


	function DoInstall() {
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . '/modules/' . $this->MODULE_ID . '/install/components', $_SERVER["DOCUMENT_ROOT"] . BX_ROOT . '/components', true, true);
		RegisterModule($this->MODULE_ID);
	}

	function DoUninstall() {
		foreach ($this->arComponents as $comp)
			if ($comp) {
				self::removeDirRec($_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/yenisite/{$comp}");
			}
		UnRegisterModule($this->MODULE_ID);
	}

	function removeDirRec($dir) {
		if ($objs = glob($dir . "/*")) {
			foreach ($objs as $obj) {
				is_dir($obj) ? self::removeDirRec($obj) : unlink($obj);
			}
		}
		rmdir($dir);
	}

}