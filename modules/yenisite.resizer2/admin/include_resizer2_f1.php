<?
define("ADMIN_MODULE_NAME", "yenisite.market");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
IncludeModuleLangFile(__FILE__);
CModule::IncludeModule("iblock");
CModule::IncludeModule("yenisite.resizer2");

global $USER;
if(!$USER->IsAdmin())
	return;




$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("YEN_TAB_1_NAME"), "TITLE" => GetMessage("YEN_TAB_1"))
	
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
?>

                    
<?
echo GetMessage("TEXT");
?>


<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>