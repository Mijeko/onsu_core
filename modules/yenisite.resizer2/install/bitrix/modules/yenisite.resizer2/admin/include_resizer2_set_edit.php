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
$id = htmlspecialchars($_REQUEST["id"]);
$name = htmlspecialchars($_REQUEST["name"]);
$w = htmlspecialchars($_REQUEST["w"]);
$h = htmlspecialchars($_REQUEST["h"]);
$q = htmlspecialchars($_REQUEST["q"]);
$wm = htmlspecialchars($_REQUEST["wm"]);
$priority = htmlspecialchars($_REQUEST["priority"]);
$conv = htmlspecialchars($_REQUEST["conv"]);
$apply = htmlspecialchars($_REQUEST["apply"]);
$save = htmlspecialchars($_REQUEST["save"]);


if($action=='edit' && $id>0 && ($apply || $save))
{
    CResizer2Set::Update($id, $name, $w, $h, $q, $wm, $priority, $conv);
    CResizer2Resize::ClearCacheByID($id);
}

if($action=='add' && ($apply || $save))
{   
    CResizer2Set::Add($name, $w, $h, $q, $wm, $priority);   
}

if($save) Localredirect("/bitrix/admin/yci_resizer2_sets.php?lang=".LANG."");


$aMenu = array();

$aMenu[] = array(
		"TEXT" => GetMessage("LIST"),
		"ICON" => "btn_list",
		"LINK" => "/bitrix/admin/yci_resizer2_sets.php?lang=".LANG."",
            );


$aMenu[] = array(
		"TEXT" => GetMessage("ADD"),
		"ICON" => "btn_new",
		"LINK" => "/bitrix/admin/yci_resizer2_set_edit.php?lang=".LANG."&action=add",
            );


$context = new CAdminContextMenu($aMenu);
$context->Show();


$aTabs = array(
		array("DIV" => "edit1", "TAB" => GetMessage("SET"), "ICON" => "catalog", "TITLE" => "")
	);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();



$tabControl->BeginNextTab();
?>
<form method="POST">
<?if($id):?>
<?
    $set = CResizer2Set::GetByID($id);
?>

                <tr>
			<td width="40%">ID:</td>
			<td width="60%"><?echo $id;?></td>
			<input type="hidden" name="action" value="edit" />
		</tr>

<?else:?>
		<input type="hidden" name="action" value="add" />
<?endif?>
                


                <tr>
			<td width="40%"><?=GetMessage("NAME")?>:</td>
			<td width="60%"><input type="text" value="<?=$set["NAME"]?>" name="name"/></td>
		</tr>


                <tr>
			<td width="40%"><?=GetMessage("WIDTH")?>:</td>
			<td width="60%"><input type="text" value="<?=$set["w"]?>" name="w"/></td>
		</tr>
                
                <tr>
			<td width="40%"><?=GetMessage("HEIGHT")?>:</td>
			<td width="60%"><input type="text" value="<?=$set["h"]?>" name="h"/></td>
		</tr>
                
                <tr>
			<td width="40%"><?=GetMessage("Q")?>:</td>
			<td width="60%"><input type="text" value="<?=$set["q"]?>" name="q"/></td>
		</tr>

                <tr>
			<td width="40%"><?=GetMessage("WM")?>:</td>
			<td width="60%"><input type="checkbox" value="Y" <?=($set["wm"]=='Y')?"checked='checked'":"";?> name="wm"/> <a style="font-size: 14px;" href= "/bitrix/admin/yci_resizer2_wm.php?id=<?=$id?>&lang=<?=LANG?>"><?=GetMessage("WM_SETTINGS")?></a></td>
		</tr>
                <tr>
			<td width="40%"><?=GetMessage("PRIORITY")?>:</td>
			<td width="60%">                            
                             <select name="priority">   
								<!--OLD PRIORITY-----------------------------
                                <option value="WIDTH" <?=$set['priority']=='WIDTH'?"selected='selected'":"";?> ><?=GetMessage("WIDTH")?></option>
                                <option value="HEIGHT" <?=$set['priority']=='HEIGHT'?"selected='selected'":"";?> ><?=GetMessage("HEIGHT")?></option>
								-------------------------------------------->
                                <option value="FILL" <?=$set['priority']=='FILL'?"selected='selected'":"";?> ><?=GetMessage("FILL")?></option>
								<option value="FIT_LARGE" <?=$set['priority']=='FIT_LARGE'?"selected='selected'":"";?> ><?=GetMessage("FIT_LARGE")?></option> 
                                <option value="CROP" <?=$set['priority']=='CROP'?"selected='selected'":"";?> ><?=GetMessage("CROP")?></option> 
								<option value="CWIDTH" <?=$set['priority']=='CWIDTH'?"selected='selected'":"";?> ><?=GetMessage("CWIDTH")?></option>
                                <option value="CHEIGHT" <?=$set['priority']=='CHEIGHT'?"selected='selected'":"";?> ><?=GetMessage("CHEIGHT")?></option>  
								
                            </select>         
                            
                        </td>
		</tr>
		<tr>
			<td width="40%"><?=GetMessage("CONVERSION_FORMAT")?>:</td>
			<td width="60%">                            
                             <select name="conv"> 
								<option value=""  >---</option>
                                <option value="PNG" <?=$set['conv']=='PNG'?"selected='selected'":"";?> ><?=GetMessage("PNG")?></option>
                                <option value="JPG" <?=$set['conv']=='JPG'?"selected='selected'":"";?> ><?=GetMessage("JPG")?></option>
                                <option value="GIF" <?=$set['conv']=='GIF'?"selected='selected'":"";?> ><?=GetMessage("GIF")?></option>
                                
                            </select>         
                            
                        </td>
		</tr>
		 




<?
$tabControl->Buttons(
                    array(
                        "back_url" => "/bitrix/admin/yci_resizer2_sets.php?lang=".LANG.""
                    )
	);
?>
</form>

<?
$tabControl->End();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>