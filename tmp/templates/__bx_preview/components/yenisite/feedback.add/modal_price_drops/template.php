<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
\Bitrix\Main\Loader::includeModule('yenisite.core');
$isAjax = Yenisite\Core\Tools::isAjax();
\Bitrix\Main\Localization\Loc::loadMessages($_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/header.php');
$pathToRules = COption::GetOptionString(CRZBitronic2Settings::getModuleId(), 'path_tu_rules_privacy', SITE_DIR . 'personal/rules/personal_data.php');
?>
<? if ($isAjax && $arResult['SUCCESS'] === true) {
    CRZBitronic2CatalogUtils::ShowMessage(array('MESSAGE' => $arResult['SUCCESS_TEXT'], 'TYPE' => 'OK')); ?>
    <script type="text/javascript">
        (typeof(jQuery) != 'undefined')
        && (function ($) {
            $('#modal_inform-when-price-drops').modal('hide');
        })(jQuery);
    </script>
<? } ?>
<?
if ($isAjax && $arResult['ERROR']) {
    CRZBitronic2CatalogUtils::ShowMessage(Array("MESSAGE" => $arResult['ERROR'], "TYPE" => "ERROR"));
} ?>
<? if (!$isAjax): ?>
<? \Yenisite\Core\Ajax::saveParams($this, $arParams, $templateName); ?>
<div class="modal fade modal-form modal_inform-when-price-drops" id="modal_inform-when-price-drops" tabindex="-1">
    <div class="modal-dialog">
        <button class="btn-close" data-toggle="modal" data-target="#modal_inform-when-price-drops">
            <span class="btn-text"><?= GetMessage("RZ_ZAKRIT") ?></span>
            <i class="flaticon-close47"></i>
        </button>
        <form method="post" action="#" data-rzoption="captcha-when-price-drops"
              class="form_inform-when-price-drops modal-form-w-icons"<? \Yenisite\Core\Ajax::printAjaxDataAttr($this) ?>>
            <? endif ?>
            <? if ($isAjax): ?>
                <input type="hidden" id="modal_price_drop_product" name="<?= $arResult['CODE'] ?>[PRODUCT]"
                       value="<?= $_REQUEST['PRODUCT'] ? htmlspecialcharsbx($_REQUEST['PRODUCT']) : htmlspecialcharsbx($_REQUEST[$arResult['CODE']]['PRODUCT']) ?>"/>
                <input type="hidden" id="modal_price_drop_cur" name="<?= $arResult['CODE'] ?>[CURRENCY]"
                       value="<?= $_REQUEST['CURRENCY'] ? htmlspecialcharsbx($_REQUEST['CURRENCY']) : htmlspecialcharsbx($_REQUEST[$arResult['CODE']]['CURRENCY']) ?>"/>
                <input type="hidden" id="modal_price_drop_price_type" name="<?= $arResult['CODE'] ?>[PRICE_TYPE_ID]"
                       value="<?= $_REQUEST['PRICE_TYPE'] ? htmlspecialcharsbx($_REQUEST['PRICE_TYPE']) : htmlspecialcharsbx($_REQUEST[$arResult['CODE']]['PRICE_TYPE_ID']) ?>"/>
                <input type="hidden" name="FORM_CODE" value="<?= $arResult['CODE'] ?>"/>
                <input type="hidden" name="privacy_policy" value="N"/>
                <input type="hidden" name="ITEM_PRICE" value="<?= $_REQUEST['PRICE'] ?: $_REQUEST['ITEM_PRICE'] ?>"/>
                <input type="hidden" name="CONVERT_CURRENCY" value="Y"/>
                <div class="title-h2"><span
                            class="header-text"><?= GetMessage("RZ_SOOBSHIT_O_SNIZHENII_TCENI") ?></span></div>
                <div>
                    <div class="text-w-icon"><?= GetMessage("RZ_VASH") ?> e-mail<span class="required-asterisk">*</span>:
                    </div>
                    <label class="textinput-wrapper email">
                        <i class="flaticon-mail9 icon-before-input"></i>
                        <input type="email" name="<?= $arResult['CODE'] ?>[EMAIL]"
                               id="modal_inform-when-price-drops_email"
                               class="textinput" required value="<?= $USER->GetEmail() ?>">
                        <span class="textinput-icons">
						<i class="icon-valid flaticon-check33"></i>
						<i class="icon-not-valid flaticon-x5"></i>
					</span>
                    </label>
                </div>
                <div>
                    <div class="text-w-icon price"><?= GetMessage("RZ_ZHELAEMAYA_TCENA") ?>
                        (<?= GetMessage("RZ_VVEDITE_ILI_ISPOLZUJTE_BEGUNOK") ?>)<span class="required-asterisk">*</span>:
                    </div>
                    <label class="textinput-wrapper price">
                        <i class="flaticon-dollar67 icon-before-input"></i>
                        <input value="<?= htmlspecialcharsbx($_REQUEST[$arResult['CODE']]['PRICE']) ?>" type="text"
                               name="<?= $arResult['CODE'] ?>[PRICE]"
                               id="modal_inform-when-price-drops_price" class="textinput" required>
                        <span class="currency">
                            <? $currency = $_REQUEST['CURRENCY'] ?: $_REQUEST[$arResult['CODE']]['CURRENCY'] ?>
                            <? $price = $_REQUEST['PRICE'] ?: $_REQUEST[$arResult['CODE']]['PRICE'] ?>
                            <? $curPrice = $_REQUEST['PRICE'] ?: $_REQUEST['ITEM_PRICE'] ?>
                            <?= CRZBitronic2CatalogUtils::getCurrencyLang($currency) ?>
						</span>
                    </label>
                </div>
                <div class="prices">
                    <div class="simple-slider desired-price-slider"></div>
                    <div class="info">
                        <div class="price-block desired" style="display: none;">
                            <span class="text"><?= GetMessage("RZ_ZHELAEMAYA") ?>:</span>
                            <span class="price" id="desired-price">
							<?= CRZBitronic2CatalogUtils::getElementPriceFormat($currency, $price) ?>
						</span>
                        </div>
                        <div class="price-block current">
                            <span class="text"><?= GetMessage("RZ_TEKUSHAYA") ?>:</span>
                            <span class="price" id="price-current">
							<?= CRZBitronic2CatalogUtils::getElementPriceFormat($currency, $curPrice) ?>
						</span>
                        </div>
                        <div class="price-block difference">
                            <span class="text"><?= GetMessage("RZ_RAZNITCA") ?>:</span>
                            <span class="price" id="price-difference">
							<?= CRZBitronic2CatalogUtils::getElementPriceFormat($currency, 0) ?>
                                <span class="percent-value"></span>
						</span>
                        </div>
                    </div>
                </div>
                <? if (!empty($arResult["CAPTCHA_CODE"])): ?>
                    <div>
                        <div><?= GetMessage('RZ_CAPTCHA_PROTECT_TEXT') ?>:</div>
                        <div class="modal-captcha-wrap">
                            <img src="/bitrix/tools/captcha.php?captcha_code=<?= $arResult["CAPTCHA_CODE"] ?>"
                                 alt="<?= GetMessage('RZ_CAPTCHA_INPUT_WORD') ?>">
                            <input type="hidden" name="captcha_code" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                        </div>
                        <div><?= GetMessage('RZ_CAPTCHA_INPUT_WORD') ?><span class="required-asterisk">*</span>:</div>
                        <label class="textinput-wrapper modal-captcha-input-wrap">
                            <input type="text" name="captcha_word" id="modal_quick-buy_captcha" class="textinput"
                                   required="">
                            <span class="textinput-icons">
                            <i class="icon-valid flaticon-check33"></i>
                            <i class="icon-not-valid flaticon-x5"></i>
                        </span>
                        </label>
                    </div>
                <? endif ?>
                <label class="checkbox-styled">
                    <input value="Y" type="checkbox" name="privacy_policy">
                    <span class="checkbox-content" tabindex="5">
                        <i class="flaticon-check14"></i><?= GetMessage('BITRONIC2_I_ACCEPT') ?>
                        <a href="<?= $pathToRules ?>" class="link"><span
                                    class="text"><?= GetMessage('BITRONIC2_POLITIC_PRIVICE') ?></span></a>
                    </span>
                </label>
                <div class="submit-wrap">
                    <button type="submit" class="btn-submit btn-main disabled">
                        <span class="text"><?= GetMessage("RZ_OTPRAVIT") ?></span>
                    </button>
                </div>
                <div class="required-info">
				<span
                        class="required-asterisk">*</span> &mdash; <?= GetMessage("RZ_POLYA__OTMECHENNIE_ZVEZDOCHKOJ__OBYAZATELNI_DLYA_ZAPOLNENIYA") ?>
                </div>
            <? endif ?>
            <? if (!$isAjax): ?>
        </form>
    </div>
</div>
<? endif ?>
