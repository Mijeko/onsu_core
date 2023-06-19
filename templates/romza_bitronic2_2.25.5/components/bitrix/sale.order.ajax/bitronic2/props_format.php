<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (!function_exists("showFilePropertyField"))
{
	function showFilePropertyField($name, $property_fields, $values, $max_file_size_show=50000)
	{
		$res = "";

		if (!is_array($values) || empty($values))
			$values = array(
				"n0" => 0,
			);

		if ($property_fields["MULTIPLE"] == "N")
		{
			$res = "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\"></label>";
		}
		else
		{
			$res = '
			<script type="text/javascript">
				function addControl(item)
				{
					var current_name = item.id.split("[")[0],
						current_id = item.id.split("[")[1].replace("[", "").replace("]", ""),
						next_id = parseInt(current_id) + 1;

					var newInput = document.createElement("input");
					newInput.type = "file";
					newInput.name = current_name + "[" + next_id + "]";
					newInput.id = current_name + "[" + next_id + "]";
					newInput.onchange = function() { addControl(this); };

					var br = document.createElement("br");
					var br2 = document.createElement("br");

					BX(item.id).parentNode.appendChild(br);
					BX(item.id).parentNode.appendChild(br2);
					BX(item.id).parentNode.appendChild(newInput);
				}
			</script>
			';

			$res .= "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\"></label>";
			$res .= "<br/><br/>";
			$res .= "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[1]\" id=\"".$name."[1]\" onChange=\"javascript:addControl(this);\"></label>";
		}

		return $res;
	}
}

