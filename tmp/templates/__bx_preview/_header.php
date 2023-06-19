<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;
use \Yenisite\Core\Page;
use \Yenisite\Core\Tools;

// @var $moduleId
// @var $moduleCode
// @var $settingsClass
include 'include/module_code.php';

if ($_POST['rz_ajax_no_header'] === 'y') {
    $APPLICATION->IncludeComponent("yenisite:settings.panel", "empty", array(
        "SOLUTION" => $moduleId,
        "SETTINGS_CLASS" => $settingsClass,
        "GLOBAL_VAR" => "rz_b2_options"
    ),
        false
    );
    return;
}

?>
    <!DOCTYPE html>
<html lang="<?= LANGUAGE_ID ?>">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><? $APPLICATION->ShowTitle() ?></title>
        <?
        \Bitrix\Main\Localization\Loc::loadMessages(__FILE__);

        global $rz_b2_options;
        global $rz_banner_num;
        global $USER;

        if (!isset($rz_banner_num)) $rz_banner_num = 0;

        $bMainPage = $APPLICATION->GetCurPage(false) == SITE_DIR;
        $arDefIncludeParams = array(
            "AREA_FILE_SHOW" => "file",
            "EDIT_TEMPLATE" => "include_areas_template.php"
        );

        if (!Loader::includeModule($moduleId)) die('Module ' . $moduleId . ' not installed!');
        if (!Loader::includeModule("yenisite.core")) die('Module yenisite.core not installed!');

        use \Bitronic2\Mobile;

        Mobile::Init();

        ?>
        <!-- fonts -->
        <?
        $APPLICATION->AddHeadString('<link href="//fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700&amp;subset=cyrillic-ext,latin" rel="stylesheet" type="text/css">');
        ?>

        <!-- styles -->
        <?
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/css/s.min.css");
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/templates_addon.css");
        $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . "/template_styles.css");
        $APPLICATION->SetAdditionalCSS("/bitrix/js/socialservices/css/ss.css");
        ?>

        <!-- Respond.js - IE8 support of media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!-- selectivizr - IE8- support for css3 classes like :checked -->
        <!--[if lt IE 9]>
        <script async src="<?=SITE_TEMPLATE_PATH?>/js/3rd-party-libs/selectivizr-min.js"></script>
        <script async src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <? ob_start();
        $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/header/settings.php")), false, array("HIDE_ICONS" => "Y"));
        $panelSettings = ob_get_clean();
        ?>

        <?//!!!SET CATALOG PARAMS FOR HIDE ITEMS
            $arParams = array();
            $arParams['HIDE_ITEMS_NOT_AVAILABLE'] = $rz_b2_options['hide-not-available'] == 'Y' ? true : false;
            $arParams['HIDE_ITEMS_ZER_PRICE'] = $rz_b2_options['hide-zero-price'] == 'Y' ? true : false;
            $arParams['HIDE_ITEMS_WITHOUT_IMG'] = $rz_b2_options['hide-empty-img'] == 'Y' ? true : false;

            $rz_b2_options['product-hover-effect'] = Mobile::isMobile() ? 'border-n-shadow' : $rz_b2_options['product-hover-effect'];

            CRZBitronic2CatalogUtils::reSafeParamsCatalog($arParams);
        ?>
        <? //!!!SET CAPTCHA FOR REGISTRATION
        COption::SetOptionString("main", "captcha_registration", $rz_b2_options['captcha-registration'])
        ?>

        <? //!!!SET CAPTCHA FOR FEEDBACK BLOG
        $rz_b2_options['feedback-for-item-on-detail'] == 'Y' ? COption::SetOptionString("blog", "captcha_choice", "A") : COption::SetOptionString("blog", "captcha_choice", "N");
        ?>
        <?
        // #### PROCESS transformers settings
        if ($rz_b2_options['header-version'] == 'v3' || ($bMainPage && Mobile::isMobile())) {
            $bVerticalTopMenu = $rz_b2_options['header-version'] == 'v3';
            $rz_b2_options['catalog-placement'] = 'top';
        }

        if (Mobile::isMobile() && $rz_b2_options['top-line-position'] != 'fixed-bottom') {
            $rz_b2_options['top-line-position'] = 'fixed-top';
        }
        $rz_b2_options['pro_vbc_bonus'] = isset($rz_b2_options['pro_vbc_bonus']) && $rz_b2_options['pro_vbc_bonus'] == 'Y' && Loader::includeModule('vbcherepanov.bonus');
        //$rz_b2_options['block_show_ad_banners'] = ($rz_b2_options['block_show_ad_banners'] === 'Y' && Loader::includeModule('advertising')) ? 'Y' : 'N';
        $rz_b2_options['block_show_ad_banners'] = $rz_b2_options['block_show_ad_banners'] === 'Y' ? 'Y' : 'N';
        ?>
        <script type="text/javascript" data-skip-moving="true">
            <?
            $arSettings = array();
            foreach ($rz_b2_options as $key => $value) {
                if ('theme-custom' == $key) continue;
                $key = preg_replace("/[^a-z]+/i", " ", strtolower($key));
                $key = str_replace(' ', '', substr_replace($key, substr(ucwords($key), 1), 1));
                $arSettings[$key] = $value;
            }
            //correct settings names
            $arSettings['colorTheme'] = $arSettings['themeDemo'];
            $arSettings['photoViewType'] = $arSettings['detailGalleryType'];
            $arSettings['productInfoMode'] = $arSettings['detailInfoMode'];
            $arSettings['productInfoModeDefExpanded'] = ($arSettings['detailInfoFullExpanded'] === 'Y');
            $arSettings['stylingType'] = substr($arSettings['themeDemo'], -4);
            $arSettings['showStock'] = ($arSettings['showStock'] === 'Y');
            $arSettings['limitSliders'] = ($arSettings['limitSliders'] === 'Y');
            $arSettings['sassWorkerUrl'] = SITE_TEMPLATE_PATH . '/js/3rd-party-libs/sass.js_0.9.11/sass.worker.js';
            $arSettings['isFrontend'] = false;
            ?>
            serverSettings = <?= CUtil::PhpToJSObject($arSettings)?><?unset($arSettings)?>;
            SITE_DIR = '<?=SITE_DIR?>';
            SITE_ID = '<?=SITE_ID?>';
            SITE_TEMPLATE_PATH = '<?=SITE_TEMPLATE_PATH?>';
            COOKIE_PREFIX = '<?=COption::GetOptionString("main", "cookie_name", "BITRIX_SM")?>';
            GOOGLE_KEY = '<?=COption::GetOptionString(CRZBitronic2Settings::getModuleId(), "google_key", "AIzaSyBaUmBHLdq8sLVQmfh8fGsbNzx6rtofKy4")?>';
        </script>

        <?
        $APPLICATION->ShowHead();

        //set main theme color to use in JS
        //@var $color
        include_once 'include/js_colors.php';

        CJSCore::Init(array('jquery', 'window'));
        if (Loader::includeModule('currency')) {
            CJSCore::Init(array('currency'));
        }
        $asset = Asset::getInstance();

        // color themes
        $asset->addString(
            '<link rel="stylesheet" href="' . SITE_TEMPLATE_PATH . '/css/themes/theme_' . $rz_b2_options['theme-demo'] .
            '.css" id="current-theme" data-path="' . SITE_TEMPLATE_PATH . '/css/themes/"/>'
        );
        if ('Y' == $rz_b2_options['custom-theme'] && !empty($rz_b2_options['theme-custom'])) {
            $rz_b2_options['theme-custom'] = str_replace('#NEED_REPLACE#', 'select', $rz_b2_options['theme-custom']);
            $asset->addString('<style type="text/css" id="custom-theme">' . $rz_b2_options['theme-custom'] . '</style>');
            $asset->addString('<style type="text/css">
			.custom-theme .hurry header {
    			background-image: url(' . SITE_TEMPLATE_PATH . '/img/bg/hurry-banner_' . $rz_b2_options['theme-demo'] . '.png);
			}</style>');
        }

        // bitrix
        $asset->addJs("/bitrix/js/socialservices/ss.js");

        // basic js libraries
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/spin.min.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/modernizr-custom.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/bootstrap/transition.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/bootstrap/collapse.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/bootstrap/modal.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/requestAnimationFrame.min.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/velocity.min.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/velocity.ui.min.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/sly.min.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/wNumb.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/jquery.maskedinput.min.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/jquery.lazyload.js");
        $asset->addJs(SITE_TEMPLATE_PATH . '/js/3rd-party-libs/chosen_v1.4.2/chosen.jquery.js');
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/libs/require.custom.js");

        //custom js scripts
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/utils/makeSwitch.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/utils/posPopup.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/libs/UmMainMenu.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/libs/SitenavMenu.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/libs/UmFooterMenu.js");

        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/initGlobals.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/settingsInitial.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/settingsHelpers.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/settingsRelated.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/initSettings.js");

        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/toggles/initToggles.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/popups/initModals.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/popups/initPopups.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/popups/initSearchPopup.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/forms/initSearch.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/forms/initSelects.js");

        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/initCommons.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/ready.js");

        CJSCore::RegisterExt('rz_b2_um_countdown', array(
            'js' => SITE_TEMPLATE_PATH . "/js/3rd-party-libs/jquery.countdown.2.0.2/jquery.countdown-ru.js",
            'lang' => SITE_TEMPLATE_PATH . '/lang/' . LANGUAGE_ID . '/countdown.php'
        ));

        if ($bMainPage) {
            if ('Y' == $rz_b2_options['block_home-feedback']
                || 'Y' == $rz_b2_options['block_home-specials']
                || 'Y' == $rz_b2_options['block_home-catchbuy']
            ) {
                $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/jquery.mobile.just-touch.min.js");
            }
            if ('Y' == $rz_b2_options['wow-effect']) {
                $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/wow.min.js");
            }
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/libs/UmAccordeon.js");
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/libs/UmTabs.js");
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/libs/UmComboBlocks.js");
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/sliders/initPhotoThumbs.js");
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/sliders/initFeedbackCarousel.js");
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/initCatalogHover.js");
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/sliders/initSpecialBlocks.js");
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/libs/UmSlider.js");
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/sliders/initBigSlider.js");
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/pages/initHomePage.js");
        }

        if ('Y' == $rz_b2_options['block_home-catchbuy']){
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/jquery.countdown.2.0.2/jquery.plugin.js");
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/jquery.countdown.2.0.2/jquery.countdown.min.js");
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/initTimers.js");
            CJSCore::Init(array('rz_b2_um_countdown'));
        }

        if (!$USER->IsAuthorized()) {
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/progression.js");
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/modals/initModalRegistration.js");
        }
        if ($rz_b2_options['pro_vbc_bonus']) {
            $asset->addJS(SITE_TEMPLATE_PATH . "/js/back-end/bonus.js");
        }
        // back-end stuff
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/back-end/utils.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/back-end/visual/hits.js");
        $asset->addJs(SITE_TEMPLATE_PATH . "/js/back-end/visual/commons.js");

        CJSCore::RegisterExt('rz_b2_um_validation', array(
            //'js' => SITE_TEMPLATE_PATH."/js/custom-scripts/utils/formValidation.js", LAZY LOAD
            'lang' => SITE_TEMPLATE_PATH . '/lang/' . LANGUAGE_ID . '/validation.php'
        ));
        // AJAX
        CJSCore::RegisterExt('rz_b2_ajax_core', array(
            'js' => SITE_TEMPLATE_PATH . "/js/back-end/ajax/core.js",
            'lang' => SITE_TEMPLATE_PATH . '/lang/' . LANGUAGE_ID . '/ajax.php',
            'rel' => array('core', 'currency')
        ));
        CJSCore::RegisterExt('rz_b2_bx_catalog_item', array(
            'js' => SITE_TEMPLATE_PATH . "/js/back-end/bx_catalog_item.js",
            'lang' => SITE_TEMPLATE_PATH . '/lang/' . LANGUAGE_ID . '/ajax.php',
        ));

        CJSCore::Init(array('rz_b2_um_validation', 'rz_b2_ajax_core'));

        if ($rz_b2_options['block_main-menu-elem'] != 'N') {
            $asset->addJs(SITE_TEMPLATE_PATH . "/js/back-end/visual/hits.js");
        }
        if ($rz_b2_options['addbasket_type'] === 'popup') {
            $asset->addJs(SITE_TEMPLATE_PATH . '/js/custom-scripts/inits/sliders/initHorizontalCarousels.js');
        }
        ?>
        <meta name="theme-color" content="<?= $color ?>">
    </head>

<?
$bodyClass = '';
if (strpos($rz_b2_options['theme-demo'], '-flat') === false) {
    $bodyClass .= ' more_bold';
}
if ('Y' == $rz_b2_options['custom-theme']) {
    $bodyClass .= ' custom-theme';
}
$bodyClass = ltrim($bodyClass);

ob_start();
Tools::IncludeAreaEdit('header', 'mobile_phone');
$mobilePhone = ob_get_clean();
?>
<body
    <? if (!empty($bodyClass)): ?>
        class="<?= $bodyClass ?>"
    <? endif ?>
        data-styling-type="<?= substr(strrchr($rz_b2_options['theme-demo'], '-'), 1) ?>"
        data-top-line-position="<?= $rz_b2_options['top-line-position'] ?>"
    <? //todo: additional price param ?>

        data-additional-prices-enabled="<?= $rz_b2_options['additional-prices-enabled'] ?>"
        data-catalog-placement="<?= $rz_b2_options['catalog-placement'] ?>"
        data-container-width="<?= $rz_b2_options['container-width'] ?>"
        style="background: <?= $rz_b2_options['color-body'] ?>;"<? //COLOR?>
        data-filter-placement="<?= $rz_b2_options['filter-placement'] ?>"
        data-limit-sliders="<?= ($rz_b2_options['limit-sliders'] === 'Y' && $rz_b2_options['container-width'] !== 'full_width' ? 'true' : 'false') ?>"
        data-table-units-col="<?= $rz_b2_options['table-units-col'] ?: 'disabled' ?>"
        data-stores="<?= $rz_b2_options['stores'] ?: 'disabled' ?>"
        data-show-stock="<?= ($rz_b2_options['show-stock'] === 'Y' ? 'true' : 'false') ?>"
        data-theme-button="<?= $rz_b2_options['theme-button'] ?>"
        data-categories-view="<?= $rz_b2_options['categories-view'] ?>"
        data-categories-with-sub="<?= $rz_b2_options['categories-with-sub'] === 'Y' ? 'true' : 'false' ?>"
        data-categories-with-img="<?= $rz_b2_options['categories-with-img'] === 'Y' ? 'true' : 'false' ?>"
        data-availability-view-type="<?= $rz_b2_options['store_amount_type'] ?>"
        data-site-background="<?= $rz_b2_options['type_bg_ground'] ?>"
        data-catalog-darken="<?= $rz_b2_options['catalog-darken'] ?>"
        data-mobile-phone-action="<?= $rz_b2_options['mobile-phone-action'] ?>"
>
<?
$frame = new \Bitrix\Main\Page\FrameBuffered('rz_dynamic_full_mode_meta');
$frame->begin('');
if (mobile::isMobile(false) && mobile::isFullMode()):
    ?>
    <script type="text/javascript" data-skip-moving="true">
        var viewPortTag = document.createElement('meta');
        viewPortTag.name = "viewport";
        viewPortTag.content = "";
        document.getElementsByTagName('head')[0].appendChild(viewPortTag);
    </script>
<?endif;
$frame->end(); ?>
    <script>
        //PHP Magic starts here
        b2.s.hoverEffect = "<?=$rz_b2_options['product-hover-effect']?>";
        BX.message({
            'tooltip-last-price': "<?=GetMessage('BITRONIC2_TOOLTIP_LAST_PRICE')?>",
            'available-limit-msg': "<?=GetMessage('BITRONIC2_PRODUCT_AVAILABLE_LIMIT_MSG')?>",
            'b-rub': "<?=GetMessage('BITRONIC2_RUB_CHAR')?>",
            'error-favorite': "<?=GetMessage('BITRONIC2_WRONG_ADD_FAVORITES')?>",
            'file-ots': "<?=GetMessage('BITRONIC2_FILE_DEFER')?>",
            'file-type': "<?=GetMessage('BITRONIC2_TYPE')?>",
        });
    </script>


<? if ($rz_b2_options['type_bg_ground'] == 'image'): ?>
    <img src="<?= $rz_b2_options['color-body'] ?>" alt="" class="full-fixed-bg">
<? endif ?>

    <!-- SVG sprite include -->
    <div class="svg-placeholder"
         style="border: 0; clip: rect(0 0 0 0); height: 1px;
	    margin: -1px; overflow: hidden; padding: 0;
	    position: absolute; width: 1px;"></div>
    <script data-skip-moving="true">
        function initSvgSprites() {
            document.querySelector('.svg-placeholder').innerHTML = SVG_SPRITE;
        }
    </script>
    <!-- end SVG sprite include -->

    <div class="bitrix-admin-panel">
        <div class="b_panel"><? $APPLICATION->ShowPanel(); ?></div>
    </div>

    <button class="btn-main to-top">
        <i class="flaticon-key22"></i>
        <span class="text"><?= GetMessage('BITRONIC2_BUTTON_UP'); ?></span>
    </button>

<div class="big-wrap" itemscope itemtype="http://schema.org/Store"><? // will be closed in footer.php ?>
<?
// Structured schema.org data, Open Graph tags
Page::setOGProperty('type', 'website');
Page::setOGProperty('type', 'website');

if (method_exists('\Yenisite\Core\Catalog', 'getSiteInfo') && $arSiteInfo = \Yenisite\Core\Catalog::getSiteInfo()):
    $storeUrl = (CMain::IsHTTPS() ? "https://" : "http://") . $arSiteInfo['SERVER_NAME'];

    Page::setOGProperty('url', $storeUrl . $APPLICATION->GetCurPage(false));
    ?>
    <link itemprop="url" href="<?= $storeUrl ?>"/>
    <meta itemprop="name" content="<?= str_replace('"', "'", $arSiteInfo['SITE_NAME']) ?>"/>
<? endif;
$storeImagePath = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'store_image.jpg');
if (@file_exists($storeImagePath)):
    Page::setOGProperty('image', $storeUrl . SITE_DIR . 'store_image.jpg');
    Page::setOGProperty('image:height', '400');
    Page::setOGProperty('image:width', '400');
    ?>
    <meta itemprop="image" content="<?= $storeUrl . SITE_DIR ?>store_image.jpg"/>
