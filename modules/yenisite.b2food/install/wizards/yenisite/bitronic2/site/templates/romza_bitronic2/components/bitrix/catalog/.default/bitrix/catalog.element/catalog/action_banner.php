<?if (!empty($arAction['IMG'])):?>
    <div class="banners-place">
        <div itemscope itemtype="http://schema.org/ImageObject" class="banner-1">
            <a href="<?=$arAction['SRC']?>">
                <img itemprop="contentUrl" class="lazy" src="<?=ConsVar::showLoaderWithTemplatePath()?>" data-original="<?=$arAction['IMG']?>" alt="">
            </a>
        </div>
    </div>
<?endif?>