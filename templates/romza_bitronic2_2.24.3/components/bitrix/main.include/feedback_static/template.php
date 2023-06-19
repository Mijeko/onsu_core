<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
use Yenisite\Core\Tools;
\Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/custom-scripts/libs/UmHeightControl.js');

$this->setFrameMode(true);
?><?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (method_exists($this, 'setFrameMode')) $this->setFrameMode(true);
?>
<div class="feedback wow fadeIn hidden-xs drag-section sFeedback" data-order="<?=$arParams['FEEDBACK_ORDER']?>">
	<div class="container carousel slide" id="comments-carousel">
		<div class="quote-start"></div>
		<div class="quote-end"></div>
		<header><?= GetMessage("RZ_OTZIVI_POKUPATELEJ") ?></header>
		<div class="slider-controls carousel-indicators"></div>
		<div class="comments carousel-inner" id="main-shop-reviews">
			<? if ($arResult["FILE"] <> '')
				include($arResult["FILE"]); ?>
		</div>
		<!-- /.comments -->
        <div class="controls">
            <a class="flaticon-arrow133 arrow prev" href="#comments-carousel" data-slide="prev"></a>
            <a class="flaticon-right20 arrow next" href="#comments-carousel" data-slide="next"></a>
        </div>
	</div>
	<!-- /.container -->
</div><!-- /.feedback -->
<script type="text/javascript">
    b2.init.feedbackCarousel('.feedback');
</script>