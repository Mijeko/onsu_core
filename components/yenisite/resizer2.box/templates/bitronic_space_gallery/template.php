<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 1;

if (!isset($arParams['SHOW_DELAY_DETAIL'])) $arParams['SHOW_DELAY_DETAIL'] = '300';
if (!isset($arParams['HIDE_DELAY_DETAIL'])) $arParams['HIDE_DELAY_DETAIL'] = '600';
if (!isset($arParams['DURATION'])) $arParams['DURATION'] = '800';
if (!isset($arParams['PERSPECTIVE'])) $arParams['PERSPECTIVE'] = '140';
if (!isset($arParams['MIN_SCALE'])) $arParams['MIN_SCALE'] = '20';
?>

<script type="text/javascript">
    $(document).ready(
        function(){

			$('.spacegallery').spacegallery({
				loadingClass: 'loading',
				
				duration: <?=$arParams["DURATION"];?>,
				perspective: <?=$arParams["PERSPECTIVE"];?>,
				minScale: <?=$arParams["MIN_SCALE"];?>/100
				});          
        }
    );
	
	//подгоняет блок spacegallery по высоте под размер картинки
	$(function() {
	<?$arRes = CResizer2Set::GetByID( $arParams["SET_BIG_DETAIL"] );?>
	var space = $(".spacegallery");
	height = <?=$arRes['h'];?>;
	width = <?=$arRes['w'];?>;
	space.css('height',height+<?=$arParams["PERSPECTIVE"];?>);
	});
	
	
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

<div  class="spacegallery">
<ul id="gallery">
<?
CModule::IncludeModule('yenisite.resizer2');
	$i=0;
	if(count($arResult["PATH"]) >0):
		foreach($arResult["PATH"] as $value):
			//$path = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_SMALL_DETAIL']);
			//$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_DETAIL']);
			$pathbb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_BIG_DETAIL']);
			$i++;							
?>
	<li><img src="<?=CFile::GetPath($pathbb)?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" /></a></li>
<?
		endforeach;
	endif;		
?>
</ul>
<a id="btnNext" href="#" ></a>
</div>
