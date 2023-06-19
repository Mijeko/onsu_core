<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
if (method_exists($this, 'setFrameMode')) $this->setFrameMode(true);
// echo "<pre style='text-align:left;'>"; print_r($arResult); echo "</pre>";
//The template is never cached
//include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';
$curPage = $APPLICATION->GetCurPage();
?>
<ul itemscope itemtype="http://www.schema.org/SiteNavigationElement" class="sitenav-menu">
    <?
    foreach ($arResult as $key => $arItem):?>
        <?
        if ($arItem['DEPTH_LEVEL'] > 1) continue;
        $class = '';
        $bParent = false;
        $arItem['ADDITIONAL_LINKS'] = array();
        if ($arItem['IS_PARENT']) {
            $k = $key + 1;
            while (($arSubItem = $arResult[$k]) && is_array($arSubItem) && ($arSubItem['DEPTH_LEVEL'] > 1)) {
                $arItem['ADDITIONAL_LINKS'][$arSubItem['TEXT']] = $arSubItem['LINK'];
                $k++;
            }
        }
        if (!empty($arItem['ADDITIONAL_LINKS'])) {
            $class .= ' with-sub';
            $bParent = true;
        }
        if ($arItem['SELECTED']) $class .= ' active';

        ?>
        <li class="sitenav-menu-item <?= $class ?>">
            <? if ($bParent): ?>
            <div class="sitenav-header">
                <? endif ?>
                <? if ($bParent):?>
                    <a href="<?= $arItem['LINK'] ?>" class="sitenav-additional-link">
                        <i class="flaticon-right10"></i>
                    </a>
                <? endif; ?>
                <a href="<?= $arItem['LINK'] ?>" itemprop="discussionUrl">
                    <span class="text"><?= $arItem['TEXT'] ?></span>
                    <i class="icon-arrow down flaticon-arrow486"></i>
                    <i class="icon-arrow up flaticon-arrow492"></i>
                </a>
                <? if ($bParent): ?>
            </div>
        <? endif ?>
            <? if ($bParent):?>
                <ul class="submenu">
                    <? foreach ($arItem['ADDITIONAL_LINKS'] as $text => $link):?>
                        <? if ($curPage == $link):?>
                            <li class="active"><span><?= $text ?></span></li>
                        <? else:?>
                            <li><a href="<?= $link ?>"><?= $text ?></a></li>
                        <? endif ?>
                    <? endforeach ?>
                </ul>
            <? endif ?>
        </li>
    <? endforeach ?>
</ul>