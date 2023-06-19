<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if (!defined("WIZARD_SITE_ID") || !defined("WIZARD_SITE_DIR"))
    return;

include WIZARD_ABSOLUTE_PATH . '/include/moduleInclude.php';

use Bitrix\Main\Loader;

$_SESSION['RZ_FILES_REWRITED'] = array();

function ___writeToAreasFile($path, $text)
{
    //if(file_exists($fn) && !is_writable($abs_path) && defined("BX_FILE_PERMISSIONS"))
    //	@chmod($abs_path, BX_FILE_PERMISSIONS);

    $fd = @fopen($path, "wb");
    if (!$fd)
        return false;

    if (false === fwrite($fd, $text)) {
        fclose($fd);
        return false;
    }

    fclose($fd);

    if (defined("BX_FILE_PERMISSIONS"))
        @chmod($path, BX_FILE_PERMISSIONS);
}

/**
 * @param string $path - path to file we need to check and to rewrite
 * @param array $arSearch - array of strings, if there are no such strings in checked file, then we will rewrite it
 * @param bool $bFullRewrite - true to replace whole file, false to replace only searched strings
 * @param mixed $toReplace - string path to file with replacement content | array of strings to be replaced by $arSearch or $arReplace if not empty
 * @param array $arReplace - array of strings to replace $toReplace with no full rewrite
 * @param bool $bRewriteIfFound
 * false - rewrite file if any of $arSearch strings is not found
 * true  - rewrite file if any of $arSearch strings is found
 * @return bool
 */
function RZ_RewriteFile($path, $arSearch, $toReplace, $bFullRewrite = true, $arReplace = array(), $bRewriteIfFound = false)
{
    if (!file_exists($path)) return false;

    $arPath = pathinfo($path);
    $fileContent = file_get_contents($path);
    if ($fileContent === false) die('Could not read file: ' . $path);

    if (empty($arSearch)) return false;

    if (!is_array($arSearch)) {
        $arSearch = array($arSearch);
    }

    $bRewrite = false;
    foreach ($arSearch as $strSearch) {
        $pos = strpos($fileContent, $strSearch);
        if (
            ($bRewriteIfFound && $pos !== false) ||
            (!$bRewriteIfFound && $pos === false)
        ) {
            $bRewrite = true;
            break;
        }
    }
    if (!$bRewrite) return false;

    $backupFileName = $arPath['dirname'] . '/' . $arPath['filename'] . '-' . date('Y_m_d-H_i_s') . '.' . $arPath['extension'];
    if (!copy($path, $backupFileName)) {
        die('Can not make backup of file: ' . $path . " \nto: $backupFileName");
    }
    $_SESSION['RZ_FILES_REWRITED'][$path] = $backupFileName;

    if ($bFullRewrite) {
        if (!file_exists($toReplace) || ($strReplace = file_get_contents($toReplace)) === false) die('Could not read file: ' . $toReplace);
        $res = file_put_contents($path, $strReplace);
    } else {
        if (is_array($arReplace) && !empty($arReplace)) {
            $arSearch = $arReplace;
        }
        if (count($arSearch) == 1) {
            $arSearch = $arSearch[0];
        }
        $fileContent = str_replace($toReplace, $arSearch, $fileContent);
        $res = file_put_contents($path, $fileContent);
    }
    if ($res === false) return false;

    return true;
}


function checkOnContent($fileForCheck, $str)
{
    if (!file_exists($fileForCheck)) return false;

    $fileContent = file_get_contents($fileForCheck);
    if ($fileContent === false) die('Could not read file: ' . $fileForCheck);

    if (empty($str)) return 'empty string';

    if (strpos($fileContent, $str) !== false) return true;
    else return false;
}

;

if (COption::GetOptionString("main", "upload_dir") == "")
    COption::SetOptionString("main", "upload_dir", "upload");

$wizard =& $this->GetWizard();

