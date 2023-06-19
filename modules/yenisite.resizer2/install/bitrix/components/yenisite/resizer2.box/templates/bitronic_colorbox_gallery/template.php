<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (!isset($arParams['THEME'])) $arParams['THEME'] = 'skin1';
if (!isset($arParams['SLIDE_SHOW_INTERVAL'])) $arParams['SLIDE_SHOW_INTERVAL'] = '1500';
if (!isset($arParams['OVERLAY_OPACITY'])) $arParams['OVERLAY_OPACITY'] = '0.8';
if (!isset($arParams['TRANSITION'])) $arParams['TRANSITION'] = 'fade';
if (!isset($arParams['SPEED'])) $arParams['SPEED'] = '350';
?>

<script type="text/javascript">
    $(document).ready(
        function() {
            $(".yenisite-zoom").colorbox( {
					rel			: 'yenisite-zoom',
					transition	: '<?=$arParams["TRANSITION"]?>',
					speed		: '<?=$arParams["SPEED"]?>',
					opacity		: '<?=$arParams["OVERLAY_OPACITY"]?>',
					
					<?if ($arParams["SLIDE_SHOW"] == "Y"):?>
					slideshow	: true,
					slideshowSpeed: '<?=$arParams["SLIDE_SHOW_INTERVAL"]?>',
						<?if ($arParams["AUTOPLAY_SLIDE_SHOW"] == "N"):?>
							slideshowAuto: false,
						<?endif;?>
					<?endif;?>
			} );
        }
    );
</script>

<ul id="gallery">
<?
//if(!$arParams['SET_DETAIL']) $arParams['SET_DETAIL'] = 2;
if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 1;
if(empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 6;
CModule::IncludeModule('yenisite.resizer2');
	$i=0;
	if(count($arResult["PATH"]) >0):
		foreach($arResult["PATH"] as $value):
			$path = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_SMALL_DETAIL']);
			//$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_DETAIL']);
			$pathbb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_BIG_DETAIL']);
			$i++;							
?>
	<li><a class="yenisite-zoom" href="<?=CFile::GetPath($pathbb)?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>"><img src="<?=CFile::GetPath($path)?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" /></a></li>
<?
		endforeach;
	endif;		
?>
</ul>
