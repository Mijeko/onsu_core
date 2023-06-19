<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?
global $rz_b2_options;
$APPLICATION->AddChainItem($APPLICATION->GetTitle());
// ShowMessage($arParams["~AUTH_RESULT"]);
// ShowMessage($arResult['ERROR_MESSAGE']);

$arFormFields = array(
	'LOGIN' => array(
		'REQUIRED' => true,
	),
	'PASSWORD' => array(
		'REQUIRED' => true,
	),
);
\Bitrix\Main\Localization\Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH . '/header.php');
$pathToRules = COption::GetOptionString(CRZBitronic2Settings::getModuleId(),'path_tu_rules_privacy',SITE_DIR.'personal/rules/personal_data.php');
?>

<main class="container new-password-page">

	<h1><?$APPLICATION->ShowTitle()?></h1>
	
	<p><?=GetMessage("AUTH_PLEASE_AUTH")?></p>
	
	<form data-toggle="validator" name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>" class="form_forgot-pass">

		<input type="hidden" name="AUTH_FORM" value="Y" />
		<input type="hidden" name="TYPE" value="AUTH" />
        <input type="hidden" name="privacy_policy" value="N"/>
		<?if (strlen($arResult["BACKURL"]) > 0):?>
			<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
		<?endif?>
		<?foreach ($arResult["POST"] as $key => $value):?>
			<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
		<?endforeach?>

		
		<?foreach($arFormFields as $code => $arField):?>
			<?switch($code):
				case 'CONFIRM_PASSWORD':
				case 'PASSWORD':?>
					<label class="textinput-wrapper">
						<span class="text"><?=GetMessage("AUTH_".$code)?><?if($arField['REQUIRED']):?><span class="required-asterisk">*</span><?endif?>:</span>
						<input type="password" name="USER_<?=$code?>" maxlength="50" value="" class="textinput" <?=($arField['REQUIRED']?'required':'')?> />
					</label>
				<?break;
				default:?>
					<label class="textinput-wrapper">
						<span class="text"><?=GetMessage("AUTH_".$code)?><?if($arField['REQUIRED']):?><span class="required-asterisk">*</span><?endif?>:</span>
						<input type="<?=($code=='LOGIN'?'email':'text')?>" name="USER_<?=$code?>" maxlength="50" value="<?=$arResult["LAST_".$code]?>" class="textinput" <?=($arField['REQUIRED']?'required':'')?> />
					</label>
			<?endswitch?>
		<?endforeach?>
	<?/* CAPTCHA */
	if ($arResult["CAPTCHA_CODE"]):?>
		<label>
			<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
			<span class="text"></span>
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
		</label>
		<label>
			<span class="text"><?=GetMessage("AUTH_CAPTCHA_PROMT")?><span class="required-asterisk">*</span></span>
			<input class="textinput" type="text" name="captcha_word" maxlength="50" value="" size="15" />
		</label>
	<?endif?>
    <?if (empty($arResult["CAPTCHA_CODE"]) && $rz_b2_options['show_google_captcha_in_auth'] == 'Y' && CRZBitronic2CatalogUtils::checkGoogleCaptchaShow()):?>
        <label>
            <?CRZBitronic2CatalogUtils::showGoogleCaptcha()?>
        </label>
    <?endif?>
	
	<?if ($arResult["STORE_PASSWORD"] == "Y"):?>
		<label class="checkbox-styled" for="USER_REMEMBER">
			<input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y"/>
			<span class="checkbox-content">
				<i class="flaticon-check14"></i>
				<?=GetMessage("AUTH_REMEMBER_ME")?>
			</span>
		</label>
	<?endif?>
    <label class="checkbox-styled">
        <input required value="Y" type="checkbox" name="privacy_policy">
        <span class="checkbox-content" tabindex="5">
        <i class="flaticon-check14"></i><?=GetMessage('BITRONIC2_I_ACCEPT')?>
            <a href="<?=$pathToRules?>" class="link"><span class="text"><?=GetMessage('BITRONIC2_POLITIC_PRIVICE')?></span></a>
    </span>
    </label>
	<div>
		<button type="submit" class="btn-main disabled" name="Login" value="Y"><span class="text"><?=GetMessage("AUTH_AUTHORIZE")?></span></button>
	</div>

	<p></p>
	
<?if ($arParams["NOT_SHOW_LINKS"] != "Y"):?>
	<p>
		<a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a>
	</p>
<?endif?>

<?if($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"):?>
	<p>
		<a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_REGISTER")?></a><br />
		<?=GetMessage("AUTH_FIRST_ONE")?>
	</p>
<?endif?>

	</form>
	


<script type="text/javascript">
<?if (strlen($arResult["LAST_LOGIN"])>0):?>
try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
<?else:?>
try{document.form_auth.USER_LOGIN.focus();}catch(e){}
<?endif?>
</script>

<?if($arResult["AUTH_SERVICES"]):?>
<?
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
?>
<?endif?>

</main>