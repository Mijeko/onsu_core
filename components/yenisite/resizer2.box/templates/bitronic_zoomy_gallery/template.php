<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (!isset($arParams['SHOW_DELAY_DETAIL'])) $arParams['SHOW_DELAY_DETAIL'] = '300';
if (!isset($arParams['HIDE_DELAY_DETAIL'])) $arParams['HIDE_DELAY_DETAIL'] = '600';
if (!isset($arParams['ZOOM_SIZE'])) $arParams['ZOOM_SIZE'] = '150';
if (!isset($arParams['ZOOM_TEXT'])) $arParams['ZOOM_TEXT'] = 'Push';
if (empty($arParams['ROUND'])) $arParams['ROUND'] = 'true';
if (empty($arParams['GLARE'])) $arParams['GLARE'] = 'true';
?>

<script type="text/javascript">
    
$(document).ready(
        function(){
			$(".yenisite-zoom").zoomy({
			zoomSize:<?=$arParams["ZOOM_SIZE"];?>,
			zoomText:'<?=$arParams["ZOOM_TEXT"];?>',
			round:<?=$arParams["ROUND"];?>, 
			glare:<?=$arParams["GLARE"];?>,
			});
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
//if(empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = 2;
if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 2;
if(empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 4;
CModule::IncludeModule('yenisite.resizer2');
	$i=0;
	if(count($arResult["PATH"]) >0):
		foreach($arResult["PATH"] as $value):
			$path = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_SMALL_DETAIL']);
			//$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_DETAIL']);
			$pathbb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_BIG_DETAIL']);
			$i++;							
?>
	<li><a class="yenisite-zoom" rel="tour" href="<?=CFile::GetPath($pathbb)?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>"><img src="<?=CFile::GetPath($path)?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" /></a></li>
<?
		endforeach;
	endif;		
?>
</ul>

