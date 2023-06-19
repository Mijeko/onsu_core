<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';
?>
<div class="row">
	<div class="col-xs-12">
		<h1><?=($APPLICATION->GetTitle("h1") ?: $APPLICATION->GetTitle('title'))?></h1>
	</div><!-- /.col-xs-12 -->
</div>
#SECTION_PLACE#
<?$arParams['ACTIONS_USE'] ? $containerArticle = '<div class="news-item-img-wrap">' : $containerNews = ' <div class="news-item-img">';?>
<?$arParams['ACTIONS_USE'] ? $containerArticleEnd = '</div>' : $containerNewsEnd = '</div>';?>
<div class="row <?=$arParams['ACTIONS_USE'] ? "stock-wrap n-stock" : 'news-n-articles'?>">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div itemscope itemtype="http://schema.org/ImageObject" class="item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <?=$containerArticle?>
            <?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
                <?=$containerNews?>
                <?if (!empty($arItem['DETAIL_PAGE_URL'])):?>
                    <a <?if($arParams['ACTIONS_USE']):?>class="news-item-img"<?endif?> href="<?=$arItem['DETAIL_PAGE_URL']?>">
                <?endif?>
                    <img itemprop="contentUrl"  class="lazy" data-original="<?=CResizer2Resize::ResizeGD2($arItem["PREVIEW_PICTURE"]["SRC"], $arParams["RESIZER_NEWS_LIST"])?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" title="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>">
                <?if(!empty($arItem['DETAIL_PAGE_URL'])):?>
                    </a>
                <?endif?>
                <?=$containerNewsEnd?>
            <?endif?>
            <?if (!empty($arItem["DISPLAY_ACTIVE_FROM"]) || !empty($arItem["DISPLAY_ACTIVE_TO"])):?>
                <div class="date-wrap">
                    <div class="svg-wrap">
                        <svg>
                            <use xlink:href="#calendar"></use>
                        </svg>
                    </div>
                    <div class="date">
                        <span class="date-from"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
                        <?if ($arParams['ACTIONS_USE']):?>
                            <span class="date-to"><?echo $arItem["DISPLAY_ACTIVE_TO"]?></span>
                        <?endif?>
                    </div>
                </div>
            <?endif?>
        <?=$containerArticleEnd?>
		<div class="content">
			<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>" class="link"><span class="text"><?echo $arItem["NAME"]?></span></a>
				<div class="desc"><?echo $arItem["PREVIEW_TEXT"];?></div>
		</div><!-- /.content -->
	</div><!-- /.item.col-xs-12.col-sm-6.col-md-4 -->
<?endforeach;?>
</div>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<div>
		<?=$arResult["NAV_STRING"]?>
	</div>
<?endif;?>
