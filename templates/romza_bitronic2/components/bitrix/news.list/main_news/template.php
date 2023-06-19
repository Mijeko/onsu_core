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
if (empty($arResult['ITEMS'])) return;
?>


<div class="news-n-articles">
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';?>
    <div class="title-h3"><?=$arParams['ACTIONS_BLOCK'] ? GetMessage('BITRONIC2_ACTIONS_TITLE') : GetMessage('BITRONIC2_NEWS_TITLE')?></div>
	<?
	$prefix = 'news_';
	$sectionUrl = '';
	foreach($arResult["ITEMS"] as $arItem):
        $sectionUrl = $sectionUrl ? : $arItem['LIST_PAGE_URL'];
		$this->AddEditAction($prefix.$arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($prefix.$arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		
		$itemTitle = (
			isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])&& $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
			? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
			: $arItem['NAME']
		);
	?>
		<div class="item item-stock" id="<?=$this->GetEditAreaId($prefix.$arItem['ID']);?>">
			<div itemscope itemtype="http://schema.org/ImageObject" class="news-img">
				<img itemprop="contentUrl" class="lazy" data-original="<?= CResizer2Resize::ResizeGD2($arItem["PREVIEW_PICTURE"]["SRC"], $arParams["RESIZER_NEWS_MAIN"]) ?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" title="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?: $itemTitle ?>" alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?: $itemTitle ?>">
			</div>
			<div class="content">
				<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link"><span class="text"><?=$itemTitle?></span></a>
                <?if (!empty($arItem["DISPLAY_ACTIVE_FROM"]) || !empty($arItem["DISPLAY_ACTIVE_TO"])):?>
                    <div class="date-wrap">
                        <div class="svg-wrap">
                            <svg>
                                <use xlink:href="#calendar"></use>
                            </svg>
                        </div>
                        <div class="date">
                            <span class="date-from"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
                            <?if ($arParams['ACTIONS_BLOCK'] && !empty($arItem["DISPLAY_ACTIVE_TO"])):?>
                                <span class="date-to"><?echo $arItem["DISPLAY_ACTIVE_TO"]?></span>
                            <?endif?>
                        </div>
                    </div>
                <?endif?>
				<div class="desc"><?=$arItem['PREVIEW_TEXT']?></div>
			</div>
		</div><!-- /.item -->
	<?endforeach?>

        <a href="<?=$sectionUrl?>" class="link more-content">
            <div class="bullets">
                <span class="bullet">&bullet;</span><!--
                --><span class="bullet">&bullet;</span><!--
                --><span class="bullet">&bullet;</span>
            </div>
            <span class="text"><?=$arParams['ACTIONS_BLOCK'] ? GetMessage('BITRONIC2_ACTIONS_ALL') : GetMessage('BITRONIC2_NEWS_ALL')?></span>
        </a>
</div><!-- /.news-n-articles -->

<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";