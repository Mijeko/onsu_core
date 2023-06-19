<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<script type="text/javascript">

    $(document).ready(
		
        function(){
		
			var zoomConfig = {
				cursor: "crosshair",
				scrollZoom: <?=$arParams['SCROLL_ZOOM']?>,
				easing: <?=$arParams['EASING']?>,
				zoomWindowWidth: <?=$arParams['WINDOW_WIDTH']?>,
				zoomWindowHeight: <?=$arParams['WINDOW_HEIGHT']?>,
				zoomWindowOffetx: <?=$arParams['WINDOW_OFFSET_X']?>,
				zoomWindowOffety: <?=$arParams['WINDOW_OFFSET_Y']?>,
				zoomWindowPosition: <?=$arParams['WINDOW_POSITION']?>,
				zoomWindowBgColour: '#<?=$arParams['WINDOW_BG_COLOUR']?>',
				zoomWindowFadeIn: <?=$arParams['WINDOW_FADE_IN']?>,
				zoomWindowFadeOut: <?=$arParams['WINDOW_FADE_OUT']?>,
				borderSize: <?=$arParams['BORDER_SIZE']?>,
				borderColour: '#<?=$arParams['BORDER_COLOUR']?>',
				zoomType: '<?=$arParams['ZOOM_TYPE']?>',
				showLens: <?=$arParams['SHOW_LENS']?>,
				lensSize: <?=$arParams['LENS_SIZE']?>,
				lensBorderSize: <?=$arParams['LENS_BORDER_SIZE']?>,
				lensBorderColour: '#<?=$arParams['LENS_BORDER_COLOUR']?>',
				lensShape: '<?=$arParams['LENS_SHAPE']?>',
				containLensZoom: <?=$arParams['CONTAIN_LENS_ZOOM']?>,
				lensFadeIn: <?=$arParams['LENS_FADE_IN']?>,
				lensFadeOut: <?=$arParams['LENS_FADE_OUT']?>,
				lensColour: '#<?=$arParams['LENS_COLOUR']?>',
				lensOpacity: <?=$arParams['LENS_OPACITY']?>,
				tint: <?=$arParams['TINT']?>,
				tintColour: '#<?=$arParams['TINT_COLOUR']?>',
				tintOpacity: <?=$arParams['TINT_OPACITY']?>,
				zoomTintFadeIn: <?=$arParams['TINT_FADE_IN']?>,
				zoomTintFadeOut: <?=$arParams['TINT_FADE_OUT']?>
			};
			var zoomImage = $('#yenisite-detail');
			
            $(".yenisite-icons").click(
                function(){                    
                    path = $(this).attr("rel");
					title = $(this).attr("title");
                    pathb = $(this).parent().attr("rel");
					$("span.yenisite-desc").text(title);
					$(".yenisite-detail").attr("alt", title);
					$(".yenisite-detail").attr("title", title);
                    $(".yenisite-buff").attr("rel", path);
                    $(".yenisite-buff").attr("src", path);
                    $(".yenisite-zoom").attr("href", pathb);
					$(".yenisite-zoom").attr("title", title);
					
					$('.zoomContainer').remove();
					zoomImage.removeData('elevateZoom');
					zoomImage.data('zoom-image', pathb);
					//Реинициализация
					zoomImage.elevateZoom(zoomConfig);
                }
            );
			
			zoomImage.elevateZoom(zoomConfig);
        }
    );

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

<div class='yenisite-photos'>
	
<?
CModule::IncludeModule('yenisite.resizer2');
if($arResult['PATH'][0]):
	$path = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_DETAIL']);
	$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_BIG_DETAIL']);
	
?>
<div class="yenisite-bigphoto">
	<img class="yenisite-detail" id='yenisite-detail' src="<?=$path?>" data-zoom-image="<?=$pathb;?>" title="<?=$arResult["DESCRIPTION"][0]?>" alt="<?=$arResult["DESCRIPTION"][0]?>" />
	<img class="yenisite-buff" src="<?=$path?>" style="display: none;" onLoad="loader();" rel="<?=$path?>" />
	
</div>
<?endif?>
	
	<div id='yeni-gallery'>
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
		<li><a href="javascript:void(0);" rel="<?=$pathbb?>"><img class="yenisite-icons" src="<?=$path?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" rel="<?=$pathb?>" /></a>
		<?endforeach?>
	<?endif?>
	</ul>
	</div>
</div>
