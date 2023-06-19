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

<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';?>
	<?
	$prefix = 'reviews_';
foreach($arResult["ITEMS"] as $arItem):
    $this->AddEditAction($prefix.$arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($prefix.$arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

    $arItem["PREVIEW_PICTURE"]["SRC"] = $arItem["PREVIEW_PICTURE"]["SRC"] ? : $arItem["DETAIL_PICTURE"]["SRC"];

    $itemTitle = (
        isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])&& $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
        ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
        : $arItem['NAME']
    );
?>
    <div class="review-item" id="<?=$this->GetEditAreaId($prefix.$arItem['ID']);?>">
        <header><?=$arItem['NAME']?></header>
        <div class="review-item-block">
            <a itemscope itemtype="http://schema.org/ImageObject" href="<?=$arItem['DETAIL_PAGE_URL']?>" class="review-img">
                <img itemprop="contentUrl" title="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?: $itemTitle ?>" class="lazy" data-original="<?= CResizer2Resize::ResizeGD2($arItem["PREVIEW_PICTURE"]["SRC"], $arParams["RESIZER_REVIEWS_IMG"]) ?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?: $itemTitle ?>">
            </a>
            <p><?=$arItem['PREVIEW_TEXT']  ? : $arItem['DETAIL_TEXT']?></p>
            <div class="review-link">
                <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link-bd link-std"><?=GetMessage('BITRONIC2_SHOW_ALL')?></a>
            </div>
        </div>
    </div><!-- /.item -->
<?endforeach?>
