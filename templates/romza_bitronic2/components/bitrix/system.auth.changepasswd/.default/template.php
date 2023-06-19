<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// ShowMessage($arParams["~AUTH_RESULT"]);
$APPLICATION->AddChainItem($APPLICATION->GetTitle());

$arFormFields = array(
	'LOGIN' => array(
		'REQUIRED' => true,
	),
	'CHECKWORD' => array(
		'REQUIRED' => true,
	),
	'PASSWORD' => array(
		'REQUIRED' => true,
	),	
	'CONFIRM_PASSWORD' => array(
		'REQUIRED' => true,
	),	
);
$arResult['USER_LOGIN'] = $arResult['LAST_LOGIN'];
\Bitrix\Main\Localization\Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH . '/header.php');
$pathToRules = COption::GetOptionString(CRZBitronic2Settings::getModuleId(),'path_tu_rules_privacy',SITE_DIR.'personal/rules/personal_data.php');
?>
<main class="container new-password-page">
	<h1><?$APPLICATION->ShowTitle()?></h1>

<form method="post" action="<?=$arResult["AUTH_FORM"]?>" name="bform" class="form_forgot-pass">
    <input type="hidden" name="privacy_policy" value="N"/>
	<?if (strlen($arResult["BACKURL"]) > 0): ?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
	<? endif ?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="CHANGE_PWD">
	
	<?foreach($arFormFields as $code => $arField):?>
		<?switch($code):
			case 'CONFIRM_PASSWORD':
			case 'PASSWORD':?>
				<label>
					<span class="text"><?=GetMessage("AUTH_".$code)?><?if($arField['REQUIRED']):?><span class="required-asterisk">*</span><?endif?></span>
					<input type="password" name="USER_<?=$code?>" maxlength="50" value="<?=$arResult["USER_".$code]?>" class="textinput" />
				</label>
			<?break;
			default:?>
				<label>
					<span class="text"><?=GetMessage("AUTH_".$code)?><?if($arField['REQUIRED']):?><span class="required-asterisk">*</span><?endif?></span>
					<input type="text" name="USER_<?=$code?>" maxlength="50" value="<?=$arResult["USER_".$code]?>" class="textinput" />
				</label>
		<?endswitch?>
	<?endforeach?>
	<?/* CAPTCHA */
    if ($arResult["USE_CAPTCHA"] == "Y"):?>
        <label class="textinput-wrapper">
            <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
            <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
        </label>
        <label class="textinput-wrapper">
            <span style="width: 100%" class="text"><?=GetMessage("CAPTCHA_REGF_TITLE")?><span class="required-asterisk">*</span>:</span>
            <input type="text" name="captcha_word" maxlength="50" value="" class="textinput"/>
        </label>
    <?endif?>
    <label class="checkbox-styled">
        <input value="Y" type="checkbox" name="privacy_policy">
        <span class="checkbox-content" tabindex="5">
        <i class="flaticon-check14"></i><?=GetMessage('BITRONIC2_I_ACCEPT')?>
            <a href="<?=$pathToRules?>" class="link"><span class="text"><?=GetMessage('BITRONIC2_POLITIC_PRIVICE')?></span></a>
    </span>
    </label>
	
	<div>
		<button type="submit" class="btn-main disabled" name="change_pwd" value="Y"><span class="text"><?=GetMessage("AUTH_CHANGE")?></span></button>
	</div>
	
	<div>
		<p></p>
		<p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
		<p><span class="required-asterisk">*</span> - <?=GetMessage("AUTH_REQ")?></p>
		
		<p>
			<a href="<?=$arResult["AUTH_AUTH_URL"]?>"><b><?=GetMessage("AUTH_AUTH")?></b></a>
		</p>
	</div>
</form>

<script type="text/javascript">
document.bform.USER_LOGIN.focus();
</script>
</main>