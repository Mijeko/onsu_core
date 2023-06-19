<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use \Bitrix\Main\Page\Asset;
use Yenisite\GoogleCaptcha\Tools;

$asset = Asset::getInstance();

if (empty($arParams['RE_SITE_KEY']) || empty($arParams['RE_SEC_KEY'])) {
    \Yenisite\GoogleCaptcha\Tools::ShowMess(GetMessage('EMPTY_KEYS'));
    return;
}
$MODULE_ID = \Yenisite\GoogleCaptcha\ReCaptcha::getModuleId();

COption::SetOptionString($MODULE_ID, 'RE_SEC_KEY_FOR_GOOGLE_CAPTCHA', $arParams['RE_SEC_KEY'], '');
$strID = Tools::getUniqID($this);
?>
<div class="rmz_google_captcha_for_load" id="google_captcha_<?= $strID ?>"></div>
<input required type="checkbox" class="hidden checkbox_for_captcha_google" id="check_input_<?= $strID ?>">

<script type="text/javascript">

    if (typeof rmzGoogle != 'undefined'){
        rmzGoogle.initGoogleCaptcha('<?=$arParams['RE_SITE_KEY']?>','<?=BX_ROOT?>/js/<?=$MODULE_ID?>/ajax/index.php');
    }
</script>
