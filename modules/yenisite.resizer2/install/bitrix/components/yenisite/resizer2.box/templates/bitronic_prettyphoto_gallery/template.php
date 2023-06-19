<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (!isset($arParams['SLIDE_SHOW_INTERVAL'])) $arParams['SLIDE_SHOW_INTERVAL'] = '1500';
if (!isset($arParams['OVERLAY_OPACITY'])) $arParams['OVERLAY_OPACITY'] = '0.4';
if (!isset($arParams['ANIMATION_SPEED'])) $arParams['ANIMATION_SPEED'] = 'fast';
if (empty($arParams['THEME'])) $arParams['THEME'] = 'dark_rounded';
?>

<script type="text/javascript">
  $(document).ready(
        function(){
			$("a[rel^='prettyPhoto']").prettyPhoto( // add .yenisite-zoom
			{
				theme			: '<?=$arParams["THEME"]?>',
				opacity			: <?=$arParams["OVERLAY_OPACITY"]?>,
				animation_speed : '<?=$arParams["ANIMATION_SPEED"]?>',
				
				<?if ( $arParams["SHOW_DESCRIPTION"] == "Y" ):?>
				show_title		: true,
				<?else:?>
				show_title		: false,
				<?endif;?>
				
				<?if ( $arParams["SLIDE_SHOW"] == "Y" ): ?>
				slideshow		: '<?=$arParams["SLIDE_SHOW_INTERVAL"]?>',
				<?else:?>
				slideshow		: false,
				<?endif;?>
				
				<?if ( $arParams["AUTOPLAY_SLIDE_SHOW"] == "Y" ):?>
				autoplay_slideshow : true,
				<?endif;?>
				
				<?if ( $arParams["ALLOW_RESIZE"] == "Y" ):?>
				allow_resize	: true,
				<?else:?>
				allow_resize	: false,
				<?endif;?>
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
	<li><a class="yenisite-zoom" rel="prettyPhoto[gallery_1]" href="<?=CFile::GetPath($pathbb)?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>"><img src="<?=CFile::GetPath($path)?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" /></a></li>
<?
		endforeach;
	endif;		
?>
</ul>
