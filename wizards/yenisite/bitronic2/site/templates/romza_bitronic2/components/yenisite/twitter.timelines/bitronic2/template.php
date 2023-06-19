<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use \Yenisite\Core\Tools;
$this->setFrameMode(true);

$strChrome = "";
foreach ($arParams["CHROME"] as $v)
    $strChrome = $strChrome . " " . $v;
?>
<div class="social-header">
    <div class="social-header_name">
        <span class="text">
            <? Tools::IncludeArea('index/social/helpers/tw', 'header', false, false) ?>
        </span>
    </div>
    <a href="<?= $arParams['HREF_FOR_WIDGET_SUBSCRIBE'] ?: 'https://twitter.com/romza_bx' ?>"
       class="btn-silver btn-subscribe"> <? Tools::IncludeArea('index/social/helpers/tw', 'button', false, false) ?></a>
</div>
<div class="social-content">
    <a href="https://twitter.com/<?=$arParams["USERNAME"];?>"
       class="twitter-timeline"
       data-width="<?=$arParams['WIDTH'];?>"
       data-height="<?=$arParams['HEIGHT'];?>"
       data-lang="<?=$arParams['LANG'];?>"
       data-theme="<?=$arParams['COLOR_SCHEME'];?>"
       data-dnt="true"
       data-link-color="<?=strtolower($arParams["LINK_COLOR"]);?>"
       data-border-color="<?=strtolower($arParams["BORDER_COLOR"]);?>"
        <? //if (0 < (int)$arParams["TWEET_LIMIT"]): ?>
        <?//data-tweet-limit="<?=$arParams["TWEET_LIMIT"];"?>
        <?// endif ?>
       data-widget-id="<?=$arParams["WIDGET_ID"];?>"
       data-chrome="<?=$strChrome;?>"
       data-related="<?=$arParams["RELATED"];?>"
    ><?=GetMessage("TWEET_BY");?> @<?=$arParams["USERNAME"];?></a>
    <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
</div>

