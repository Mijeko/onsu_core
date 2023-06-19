<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!CRZBitronic2Settings::isPro()) return;?>
<?//<a name="tb"></a> ??? WHAT IS IT ??? ?>
<a class="link" href="<?=$arParams["PATH_TO_LIST"]?>">
	<span class="text"><?=GetMessage("SPPA_RECORDS_LIST")?></span>
</a>

<?
if(strlen($arResult["ERROR_MESSAGE"])>0) {
	echo CRZBitronic2CatalogUtils::ShowMessage(Array("MESSAGE" => $arResult["ERROR_MESSAGE"], "TYPE" => "ERROR"));
}
?>

<?/* <pre><?print_r($arResult);?></pre> */?>
<?if($arResult['STEP'] == 'PERSON_TYPE'):?>

<form action="" method="post">
	<?foreach($arResult['HIDDEN'] as $hidden):?>
		<input type="hidden" name="<?=$hidden['NAME'];?>" value="<?=$hidden['VALUE'];?>"/>
	<?endforeach;?>
	<div class="user_profile_cont">
		
		<div class="user_profile">
			<div class="title-h2"><?=GetMessage("SPPA_PERSON_TYPE");?></div>
		<?foreach($arResult[$arResult['STEP']] as $res):?>
			<?switch($res['INPUT']['TYPE']):
				case 'RADIO':?>

			<p>
				<label class="radio-styled">
					<input type="radio" name="<?=$res['INPUT']['NAME']?>" value="<?=$res['INPUT']["VALUE"]?>"<?if($res['INPUT']["CHECKED"] == "Y") echo " checked"?> />
					<span class="radio-content">
						<span class="radio-fake"></span>
						<span class="text"><?=$res["NAME"]?></span>
					</span>
				</label>
			</p>
				<?break;?>
			<?endswitch;?>
		<?endforeach;?>
		 
		</div>
	</div>
	<input type="submit" value="<?=GetMessage("SPPA_NEXT_STEP");?>" class="btn-main">
</form>

<?elseif($arResult['STEP'] == 'ORDER_PROPS'):?>

