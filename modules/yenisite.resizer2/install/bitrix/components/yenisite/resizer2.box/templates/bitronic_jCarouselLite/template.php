<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
CModule::IncludeModule('yenisite.resizer2');
if($arResult['PATH'][0]):
	$path = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_DETAIL']);
	$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_BIG_DETAIL']);
    $arSettings = CResizer2Settings::GetSettings();
?>

<script type="text/javascript">

$(document).ready(function() {
	$('#yenisite-gallery img').last().load(function(){
	    $(".yenisite-photos .carousel .jCarouselLite").jCarouselLite({
	        
			btnNext: ".yenisite-photos .carousel .next",
			btnPrev: ".yenisite-photos .carousel .prev",
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
    var pp = {theme: 'dark_rounded'};
    var $z = $(".yenisite-zoom");
<?
$bBox = true;
do {
	if ($arSettings['fancy']      == 'Y') {echo '$z.fancybox();';      break;}
	if ($arSettings['pretty']     == 'Y') {echo '$z.prettyPhoto(pp);'; break;}
	if ($arSettings['nyroModal']  == 'Y') {echo '$z.nyroModal();';     break;}
	if ($arSettings['nflightbox'] == 'Y') {echo '$z.lightBox();';      break;}
	if ($arSettings['colorbox']   == 'Y') {echo '$z.colorbox();';      break;}
	$bBox = false;
} while (0);
?>
});

$(document).ready(function(){
	$(".yenisite-icons").click(function(){
		
		path = $(this).attr("rel");
		title = $(this).attr("title");
		pathb = $(this).parent().attr("rel");
		$("span.yenisite-desc").text(title);
		$(".yenisite-detail").attr("alt", title);
		$(".yenisite-detail").attr("title", title);
		$(".yenisite-buff").attr("rel", path);
		$(".yenisite-buff").attr("src", path);
		<?if($bBox):?>
		$(".yenisite-zoom").attr("href", pathb);
		$(".yenisite-zoom").attr("title", title);
		<?endif?>
	});
});

    function loader()
    {
        if($(".yenisite-detail").attr("src") != $(".yenisite-buff").attr("src"))
        {          
            $(".yenisite-detail").animate(
                {opacity: "hide"},
                <?=$arParams["HIDE_DELAY_DETAIL"];?>,
                function(){
                    $(".yenisite-detail").attr("src", $(".yenisite-buff").attr("src"));
                    $(".yenisite-detail").animate(
                        {opacity: "show"},
                    <?=$arParams["SHOW_DELAY_DETAIL"];?>);
                }
            );
            
        }

    }
</script>

<div class="yenisite-photos<?if($arParams['VERTICAL']=='true'):?> vertical<?endif?>">


<div class="yenisite-bigphoto">
	<?if($bBox):?>
	<a class="yenisite-zoom" href="<?=$pathb?>" rel="tour" title="<?=$arResult["DESCRIPTION"][0]?>">
	<?endif?>
		<img class="yenisite-detail" src="<?=$path?>" title="<?=$arResult["DESCRIPTION"][0]?>" alt="<?=$arResult["DESCRIPTION"][0]?>" />
	<?if($bBox):?>
	</a>
	<?endif?>
	<img class="yenisite-buff" src="<?=$path?>" style="display: none;" onload="loader();" rel="<?=$path?>" />
	
</div>
<?endif?>

<div id="jCarouselLiteDemo">
<div class="carousel<?if($arParams['VERTICAL']=='true'):?> vertical<?endif?>">
<a href="#" class="prev">&nbsp;</a>

<div class="jCarouselLite">
	<ul id="yenisite-gallery">
	<?
	$i=0;
	if(count($arResult["PATH"]) >1):
		foreach($arResult["PATH"] as $value):
			$path = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_SMALL_DETAIL']);
			$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_DETAIL']);
			$pathbb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_BIG_DETAIL']);
			$i++;
        ?>
		<li><a href="javascript:void(0);"  rel="<?=$pathbb?>"><img class="yenisite-icons" src="<?=$path?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" rel="<?=$pathb?>" /></a>
		<?endforeach?>
	<?endif?>
	</ul>
	
</div>
<a href="#" class="next">&nbsp;</a>
<div class="clear"></div>
</div>
</div>
</div>
