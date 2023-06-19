<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitronic2\Mobile;
use Yenisite\Core\Tools;
use Yenisite\Core\Page;

global $rz_b2_options;
//@var $arDefIncludeParams set in header.php
\Bitrix\Main\Localization\Loc::loadMessages('header');
?>
    <div class="footer-top wow fadeIn">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-3">
                    <?
                    $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/menu_bottom_title.php")), false, array("HIDE_ICONS" => "N")); ?>

                    <nav class="sitenav vertical" id="sitenav-footer">
                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/menu_bottom.php")), false, array("HIDE_ICONS" => "Y")); ?>
                    </nav>
                    <?
                    $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/feedback_text.php")), false, array("HIDE_ICONS" => "Y"));
                    ?>

                    <div class="yamarket">
                        <?
                        $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/ym_reviews.php")), false, array("HIDE_ICONS" => "N"));
                        ?>

                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <?
                    $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/menu_catalog.php")), false, array("HIDE_ICONS" => "Y"));
                    ?>
                    <? if (\Bitrix\Main\Loader::includeModule('yenisite.pricegen')): ?>
                        <? Tools::IncludeArea('footer', 'pricelist', array(), true, $rz_b2_options['block_pricelist']) ?>
                    <?endif ?>

                </div>
                <div class="col-md-6 col-sm-12">
                    <?
                    $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/subscribe.php")), false, array("HIDE_ICONS" => "N"));

                    $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/socserv.php")), false, array("HIDE_ICONS" => "N"));

                    $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/contact_info_title.php")), false, array("HIDE_ICONS" => "N"));
                    $pf = '';
                    if ('Y' == $rz_b2_options['change_contacts']) {
                        $pf = $rz_b2_options['GEOIP']['INCLUDE_POSTFIX'];
                    }

                    Tools::includePostfixArea($pf, SITE_DIR . "include_areas/footer/address.php", true, 'address');


                    Tools::includePostfixArea($pf, SITE_DIR . "include_areas/footer/email.php", true);
                    ?>
                </div>
                <div class="col-xs-12 payment-systems">
                    <?
                    $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/payment_systems.php")), false, array("HIDE_ICONS" => "N"));

                    $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/payment_systems_info.php")), false, array("HIDE_ICONS" => "N"));
                    ?>

                </div>
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div><!-- /.footer-top -->
    <div class="footer-middle wow fadeIn"
         style="background: <?= $rz_b2_options['color-footer'] ?>; color: <?= $rz_b2_options['color-footer-font'] ?>"><?//COLOR?>
        <div class="container">
            <div class="info-text">
                <?
                Tools::includePostfixArea($pf, SITE_DIR . "include_areas/footer/info_text.php", true);
                ?>
            </div>
            <nav class="footer-nav">
                <? if ($APPLICATION->GetCurDir() != SITE_DIR . 'personal/order/' && $APPLICATION->GetCurDir() != SITE_DIR . 'personal/order/make/'):
                    $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/links.php")), false, array("HIDE_ICONS" => "N"));
                elseif($rz_b2_options['hide_all_hrefs'] != 'Y'):
                    $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/links.php")), false, array("HIDE_ICONS" => "N"));
                endif;
                ?>
            </nav>
            <div class="counters-and-logos">
                <?
                $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/counters.php")), false, array("HIDE_ICONS" => "N"));
                ?>
                <div id="bx-composite-banner">
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom wow fadeIn" data-wow-offset="0">
        <div class="container clearfix">
            <div class="copyright">
                <? $frame = new \Bitrix\Main\Page\FrameBuffered("rz_dynamic_full_mode");
                $frame->begin('');
                if (mobile::isMobile(false)):?>
                    <div><a class="link"
                            href="?<?= mobile::fullModeName ?>=<?= mobile::isFullMode() ? 'N' : 'Y' ?>"><?= mobile::isFullMode() ? GetMessage('BITRONIC2_FOOTER_MOBIL_MODE') : GetMessage('BITRONIC2_FOOTER_FULL_MODE') ?></a>
                    </div>
                <?endif;
                $frame->end();
                $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/copyright.php")), false, array("HIDE_ICONS" => "N"));

                $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/shop_name.php")), false, array("HIDE_ICONS" => "N"));
                ?>

            </div>
            <div class="developed-by">
                <?
                $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/romza.php")), false, array("HIDE_ICONS" => "N"));
                ?>
            </div>
        </div>
    </div>

    </div><!-- /big-wrap --><? // opened in header.php ?>

    <!-- MODALS -->
    <div class="modal fade" id="modal_quick-view" tabindex="-1">
        <div class="modal-dialog modal_quick-view">
            <button class="btn-close" data-toggle="modal" data-target="#modal_quick-view">
                <span class="btn-text"><?= GetMessage('BITRONIC2_MODAL_CLOSE') ?></span>
                <i class="flaticon-close47"></i>
            </button>
            <div class="modal_quick-view_content">
                <?= GetMessage('BITRONIC2_LOADING') ?>

            </div>
            <a href="#" class="flaticon-arrow133 arrow prev"></a>
            <a href="#" class="flaticon-right20 arrow next"></a>
        </div>
    </div>
    <div class="modal fade modal-form" id="modal_registration" tabindex="-1">
        <div class="modal-dialog">
            <button class="btn-close" data-toggle="modal" data-target="#modal_registration">
                <span class="btn-text"><?= GetMessage('BITRONIC2_MODAL_CLOSE') ?></span>
                <i class="flaticon-close47"></i>
            </button>
            <div class="content">
            </div>
        </div>
    </div>
