<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (method_exists($this, 'setFrameMode')) $this->setFrameMode(true);

\Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/custom-scripts/libs/UmHeightControl.js');

?>
<div class="feedback wow fadeIn hidden-xs drag-section sFeedback" data-order="<?=$arParams['FEEDBACK_ORDER']?>">
	<div class="container carousel slide" id="comments-carousel">
		<div class="quote-start"></div>
		<div class="quote-end"></div>
		<header><?=GetMessage("RZ_OTZIVI_POKUPATELEJ")?></header>
		<div class="slider-controls carousel-indicators"></div>
		<div class="comments carousel-inner" id="main-shop-reviews"></div><!-- /.comments -->
		<div class="controls">
			<a class="flaticon-arrow133 arrow prev" href="#comments-carousel" data-slide="prev"></a>
			<a class="flaticon-right20 arrow next" href="#comments-carousel" data-slide="next"></a>
		</div>
		<a class="all-comments link" href="http://market.yandex.ru/shop/<?=$arParams['SHOPID']?>/reviews"><span class="text"><?=GetMessage('LINK')?></span></a>
	</div><!-- /.container -->
</div><!-- /.feedback -->
<script type="text/javascript">
	$(document).ready(function () {
		updateYRMS(1, '<?=$templateFolder?>', '<?=$arParams["COUNT"]?>', '<?=$APPLICATION->GetCurPage(true)?>');
	})
</script>