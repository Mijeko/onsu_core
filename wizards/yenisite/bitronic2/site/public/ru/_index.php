<?
use Yenisite\Core\Tools;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetPageProperty("title", "Bitronic2 - магазин бытовой и цифровой техники");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("Главная страница");

global $rz_b2_options;
?>
    <main class="home-page" data-page="home-page">
        <h1 class="home-page-h1"><? $APPLICATION->ShowTitle(false) ?></h1>
        <?
        if ($rz_b2_options['block_home-main-slider'] == 'Y' || $rz_b2_options["menu-catalog"] == "side") {
            $APPLICATION->IncludeComponent("bitrix:main.include", "wrap_big_slider", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/big-slider.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y"));
        }
        if ($rz_b2_options['block_home-catchbuy'] == 'Y') {
            $APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/catchbuy.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y"));
        }
        Tools::IncludeArea('index', 'banner_first', false, true, $rz_b2_options['block_show_ad_banners']);
        if ($rz_b2_options['block_home-cool-slider'] == 'Y') {
            $APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/cool-slider.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y"));
        }
        Tools::IncludeArea('index', 'banner_second', false, true, $rz_b2_options['block_show_ad_banners']);
        if ($rz_b2_options['block_home-rubric'] == 'Y') {
            $APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/categories.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y"));
        }
        if ($rz_b2_options['block_home-specials'] == 'Y') {
            $APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/main_spec.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y"));
        }
        ?>
        <? if ($rz_b2_options['block_home-our-adv'] == 'Y'): ?>
            <div class="advantage container hidden-xs drag-section sAdvantage"
                 data-order="<?= $rz_b2_options['order-sAdvantage'] ?>">
                <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/benefits.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "N")); ?>
            </div>
        <? endif ?>
        <? if ('Y' == $rz_b2_options['block_home-feedback']): ?>
            <? Tools::IncludeArea('index', 'feedback', false, true) ?>
        <? endif ?>

        <div class="promo-banners container wow fadeIn drag-section sPromoBanners"
             data-order="<?= $rz_b2_options['order-sPromoBanners'] ?>">
            <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/banner1.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y", "ACTIVE_COMPONENT" => $rz_b2_options['block_show_ad_banners'])); ?>
            <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/banner2.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y", "ACTIVE_COMPONENT" => $rz_b2_options['block_show_ad_banners'])); ?>
        </div>

        <div class="text-content container wow fadeIn  drag-section sContentNews"
             data-order="<?= $rz_b2_options['order-sContentNews'] ?>">
            <div class="text-content-flex">
                <? if ($rz_b2_options['block_home-actions'] == 'Y'): ?>
                    <? Tools::IncludeArea('index', 'actions', array('TEMPLATE' => 'filter_news_on_main')); ?>
                <? endif ?>
                <? if ($rz_b2_options['block_home-news'] == 'Y'): ?>
                    <? $APPLICATION->IncludeComponent("bitrix:main.include", "filter_news_on_main", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/news.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y")); ?>
                <? endif ?>
                <? if ($rz_b2_options['block_home-reviews'] == 'Y'): ?>
                    <? Tools::IncludeAreaWithHideParam('index', 'reviews', $rz_b2_options['block_home-reviews'] != 'N') ?>
                <? endif ?>
            </div>
        </div>
        <div class="text-content container wow fadeIn drag-section sContentAbout"
             data-order="<?= $rz_b2_options['order-sContentAbout'] ?>">
            <div class="text-content-flex">
                <div class="about">
                    <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/about_title.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "N")); ?>
                    <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/about_text.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "N")); ?>
                </div>
                <? if ($rz_b2_options['block_home-voting'] == 'Y'): ?>
                    <div class="hidden-sm hidden-xs questionnaire-wrap">
                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/voting.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y")); ?>
                    </div>
                <? endif ?>
            </div>
        </div>
        <div class="text-content container wow fadeIn drag-section sContentBrands"
             data-order="<?= $rz_b2_options['order-sContentBrands'] ?>">
            <? if ($rz_b2_options['block_home-brands'] == 'Y'): ?>
                <div class="row brands-wrap wow fadeIn"
                     data-brands-view-type="<?= ($rz_b2_options['brands_cloud'] == 'Y') ? 'tags' : 'carousel' ?>">
                    <div class="col-sm-12">
                        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR . "include_areas/index/brands.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y")); ?>
                    </div><!-- /.col-sm-12 -->
                </div><!-- /.row.brands-wrap -->
            <? endif ?>
        </div>
        <? if ($rz_b2_options['block_home-vk'] != 'N' || $rz_b2_options['block_home-ok'] != 'N' || $rz_b2_options['block_home-fb'] != 'N' || $rz_b2_options['block_home-tw'] != 'N' || $rz_b2_options['block_home-flmp'] != 'N' || $rz_b2_options['block_home-inst'] != 'N'): ?>
            <div class="text-content container wow fadeIn drag-section sContentNetwork"
                 data-order="<?= $rz_b2_options['order-sContentNetwork'] ?>">
                <div class="social-boxes">
                    <?
                    switch ('Y') {
                        case $rz_b2_options['block_home-vk']:
                        case $rz_b2_options['block_home-ok']:
                        case $rz_b2_options['block_home-fb']:
                        case $rz_b2_options['block_home-tw']:
                        case $rz_b2_options['block_home-inst']:
                            $bHasFooter = true;
                            Tools::IncludeArea('index/social', 'header', false, false); ?>
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
                                        <use xlink:href="#chevron"></use>
                                    </span>
                                </button>
                            </div>
                            <div id="slider-framessss">
                                <div class="slidee">
                            <?
                        default:
                            break;
                    } ?>
                    <? Tools::IncludeArea('index/social', 'inst', false, true, $rz_b2_options['block_home-inst']) ?>
                    <? Tools::IncludeArea('index/social', 'vk', false, true, $rz_b2_options['block_home-vk']) ?>
                    <? Tools::IncludeArea('index/social', 'ok', false, true, $rz_b2_options['block_home-ok']) ?>
                    <? Tools::IncludeArea('index/social', 'fb', false, true, $rz_b2_options['block_home-fb']) ?>
                    <? Tools::IncludeArea('index/social', 'tw', false, true, $rz_b2_options['block_home-tw']) ?>
                    <? Tools::IncludeArea('index/social', 'flmp', false, true, $rz_b2_options['block_home-flmp']) ?>
                            <?if ($bHasFooter):?>
                                </div>
                            </div>
                <?endif?>
                </div>
            </div><!-- /.text-content.container -->
        <? endif ?>
    </main><!-- /.home-page -->
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>