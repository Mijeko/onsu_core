<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$tabindex = 1;
?>
<script>
<!--
function ChangeGenerate(val)
{
	if(val)
	{
		document.getElementById("sof_choose_login").style.display='none';
	}
	else
	{
		document.getElementById("sof_choose_login").style.display='block';
		document.getElementById("NEW_GENERATE_N").checked = true;
	}

	try{document.order_reg_form.NEW_LOGIN.focus();}catch(e){}
}
//-->
</script>
<p><?=GetMessage('BITRONIC2_STOF_SUBTITLE')?>:</p>

<div class="row auth-before-order-page">
	<div class="col-sm-5 col-xl-3 col-xl-offset-1 modal-form">
		<form method="post" action="<?=$APPLICATION->GetCurPage()?>" name="order_auth_form" class="form_login">
			<div class="title-h2"><span class="header-text"><?echo GetMessage("BITRONIC2_STOF_2NEW")?></span></div>
			
			<?=bitrix_sessid_post()?>
			
			<?foreach ($arResult["POST"] as $key => $value):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
			<?endforeach?>
			<input type="hidden" name="do_authorize" value="Y">

			<label class="textinput-wrapper">
				<span class="label-text">
					<i class="textinput-icon flaticon-user12"></i>
					<?=GetMessage("BITRONIC2_STOF_LOGIN")?><span class="required-asterisk">*</span>:
				</span>
				<input type="email" name="USER_LOGIN" maxlength="30" size="30" value="<?=$arResult["AUTH"]["USER_LOGIN"]?>" class="textinput" required tabindex="<?=$tabindex++?>">
				<span class="textinput-icons">
					<i class="icon-valid flaticon-check33"></i>
					<i class="icon-not-valid flaticon-x5"></i>
				</span>
				
			</label>
			<label class="textinput-wrapper">
				<span class="label-text">
					<i class="flaticon-blockade"></i>
					<?=GetMessage("BITRONIC2_STOF_PASSWORD")?><span class="required-asterisk">*</span>:
				</span>
				<input type="password" name="USER_PASSWORD" maxlength="30" size="30" class="textinput password" required tabindex="<?=$tabindex++?>">
				<span class="textinput-icons">
					<span class="btn-password-toggle">
						<i class="password-shown flaticon-eye36"></i>
						<i class="password-hidden flaticon-closed40"></i>
					</span>
					<i class="icon-valid flaticon-check33"></i>
					<i class="icon-not-valid flaticon-x5"></i>
				</span>
			</label>
			
			<label class="checkbox-styled">
				<input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y">
				<span class="checkbox-content" tabindex="<?=$tabindex++?>">
					<i class="flaticon-check14"></i>
					<?=GetMessage('BITRONIC2_STOF_REMEMBER_ME')?>
				</span>
			</label>

			<div>
				<a href="<?=$arParams["PATH_TO_AUTH"]?>?forgot_password=yes&amp;back_url=<?= urlencode($APPLICATION->GetCurPageParam()); ?>" class="forgot-pass link"><span class="text"><?= GetMessage("BITRONIC2_STOF_FORGET_PASSWORD")?></span></a>
			</div>
			<div class="textinput-wrapper submit-wrap">
				<button type="submit" class="btn-submit btn-main" tabindex="<?=$tabindex++?>">
					<span class="btn-text"><?=GetMessage("BITRONIC2_STOF_CONTINUE")?></span>
				</button>
			</div>
		</form>
		<?
		/*
		TODO
		$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
			array(
				"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
				"CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
				"AUTH_URL" => $arResult["AUTH_URL"],
				"POST" => $arResult["POST"],
				"SHOW_TITLES" => $arResult["FOR_INTRANET"]?'N':'Y',
				"FOR_SPLIT" => $arResult["FOR_INTRANET"]?'Y':'N',
				"AUTH_LINE" => $arResult["FOR_INTRANET"]?'N':'Y',
			),
			$component,
			array("HIDE_ICONS"=>"Y")
		);
		*/?>
	</div>
	
	<?if($arResult["AUTH"]["new_user_registration"]=="Y"):?>
	<div class="col-sm-offset-1 col-sm-6 col-xl-4 col-xl-offset-3 modal-form">
		<form method="post" action="<?=$APPLICATION->GetCurPage()?>" name="order_reg_form" class="form_registration" onSubmit="if(!document.getElementById('stof-accept-agreement').checked){RZB2.ajax.showMessage('<?=GetMessage('BITRONIC2_STOF_FORM_ALERT')?>','fail'); return false;}">
			<div class="title-h2"><span class="header-text"><?=GetMessage("BITRONIC2_STOF_2REG")?></span></div>
			
			<?=bitrix_sessid_post()?>
			
			<?foreach ($arResult["POST"] as $key => $value):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
			<?endforeach?>
			<input type="hidden" name="do_register" value="Y">
			
			<?if($arResult["AUTH"]["new_user_registration_email_confirmation"] != "Y"):?>
				<div class="generate-pass-wrap">
					<label class="radio-styled">
						<input type="radio" name="NEW_GENERATE" value="Y" OnClick="ChangeGenerate(true)"<?if ($POST["NEW_GENERATE"] != "N") echo " checked";?>>
						<span class="radio-content">
							<span class="radio-fake"></span>
							<span class="text"><?=GetMessage("BITRONIC2_STOF_SYS_PASSWORD")?></span>
						</span>
					</label>
					<label class="radio-styled">
						<input type="radio" name="NEW_GENERATE" value="N" OnClick="ChangeGenerate(false)"<?if ($_POST["NEW_GENERATE"] == "N") echo " checked";?>>
						<span class="radio-content">
							<span class="radio-fake"></span>
							<span class="text"><?=GetMessage("BITRONIC2_STOF_MY_PASSWORD")?></span>
						</span>
					</label>
				</div>
			<?endif?>
			
			<label class="textinput-wrapper">
				<span class="label-text">E-mail<span class="required-asterisk">*</span>:</span>
				<input type="email" name="NEW_EMAIL" size="40" value="<?=$arResult["AUTH"]["NEW_EMAIL"]?>" class="textinput" required tabindex="<?=$tabindex++?>" autofocus data-progression
					data-tooltip data-placement="bottom" title="<?=GetMessage('BITRONIC2_STOF_EMAIL_TITLE')?>">
				<span class="textinput-icons">
					<i class="icon-valid flaticon-check33"></i>
					<i class="icon-not-valid flaticon-x5"></i>
				</span>
			</label>
			
			<?if($arResult["AUTH"]["new_user_registration_email_confirmation"] != "Y"):?>
				<div id="sof_choose_login">
			<?endif;/*
			<label class="textinput-wrapper">
				<span class="label-text"><?echo GetMessage("BITRONIC2_STOF_LOGIN")?><span class="required-asterisk">*</span>:</span>
				<input type="text" name="NEW_LOGIN" size="30" value="<?=$arResult["AUTH"]["NEW_LOGIN"]?>" class="textinput" required tabindex="<?=$tabindex++?>" data-progression>
				<span class="textinput-icons">
					<i class="icon-valid flaticon-check33"></i>
					<i class="icon-not-valid flaticon-x5"></i>
				</span>
			</label>
			*/?>
			<input type="hidden" name="NEW_LOGIN" value="<?=$arResult["AUTH"]["NEW_LOGIN"]?>" />
			<label class="textinput-wrapper">
				<span class="label-text"><?echo GetMessage("BITRONIC2_STOF_PASSWORD")?><span class="required-asterisk">*</span>:</span>
				<input type="password" name="NEW_PASSWORD" size="30" class="textinput password" required tabindex="<?=$tabindex++?>" data-progression>
				<span class="textinput-icons">
					<span class="btn-password-toggle">
						<i class="password-shown flaticon-eye36"></i>
						<i class="password-hidden flaticon-closed40"></i>
					</span>
					<i class="icon-valid flaticon-check33"></i>
					<i class="icon-not-valid flaticon-x5"></i>
				</span>
			</label>
			<label class="textinput-wrapper">
				<span class="label-text"><?echo GetMessage("BITRONIC2_STOF_RE_PASSWORD")?><span class="required-asterisk">*</span>:</span>
				<input type="password" name="NEW_PASSWORD_CONFIRM" size="30" class="textinput password" required tabindex="<?=$tabindex++?>" data-progression>
				<span class="textinput-icons">
					<span class="btn-password-toggle">
						<i class="password-shown flaticon-eye36"></i>
						<i class="password-hidden flaticon-closed40"></i>
					</span>
					<i class="icon-valid flaticon-check33"></i>
					<i class="icon-not-valid flaticon-x5"></i>
				</span>
			</label>
			
			<?if($arResult["AUTH"]["new_user_registration_email_confirmation"] != "Y"):?>
				</div> <!-- #sof_choose_login -->
				<script>
					ChangeGenerate(<?= (($_POST["NEW_GENERATE"] != "N") ? "true" : "false") ?>);
				</script>
			<?endif;?>
			
			<label class="textinput-wrapper">
				<span class="label-text"><?=GetMessage("BITRONIC2_STOF_NAME")?><span class="required-asterisk">*</span>:</span>
				<input type="text" name="NEW_NAME" size="40" value="<?=$arResult["AUTH"]["NEW_NAME"]?>" class="textinput" required tabindex="<?=$tabindex++?>" data-progression>
				<span class="textinput-icons">
					<i class="icon-valid flaticon-check33"></i>
					<i class="icon-not-valid flaticon-x5"></i>
				</span>
			</label>
			
			<label class="textinput-wrapper">
				<span class="label-text"><?=GetMessage("BITRONIC2_STOF_LASTNAME")?><span class="required-asterisk">*</span>:</span>
				<input type="text" name="NEW_LAST_NAME" size="40" value="<?=$arResult["AUTH"]["NEW_LAST_NAME"]?>" class="textinput" required tabindex="<?=$tabindex++?>" data-progression>
				<span class="textinput-icons">
					<i class="icon-valid flaticon-check33"></i>
					<i class="icon-not-valid flaticon-x5"></i>
				</span>
			</label>
			
			<?//CAPTCHA
			if($arResult["AUTH"]["captcha_registration"] == "Y"):?>
				<div class="anti-robot clearfix">
					<label class="textinput-wrapper">
						<input type="text" name="captcha_word" size="30" maxlength="50" value="" class="textinput" required tabindex="<?=$tabindex++?>" data-progression>
						<span class="textinput-icons">
							<i class="icon-valid flaticon-check33"></i>
							<i class="icon-not-valid flaticon-x5"></i>
						</span>
						<span class="label-text"><?=GetMessage("BITRONIC2_CAPTCHA_REGF_PROMT")?><span class="required-asterisk">*</span>:</span>
						<span class="captcha img-container centering">
							<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["AUTH"]["capCode"]?>" width="180" height="40" alt="CAPTCHA">
						</span>
					</label>
					<input type="hidden" name="captcha_sid" value="<?=$arResult["AUTH"]["capCode"]?>">
				</div>
			<?endif?>
			
			<label class="checkbox-styled">
				<input type="checkbox" name="stof-accept-agreement" id="stof-accept-agreement" <?=(isset($_POST['stof-accept-agreement']) && $_POST['stof-accept-agreement'] == 'Y') ? " checked=\"checked\"" : ""?> value="Y">
				<span class="checkbox-content" tabindex="<?=$tabindex++?>">
					<i class="flaticon-check14"></i>
					<?=GetMessage("BITRONIC2_STOF_I_ACCEPT")?> <a target="_blank lick" href="<?=$arParams['URL_SHOP_RULES']?>"><span class="text"><?=GetMessage("BITRONIC2_STOF_SHOP_RULES")?></span></a>
				</span>
			</label>
			<?if (CModule::IncludeModule('subscribe')):?>
			<div>
				<label class="checkbox-styled">
					<input type="checkbox" name="RZ_SUBSCRIBE_ACCEPT" id="RZ_SUBSCRIBE_ACCEPT" <?=(isset($_POST['RZ_SUBSCRIBE_ACCEPT']) && $_POST['RZ_SUBSCRIBE_ACCEPT'] == 'Y') ? " checked=\"checked\"" : ""?> value="Y" tabindex="<?=$tabindex++?>">
					<span class="checkbox-content" tabindex="<?=$tabindex++?>">
						<i class="flaticon-check14"></i>
						<?=GetMessage("BITRONIC2_SUBSCRIBE_ACCEPT")?>
					</span>
				</label>
			</div>
			<? endif ?>
			
			<div class="textinput-wrapper submit-wrap">
				<button type="submit" class="btn-submit btn-main" tabindex="<?=$tabindex++?>">
					<span class="btn-text"><?=GetMessage("BITRONIC2_STOF_CONTINUE")?></span>
				</button>
			</div>
		</form>

	</div>
<?endif?>
</div>
<div class="required-info"><span class="required-asterisk">*</span> <?=GetMessage("BITRONIC2_STOF_REQUIED_FIELDS_NOTE")?></div>
<?if($arResult["AUTH"]["new_user_registration"]=="Y"):?>
	<div class="required-info"><?echo GetMessage("BITRONIC2_STOF_EMAIL_NOTE")?></div>
<?endif;?>
<div class="required-info"><?echo GetMessage("BITRONIC2_STOF_PRIVATE_NOTES")?></div>