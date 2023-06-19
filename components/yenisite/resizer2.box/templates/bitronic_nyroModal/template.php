<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<script type="text/javascript">
    $(document).ready(
        function(){
						
			$('.yenisite-zoom').nyroModal({
			showCloseButton: <?=$arParams["SHOW_CLOSE_BUTTON"];?>,
			closeOnClick: <?=$arParams["CLOSE_ON_CLICK"];?>,
			
			});
			
            $(".yenisite-icons").click(
                function(){                    
                    path = $(this).attr("rel");
					title = $(this).attr("title");
                    pathb = $(this).parent().attr("rev");
					$("span.yenisite-desc").text(title);
					$(".yenisite-detail").attr("alt", title);
					$(".yenisite-detail").attr("title", title);
                    $(".yenisite-buff").attr("rel", path);
                    $(".yenisite-buff").attr("src", path);
                    $(".yenisite-zoom").attr("href", pathb);
					$(".yenisite-zoom").attr("title", title);
					//$(".yenisite-zoom").attr("rel", pathb);
                }
            );
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
	<a class="yenisite-zoom" href="<?=$pathb?>"  title="<?=$arResult["DESCRIPTION"][0]?>"><img class="yenisite-detail" src="<?=$path?>" title="<?=$arResult["DESCRIPTION"][0]?>" alt="<?=$arResult["DESCRIPTION"][0]?>" />  </a>
	<img class="yenisite-buff" src="<?=$path?>" style="display: none;" onload="loader();" rel="<?=$path?>" />
	<?if ($arParams["SHOW_DESCRIPTION"]=="Y"):?><br />
		<span class="yenisite-desc"><?=$arResult["DESCRIPTION"][0]?></span>
	<?endif?>
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
		<li><a href="javascript:void(0);" rel="tour" rev="<?=$pathbb?>"><img class="yenisite-icons" src="<?=$path?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" rel="<?=$pathb?>" /></a>
		<?endforeach?>
	<?endif?>
	</ul>
</div>
