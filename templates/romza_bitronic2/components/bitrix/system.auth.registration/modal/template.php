<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if ($arParams['EMPTY'] === true || $USER->IsAdmin()) {
	echo GetMessage('BITRONIC2_AUTH_REGISTER');
	return;
}
\Bitrix\Main\Localization\Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH . '/header.php');
$pathToRules = COption::GetOptionString(CRZBitronic2Settings::getModuleId(),'path_tu_rules_privacy',SITE_DIR.'personal/rules/personal_data.php');
// ShowMessage($arParams["~AUTH_RESULT"]);

if ($_REQUEST['rz_ajax'] == 'Y' && $_REQUEST['AUTH_FORM'] == 'Y') {
	if(isset($APPLICATION->arAuthResult) && $APPLICATION->arAuthResult !== true) {
		$arResult['ERROR_MESSAGE'] = $APPLICATION->arAuthResult;
	} else {
		echo '<script>window.location.reload();</script>';
	}
	if (is_array($arResult['ERROR_MESSAGE']) && $arResult['ERROR_MESSAGE']['TYPE'] == 'OK') {
		if ($arResult['USE_EMAIL_CONFIRMATION'] === 'Y') {
			$arResult['ERROR_MESSAGE']['MESSAGE'] .= '<br>' . GetMessage('BITRONIC2_AUTH_EMAIL_SENT');
		} else {
			echo '<script>setTimeout(function(){window.location.reload();}, 3000);</script>';
		}
	}
	CRZBitronic2CatalogUtils::ShowMessage($arResult['ERROR_MESSAGE']);
}
/*
$id = 'bxdinamic_bitronic2_reg_string';

?>
<span id="<?=$id?>">
	<?$frame = $this->createFrame($id, false)->begin(CRZBitronic2Composite::insertCompositLoader());?>
	/ <a href="<?=POST_FORM_ACTION_URI?>" class="pseudolink" data-toggle="modal" data-target="#modal_registration"><span class="link-text"><?=GetMessage("BITRONIC2_AUTH_REGISTER")?></span></a>
	<?$frame->end();?>
</span>

<?$this->SetViewTarget('bitronic2_modal_register');*/?>
		<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform" enctype="multipart/form-data" class="form_registration" id="modal_form_registration">
			<input type="hidden" name="AUTH_FORM" value="Y" />
            <input type="hidden" name="privacy_policy" value="N"/>
			<input type="hidden" name="TYPE" value="REGISTRATION" />

			<div style="display:none" class="registration_alert">
				<?=GetMessage('BITRONIC2_AUTH_FORM_ALERT')?>
			</div>

			<div class="title-h2"><span class="header-text"><?=GetMessage("BITRONIC2_AUTH_REGISTER")?><span class="hidden-xs"><?=GetMessage('BITRONIC2_REGISTER_IN_OUR_SHOP')?></span></span></div>
			<div class="switch-form-block"><?=GetMessage('BITRONIC2_REGISTER_OR')?><button type="button" class="btn-form-switch" data-toggle="modal" data-target="#modal_login"><span class="btn-text"><?=GetMessage('BITRONIC2_REGISTER_ENTER')?></span></button></div>
			<?/*
			<div class="generate-pass-wrap">
				<label class="radio-styled">
					<input type="radio" name="generate-pass" value="true" checked>
					<span class="radio-content">
						<span class="radio-fake"></span>
						<span class="text"><?=GetMessage('BITRONIC2_REGISTER_GENERATE_PASS_AUTO')?></span>
					</span>
				</label>
				<label class="radio-styled">
					<input type="radio" name="generate-pass" value="false" checked>
					<span class="radio-content">
						<span class="radio-fake"></span>
						<span class="text"><?=GetMessage('BITRONIC2_REGISTER_GENERATE_PASS_MANUAL')?></span>
					</span>
				</label>
			</div>
			*/?>
			
			<?
			$arResult["SHOW_FIELDS"] = array('EMAIL', 'PASSWORD', 'CONFIRM_PASSWORD', 'NAME', 'LAST_NAME');
			$arResult["REQUIRED_FIELDS_FLAGS"] = array('LOGIN' => 'Y', 'PASSWORD' => 'Y', 'CONFIRM_PASSWORD' => 'Y');
			if($arResult['EMAIL_REQUIRED']) $arResult["REQUIRED_FIELDS_FLAGS"]['EMAIL'] = 'Y';
			$tabindex = 1;
			foreach ($arResult["SHOW_FIELDS"] as $FIELD):
				if($FIELD == "AUTO_TIME_ZONE" && $arResult["TIME_ZONE_ENABLED"] == true):
				else:?>
				<label class="textinput-wrapper">
					<span class="label-text"><span class="inner-wrap"><span class="inner"><?=GetMessage("BITRONIC2_REGISTER_FIELD_".$FIELD)?><?if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?><span class="required-asterisk">*</span><?endif?>:</span></span></span>
					<?
					switch ($FIELD)
					{
						case "PASSWORD":?>
							<input 
								size="30" 
								type="password" 
								name="USER_<?=$FIELD?>" 
								value="<?=$arResult["USER_".$FIELD]?>" 
								autocomplete="off" 
								id="reg-password" 
								class="textinput password" 
								<?=($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y")? 'required' :''?>
								tabindex="<?=$tabindex++?>"
								data-progression 
								data-helper="<?=GetMessage("BITRONIC2_REGISTER_FIELD_HELPER_".$FIELD)?>"
							>
							<span class="textinput-icons">
									<span class="btn-password-toggle">
										<i class="password-shown flaticon-eye36"></i>
										<i class="password-hidden flaticon-closed40"></i>
									</span>
									<i class="icon-valid flaticon-check33"></i>
									<i class="icon-not-valid flaticon-x5"></i>
							</span>
						<?
						break;
						case "CONFIRM_PASSWORD":
							?>
							<input 
								size="30" 
								type="password" 
								name="USER_<?=$FIELD?>" 
								value="<?=$arResult["USER_".$FIELD]?>" 
								autocomplete="off" 
								id="reg-password-repeat" 
								class="textinput password" 
								<?=($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y")? 'required' :''?>
								tabindex="<?=$tabindex++?>"
								data-progression 
								data-helper="<?=GetMessage("BITRONIC2_REGISTER_FIELD_HELPER_".$FIELD)?>"
							>
							<span class="textinput-icons">
								<span class="btn-password-toggle">
									<i class="password-shown flaticon-eye36"></i>
									<i class="password-hidden flaticon-closed40"></i>
								</span>
								<i class="icon-valid flaticon-check33"></i>
								<i class="icon-not-valid flaticon-x5"></i>
							</span><?
						break;
						
						default:
							if ($FIELD == "PERSONAL_BIRTHDAY"):?><small><?=$arResult["DATE_FORMAT"]?></small><br /><?endif;?>
							
							<input 
								size="30"
								type="<?=($FIELD == "EMAIL") ? 'email' : 'text'?>" 
								name="USER_<?=$FIELD?>" 
								value="<?=$arResult["USER_".$FIELD]?>" 
								class="textinput" 
								<?=($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y")? 'required' :''?>
								tabindex="<?=$tabindex++?>"
								data-progression 
								data-helper="<?=GetMessage("BITRONIC2_REGISTER_FIELD_HELPER_".$FIELD)?><?=(empty($arParams[$FIELD.'_NOTICE'])?'':'.<br>'.$arParams[$FIELD.'_NOTICE'])?>"
							/>
							<span class="textinput-icons">
								<i class="icon-valid flaticon-check33"></i>
								<i class="icon-not-valid flaticon-x5"></i>
							</span>
							<?							
							if ($FIELD == "PERSONAL_BIRTHDAY")
							{
								$APPLICATION->IncludeComponent(
									'bitrix:main.calendar',
									'',
									array(
										'SHOW_INPUT' => 'N',
										'FORM_NAME' => 'regform',
										'INPUT_NAME' => 'USER_[PERSONAL_BIRTHDAY]',
										'SHOW_TIME' => 'N'
									),
									null,
									array("HIDE_ICONS"=>"Y")
								);
							}
					}
					?>
				</label>				
				<?endif;
			endforeach;?>
				<input type="hidden" name="USER_LOGIN" value="<?=$arResult['USER_LOGIN']?>" />

			<?$frame = $this->createFrame()->begin('');?>
			<?if ($arResult["USE_CAPTCHA"] == "Y"):?>
                <div class="anti-robot clearfix">
                    <label class="textinput-wrapper">
                        <input type="text" name="captcha_word" maxlength="50" value="" id="captcha" class="textinput" required tabindex="<?=$tabindex++?>" data-progression data-helper="<?=GetMessage("BITRONIC2_REGISTER_FIELD_HELPER_CAPTCHA")?>">
                        <span class="textinput-icons">
                            <i class="icon-valid flaticon-check33"></i>
                            <i class="icon-not-valid flaticon-x5"></i>
                        </span>
                        <span class="label-text"><span class="inner-wrap"><span class="inner"><?=GetMessage("BITRONIC2_REGISTER_CAPTCHA_PROMT")?><span class="required-asterisk">*</span>:</span></span></span>
                        <span class="captcha img-container centering"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" alt="<?=GetMessage("BITRONIC2_REGISTER_CAPTCHA_TITLE")?>"></span>
                    </label>
                    <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
                </div>
			<?endif;?>
			<?if (CModule::IncludeModule('subscribe')):?>
			<label class="checkbox-styled">
				<input type="checkbox" name="RZ_SUBSCRIBE_ACCEPT" id="RZ_SUBSCRIBE_ACCEPT" <?=(isset($_POST['RZ_SUBSCRIBE_ACCEPT']) && $_POST['RZ_SUBSCRIBE_ACCEPT'] == 'Y') ? " checked=\"checked\"" : ""?> value="Y" tabindex="<?=$tabindex++?>">
				<span class="checkbox-content" tabindex="<?=$tabindex++?>">
					<i class="flaticon-check14"></i>
					<?=GetMessage("BITRONIC2_SUBSCRIBE_ACCEPT")?>
				</span>
			</label>
			<?endif?>
			<?$frame->end();?>
            <label class="checkbox-styled">
                <input value="Y" type="checkbox" name="privacy_policy">
                <span class="checkbox-content" tabindex="5">
        <i class="flaticon-check14"></i><?=GetMessage('BITRONIC2_I_ACCEPT')?>
                    <a href="<?=$pathToRules?>" class="link"><span class="text"><?=GetMessage('BITRONIC2_POLITIC_PRIVICE')?></span></a>
    </span>
            </label>
			<div class="textinput-wrapper submit-wrap">
				<button type="submit" class="btn-submit btn-main disabled" name="Register" tabindex="<?=$tabindex++?>" value="Y">
					<span class="btn-text"><?=GetMessage("BITRONIC2_AUTH_REGISTER")?></span>
				</button>
			</div>
			
			<div class="required-info"><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></div>
			<div class="required-info"><span class="required-asterisk">*</span> &mdash; <?=GetMessage("BITRONIC2_AUTH_REQ")?></div>
		</form>
		<footer class="modal-footer">
			<div class="auth-privilegies">
			<?
			$APPLICATION->IncludeComponent('bitrix:main.include', '',
				array(
					"AREA_FILE_SHOW" => "file",
					"EDIT_TEMPLATE" => "include_areas_template.php",
					"PATH" => SITE_DIR."include_areas/header/auth_privilegies.php"
				),
				false,
				array("HIDE_ICONS"=>"N")
			);
			?>
			</div>
		</footer>
<?//$this->EndViewTarget();