if (!function_exists("PrintPropsForm"))
{
	function PrintPropsForm($arSource = array(), $locationTemplate = ".default")
	{
		if (!empty($arSource))
		{
			foreach ($arSource as $arProperties)
			{
				if(CSaleLocation::isLocationProMigrated())
				{
					$propertyAttributes = array(
						'type' => $arProperties["TYPE"],
						'valueSource' => $arProperties['SOURCE'] == 'DEFAULT' ? 'default' : 'form'
					);

					if(intval($arProperties['IS_ALTERNATE_LOCATION_FOR']))
						$propertyAttributes['isAltLocationFor'] = intval($arProperties['IS_ALTERNATE_LOCATION_FOR']);

					if(intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION']))
						$propertyAttributes['altLocationPropId'] = intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION']);

					if($arProperties['IS_ZIP'] == 'Y')
						$propertyAttributes['isZip'] = true;
				}
				?>
				
				
				<div data-property-id-row="<?=intval(intval($arProperties["ID"]))?>" class="buyer-info-section">
					<div><span class="text"><?=$arProperties["NAME"]?><?if ($arProperties["REQUIED_FORMATED"]=="Y"):?><span class="required-asterisk">*</span><?endif;?>:</span></div>
					<?
					switch($arProperties["TYPE"])
					{
						case "CHECKBOX":
							?>
							<input type="hidden" name="<?=$arProperties["FIELD_NAME"]?>" value="">
							
							<label class="checkbox-styled">
								<input type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" value="Y"<?if ($arProperties["CHECKED"]=="Y") echo " checked";?>>
								<span class="checkbox-content">
									<i class="flaticon-check14"></i>
								</span>
							</label>
							
							<?
						break;
						case "TEXT":
							?>
							<input type="text" maxlength="250" size="<?=$arProperties["SIZE1"]?>" value="<?=$arProperties["VALUE"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" class="textinput">
							<?
						break;
						case "SELECT":
						case "MULTISELECT":
							?>
							<select <?=$arProperties["TYPE"]=="MULTISELECT" ? 'multiple' :''?> name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>" class="select-styled">
								<?foreach($arProperties["VARIANTS"] as $arVariants):?>
									<option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
								<?endforeach;?>
							</select>
							
							<?
						break;
						case "TEXTAREA":
							$rows = ($arProperties["SIZE2"] > 10) ? 4 : $arProperties["SIZE2"];
							?>
							
							<textarea class="textinput" rows="<?=$rows?>" cols="<?=$arProperties["SIZE1"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>"><?=$arProperties["VALUE"]?></textarea>
							
							<?
						break;
						case "LOCATION":
							// TODO stylized LOCATION isLocationProMigrated() == true
							$value = 0;
							if (is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0)
							{
								foreach ($arProperties["VARIANTS"] as $arVariant)
								{
									if ($arVariant["SELECTED"] == "Y")
									{
										$value = $arVariant["ID"];
										break;
									}
								}
							}
							$value = $value ?:$arProperties['VALUE'];

							// here we can get '' or 'popup'
							// map them, if needed
							if(CSaleLocation::isLocationProMigrated())
							{
								$locationTemplateP = $locationTemplate == 'popup' ? 'search' : 'steps';
								$locationTemplateP = $_REQUEST['PERMANENT_MODE_STEPS'] == 1 ? 'steps' : $locationTemplateP; // force to "steps"
							}
							?>

							<?if($locationTemplateP == 'steps'):?>
								<input type="hidden" id="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" name="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]" value="<?=($_REQUEST['LOCATION_ALT_PROP_DISPLAY_MANUAL'][intval($arProperties["ID"])] ? '1' : '0')?>" />
							<?endif?>

							<?CSaleLocation::proxySaleAjaxLocationsComponent(array(
								"AJAX_CALL" => "N",
								"COUNTRY_INPUT_NAME" => "COUNTRY",
								"REGION_INPUT_NAME" => "REGION",
								"CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
								"CITY_OUT_LOCATION" => "Y",
								"LOCATION_VALUE" => $value,
								"ORDER_PROPS_ID" => $arProperties["ID"],
								"ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
								"SIZE1" => $arProperties["SIZE1"],
							),
							array(
								"ID" => $value,
								"CODE" => "",
								"SHOW_DEFAULT_LOCATIONS" => "Y",

								// function called on each location change caused by user or by program
								// it may be replaced with global component dispatch mechanism coming soon
								"JS_CALLBACK" => "submitFormProxy",

								// function window.BX.locationsDeferred['X'] will be created and lately called on each form re-draw.
								// it may be removed when sale.order.ajax will use real ajax form posting with BX.ProcessHTML() and other stuff instead of just simple iframe transfer
								"JS_CONTROL_DEFERRED_INIT" => intval($arProperties["ID"]),

								// an instance of this control will be placed to window.BX.locationSelectors['X'] and lately will be available from everywhere
								// it may be replaced with global component dispatch mechanism coming soon
								"JS_CONTROL_GLOBAL_ID" => intval($arProperties["ID"]),

								"DISABLE_KEYBOARD_INPUT" => "Y",
								"PRECACHE_LAST_LEVEL" => "Y",
							),
							$locationTemplateP,
							true,
							'location-block-wrapper'
							)?>

							<?
						break;
						case "RADIO":
							if (is_array($arProperties["VARIANTS"]))
							{
								foreach($arProperties["VARIANTS"] as $arVariants):
								?>
									<label class="radio-styled">
										<input
											type="radio"
											name="<?=$arProperties["FIELD_NAME"]?>"
											id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>"
											value="<?=$arVariants["VALUE"]?>" <?if($arVariants["CHECKED"] == "Y") echo " checked";?> />
										<span class="radio-content">
											<span class="radio-fake"></span>
											<span class="text"><?=$arVariants["NAME"]?></span>
										</span>
									</label>
								<?
								endforeach;
							}
						break;
						case "FILE":
							?>
							<span class="text"><?=$arProperties["NAME"]?>:
								<?if ($arProperties["REQUIED_FORMATED"]=="Y"):?>
									<span class="required-asterisk">*</span>
								<?endif;?>
							</span>

							<?=showFilePropertyField("ORDER_PROP_".$arProperties["ID"], $arProperties, $arProperties["VALUE"], $arProperties["SIZE1"])?>

							<?
					}
					
					if (strlen(trim($arProperties["DESCRIPTION"])) > 0)
					{
						?>
						<span>
							- <?=$arProperties["DESCRIPTION"]?>
						</span>
						<?
					}
					?>
				</div>

				<?if(CSaleLocation::isLocationProEnabled()):?>
					<script>
						(window.top.BX || BX).saleOrderAjax.addPropertyDesc(<?=CUtil::PhpToJSObject(array(
							'id' => intval($arProperties["ID"]),
							'attributes' => $propertyAttributes
						))?>);
					</script>
				<?endif?>
				<?
			}?>
			<div><span class="required-asterisk">*</span> &mdash; <?=GetMessage("BITRONIC2_REQ_FIELDS")?></div>
			<?
		}
	}
}
?>