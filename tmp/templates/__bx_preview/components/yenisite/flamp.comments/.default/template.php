<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (method_exists($this, 'setFrameMode')) $this->setFrameMode(true);
use \Yenisite\Core\Tools;?>

<div class="social-header">
    <div class="social-header_name">
        <span class="text">
            <? Tools::IncludeArea('index/social/helpers/flmp', 'header', false, false) ?>
        </span>
    </div>
    <a href="<?= $arParams['HREF_FOR_WIDGET_SUBSCRIBE'] ?: 'https://krasnoyarsk.flamp.ru/firm/romza_studiya_tirazhnykh_veb_reshenijj_ip_zabrodin_roman_aleksandrovich-70000001027298676' ?>"
       class="btn-silver btn-subscribe"> <? Tools::IncludeArea('index/social/helpers/flmp', 'button', false, false) ?></a>
</div>
<div class="social-content">
    <div class="flamp-commnents">
        <a class="flamp-widget"
           href="https://flamp.ru/"
           data-flamp-widget-type="<?= $arParams['WIDGET_TYPE'] ?>"
           data-flamp-widget-count="<?= $arParams['COMMENTS_COUNT'] ?>"
           data-flamp-widget-id="<?= $arParams['WIDGET_ID'] ?>"
           data-flamp-widget-width="<?= $arParams['WIDTH'] . $arParams['UNIT_MEASURE'] ?>"
           data-flamp-widget-height="<?= $arParams['HEIGHT'] . $arParams['UNIT_MEASURE'] ?>">
            <?= $arParams['MAIN_TEXT'] ?>
        </a>
        <script>!function (d, s) {
                var jst, fjst = d.getElementsByTagName(s)[0];
                jst = d.createElement(s);
                jst.async = 1;
                jst.src = "//widget.flamp.ru/loader.js";
                fjst.parentNode.insertBefore(jst, fjst);
            }(document, "script");</script>
    </div>
</div>