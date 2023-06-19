<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<script type="text/javascript">
    Shadowbox.init({
    // skip the automatic setup again, we do this later manually
    skipSetup: true
});

window.onload = function() {

    // set up all anchor elements with a "movie" class to work with Shadowbox
    Shadowbox.setup(".yenisite-zoom", {
        gallery:            "My gallery",
        autoplayMovies:     true,
		//animateFade: false
		<?if($arParams["ANIMATE"]):?>animate: <?=$arParams["ANIMATE"];?>,<?endif?>
		<?if($arParams["ANIMATE_FADE"]):?>animateFade: <?=$arParams["ANIMATE_FADE"];?>,<?endif?>
		<?if($arParams["ANIMATE_SEQUENCE"]):?>animSequence: "<?=$arParams["ANIMATE_SEQUENCE"];?>",<?endif?>
		<?if($arParams["CONTINUOUS"]):?>continuous: <?=$arParams["CONTINUOUS"];?>,<?endif?>
		
		<?if($arParams["COUNTER_TYPE"]):?>counterType: "<?=$arParams["COUNTER_TYPE"];?>",<?endif?>
		<?if($arParams["COUNTER_LIMIT"]):?>counterLimit: <?=$arParams["COUNTER_LIMIT"];?>,<?endif?>
		<?if($arParams["DISPLAY_COUNTER"]):?>displayCounter: <?=$arParams["DISPLAY_COUNTER"];?>,<?endif?>
		<?if($arParams["ENABLE_KEYS"]):?>enableKeys: <?=$arParams["ENABLE_KEYS"];?>,<?endif?>
		
		<?if($arParams["FADE_DURATION"]):?>fadeDuration: <?=$arParams["FADE_DURATION"];?>,<?endif?>
		//initialHeight: <?=$arParams["INITIAL_HEIGHT"];?>,
		//initialWidth: <?=$arParams["INITIAL_WIDTH"];?>,
		<?if($arParams["MODAL"]):?>modal: <?=$arParams["MODAL"];?>,<?endif?>
		<?if($arParams["OVERLAY_COLOR"]):?>overlayColor: "#<?=$arParams["OVERLAY_COLOR"];?>",<?endif?>
		<?if($arParams["OVERLAY_OPACITY"]):?>overlayOpacity: <?=$arParams["OVERLAY_OPACITY"];?>,<?endif?>
		<?if($arParams["RESIZE_DURATION"]):?>resizeDuration: <?=$arParams["RESIZE_DURATION"];?>,<?endif?>
		<?if($arParams["SHOW_OVERLAY"]):?>showOverlay: <?=$arParams["SHOW_OVERLAY"];?>,<?endif?>
		<?if($arParams["SLIDES_SHOW_DELAY"]):?>slideshowDelay: <?=$arParams["SLIDES_SHOW_DELAY"];?>,<?endif?>
		<?if($arParams["VIEWPORT_PADDING"]):?>viewportPadding: <?=$arParams["VIEWPORT_PADDING"];?>,<?endif?>
    });

};

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

    }
</script>


<ul id="gallery">
<?
if(empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = 2;
if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 1;
if(empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 6;
CModule::IncludeModule('yenisite.resizer2');
	$i=0;
	if(count($arResult["PATH"]) >0):
		foreach($arResult["PATH"] as $value):
			$path = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_SMALL_DETAIL']);
			$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_DETAIL']);
			$pathbb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_BIG_DETAIL']);
			$i++;							
?>
	<li><a class="yenisite-zoom" rel="tour" href="<?=CFile::GetPath($pathbb)?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>"><img src="<?=CFile::GetPath($path)?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" /></a></li>
<?
		endforeach;
	endif;		
?>
</ul>

