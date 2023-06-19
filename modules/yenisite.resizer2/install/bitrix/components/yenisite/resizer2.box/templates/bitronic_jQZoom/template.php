<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
    $arParams["SHOW_TITLE"] = ($arParams["SHOW_TITLE"] == "Y")? "true" : "false";
    $arParams["ZOOM_TYPE"] = (!isset($arParams["ZOOM_TYPE"]))? "window" : $arParams["ZOOM_TYPE"];
    $arParams["SHOW_EFFECT"] = (!isset($arParams["SHOW_EFFECT"]))? "show" : $arParams["SHOW_EFFECT"];
    $arParams["HIDE_EFFECT"] = (!isset($arParams["HIDE_EFFECT"]))? "hide" : $arParams["HIDE_EFFECT"];
    $arParams["ZOOM_SPEED_IN"] = (!isset($arParams["ZOOM_SPEED_IN"]))? "500" : $arParams["ZOOM_SPEED_IN"];
    $arParams["ZOOM_SPEED_OUT"] = (!isset($arParams["ZOOM_SPEED_OUT"]))? "700" : $arParams["ZOOM_SPEED_OUT"];
	
    //вычисление размеров окна зума
    CModule::IncludeModule('yenisite.resizer2');
    /*
    $setBig = CResizer2Set::GetByID($arParams['SET_BIG_DETAIL']);
    $setDet = CResizer2Set::GetByID($arParams['SET_DETAIL']);
    
    $ratioBig = $setBig['w'] / $setBig['h'];
    $ratioDet = $setDet['w'] / $setDet['h'];
    $ratioSum = $ratioBig    / $ratioDet;

    $arParams['ZOOM_WIDTH']  = ($ratioSum <= 1)? 300 : 300 * $ratioSum;
    $arParams['ZOOM_HEIGHT'] = ($ratioSum >= 1)? 300 : 300 / $ratioSum;
    */
?>

<script type="text/javascript">
var jqZoomConf = {
	zoomType: "<?=$arParams["ZOOM_TYPE"];/*?>",
	zoomWidth: "<?=round($arParams['ZOOM_WIDTH'], 0)?>",
	zoomHeight: "<?=round($arParams['ZOOM_HEIGHT'], 0)*/?>",
	showEffect: "<?=$arParams["SHOW_EFFECT"];?>",
	hideEffect: "<?=$arParams["HIDE_EFFECT"];?>",
	fadeinSpeed: <?=$arParams["ZOOM_SPEED_IN"];?>,
	fadeoutSpeed: <?=$arParams["ZOOM_SPEED_OUT"];?>,
	title: <?=$arParams["SHOW_TITLE"];?>,
	lens: true,
	preloadImages: false,
	alwaysOn: false
};

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
                    $(".yenisite-buff").attr("rel", path);
                    $(".yenisite-buff").attr("src", path);
                    $(".yenisite-zoom").attr("href", pathb);
                    $(".yenisite-zoom").attr("title", title);
					
					$('.yenisite-icons').removeClass('active');
					$(this).addClass('active');
                }
            );
            $('.yenisite-zoom').jqzoom(jqZoomConf);
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
                    var $yZoom = $("a.yenisite-zoom");
                    var title = $yZoom.attr('title');
                    $yZoom.replaceWith('<a class="yenisite-zoom" href="' + $yZoom.attr('href') + '" title="' + title + '"><img class="yenisite-detail" src="' + $('.yenisite-buff').attr('src') + '" title="' + title + '" alt="' + title + '" /></a>');
                    $('a.yenisite-zoom').jqzoom(jqZoomConf);
					$('a.yenisite-zoom').hide().animate(
                        {opacity: "show"},
                    	<?=$arParams["SHOW_DELAY_DETAIL"];?>,
						function(){}
                    );
                }
            );
        }
    }
</script>

<div class='yenisite-photos'>
	
<?
if(empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = 2;
if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 7;
if(empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 6;
if($arResult['PATH'][0]):
	$path = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_DETAIL']);
	$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_BIG_DETAIL']);
	
?>
<div class="yenisite-bigphoto">
    <a class="yenisite-zoom" href="<?=$pathb?>" title="<?=$arResult["DESCRIPTION"][0]?>">
	<img class="yenisite-detail" src="<?=$path?>" title="<?=$arResult["DESCRIPTION"][0]?>" alt="<?=$arResult["DESCRIPTION"][0]?>" />
    </a>
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
		<li><a href="javascript:void(0);" rel="<?=$pathbb?>"><img class="yenisite-icons<?=($i==1)?' active':''?>" src="<?=$path?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" rel="<?=$pathb?>" /></a>
		<?endforeach?>
	<?endif?>
	</ul>
	</div>
</div>
