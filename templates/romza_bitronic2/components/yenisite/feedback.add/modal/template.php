<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
if ($arParams['EMPTY']) { 
	echo $arParams['TITLE'];
	return;
}
if ($arResult['SUCCESS']) {
	CRZBitronic2CatalogUtils::ShowMessage(Array("MESSAGE" => $arResult['SUCCESS_TEXT'], "TYPE" => "OK"));
}
if ($arResult['ERROR']) {
	CRZBitronic2CatalogUtils::ShowMessage(Array("MESSAGE" => $arResult['ERROR'], "TYPE" => "ERROR"));
}
\Bitrix\Main\Localization\Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH . '/header.php');
$pathToRules = COption::GetOptionString(CRZBitronic2Settings::getModuleId(),'path_tu_rules_privacy',SITE_DIR.'personal/rules/personal_data.php');
?>
<form method="post" action="#" class="<?=$arParams['FORM']?>">
    <input type="hidden" name="privacy_policy" value="N"/>
	<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';?>
	<div class="title-h2"><span class="header-text"><?=$arParams['TITLE']?></span></div>
	
	<?foreach($arResult['FIELDS'] as $arItem):?>
		<label class="textinput-wrapper">
			<span class="label-text"><span class="inner-wrap"><span class="inner"><?=$arItem['NAME'];?><?=($arItem['IS_REQUIRED'] == 'Y')?'<span class="required-asterisk">*</span>':'';?>:</span></span></span>
			<?=$arItem['HTML'];?>
			<span class="textinput-icons">
				<i class="icon-valid flaticon-check33"></i>
				<i class="icon-not-valid flaticon-x5"></i>
			</span>
		</label>
  <?endforeach;?>
	<? if (!empty($arResult["CAPTCHA_CODE"])): ?>
		<label class="textinput-wrapper">
			<span class="label-text"><span class="inner-wrap"><span class="inner"><?//=GetMessage("BITRONIC2_FEED_MODAL_CAPTCHA_ALT")?></span></span></span>
			<img src="/bitrix/tools/captcha.php?captcha_code=<?= $arResult["CAPTCHA_CODE"] ?>" alt="<?=GetMessage("BITRONIC2_FEED_MODAL_CAPTCHA_ALT")?>">
			<input type="hidden" name="captcha_code" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
		</label>
		<label class="textinput-wrapper">
			<span class="label-text"><span class="inner-wrap"><span class="inner"><?=GetMessage('BITRONIC2_FEED_MODAL_CAPTCHA_TITLE')?><span class="required-asterisk">*</span>:</span></span></span>
			<input type="text" name="captcha_word" class="textinput" required="">
			<span class="textinput-icons">
				<i class="icon-valid flaticon-check33"></i>
				<i class="icon-not-valid flaticon-x5"></i>
			</span>
		</label>
	<? endif; ?>
    <label class="checkbox-styled">
        <input value="Y" type="checkbox" name="privacy_policy">
        <span class="checkbox-content" tabindex="5">
			<i class="flaticon-check14"></i><?=GetMessage('BITRONIC2_I_ACCEPT')?>
            <a href="<?=$pathToRules?>" class="link"><span class="text"><?=GetMessage('BITRONIC2_POLITIC_PRIVICE')?></span></a>
		</span>
    </label>
	<div class="textinput-wrapper submit-wrap">
		<button type="submit" class="btn-submit btn-main disabled"><span class="btn-text"><?=GetMessage('BITRONIC2_FEED_MODAL_SEND')?></span></button>
	</div>				
	
	<div class="required-info"><span class="required-asterisk">*</span> &mdash; <?=GetMessage('BITRONIC2_FEED_MODAL_REQUIRED')?></div>
</form>