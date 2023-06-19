<?
global $MESS;
$strPath2Lang = str_replace('\\', '/', __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));
include($strPath2Lang."/install/version.php");
//require_once(dirname(__FILE__)."/../classes/general/yenisite_resizer2.php");


Class yenisite_resizer2 extends CModule
{
	var $MODULE_ID = "yenisite.resizer2";
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;

	public function yenisite_resizer2()
	{
		$arModuleVersion = array();
		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("yenisite.resizer2_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("yenisite.resizer2_MODULE_DESC");
		$this->PARTNER_NAME = GetMessage("yenisite.resizer2_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("yenisite.resizer2_PARTNER_URI");
		return true;
	}

	public function DoInstall()
	{
		$this->InstallFiles();
		$this->InstallDB();
		$this->InstallEvents();
		RegisterModule($this->MODULE_ID);
		LocalRedirect('/bitrix/admin/yci_resizer2_f1.php?lang=ru');
	}

	public function InstallDB()
	{
		global $DB, $APPLICATION;

		$this->errors = false;
		$this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/" . $this->MODULE_ID . "/install/db/" . strtolower($DB->type) . "/install.sql");
		if ($this->errors !== false) {
			$APPLICATION->ThrowException(implode("", $this->errors));
			return false;
		}

		$strSql = "INSERT INTO yen_resizer2_sets (NAME, w, h, q, wm, priority) VALUES ('".GetMessage("SET_BIG")."', 880, 500, 100, 'Y', 'FILL'), ('".GetMessage("SET_SMALL")."', 400, 400, 100, 'Y', 'FILL'), ('".GetMessage("SET_ICON")."', 150, 150, 100, 'N', 'FILL'), ('".GetMessage("SET_LIST")."', 200, 200, 100, 'N', 'FILL'),  ('".GetMessage("SET_TABLE")."', 50, 50, 100, 'N', 'FILL'),  ('".GetMessage("SET_DETAIL_ICON")."', 58, 58, 100, 'N', 'FILL'),  ('".GetMessage("SET_ZOOM")."', 600, 600, 100, 'Y', 'FILL');";
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);

		$strSql = "INSERT INTO yen_resizer2_settings (NAME, VALUE) VALUES ('text', '".COption::GetOptionString("main", "server_name", "watermark")."'), ('color', '#000000'), ('place_v', 'center'), ('place_h', 'center'), ('left_margin', '0'), ('right_margin', '0'), ('top_margin', '0'), ('bottom_margin', '0'), ('opacity', '95'), ('fs', '70'), ('angle', '0'), ('font_family', 'arial.ttf'), ('fill', '#ffffff'), ('image', '');";
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);

		////////////////vstavka zaglushki///////////////////
		$arNoPhoto=CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"]."/upload/resizer2/no_photo.gif");
		$fid = CFile::SaveFile($arNoPhoto, "resizer2");
		$strSql = "DELETE FROM yen_resizer2_settings WHERE NAME='no_image'";
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
		$strSql = "INSERT INTO yen_resizer2_settings(NAME, VALUE)  VALUES('no_image', {$fid})";
		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
		///////////////////////////////////////////////////

		return true;
	}

	public function InstallFiles()
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.resizer2/install/public_html/", $_SERVER["DOCUMENT_ROOT"]."/", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.resizer2/install/bitrix/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.resizer2/install/bitrix/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.resizer2/install/bitrix/images", $_SERVER["DOCUMENT_ROOT"]."/bitrix/images", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.resizer2/install/bitrix/themes", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes", true, true);
	}

	public function InstallEvents()
	{
		UnRegisterModuleDependences("main", "OnPageStart", "yenisite.resizer2", "CResizer2", "ShowResizer2Head");
		RegisterModuleDependences("main", "OnPageStart", "yenisite.resizer2", "CResizer2", "ShowResizer2Head", "10");

		UnRegisterModuleDependences("main", "OnEndBufferContent", "yenisite.resizer2", "CResizer2", "replaceResizer2Content");
		RegisterModuleDependences("main", "OnEndBufferContent", "yenisite.resizer2", "CResizer2", "replaceResizer2Content", "100");

		//for OLD html redactor
		UnRegisterModuleDependences("fileman", "OnBeforeHTMLEditorScriptsGet", "yenisite.resizer2", "CResizer2", "HTMLEditorButton");
		RegisterModuleDependences("fileman", "OnBeforeHTMLEditorScriptsGet", "yenisite.resizer2", "CResizer2", "HTMLEditorButton", "100");

		//for NEW html redactor
		UnRegisterModuleDependences("fileman", "OnBeforeHTMLEditorScriptRuns", "yenisite.resizer2", "CResizer2", "HTMLEditorButton");
		RegisterModuleDependences("fileman", "OnBeforeHTMLEditorScriptRuns", "yenisite.resizer2", "CResizer2", "HTMLEditorButton", "100");
	}

	public function DoUninstall()
	{
		UnRegisterModuleDependences("fileman", "OnBeforeHTMLEditorScriptsGet", "yenisite.resizer2", "CResizer2", "HTMLEditorButton");
		UnRegisterModuleDependences("main", "OnEndBufferContent", "yenisite.resizer2", "CResizer2", "replaceResizer2Content");
		UnRegisterModuleDependences("main", "OnPageStart", "yenisite.resizer2", "CResizer2", "ShowResizer2Head");

		global $DB;
		//if (!$arParams['savedata']) {
			$this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/" . $this->MODULE_ID . "/install/db/" . strtolower($DB->type) . "/uninstall.sql");
		//}

		if (!empty($this->errors)) {
			$APPLICATION->ThrowException(implode("", $this->errors));
			return false;
		}
		UnRegisterModule($this->MODULE_ID);

		DeleteDirFilesEx("/bitrix/components/yenisite/resizer2.box");
		DeleteDirFilesEx("/bitrix/components/yenisite/resizer2.gallery");
		DeleteDirFilesEx("/yenisite.resizer2");

		return true;
	}
}
?>
