<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if ($arResult['USE_BONUS']) {
	CVbchbbEvents::RefreshPayedFromAccount($arResult);
}
?>
<script type="text/javascript">
		function changePaySystem(param)
		{
			//this is bigbonus module add--------------------------------------------------------
			if(param == 'bonus'){
				BX("PAY_BONUS_ACCOUNT").checked = !BX("PAY_BONUS_ACCOUNT").checked;
				BX.addClass(BX("PAY_BONUS_ACCOUNT_LABEL"), 'selected');
			}
			if(param == 'bonusorderpay'){
				BX("PAY_BONUSORDERPAY").checked = !BX("PAY_BONUSORDERPAY").checked;
				BX.addClass(BX("PAY_BONUSORDERPAY_LABEL"), 'selected');
			}
			//-------------------------------------------------------------------------------
			if (BX("account_only") && BX("account_only").value == 'Y') // PAY_CURRENT_ACCOUNT checkbox should act as radio
			{
				if (param == 'account')
				{
					if (BX("PAY_CURRENT_ACCOUNT"))
					{
						BX("PAY_CURRENT_ACCOUNT").checked = true;
						BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
						BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');

						// deselect all other
						var el = document.getElementsByName("PAY_SYSTEM_ID");
						for(var i=0; i<el.length; i++)
							el[i].checked = false;
					}
				}
				else
				{
					BX("PAY_CURRENT_ACCOUNT").checked = false;
					BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
					BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
				}
			}
			else if (BX("account_only") && BX("account_only").value == 'N')
			{
				if (param == 'account')
				{
					if (BX("PAY_CURRENT_ACCOUNT"))
					{
						BX("PAY_CURRENT_ACCOUNT").checked = !BX("PAY_CURRENT_ACCOUNT").checked;

						if (BX("PAY_CURRENT_ACCOUNT").checked)
						{
							BX("PAY_CURRENT_ACCOUNT").setAttribute("checked", "checked");
							BX.addClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
						}
						else
						{
							BX("PAY_CURRENT_ACCOUNT").removeAttribute("checked");
							BX.removeClass(BX("PAY_CURRENT_ACCOUNT_LABEL"), 'selected');
						}
					}
				}
			}

			submitForm();
		}
