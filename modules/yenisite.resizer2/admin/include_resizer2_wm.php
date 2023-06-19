<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin.php");
// require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
// require_once($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_admin_after.php");

global $USER;
if(!$USER->IsAdmin())
	return;
if(!check_bitrix_sessid())
	return;
	
IncludeModuleLangFile(__FILE__);
CModule::IncludeModule("iblock");
CModule::IncludeModule("yenisite.resizer2");

	
$aTabs = array(
		array("DIV" => "edit1", "TAB" => GetMessage("SETTINGS"), "ICON" => "catalog", "TITLE" => "")
	);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();
$tabControl->BeginNextTab();
?>

<style>
table #prop td{padding: 5px;}
#wm {font-size: 100%; position: absolute; font-weight: bold; color: #ffffff;}
#wm2 { position: absolute; background: none;}
</style>

<?$APPLICATION->AddHeadScript("/yenisite.resizer2/js/jquery/jquery-1.6.1.min.js");?>
<?$APPLICATION->AddHeadScript("/yenisite.resizer2/js/colorpicker/js/colorpicker.js");?>
<?$APPLICATION->AddHeadScript("/yenisite.resizer2/js/ui/js/jquery-ui-1.8.14.custom.min.js");?>
<?$APPLICATION->SetAdditionalCSS("/yenisite.resizer2/js/colorpicker/css/colorpicker.css");?>
<?$APPLICATION->SetAdditionalCSS("/yenisite.resizer2/js/colorpicker/css/layout.css");?>
<?$APPLICATION->SetAdditionalCSS("/yenisite.resizer2/js/ui/css/ui-lightness/jquery-ui-1.8.14.custom.css");?>

