<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = '2';
if (!isset($arParams['SQUARES_PER_WIDTH'])) $arParams['SQUARES_PER_WIDTH'] = '7';
if (!isset($arParams['SQUARES_PER_HEIGHT'])) $arParams['SQUARES_PER_HEIGHT'] = '5';
if (!isset($arParams['SLIDE_SHOW_INTERVAL'])) $arParams['SLIDE_SHOW_INTERVAL'] = '2500';
if (!isset($arParams['DELAY'])) $arParams['DELAY'] = '30';
if (!isset($arParams['OPACITY'])) $arParams['OPACITY'] = '0.7';
if (!isset($arParams['TITLE_SPEED'])) $arParams['TITLE_SPEED'] = '500';
if (!isset($arParams['EFFECT'])) $arParams['EFFECT'] = 'random';
?>

<script type="text/javascript">
    $(document).ready(
        function() {
            $('#coin-slider').coinslider( {
				<?$arRes = CResizer2Set::GetByID( $arParams["SET_DETAIL"] );?>
				width: <?=$arRes["w"]?>,
				height: <?=$arRes["h"]?>,
				spw: <?=$arParams["SQUARES_PER_WIDTH"]?>,
				sph: <?=$arParams["SQUARES_PER_HEIGHT"]?>,
				delay: <?=$arParams["SLIDE_SHOW_INTERVAL"]?>,
				sDelay: <?=$arParams["DELAY"]?>,
				opacity: <?=$arParams["OPACITY"]?>,
				titleSpeed: <?=$arParams["TITLE_SPEED"]?>,
				effect: '<?=$arParams["EFFECT"]?>',
				
				<?if ( $arParams["NAVIGATION"] != "Y" ):?>
					navigation: false,
				<?endif;?>
				
				<?if ( $arParams["HOVER_PAUSE"] != "Y" ):?>
					hoverPause: false,
				<?endif;?>
			} );
			
			$('.cs-title').css( { width: <?=$arRes["w"]?> - 20 } );
        }
    );
</script>

<div id="coin-slider">

<?
CModule::IncludeModule('yenisite.resizer2');
	$i=0;
	if(count($arResult["PATH"]) >0):
		foreach($arResult["PATH"] as $value):
			//$path = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_SMALL_DETAIL']);
			$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_DETAIL']);
			//$pathbb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_BIG_DETAIL']);
			$i++;							
?>
			<a href="javascript:void(0);"><img src="<?=CFile::GetPath($pathb)?>" /><span><?=$arResult["DESCRIPTION"][$i-1]?></span></a>
<?
		endforeach;
	endif;		
?>

</div>