if (file_exists(WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/")) {
    CopyDirFiles(
        WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/",
        WIZARD_SITE_PATH,
        $wizard->GetVar("install_type") != 'update',
        $recursive = true,
        $delete_after_copy = false
    );
}


//CHANGE
if ($wizard->GetVar("install_type") != 'update') {
    ___writeToAreasFile(WIZARD_SITE_PATH . "include_areas/header/logo_text.php", $wizard->GetVar("siteName"));
    ___writeToAreasFile(WIZARD_SITE_PATH . "include_areas/header/logo_under_text.php", $wizard->GetVar("siteSlogan"));
    ___writeToAreasFile(WIZARD_SITE_PATH . "include_areas/footer/copyright.php", $wizard->GetVar("siteCopy"));
    ___writeToAreasFile(WIZARD_SITE_PATH . "include_areas/footer/shop_name.php", $wizard->GetVar("siteCopyName"));
    // ___writeToAreasFile(WIZARD_SITE_PATH."include_areas/header/phone.php", $wizard->GetVar("siteTelephone"));
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "include_areas/header/phones.php", Array("PHONE_NUM" => preg_replace('~[^0-9+]+~', '', $wizard->GetVar("siteTelephone")), "PHONE_SHOW" => $wizard->GetVar("siteTelephone")));
    // ___writeToAreasFile(WIZARD_SITE_PATH."include/timesheet.php", $wizard->GetVar("siteSchedule"));
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "include_areas/", Array("SALE_EMAIL" => $wizard->GetVar("shopEmail")));

    $arSocNets = array(
        "shopFacebook" => "SOC_FB",
        "shopTwitter" => "SOC_TW",
        "shopVk" => "SOC_VK",
        "shopYouTube" => "SOC_YT",
        "shopSkype" => "SOC_SK"
    );
    foreach ($arSocNets as $socNet => $macros) {
        $curSocnet = $wizard->GetVar($socNet);
        if ($curSocnet) {
            CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "include_areas/footer/socserv.php", Array($macros => $curSocnet));
            // ___writeToAreasFile(WIZARD_SITE_PATH."include/socnet_".$includeFile.".php", $text);
        }
    }

    WizardServices::PatchHtaccess(WIZARD_SITE_PATH);


    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "about/", Array("SALE_EMAIL" => $wizard->GetVar("shopEmail")));

    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "about/", Array("SALE_PHONE" => $wizard->GetVar("siteTelephone")));
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "about/", Array("SALE_LOCATION" => $wizard->GetVar("shopLocation")));
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "about/", Array("SALE_ADDRESS" => $wizard->GetVar("shopAdr")));

    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "about/", Array("SHOP_INN" => $wizard->GetVar("shopINN")));
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "about/", Array("SHOP_KPP" => $wizard->GetVar("shopKPP")));
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "about/", Array("SHOP_NS" => $wizard->GetVar("shopNS")));
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "about/", Array("SHOP_BANK" => $wizard->GetVar("shopBANK")));
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "about/", Array("SHOP_BANKREKV" => $wizard->GetVar("shopBANKREKV")));
    WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "about/", Array("SHOP_KS" => $wizard->GetVar("shopKS")));

    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/.section.php", array("SITE_DESCRIPTION" => htmlspecialcharsbx($wizard->GetVar("siteMetaDescription"))));
    CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/.section.php", array("SITE_KEYWORDS" => htmlspecialcharsbx($wizard->GetVar("siteMetaKeywords"))));
} else //===== UPDATE =====//
{
    define('NO_FULL_REWRITE', false);
    CopyDirFiles(
        WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/ajax/",
        WIZARD_SITE_PATH . 'ajax/',
        $rewrite = true,
        $recursive = true,
        $delete_after_copy = false
    );

    //update cool_slider + currency.switcher (2.3.0)
    $path = WIZARD_SITE_PATH . 'include_areas/index/cool-slider.php';
    $toReplace = array('bitrix:catalog.section', 'cool_slider');
    $arSearch = array('yenisite:catalog.section.proxy', 'bitronic2');
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE);

    //update brands
    $path = WIZARD_SITE_PATH . 'include_areas/index/brands.php';
    $toReplace = WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/index/brands.php";
    $arSearch = array('"BRAND_DETAIL" => $arBrandParams["PATH_TO_VIEW"]');
    RZ_RewriteFile($path, $arSearch, $toReplace);

    // update menu_catalog
    $path = WIZARD_SITE_PATH . 'include_areas/header/menu_catalog.php';
    $arSearch = array('"VIEW_HIT" => $rz_b2_options["block_main-menu-elem"],', //bitronic 2.6.0 (block show options)
        '"HITS_POSITION" => $rz_b2_options["menu-hits-position"],' //bitronic 2.8.5
    );
    $toReplace = WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/header/menu_catalog.php";
    RZ_RewriteFile($path, $arSearch, $toReplace);

    $arSearch = array('"SHOW_ICONS" => $rz_b2_options["menu-show-icons"]'); //bitronic 2.15.0 (menu icons)
    $toReplace = array('"HITS_POSITION" => $rz_b2_options["menu-hits-position"]');
    $arReplace = array('"HITS_POSITION" => $rz_b2_options["menu-hits-position"],
	"SHOW_ICONS" => $rz_b2_options["menu-show-icons"],
	"ICON_RESIZER_SET" => "#PERSONAL_AVA_RESIZER_SET#"');
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace);

    $arSearch = array('"MENU_CACHE_TYPE" => "N"');
    $toReplace = array('"MENU_CACHE_TYPE" => "A"', '"MENU_CACHE_TYPE" => "Y"');
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE);

    // update one_click
    $path = WIZARD_SITE_PATH . 'include_areas/catalog/one_click.php';
    $arSearch = array('"OFFER_PROPS" => $arProps', //bitronic 2.8.0 (SCU support)
    );
    $toReplace = WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/catalog/one_click.php";
    RZ_RewriteFile($path, $arSearch, $toReplace);

    // update big-slider 2.17.0
    $path = WIZARD_SITE_PATH . 'include_areas/index/big-slider.php';
    $arSearch = array('$arMenuOptions');
    $toReplace = WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/index/big-slider.php";
    RZ_RewriteFile($path, $arSearch, $toReplace);

    // update favorites
    $path = WIZARD_SITE_PATH . 'include_areas/header/favorites.php';
    $arSearch = array('"STORE_DISPLAY_TYPE" => $rz_b2_options["store_amount_type"],', //bitronic 2.6.0 (store amount view type)
    );
    $toReplace = WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/header/favorites.php";
    RZ_RewriteFile($path, $arSearch, $toReplace);

    //update basket/info.php
    $path = WIZARD_SITE_PATH . 'include_areas/basket/info.php';
    $arSearch = array(
        'class="link"><span class="text">', //bitronic 2.7.2 (link text fix)
    );
    $toReplace = WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/basket/info.php";
    RZ_RewriteFile($path, $arSearch, $toReplace);

    // update main_spec
    $path = WIZARD_SITE_PATH . 'include_areas/index/main_spec.php';
    $arSearch = array(
        '"STORE_DISPLAY_TYPE" => $rz_b2_options["store_amount_type"],', //bitronic 2.6.0 (store amount view type)
        '"SB_FULL_DEFAULT" => $rz_b2_options["sb_full_default"],', // 2.7.0 SHOW ALL PARAM
    );
    $toReplace = WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/index/main_spec.php";
    RZ_RewriteFile($path, $arSearch, $toReplace);

    // update cool_slider
    $path = WIZARD_SITE_PATH . 'include_areas/index/cool-slider.php';
    $arSearch = array(
        '"DISPLAY_NAMES" => $rz_b2_options["cool_slider_show_names"],', // 2.7.0 SHOW ALL PARAM
    );
    $toReplace = WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/index/cool-slider.php";
    RZ_RewriteFile($path, $arSearch, $toReplace);

    // update settings.php
    $path = WIZARD_SITE_PATH . 'include_areas/header/settings.php';
    $arSearch = array(
        '"SET_MOBILE" => $setMobile', // 2.8.0 MOBILE_SETTINGS
    );
    $toReplace = WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/header/settings.php";
    RZ_RewriteFile($path, $arSearch, $toReplace);

    // update benefits
    $arSearch = array('hidden-sm', 'col-md-4');
    $toReplace = array(' hidden-sm', ' col-md-4');
    $arReplace = array('');
    $path = WIZARD_SITE_PATH . 'include_areas/index/benefits.php';
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, $bRewriteIfFound = true);
    $path = WIZARD_SITE_PATH . 'include_areas/catalog/benefits.php';
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, $bRewriteIfFound = true);

    // update search
    $path = WIZARD_SITE_PATH . 'include_areas/header/search.php';
    $arSearch = array(
        'catalog/', //2.6.0
        '"SHOW_CATEGORY_SWITCH" => ($rz_b2_options' //2.16.5
    );
    $toReplace = WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/header/search.php";
    RZ_RewriteFile($path, $arSearch, $toReplace);

    if (CRZBitronic2Settings::isPro(true, WIZARD_SITE_ID)) {
        // update geoip
        $path = WIZARD_SITE_PATH . 'include_areas/header/geoip.php';
        $arSearch = array(
            '"ONLY_GEOIP" => $rz_b2_options["geoip_unite"]', //2.9.0
            '"DETERMINE_CURRENCY" => $rz_b2_options["geoip_currency"]' //2.14.0
        );
        $toReplace = WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/header/geoip.php";
        RZ_RewriteFile($path, $arSearch, $toReplace);
        // 2.18.0
        $arSearch = array('"UNITE_WITH_STORE" =>');
        $toReplace = array(
            '"AUTOCONFIRM"',
            'CRZBitronic2Settings::isPro($bGeoipStore = true)',
            'CModule::IncludeModule(\'yenisite.geoipstore\') && CRZBitronic2Settings::isPro()',
            'global $rz_b2_options;'
        );
        $arReplace = array(
            '"UNITE_WITH_STORE" => ($bPro && $rz_b2_options["geoip_unite"] == "Y") ? "Y" : "N",
		"AUTOCONFIRM"',
            '$bPro',
            '$bPro',
            'global $rz_b2_options;

$bPro = CRZBitronic2Settings::isPro($bGeoipStore = true);
'
        );
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace);
    }
    //update currency switcher
    $path = WIZARD_SITE_PATH . 'include_areas/header/switch_currency.php';
    $arSearch = array(
        '$rz_b2_options[\'convert_currency\']' //2.14.0
    );
    $toReplace = WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/header/switch_currency.php";
    RZ_RewriteFile($path, $arSearch, $toReplace);

    //update map in popup
    $path = WIZARD_SITE_PATH . 'include_areas/footer/address_popup.php';
    $arSearch = 'address_modal_map';
    $toReplace = 'address-modal-map';
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE);

    //update basket URL for redirect on buy buttons to work (2.7.0)
    $arSearch = array('"BASKET_URL" => "/personal/cart/"');
    $toReplace = array('"BASKET_URL" => "/personal/basket.php"');
    RZ_RewriteFile(WIZARD_SITE_PATH . 'include_areas/header/favorites.php', $arSearch, $toReplace, NO_FULL_REWRITE);
    RZ_RewriteFile(WIZARD_SITE_PATH . 'include_areas/index/catchbuy.php', $arSearch, $toReplace, NO_FULL_REWRITE);
    RZ_RewriteFile(WIZARD_SITE_PATH . 'include_areas/index/cool-slider.php', $arSearch, $toReplace, NO_FULL_REWRITE);
    RZ_RewriteFile(WIZARD_SITE_PATH . 'include_areas/index/main_spec.php', $arSearch, $toReplace, NO_FULL_REWRITE);

    //update site-map
    $path = WIZARD_SITE_PATH . 'site-map/index.php';
    $arSearch = array('<main class="container site-map-page" data-page="site-map-page">');
    $toReplace = array('<main class="container site-map-page">');
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE);

    //fix news pager title 2.11.0
    $path = WIZARD_SITE_PATH . 'news/index.php';
    $arSearch = array('"PAGER_TITLE" => "' . GetMessage('NEWS') . '"');
    $toReplace = array('"PAGER_TITLE" => ""');
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE);

    //change feedback_static inclusion method 2.12.10
    $path = WIZARD_SITE_PATH . 'include_areas/index/feedback.php';
    $arSearch = array('bitrix:main.include');
    $toReplace = WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . '/include_areas/index/feedback.php';
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE);

    // new include area on /about/index.php page 2.12.0
    $path = WIZARD_SITE_PATH . 'about/index.php';
    $arSearch = array('\\Yenisite\\Core\\Tools::IncludeArea(\'about\', \'reviews\', false, true)');
    $arReplace = array(
        '	<? \\Yenisite\\Core\\Tools::IncludeArea(\'about\', \'reviews\', false, true) ?>
</main>'
    );
    $toReplace = array('</main>');
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace);

    //prevent feedback modal forms parameters from being corrupted (2.13.0, 2.17.0)
    $arSearch = array('"TEXT_REQUIRED" => "N"');
    $arForms = array(
        'ELEMENT_EXIST_ADMIN' => 'include_areas/footer/modal_subscribe.php',
        'FOUND_CHEAP' => 'include_areas/catalog/modal_price_cry.php',
        'PRICE_LOWER_ADMIN' => 'include_areas/catalog/modal_price_drops.php',
    );
    foreach ($arForms as $eventName => $file) {
        $path = WIZARD_SITE_PATH . $file;
        $arSearch[1] = '"EVENT_NAME" => "' . $eventName . '"';
        $toReplace = WIZARD_ABSOLUTE_PATH . '/site/public/' . LANGUAGE_ID . '/' . $file;
        RZ_RewriteFile($path, $arSearch, $toReplace);
    }

    //update catalog 2.15.0
    $path = WIZARD_SITE_PATH . 'catalog/index.php';
    $arSearch = array('"RESIZER_DETAIL_PROP" => ');
    $toReplace = array('"RESIZER_DETAIL_FLY_BLOCK" => ');
    $arReplace = array('"RESIZER_DETAIL_PROP" => "#ELEMENT_DETAIL_PROP_RESIZER_SET#",
		"RESIZER_DETAIL_FLY_BLOCK" => ');
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace);

    // update 2.16.0
    $path = WIZARD_SITE_PATH . 'include_areas/header/geoip.php';
    $arSearch = array('$rz_b2_options[\'block_show_geoip\']');
    $toReplace = WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/header/geoip.php";
    RZ_RewriteFile($path, $arSearch, $toReplace);

    //big basket
    $path = WIZARD_SITE_PATH . 'personal/cart/index.php';
    $arSearch = array('PROPERTY_RZ_FOR_ORDER_TEXT');
    $toReplace = array('"COLUMNS_LIST" => array(');
    $arReplace = array('"COLUMNS_LIST" => array(
			-1 => "PROPERTY_RZ_FOR_ORDER_TEXT",');
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace);

    //menu count for 2.16.5
    $path = WIZARD_SITE_PATH . '.catalog.menu_ext.php';
    $arSearch = array('"ELEMENT_CNT" => ($rz_b2_options["block_menu_count"] !== "N" ? "Y" : "N")');
    $toReplace = array('"ELEMENT_CNT" => "Y"', '"ELEMENT_CNT" => "N"', "'ELEMENT_CNT' => 'Y'");
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE);
    $arSearch = array('global $APPLICATION, $rz_b2_options;');
    $toReplace = array('global $APPLICATION;');
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE);

    $path = WIZARD_SITE_PATH . 'include_areas/index/cool-slider.php';
    $arSearch = array('$rz_b2_options["coolslider_show_stickers"]');
    $toReplace = array('"DISPLAY_NAMES"');
    $arReplace = array('"SHOW_STICKERS" => $rz_b2_options["coolslider_show_stickers"],
		"DISPLAY_NAMES"');
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace);

    $path = WIZARD_SITE_PATH . 'brands/index.php';
    $arSearch = array('yenisite:highloadblock');
    $toReplace = WIZARD_ABSOLUTE_PATH . '/site/public/' . LANGUAGE_ID . '/brands/index.php';
    RZ_RewriteFile($path, $arSearch, $toReplace);

    $path = WIZARD_SITE_PATH . '404.php';
    $arSearch = array('$rz_b2_options[\'block_404', "Yenisite\\Core\\Tools::includeArea('404', 'banner'");
    $toReplace = WIZARD_ABSOLUTE_PATH . '/site/public/' . LANGUAGE_ID . '/404.php';
    RZ_RewriteFile($path, $arSearch, $toReplace);

    // fix h1 on index page 2.18.0
    $path = WIZARD_SITE_PATH . 'include_areas/index/news.php';
    $arSearch = array('"SET_TITLE" => "N"');
    $toReplace = array('"SET_TITLE" => "Y"');
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE);
    // add images to index news
    $arSearch = array('"RESIZER_NEWS_MAIN"');
    $toReplace = array('"NEWS_COUNT"');
    $arReplace = array('"RESIZER_NEWS_MAIN" => "#NEWS_MAIN_RESIZER_SET#",
	"NEWS_COUNT"');
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace);

    if (COption::GetOptionString(CRZBitronic2Settings::getModuleId(), 'update_2.17.0', 'N', WIZARD_SITE_ID) === 'Y') {
        $toReplace = WIZARD_ABSOLUTE_PATH . '/site/public/' . LANGUAGE_ID . '/include_areas/header/settings.php';
        $arSearch = array('some string you will never find in this file to rewrite it anyway');
        $path = WIZARD_SITE_PATH . 'include_areas/header/settings.php';
        RZ_RewriteFile($path, $arSearch, $toReplace);
    }
    if (COption::GetOptionString(CRZBitronic2Settings::getModuleId(), 'update_2.19.0', 'N', WIZARD_SITE_ID) === 'Y') {
        //update index page
        $path = WIZARD_SITE_PATH . 'index.php';
        $arSearch = array('some string you will never find in this file to rewrite it anyway');
        $toReplace = WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/_index.php";
        RZ_RewriteFile($path, $arSearch, $toReplace);

        //change footer h3 to h4
        $arSearch = 'h4>';
        $toReplace = 'h3>';
        $path = WIZARD_SITE_PATH . 'include_areas/footer/contact_info_title.php';
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE);
        $path = WIZARD_SITE_PATH . 'include_areas/footer/menu_bottom_title.php';
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE);
        $path = WIZARD_SITE_PATH . 'include_areas/footer/payment_systems.php';
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE);
        $path = WIZARD_SITE_PATH . 'include_areas/footer/socserv.php';
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE);

        //fix romza sign in footer
        $path = WIZARD_SITE_PATH . 'include_areas/footer/romza.php';
        $arSearch = array(GetMessage('FOOTER_ROMZA'), GetMessage('FOOTER_ROMZA_2'));
        $toReplace = WIZARD_ABSOLUTE_PATH . '/site/public/' . LANGUAGE_ID . '/include_areas/footer/romza.php';
        $arReplace = array();
        RZ_RewriteFile($path, $arSearch, $toReplace, $bFullRewrite = true, $arReplace, $bRewriteIfFound = true);
        $arSearch = array('<img ');
        $toReplace = array('<a href');
        $arReplace = array('<a class="with-text" href');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace);
    }
    if (COption::GetOptionString(CRZBitronic2Settings::getModuleId(), 'update_2.20.0', 'N', WIZARD_SITE_ID) === 'Y') {
        //update personal section, new complex component
        $path = WIZARD_SITE_PATH . 'personal/index.php';
        $arSearch = array('bitrix:sale.personal.section');
        $toReplace = WIZARD_ABSOLUTE_PATH . '/site/public/' . LANGUAGE_ID . '/personal/index.php';
        RZ_RewriteFile($path, $arSearch, $toReplace);

        $path = WIZARD_SITE_PATH . '.user.menu.php';
        $arSearch = array('2.20.1'); //force rewrite
        $toReplace = WIZARD_ABSOLUTE_PATH . '/site/public/' . LANGUAGE_ID . '/.user.menu.php';
        RZ_RewriteFile($path, $arSearch, $toReplace);

        //update index page with new banners
        $path = WIZARD_SITE_PATH . 'index.php';
        $arSearch = array(
            'Tools::IncludeArea(\'index\', \'banner_first\'',
            'Tools::IncludeArea(\'index\', \'banner_second\''
        );
        $toReplace = array(
            'if($rz_b2_options[\'block_home-cool-slider\']',
            'if($rz_b2_options[\'block_home-rubric\']'
        );
        $arReplace = array(
            "Tools::IncludeArea('index', 'banner_first', false, true, \$rz_b2_options['block_show_ad_banners']);" . PHP_EOL . '	if($rz_b2_options[\'block_home-cool-slider\']',
            "Tools::IncludeArea('index', 'banner_second', false, true, \$rz_b2_options['block_show_ad_banners']);" . PHP_EOL . '	if($rz_b2_options[\'block_home-rubric\']'
        );
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace);

        //backup old personal section folders
        if ('LITE' !== CRZBitronic2Settings::getEdition()) {
            $arPath = array(
                WIZARD_SITE_PATH . 'personal/orders',
                WIZARD_SITE_PATH . 'personal/profile',
                WIZARD_SITE_PATH . 'personal/profiles'
            );
            foreach ($arPath as $path) {
                if (\Bitrix\Main\IO\Directory::isDirectoryExists($path)) {
                    rename($path, $path . '-old');
                }
            }
        }
    }

    $path = WIZARD_SITE_PATH . 'personal/left_menu.php';
    $arSearch = array('"CACHE_SELECTED_ITEMS" => "N"');
    $toReplace = WIZARD_ABSOLUTE_PATH . '/site/public/' . LANGUAGE_ID . '/personal/left_menu.php';
    RZ_RewriteFile($path, $arSearch, $toReplace);


    if (!is_dir(WIZARD_SITE_PATH . 'include_areas/catalog/banner_in_items.php')) {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/catalog/banner_in_items.php",
            WIZARD_SITE_PATH . 'include_areas/catalog/banner_in_items.php',
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false
        );
    }
    if (!file_exists(WIZARD_SITE_PATH . "include_areas/index/actions.php")) {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/index/actions.php",
            WIZARD_SITE_PATH . 'include_areas/index/actions.php',
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false
        );
    }
    if (!is_dir(WIZARD_SITE_PATH . 'actions/')) {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/actions/",
            WIZARD_SITE_PATH . 'actions/',
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false
        );
    }

    $path = WIZARD_SITE_PATH . 'index.php';
    if (!checkOnContent($path, '$rz_b2_options[\'block_home-actions\']')) {
        $arReplace = $arSearch = array('
    <?if ($rz_b2_options[\'block_home-actions\'] == \'Y\'):?>
        <?Tools::IncludeArea(\'index\', \'actions\', false);?>
    <?endif?>
    <?if ($rz_b2_options[\'block_home-news\']');
        $toReplace = array('<?if ($rz_b2_options[\'block_home-news\']');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'catalog/index.php';
    if (!checkOnContent($path, 'RESIZER_BANNER_ACTION')) {
        $arReplace = $arSearch = array('
    "CACHE_TYPE" => "A",
    "RESIZER_BANNER_ACTION" => "#BANNER_ACTION_RESIZER_SET#",');
        $toReplace = array('"CACHE_TYPE" => "A",');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'include_areas/index/categories.php';
    if (!checkOnContent($path, 'RESIZER_SECTION_LARGE')) {
        $arReplace = $arSearch = array('
        "CACHE_TYPE" => "A",
        "RESIZER_SECTION_LARGE" => "#SECTION_LARGE_RESIZER_SET#",');
        $toReplace = array('"CACHE_TYPE" => "A",');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $arReplace = $arSearch = array('
        "CACHE_TYPE" => "A",
        "RESIZER_SECTION_BIG" => "#SECTION_BIG_RESIZER_SET#",');
        $toReplace = array('"CACHE_TYPE" => "A",');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);


        $arReplace = $arSearch = array('"SECTION_USER_FIELDS" => array(
        0 => "UF_IMG_BLOCK_FOTO",');
        $toReplace = array('"SECTION_USER_FIELDS" => array(
        0 => "",');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }


    $path = WIZARD_SITE_PATH . 'catalog/index.php';
    if (!checkOnContent($path, 'RESIZER_IMG_STORE')) {
        $arReplace = $arSearch = array('
    "CACHE_TYPE" => "A",
    "RESIZER_IMG_STORE" => "#IMG_STORE_RESIZER_SET#",');
        $toReplace = array('"CACHE_TYPE" => "A",');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }
    if (!checkOnContent($path, 'ELEMENT_LIST_VIP_RESIZER_SET')) {
        $arReplace = $arSearch = array('
    "CACHE_TYPE" => "A",
    "RESIZER_SECTION_VIP" => "#ELEMENT_LIST_VIP_RESIZER_SET#",');
        $toReplace = array('"CACHE_TYPE" => "A",');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'include_areas/index/categories.php';
    if (!checkOnContent($path, '"TOP_DEPTH" => "2",')) {
        $arReplace = $arSearch = array('"TOP_DEPTH" => "2",');
        $toReplace = array('"TOP_DEPTH" => "1",');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }


    $path = WIZARD_SITE_PATH . 'index.php';
    if (!checkOnContent($path, 'use Yenisite\Core\Tools;')) {
        $arReplace = $arSearch = array('use Yenisite\Core\Tools;
            require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");');
        $toReplace = array('require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'personal/order/make/thank_you.php';
    if (!checkOnContent($path, 'intval($_REQUEST[\'ORDER_ID\'])')) {
        $arReplace = $arSearch = array('$orderId = intval($_REQUEST[\'id\']) ? :intval($_REQUEST[\'ORDER_ID\']);');
        $toReplace = array('$orderId = intval($_REQUEST[\'id\']);');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'personal/order/thank_you.php';
    if (!checkOnContent($path, 'intval($_REQUEST[\'ORDER_ID\'])')) {
        $arReplace = $arSearch = array('$orderId = intval($_REQUEST[\'id\']) ? :intval($_REQUEST[\'ORDER_ID\']);');
        $toReplace = array('$orderId = intval($_REQUEST[\'id\']);');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    if (COption::GetOptionString(CRZBitronic2Settings::getModuleId(), 'update_2.21.1', 'N', WIZARD_SITE_ID) === 'Y') {
        $path = WIZARD_SITE_PATH . 'catalog/index.php';
        if (checkOnContent($path, '"ELEMENT_SORT_FIELD" => "shows",')) {
            $arReplace = $arSearch = array('"ELEMENT_SORT_FIELD" => "",');
            $toReplace = array('"ELEMENT_SORT_FIELD" => "shows",');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        }
    }

    $path = WIZARD_SITE_PATH . 'include_areas/catalog/banner_in_items.php';
    if (!checkOnContent($path, 'advertising')) {
        $arReplace = $arSearch = array('\Bitrix\Main\Loader::includeModule(\'sale\')');
        $toReplace = array('\Bitrix\Main\Loader::includeModule(\'advertising\')');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'include_areas/header/geoip.php';
    if (!checkOnContent($path, 'CRZBitronic2CatalogUtils::checkDifPriceAndSetBasketItems($rz_b2_options[\'GEOIP\']);')) {
        $arReplace = $arSearch = array('$rz_b2_options[\'GEOIP\'] = $arRes;');
        $toReplace = array('$rz_b2_options[\'GEOIP\'] = $arRes;
        CRZBitronic2CatalogUtils::checkDifPriceAndSetBasketItems($rz_b2_options[\'GEOIP\']);');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'include_areas/index/voting.php';
    if (!checkOnContent($path, 'catalog_hits_dynamic')) {
        $arReplace = $arSearch = array('<?if(CModule::IncludeModule(\'vote\')):?>');
        $toReplace = array('<?if(CModule::IncludeModule(\'vote\')):?>
        <?$dynamicArea = new \Bitrix\Main\Page\FrameStatic("catalog_hits_dynamic");
$dynamicArea->setAnimation(true);
$dynamicArea->startDynamicArea();?>');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $arReplace = $arSearch = array('<?endif;?>');
        $toReplace = array('<?$dynamicArea->finishDynamicArea();?>
<?endif;?>');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    if (!Loader::includeModule('advertising')) {
        $arPathes = array(
            0 => array('SRC' => WIZARD_SITE_PATH . 'include_areas/404/banner.php', 'QUANTITY' => 1, 'TMPL' => 'bitronic2'),
            1 => array('SRC' => WIZARD_SITE_PATH . 'include_areas/brands/banner.php', 'QUANTITY' => 1, 'TMPL' => 'bitronic2'),
            3 => array('SRC' => WIZARD_SITE_PATH . 'include_areas/catalog/element_banner_double.php', 'QUANTITY' => 1, 'TMPL' => 'bitronic2'),
            4 => array('SRC' => WIZARD_SITE_PATH . 'include_areas/catalog/element_banner_single.php', 'QUANTITY' => 1, 'TMPL' => 'bitronic2'),
            5 => array('SRC' => WIZARD_SITE_PATH . 'include_areas/catalog/element_banner_triple.php', 'QUANTITY' => 1, 'TMPL' => 'bitronic2'),
            6 => array('SRC' => WIZARD_SITE_PATH . 'include_areas/catalog/section_banner_double.php', 'QUANTITY' => 1, 'TMPL' => 'bitronic2'),
            7 => array('SRC' => WIZARD_SITE_PATH . 'include_areas/catalog/section_banner_single.php', 'QUANTITY' => 1, 'TMPL' => 'bitronic2'),
            8 => array('SRC' => WIZARD_SITE_PATH . 'include_areas/index/banner_second.php', 'QUANTITY' => 1, 'TMPL' => 'bitronic2'),
            9 => array('SRC' => WIZARD_SITE_PATH . 'include_areas/index/banner_first.php', 'QUANTITY' => 1, 'TMPL' => 'bitronic2'),
            10 => array('SRC' => WIZARD_SITE_PATH . 'include_areas/news/banner.php', 'QUANTITY' => 1, 'TMPL' => 'bitronic2'),
        );

        $strProxy = '
        <?$APPLICATION->IncludeComponent(
            "yenisite:proxy",
            "#TEMPLATE#",
            array(
                "NOINDEX" => "Y",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "COMPONENT_TEMPLATE" => "#TEMPLATE#",
                "REMOVE_POSTFIX_IN_NAMES" => "N",
                "QUANTITY" => "#QUANTITY#",
                "COMPOSITE_FRAME_MODE" => "A",
                "COMPOSITE_FRAME_TYPE" => "AUTO"
            ),
            false
        );?>';

        foreach ($arPathes as $path) {
            if (!checkOnContent($path['SRC'], 'yenisite:proxy')) {
                $toReplace = array('$APPLICATION->IncludeComponent(');
                $arReplace = $arSearch = array('if (\Bitrix\Main\Loader::includeModule(\'advertising\')):?>
    <?$APPLICATION->IncludeComponent(');
                RZ_RewriteFile($path['SRC'], $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

                $toReplace = array(');?>');
                $arReplace = $arSearch = array(');?>
    <?else:?>'
                    . str_replace(array('#QUANTITY#', '#TEMPLATE#'), array($path['QUANTITY'], $path['TMPL']), $strProxy) . '
    <?endif?>');
                RZ_RewriteFile($path['SRC'], $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            }
        }

        $arPathes = array(
            0 => array('SRC' => WIZARD_SITE_PATH . 'include_areas/catalog/banner_in_items.php', 'QUANTITY' => 1, 'TMPL' => 'bitronic2'),
            1 => array('SRC' => WIZARD_SITE_PATH . 'include_areas/index/banner1.php', 'QUANTITY' => 1, 'TMPL' => 'simple'),
            2 => array('SRC' => WIZARD_SITE_PATH . 'include_areas/index/banner2.php', 'QUANTITY' => 1, 'TMPL' => 'simple'),
        );

        foreach ($arPathes as $path) {
            if (!checkOnContent($path['SRC'], 'yenisite:proxy')) {
                $toReplace = array('<?endif?>');
                $arReplace = $arSearch = array('<?else:?>' .
                    str_replace(array('#QUANTITY#', '#TEMPLATE#'), array($path['QUANTITY'], $path['TMPL']), $strProxy) . '
                <?endif?>');
                RZ_RewriteFile($path['SRC'], $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            }
        }
    }

    $path = WIZARD_SITE_PATH . 'include_areas/footer/callme.php';

    if (!checkOnContent($path, 'captcha-callme')) {
        $toReplace = array('if(CModule::IncludeModule("yenisite.feedback"))');
        $arReplace = $arSearch = array('global $rz_b2_options;
        if(CModule::IncludeModule("yenisite.feedback"))');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('"USE_CAPTCHA"');
        $arReplace = $arSearch = array('//"USE_CAPTCHA"');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('"CACHE_TYPE" => "A",');
        $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
        "USE_CAPTCHA" => $rz_b2_options[\'captcha-callme\'],');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'include_areas/catalog/one_click.php';

    if (!checkOnContent($path, '$rz_b2_options')) {
        $toReplace = array('$APPLICATION->IncludeComponent(');
        $arReplace = $arSearch = array('global $rz_b2_options;
        $APPLICATION->IncludeComponent(');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('"USE_CAPTCHA"');
        $arReplace = $arSearch = array('//"USE_CAPTCHA"');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('"EMPTY" => $arParams["EMPTY"],');
        $arReplace = $arSearch = array('"EMPTY" => $arParams["EMPTY"],
        "USE_CAPTCHA" => $rz_b2_options[\'captcha-quick-buy\'],
        "USE_CAPTCHA_FORCE" => $rz_b2_options[\'captcha-quick-buy\'],');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }
    $path = WIZARD_SITE_PATH . 'include_areas/footer/feedback.php';

    if (!checkOnContent($path, '$rz_b2_options')) {
        $toReplace = array('$APPLICATION->IncludeComponent(');
        $arReplace = $arSearch = array('global $rz_b2_options;
        $APPLICATION->IncludeComponent(');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('"USE_CAPTCHA"');
        $arReplace = $arSearch = array('//"USE_CAPTCHA"');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('"CACHE_TYPE" => "A",');
        $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
        "USE_CAPTCHA" => $rz_b2_options[\'captcha-feedback\'],');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }
    $path = WIZARD_SITE_PATH . 'catalog/index.php';

    if (!checkOnContent($path, 'IBLOCK_REVIEWS_ID')) {
        $toReplace = array('"CACHE_TYPE" => "A",');
        $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
       "IBLOCK_REVIEWS_ID" => "#REVIEWS_IBLOCK_ID#",');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }
    $path = WIZARD_SITE_PATH . 'include_areas/footer/links.php';

    if (!checkOnContent($path, 'img') && COption::GetOptionString(CRZBitronic2Settings::getModuleId(), 'update_2.22.3', 'N', WIZARD_SITE_ID) === 'Y') {
        require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".CRZBitronic2Settings::getModuleId()."/install/version.php");
        $ver = $arModuleVersion["VERSION"];
        $bitrixTemplateDir = BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID.'_'.$ver;

        $fileLinks = new Bitrix\Main\IO\File($path);
        if ($fileLinks->isExists()) {
            $data = ' | <img src="' . $bitrixTemplateDir . '/img/16.png" title="' . GetMessage('PEGINE_SIXTEEN') . '" alt="' . GetMessage('PEGINE_SIXTEEN') . '"> |
<a href="' . WIZARD_SITE_DIR . 'personal/rules/personal_data.php">' . GetMessage('PRIVACY_POLITIC') . '</a>';
            $fileLinks->putContents($data, Bitrix\Main\IO\File::APPEND);
            $_SESSION['RZ_FILES_REWRITED'][$path];
        }
    }

    if (!is_dir(WIZARD_SITE_PATH . 'personal/rules/')) {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/personal/rules/",
            WIZARD_SITE_PATH . 'personal/rules/',
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false
        );
    }

    $path = WIZARD_SITE_PATH . 'include_areas/index/big-slider.php';

    if (!checkOnContent($path, '\'hits\' =>$rz_b2_options["block_main-menu-elem"],')) {
        $toReplace = array('$arMenuOptions = array(
	$rz_b2_options["block_home-main-slider"],
	$rz_b2_options["block_main-menu-elem"],');
        $arReplace = $arSearch = array('$arMenuOptions = array(
	$rz_b2_options["block_home-main-slider"],
       \'hits\' =>$rz_b2_options["block_main-menu-elem"],');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'site-map/index.php';

    if (!checkOnContent($path, '"CHILD_MENU_TYPE" => "top_sub"')) {
        $toReplace = array('$APPLICATION->IncludeComponent("bitrix:menu", "sitemap", array(');
        $arReplace = $arSearch = array('$APPLICATION->IncludeComponent("bitrix:menu", "sitemap", array(
        "CHILD_MENU_TYPE" => "top_sub",
        "MAX_LEVEL" => "2",');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    if (!is_dir(WIZARD_SITE_PATH . 'include_areas/index/social/helpers/')) {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/index/social/helpers/",
            WIZARD_SITE_PATH . 'include_areas/index/social/helpers/',
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false
        );
    }

    if (!file_exists(WIZARD_SITE_PATH . "include_areas/index/social/flmp.php")) {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/index/social/flmp.php",
            WIZARD_SITE_PATH . 'include_areas/index/social/flmp.php',
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false
        );
    }

    $path = WIZARD_SITE_PATH . 'index.php';

    if (!checkOnContent($path, '$rz_b2_options[\'block_home-flmp\']')) {
        $toReplace = array('<? Tools::IncludeArea(\'index/social\', \'tw\', false, true, $rz_b2_options[\'block_home-tw\']) ?>');
        $arReplace = $arSearch = array('<? Tools::IncludeArea(\'index/social\', \'tw\', false, true, $rz_b2_options[\'block_home-tw\']) ?>
            <? Tools::IncludeArea(\'index/social\', \'flmp\', false, true, $rz_b2_options[\'block_home-flmp\']) ?>');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('case $rz_b2_options[\'block_home-tw\']:');
        $arReplace = $arSearch = array('case $rz_b2_options[\'block_home-tw\']:
         case $rz_b2_options[\'block_home-flmp\']:');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'catalog/index.php';

    if (!checkOnContent($path, '"RESIZER_COMPLECTS"')) {
        $toReplace = array('"CACHE_TYPE" => "A",');
        $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
        "RESIZER_COMPLECTS" => "#RESIZER_COMPLECTS_RESIZER_SET#",');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'include_areas/index/big-slider.php';

    if (!checkOnContent($path, '"SLIDER_ORDER"')) {
        $toReplace = array('"bs_text_v-align" => $rz_b2_options["bs_text_v-align"],');
        $arReplace = $arSearch = array('"bs_text_v-align" => $rz_b2_options["bs_text_v-align"],
        \'SLIDER_ORDER\' => $rz_b2_options["order-sBigSlider"],');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'include_areas/index/banner_first.php';

    if (!checkOnContent($path, '$rz_b2_options')) {
        if (!Loader::includeModule('advertising')) {
            $toReplace = array('<?if(\Bitrix\Main\Loader::includeModule("advertising")):?>');
            $arReplace = $arSearch = array('<?global $rz_b2_options?>
        <?if(\Bitrix\Main\Loader::includeModule("advertising")):?>');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

            $toReplace = array('<?if (\Bitrix\Main\Loader::includeModule(\'advertising\')):?>');
            $arReplace = $arSearch = array('<?global $rz_b2_options?>
        <?if(\Bitrix\Main\Loader::includeModule("advertising")):?>');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

            $toReplace = array('"CACHE_TYPE" => "A",');
            $arReplace = $arSearch = array('"PLACE_CLASS" => "sBannerTwo",');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);


        } else{
            $toReplace = array('<?$APPLICATION->IncludeComponent(');
            $arReplace = $arSearch = array('<?global $rz_b2_options?>
        <?$APPLICATION->IncludeComponent(');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

            if (!checkOnContent($path, '"PLACE_CLASS" => "container",')) {
                $toReplace = array('"PLACE_CLASS" => "container",');
                $arReplace = $arSearch = array('"PLACE_CLASS" => "container sBannerTwo",');
                RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            } else{
                $toReplace = array('"CACHE_TYPE" => "A",');
                $arReplace = $arSearch = array('"PLACE_CLASS" => "sBannerTwo",');
                RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            }
        }

        $toReplace = array('"CACHE_TYPE" => "A",');
        $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
        "ORDER_BANNER" => $rz_b2_options[\'order-sBannerTwo\'],');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

    }

    $path = WIZARD_SITE_PATH . 'include_areas/index/cool-slider.php';

    if (!checkOnContent($path, 'SLIDER_ORDER')) {
        $toReplace = array('"CACHE_TYPE" => "A",');
        $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
        "SLIDER_ORDER" => $rz_b2_options["order-sCoolSlider"],');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'include_areas/index/banner_second.php';

    if (!checkOnContent($path, '$rz_b2_options')) {
        if (!Loader::includeModule('advertising')) {
            $toReplace = array('<?if(\Bitrix\Main\Loader::includeModule("advertising")):?>');
            $arReplace = $arSearch = array('<?global $rz_b2_options?>
        <?if(\Bitrix\Main\Loader::includeModule("advertising")):?>');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            $toReplace = array('<?if (\Bitrix\Main\Loader::includeModule(\'advertising\')):?>');
            $arReplace = $arSearch = array('<?global $rz_b2_options?>
        <?if(\Bitrix\Main\Loader::includeModule("advertising")):?>');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            $toReplace = array('"CACHE_TYPE" => "A",');
            $arReplace = $arSearch = array('"PLACE_CLASS" => "sBannerTwo",');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        } else{
            $toReplace = array('<?$APPLICATION->IncludeComponent(');
            $arReplace = $arSearch = array('<?global $rz_b2_options?>
        <?$APPLICATION->IncludeComponent(');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

            $toReplace = array('"PLACE_CLASS" => "container",');
            $arReplace = $arSearch = array('"PLACE_CLASS" => "container sBannerOne",');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        }

        $toReplace = array('"CACHE_TYPE" => "A",');
        $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
        "ORDER_BANNER" => $rz_b2_options[\'order-sBannerOne\'],');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

    }

    $path = WIZARD_SITE_PATH . 'include_areas/index/categories.php';

    if (!checkOnContent($path, '$rz_b2_options')) {
        $toReplace = array('$APPLICATION->IncludeComponent(');
        $arReplace = $arSearch = array('global $rz_b2_options;
       $APPLICATION->IncludeComponent(');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('"CACHE_TYPE" => "A",');
        $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
         "CATEGORIES_ORDER" => $rz_b2_options[\'order-sCategories\'],');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'include_areas/index/feedback.php';

    if (!checkOnContent($path, '$rz_b2_options')) {
        $toReplace = array('<? if (\Bitrix\Main\Loader::IncludeModule(\'yenisite.ymrs\')): ?>');
        $arReplace = $arSearch = array('<?global $rz_b2_options;?>
        <? if (\Bitrix\Main\Loader::IncludeModule(\'yenisite.ymrs\')): ?>');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('"CACHE_TYPE" => "A",');
        $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
        "FEEDBACK_ORDER" => $rz_b2_options[\'order-sFeedback\'],');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('"main_page", array(),');
        $arReplace = $arSearch = array('"main_page", array(
        "FEEDBACK_ORDER" => $rz_b2_options[\'order-sFeedback\']),');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('"feedback_static", array("PATH"');
        $arReplace = $arSearch = array('"feedback_static", array("FEEDBACK_ORDER" => $rz_b2_options[\'order-sFeedback\'], "PATH"');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'index.php';

    if (!checkOnContent($path, 'sPromoBanners')) {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/index.php",
            WIZARD_SITE_PATH . 'index.php',
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false
        );
    }

    $path = WIZARD_SITE_PATH . 'include_areas/catalog/element_banner_single.php';

    if (!checkOnContent($path, '$rz_b2_options')) {
        if (!Loader::includeModule('advertising')) {
            $toReplace = array('<?if(\Bitrix\Main\Loader::includeModule("advertising")):?>');
            $arReplace = $arSearch = array('<?global $rz_b2_options?>
        <?if(\Bitrix\Main\Loader::includeModule("advertising")):?>');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            $toReplace = array('<?if (\Bitrix\Main\Loader::includeModule(\'advertising\')):?>');
            $arReplace = $arSearch = array('<?global $rz_b2_options?>
        <?if(\Bitrix\Main\Loader::includeModule("advertising")):?>');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            $toReplace = array('"CACHE_TYPE" => "A",');
            $arReplace = $arSearch = array('"PLACE_CLASS" => "sPrBannerOne",');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        } else{
            $toReplace = array('<?$APPLICATION->IncludeComponent(');
            $arReplace = $arSearch = array('<?global $rz_b2_options?>
        <?$APPLICATION->IncludeComponent(');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

            if (!checkOnContent($path, '"PLACE_CLASS" => "container",')) {
                $toReplace = array('"PLACE_CLASS" => "container",');
                $arReplace = $arSearch = array('"PLACE_CLASS" => "container sPrBannerOne",');
                RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            } else{
                $toReplace = array('"CACHE_TYPE" => "A",');
                $arReplace = $arSearch = array('"PLACE_CLASS" => "sPrBannerOne",');
                RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            }
        }

        if (!checkOnContent($path, '"CACHE_TYPE"')) {
            $toReplace = array('"CACHE_TYPE" => "A",');
            $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
        "ORDER_BANNER" => $rz_b2_options[\'order-sPrBannerOne\'],');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        } else{
            $toReplace = array('"CACHE_TIME" => "3600",');
            $arReplace = $arSearch = array('"CACHE_TIME" => "3600",
            "ORDER_BANNER" => $rz_b2_options[\'order-sPrBannerOne\'],');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        }

    }

    $path = WIZARD_SITE_PATH . 'include_areas/catalog/element_banner_double.php';

    if (!checkOnContent($path, '$rz_b2_options')) {
        if (!Loader::includeModule('advertising')) {
            $toReplace = array('<?if(\Bitrix\Main\Loader::includeModule("advertising")):?>');
            $arReplace = $arSearch = array('<?global $rz_b2_options?>
        <?if(\Bitrix\Main\Loader::includeModule("advertising")):?>');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            $toReplace = array('<?if (\Bitrix\Main\Loader::includeModule(\'advertising\')):?>');
            $arReplace = $arSearch = array('<?global $rz_b2_options?>
        <?if(\Bitrix\Main\Loader::includeModule("advertising")):?>');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            $toReplace = array('"CACHE_TYPE" => "A",');
            $arReplace = $arSearch = array('"PLACE_CLASS" => "sPrBannerTwo",');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        } else{
            $toReplace = array('<?$APPLICATION->IncludeComponent(');
            $arReplace = $arSearch = array('<?global $rz_b2_options?>
        <?$APPLICATION->IncludeComponent(');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

            if (!checkOnContent($path, '"PLACE_CLASS" => "container",')) {
                $toReplace = array('"PLACE_CLASS" => "container",');
                $arReplace = $arSearch = array('"PLACE_CLASS" => "container sPrBannerTwo",');
                RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            } else{
                $toReplace = array('"CACHE_TYPE" => "A",');
                $arReplace = $arSearch = array('"PLACE_CLASS" => "sPrBannerTwo",');
                RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            }
        }

        if (!checkOnContent($path, '"CACHE_TYPE"')) {
            $toReplace = array('"CACHE_TYPE" => "A",');
            $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
            "ORDER_BANNER" => $rz_b2_options[\'order-sPrBannerTwo\'],');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        } else{
            $toReplace = array('"CACHE_TIME" => "3600",');
            $arReplace = $arSearch = array('"CACHE_TIME" => "3600",
            "ORDER_BANNER" => $rz_b2_options[\'order-sPrBannerTwo\'],');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        }

    }

    $path = WIZARD_SITE_PATH . 'include_areas/catalog/element_banner_triple.php';

    if (!checkOnContent($path, '$rz_b2_options')) {
        if (!Loader::includeModule('advertising')) {
            $toReplace = array('<?if(\Bitrix\Main\Loader::includeModule("advertising")):?>');
            $arReplace = $arSearch = array('<?global $rz_b2_options?>
        <?if(\Bitrix\Main\Loader::includeModule("advertising")):?>');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            $toReplace = array('<?if (\Bitrix\Main\Loader::includeModule(\'advertising\')):?>');
            $arReplace = $arSearch = array('<?global $rz_b2_options?>
        <?if(\Bitrix\Main\Loader::includeModule("advertising")):?>');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            $toReplace = array('"CACHE_TYPE" => "A",');
            $arReplace = $arSearch = array('"PLACE_CLASS" => "sPrBannerThird",');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        } else{
            $toReplace = array('<?$APPLICATION->IncludeComponent(');
            $arReplace = $arSearch = array('<?global $rz_b2_options?>
        <?$APPLICATION->IncludeComponent(');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

            if (!checkOnContent($path, '"PLACE_CLASS" => "container",')) {
                $toReplace = array('"PLACE_CLASS" => "container",');
                $arReplace = $arSearch = array('"PLACE_CLASS" => "container sPrBannerThird",');
                RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            } else{
                $toReplace = array('"CACHE_TYPE" => "A",');
                $arReplace = $arSearch = array('"PLACE_CLASS" => "sPrBannerThird",');
                RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
            }

        }

        if (!checkOnContent($path, '"CACHE_TYPE"')) {
            $toReplace = array('"CACHE_TYPE" => "A",');
            $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
        "ORDER_BANNER" => $rz_b2_options[\'order-sPrBannerThird\'],');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        } else{
            $toReplace = array('"CACHE_TIME" => "3600",');
            $arReplace = $arSearch = array('"CACHE_TIME" => "3600",
            "ORDER_BANNER" => $rz_b2_options[\'order-sPrBannerThird\'],');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        }

    }

    $path = WIZARD_SITE_PATH . 'include_areas/catalog/banner_in_items.php';

    $toReplace = array('"DIV_COMPOSITE_CLASS" => "banner-catalog"');
    $arReplace = $arSearch = array('"PLACE_CLASS" => "banner-catalog"');
    RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

    $path = WIZARD_SITE_PATH . 'include_areas/footer/menu_bottom.php';

    if (!checkOnContent($path, 'top_sub')) {
        $toReplace = array('"MENU_CACHE_TYPE" => "A",');
        $arReplace = $arSearch = array('"MENU_CACHE_TYPE" => "A",
        "CHILD_MENU_TYPE" => "top_sub",
        "MAX_LEVEL" => "2",');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    if (!file_exists(WIZARD_SITE_PATH . "include_areas/header/mobile_phone.php")) {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/header/mobile_phone.php",
            WIZARD_SITE_PATH . 'include_areas/header/mobile_phone.php',
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false
        );
    }

    if (COption::GetOptionString(CRZBitronic2Settings::getModuleId(), 'update_2.23.5', 'N', WIZARD_SITE_ID) === 'Y') {
        $path = WIZARD_SITE_PATH . "include_areas/index/categories.php";

        if (!checkOnContent($path, 'COUNT_ELEMENTS')) {
            $toReplace = array('"CACHE_TYPE" => "A",');
            $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
        "COUNT_ELEMENTS" => "Y",');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        }
            $toReplace = array('"COUNT_ELEMENTS" => "N",');
            $arReplace = $arSearch = array('"COUNT_ELEMENTS" => "Y",');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $path = WIZARD_SITE_PATH . "catalog/index.php";

    }

    $path = WIZARD_SITE_PATH . "catalog/index.php";

    if (!checkOnContent($path, '$rz_b2_options')) {
        $toReplace = array('require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");');
        $arReplace = $arSearch = array('require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
        global $rz_b2_options;');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    if (!checkOnContent($path, 'block-quantity')) {
        if (!checkOnContent($path, 'USE_PRODUCT_QUANTITY')) {
            $toReplace = array('"CACHE_TYPE" => "A",');
            $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
        "USE_PRODUCT_QUANTITY" => $rz_b2_options[\'block-quantity\'],');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        } else{
            $toReplace = array('"USE_PRODUCT_QUANTITY" => "N",');
            $arReplace = $arSearch = array('"USE_PRODUCT_QUANTITY" => $rz_b2_options[\'block-quantity\'],');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

            $toReplace = array('"USE_PRODUCT_QUANTITY" => "Y",');
            $arReplace = $arSearch = array('"USE_PRODUCT_QUANTITY" => $rz_b2_options[\'block-quantity\'],');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        }
    }

    $arFilesAddNewStr = array('include_areas/footer/menu_catalog.php', 'include_areas/header/menu_catalog.php','include_areas/header/menu_top.php', 'include_areas/footer/menu_bottom.php');

    foreach ($arFilesAddNewStr as $file) {
        $path = WIZARD_SITE_PATH . $file;

        if (!checkOnContent($path, '$rz_b2_options')) {
            $toReplace = array('$APPLICATION->IncludeComponent(');
            $arReplace = $arSearch = array('global $rz_b2_options;
        $APPLICATION->IncludeComponent(');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        }

        if (!checkOnContent($path, 'hide_all_hrefs')) {
            $toReplace = array('$APPLICATION->IncludeComponent(');
            $arReplace = $arSearch = array('if($rz_b2_options[\'hide_all_hrefs\'] == \'Y\' && ($APPLICATION->GetCurDir() == SITE_DIR.\'personal/order/\' || $APPLICATION->GetCurDir() == SITE_DIR.\'personal/order/make/\')) return;
        $APPLICATION->IncludeComponent(');
            RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
        }
    }

    if (COption::GetOptionString(CRZBitronic2Settings::getModuleId(), 'update_2.25.0', 'N', WIZARD_SITE_ID) === 'Y') {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/footer/pricelist.php",
            WIZARD_SITE_PATH . 'include_areas/footer/pricelist.php',
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false
        );
    }
   
   	/*update 2.23.9*/
	$path = WIZARD_SITE_PATH . "include_areas/footer/pricelist.php";
    if (checkOnContent($path, '/public_s1/')) {
        $toReplace = array('<a href="/public_s1/pricelist/" class="link with-icon">');
        $arReplace = $arSearch = array('<a href="#SITE_DIR#pricelist/" class="link-bd link-std flaticon-link49">');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
		
    }
	
	$path = WIZARD_SITE_PATH . "include_areas/header/menu_catalog.php";
    if (!checkOnContent($path, '"CACHE_SELECTED_ITEMS" => false,')) {
        $toReplace = array('"CACHE_SELECTED_ITEMS" => "N",');
        $arReplace = $arSearch = array('"CACHE_SELECTED_ITEMS" => false,');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }
	
	$path = WIZARD_SITE_PATH . "include_areas/footer/menu_catalog.php";
    if (!checkOnContent($path, '"CACHE_SELECTED_ITEMS" => false,')) {
        $toReplace = array('"ALLOW_MULTI_SELECT" => "N",');
        $arReplace = $arSearch = array('"ALLOW_MULTI_SELECT" => "N",
		"CACHE_SELECTED_ITEMS" => false,');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }
	
	$path = WIZARD_SITE_PATH . "include_areas/header/menu_top.php";
    if (!checkOnContent($path, '"CACHE_SELECTED_ITEMS" => false,')) {
        $toReplace = array('"ALLOW_MULTI_SELECT" => "N",');
        $arReplace = $arSearch = array('"ALLOW_MULTI_SELECT" => "N",
		"CACHE_SELECTED_ITEMS" => false,');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }
	
	$path = WIZARD_SITE_PATH . "include_areas/footer/menu_bottom.php";
    if (!checkOnContent($path, '"CACHE_SELECTED_ITEMS" => false,')) {
        $toReplace = array('"ALLOW_MULTI_SELECT" => "N",');
        $arReplace = $arSearch = array('"ALLOW_MULTI_SELECT" => "N",
		"CACHE_SELECTED_ITEMS" => false,');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }
	
	$path = WIZARD_SITE_PATH . "include_areas/header/user_menu.php";
    if (!checkOnContent($path, '"CACHE_SELECTED_ITEMS" => false,')) {
        $toReplace = array('"ALLOW_MULTI_SELECT" => "N",');
        $arReplace = $arSearch = array('"ALLOW_MULTI_SELECT" => "N",
		"CACHE_SELECTED_ITEMS" => false,');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }
	/*end of update 2.23.9*/

	//VERSION 2.23.10
    if(!file_exists(WIZARD_SITE_PATH.'personal/rules/.section.php')) {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/personal/rules/.section.php",
            WIZARD_SITE_PATH . 'personal/rules/.section.php',
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false
        );
    }

    $path = WIZARD_SITE_PATH . "include_areas/footer/links.php";
    if (!checkOnContent($path, '$APPLICATION->GetCurDir()')) {
        $toReplace = array('href="<?=SITE_DIR?>site-map/"');
        $arReplace = $arSearch = array(' <?=$APPLICATION->GetCurDir() == SITE_DIR.\'site-map/\' ? \'class="active"\' : \'\'?> href="<?=SITE_DIR?>site-map/"');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('href="<?=SITE_DIR?>about/"');
        $arReplace = $arSearch = array(' <?=$APPLICATION->GetCurDir() == SITE_DIR.\'about/\' ? \'class="active"\' : \'\'?> href="<?=SITE_DIR?>about/"');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('href="<?=SITE_DIR?>personal/rules/personal_data.php"');
        $arReplace = $arSearch = array(' <?=$APPLICATION->GetCurDir() == SITE_DIR.\'personal/rules/\' ? \'class="active"\' : \'\'?> href="<?=SITE_DIR?>personal/rules/personal_data.php"');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    //VERSION 2.24.0

    $path = WIZARD_SITE_PATH . "company/.section.php";
    if (checkOnContent($path, '"title" => "'.GetMessage('RZ_ABOUT_COMPANY').'"')) {
        $toReplace = array('"title" => "'.GetMessage('RZ_ABOUT_COMPANY').'"');
        $arReplace = $arSearch = array('');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . "company/services/.section.php";
    if (checkOnContent($path, '"title" => "'.GetMessage('RZ_SERVICES').'"')) {
        $toReplace = array('"title" => "'.GetMessage('RZ_SERVICES').'"');
        $arReplace = $arSearch = array('');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'include_areas/footer/callme.php';

    if (!checkOnContent($path, 'use_google_captcha')) {

        $toReplace = array('"USE_CAPTCHA" => $rz_b2_options[\'captcha-callme\'],');
        $arReplace = $arSearch = array('"USE_CAPTCHA" => $rz_b2_options[\'captcha-callme\'] && $rz_b2_options[\'use_google_captcha\'] != \'Y\' ? $rz_b2_options[\'captcha-callme\'] : \'N\',
            \'USE_GOOGLE_CAPTCHA\' => $rz_b2_options[\'use_google_captcha\'] == \'Y\'? $rz_b2_options[\'captcha-callme\'] : \'N\',');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'include_areas/catalog/one_click.php';

    if (!checkOnContent($path, 'use_google_captcha')) {
        $toReplace = array('"USE_CAPTCHA_FORCE" => $rz_b2_options[\'captcha-quick-buy\'],');
        $arReplace = $arSearch = array('"USE_CAPTCHA_FORCE" => $rz_b2_options[\'use_google_captcha\'] == \'N\' ? $rz_b2_options["captcha-quick-buy"] : \'N\',
		"USE_GOOGLE_CAPTCHA" => $rz_b2_options[\'use_google_captcha\'] == \'Y\' ? $rz_b2_options["captcha-quick-buy"] : \'N\',
        ');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'include_areas/footer/feedback.php';

    if (!checkOnContent($path, 'use_google_captcha')) {

        if (checkOnContent($path, '"USE_CAPTCHA" => "Y",')){
            $toReplace = array('"USE_CAPTCHA" => "Y",');
        } else{
            $toReplace = array('"USE_CAPTCHA" => $rz_b2_options[\'captcha-feedback\'],');
        }
        $arReplace = $arSearch = array('"USE_CAPTCHA" => $rz_b2_options[\'use_google_captcha\'] == \'N\' ? $rz_b2_options["captcha-feedback"] : \'N\',
            "USE_GOOGLE_CAPTCHA" => $rz_b2_options[\'use_google_captcha\'] == \'Y\' ? $rz_b2_options["captcha-feedback"] : \'N\',');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'include_areas/footer/modal_contact.php';

    if (!checkOnContent($path, '$rz_b2_options')) {
        $toReplace = array('$APPLICATION->IncludeComponent(');
        $arReplace = $arSearch = array('global $rz_b2_options;
        $APPLICATION->IncludeComponent(');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('"USE_CAPTCHA"');
        $arReplace = $arSearch = array('//"USE_CAPTCHA"');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('"CACHE_TYPE" => "A",');
        $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
        "USE_CAPTCHA" => $rz_b2_options[\'use_google_captcha\'] == \'N\' ? $rz_b2_options["captcha-feedback"] : \'N\',
         "USE_GOOGLE_CAPTCHA" => $rz_b2_options[\'use_google_captcha\'] == \'Y\' ? $rz_b2_options["captcha-feedback"] : \'N\',');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'index.php';

    if (!checkOnContent($path, 'arrows-wrap')) {
        $toReplace = array('Tools::IncludeArea(\'index/social\', \'header\', false, false);');
        $arReplace = $arSearch = array('$bHasFooter = true;
Tools::IncludeArea(\'index/social\', \'header\', false, false);?>
        <div class="arrows-wrap">
				<button type="button" class="arrow prev primary-light disabled" disabled="">
					<span class="icon">
					     <svg>
                             <use xlink:href="#chevron"></use>
                         </svg>
					</span>
				</button>
				<button type="button" class="arrow next primary-light">
					<span class="icon">
					     <svg>
                            <use xlink:href="#chevron"></use>
                         </svg>
					</span>
				</button>
			</div>
			<div id="widgets-slider-frame">
			    <div class="slidee"><?
        ');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }
    if (!checkOnContent($path, 'block_home-inst')) {
        $toReplace = array('|| $rz_b2_options[\'block_home-tw\'] != \'N\' || $rz_b2_options[\'block_home-flmp\'] != \'N\'');
        $arReplace = $arSearch = array('|| $rz_b2_options[\'block_home-tw\'] != \'N\' || $rz_b2_options[\'block_home-flmp\'] != \'N\' || $rz_b2_options[\'block_home-inst\'] != \'N\'');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('case $rz_b2_options[\'block_home-tw\']:');
        $arReplace = $arSearch = array('case $rz_b2_options[\'block_home-tw\']:
        case $rz_b2_options[\'block_home-inst\']:');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('<? Tools::IncludeArea(\'index/social\', \'vk\', false, true, $rz_b2_options[\'block_home-vk\']) ?>');
        $arReplace = $arSearch = array('<? Tools::IncludeArea(\'index/social\', \'inst\', false, true, $rz_b2_options[\'block_home-inst\']) ?>
        <? Tools::IncludeArea(\'index/social\', \'vk\', false, true, $rz_b2_options[\'block_home-vk\']) ?>');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }
    if (!checkOnContent($path, 'if ($bHasFooter)')) {
        $toReplace = array('<? Tools::IncludeArea(\'index/social\', \'flmp\', false, true, $rz_b2_options[\'block_home-flmp\']) ?>');
        $arReplace = $arSearch = array('<? Tools::IncludeArea(\'index/social\', \'flmp\', false, true, $rz_b2_options[\'block_home-flmp\']) ?>
 <?if ($bHasFooter):?>
 </div>
 </div>
 <?endif?>');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

    }

    if(!file_exists(WIZARD_SITE_PATH.'include_areas/index/social/inst.php')) {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/index/social/inst.php",
            WIZARD_SITE_PATH . 'include_areas/index/social/inst.php',
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false
        );
    }

    $path = WIZARD_SITE_PATH . 'catalog/index.php';

    if (!checkOnContent($path, 'RESIZER_SECTIONS_LVL_FIRST')) {
        $toReplace = array('"CACHE_TYPE" => "A",');
        $arReplace = $arSearch = array('
    "CACHE_TYPE" => "A",
    "RESIZER_SECTIONS_LVL_FIRST" => "#RESIZER_SECTIONS_LVL_FIRST_RESIZER_SET#",');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    //VERSION 2.24.1

    $path = WIZARD_SITE_PATH . 'catalog/index.php';

    if (!checkOnContent($path, 'RESIZER_DETAIL_SKU_TABLE')) {
        $toReplace = array('"CACHE_TYPE" => "A",');
        $arReplace = $arSearch = array('
    "CACHE_TYPE" => "A",
    "RESIZER_DETAIL_SKU_TABLE" => "#RESIZER_DETAIL_SKU_TABLE_RESIZER_SET#",');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    $path = WIZARD_SITE_PATH . 'index.php';

    if (!checkOnContent($path, 'wrap_big_slider')) {
        $toReplace = array('$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/big-slider.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y"));');
        $arReplace = $arSearch = array('$APPLICATION->IncludeComponent("bitrix:main.include", "wrap_big_slider", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/big-slider.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y"));');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    //VERSION 2.25.0

    $path = WIZARD_SITE_PATH . 'include_areas/header/menu_catalog.php';

    if (!checkOnContent($path, 'img-for-first-lvl-menu')) {
        $toReplace = array('"SHOW_ICONS" => $rz_b2_options["menu-show-icons"],');
        $arReplace = $arSearch = array('"SHOW_ICONS" => $rz_b2_options["menu-show-icons"],
    "SHOW_FIRST_LVL_IMG" => $rz_b2_options["img-for-first-lvl-menu"],
    "SHOW_SECOND_LVL_IMG" => $rz_b2_options["img-for-second-lvl-menu"],
    "SHOW_THIRD_LVL_IMG" => $rz_b2_options["img-for-third-lvl-menu"],');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    if (!checkOnContent($path, 'menu-opened-in-catalog')) {
        $toReplace = array('"SHOW_THIRD_LVL_IMG" => $rz_b2_options["img-for-third-lvl-menu"],');
        $arReplace = $arSearch = array('"SHOW_THIRD_LVL_IMG" => $rz_b2_options["img-for-third-lvl-menu"],   
    "OPEN_MENU" => $rz_b2_options["menu-opened-in-catalog"] == \'open\',
    "IN_CATALOG" => defined(\'IN_CATALOG_LIST\'),');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }


    $path = WIZARD_SITE_PATH . 'index.php';

    if (!checkOnContent($path, 'block_home-reviews')) {
        $toReplace = array('<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/news.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y"));?>');
        $arReplace = $arSearch = array('<?$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/news.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y"));?>
                <? endif ?>
                <? if ($rz_b2_options[\'block_home-reviews\'] == \'Y\'): ?>
                    <? Tools::IncludeAreaWithHideParam(\'index\', \'reviews\', $rz_b2_options[\'block_home-reviews\'] != \'N\') ?>');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }

    if(!file_exists(WIZARD_SITE_PATH.'include_areas/index/social/inst.php')) {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/index/reviews.php",
            WIZARD_SITE_PATH . 'include_areas/index/reviews.php',
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false
        );
    }

    if(!file_exists(WIZARD_SITE_PATH.'include_areas/header/viewed.php')) {
        CopyDirFiles(
            WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/include_areas/header/viewed.php",
            WIZARD_SITE_PATH . 'include_areas/header/viewed.php',
            $rewrite = true,
            $recursive = true,
            $delete_after_copy = false
        );
    }

    $path = WIZARD_SITE_PATH . 'index.php';

    if (CRZBitronic2Settings::isPro() && !checkOnContent($path, 'filter_news_on_main')) {
        $toReplace = array('<? Tools::IncludeArea(\'index\', \'actions\', false); ?>');
        $arReplace = $arSearch = array('<? Tools::IncludeArea(\'index\', \'actions\', array(\'TEMPLATE\' => \'filter_news_on_main\')); ?>');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('<?Tools::IncludeArea(\'index\', \'actions\', false);?>');
        $arReplace = $arSearch = array('<? Tools::IncludeArea(\'index\', \'actions\', array(\'TEMPLATE\' => \'filter_news_on_main\')); ?>');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('<? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/news.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y")); ?>');
        $arReplace = $arSearch = array('<? $APPLICATION->IncludeComponent("bitrix:main.include", "filter_news_on_main", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/news.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y")); ?>');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $path = WIZARD_SITE_PATH . 'include_areas/index/news.php';

        $toReplace = array('"FILTER_NAME"');
        $arReplace = $arSearch = array('//"FILTER_NAME"');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('"CACHE_TYPE" => "A",');
        $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
        "FILTER_NAME" => "arrNewsFilterInInclude",');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $path = WIZARD_SITE_PATH . 'include_areas/index/actions.php';

        $toReplace = array('"FILTER_NAME"');
        $arReplace = $arSearch = array('//"FILTER_NAME"');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);

        $toReplace = array('"CACHE_TYPE" => "A",');
        $arReplace = $arSearch = array('"CACHE_TYPE" => "A",
        "FILTER_NAME" => "arrNewsFilterInInclude",');
        RZ_RewriteFile($path, $arSearch, $toReplace, NO_FULL_REWRITE, $arReplace, false);
    }
}

// ### SOLUTION_CODE ###
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "include_areas/header/settings.php", Array("SOLUTION_CODE" => CRZBitronic2Settings::getModuleId()));

// ### SITE_DIR ###
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "catalog/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "actions/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "include_areas/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "news/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "personal/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "pricelist/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "reviews/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "company/", Array("SITE_DIR" => WIZARD_SITE_DIR));
WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH . "brands/", Array("SITE_DIR" => WIZARD_SITE_DIR));

// CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."_index.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . ".top.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "about/delivery/.top_sub.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "company/.top_sub.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . ".user.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . ".catalog.menu.php", Array("SITE_DIR" => WIZARD_SITE_DIR));

// copy(WIZARD_THEME_ABSOLUTE_PATH."/favicon.ico", WIZARD_SITE_PATH."favicon.ico");


// delete old rewrite rules
$arUrls = CUrlRewriter::GetList(array(
    'ID' => '',
    'PATH' => WIZARD_SITE_DIR . 'brands/detail.php',
    "SITE_ID" => WIZARD_SITE_ID
));
if (COption::GetOptionString(CRZBitronic2Settings::getModuleId(), 'update_2.20.0', 'N', WIZARD_SITE_ID) === 'Y') {
    $arUrls = array_merge(
        $arUrls,
        CUrlRewriter::GetList(array(
            'ID' => 'bitrix:sale.personal.profile',
            'PATH' => WIZARD_SITE_DIR . 'personal/profiles/index.php',
            "SITE_ID" => WIZARD_SITE_ID
        ))
    );
}

foreach ($arUrls as $arUrl) {
    unset($arUrl['RULE'],$arUrl['SORT']);
    CUrlRewriter::Delete($arUrl);
}

// add new rewrite rules
$arUrlRewrite = array();
if (file_exists(WIZARD_SITE_ROOT_PATH . "/urlrewrite.php")) {
    include(WIZARD_SITE_ROOT_PATH . "/urlrewrite.php");
}

$arNewUrlRewrite = array(
    array(
        "CONDITION" => "#^" . WIZARD_SITE_DIR . "news/#",
        "RULE" => "",
        "ID" => "bitrix:news",
        "PATH" => WIZARD_SITE_DIR . "news/index.php",
        "SITE_ID" => WIZARD_SITE_ID
    ),
    array(
        "CONDITION" => "#^" . WIZARD_SITE_DIR . "actions/#",
        "RULE" => "",
        "ID" => "bitrix:news",
        "PATH" => WIZARD_SITE_DIR . "actions/index.php",
        "SITE_ID" => WIZARD_SITE_ID
    ),
    array(
        "CONDITION" => "#^" . WIZARD_SITE_DIR . "reviews/#",
        "RULE" => "",
        "ID" => "bitrix:news",
        "PATH" => WIZARD_SITE_DIR . "reviews/index.php",
        "SITE_ID" => WIZARD_SITE_ID
    ),
    array(
        "CONDITION" => "#^" . WIZARD_SITE_DIR . "catalog/#",
        "RULE" => "",
        "ID" => "bitrix:catalog",
        "PATH" => WIZARD_SITE_DIR . "catalog/index.php",
        "SITE_ID" => WIZARD_SITE_ID
    ),
    array(
        "CONDITION" => "#^" . WIZARD_SITE_DIR . "brands/#",
        "RULE" => "",
        "ID" => "yenisite:highloadblock",
        "PATH" => WIZARD_SITE_DIR . "brands/index.php",
        "SITE_ID" => WIZARD_SITE_ID
    ),
    array(
        "CONDITION" => "#^" . WIZARD_SITE_DIR . "company/services/#",
        "RULE" => "",
        "ID" => "bitrix:news",
        "PATH" => WIZARD_SITE_DIR . "company/services/index.php",
        "SITE_ID" => WIZARD_SITE_ID
    ),
    array(
        "CONDITION" => "#^" . WIZARD_SITE_DIR . "personal/#",
        "RULE" => "",
        "ID" => "bitrix:sale.personal.section",
        "PATH" => WIZARD_SITE_DIR . "personal/index.php",
        "SITE_ID" => WIZARD_SITE_ID
    )
);

foreach ($arNewUrlRewrite as $arUrl) {
    if (!in_array($arUrl, $arUrlRewrite)) {
        CUrlRewriter::Add($arUrl);
    }
}
