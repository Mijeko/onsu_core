<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<? if(!is_array($arResult["PATH"])): ?>

	<?if($arParams["DROP_PREVIEW_DETAIL"]!="Y" && $arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
			<img class="detail_picture" border="0" src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>" height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["NAME"]?>"  title="<?=$arResult["NAME"]?>" />
	<?endif?>

<? else: ?>

<script type="text/javascript">
    $(document).ready(
        function(){

			$('.yenisite-zoom').lightBox(
			{
				overlayOpacity:           <?=$arParams["OVERLAY_OPACITY"];?>,
				fixedNavigation:          <?if ($arParams["NAVIGATION"]=="Y")echo "true"; else echo "false";?>,
				containerResizeSpeed:     <?=$arParams["ZOOM_SPEED_IN"];?>
			}
			);          
        }
    );

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
CModule::IncludeModule('yenisite.resizer2');
	$i=0;
	if(count($arResult["PATH"]) >0):
		foreach($arResult["PATH"] as $value):
			$path = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_SMALL_DETAIL']);
			$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_DETAIL']);
			$pathbb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_BIG_DETAIL']);
			$i++;							
?>
	<li><a class="yenisite-zoom" rel="fancy-tour" href="<?=CFile::GetPath($pathbb)?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>"><img src="<?=CFile::GetPath($path)?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" /></a></li>
<?
		endforeach;
	endif;		
?>
</ul>

<?endif?>
