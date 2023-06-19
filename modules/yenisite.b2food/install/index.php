<?
IncludeModuleLangFile(__FILE__);

class yenisite_b2food extends CModule	/// !!!!
{

	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = "Y";

	////
	var $MODULE_ID = "yenisite.b2food";
	static private $commonClassName = 'Bitronic2';
	static private $_MODULE_ID = "yenisite.b2food";
	static private $_WIZARD_ID = "bitronic2";
	////


	function __construct() {
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = GetMessage("BITRONIC2_SCOM_INSTALL_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("BITRONIC2_SCOM_INSTALL_DESCRIPTION");
		$this->PARTNER_NAME = GetMessage("BITRONIC2_SPER_PARTNER");
		$this->PARTNER_URI = GetMessage("BITRONIC2_PARTNER_URI");
	}


	function InstallDB($install_wizard = true)
	{
		global $DB, $DBType, $APPLICATION;
		RegisterModule(self::$_MODULE_ID);
		return true;
	}


	public static function OptionCheckInstall($Name, $setValue, $type = 'string') {
		if ($type == 'string') {
			$curVal = COption::GetOptionString(self::$_MODULE_ID, $Name);
			if (empty($curVal)) {
				COption::SetOptionString(self::$_MODULE_ID, $Name, $setValue);
			}
		} else {
			$curVal = COption::GetOptionInt(self::$_MODULE_ID, $Name);
			if (empty($curVal)) {
				COption::SetOptionInt(self::$_MODULE_ID, $Name, $setValue);
			}
		}

	}
	function UnInstallDB($arParams = Array())
	{
		UnRegisterModule(self::$_MODULE_ID);
		return true;
	}

	function InstallOptions() {
		self::OptionCheckInstall('store_prop_sync','Y');
	}

	function InstallEvents()
	{
		RegisterModuleDependences("sale", "OnOrderAdd", self::$_MODULE_ID, "CRZ".self::$commonClassName."Handlers", "setGeoStoreToOrder");
	}

	function UnInstallEvents()
	{
		UnRegisterModuleDependences('main', 'OnGetStaticCacheProvider', self::$_MODULE_ID, 'CacheProvider', 'getObject');
		UnRegisterModuleDependences("sale", "OnOrderAdd",               self::$_MODULE_ID, "CRZ".self::$commonClassName."Handlers", "setGeoStoreToOrder");

		$obSite = CSite::GetList($by = "def", $order = "desc");
		while ($arSite = $obSite->Fetch()) {
			UnRegisterModuleDependences("main", "OnBeforeUserRegister", self::$_MODULE_ID, "CRZ".self::$commonClassName."Handlers", "OnBeforeUserRegisterHandler", "", array($arSite['LID']));
			UnRegisterModuleDependences("main", "OnBeforeUserUpdate",   self::$_MODULE_ID, "CRZ".self::$commonClassName."Handlers", "OnBeforeUserRegisterHandler", "", array($arSite['LID']));
			UnRegisterModuleDependences("main", "OnBeforeUserAdd",      self::$_MODULE_ID, "CRZ".self::$commonClassName."Handlers", "OnBeforeUserRegisterHandler", "", array($arSite['LID']));
			UnRegisterModuleDependences("main", "OnEpilog",             self::$_MODULE_ID, "CRZ".self::$commonClassName."Handlers", "Redirect404",                 "", array($arSite['LID']));

			UnRegisterModuleDependences("iblock",  "OnAfterIBlockElementUpdate", self::$_MODULE_ID, "CRZ".self::$commonClassName."Handlers", "DoIBlockAfterSave", "", array($arSite['LID']));
			UnRegisterModuleDependences("iblock",  "OnAfterIBlockElementAdd",    self::$_MODULE_ID, "CRZ".self::$commonClassName."Handlers", "DoIBlockAfterSave", "", array($arSite['LID']));
			UnRegisterModuleDependences("catalog", "OnPriceAdd",                 self::$_MODULE_ID, "CRZ".self::$commonClassName."Handlers", "DoIBlockAfterSave", "", array($arSite['LID']));
			UnRegisterModuleDependences("catalog", "OnPriceUpdate",              self::$_MODULE_ID, "CRZ".self::$commonClassName."Handlers", "DoIBlockAfterSave", "", array($arSite['LID']));
			UnRegisterModuleDependences("catalog", "OnProductUpdate",            self::$_MODULE_ID, "CRZ".self::$commonClassName."Handlers", "DoIBlockAfterSave", "", array($arSite['LID']));
			UnRegisterModuleDependences("catalog", "OnStoreProductUpdate",       self::$_MODULE_ID, "CRZ".self::$commonClassName."Handlers", "StoreInProperties", "", array($arSite['LID']));
			UnRegisterModuleDependences("catalog", "OnDiscountAdd",              self::$_MODULE_ID, "CRZ".self::$commonClassName."Handlers", "OnDiscountAddUpdate", "", array($arSite['LID']));
			UnRegisterModuleDependences("catalog", "OnDiscountUpdate",           self::$_MODULE_ID, "CRZ".self::$commonClassName."Handlers", "OnDiscountAddUpdate", "", array($arSite['LID']));
		}
	}

	function InstallFiles()
	{
		// admin section
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::$_MODULE_ID . '/admin')) {
			if ($dir = opendir($p)) {
				while (false !== $item = readdir($dir)) {
					if ($item == '..' || $item == '.' || $item == 'menu.php')
						continue;
					file_put_contents($file = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . $item,
						'<' . '? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/' . self::$_MODULE_ID . '/admin/' . $item . '");?' . '>');
				}
				closedir($dir);
			}
		}
		// wizard
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::$_MODULE_ID."/install/wizards/yenisite/".self::$_WIZARD_ID, $_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/yenisite/".self::$_WIZARD_ID, true, true);

		//components
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::$_MODULE_ID."/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/", true, true);

		//images
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::$_MODULE_ID."/install/images", $_SERVER["DOCUMENT_ROOT"]."/bitrix/images/".self::$_MODULE_ID, true, true);

		return true;
	}

	function InstallPublic()
	{
	}

	function UnInstallFiles()
	{
		// admin section
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::$_MODULE_ID . '/admin')) {
			if ($dir = opendir($p)) {
				while (false !== $item = readdir($dir)) {
					if ($item == '..' || $item == '.')
						continue;
					unlink($_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . self::$_MODULE_ID . '_' . $item);
				}
				closedir($dir);
			}
		}
		DeleteDirFilesEx("/bitrix/wizards/yenisite/".self::$_WIZARD_ID);
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION, $step;

		$this->InstallFiles();
		$this->InstallDB(false);
		$this->InstallEvents();
		$this->InstallOptions();
		$this->InstallPublic();

	}

	function DoUninstall()
	{
		global $APPLICATION, $step;

		$this->UnInstallDB();
		$this->UnInstallFiles();
		$this->UnInstallEvents();

	}
}
?>
