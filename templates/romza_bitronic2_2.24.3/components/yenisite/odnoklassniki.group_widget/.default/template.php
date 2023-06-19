<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (method_exists($this, 'setFrameMode')) $this->setFrameMode(true); ?>
<? use \Yenisite\Core\Tools; ?>

<div class="social-header">
    <div class="social-header_name">
        <span class="text">
            <? Tools::IncludeArea('index/social/helpers/ok', 'header', false, false) ?>
        </span>
    </div>
    <a href="<?= $arParams['HREF_FOR_WIDGET_SUBSCRIBE'] ?: 'https://ok.ru/group/54188100943986?st._aid=ExternalGroupWidget_OpenGroup' ?>"
       class="btn-silver btn-subscribe"> <? Tools::IncludeArea('index/social/helpers/ok', 'button', false, false) ?></a>
</div>
<div class="social-content">
    <div id="ok_group_widget" class="ok_group_widget"></div>
    <script>
        !function (d, id, did, st) {
            var js = d.createElement("script");
            js.src = "//connect.ok.ru/connect.js";
            js.onload = js.onreadystatechange = function () {
                if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
                    if (!this.executed) {
                        this.executed = true;
                        setTimeout(function () {
                            OK.CONNECT.insertGroupWidget(id, did, st);
                        }, 0);
                    }
                }
            }
            d.documentElement.appendChild(js);
        }(document, "ok_group_widget", "<?=$arParams["GROUP_ID"];?>", "{width:<?=$arParams["WIDTH"];?>,height:<?=$arParams["HEIGHT"];?>}");
    </script>
</div>