<? endif ?>

    <div class="top-line">
        <div class="container">
            <div class="top-line-content clearfix">
                <?= $panelSettings; ?>
                     <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/header/user_auth.php")), false, array("HIDE_ICONS" => "Y")); ?>
                <? ob_start();
                global $GEOIP_IN_BUFFER;
                $GEOIP_IN_BUFFER = 1;
                $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/header/geoip.php")), false, array("HIDE_ICONS" => "Y"));
                $_includeGeoip = ob_get_clean();
                ob_start();
                $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/header/switch_currency.php")), false, array("HIDE_ICONS" => "Y"));
                $switch_currency = ob_get_clean(); ?>
                <? if ($rz_b2_options['block_show_compare'] == 'Y'): ?>
                    <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/header/compare.php")), false, array("HIDE_ICONS" => "Y")); ?>
                <? endif ?>
                <? if ($rz_b2_options['block_show_favorite'] == 'Y'): ?>
                    <? if (Loader::includeModule('yenisite.favorite')) $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/header/favorites.php")), false, array("HIDE_ICONS" => "Y")); ?>
                <? endif ?>
                <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/header/basket.php")), false, array("HIDE_ICONS" => "Y")); ?>
            </div><!-- /top-line-content -->

            <?= $switch_currency; ?>
            <?
            // TODO
            // $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams,array("PATH" => SITE_DIR."include_areas/header/switch_lang.php")), false, array("HIDE_ICONS"=>"Y"));
            ?>
        </div><!-- container -->
    </div><!-- top-line -->
    <?php // $rz_b2_options['color-header'] тут хранится путь к фону заданный в панели настоек ?>
    <header class="page-header" data-sitenav-type="<?= $rz_b2_options['header-version'] ?>"
            data-header-version="<?= $rz_b2_options['header-version'] ?>"
            style="background: url(<?=SITE_TEMPLATE_PATH?>/img/bg/bg_header3.png) no-repeat;
            background-size: 100%">
           <? $frame = new \Bitrix\Main\Page\FrameBuffered("rz_dynamic_flashmessage") ?>
