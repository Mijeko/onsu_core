<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

IncludeModuleLangFile(__FILE__);
CModule::IncludeModule("iblock");
CModule::IncludeModule("yenisite.resizer2");

global $USER;
if(!$USER->IsAdmin())
	return;

$action = htmlspecialchars($_REQUEST["action"]);
$id = htmlspecialchars($_REQUEST["ID"]);
if($action == 'delete' && $id > 0)
{
    global $DB;
    $strSql = "DELETE FROM yen_resizer2_sets WHERE id=".$id;
    $dbResultList = $DB->Query($strSql, false, $err_mess.__LINE__);
}




if(!isset($_REQUEST["by"]))
{
    $by = "ID";
    $sort = "asc";
}
else
{
    $by = $_REQUEST["by"];
    $sort = $_REQUEST["sort"];
}


$sTableID = "resizer2_set_list";
$oSort = new CAdminSorting($sTableID, "id", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilterFields = array();
$lAdmin->InitFilter($arFilterFields);
$arFilter = array();

global $DB;
$strSql = "SELECT * FROM yen_resizer2_sets ORDER BY ".$by." ".$order;
$dbResultList = $DB->Query($strSql, false, $err_mess.__LINE__);

$dbResultList = new CAdminResult($dbResultList, $sTableID);
$dbResultList->NavStart();
$lAdmin->NavText($dbResultList->GetNavPrint(GetMessage('SET')));

$lAdmin->AddHeaders(
        array(
            array(
                "id"    =>"ID",
                "content"  => GetMessage('ID'),
                "sort"     =>"id",
                "default"  =>true,
              ),
            array(
                "id"    =>"NAME",
                "content"  => GetMessage('NAME'),
                "sort"     =>"NAME",
                "default"  =>true,
		
              ),
	    array(
                "id"    =>"WIDTH",
                "content"  => GetMessage('WIDTH'),
				"sort"     =>"w",
                "default"  =>true,
              ),
	    array(
                "id"    =>"HEIGHT",
                "content"  => GetMessage('HEIGHT'),
				"sort"     =>"h",
                "default"  =>true,

              ),	    
	    array(
                "id"    =>"Q",
                "content"  => GetMessage('Q'),
				"sort"     =>"q",
                "default"  =>true,		
              ),
	    array(
                "id"    =>"WM",
                "content"  => GetMessage('WM'),
				"sort"     =>"wm",
                "default"  =>true,
		
              ),
	    array(
                "id"    =>"PRIORITY",
                "content"  => GetMessage('PRIORITY'),
				"sort"     =>"priority",
                "default"  =>true,
		
              ),
	    array(
                "id"    =>"CONV",
                "content"  => GetMessage('CONVERSION_FORMAT'),
				"sort"     =>"conv",
                "default"  =>true,
		
              ),   
	    

        )
);

while($prof = $dbResultList->GetNext())
{
    $arActions = Array();
    $arActions[] = array("SEPARATOR" => true);
    $arActions[] = array("ICON"=>"delete", "TEXT"=>GetMessage('DELETE'), "ACTION"=>"if(confirm('".GetMessage('DELETE_STATUS_CONFIRM')."')) ".$lAdmin->ActionDoGroup($prof["id"], "delete"));
    $arActions[] = array("ICON"=>"edit", "TEXT"=>GetMessage('EDIT'),  "ACTION"=>$lAdmin->ActionRedirect("yci_resizer2_set_edit.php?id=".$prof["id"]."&action=edit&lang=".LANG.""));    
    

    
    $row =& $lAdmin->AddRow(1, array(
				     "ID"=>$prof["id"],
				     "NAME"=>$prof["NAME"],
				     "WIDTH"=>$prof["w"],
				     "HEIGHT"=>$prof["h"],
				     "Q"=>$prof["q"],
				     "WM"=>$prof["wm"]==Y?GetMessage('YES'):GetMessage('NO'),
				     "PRIORITY"=>GetMessage($prof["priority"]),
					 "CONV"=>$prof["conv"],
				     
				    ), "yci_resizer2_set_edit.php?id=".$prof["id"]."&action=edit&lang=".LANG."");
    $row->AddActions($arActions);
}




$aContext = array(
		array(
			"TEXT" => GetMessage("ADD"),
			"ICON" => "btn_new",
			"LINK" => "yci_resizer2_set_edit.php?lang=".LANG,
			"TITLE" => GetMessage("ADD")
		),
	);
$lAdmin->AddAdminContextMenu($aContext, false, false);

?>

<?
    $lAdmin->CheckListMode();
    require($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_admin_after.php");
    $lAdmin->DisplayList();

?>


<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>