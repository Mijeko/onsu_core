<?
global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

Class yenisite_feedback extends CModule
{
	var $MODULE_ID = "yenisite.feedback";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = "Y";

	function yenisite_feedback()
	{
            $arModuleVersion = array();

            $path = str_replace("\\", "/", __FILE__);
            $path = substr($path, 0, strlen($path) - strlen("/index.php"));
            include($path."/version.php");

            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
            $this->MODULE_NAME = GetMessage("yenisite.feedback_MODULE_NAME");
            $this->MODULE_DESCRIPTION = GetMessage("yenisite.feedback_MODULE_DESC");
            $this->PARTNER_NAME = GetMessage("yenisite.feedback_PARTNER_NAME");
            $this->PARTNER_URI = GetMessage("yenisite.feedback_PARTNER_URI");
	}


	function InstallDB($install_wizard = true)
	{
		/*global $DB, $DBType, $APPLICATION;


		RegisterModuleDependences("main", "OnBeforeProlog", "bitrix.sitestore", "CSiteStore", "ShowPanel");
        */
 		//LocalRedirect("/bitrix/admin/wizard_install.php?lang=ru&wizardName=yenisite:bitronic&".bitrix_sessid_get());
            return true;
	}
        
	function UnInstallDB($arParams = Array())
	{
		/*
		global $DB, $DBType, $APPLICATION;

		UnRegisterModuleDependences("main", "OnBeforeProlog", "bitrix.sitestore", "CSiteStore", "ShowPanel"); 		
        */
        
            return true;
	}

	function InstallEvents()
	{
            return true;
	}

	function UnInstallEvents()
	{
            return true;
	}

	function InstallFiles()
	{
		//CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.guestbook/install/wizards/yenisite/guestbook", $_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/yenisite/guestbook", true, true);
            CopyDirFiles($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/yenisite.feedback/install/bitrix/components", $_SERVER['DOCUMENT_ROOT']."/bitrix/components", true, true);
            CopyDirFiles($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/yenisite.feedback/install/bitrix/wizards", $_SERVER['DOCUMENT_ROOT']."/bitrix/wizards", true, true);
            return true;
	}

	function InstallPublic()
	{
	}

	function UnInstallFiles()
	{
        //$_SERVER['DOCUMENT_ROOT']
            DeleteDirFilesEx("/bitrix/components/yenisite/feedback/");
            DeleteDirFilesEx("/bitrix/components/yenisite/feedback.add/");
            DeleteDirFilesEx("/bitrix/components/yenisite/feedback.list/");
            
            DeleteDirFilesEx("/bitrix/wizards/yenisite/feedback/");

            return true;
	}

	function DoInstall()
	{
            global $APPLICATION, $step;

            $this->InstallFiles();
            $this->InstallDB(false);
            $this->InstallEvents();
            $this->InstallPublic();
            RegisterModule("yenisite.feedback");
            if (CModule::IncludeModule("catalog"))
            {
                //RegisterModuleDependences("catalog", "OnBeforePriceUpdate", "yenisite.feedback", "CYSElementEvents", "OnBeforePriceUpdateHandler");
                //RegisterModuleDependences("catalog", "OnProductUpdate", "yenisite.feedback", "CYSElementEvents", "OnProductUpdateHandler");
            }
            else
            {
                //RegisterModuleDependences("iblock", "OnAfterIBlockElementUpdate", "yenisite.feedback", "CYSElementEvents", "OnAfterIBlockElementUpdateHandler");
            }
            
            CAdminMessage::ShowNote(GetMessage("INSTALL_COMPLETE"));
            //$APPLICATION->IncludeAdminFile(GetMessage("yenisite.feedback_INSTALL_TITLE"), $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/yenisite.feedback/install/step.php");
	}

	function DoUninstall()
	{
            global $APPLICATION, $step;

            $this->UnInstallDB();
            $this->UnInstallFiles();
            $this->UnInstallEvents();
            UnRegisterModule("yenisite.feedback");
            
            //UnRegisterModuleDependences("iblock", "OnAfterIBlockElementUpdate", "yenisite.feedback", "CYSElementEvents", "OnAfterIBlockElementUpdateHandler");
            //UnRegisterModuleDependences("catalog", "OnBeforePriceUpdate", "yenisite.feedback", "CYSElementEvents", "OnBeforePriceUpdateHandler");
            //UnRegisterModuleDependences("catalog", "OnProductUpdate", "yenisite.feedback", "CYSElementEvents", "OnProductUpdateHandler");
            
            CAdminMessage::ShowNote(GetMessage("UNINSTALL_COMPLETE"));
            //$APPLICATION->IncludeAdminFile(GetMessage("yenisite.feedback_UNINSTALL_TITLE"), $_SERVER['DOCUMENT_ROOT']."/bitrix/modules/yenisite.feedback/install/unstep.php");
	}
}
?>
