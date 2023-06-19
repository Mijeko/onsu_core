<?

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_admin_after.php");


IncludeModuleLangFile(__FILE__);
CModule::IncludeModule("iblock");
CModule::IncludeModule("yenisite.resizer2");

global $USER;
if(!$USER->IsAdmin())
	return;


$action = htmlspecialchars($_REQUEST["action"]);

if($action)
{
    $arSets = CResizer2Set::GetList();
    while($arr = $arSets->Fetch())
    {
	CResizer2Resize::ClearCacheByID($arr["id"]);
	echo GetMessage('DROP')." ".$arr[NAME]."<br/>";
    }
    
}

$aTabs = array(
		array("DIV" => "edit1", "TAB" => GetMessage("CACHE"), "ICON" => "catalog", "TITLE" => "")
	);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();



$tabControl->BeginNextTab();
?>
<form method='get'><br/>
	<input type='hidden' value='ru' name='lang'>
    <input type='submit' name='action' value='<?=GetMessage("CLEAR")?>' />
    <br/>
</form>

<?
$tabControl->End();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>
