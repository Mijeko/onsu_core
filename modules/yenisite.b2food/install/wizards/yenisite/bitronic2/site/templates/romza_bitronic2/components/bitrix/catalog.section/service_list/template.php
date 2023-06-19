<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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

$bPartResult = 1 < $arResult['NAV_RESULT']->NavPageCount;
$templateData['SECTIONS'] = array();
?>
<? if ($arParams["DISPLAY_TOP_PAGER"]): ?>
	<?=$arResult["NAV_STRING"]?>
<? endif ?>

			<div class="isotope__grid">
<? foreach ($arResult["ITEMS"] as $arItem): ?>
<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

	if ($bPartResult) $templateData['SECTIONS'][] = $arItem['IBLOCK_SECTION_ID'];
?>
				<div itemscope itemtype="http://schema.org/ImageObject" class="element-item js-<?= $arItem['IBLOCK_SECTION_ID'] ?>" id="<?= $this->GetEditAreaId($arItem['ID']) ?>">
<?
	if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):
?>
					<img itemprop="contentUrl" data-original="<?=CResizer2Resize::ResizeGD2($arItem["PREVIEW_PICTURE"]["SRC"], $arParams["RESIZER_SET"])?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" class="lazy img" title="<?= $arItem["PREVIEW_PICTURE"]["ALT"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>">
<?
	endif
?>
<?
	$frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader());
	if (!empty($arItem["PRINT_PRICE"])): ?>
					<div class="price"><?= $arItem["PRINT_PRICE"] ?></div><?
	endif;
	$frame->end();
	?>

					<div class="title"><?= $arItem["NAME"] ?></div>
					<div class="desc"><?= $arItem["PREVIEW_TEXT"] ?></div>
					<div class="more-info">
						<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="link">
							<span class="text"><?= GetMessage('BITRONIC2_SERVICE_DETAIL_LINK') ?></span>
						</a>
					</div>
				</div>
<? endforeach ?>
			</div>

<? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
	<?=$arResult["NAV_STRING"]?>
<? endif ?>
<?
$templateData['SECTIONS'] = array_unique($templateData['SECTIONS']);
