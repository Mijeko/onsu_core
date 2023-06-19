<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use \Yenisite\Core\Tools;

if (function_exists('yenisite_GetCompositeLoader')) {
    global $MESS;
    $MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();
}

if (method_exists($this, 'setFrameMode')) $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING'));
function flag_to_bool($flag)
{
    if ($flag == "Y")
        return "true";
    return "false";
}

$styleCssFacebook = "<style type='text/css'>
	.fb_iframe_widget>span { width: " . $arParams["WIDTH"] . "px !important; }
	.fb-like-box iframe { width: " . $arParams["WIDTH"] . "px !important; }
</style>";

$APPLICATION->AddHeadString($styleCssFacebook);
?>
<div class="social-header">
    <div class="social-header_name">
        <span class="text">
            <? Tools::IncludeArea('index/social/helpers/fb', 'header', false, false) ?>
        </span>
    </div>
    <a href="<?= $arParams['HREF_FOR_WIDGET_SUBSCRIBE'] ?: 'https://www.facebook.com/romza.marketplace/' ?>"
       class="btn-silver btn-subscribe"> <? Tools::IncludeArea('index/social/helpers/fb', 'button', false, false) ?></a>
</div>
<div class="social-content">
    <div id="fb-root"></div>
    <script>(function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/ru_RU/all.js#xfbml=1";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>

    <div
            class="fb-like-box"
            data-href="<?= $arParams["PAGE_URL"]; ?>"
            data-width="<?= $arParams["WIDTH"]; ?>"
        <? if ($arParams["HEIGHT"] != ""): ?>
            data-height="<?= $arParams["HEIGHT"]; ?>"
        <? endif; ?>
            data-colorscheme="<?= $arParams["COLOR_SCHEME"]; ?>"
            data-show-faces="<?= flag_to_bool($arParams["FACES"]); ?>"
            data-stream="<?= flag_to_bool($arParams["STREAM"]); ?>"
            data-show-border="<?= flag_to_bool($arParams["BORDER"]); ?>"
            data-header="<?= flag_to_bool($arParams["HEADER"]); ?>"
    >
    </div>
</div>

