<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (method_exists($this, 'setFrameMode')) $this->setFrameMode(true);
?>
<div class="title-h4"><?= (!empty($arParams['TITLE'])) ? $arParams['TITLE'] : GetMessage('BITRONIC2_CAT_BOT_MENU_TITLE') ?></div>
<div itemscope itemtype="http://schema.org/SiteNavigationElement" class="catalog-menu-footer-wrap">
    <div class="container-wrap">
        <div class="btn-catalog-footer-wrap" id="btn-catalog-footer-wrap">
            <button type="button" class="btn-catalog catalog-show link more-content" id="catalog-footer-show">
								<span class="bullets">
									<span class="bullet">&bullet;</span><!--
									--><span class="bullet">&bullet;</span><!--
									--><span class="bullet">&bullet;</span>
								</span>
                <span class="text"><?=GetMessage('BITRONIC2_CAT_BOT_MENU_MORE_ITEMS')?></span>
            </button>
            <button type="button" class="btn-catalog catalog-hide link more-content" id="catalog-footer-hide">
                <span class="text"><?=GetMessage('BITRONIC2_CAT_BOT_MENU_HIDE_ITEMS')?></span>
            </button>
        </div>
        <div class="catalog-menu-footer main">
            <?
            //The template is never cached
            //include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';
            foreach ($arResult as $key => $arItem):
                if ($arItem['DEPTH_LEVEL'] > 1) continue;
                if ($arItem['SELECTED']) $class .= ' active'; ?>
                <div class="footer-menu-item">
                    <a itemprop="discussionUrl" href="<?= $arItem['LINK'] ?>" class="link <?= $class ?>">
                        <span class="text"><?= $arItem['TEXT'] ?></span>
                    </a>
                </div>
            <? endforeach ?>
        </div>
    </div>
    <a href="<?= CRZBitronic2CatalogUtils::getCatalogFolder() . '?rz_all_elements=y' ?>" class="link more-content">
        <div class="bullets">
            <span class="bullet">&bullet;</span><!--
			--><span class="bullet">&bullet;</span><!--
			--><span class="bullet">&bullet;</span>
        </div>
        <span class="text">
			<?= GetMessage('BITRONIC2_ALL_ELEMENTS') ?>
		</span>
    </a>
</div>