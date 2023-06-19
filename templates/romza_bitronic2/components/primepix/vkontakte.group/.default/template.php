<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?use \Yenisite\Core\Tools;?>
<? $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader()); ?>
<div class="social-header">
    <div class="social-header_name">
        <span class="text">
            <?Tools::IncludeArea('index/social/helpers/vk','header', false,false)?>
        </span>
    </div>
    <a href="<?=$arParams['HREF_FOR_WIDGET_SUBSCRIBE'] ? : 'https://vk.com/romza.marketplace'?>" class="btn-silver btn-subscribe"> <?Tools::IncludeArea('index/social/helpers/vk','button', false,false)?></a>
</div>
<div class="social-content">
    <div id="vkontakte-group<?= $arResult['SUFFIX'] ?>"></div>
    <script type="text/javascript">
        VK.Widgets.Group("vkontakte-group<?=$arResult['SUFFIX']?>", <?=$arResult['OPTIONS']?>);
    </script>
</div>