</script>
<div class="title-h3"><?=GetMessage("BITRONIC2_SOA_TEMPL_PAY_SYSTEM")?></div>
<div class="payment-system-type row">
<?
	if ($arResult["PAY_FROM_ACCOUNT"] == "Y" && !$arResult['USE_BONUS']) {
		$accountOnly = ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y") ? "Y" : "N";
		?>
			<input type="hidden" id="account_only" value="<?=$accountOnly?>" />
			<div class="col-xs-12 pay-from-inner-wrap">
				<input type="hidden" name="PAY_CURRENT_ACCOUNT" value="N">
				<label class="checkbox-styled" onclick="changePaySystem('account');">
					<input type="checkbox" name="PAY_CURRENT_ACCOUNT" id="PAY_CURRENT_ACCOUNT" value="Y"<?if($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y") echo " checked=\"checked\"";?>>
					<span class="checkbox-content">
						<i class="flaticon-check14"></i>
						<?=GetMessage("BITRONIC2_SOA_TEMPL_PAY_ACCOUNT")?>
						<div> - <?=GetMessage("BITRONIC2_SOA_TEMPL_PAY_ACCOUNT1")." <b>".
						CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['BONUS_PRICE']['CURRENCY'],$arResult["CURRENT_BUDGET_FORMATED"],$arResult["CURRENT_BUDGET_FORMATED"])
						?></b></div>
						<div>
							<? if ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y"):?>
								<?=GetMessage("BITRONIC2_SOA_TEMPL_PAY_ACCOUNT3")?>
							<? else:?>
								<?=GetMessage("BITRONIC2_SOA_TEMPL_PAY_ACCOUNT2")?>
							<? endif;?>
						</div>
					</span>
				</label>
			</div>
			<?
	} else {
		if ($arResult['SYSTEMPAY']['BONUSORDERPAY'] || $arResult['BONUSPAY']['BONUSORDERPAY']) {
			?>
			<input type="hidden" name="PAY_BONUSORDERPAY" value="N">
			<label class="col-md-2 col-sm-4 col-xs-6"  onclick="changePaySystem('bonusorderpay');">
				<input type="checkbox" name="PAY_BONUSORDERPAY" id="PAY_BONUSORDERPAY" value="Y"<?if($arResult["USER_VALS"]["PAY_BONUSORDERPAY"]=="Y") echo " checked=\"checked\"";?>>
				<span class="radio-item">
					<span class="radio-img">
						<img src="<?=$this->GetFolder();?>/images/bonus-to-pay.gif" alt="<?=GetMessage('VBCHBB_SALE_ORDER_AJAX_P7')?>">
					</span>
					<?if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
						<span class="radio-item-header"><?=GetMessage('VBCHBB_SALE_ORDER_AJAX_P7')?></span>
					<?endif;?>
				</span>
			</label>
		<?}
		if(!is_array($arResult['TYPEPAY']))  $arResult['TYPEPAY'] = array();
		if (in_array("SYSTEMPAY", $arResult['TYPEPAY']) && $arResult['PAY_FROM_ACCOUNT1'] == 'Y')
		{
			$accountOnly = ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y") ? "Y" : "N";
			?>
			<input type="hidden" id="account_only" value="<?=$accountOnly?>" />
			<input type="hidden" name="PAY_CURRENT_ACCOUNT" value="N">
				<label class="col-md-2 col-sm-4 col-xs-6" for="PAY_CURRENT_ACCOUNT" id="PAY_CURRENT_ACCOUNT_LABEL" class="<?if($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y") echo "selected"?>">
					<input type="checkbox" name="PAY_CURRENT_ACCOUNT" id="PAY_CURRENT_ACCOUNT" value="Y"<?if($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y") echo " checked=\"checked\"";?>>
					<span class="radio-item">
						<span class="radio-img">
							<img src="<?=$this->GetFolder();?>/images/inner-ps.gif" alt="<?=GetMessage("SOA_TEMPL_PAY_ACCOUNT3")?>" onclick="changePaySystem('account');">
						</span>
						<p>
						<strong><?=GetMessage('VBCHBB_SALE_ORDER_AJAX_P6')?>&nbsp; <?=$arResult['SYSTEMPAY']['ORDER_PAY_PERCENT']?>)</strong>
						<? if ($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y"):?>
								<div><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT3")?></div>
							<? else:?>
								<div><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT2")?></div>
							<? endif;?>
							<?if(!$arResult['SYSTEMPAY']['USER_INPUT']){?>
								<input type="hidden" name="ACCOUNT_CNT" value="<?=$arResult['SYSTEMPAY']['MAXPAY']?>"/>
							<?}?>
						</p>
				<? if ($arResult['SYSTEMPAY']['USER_INPUT']) { ?>
						<div>
							<? if ($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"] == "Y") { ?>
								<input type="text" id="account_pay" name="ACCOUNT_CNT" placeholder="<?=$arResult['SYSTEMPAY']["MAXPAY"]?>" disabled="disabled"
									   style="width:180px;" value="<?=$arResult['SYSTEMPAY']['MAXPAY']?>"/>
								<input type="hidden" id="account_pay" name="ACCOUNT_CNT"  value="<?=$arResult['SYSTEMPAY']['MAXPAY']?>"/>
							<? } else { ?>
								<input type="text" id="account_pay" name="ACCOUNT_CNT" placeholder="<?=$arResult['SYSTEMPAY']["MAXPAY"]?>"
									   style="width:180px;" value="<?=$arResult['SYSTEMPAY']['MAXPAY']?>"/>
							<? } ?>
						</div>
					<? } ?>
					</span>
				</label>
		<? } ?>
		<? if (in_array("BONUSPAY", $arResult['TYPEPAY']) && $arResult['PAY_FROM_BONUS'] == "Y") { ?>
			<input type="hidden" name="PAY_BONUS_ACCOUNT" value="N">
			<label class="col-md-2 col-sm-4 col-xs-6" for="PAY_BONUS_ACCOUNT" id="PAY_BONUS_ACCOUNT_LABEL" class="<?if($arResult["USER_VALS"]["PAY_BONUS_ACCOUNT"]=="Y") echo "selected"?>">
						<input type="checkbox" name="PAY_BONUS_ACCOUNT" onclick="changePaySystem('bonus');"  id="PAY_BONUS_ACCOUNT" value="Y"<?if($arResult["USER_VALS"]["PAY_BONUS_ACCOUNT"]=="Y") echo " checked=\"checked\"";?>>
						<span class="radio-item">
							<span class="radio-img" onclick="changePaySystem('bonus');" >
								<img src="<?=$this->GetFolder();?>/images/bonus-ps.gif" alt="<?=GetMessage("SOA_TEMPL_PAY_ACCOUNT3")?>">
							</span>
							<strong><?=GetMessage('VBCHBB_SALE_ORDER_AJAX_P0')?>&nbsp;<?=$arResult['BONUSPAY']['ORDER_PAY_PERCENT']?>)</strong>
							<p>
							<div><?=GetMessage('VBCHBB_SALE_ORDER_AJAX_P1')?>&nbsp;<b>
							<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['BONUS_PRICE']['CURRENCY'],$arResult['BONUSPAY']["CURRENT_BONUS_BUDGET_FORMATED"],$arResult['BONUSPAY']["CURRENT_BONUS_BUDGET_FORMATED"])?>
							</b></div>
							<? if (!$arResult['BONUSPAY']['USER_INPUT']) { ?>
								<div><?=GetMessage('VBCHBB_SALE_ORDER_AJAX_P2')?>&nbsp;<?=$arResult['BONUSPAY']['MAXPAY']?></div>
								<input type="hidden" name="BONUS_CNT" value="<?=$arResult['BONUSPAY']['MAXPAY']?>"/>
							<? } ?>
							</p>
							<? if ($arResult['BONUSPAY']['USER_INPUT']) { ?>
						<div>
						<? if ($arResult["USER_VALS"]["PAY_BONUS_ACCOUNT"] == "Y") { ?>
							<input type="text" id="bonus_pay" name="BONUS_CNT" placeholder="<?=$arResult['BONUSPAY']["MAXPAY"]?>" class="textinput" disabled="disabled"
								   style="width:180px;" value="<?=$arResult['BONUSPAY']['MAXPAY']?>"/>
							<input type="hidden" id="bonus_pay" name="BONUS_CNT" value="<?=$arResult['BONUSPAY']['MAXPAY']?>"/>
						<? } else { ?>
							<input type="text" id="bonus_pay" class="textinput" name="BONUS_CNT" placeholder="<?=$arResult['BONUSPAY']["MAXPAY"]?>"
								   style="width:180px;" value="<?=$arResult['BONUSPAY']['MAXPAY']?>"/>
						<? } ?>
					</div>
				<? } ?>
						</span>
				</label>
				
			<?
		}

	}

		uasort($arResult["PAY_SYSTEM"], "cmpBySort"); // resort arrays according to SORT value

		foreach($arResult["PAY_SYSTEM"] as $arPaySystem)
		{
			if (strlen(trim(str_replace("<br />", "", $arPaySystem["DESCRIPTION"]))) > 0 || intval($arPaySystem["PRICE"]) > 0)
			{
				if (count($arResult["PAY_SYSTEM"]) == 1)
				{
					if (count($arPaySystem["PSA_LOGOTIP"]) > 0):
						$imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];
					else:
						$imgUrl = $templateFolder."/images/logo-default-ps.gif";
					endif;
					?>
					<input type="hidden" name="PAY_SYSTEM_ID" value="<?=$arPaySystem["ID"]?>">
					<label class="col-md-2 col-sm-4 col-xs-6" onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked=true;changePaySystem();">
						<input type="radio"
							id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
							name="PAY_SYSTEM_ID"
							value="<?=$arPaySystem["ID"]?>"
							<?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?>
							onclick="changePaySystem();"
							/>
						<span class="radio-item">
							<span class="radio-img">
								<img src="<?=$imgUrl?>" alt="<?=$arPaySystem["PSA_NAME"];?>">
							</span>
							<?if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
								<span class="radio-item-header"><?=$arPaySystem["PSA_NAME"];?></span>
							<?endif;?>
							<p>
								<?
								if (intval($arPaySystem["PRICE"]) > 0)
									echo str_replace("#PAYSYSTEM_PRICE#", SaleFormatCurrency(roundEx($arPaySystem["PRICE"], SALE_VALUE_PRECISION), $arResult["BASE_LANG_CURRENCY"]), GetMessage("BITRONIC2_SOA_TEMPL_PAYSYSTEM_PRICE"));
								else
									echo $arPaySystem["DESCRIPTION"];
								?>
							</p>
						</span>
					</label>
					<?
				}
				else // more than one
				{
					if (count($arPaySystem["PSA_LOGOTIP"]) > 0):
						$imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];
					else:
						$imgUrl = $templateFolder."/images/logo-default-ps.gif";
					endif;
				?>
					<label class="col-md-2 col-sm-4 col-xs-6" onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked=true;changePaySystem();">
						<input type="radio"
							id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
							name="PAY_SYSTEM_ID"
							value="<?=$arPaySystem["ID"]?>"
							<?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?>
							onclick="changePaySystem();" />
						<span class="radio-item">
							<span class="radio-img">
								<img src="<?=$imgUrl?>" alt="<?=$arPaySystem["PSA_NAME"];?>">
							</span>
							<?if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
								<span class="radio-item-header"><?=$arPaySystem["PSA_NAME"];?></span>
							<?endif;?>
							<p>
								<?
								if (intval($arPaySystem["PRICE"]) > 0)
									echo str_replace("#PAYSYSTEM_PRICE#", SaleFormatCurrency(roundEx($arPaySystem["PRICE"], SALE_VALUE_PRECISION), $arResult["BASE_LANG_CURRENCY"]), GetMessage("BITRONIC2_SOA_TEMPL_PAYSYSTEM_PRICE"));
								else
									echo $arPaySystem["DESCRIPTION"];
								?>
							</p>
						</span>
					</label>
				<?
				}
			}

			if (strlen(trim(str_replace("<br />", "", $arPaySystem["DESCRIPTION"]))) == 0 && intval($arPaySystem["PRICE"]) == 0)
			{
				if (count($arResult["PAY_SYSTEM"]) == 1)
				{
					if (count($arPaySystem["PSA_LOGOTIP"]) > 0):
						$imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];
					else:
						$imgUrl = $templateFolder."/images/logo-default-ps.gif";
					endif;
					?>
					<input type="hidden" name="PAY_SYSTEM_ID" value="<?=$arPaySystem["ID"]?>">
					<label class="col-md-2 col-sm-4 col-xs-6" onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked=true;changePaySystem();">
						<input type="radio"
							id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
							name="PAY_SYSTEM_ID"
							value="<?=$arPaySystem["ID"]?>"
							<?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?>
							onclick="changePaySystem();"
							/>
						<span class="radio-item">
							<span class="radio-img">
								<img src="<?=$imgUrl?>" alt="<?=$arPaySystem["PSA_NAME"];?>">
							</span>
							<?if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
								<span class="radio-item-header"><?=$arPaySystem["PSA_NAME"];?></span>
							<?endif;?>
						</span>
					</label>
				<?
				}
				else // more than one
				{
					if (count($arPaySystem["PSA_LOGOTIP"]) > 0):
						$imgUrl = $arPaySystem["PSA_LOGOTIP"]["SRC"];
					else:
						$imgUrl = $templateFolder."/images/logo-default-ps.gif";
					endif;
				?>
					<label class="col-md-2 col-sm-4 col-xs-6" onclick="BX('ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>').checked=true;changePaySystem();">
						<input type="radio"
							id="ID_PAY_SYSTEM_ID_<?=$arPaySystem["ID"]?>"
							name="PAY_SYSTEM_ID"
							value="<?=$arPaySystem["ID"]?>"
							<?if ($arPaySystem["CHECKED"]=="Y" && !($arParams["ONLY_FULL_PAY_FROM_ACCOUNT"] == "Y" && $arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y")) echo " checked=\"checked\"";?>
							onclick="changePaySystem();" />
						<span class="radio-item">
							<span class="radio-img">
								<img src="<?=$imgUrl?>" alt="<?=$arPaySystem["PSA_NAME"];?>">
							</span>
							<?if ($arParams["SHOW_PAYMENT_SERVICES_NAMES"] != "N"):?>
								<span class="radio-item-header"><?=$arPaySystem["PSA_NAME"];?></span>
							<?endif;?>
						</span>
					</label>
				<?
				}
			}
	}?>
</div><!-- /payment-system-type.row -->