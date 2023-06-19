<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);

include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';

if(empty($arResult['ALL_ITEMS']))
{
	return false;
}
?>
<div class="catalog-lvl0-actions">
	<button type="button" class="action" id="minify-lvl0-categories">
		<span class="text"><?=GetMessage('BITRONIC2_CATLVL0_ALL_HIDE')?></span>
	</button>
	<button type="button" class="action" id="expand-lvl0-categories">
		<span class="text"><?=GetMessage('BITRONIC2_CATLVL0_ALL_SHOW')?></span>
	</button>
</div>
<? $hasCounter = $arParams['~COUNT_ELEMENTS'] == 'Y'; ?>
<?foreach($arResult['ALL_ITEMS_ID'] as $idItem_1=>$arItem_1):?>
	<div class="catalog-category-header">
		<div itemscope itemtype="http://schema.org/ImageObject" class="img-wrap">
			<?if(!empty($arResult['ALL_ITEMS'][$idItem_1]['PICTURE'])):?>
				<img itemprop="contentUrl" class="lazy" data-original="<?=$arResult['ALL_ITEMS'][$idItem_1]['PICTURE']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>"
				alt="<?=$arResult['ALL_ITEMS'][$idItem_1]['PICTURE']['ALT']?>"
				title="<?=$arResult['ALL_ITEMS'][$idItem_1]['PICTURE']['TITLE']?>"
				>
			<?endif?>
		</div>
		<div class="header-content">
			<a href="<?=$arResult['ALL_ITEMS'][$idItem_1]['SECTION_PAGE_URL']?>" class="link">
				<span class="text"><?=$arResult['ALL_ITEMS'][$idItem_1]['NAME']?></span>
			</a>
			<? if ($hasCounter): ?>
				<div class="products-in-category">
					<?=GetMessage('BITRONIC2_CATLVL0_GOODS_IN_SECTION')?><strong><?=$arResult['ALL_ITEMS'][$idItem_1]['ELEMENT_CNT']?></strong>
				</div>
			<? endif ?>
			<button type="button" class="btn-expand"></button>
		</div>
	</div>
	<div class="catalog-category-content expand-content">
		<?foreach($arItem_1 as $idItem_2=>$arItem_2):
			$bImg = (!empty($arResult['ALL_ITEMS'][$idItem_2]['PICTURE']) && $arParams['SHOW_ICONS']);?>
			<div class="catalog-menu-lvl1">
				<div itemscope itemtype="http://schema.org/ImageObject" class="menu-lvl1-header">
					<a href="<?=$arResult["ALL_ITEMS"][$idItem_2]['SECTION_PAGE_URL']?>" class="menu-lvl1-link">
						<?if($bImg):?><img itemprop="contentUrl" src="<?=$arResult['ALL_ITEMS'][$idItem_2]['PICTURE']['SRC']?>"
						alt="<?=$arResult['ALL_ITEMS'][$idItem_2]['PICTURE']['ALT']?>"
						title="<?=$arResult['ALL_ITEMS'][$idItem_2]['PICTURE']['TITLE']?>"
						class="subcategory-img"
						>
						<? endif ?><span class="text"><?=$arResult["ALL_ITEMS"][$idItem_2]['NAME']?></span>
						<? if ($hasCounter): ?><sup class="i-number"><?=$arResult["ALL_ITEMS"][$idItem_2]['ELEMENT_CNT']?></sup>
					<? endif ?></a>
				</div>
				<?if(is_array($arItem_2) && count($arItem_2) > 0):?>
				<ul>
					<?foreach($arItem_2 as $idItem_3):
						$bImg = (!empty($arResult['ALL_ITEMS'][$idItem_3]['PICTURE']) && $arParams['SHOW_ICONS']);?>
					<li itemscope itemtype="http://schema.org/ImageObject">
						<a href="<?=$arResult["ALL_ITEMS"][$idItem_3]['SECTION_PAGE_URL']?>" class="link">
							<?if($bImg):?><img itemprop="contentUrl"
                            data-original="<?=$arResult['ALL_ITEMS'][$idItem_3]['PICTURE']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>"
							alt="<?=$arResult['ALL_ITEMS'][$idItem_3]['PICTURE']['ALT']?>"
							title="<?=$arResult['ALL_ITEMS'][$idItem_3]['PICTURE']['TITLE']?>"
							class="lazy subcategory-img"
							>
							<? endif ?><span class="text"><?=$arResult["ALL_ITEMS"][$idItem_3]['NAME']?></span>
							<? if ($hasCounter): ?><sup class="i-number"><?=$arResult["ALL_ITEMS"][$idItem_3]['ELEMENT_CNT']?></sup>
						<? endif ?></a>
					</li>
					<?endforeach?>
				</ul>
				<?endif?>
			</div><?
		endforeach?>
	</div>
<?endforeach?>

<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";