<script type="text/javascript">

	function setWM(){

			$('#wm').html($('input[name*=text]').attr('value'));
			
			ml = $('input[name*=left_margin]').attr('value');
			mr = $('input[name*=right_margin]').attr('value');
			mt = $('input[name*=top_margin]').attr('value');
			mb = $('input[name*=bottom_margin]').attr('value');
			//ph = 'left';
			//pv = 'top';
			
			$('#wm').css('opacity',(120-$('input[name*=opacity]').attr('value'))/100);
			$('#wm2').css('opacity',(120-$('input[name*=opacity]').attr('value'))/100);
			
			$('input[name*=place_h]').each(
				function(){
					if($(this).attr('checked')){
						ph = $(this).attr('value');
					}
					
				}
			);
			
			$('input[name*=place_v]').each(
				function(){
					if($(this).attr('checked')){
						pv = $(this).attr('value');
					}
					
				}
			);
			
			
			
			switch(ph){
				case 'left':
					$('#wm').css('left', parseInt(ml) + 'px');
					$('#wm').css('right', '');
					$('#wm2').css('left', parseInt(ml) + 'px');
					$('#wm2').css('right', '');
					break;
				case 'right':
					$('#wm').css('right',parseInt(mr) + 'px');
					$('#wm').css('left', '');
					$('#wm2').css('right',parseInt(mr) + 'px');
					$('#wm2').css('left', '');
					break;
				case 'center':
					left = $('#wm').parent().width()/2 - $('#wm').width()/2;					
					$('#wm').css('left', left + 'px');
					left = $('#wm2').parent().width()/2 - $('#wm2').width()/2;					
					$('#wm2').css('left', left + 'px');
					break;
				
				
			}
			
			switch(pv){
				case 'top':
					$('#wm').css('top', parseInt(mt) + 'px');
					$('#wm').css('bottom', '');
					$('#wm2').css('top', parseInt(mt) + 'px');
					$('#wm2').css('bottom', '');
					break;
				case 'bottom':
					$('#wm').css('bottom', parseInt(mb) + 'px');
					$('#wm').css('top', '');
					$('#wm2').css('bottom', parseInt(mb) + 'px');
					$('#wm2').css('top', '');
					break;
				case 'center':
					top = $('#wm').parent().height()/2 - $('#wm').height()/2;					
					$('#wm').css('top', top + 'px');
					top = $('#wm2').parent().height()/2 - $('#wm2').height()/2;					
					$('#wm2').css('top', top + 'px');
					break;				
				
			}
			
			
			
	}

	$(document).ready(
		function(){
			
			
			$("#slider").slider({
			    min: 0,
			    max: 100,
			    values: [$('input[name*=opacity]').attr('value')],
			    slide: function(event, ui) {
				$('input[name*=opacity]').attr('value', parseInt(0)+parseInt(ui.values));
				$('#opacity').html(parseInt(0)+parseInt(ui.values));
				$('#wm').css('opacity', (120-ui.values)/100);
				$('#wm2').css('opacity', (120-ui.values)/100);
				
			    }
			});
			
			$("#slider2").slider({
			    min: 0,
			    max: 100,
			    values: [$('input[name*=fs]').attr('value')],
			    slide: function(event, ui) {
				$('input[name*=fs]').attr('value', parseInt(0)+parseInt(ui.values));
				$('#fs').html(ui.values + '%');
				$('#wm').css('font-size', ui.values + '%');
				setWM();
				
			    }
			});
			
			$("#slider3").slider({
			    min: 0,
			    max: 360,
			    values: [$('input[name*=angle]').attr('value')],
			    slide: function(event, ui) {
				$('input[name*=angle]').attr('value', parseInt(0)+parseInt(ui.values));
				$('#angle').html(ui.values + '');				
				setWM();
				
			    }
			});
			
			$('#opacity').html($('input[name*=opacity]').attr('value'));
			//$('#wm').css('opacity',$('input[name*=opacity]').attr('value')/100);
			//$('#wm2').css('opacity',$('input[name*=opacity]').attr('value')/100);
			
			$('#fs').html($('input[name*=fs]').attr('value') + '%');
			$('#wm').css('font-size',$('input[name*=fs]').attr('value') + '%');
			
			$('#angle').html($('input[name*=angle]').attr('value') + '');
			
			
			$('#colorSelector').ColorPicker(
				{			
					color: $('input[name*=color]').attr('value'),
					onShow: function (colpkr) {
						$(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						$(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						$('input[name*=color]').attr('value', '#' + hex);
						$('#wm').css('color', '#' + hex);
						$('#colorSelector div').css('background-color',  '#' + hex);
					}
				}
			);
			
			$('#colorSelectorFill').ColorPicker(
				{			
					color: $('input[name*=fill]').attr('value'),
					onShow: function (colpkr) {
						$(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						$(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						$('input[name*=fill]').attr('value', '#' + hex);
						$('#colorSelectorFill div').css('background-color',  '#' + hex);
					}
				}
			);
			
			$('#colorSelector div').css('background-color', $('input[name*=color]').attr('value'));
			$('#colorSelectorFill div').css('background-color', $('input[name*=fill]').attr('value'));
			
			$('#wm').css('color', $('input[name*=color]').attr('value'));			
			
			
			setWM();
			
			$('input[name*=text], input[name*=place_h],input[name*=place_v], input[name*=left_margin], input[name*=left_margin], input[name*=right_margin], input[name*=top_margin], input[name*=bottom_margin]').change(
				function(){
					setWM();
				}
			);
			
			
			
		}
	);
</script>
<? 

$id = $DB->ForSql($_REQUEST["id"]);
$apply = htmlspecialcharsEx($_REQUEST["apply"]);
$save = htmlspecialcharsEx($_REQUEST["save"]);
$clear = htmlspecialcharsEx($_REQUEST["clear"]);

if($apply || $save || $clear)
{
	if(is_numeric($id) && $id > 0)
	{
		CModule::IncludeModule('yenisite.resizer2');
		$arSet = CResizer2Set::GetByID($id);
		
		$wms = unserialize(base64_decode(trim($arSet['watermark_settings'])));

		if($wms['image'])
		{
			if(!$_POST['image_del'])
			{
				$str_IMAGE_ID = $wms['image'];
			}
			else
			{
				CFile::Delete($wms['image']); 
			}
		}

		$arrWMSettings = array();	

		foreach($_POST as $key => $value)
		{
			if($key == 'apply' || $key == 'image' || $key == 'save')
				continue;				
			$arrWMSettings[$key] = $value;
		}

		if($_FILES['image']['size'] > 0)
		{
			$str_IMAGE_ID = CFile::SaveFile($_FILES['image'], '/resizer2');
		}

		if($_FILES['no_image']['size'] > 0)
		{
			$str_IMAGE_ID_no_photo = CFile::SaveFile($_FILES['no_image'], '/resizer2');		
		}

		$arrWMSettings['image'] = $str_IMAGE_ID;
		$arrWMSettings['no_image'] = $str_IMAGE_ID_no_photo;
		
		$arrWMSettings = base64_encode(serialize($arrWMSettings));
		$arrWMSettings = $DB->ForSql($arrWMSettings);
		if(isset($clear) && !empty($clear)){				
			$strSql = "UPDATE yen_resizer2_sets SET watermark_settings='' where id=".$id;	
		}
		else
		{
			$strSql = "UPDATE yen_resizer2_sets SET watermark_settings='{$arrWMSettings}' where id=".$id;	
		}

		$res = $DB->Query($strSql, false, $err_mess.__LINE__);
		
		CResizer2Resize::ClearCacheByID($id);
		if($save)
			LocalRedirect("/bitrix/admin/yci_resizer2_set_edit.php?id=".intVal($id)."&action=edit&".bitrix_sessid_get()."&lang=ru");
	}
	else
	{
		$arServiceParams = array('apply', 'save', 'sessid', 'id');
		$arFilesParams = array('image', 'no_image');
		
		$arParams = CResizer2Settings::GetSettings();
		foreach($arParams as $name => $oldValue)
		{
			if(in_array($name, $arServiceParams))
			{
				CResizer2Settings::DeleteSettingByName($name);
				continue;
			}
			
			switch($name)
			{
				case 'image':
					if($oldValue)
					{
						if(!$_POST['image_del'])
						{		
							$str_IMAGE_ID = $oldValue;
						}
						else
						{
							CFile::Delete($oldValue);
							$str_IMAGE_ID = false;
							CResizer2Settings::SetSettingByName($name,$str_IMAGE_ID);
						}
					}
				break;
				
				case 'no_image':
					if($oldValue)
					{
						if(!$_POST['no_image_del'])
						{		
							$str_IMAGE_ID_no_photo = $oldValue;
						}
						else
						{
							CFile::Delete($oldValue);
							$str_IMAGE_ID_no_photo = false;
							CResizer2Settings::SetSettingByName($name,$str_IMAGE_ID_no_photo);
						}
					}
				break;
				
				default:
					if(!isset($_POST[$name]))
					{
						$newValue = false;
					}
					else
					{
						$newValue = $_POST[$name];
						$newValue = $DB->ForSql($newValue);
					}
					CResizer2Settings::SetSettingByName($name,$newValue);
				break;
			}
			
			unset($_POST[$name]);
		}
		
		// for new params (there are not in the table)
		foreach($_POST as $name => $newValue)
		{
			if(in_array($name, $arServiceParams)) continue;
			
			$name = $DB->ForSql($name);
			$newValue = $DB->ForSql($newValue);
			
			CResizer2Settings::SetSettingByName($name,$newValue);
		}
		
		// for new files params (there are not in the table)
		foreach($_FILES as $name => $newValue)
		{
			if(!in_array($name, $arFilesParams)) continue;
			if($newValue['size'] > 0)
			{
				$newValue = CFile::SaveFile($newValue, '/resizer2');
				$newValue = $DB->ForSql($newValue);
				if(!empty($newValue))
				{
					CResizer2Settings::SetSettingByName($name,$newValue);
				}
			}
		}
	}	
}

$strSql = "SELECT * FROM yen_resizer2_settings";	
$res = $DB->Query($strSql, false, $err_mess.__LINE__);	
while($setting = $res->GetNext())
{	
	$arResult[$setting['NAME']] = $setting['VALUE'];
}

$str_IMAGE_ID = $arResult['image'];
$str_IMAGE_ID_no_photo = $arResult['no_image'];

if(is_numeric($id) && $id > 0)
{
	CModule::IncludeModule('yenisite.resizer2');
	$arSet = CResizer2Set::GetByID($id);
	if($arSet['watermark_settings'])
	{
		$wms = unserialize(base64_decode(trim($arSet['watermark_settings'])));
		foreach($wms as $k=>$w){
			$arResult[$k] = $w;
		}
		$str_IMAGE_ID = $arResult['image'];
		$str_IMAGE_ID_no_photo = $arResult['no_image'];		
	}
	else
	{	
		$arResult["text"] ="";
		$str_IMAGE_ID = "";
		$str_IMAGE_ID_no_photo = "";		
	}	
}
?>
<form method="POST" ENCTYPE="multipart/form-data">
<table class="edit-table">
<?=bitrix_sessid_post();?>
<input type="hidden" value="<?=$id?>" name="id" />
<?if($id > 0):?>
		<tr>
			<td colspan='2' align='center' width="100%"><input type="submit" name="clear" value="<?=GetMessage('DROP_SETTINGS')?>" /></td>				
		</tr>
<?endif?>		

		<tr>
			<td colspan='2' align='center' style='background: #E7EAF5; font-weight: bold;' width="100%"><? echo GetMessage("WM")?></td>
				
		</tr>
                <tr>
			
			<td colspan='2' width="100%" style='border: 1px solid #E7EAF5;' align='left'>
				<table width='50%' id='prop' style='float: left;'>
					<tr><td width='50%' align='right'><? echo GetMessage("TEXT")?></td><td width='50%' align='left'><input type='text' name='text' value='<?=$arResult['text']?>'/></td></tr>
					
					<tr><td width='50%' align='right'><? echo GetMessage("COLOR")?></td><td width='50%' align='left'>
						<div id='colorSelector'><div style="background-color: #0000ff"></div></div>
						<input style='display: none;' type='text' name='color' value='<?=$arResult['color']?>'/>
					</td></tr>
					
					<tr><td width='50%' align='right'><? echo GetMessage("IMAGE")?></td><td width='50%' align='left'>
						
						<?
							echo CFile::InputFile("image", array("IMAGE" => "Y", "PATH" => "Y", "FILE_SIZE" => "Y", "DIMENSIONS" => "Y",
						"IMAGE_POPUP"=>"Y"), $str_IMAGE_ID);

							if (strlen($str_IMAGE_ID)>0):
						?>
						<br>
						<?
							echo CFile::ShowImage($str_IMAGE_ID, 100, 100, "border=0", "", true);
							endif;
						?>
						
					</td></tr>
					

					
					<tr><td width='50%' align='right'><? echo GetMessage("PLACE_VERTICAL")?></td><td width='50%' align='left'>
					<? if($arResult['place_v'] == 'top') $ts = "checked='checked'"; ?>
					<? if($arResult['place_v'] == 'center') $cs = "checked='checked'"; ?>
					<? if($arResult['place_v'] == 'bottom') $bs = "checked='checked'"; ?>
						<input type='radio' name='place_v' value='top' <?=$ts?>  /><? echo GetMessage("PTOP")?><br/>
						<input type='radio' name='place_v' value='center' <?=$cs?>  /><? echo GetMessage("PCENTER")?><br/>
						<input type='radio' name='place_v' value='bottom' <?=$bs?> /><? echo GetMessage("PBOTTOM")?><br/>
					</td></tr>
					
					<tr><td width='50%' align='right'><? echo GetMessage("PLACE_HORIZONTAL")?></td><td width='50%' align='left'>
					<? if($arResult['place_h'] == 'left') $ls = "checked='checked'"; ?>
					<? if($arResult['place_h'] == 'center') $cs2 = "checked='checked'"; ?>
					<? if($arResult['place_h'] == 'right') $rs = "checked='checked'"; ?>
						<input type='radio' name='place_h' value='left' <?=$ls?> /><? echo GetMessage("PLEFT")?><br/>
						<input type='radio' name='place_h' value='center' <?=$cs2?> /><? echo GetMessage("PCENTER")?><br/>
						<input type='radio' name='place_h' value='right' <?=$rs?>/><? echo GetMessage("PRIGHT")?><br/>
					</td></tr>
					
					<tr><td width='50%' align='right'><? echo GetMessage("LEFT")?></td><td width='50%' align='left'><input type='text' name='left_margin' value='<?=$arResult['left_margin']?>'/></td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("RIGHT")?></td><td width='50%' align='left'><input type='text' name='right_margin' value='<?=$arResult['right_margin']?>'/></td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("TOP")?></td><td width='50%' align='left'><input type='text' name='top_margin' value='<?=$arResult['top_margin']?>'/></td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("BOTTOM")?></td><td width='50%' align='left'><input type='text' name='bottom_margin' value='<?=$arResult['bottom_margin']?>'/></td></tr>					
					
					

					<tr><td width='50%' align='right'> <? echo GetMessage("OPACITY")?> [<b><span id='opacity'></span></b>]</td><td width='50%' align='left'>
						<div id='slider'></div>
						<input style='display: none;' type='text' name='opacity' value='<?=$arResult['opacity']?>'/>
					</td></tr>
					
					<tr><td width='50%' align='right'> <? echo GetMessage("FONT_SIZE")?> [<b><span id='fs'></span></b>]</td><td width='50%' align='left'>
						<div id='slider2'></div>
						<input style='display: none;' type='text' name='fs' value='<?=$arResult['fs']?>'/>
					</td></tr>
					
					<tr><td width='50%' align='right'> <? echo GetMessage("ANGLE")?> [<b><span id='angle'></span></b>]</td><td width='50%' align='left'>
						<div id='slider3'></div>
						<input style='display: none;' type='text' name='angle' value='<?=$arResult['angle']?>''
					</td></tr>
					
					<tr><td width='50%' align='right'><? echo GetMessage("FONT")?></td><td width='50%' align='left'>
					
					<select name='font_family'>
					<?					
						$fonts = CResizer2Settings::GetFontList();
						foreach($fonts as $font):
							if($font == $arResult['font_family'])
								$selected = " selected='selected' ";
							else $selected = "";
					?>
						<option <?=$selected?> value='<?=$font?>'><?=$font?></option>
					<? endforeach; ?>
					</select>
					</td></tr>
					
					
					
				</table>
				
				<table width='50%' cellpadding='15' >
					<tr><td width='100%' align='center'>
					<? CResizer2Resize::ClearImgCache('/yenisite.resizer2/image.jpg', $id?$id:0);?>
					<!--
					<p style='width: 400px; margin: 10px auto; text-align: center; font-size: 16px;'><? echo GetMessage("NOTICE")?></p>
					<div style="z-index: 999; overflow: hidden; font-size: 66px; position: relative; border: #aaaaaa; height: 600px; width: 400px; margin: 20px auto; background: url('/bitrix/modules/yenisite.resizer2/admin/image.jpg'); ">
						<?if(!$str_IMAGE_ID): ?>
							<span style='z-index: 990' id='wm'>yenisite.ru</span>
						<?else: ?>
							<img id='wm2' src='<?=CFile::GetPath($str_IMAGE_ID);?>' />
						<?endif?>
					</div>
					-->
					
					<img src='<?=CResizer2Resize::ResizeGD2('/yenisite.resizer2/image.jpg', $id?$id:0);?>?i=<?=date('d-m-Y-i-s-h');?>' />
					
					</td></tr>
					
				</table>

				
			</td>
			
		</tr>
<?
// ################################
// ###		COMMON SETTINGS		###
// ################################
?>
<?if($id <= 0):?>	
		<tr >
			<td colspan='2' align='center' style='background: #E7EAF5; font-weight: bold;' width="100%"><? echo GetMessage("COMMON")?></td>
				
		</tr>
		<tr>
			<td colspan='2' width="100%" style='border: 1px solid #E7EAF5;' align='left'>
				<table width='100%' id='prop' style='float: left;'>				
					<tr><td width='50%' align='right'><?=GetMessage("NOT_INCREASE")?></td><td width='50%' align='left'>
						<? $checked = ($arResult['not_increase']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='not_increase' value='Y'/> 
					</td></tr>
				</table>
			</td>
		</tr>

		<tr><td colspan='2' width="100%" style='border: 1px solid #E7EAF5;' align='left'>
			<table width='100%' id='prop' style='float: left;'>									
				


				<tr><td width='50%' align='right'><? echo GetMessage("NO_IMAGE")?></td><td width='50%' align='left'>
					
		<?
			echo CFile::InputFile("no_image", array("IMAGE" => "Y", "PATH" => "Y", "FILE_SIZE" => "Y", "DIMENSIONS" => "Y",
			"IMAGE_POPUP"=>"Y"), $str_IMAGE_ID_no_photo);

			if (strlen($str_IMAGE_ID_no_photo)>0):
		?>
			<br>
		<?
			echo CFile::ShowImage($str_IMAGE_ID_no_photo, 100, 100, "border=0", "", true);
			endif;
		?>
					</td></tr>
					
					
				</table>
					
		</td></tr>


		
		 <tr>
			
			<td colspan='2' width="100%" style='border: 1px solid #E7EAF5;' align='left'>
				<table width='100%' id='prop' style='float: left;'>				
					
					<tr><td width='50%' align='right'><? echo GetMessage("COLOR_FILL")?></td><td width='50%' align='left'>
						<div id='colorSelectorFill' class='colorsel'><div style="background-color: #000000"></div></div>
						<input style='display: none;' type='text' name='fill' value='<?=$arResult['fill']?>'/>
					</td></tr>
				</table>

				
			</td>
			
		</tr>
		
		
		<tr >
			<td colspan='2' align='center' style='background: #E7EAF5; font-weight: bold;' width="100%"><? echo GetMessage("LINKED")?></td>
				
		</tr>
		
		 <tr>
			
			<td colspan='2' width="100%" style='border: 1px solid #E7EAF5;' align='left'>
				<table width='100%' id='prop' style='float: left;'>				
					
					<tr><td width='50%' align='right'><? echo GetMessage("JQUERY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['jquery']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='jquery' value='Y'/> jQuery
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['fancy']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='fancy' value='Y'/> FancyBox
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['lightbox']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='lightbox' value='Y'/> LightBox
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['nflightbox']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='nflightbox' value='Y'/> NFLightBox
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['pretty']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='pretty' value='Y'/> PrettyPhoto
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['nyroModal']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='nyroModal' value='Y'/> NyroModal
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['thickbox']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='thickbox' value='Y'/> ThickBox
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['windy']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='windy' value='Y'/> Windy
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['easyzoom']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='easyzoom' value='Y'/> Easyzoom
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['highslide']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='highslide' value='Y'/> Highslide
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['carousellite']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='carousellite' value='Y'/> jCarousel Lite
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['shadowbox']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='shadowbox' value='Y'/> ShadowBox
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['colorbox']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='colorbox' value='Y'/> ColorBox
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['skitter']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='skitter' value='Y'/> Skitter Slideshow
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['cloudzoom']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='cloudzoom' value='Y'/> CloudZoom
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['zoomy']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='zoomy' value='Y'/> Zoomy
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['ad']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='ad' value='Y'/> AD Gallery
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['hoverpulse']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='hoverpulse' value='Y'/> HoverPulse
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['pika']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='pika' value='Y'/> Pikachoose slider
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['coin']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='coin' value='Y'/> Coin Slider
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['spacegallery']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='spacegallery' value='Y'/> Space Gallery
					</td></tr>
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['pirobox']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='pirobox' value='Y'/> PiroBox
					</td></tr>
					
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['elevateZoom']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='elevateZoom' value='Y'/> elevateZoom
					</td></tr>
                                        
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['jQZoom']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='jQZoom' value='Y'/> jQZoom
					</td></tr>
                                        
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['fancyZoom']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='fancyZoom' value='Y'/> fancyZoom
					</td></tr>
                                        
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['zoom']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='zoom' value='Y'/> zoom
					</td></tr>
                                        
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['superbox']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='superbox' value='Y'/> superbox
					</td></tr>
                                        
					<tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
					<? $checked = ($arResult['imageZoomer']=='Y')?"checked='checked'":""; ?>
						<input <?=$checked?> type='checkbox' name='imageZoomer' value='Y'/> Featured Image Zoomer
					</td></tr>
                                        
                    <tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
                    <? $checked = ($arResult['blogslideshow']=='Y')?"checked='checked'":""; ?>
                        <input <?=$checked?> type='checkbox' name='blogslideshow' value='Y'/> BlogSlideShow
                    </td></tr>
                                        
                    <tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
                    <? $checked = ($arResult['powerzoomer']=='Y')?"checked='checked'":""; ?>
                        <input <?=$checked?> type='checkbox' name='powerzoomer' value='Y'/> Image Power Zoomer
                    </td></tr>
                                        
                    <tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
                    <? $checked = ($arResult['camera']=='Y')?"checked='checked'":""; ?>
                        <input <?=$checked?> type='checkbox' name='camera' value='Y'/> Camera
                    </td></tr>
                                        
                    <tr><td width='50%' align='right'><? echo GetMessage("FANCY")?></td><td width='50%' align='left'>
                    <? $checked = ($arResult['galleria']=='Y')?"checked='checked'":""; ?>
                        <input <?=$checked?> type='checkbox' name='galleria' value='Y'/> Galleria
                    </td></tr>
				</table>

				
			</td>
			
		</tr>
<?endif?>

<?
if(is_numeric($id) && $id > 0)
	$back_url = "/bitrix/admin/yci_resizer2_set_edit.php?id=".intVal($id)."&action=edit&".bitrix_sessid_get()."&lang=ru";
else
	$back_url = "/bitrix/admin/yci_resizer2_wm.php?".bitrix_sessid_get()."&lang=".LANG;
$tabControl->Buttons(
                    array(
                        "back_url" => $back_url
                    )
	);
?>
</table>
</form>
<?
$tabControl->End();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>
