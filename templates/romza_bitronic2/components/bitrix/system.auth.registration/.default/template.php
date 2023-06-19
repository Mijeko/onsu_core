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
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$APPLICATION->AddChainItem($APPLICATION->GetTitle());

$arFormFields = array(
	'NAME' => array(
		'REQUIRED' => false,
	),
	'LAST_NAME' => array(
		'REQUIRED' => false,
	),/*
	'LOGIN' => array(
		'REQUIRED' => true,
	),*/
	'EMAIL' => array(
		'REQUIRED' => true,
	),
	'PASSWORD' => array(
		'REQUIRED' => true,
	),
	'CONFIRM_PASSWORD' => array(
		'REQUIRED' => true,
	),
);
\Bitrix\Main\Localization\Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH . '/header.php');
$pathToRules = COption::GetOptionString(CRZBitronic2Settings::getModuleId(),'path_tu_rules_privacy',SITE_DIR.'personal/rules/personal_data.php');
?>
<main class="container new-password-page">
	<h1><?$APPLICATION->ShowTitle()?></h1>
<?
// ShowMessage($arParams["~AUTH_RESULT"]);
?>
<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) &&  $arParams["AUTH_RESULT"]["TYPE"] === "OK"):?>
	<p><?echo GetMessage("AUTH_EMAIL_SENT")?></p>
<?else:?>

<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
	<p><?echo GetMessage("AUTH_EMAIL_WILL_BE_SENT")?></p>
<?endif?>
<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform" class="form_forgot-pass">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
    <input type="hidden" name="privacy_policy" value="N"/>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="REGISTRATION" />
	<input type="hidden" name="USER_LOGIN" value="<?=$arResult['USER_LOGIN']?>" />

	<?foreach($arFormFields as $code => $arField):?>
		<?switch($code):
			case 'CONFIRM_PASSWORD':
			case 'PASSWORD':?>
				<label class="textinput-wrapper">
					<span class="text"><?=GetMessage("AUTH_".$code)?><?if($arField['REQUIRED']):?><span class="required-asterisk">*</span><?endif?>:</span>
					<input type="password" name="USER_<?=$code?>" maxlength="50" value="<?=$arResult["USER_".$code]?>" class="textinput" <?=($arField['REQUIRED']?'required':'')?> />
				</label>
			<?break;
			default:?>
				<label class="textinput-wrapper">
					<span class="text"><?=GetMessage("AUTH_".$code)?><?if($arField['REQUIRED']):?><span class="required-asterisk">*</span><?endif?>:</span>
					<input type="<?=($code=='EMAIL'?'email':'text')?>" name="USER_<?=$code?>" maxlength="50" value="<?=$arResult["USER_".$code]?>" <?=($arField['REQUIRED']?'required':'')?> class="textinput"<?if($code=='EMAIL'):?> data-tooltip data-placement="right" title="<?=GetMessage('AUTH_EMAIL_NOTICE')?>"<?endif?> />
				</label>
		<?endswitch?>
	<?endforeach?>
	
	
	
	<?/* CAPTCHA */
	if ($arResult["USE_CAPTCHA"] == "Y"):?>
		<label>
			<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
			<span class="text"></span>
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
		</label>
		<label class="textinput-wrapper">
			<span class="text"><?=GetMessage("CAPTCHA_REGF_PROMT")?><span class="required-asterisk">*</span>:</span>
			<input type="text" name="captcha_word" maxlength="50" value="" class="textinput"/>
		</label>
	<?endif?>

	<?if (CModule::IncludeModule('subscribe')):?>
	<label class="checkbox-styled">
		<input type="checkbox" name="RZ_SUBSCRIBE_ACCEPT" id="RZ_SUBSCRIBE_ACCEPT" <?=(isset($_POST['RZ_SUBSCRIBE_ACCEPT']) && $_POST['RZ_SUBSCRIBE_ACCEPT'] == 'Y') ? " checked=\"checked\"" : ""?> value="Y" tabindex="<?=$tabindex++?>">
		<span class="checkbox-content" tabindex="<?=$tabindex++?>">
			<i class="flaticon-check14"></i>
			<?=GetMessage("BITRONIC2_SUBSCRIBE_ACCEPT")?>
		</span>
	</label>
	<? endif ?>
        <label class="checkbox-styled">
            <input value="Y" type="checkbox" name="privacy_policy">
            <span class="checkbox-content" tabindex="5">
        <i class="flaticon-check14"></i><?=GetMessage('BITRONIC2_I_ACCEPT')?>
                <a href="<?=$pathToRules?>" class="link"><span class="text"><?=GetMessage('BITRONIC2_POLITIC_PRIVICE')?></span></a>
    </span>
        </label>
	
	<div>
		<button type="submit" class="btn-main disabled" name="Register" value="Y"><span class="text"><?=GetMessage("AUTH_REGISTER")?></span></button>
	</div>
	
	<div>
		<p></p>
		<p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
		<p><span class="required-asterisk">*</span> - <?=GetMessage("AUTH_REQ")?></p>
		
		<p>
			<a href="<?=$arResult["AUTH_AUTH_URL"]?>" rel="nofollow"><b><?=GetMessage("AUTH_AUTH")?></b></a>
		</p>
	</div>
</form>
<script type="text/javascript">
document.bform.USER_NAME.focus();
</script>

<?endif?>
</main>