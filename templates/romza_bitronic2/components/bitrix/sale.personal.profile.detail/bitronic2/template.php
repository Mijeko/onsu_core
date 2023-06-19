<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!CRZBitronic2Settings::isPro()) return;?>
<?//<a name="tb"></a> ??? WHAT IS IT ??? ?>
<a class="link" href="<?=$arParams["PATH_TO_LIST"]?>">
	<span class="text"><?=GetMessage("SPPD_RECORDS_LIST")?></span>
</a>
<?
if(strlen($arResult["ID"])>0):
	if(strlen($arResult["ERROR_MESSAGE"])>0) {
		echo CRZBitronic2CatalogUtils::ShowMessage(Array("MESSAGE" => $arResult["ERROR_MESSAGE"], "TYPE" => "ERROR"));
	}
    \Bitrix\Main\Localization\Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH . '/header.php');
    $pathToRules = COption::GetOptionString(CRZBitronic2Settings::getModuleId(),'path_tu_rules_privacy',SITE_DIR.'personal/rules/personal_data.php');
	?>
<form method="post" action="<?=POST_FORM_ACTION_URI?>" class="form_account-settings">
	<?=bitrix_sessid_post()?>
	<input type="hidden" name="ID" value="<?=$arResult["ID"]?>">
    <input type="hidden" name="privacy_policy" value="N"/>
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
					<? elseif ($vval["TYPE"]=="TEXTAREA"): ?>
						<textarea class="textinput" rows="<?echo (IntVal($vval["SIZE2"])>0)?$vval["SIZE2"]:4; ?>" cols="<?echo (IntVal($vval["SIZE1"])>0)?$vval["SIZE1"]:40; ?>" name="<?=$name?>"><?echo (isset($currentValue)) ? $currentValue : $vval["DEFAULT_VALUE"];?></textarea>
					<? elseif ($vval["TYPE"]=="LOCATION"):

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
            <label class="line-wrap">
                <input value="Y" type="checkbox" name="privacy_policy">
                <span class="checkbox-content" tabindex="5">
        <i class="flaticon-check14"></i><?=GetMessage('BITRONIC2_I_ACCEPT')?>
                    <a href="<?=$pathToRules?>" class="link"><span class="text"><?=GetMessage('BITRONIC2_POLITIC_PRIVICE')?></span></a>
    </span>
            </label>

		</div>
	</div><?

	}
	?>

	<div class="action-buttons">
		<input type="submit" name="save disabled" value="<?echo GetMessage("SALE_SAVE") ?>" class="btn-main">
		&nbsp;
		<input type="submit" name="apply" value="<?=GetMessage("SALE_APPLY")?>" class="btn-main">
		&nbsp;
		<input type="submit" name="reset" value="<?echo GetMessage("SALE_RESET")?>" class="btn-action btn-silver">
	</div>
</form>

<?else:?>
	<?
	if(strlen($arResult["ERROR_MESSAGE"])>0) {
		echo CRZBitronic2CatalogUtils::ShowMessage(Array("MESSAGE" => $arResult["ERROR_MESSAGE"], "TYPE" => "ERROR"));
	}
	?>
<?endif;?>
