<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (!in_array($arParams['EFFECT'], array('slide-hori', 'slide-vert', 'resize', 'fade', 'none'))) {
    $arParams['EFFECT'] = 'slide-hori';
}
if (!isset($arParams['SPEED'])) $arParams['SPEED'] = 400;
?>

<script type="text/javascript">
    $(document).ready(
        function() {
            var galleries = $('.ad-gallery');
			galleries.adGallery( {
				effect: '<?=$arParams["EFFECT"]?>',
				animation_speed: <?=$arParams["SPEED"]?>,
				
				<?if ( $arParams["DISPLAY_NEXT_AND_PREV"] == "Y" ):?>
					display_next_and_prev: true,
				<?else:?>
					display_next_and_prev: false,
				<?endif;?>
				
				<?if ( $arParams["DISPLAY_BACK_AND_FORWARD"] == "Y" ):?>
					display_back_and_forward: true,
				<?else:?>
					display_back_and_forward: false,
				<?endif;?>
				
				thumb_opacity: <?=$arParams["THUMB_OPACITY"]?>,
				
				<?if ( $arParams["CYCLE"] == "Y" ):?>
					cycle: true,
				<?endif;?>
				
				<?if ( $arParams["SLIDE_SHOW"] == "Y" ):?>
					slideshow: {
						enable: true,
						start_label: 'Start',
						stop_label: 'Stop',
						
						<?if ( $arParams["AUTOPLAY_SLIDE_SHOW"] == "Y" ):?>
							autostart: true,
						<?endif;?>
						
						speed: <?=$arParams["SLIDE_SHOW_INTERVAL"]?>,
					},
				<?else:?>
					slideshow: {
						enable: false,		
					},
				<?endif;?>
				
			} );
        }
    );
</script>

<ul id="gallery">

</ul>


<div class="ad-gallery">
  <div class="ad-image-wrapper">
  </div>
  <div class="ad-controls">
  </div>
  <div class="ad-nav">
    <div class="ad-thumbs">
      <ul class="ad-thumb-list">
		
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
				<li>
					<a href="<?=CFile::GetPath($pathbb)?>"><img src="<?=CFile::GetPath($path)?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>" /></a>
				</li>
		<?
			endforeach;
		endif;		
		?>
	 
      </ul>
    </div>
  </div>
</div>
