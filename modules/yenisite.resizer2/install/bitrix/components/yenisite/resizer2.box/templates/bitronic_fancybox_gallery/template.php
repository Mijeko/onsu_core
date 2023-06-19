<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (!isset($arParams['OPEN_SPEED']))  $arParams['OPEN_SPEED']  = 'normal';
if (!isset($arParams['CLOSE_SPEED'])) $arParams['CLOSE_SPEED'] = 'normal';
if (empty($arParams['PREV_EFFECT'])) $arParams['PREV_EFFECT'] = 'elastic';
if (empty($arParams['NEXT_EFFECT'])) $arParams['NEXT_EFFECT'] = 'elastic';
if (empty($arParams['OPEN_EFFECT'])) $arParams['OPEN_EFFECT'] = 'fade';
if (empty($arParams['CLOSE_EFFECT'])) $arParams['CLOSE_EFFECT'] = 'fade';
if (!isset($arParams['OVERLAY_OPACITY'])) $arParams['OVERLAY_OPACITY'] = '0.8';
?>

<script type="text/javascript">
    $(document).ready(
        function(){
            $(".yenisite-zoom").fancybox(
			{	
				openSpeed		: '<?=$arParams["OPEN_SPEED"]?>',
				closeSpeed		: '<?=$arParams["CLOSE_SPEED"]?>',
				
				prevEffect		: '<?=$arParams["PREV_EFFECT"]?>',
				nextEffect		: '<?=$arParams["NEXT_EFFECT"]?>',
				openEffect		: '<?=$arParams["OPEN_EFFECT"]?>',
				closeEffect		: '<?=$arParams["CLOSE_EFFECT"]?>',
				
				helpers	: {

					<?if ($arParams["THUMBS"] == 'Y'):?>
					thumbs	: {
						width	: 50,
						height	: 50
					},
					<?endif;?>
						
					<?if ($arParams["BUTTONS"] == 'Y'):?>
					buttons	: {},
					<?endif;?>
					
					<?if ( $arParams["OVERLAY"] == "true" ):?>
					
					overlay : {
						css : {'background' : 'rgba(45, 45, 45, <?=$arParams["OVERLAY_OPACITY"]?>)'},
					},
					
					<?else:?>
					overlay : {
						css : { 'background' : 'rgba(45, 45, 45, 0.0)' },
					},
					<?endif;?>
					
					<?if ( $arParams["SHOW_DESCRIPTION"] == "Y" ):?>
					title: {
						type : 'outside',
					},
					<?endif;?>
				}
				
			}
				);
        }
    );

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
	<li><a class="yenisite-zoom" rel="fancy-tour" href="<?=CFile::GetPath($pathbb)?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>"><img src="<?=CFile::GetPath($path)?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" /></a></li>
<?
		endforeach;
	endif;	
?>
</ul>
