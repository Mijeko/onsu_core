<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
\Bitrix\Main\Loader::includeModule('yenisite.core');
$isAjax = Yenisite\Core\Tools::isAjax();
\Bitrix\Main\Localization\Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH . '/header.php');
$pathToRules = COption::GetOptionString(CRZBitronic2Settings::getModuleId(),'path_tu_rules_privacy',SITE_DIR.'personal/rules/personal_data.php');
if(!$isAjax) {
	$this->setFrameMode(true);
}
?>
<? if ($isAjax && $arResult['SUCCESS'] === true) {
	CRZBitronic2CatalogUtils::ShowMessage(array('MESSAGE' => $arResult['SUCCESS_TEXT'], 'TYPE' => 'OK')); ?>
	<script type="text/javascript">
		(typeof(jQuery) != 'undefined')
		&& (function ($) {
			$('#modal_subscribe_product').modal('hide');
		})(jQuery);
	</script>
<? } else if ($isAjax && !empty($arResult['ERROR'])) {
	CRZBitronic2CatalogUtils::ShowMessage(array('MESSAGE' => $arResult['ERROR'], 'TYPE' => 'ERROR'));
} ?>
<? if (!$isAjax): ?>
	<? \Yenisite\Core\Ajax::saveParams($this, $arParams, $templateName); ?>
	<div class="modal fade modal-form" id="modal_subscribe_product" tabindex="-1">
		<div class="modal-dialog">
			<button class="btn-close" data-toggle="modal" data-target="#modal_subscribe_product">
				<span class="btn-text"><?= GetMessage("RZ_ZAKRIT") ?></span>
				<i class="flaticon-close47"></i>
			</button>
			<form method="post" action="#" data-rzoption="captcha-when-in-stock" id="modal_subscribe_product_form"
						class="form_subscribe_product modal-form-w-icons"<? \Yenisite\Core\Ajax::printAjaxDataAttr($this) ?>>
				<? endif ?>
				<? if ($isAjax): ?>
                    <input type="hidden" name="privacy_policy" value="N"/>
					<input type="hidden" name="<?= $arResult['CODE'] ?>[PRODUCT]" value="<?=$_REQUEST['PRODUCT'] ? htmlspecialcharsbx($_REQUEST['PRODUCT']) : htmlspecialcharsbx($_REQUEST[$arResult['CODE']]['PRODUCT']) ?>"/>
					<input type="hidden" name="FORM_CODE" value="<?= $arResult['CODE'] ?>"/>
					<div class="title-h2"><span class="header-text"><?= GetMessage('RZ_SUBSCRIBE_TITLE') ?></span></div>
					<div class="help-block"><?= GetMessage('RZ_SUBSCRIBE_TEXT') ?></div>
					<div>
						<div class="text-w-icon"><?= GetMessage("RZ_VASH") ?> e-mail<span class="required-asterisk">*</span>:</div>
						<label class="textinput-wrapper email">
							<i class="flaticon-mail9 icon-before-input"></i>
							<input type="email" name="<?= $arResult['CODE'] ?>[EMAIL]"
								   id="modal_subscribe_product_email"
								   class="textinput" required
								   value="<?= (!empty($arResult['DATA']['EMAIL'])) ? $arResult['DATA']['EMAIL'] : $USER->GetEmail() ?>">
					<span class="textinput-icons">
						<i class="icon-valid flaticon-check33"></i>
						<i class="icon-not-valid flaticon-x5"></i>
					</span>
						</label>
					</div>
                    <? if (!empty($arResult["CAPTCHA_CODE"])): ?>
                        <div>
                            <div><?=GetMessage('RZ_CAPTCHA_PROTECT_TEXT')?>:</div>
                            <div class="modal-captcha-wrap">
                                <img src="/bitrix/tools/captcha.php?captcha_code=<?= $arResult["CAPTCHA_CODE"] ?>" alt="<?=GetMessage('RZ_CAPTCHA_INPUT_WORD')?>">
                                <input type="hidden" name="captcha_code" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                            </div>
                            <div><?=GetMessage('RZ_CAPTCHA_INPUT_WORD')?><span class="required-asterisk">*</span>:</div>
                            <label class="textinput-wrapper modal-captcha-input-wrap">
                                <input type="text" name="captcha_word" id="modal_quick-buy_captcha" class="textinput" required="">
                                <span class="textinput-icons">
                            <i class="icon-valid flaticon-check33"></i>
                            <i class="icon-not-valid flaticon-x5"></i>
                        </span>
                            </label>
                        </div>
                    <?endif?>
                    <label class="checkbox-styled">
                        <input value="Y" type="checkbox" name="privacy_policy">
                        <span class="checkbox-content" tabindex="5">
			<i class="flaticon-check14"></i><?=GetMessage('BITRONIC2_I_ACCEPT')?>
                            <a href="<?=$pathToRules?>" class="link"><span class="text"><?=GetMessage('BITRONIC2_POLITIC_PRIVICE')?></span></a>
		</span>
                    </label>
					<div class="submit-wrap">
						<button type="submit" class="btn-submit btn-main disabled">
							<span class="text"><?= GetMessage("RZ_OTPRAVIT") ?></span>
						</button>
					</div>
					<div class="required-info"><span
							class="required-asterisk">*</span> &mdash; <?= GetMessage("RZ_POLYA__OTMECHENNIE_ZVEZDOCHKOJ__OBYAZATELNI_DLYA_ZAPOLNENIYA") ?>
					</div>
				<? endif ?>
				<? if (!$isAjax): ?>
			</form>
		</div>
	</div>
<? endif ?>