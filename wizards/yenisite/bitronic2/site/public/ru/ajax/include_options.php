<?
/**
 * @var string $moduleId
 * @var string $moduleCode
 * @var string $settingsClass
 */
include_once 'include_module.php';

use Bitrix\Main\Loader;
use Bitronic2\Mobile;
mobile::Init();

global $rz_b2_options;
global $rz_banner_num;

if (!isset($rz_banner_num)) $rz_banner_num = 0;

//fill rz_b2_options
$APPLICATION->IncludeComponent("yenisite:settings.panel", "empty", array(
    "SOLUTION" => $moduleId,
    "SETTINGS_CLASS" => $settingsClass,
    "GLOBAL_VAR" => "rz_b2_options",
    "EDIT_SETTINGS" => array(),
    "SET_MOBILE" => (mobile::isMobile() ? 'Y' : 'N')
),
    false,
    array('HIDE_ICONS' => 'Y')
);

$rz_b2_options['pro_vbc_bonus'] = isset($rz_b2_options['pro_vbc_bonus']) && $rz_b2_options['pro_vbc_bonus'] == 'Y' && Loader::includeModule('vbcherepanov.bonus');
$rz_b2_options['block_show_ad_banners'] = ($rz_b2_options['block_show_ad_banners'] === 'Y' && Loader::includeModule('advertising')) ? 'Y' : 'N';

if (Loader::includeModule('catalog') && CRZBitronic2Settings::isPro($bGeoipStore = true)) {
    $rz_b2_options['GEOIP'] = $APPLICATION->IncludeComponent(
        "yenisite:geoip.store",
        "empty",
        array(
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "360000",
            "INCLUDE_JQUERY" => "N",
            "ONLY_GEOIP" => $rz_b2_options["geoip_unite"],
            "DETERMINE_CURRENCY" => $rz_b2_options["geoip_currency"],
        ),
        false,
        array('HIDE_ICONS' => 'Y')
    );
}

ob_start();
//fill rz_b2_options['active-currency']
include $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'include_areas/header/switch_currency.php';
ob_end_clean();

$fileSwitch = $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'include_areas/demoswitch.php';
if (file_exists($fileSwitch)) {
    include($fileSwitch);
}

//!!!SET CAPTCHA FOR REGISTRATION
CRZBitronic2CatalogUtils::setCaptchaForRegistration();

//!!!SET CAPTCHA FOR FEEDBACK BLOG
CRZBitronic2CatalogUtils::setCaptchaBlog();

//!!!SET PARAMS FOR HIDE ITEMS IN CATALOG BY PHOTO, STATUS, PRICE
CRZBitronic2CatalogUtils::setParamsForHideItemsInCatalog();
?>
