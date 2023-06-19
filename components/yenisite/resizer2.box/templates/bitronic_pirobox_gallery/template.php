<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<script type="text/javascript">
 $(document).ready(function() {
    $().piroBox_ext({
        piro_speed : <?=$arParams["PIRO_SPEED"];?>,
        bg_alpha : <?=$arParams["BG_ALPHA"];?>,
        piro_scroll : <?=$arParams["PIRO_SCROLL"];?> //pirobox always positioned at the center of the page
    });
    
	var pirobox = $(".piro_overlay");
	color = '<?=$arParams["PIRO_COLOR"]?>';
	pirobox.css('background','#'+color);
});
</script>


<ul id="gallery">
<?
CModule::IncludeModule('yenisite.resizer2');
	$i=0;
	if(count($arResult["PATH"]) >0):
		foreach($arResult["PATH"] as $value):
			$path = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_SMALL_DETAIL']);
			$pathbb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_BIG_DETAIL']);
			$i++;
?>
	<li><a class="pirobox_gall" rel="gallery" href="<?=CFile::GetPath($pathbb)?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>"><img src="<?=CFile::GetPath($path)?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" /></a></li>
<?
		endforeach;
	endif;		
?>
</ul>


