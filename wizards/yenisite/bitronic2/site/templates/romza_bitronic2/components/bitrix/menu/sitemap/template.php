<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Page\Asset;

Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/inits/pages/initSiteMapPage.js");

if(method_exists($this, 'setFrameMode')) $this->setFrameMode(false);
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';
?>
<?if($arParams['SECOND'] !== 'Y'):?>
	<div class="site-map-actions">
		<button type="button" class="action" id="minify-site-map">
			<i class="flaticon-folder24 closed"></i>
			<span class="text"><?=GetMessage('BITRONIC2_CLOSE_ALL')?></span>
		</button>
		<button type="button" class="action" id="expand-site-map">
			<i class="flaticon-open16 opened"></i>
			<span class="text"><?=GetMessage('BITRONIC2_SHOW_ALL')?></span>
		</button>
	</div>
<?endif?>			
<ul class="site-map">
	<?
		$previousLevel = -1;
		foreach($arResult as $arItem):?>
			<?if($arItem["DEPTH_LEVEL"]<$previousLevel):?>
				<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
			<?endif?>
			
			<?if($arItem["IS_PARENT"]):?>
				<li><div class="site-map-item-wrap expandable allow-multiple-expanded">
				<div class="list-item">
					<i class="flaticon-folder24 closed"></i>
					<i class="flaticon-open16 opened"></i>
					<i class="flaticon-newspapre not-expandable"></i>
					<a href="<?=$arItem["LINK"]?>" class="link"><span class="text"><?=$arItem["TEXT"]?></span></a>
				</div>
				<ul class="site-map expand-content">
			<?else:?>
				<li><div class="site-map-item-wrap">
					<div class="list-item">
						<i class="flaticon-folder24 closed"></i>
						<i class="flaticon-open16 opened"></i>
						<i class="flaticon-newspapre not-expandable"></i>
						<a href="<?=$arItem["LINK"]?>" class="link"><span class="text"><?=$arItem["TEXT"]?></span></a>
					</div>
				</div></li>
			<?endif?>
		<?$previousLevel = $arItem["DEPTH_LEVEL"];?>
		<?endforeach?>	
		<?if ($previousLevel > 1): //close last item tags?>
			<?=str_repeat("</ul></li>", ($previousLevel-1) );?>
		<?endif?>	
</ul>