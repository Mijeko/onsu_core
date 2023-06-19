<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
\Bitrix\Main\Loader::includeModule('yenisite.core');
$isAjax = Yenisite\Core\Tools::isAjax();
\Bitrix\Main\Localization\Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH . '/header.php');
$pathToRules = COption::GetOptionString(CRZBitronic2Settings::getModuleId(),'path_tu_rules_privacy',SITE_DIR.'personal/rules/personal_data.php');
?>
<? if ($isAjax && $arResult['SUCCESS'] === true) {
	CRZBitronic2CatalogUtils::ShowMessage(array('MESSAGE' => $arResult['SUCCESS_TEXT'], 'TYPE' => 'OK')); ?>
	<script type="text/javascript">
		(typeof(jQuery) != 'undefined')
		&& (function ($) {
			$('#modal_cry-for-price').modal('hide');
		})(jQuery);
	</script>
<? } else if ($isAjax && !empty($arResult['ERROR'])) {
	CRZBitronic2CatalogUtils::ShowMessage(array('MESSAGE' => $arResult['ERROR'], 'TYPE' => 'ERROR'));
} ?>
<? if (!$isAjax): ?>
	<? \Yenisite\Core\Ajax::saveParams($this, $arParams, $templateName); ?>
	<div class="modal fade modal-form" id="modal_cry-for-price" tabindex="-1">
		<div class="modal-dialog">
			<button class="btn-close" data-toggle="modal" data-target="#modal_cry-for-price">
				<span class="btn-text"><?= GetMessage("RZ_ZAKRIT") ?></span>
				<i class="flaticon-close47"></i>
			</button>
			<form method="post" action="#" data-rzoption="captcha-cry-for-price" class="form_cry-for-price modal-form-w-icons"<? \Yenisite\Core\Ajax::printAjaxDataAttr($this) ?>>
				<? endif ?>
				<? if ($isAjax): ?>
					<input type="hidden" name="<?= $arResult['CODE'] ?>[PRODUCT]" value="<?= $_REQUEST['PRODUCT'] ? htmlspecialcharsbx($_REQUEST['PRODUCT']) : htmlspecialcharsbx($_REQUEST[$arResult['CODE']]['PRODUCT']) ?>"/>
					<input type="hidden" name="<?= $arResult['CODE'] ?>[CURRENCY]" value="<?= $_REQUEST['CURRENCY'] ? htmlspecialcharsbx($_REQUEST['CURRENCY']) : htmlspecialcharsbx($_REQUEST[$arResult['CODE']]['CURRENCY']) ?>"/>
					<input type="hidden" name="<?= $arResult['CODE'] ?>[PRICE_TYPE_ID]" value="<?= $_REQUEST['PRICE_TYPE'] ? htmlspecialcharsbx($_REQUEST['PRICE_TYPE']) : htmlspecialcharsbx($_REQUEST[$arResult['CODE']]['PRICE_TYPE_ID']) ?>"/>
					<input type="hidden" name="<?= $arResult['CODE'] ?>[PRICE]" value="<?= $_REQUEST['PRICE'] ? htmlspecialcharsbx($_REQUEST['PRICE']) : htmlspecialcharsbx($_REQUEST[$arResult['CODE']]['PRICE']) ?>"/>
					<input type="hidden" name="FORM_CODE" value="<?= $arResult['CODE'] ?>"/>
					<input type="hidden" name="CONVERT_CURRENCY" value="Y"/>
                    <input type="hidden" name="privacy_policy" value="N"/>
					<div class="title-h2"><span class="header-text"><?= GetMessage("RZ_POZHALOVATSYA_NA_TCENU") ?></span></div>

					<div>
						<div class="text-w-icon url"><?= GetMessage("RZ_VVEDITE_SSILKU_NA_ETOT_TOVAR_V_MAGAZINE") ?><span
								class="required-asterisk">*</span>:
						</div>
						<label class="textinput-wrapper url">
							<i class="flaticon-link49 icon-before-input"></i>
							<input type="url" name="<?= $arResult['CODE'] ?>[URL]"
								   id="modal_cry-for-price_url"
								   class="textinput" required
								   value="<?= (!empty($arResult['DATA']['URL'])) ? $arResult['DATA']['URL'] : '' ?>">
					<span class="textinput-icons">
						<i class="icon-valid flaticon-check33"></i>
						<i class="icon-not-valid flaticon-x5"></i>
					</span>
						</label>
					</div>
					<div>
						<div class="text-w-icon url"><?=GetMessage("RZ_TCENA_V_DRUGOM_MAGAZINE")?><span
								class="required-asterisk">*</span>:
						</div>
						<label class="textinput-wrapper url">
							<i class="flaticon-dollar67 icon-before-input"></i>
							<input type="text" name="<?= $arResult['CODE'] ?>[PRICE_OTHER]"
								   id="modal_cry-for-price_price_other"
								   class="textinput" required
								   value="<?= (!empty($arResult['DATA']['PRICE_OTHER'])) ? $arResult['DATA']['PRICE_OTHER'] : '' ?>">
					<span class="textinput-icons">
						<i class="icon-valid flaticon-check33"></i>
						<i class="icon-not-valid flaticon-x5"></i>
					</span>
						</label>
					</div>
					<div>
						<div class="text-w-icon"><?= GetMessage("RZ_VASH") ?> e-mail<span class="required-asterisk">*</span>:</div>
						<label class="textinput-wrapper email">
							<i class="flaticon-mail9 icon-before-input"></i>
							<input type="email" name="<?= $arResult['CODE'] ?>[EMAIL]"
								   id="modal_cry-for-price_email"
								   class="textinput" required
								   value="<?= (!empty($arResult['DATA']['EMAIL'])) ? $arResult['DATA']['EMAIL'] : $USER->GetEmail() ?>">
					<span class="textinput-icons">
						<i class="icon-valid flaticon-check33"></i>
						<i class="icon-not-valid flaticon-x5"></i>
					</span>
						</label>
					</div>
					<div>
						<div class="text-w-icon"><?= GetMessage("RZ_FAMILIYA_IMYA") ?> (<?= GetMessage("RZ_OTCHESTVO") ?>)<span
								class="required-asterisk">*</span>:
						</div>
						<label class="textinput-wrapper name">
							<i class="flaticon-user12 icon-before-input"></i>
							<input type="text" name="<?= $arResult['CODE'] ?>[FIO]"
								   id="modal_cry-for-price_name"
								   value="<?= (!empty($arResult['DATA']['FIO'])) ? $arResult['DATA']['FIO'] : $USER->GetFullName() ?>"
								   class="textinput" required>
					<span class="textinput-icons">
						<i class="icon-valid flaticon-check33"></i>
						<i class="icon-not-valid flaticon-x5"></i>
					</span>
						</label>
					</div>
					<div>
						<div class="text-w-icon"><?= GetMessage("RZ_VASH_TELEFON") ?>:</div>
						<label class="textinput-wrapper phone">
							<i class="flaticon-phone12 icon-before-input"></i>
							<input type="text" name="<?= $arResult['CODE'] ?>[PHONE]"
								   id="modal_cry-for-price_phone"
								   class="textinput phone-masked" value="<?= (!empty($arResult['DATA']['PHONE'])) ? $arResult['DATA']['PHONE'] : '' ?>">
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