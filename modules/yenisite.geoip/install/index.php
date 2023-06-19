<?
IncludeModuleLangFile(__FILE__);
Class yenisite_geoip extends CModule
{
	const  MODULE_ID = "yenisite.geoip";
	var $MODULE_ID = "yenisite.geoip";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;

    function __construct()
    {
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->PARTNER_NAME = GetMessage('GEOIP_SPER_PARTNER');
		$this->PARTNER_URI = "http://romza.ru/";
		$this->MODULE_NAME = GetMessage("GEOIP_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("GEOIP_MODULE_DESC");
	}
	
	function InstallEvents()
	{
		RegisterModuleDependences("main",                "OnBeforeUserAdd",                          self::MODULE_ID, "CYSGeoIP", "OnBeforeUserAddHandler", 10000);
		RegisterModuleDependences("sale",                "OnSaleComponentOrderOneStepPersonType",    self::MODULE_ID, "CYSGeoIP", "OnSaleComponentOrderOneStepPersonTypeHandler", 10000);
		RegisterModuleDependences("sale",                "OnSaleComponentOrderProperties",				 self::MODULE_ID, "CYSGeoIP", "OnSaleComponentOrderProperties", 10000);
		RegisterModuleDependences("sale",                "OnSaleComponentOrderOneStepOrderProps",    self::MODULE_ID, "CYSGeoIP", "OnSaleComponentOrderOneStepOrderPropsHandler", 10);
		RegisterModuleDependences('yenisite.profileadd', 'OnBeforeComponentNewProfileGetOrderValue', self::MODULE_ID, "CYSGeoIP", "OnBeforeComponentNewProfileGetOrderValueHandler");

		return true;
	}

	function UnInstallEvents()
	{
		UnRegisterModuleDependences("main",                "OnBeforeUserAdd",                          self::MODULE_ID, "CYSGeoIP", "OnBeforeUserAddHandler");
		UnRegisterModuleDependences("sale",                "OnSaleComponentOrderOneStepPersonType",    self::MODULE_ID, "CYSGeoIP", "OnSaleComponentOrderOneStepPersonTypeHandler");
		UnRegisterModuleDependences("sale",                "OnSaleComponentOrderProperties",			   self::MODULE_ID, "CYSGeoIP", "OnSaleComponentOrderProperties");
		UnRegisterModuleDependences("sale",                "OnSaleComponentOrderOneStepOrderProps",    self::MODULE_ID, "CYSGeoIP", "OnSaleComponentOrderOneStepOrderPropsHandler");
		UnRegisterModuleDependences('yenisite.profileadd', 'OnBeforeComponentNewProfileGetOrderValue', self::MODULE_ID, "CYSGeoIP", "OnBeforeComponentNewProfileGetOrderValueHandler");
		
		return true;
	}
	
	function InstallFiles($arParams = array())
	{
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/install/bitrix')) {
			if ($dir = opendir($p)) {
				while (false !== $item = readdir($dir))	{
					if ($item == '..' || $item == '.')
						continue;
					CopyDirFiles($p.'/'.$item, $_SERVER['DOCUMENT_ROOT'].'/bitrix/'.$item, $ReWrite = True, $Recursive = True);
				}
				closedir($dir);
			}
		}
	}
	
	function UnInstallFiles()
	{
		DeleteDirFilesEx('/bitrix/js/' . self::MODULE_ID);
		DeleteDirFilesEx('/bitrix/components/yenisite/geoip.city/');
	}
	
	function DoInstall()
	{
		global $APPLICATION;
		RegisterModule(self::MODULE_ID);
		$this->InstallEvents();
		$this->InstallFiles();
	}

	function DoUninstall()
	{
		global $APPLICATION;
		UnRegisterModule(self::MODULE_ID);
		$this->UnInstallEvents();
		$this->UnInstallFiles();
	}
}