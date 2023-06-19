<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Config\Option;

global $APPLICATION,$rz_captcha_options;

$rz_captcha_options['RE_SEC_KEY'] = $rz_captcha_options['RE_SEC_KEY'] ? : Option::get(CRZBitronic2Settings::getModuleId(),'google_captcha_re_sec_key');
$rz_captcha_options['RE_SITE_KEY'] = $rz_captcha_options['RE_SITE_KEY'] ? : Option::get(CRZBitronic2Settings::getModuleId(),'google_captcha_re_site_key');

if (!empty($rz_captcha_options['RE_SEC_KEY']) && !empty($rz_captcha_options['RE_SITE_KEY'])) {
    $APPLICATION->IncludeComponent(
        "yenisite:googlecaptcha",
        "",
        array(
            'RE_SEC_KEY' => $rz_captcha_options['RE_SEC_KEY'],
            'RE_SITE_KEY' => $rz_captcha_options['RE_SITE_KEY']
        )
    );
} else{?>
  <p class="error"><?=GetMessage('GOOGLE_CAPTCHA_KEYS_NOT_EXIST')?></p>
<?}