<form method="post" action="<?=POST_FORM_ACTION_URI?>" class="form_account-settings">
	<?foreach($arResult['HIDDEN'] as $hidden):?>
	<input type="hidden" name="<?=$hidden['NAME'];?>" value="<?=$hidden['VALUE'];?>"/>
	<?endforeach;?>
	<input type="hidden" name="PT" value="<?=$arResult["PERSON_TYPE"]['ID'];?>"/>
	<?=bitrix_sessid_post()?>
	<div class="general-info">
		<div class="line-wrap">
			<span class="text"><?=GetMessage("SALE_PERS_TYPE")?>:</span>
			<span class="content"><?=$arResult["PERSON_TYPE"]["NAME"]?></span>
		</div>
		<label class="line-wrap">
			<span class="label-text text"><?echo GetMessage("SALE_PNAME")?>:<span class="required-asterisk">*</span></span>
			<span class="content">
				<input type="text" name="NAME" class="textinput" value="<?=$arResult["NAME"]?>" size="40">
			</span>
		</label>
	</div>
	<?
	foreach($arResult["ORDER_PROPS"] as $val)
	{
		if(empty($val["PROPS"])) continue;
		?>
	<div class="expandable allow-multiple-expanded expanded">
		<header>
			<span class="text-wrap">
				<span class="text"><?=$val["NAME"]?></span>
			</span>
		</header>
		<div class="expand-content">
		<?
		foreach($val["PROPS"] as $vval)
		{
			$currentValue = $arResult["ORDER_PROPS_VALUES"]["ORDER_PROP_".$vval["ID"]];
			$name = "ORDER_PROP_".$vval["ID"];
			?>
			<label class="line-wrap">
				<span class="label-text text"><?=$vval["NAME"]?>:<?
					if ($vval["REQUIED"]=="Y")
					{
						?><span class="required-asterisk">*</span><?
					}
					?></span>
				<span class="content">

					<?if ($vval["TYPE"]=="CHECKBOX"):?>
						<input type="hidden" name="<?=$name?>" value="">
						<input type="checkbox" name="<?=$name?>" value="Y"<?if ($currentValue=="Y" || !isset($currentValue) && $vval["DEFAULT_VALUE"]=="Y") echo " checked";?>>
					<?elseif ($vval["TYPE"]=="TEXT"):?>
						<input type="text" class="textinput" size="<?echo (IntVal($vval["SIZE1"])>0)?$vval["SIZE1"]:30; ?>" maxlength="250" value="<?echo (isset($currentValue)) ? $currentValue : $vval["DEFAULT_VALUE"];?>" name="<?=$name?>">
					<?elseif ($vval["TYPE"]=="SELECT"):?>
						<select name="<?=$name?>" size="<?echo (IntVal($vval["SIZE1"])>0)?$vval["SIZE1"]:1; ?>">
							<?foreach($vval["VALUES"] as $vvval):?>
								<option value="<?echo $vvval["VALUE"]?>"<?if ($vvval["VALUE"]==$currentValue || !isset($currentValue) && $vvval["VALUE"]==$vval["DEFAULT_VALUE"]) echo " selected"?>><?echo $vvval["NAME"]?></option>
							<?endforeach;?>
						</select>
					<?elseif ($vval["TYPE"]=="MULTISELECT"):?>
						<select multiple name="<?=$name?>[]" size="<?echo (IntVal($vval["SIZE1"])>0)?$vval["SIZE1"]:5; ?>">
							<?
							$arCurVal = array();
							$arCurVal = explode(",", $currentValue);
							for ($i = 0, $cnt = count($arCurVal); $i < $cnt; $i++)
								$arCurVal[$i] = trim($arCurVal[$i]);
							$arDefVal = explode(",", $vval["DEFAULT_VALUE"]);
							for ($i = 0, $cnt = count($arDefVal); $i < $cnt; $i++)
								$arDefVal[$i] = trim($arDefVal[$i]);
							foreach($vval["VALUES"] as $vvval):?>
								<option value="<?echo $vvval["VALUE"]?>"<?if (in_array($vvval["VALUE"], $arCurVal) || !isset($currentValue) && in_array($vvval["VALUE"], $arDefVal)) echo" selected"?>><?echo $vvval["NAME"]?></option>
							<?endforeach;?>
						</select>
					<? elseif ($vval["TYPE"] == "TEXTAREA"): ?>
						<textarea class="textinput" rows="<?echo (IntVal($vval["SIZE2"])>0)?$vval["SIZE2"]:4; ?>" cols="<?echo (IntVal($vval["SIZE1"])>0)?$vval["SIZE1"]:40; ?>" name="<?=$name?>"><?echo (isset($currentValue)) ? $currentValue : $vval["DEFAULT_VALUE"];?></textarea>
					<? elseif ($vval["TYPE"] == "LOCATION"):

						$locationTemplate = ($arParams['USE_AJAX_LOCATIONS'] !== 'Y') ? "popup" : "";
						$locationValue = intval($currentValue) ? $currentValue : $vval["DEFAULT_VALUE"];

						CSaleLocation::proxySaleAjaxLocationsComponent(
							array(
								"AJAX_CALL" => "N",
								'CITY_OUT_LOCATION' => 'Y',
								'COUNTRY_INPUT_NAME' => $name.'_COUNTRY',
								'CITY_INPUT_NAME' => $name,
								'LOCATION_VALUE' => $locationValue,
							),
							array(
							),
							$locationTemplate,
							true,
							'location-block-wrapper'
						);

						?>
					<?elseif ($vval["TYPE"]=="RADIO"):?>
						<?foreach($vval["VALUES"] as $vvval):?>
							<input type="radio" name="<?=$name?>" value="<?echo $vvval["VALUE"]?>"<?if ($vvval["VALUE"]==$currentValue || !isset($currentValue) && $vvval["VALUE"]==$vval["DEFAULT_VALUE"]) echo " checked"?>><?echo $vvval["NAME"]?><br />
						<?endforeach;?>
					<?endif?>

					<?if (strlen($vval["DESCRIPTION"])>0):?>
						<br /><small><?echo $vval["DESCRIPTION"] ?></small>
					<?endif?>
				</span>
			</label>
			<?
		}
		?>

		</div>
	</div><?

	}
	?>

	<div class="action-buttons">
		<input type="submit" name="save" value="<?echo GetMessage("SALE_SAVE") ?>" class="btn-main">
		&nbsp;
		<input type="submit" name="apply" value="<?=GetMessage("SALE_APPLY")?>" class="btn-main">
		&nbsp;
		<input type="submit" name="reset" value="<?echo GetMessage("SALE_RESET")?>" class="btn-action btn-silver">
	</div>
</form>

<?elseif($arResult['STEP'] == 'FINISH'):?>
	<br/><?=GetMessage("SPPA_FINISH", array("#ID#"=>$arResult["ID"]));?><br/>
<?endif;?>