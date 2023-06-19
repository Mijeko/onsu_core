<style type="text/css">
	.yenisite_iblocks{font-size: 12px; background: #ffffff; margin-left: 50px; margin-top: 50px;}
	.yenisite_iblocks td {border: 1px solid #cccccc; padding: 0;}
	.whiter {background: #ffffff important!;}
</style>

<?
global $USER;
if (!$USER->IsAdmin())
	return;

IncludeModuleLangFile(__FILE__);

CModule::IncludeModule('yenisite.resizer2');

$tabControl = new CAdminTabControl("tabControl", $aTabs);

if ($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults) > 0 && check_bitrix_sessid())
{
	if (strlen($RestoreDefaults)>0)
	{
		COption::RemoveOption("yenisite.resizer2");
	}
	else
	{		
		COption::SetOptionString("yenisite.resizer2", 'resize_class', $_REQUEST['resize_class'], GetMessage('RESIZE_CLASS'));
		COption::SetOptionString("yenisite.resizer2", 'resize_class_classname', $_REQUEST['resize_class_classname'], GetMessage('RESIZE_CLASS_CLASSNAME'));
		COption::SetOptionString("yenisite.resizer2", 'resize_class_set_small', $_REQUEST['resize_class_set_small'], GetMessage('RESIZE_CLASS_SET_SMALL'));
		COption::SetOptionString("yenisite.resizer2", 'resize_class_set_big', $_REQUEST['resize_class_set_big'], GetMessage('RESIZE_CLASS_SET_BIG'));
		
		COption::SetOptionString("yenisite.resizer2", 'resize_wm', $_REQUEST['resize_wm'], GetMessage('RESIZE_WH'));
		COption::SetOptionString("yenisite.resizer2", 'resize_wm_set', $_REQUEST['resize_wm_set'], GetMessage('RESIZE_WM_SET'));
		COption::SetOptionString("yenisite.resizer2", 'resize_compress', $_REQUEST['resize_compress'], GetMessage('RESIZE_COMPRESS'));
		COption::SetOptionString("yenisite.resizer2", 'resize_compress_set', $_REQUEST['resize_compress_set'], GetMessage('RESIZE_COMPRESS_SET'));
		COption::SetOptionString("yenisite.resizer2", 'resize_compress_property', $_REQUEST['resize_compress_property'], GetMessage('RESIZE_COMPRESS_PROPERTY'));
	}

	if (strlen($Update) > 0 && strlen($_REQUEST["back_url_settings"]) > 0)
	{
		LocalRedirect($_REQUEST["back_url_settings"]);
	}
	else
	{
		LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
	}
}

$resize_class = COption::GetOptionString('yenisite.resizer2', 'resize_class', '');
$resize_class_classname = COption::GetOptionString('yenisite.resizer2', 'resize_class_classname', 'r2buff');
$resize_class_set_small = COption::GetOptionString('yenisite.resizer2', 'resize_class_set_small', '');
$resize_class_set_big = COption::GetOptionString('yenisite.resizer2', 'resize_class_set_big', '');
$resize_wm = COption::GetOptionString('yenisite.resizer2', 'resize_wm', '');
$resize_wm_set = COption::GetOptionString('yenisite.resizer2', 'resize_wm_set', '');
$resize_compress = COption::GetOptionString('yenisite.resizer2', 'resize_compress', '');
$resize_compress_set = COption::GetOptionString('yenisite.resizer2', 'resize_compress_set', '');
$resize_compress_property = COption::GetOptionString('yenisite.resizer2', 'resize_compress_property', '');

$tabControl->Begin();
?>

<?

if($resize_compress == 'Y'):
RegisterModuleDependences("iblock", "OnAfterIBlockElementUpdate", "yenisite.resizer2", "CResizer2","CompressImages");
else:
UnRegisterModuleDependences("iblock", "OnAfterIBlockElementUpdate", "yenisite.resizer2", "CResizer2","CompressImages");		
endif;

?>
<?$tabControl->BeginNextTab();?>
<table width="100%">
	<form method="post" name="intr_opt_form" action="/bitrix/admin/settings.php?mid=<?=urlencode($mid)?>&amp;lang=<?echo LANGUAGE_ID?>">
		<?=bitrix_sessid_post();?>
		<tr>
			<td valign="middle" width="100%" colspan="2" style='' ><div class="buttons" style="width: 100%;"><?=GetMessage('RESIZE_CLASS_TITLE')?></div></td>			
		</tr>
		<tr>
			<td align="right" valign="middle" width="50%"><?=GetMessage('RESIZE_CLASS')?>: </td>
			<td valign="bottom" width="50%"><input style="width: 200px;"  <?if($resize_class == 'Y'):?>checked='checked'<?endif?> type="checkbox" name="resize_class" value="Y" /></td>
		</tr>
		<tr>
			<td align="right" valign="middle" width="50%"><?=GetMessage('RESIZE_CLASS_CLASSNAME')?>: </td>
			<td valign="bottom" width="50%"><input style="width: 200px;"  type="text" name="resize_class_classname" value="<?=htmlspecialchars($resize_class_classname);?>" /></td>
		</tr>
		<? $sets = CResizer2Set::GetList(); ?>		
		<tr>
			<td align="right" valign="middle" width="50%"><?=GetMessage('RESIZE_CLASS_SET_SMALL')?>: </td>
			<td valign="bottom" width="50%">
				<select name="resize_class_set_small">
						<option value="">---</option>
					<?while($set = $sets->Fetch()):?>
						<option <?if($resize_class_set_small==$set["id"]):?>selected="selected"<?endif?> value="<?=$set["id"]?>"><?=$set["NAME"]?></option>
					<?endwhile?>
				</select>
			</td>
		</tr>
		<? $sets = CResizer2Set::GetList(); ?>		
		<tr>
			<td align="right" valign="middle" width="50%"><?=GetMessage('RESIZE_CLASS_SET_BIG')?>: </td>
			<td valign="bottom" width="50%">
				<select name="resize_class_set_big">
						<option value="">---</option>
					<?while($set = $sets->Fetch()):?>
						<option <?if($resize_class_set_big==$set["id"]):?>selected="selected"<?endif?> value="<?=$set["id"]?>"><?=$set["NAME"]?></option>
					<?endwhile?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td valign="middle" width="100%" colspan="2" style='' ><div class="buttons" style="width: 100%;"><?=GetMessage('RESIZE_WM_TITLE')?></div></td>			
		</tr>
		
		<tr>
			<td align="right" valign="middle" width="50%"><?=GetMessage('RESIZE_WM')?>: </td>
			<td valign="bottom" width="50%"><input style="width: 200px;"  <?if($resize_wm == 'Y'):?>checked='checked'<?endif?> type="checkbox" name="resize_wm" value="Y" /></td>
		</tr>
		
		<? $sets = CResizer2Set::GetList(); ?>		
		<tr>
			<td align="right" valign="middle" width="50%"><?=GetMessage('RESIZE_WM_SET')?>: </td>
			<td valign="bottom" width="50%">
				<select name="resize_wm_set">
						<option value="">---</option>
					<?while($set = $sets->Fetch()):?>
						<option <?if($resize_wm_set==$set["id"]):?>selected="selected"<?endif?> value="<?=$set["id"]?>"><?=$set["NAME"]?></option>
					<?endwhile?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td valign="middle" width="100%" colspan="2" style='' ><div class="buttons" style="width: 100%;"><?=GetMessage('RESIZE_COMPRESS_TITLE')?></div></td>			
		</tr>
		
		
		<tr>
			<td align="right" valign="middle" width="50%"><?=GetMessage('RESIZE_COMPRESS')?>: </td>
			<td valign="bottom" width="50%"><input style="width: 200px;"  <?if($resize_compress == 'Y'):?>checked='checked'<?endif?> type="checkbox" name="resize_compress" value="Y" /></td>
		</tr>
		
		<? $sets = CResizer2Set::GetList(); ?>		
		<tr>
			<td align="right" valign="middle" width="50%"><?=GetMessage('RESIZE_COMPRESS_SET')?>: </td>
			<td valign="bottom" width="50%">
				<select name="resize_compress_set">
						<option value="">---</option>
					<?while($set = $sets->Fetch()):?>
						<option <?if($resize_compress_set==$set["id"]):?>selected="selected"<?endif?> value="<?=$set["id"]?>"><?=$set["NAME"]?></option>
					<?endwhile?>
				</select>
			</td>
		</tr>
		
		<tr>
			<td align="right" valign="middle" width="50%"><?=GetMessage('RESIZE_COMPRESS_PROPERTY')?>: </td>
			<td valign="bottom" width="50%"><input style="width: 200px;"  type="text" name="resize_compress_property" <?if(htmlspecialchars($resize_compress_property)):?>value="<?=htmlspecialchars($resize_compress_property);?>"<?else:?>value="MORE_PHOTO"<?endif;?> /></td>
		</tr>
		
		
		<?$tabControl->Buttons();?>
		<input type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>" />
		<input type="submit" name="Apply" value="<?=GetMessage("MAIN_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">	
	</form>
</table>

<?$tabControl->End();?>
