<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<script type="text/javascript">

    $(document).ready(
        function(){
				
            $(".yenisite-icons").click(
                function(){                    
                    path = $(this).attr("rel");
					title = $(this).attr("title");
                    pathb = $(this).parent().attr("rel");
					$("span.yenisite-desc").text(title);
					$(".yenisite-detail").attr("alt", title);
					$(".yenisite-detail").attr("title", title);
                    //$(".yenisite-buff").attr("rel", path);
                    //$(".yenisite-buff").attr("src", path);
                    //$(".cloud-zoom").attr("href", pathb);
					$(".cloud-zoom").attr("title", title);
					//$(".cloud-zoom").attr("rel", pathb);
                }
            );
        }
    );
		// only for ajax load component
		$('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
/*
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
*/
</script>

<div class='yenisite-photos'>
	
<?
if(empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = 2;
if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 7;
if(empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 6;
CModule::IncludeModule('yenisite.resizer2');
if($arResult['PATH'][0]):
	$path = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_DETAIL']);
	$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_BIG_DETAIL']);
	//отсекаем из ссылки все параметры после знака '?'
	$pos=strpos($pathb,"?");
	if($pos>0)
	$pathb=substr($pathb,0,$pos);
?>



<div class="yenisite-bigphoto">
	<a class="cloud-zoom" href="<?=$pathb?>" id="zoom1" rel="softFocus: <?=$arParams["SOFT_FOCUS"];?>, zoomWidth:<?=$arParams["ZOOM_WIDTH"];?>, zoomHeight:<?=$arParams["ZOOM_HEIGHT"];?>, position:'<?=$arParams["POSITION"];?>' , adjustX:<?=$arParams["ADJUST_X"];?> , adjustY:<?=$arParams["ADJUST_Y"];?>, tint: '#<?=$arParams["TINT"];?>',tintOpacity:<?=$arParams["TINT_OPACITY"];?> ,lensOpacity:<?=$arParams["LENS_OPACITY"];?> ,smoothMove:<?=$arParams["SMOOTH_MOVE"];?> ,showTitle:<?=$arParams["SHOW_TITLE"];?> ,titleOpacity:<?=$arParams["TITLE_OPACITY"];?>" title="<?=$arResult["DESCRIPTION"][0]?>">
		<img class="yenisite-detail" src="<?=$path?>" title="<?=$arResult["DESCRIPTION"][0]?>" alt="<?=$arResult["DESCRIPTION"][0]?>"  />
	</a>
	<img class="yenisite-buff" src="<?=$path?>" style="display: none;" rel="<?=$path?>" />
	
	</div>
	
	
	
<?endif?>

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
		<li><a class="cloud-zoom-gallery" href="<?=$pathbb?>" rel="useZoom: 'zoom1', smallImage: '<?=$pathb?>'"><img class="yenisite-icons" src="<?=$path?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" rel="<?=$pathb?>" /></a>
		<?endforeach?>
	<?endif?>
	</ul>
</div>
