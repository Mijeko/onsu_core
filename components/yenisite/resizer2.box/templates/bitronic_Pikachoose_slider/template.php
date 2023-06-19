<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (!isset($arParams['TRANSITION'])) $arParams['TRANSITION'] = '-1';
if (!isset($arParams['SPEED'])) $arParams['SPEED'] = '600';
if (!isset($arParams['THUMB_OPACITY'])) $arParams['THUMB_OPACITY'] = '0.4';
if (!isset($arParams['THUMB'])) $arParams['THUMB'] = 'bottom';
?>


<script type="text/javascript">
    $(document).ready(
        function() {
            $("#pikame").PikaChoose( {
				carousel : true,
				<?if ( $arParams["THUMB"] != "bottom"):?>
					carouselVertical:true,
				<?endif;?>
				carouselOptions: { wrap : 'circular' },
				
				transition: [<?=$arParams["TRANSITION"]?>],
				
				animationSpeed: <?=$arParams["SPEED"]?>,
				
				<?if ( $arParams["AUTOPLAY_SLIDE_SHOW"] != "Y" ):?>
					autoPlay: false,
				<?endif;?>
				
				thumbOpacity: <?=$arParams["THUMB_OPACITY"]?>,
				
				<?if ( $arParams["CAPTION"] != "Y" ):?>
					showCaption: false,
				<?endif;?>
				hoverPause: true
			} );
        }
    );
</script>

<div class="pikachoose">
	<ul id="pikame" class="jcarousel-skin-pika">
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
		<li><a href="javascript:void(0);"><img src="<?=$pathbb?>" /></a><span><?=$arResult["DESCRIPTION"][$i-1]?></span></li>
	<?
			endforeach;
		endif;		
	?>
	</ul>
</div>