<? $frame->setAssetMode(\Bitrix\Main\Page\AssetMode::STANDARD) // YOU NEED IT TO PREVENT JS AND CSS FROM REWRITING COMPOSITE CACHE?>
<? $frame->begin('') ?>
<? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/footer/flashmessage.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y")); ?>
<? $frame->end() ?>
        <div class="container">
            <div class="header-main-content <?= CRZBitronic2Settings::isPro() ? 'with-delivery' : '' ?>">
                <div class="sitenav-wrap">
                    <div class="sitenav-table">
                        <div class="sitenav-tcell">
                            <nav class="sitenav horizontal" id="sitenav">
                                <button type="button" class="btn-sitenav-toggle">
                                    <i class="flaticon-menu6"></i>
                                </button>
                                <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/header/menu_top.php")), false, array("HIDE_ICONS" => "Y")); ?>
                            </nav><!-- sitenav.horizontal -->
                        </div>
                    </div>
                </div>
                <a href="<?= SITE_DIR ?>" class="brand">
                    <img class="img-logo" src="<?= SITE_TEMPLATE_PATH ?>/img/car_r.png">
                    <!--
                    <div class="brand-logo" itemprop="logo">
                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/header/logo_icon.php")), false, array("HIDE_ICONS" => "N"))?>
                    </div>
                    -->
                    <div class="brand-name">
                        
                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/header/logo_text.php")), false, array("HIDE_ICONS" => "N"))?>
                    </div>
                    <div class="brand-desc">
                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/header/logo_under_text.php")), false, array("HIDE_ICONS" => "N")); ?>
                    </div>
                </a><!-- logo -->
                <div class="city-and-time with-time" id="city-and-time">
                    <div class="city-and-time__city-block">
                        <?= $_includeGeoip;
                        unset($_includeGeoip); // do not delete
                        $pf = '';
                        if ('Y' == $rz_b2_options['change_contacts']) {
                            $pf = $rz_b2_options['GEOIP']['INCLUDE_POSTFIX'];
                        } ?>
                    </div>
                    <div class="city-and-time__time-block">
                        <? if ($rz_b2_options['block_worktime'] !== 'N') {
                            Tools::includePostfixArea($pf, SITE_DIR . "include_areas/header/work_time.php", true, NULL, true);
                        }
                        ?>
                    </div><!-- .city-and-time__time-block - do not delete -->
                </div><!-- city-and-time -->
                <div class="header-contacts">
                    <div id="switch-contacts" class="flaticon-phone12 phone">
                        <span class="popup" data-popup="^.header-contacts>.contacts-content"></span>
                        <? if (!Tools::isEditModeOn()): ?>
                            <a href="tel:<?= $mobilePhone ?>" class="call"></a>
                        <? endif ?>
                    </div>

                    <div class="contacts-content"
                         style="background: <? if ($rz_b2_options['color-header']{0} != '#'): ?>#fff <? endif ?><?= $rz_b2_options['color-header'] ?>"><? //COLOR?>
                        <div class="phones" itemprop="telephone">
                            <i class="flaticon-phone12 phone"></i>
                            <?
                            Tools::includePostfixArea($pf, SITE_DIR . "include_areas/header/phones.php", true);
                            ?>
                        </div>
                        <span class="free-call-text">
								<?
                                Tools::includePostfixArea($pf, SITE_DIR . "include_areas/header/phones_text.php", true);
                                ?>
							</span>

                        <div class="email-wrap">
                            <?
                            Tools::includePostfixArea($pf, SITE_DIR . "include_areas/header/email.php", true);
                            ?>
                        </div>

                        <div class="address-wrap">
                            <?
                            Tools::includePostfixArea($pf, SITE_DIR . "include_areas/footer/address.php", true, 'address-only');
                            ?>
                        </div>

                        <div class="modal-form">
                             <? if ($rz_b2_options['mobile-phone-action'] == 'callback' && Mobile::isMobile()): ?>
                                   <? Tools::IncludeArea('footer', 'callme') ?>
                                <? endif ?>
                        </div>
                    </div>
                </div><!-- header-contacts -->
                <div class="search-block">
                 <? if (($APPLICATION->GetCurDir() != SITE_DIR . 'personal/order/' && $APPLICATION->GetCurDir() != SITE_DIR . 'personal/order/make/')): ?>
                    <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/header/search.php")), false, array("HIDE_ICONS" => "Y")); ?>
                    <?elseif ($rz_b2_options['hide_all_hrefs'] != 'Y'):?>
                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/header/search.php")), false, array("HIDE_ICONS" => "Y")); ?>
                    <?endif?>
                </div>
            </div><!-- header-main-content -->
        </div><!-- /container -->
        <div class="<?= ($bVerticalTopMenu) ? 'catalog-at-side minified container' : 'catalog-at-top' ?>"
             id="catalog-at-top">
            <?
            if ($rz_b2_options['catalog-placement'] == 'top' || $bVerticalTopMenu) {
                $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/header/menu_catalog.php")), false, array("HIDE_ICONS" => "Y"));
            }
            ?>
        </div>
    </header><!-- page-header v1/v2/v3/v4 -->

<? if(!$bMainPage):?>
	<div class="container bcrumbs-container">
		<nav class="breadcrumbs" data-backnav-enabled="<?= ($rz_b2_options['backnav_enabled'] == 'Y') ? 'true' : 'false' ?>">
			<?Tools::showViewContent('left_menu');?>
			 <? if ($APPLICATION->GetCurDir() != SITE_DIR . 'personal/order/' && $APPLICATION->GetCurDir() != SITE_DIR . 'personal/order/make/'): ?>
			    <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "", array("START_FROM" => "1"),	false );?>
            <?elseif($rz_b2_options['hide_all_hrefs'] != 'Y'):?>
                <?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "", array("START_FROM" => "1"),	false );?>
			<?endif?>
		</nav>
	</div>
	<?endif ?>