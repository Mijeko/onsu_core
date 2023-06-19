<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";

$id = 'bx_dynamic_'.$this->randString(20);
?>

			<div class="brands-catalog hidden-xs wow fadeIn" id="<?=$id?>"><?

	$frame = $this->createFrame($id, false)->begin();
	if (!empty($arResult['ITEMS'])):
		foreach ($arResult['ITEMS'] as $arItem):
	
				?><div itemscope itemtype="http://schema.org/ImageObject" class="brand">
					<a href="<?=$arItem['LINK']?>" class="brand-img">
						<img itemprop="contentUrl" title="<?=$arItem['NAME']?>" class="lazy" data-original="<?=$arItem['PICT']['SRC']?>" src="<?=consVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['NAME']?>">
					</a>
					<?//TODO <sup>43</sup>?>
				</div><?

		endforeach;
	endif;
	include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';
	$frame->end();
	?>
			</div><!-- /.brands-catalog -->
			<div class="brands-catalog-toggle-wrap">
				<a href="#<?=$id?>"
				   class="pseudolink-bd link-std collapsed" data-toggle="height-collapse"
				   data-when-expanded="<?=GetMessage('RZ_COLLAPSE_CATALOG_BRANDS')?>"
				   data-when-collapsed="<?=GetMessage('RZ_EXPAND_CATALOG_BRANDS')?>"></a>
			</div>

<? unset($_SESSION['RZ_SECTION_BRANDS']) ?>
