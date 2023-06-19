<?

class yenisite_catchbuy extends CModule {
	var $MODULE_ID = "yenisite.catchbuy";
	var $PREFIX;
	var $VENDOR;
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_COMPONENTS;

	private $errors;

	function __construct() {
		global $MOD_PREFIX;
		$arClass = explode('.', $this->MODULE_ID);
		$this->PREFIX = $MOD_PREFIX = $arClass[1];
		$this->VENDOR = $VENDOR = $arClass[0];
		IncludeModuleLangFile(__FILE__);
		$arModuleVersion = array();
		include(dirname(__FILE__) . "/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->PARTNER_NAME = GetMessage("RZ_VENDOR_NAME");
		$this->PARTNER_URI = 'http://romza.ru/';
		$this->MODULE_NAME = GetMessage($MOD_PREFIX . "_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage($MOD_PREFIX . "_MODULE_DESC");
		$this->MODULE_COMPONENTS = array(
			'yenisite:catchbuy.list'
		);
	}

	function InstallEvents() {
		RegisterModuleDependences('sale', 'OnSaleOrderSaved', $this->MODULE_ID, 'Yenisite\Catchbuy\Events', 'OnOrderAddHandler', 10000);
		RegisterModuleDependences('sale', 'OnBeforeSaleBasketItemSetField', $this->MODULE_ID, 'Yenisite\Catchbuy\Events', 'OnBeforeSaleBasketItemSetFieldHandler', 100);
		return true;
	}

	function UnInstallEvents() {
		UnRegisterModuleDependences('sale', 'OnSaleOrderSaved', $this->MODULE_ID, 'Yenisite\Catchbuy\Events', 'OnOrderAddHandler', '',array());
		UnRegisterModuleDependences('sale', 'OnBeforeSaleBasketItemSetField', $this->MODULE_ID, 'Yenisite\Catchbuy\Events', 'OnBeforeSaleBasketItemSetFieldHandler', '',array());
		return true;
	}

	function InstallFiles($arParams = array()) {
		CopyDirFiles(__DIR__ . '/admin/', $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/admin/', $ReWrite = True, $Recursive = True);
		CopyDirFiles(__DIR__ . '/components/', $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/components/', $ReWrite = True, $Recursive = True);
		CopyDirFiles(__DIR__ . '/themes/', $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/themes/', $ReWrite = True, $Recursive = True);
	}

	function InstallDB() {
		global $DB, $APPLICATION, $MODULE_ID;

		$this->errors = false;
		$this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/" . $this->MODULE_ID . "/install/db/" . strtolower($DB->type) . "/install.sql");
		if ($this->errors !== false) {
			$APPLICATION->ThrowException(implode("", $this->errors));
			return false;
		}
		$MODULE_ID = $this->MODULE_ID;
		require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/tasks/install.php");
		return true;
	}

	function UnInstallDB($arParams = array()) {
		global $APPLICATION, $DB, $MODULE_ID;
		if (!$arParams['savedata']) {
			$this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/" . $this->MODULE_ID . "/install/db/" . strtolower($DB->type) . "/uninstall.sql");
		}

		if (!empty($this->errors)) {
			$APPLICATION->ThrowException(implode("", $this->errors));
			return false;
		}
		$MODULE_ID = $this->MODULE_ID;
		require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $this->MODULE_ID . "/install/tasks/uninstall.php");
		return true;
	}

	function InstallDemo() {
		global $APPLICATION;
		\Bitrix\Main\Loader::includeModule($this->MODULE_ID);
		\Bitrix\Main\Loader::includeModule('catalog');
		\Yenisite\Catchbuy\Catchbuy::add(array(
			'PRODUCT_ID' => 4495,
			'DISCOUNT_ID' => 1,
			'ACTIVE' => 'Y',
			'MAX_USES' => 10,
			'COUNT_USES' => 0,
			'LID' => 's1'
		));
		\Yenisite\Catchbuy\Catchbuy::add(array(
			'PRODUCT_ID' => 4496,
			'DISCOUNT_ID' => 1,
			'MAX_USES' => 7,
			'COUNT_USES' => 2,
			'LID' => 's2'
		));
		\Yenisite\Catchbuy\Catchbuy::add(array(
			'PRODUCT_ID' => 4497,
			'DISCOUNT_ID' => 0,
			'ACTIVE' => 'Y',
			'MAX_USES' => 10,
			'COUNT_USES' => 10,
			'LID' => 's1'
		));
		$arErr = \Yenisite\Catchbuy\Catchbuy::getErrors();
		if (count($arErr) > 0) {
			$APPLICATION->ThrowException(implode('<br>', $arErr));
			return false;
		}
		return true;
	}

	function UnInstallFiles() {
		DeleteDirFilesEx('/bitrix/themes/.default/icons/' . $this->MODULE_ID);
		DeleteDirFilesEx('/bitrix/themes/.default/icons/' . $this->MODULE_ID . '.css');
		if (isset($this->MODULE_COMPONENTS) && is_array($this->MODULE_COMPONENTS)) {
			foreach ($this->MODULE_COMPONENTS as $cmpString) {
				DeleteDirFilesEx('/bitrix/components/' . str_replace(':', '/', $cmpString));
			}
		}
		//DeleteDirFilesEx('/bitrix/js/' . $this->MODULE_ID);
	}

	function DoInstall() {
		RegisterModule($this->MODULE_ID);
		$this->InstallEvents();
		$this->InstallFiles();
		$this->InstallDB();
		if (false && !$this->InstallDemo()) {
			self::DoUninstall();
			return false;
		};
		return true;
	}

	function DoUninstall() {
		//global $APPLICATION;
		UnRegisterModule($this->MODULE_ID);
		$this->UnInstallEvents();
		$this->UnInstallFiles();
		$this->UnInstallDB();
	}
}