<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?if($arResult['PATH'][0]):?>

<?
CModule::IncludeModule('yenisite.resizer2');

$pathD = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_DETAIL']);
$pathB = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_BIG_DETAIL']);

$multi = count($arResult['PATH']) > 1;
?>

<script type="text/javascript">
jQuery(document).ready(function($){
	$("#yenisite-imageZoomer").addimagezoom({
<?if ($arParams['CURSOR_SHADE'] == 'true'):?>
		cursorshade: true,
		cursorshadecolor: '#<?=$arParams['CURSOR_SHADE_COLOR']?>',
		cursorshadeopacity: <?=$arParams['CURSOR_SHADE_OPACITY']?>,
<?endif?>
<?if ($arParams['SHOW_DESCRIPTION'] == 'Y' && $multi):?>
		descArea: '#yenisite-desc',
<?endif?>
		disablewheel: <?=$arParams['DISABLE_WHEEL']?>,
		magvertcenter: <?=$arParams['MAGNIFIER_VERT_CENTER']?>,
<?if (!$multi):?>
		largeimage: '<?=$pathB?>',
<?endif?>
<?if ($arParams['Z_INDEX'] != ''):?>
		zIndex: <?=$arParams['Z_INDEX']?>,
<?endif?>
		magnifierpos: '<?=$arParams['MAGNIFIER_POSITION']?>',
		magnifiersize: <?=$arParams['MAGNIFIER_SIZE']?>,
		zoomrange: <?=$arParams['ZOOM_RANGE']?>,
		initzoomablefade: false,
		zoomablefade: <?=$arParams['ZOOM_ABLE_FADE']?>,
		speed: <?=$arParams['FADE_SPEED']?>
	});
});
</script>

<div class="yenisite-photos">

<div class="yenisite-bigphoto">
	<img class="yenisite-detail" id="yenisite-imageZoomer" alt="zoomable" title="<?=$arResult['DESCRIPTION'][0]?>" src="<?=$pathD?>"/>
</div>

<?if ($arParams['SHOW_DESCRIPTION'] == 'Y'):?>
<div id="yenisite-desc"><?=$arResult['DESCRIPTION'][0]?></div>
<?endif?>

<?if ($multi):?>
<?$size = CResizer2Set::GetByID($arParams['SET_DETAIL'])?>
<div class="yenisite-imageZoomer thumbs" style="width: <?=$size['w']+4?>px">
	<?$i = 0;
	foreach($arResult['PATH'] as $path):
	$pathS = CResizer2Resize::ResizeGD2($path, $arParams['SET_SMALL_DETAIL']);
	$pathD = CResizer2Resize::ResizeGD2($path, $arParams['SET_DETAIL']);
	$pathB = CResizer2Resize::ResizeGD2($path, $arParams['SET_BIG_DETAIL']);
	?>
	<a href="<?=$pathD?>" data-large="<?=$pathB?>" data-title="<?=$arResult['DESCRIPTION'][$i]?>">
		<img class="yenisite-icons" src="<?=$pathS?>" alt="<?=$arResult['DESCRIPTION'][$i]?>" title="<?=$arResult['DESCRIPTION'][$i++]?>"/>
	</a>
	<?endforeach?>
</div>
<?endif?>

</div>

<?endif//($arResult['PATH'][0])?>