<?
$APPLICATION->ShowViewContent('bitronic2_settings');
$APPLICATION->ShowViewContent('bitronic2_modal_login');
//$APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams,array("PATH" => SITE_DIR."include_areas/header/user_reg.php")), false, array("HIDE_ICONS"=>"Y"));
$APPLICATION->ShowViewContent('bitronic2_modal_callme');
$APPLICATION->ShowViewContent('bitronic2_modal_detail');
$APPLICATION->ShowViewContent('bitronic2_modal_license');
$APPLICATION->ShowViewContent('bitronic2_modal_adress');
// SUBSCRIBE PRODUCT
\Yenisite\Core\Tools::IncludeArea('footer', 'modal_subscribe');

// ADD2BASKET_POPUP
if ($rz_b2_options['addbasket_type'] == 'popup'):
    ?>
    <div class="modal fade" id="modal_basket" tabindex="-1">
        <div class="modal-dialog modal_basket">
            <button class="btn-close" data-toggle="modal" data-target="#modal_basket">
                <span class="btn-text"><?= GetMessage('BITRONIC2_MODAL_CLOSE') ?></span>
                <i class="flaticon-close47"></i>
            </button>
            <div class="content"></div>
        </div>
    </div>
<? endif;

// ONE_CLICK
if (CModule::IncludeModule('yenisite.oneclick')):
    ?>
    <div class="modal fade modal-form" id="modal_quick-buy" tabindex="-1">
        <div class="modal-dialog">
            <button class="btn-close" data-toggle="modal" data-target="#modal_quick-buy">
                <span class="btn-text"><?= GetMessage('BITRONIC2_MODAL_CLOSE') ?></span>
                <i class="flaticon-close47"></i>
            </button>
            <div class="content"></div>
        </div>
    </div>
<? endif;

// ### FORM CALL ME 
if (CModule::IncludeModule("yenisite.feedback")):?>
    <? if ($rz_b2_options['mobile-phone-action'] != 'callback' || !Mobile::isMobile()) { ?>
        <div class="modal fade modal-form modal_callme" id="modal_callme" tabindex="-1">
            <div class="modal-dialog">
                <button class="btn-close" data-toggle="modal" data-target="#modal_callme">
                    <span class="btn-text"><?= GetMessage('BITRONIC2_MODAL_CLOSE') ?></span>
                    <i class="flaticon-close47"></i>
                </button>
                <div class="content"></div>
            </div>
        </div>
    <?
    } ?>
    <?
// ### FORM FEEDBACK
    ?>
    <div class="modal fade modal-form modal_feedback" id="modal_feedback" tabindex="-1">
        <div class="modal-dialog">
            <button class="btn-close" data-toggle="modal" data-target="#modal_feedback">
                <span class="btn-text"><?= GetMessage('BITRONIC2_MODAL_CLOSE') ?></span>
                <i class="flaticon-close47"></i>
            </button>
            <div class="content"></div>
        </div>
    </div>
    <?
