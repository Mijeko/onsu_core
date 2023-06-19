<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<script type="text/javascript">
 $(document).ready(function() {
	$('#gallery img').last().load(function(){
	    $(".carousel .jCarouselLite").jCarouselLite({
	        
			btnNext: ".carousel .next",
			btnPrev: ".carousel .prev",
			mouseWheel: <?=$arParams["MOUSE_WHEEL"];?>,
			auto: <?=$arParams["AUTO_SCROLL"];?>,
			speed: <?=$arParams["SPEED"];?>,
			scroll: <?=$arParams["SCROLL_MORE"];?>,
			easing: "<?=$arParams["EFFECTS"];?>",
			visible: <?=$arParams["VISIBLE"];?>,
			vertical: <?=$arParams["VERTICAL"];?>,
			circular: <?=$arParams["CIRCULAR"];?>
	    });
	});
});
<?/*
    function loader()
    {
        if($(".yedetail").attr("src") != $(".yebuff").attr("src"))
        {          
            $(".yedetail").animate(
                {opacity: "hide"},
                <?=$arParams["HIDE_DELAY_DETAIL"];?>,
                function(){
                    $(".yedetail").attr("src", $(".yebuff").attr("src"));
                    $(".yedetail").animate(
                        {opacity: "show"},
                    <?=$arParams["SHOW_DELAY_DETAIL"];?>);
                }
            );
            
        }

    }*/?>
</script>

<div id="jCarouselLiteDemo">
<div class="carousel<?if($arParams['VERTICAL']=='true'):?> vertical<?endif?>">
<a href="#" class="prev">&nbsp;</a>
<div class="jCarouselLite" >
<ul id="gallery" >
<?
CModule::IncludeModule('yenisite.resizer2');
	$i=0;
	if(count($arResult["PATH"]) >0):
		foreach($arResult["PATH"] as $value):
			$path = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_SMALL_DETAIL']);
			$i++;
?>
	<li><img src="<?=CFile::GetPath($path)?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>" /></li>
<?
		endforeach;
	endif;		
?>
</ul>

</div>

<a href="#" class="next">&nbsp;</a>
<div class="clear"></div>
</div>
</div>

