<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if(empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = 2;
if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 1;
if(empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 6;

if(empty($arParams['ANIMATION'])) $arParams['ANIMATION'] = 'random';
if(empty($arParams['ALIGNMENT'])) $arParams['ALIGNMENT'] = 'center';
if(empty($arParams['NAVIGATION'])) $arParams['NAVIGATION'] = 'dots';
if(empty($arParams['FOCUS_POSITION'])) $arParams['FOCUS_POSITION'] = 'leftTop';
if(empty($arParams['CONTROLS_POSITION'])) $arParams['CONTROLS_POSITION'] = 'rightTop';
if(!isset($arParams['SLIDE_SHOW_INTERVAL'])) $arParams['SLIDE_SHOW_INTERVAL'] = '2500';
if(!isset($arParams['VELOCITY'])) $arParams['VELOCITY'] = '1.0';
?>

<script type="text/javascript">
    $(document).ready(
        function() {
			<?$arRes = CResizer2Set::GetByID( $arParams["SET_DETAIL"] );?>
            $(".box_skitter_large").css( { width: <?=$arRes["w"]?>, height: <?=$arRes["h"]?> } ).skitter( {
				animation: "<?=$arParams["ANIMATION"]?>",
				
				<?if ( $arParams["NAVIGATION"] != "preview" ):?>
					<?=$arParams["NAVIGATION"]?> : true,
				<?else:?>
					dots: true,
					preview: true,
				<?endif;?>
				
				velocity: <?=$arParams['VELOCITY']?>,
				
				<?if ( $arParams["HIDE_TOOLS"] == "Y" ):?>
					hideTools: true,
				<?endif;?>
				
				numbers_align: "<?=$arParams["ALIGNMENT"]?>",
				
				<?if ( $arParams["AUTOPLAY_SLIDE_SHOW"] != "Y" ):?>
					auto_play: false,
				<?endif;?>
				
				<?if ( $arParams["CONTROLS"] == "Y" ):?>
					controls: true,
				<?endif;?>
				
				controls_position: "<?=$arParams["CONTROLS_POSITION"]?>",
				
				<?if ( $arParams["FOCUS"] == "Y" ):?>
					focus: true,
				<?endif;?>
				
				focus_position: '<?=$arParams["FOCUS_POSITION"]?>',
				
				interval: <?=$arParams["SLIDE_SHOW_INTERVAL"]?>,
				
				<?if ( $arParams["SHOW_RANDOMLY"] == "Y" ):?>
					show_randomly: true,
				<?endif;?>
				
				<?if ( $arParams["LABEL"] != "Y" ):?>
					label: false,
				<?endif;?>
			} );
        }
    );
</script>

<div id="content">
	<div class="border_box">
		<div class="box_skitter box_skitter_large">
			<ul>
			<?
			CModule::IncludeModule('yenisite.resizer2');
				$i=0;
				if(count($arResult["PATH"]) > 0):
					foreach($arResult["PATH"] as $value):
						$path = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_SMALL_DETAIL']);
						$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_DETAIL']);
						$pathbb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_BIG_DETAIL']);
						$i++;							
			?>
				<li>
					<a href="#" ><img src="<?=$pathb?>" /></a>
					<div class="label_text">
						<p><?=$arResult["DESCRIPTION"][$i-1]?></p>
					</div>
				</li>
			<?
					endforeach;
				endif;		
			?>

			</ul>
		</div>
	</div>
</div>