// ### FORM PRODUCT CONTACT
    ?>
    <div class="modal fade modal-form modal_feedback" id="modal_contact_product">
        <div class="modal-dialog">
            <button class="btn-close" data-toggle="modal" data-target="#modal_contact_product">
                <span class="btn-text"><?= GetMessage('BITRONIC2_MODAL_CLOSE') ?></span>
                <i class="flaticon-close47"></i>
            </button>
            <div class="content"></div>
        </div>
    </div>
<?endif;

if (CRZBitronic2Settings::isPro($withGeoip = true)) {
    if ($APPLICATION->GetCurDir() == SITE_DIR) {
        CYSGeoIPStore::setMetaTags();
    }
}

// FOR SET PARAMS OF AJAX COMPONENTS
if ($USER->IsAdmin()) {
    $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/callme.php", "EMPTY" => true)), false, array("HIDE_ICONS" => "Y"));
    $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/footer/feedback.php", "EMPTY" => true)), false, array("HIDE_ICONS" => "Y"));
    //$APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams,array("PATH" => SITE_DIR."include_areas/header/user_reg.php", "EMPTY" => true)), false, array("HIDE_ICONS"=>"Y"));
    $APPLICATION->IncludeComponent("bitrix:main.include", "", array_merge($arDefIncludeParams, array("PATH" => SITE_DIR . "include_areas/catalog/one_click.php", "EMPTY" => true)), false, array("HIDE_ICONS" => "Y"));
    if (Tools::isEditModeOn()) {
        Tools::IncludeAreaEdit('header', 'mobile_phone', array('TITLE' => GetMessage('EDIT_HEADER_MOBILE_PHONE')));
    }
}
?>
<?/* TODO
<? include '_/modals/modal_yourcity.html'; ?>
<? include '_/modals/modal_place-order.html'; ?>
<? include '_/modals/modal_inform-when-in-stock.html'; ?>
*/ ?>
<? $APPLICATION->ShowViewContent('modal_city_select'); ?>
<? $APPLICATION->ShowViewContent('modal_store_select'); ?>
    <div class="modal modal_success" id="modal_success" role="dialog" tabindex="-1">
        <div class="alert success">
            <i class="flaticon-close47 btn-close" data-toggle="modal" data-target="#modal_success"></i>
            <div class="alert-header">
                <i class="flaticon-check14"></i>
                <?//title?>
            </div>
            <div class="alert-text">
                <?//text?>
            </div>
            <div class="line"></div>
            <button type="button" class="btn-main" data-toggle="modal"
                    data-target="#modal_success"><?= GetMessage('BITRONIC2_MODAL_BUTTON_TEXT') ?></button>
        </div>
    </div>
    <div class="modal modal_fail" id="modal_fail" role="dialog" tabindex="-1">
        <div class="alert fail">
            <i class="flaticon-close47 btn-close" data-toggle="modal" data-target="#modal_fail"></i>
            <div class="alert-header">
                <i class="flaticon-close47"></i>
                <span class="alert-title"><?//title?></span>
            </div>
            <div class="alert-text"><?//text?></div>
            <div class="line"></div>
            <button type="button" class="btn-main" data-toggle="modal"
                    data-target="#modal_fail"><?= GetMessage('BITRONIC2_MODAL_BUTTON_TEXT') ?></button>
        </div>
    </div>
<?$APPLICATION->ShowViewContent('modal_flashmessage');?>
    <!-- END OF MODALS -->
<?
$panelPath = $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . '/rz_panel/index.php';
if (file_exists($panelPath) && $_REQUEST['no_statistic'] !== 'y') {
    include $panelPath;
}

$sessid = bitrix_sessid();
global $APPLICATION;
$callbaCookie = $APPLICATION->get_cookie($sessid);
if ($_GET['callback'] == 'Y' || !empty($callbaCookie)) {
    $APPLICATION->set_cookie($sessid, 156);
    \Yenisite\Core\Tools::IncludeArea('footer', 'callbackform');
}
?>
    <script async onload="initSvgSprites()" type="text/javascript" data-skip-moving="true"
            src="<?= SITE_TEMPLATE_PATH ?>/fonts/svg.js"></script>
    </body>
    </html>
<?

Page::setOGProperty('title', $APPLICATION->GetTitle(false));

?>

<?php




