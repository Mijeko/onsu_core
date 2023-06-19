<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = 2;
if (!isset($arParams['ROTATE_X_MIN'])) $arParams['ROTATE_X_MIN'] = '40';
if (!isset($arParams['ROTATE_X_MAX'])) $arParams['ROTATE_X_MAX'] = '90';
if (!isset($arParams['ROTATE_Y_MIN'])) $arParams['ROTATE_Y_MIN'] = '-15';
if (!isset($arParams['ROTATE_Y_MAX'])) $arParams['ROTATE_Y_MAX'] = '45';
if (!isset($arParams['ROTATE_Z_MIN'])) $arParams['ROTATE_Z_MIN'] = '-10';
if (!isset($arParams['ROTATE_Z_MAX'])) $arParams['ROTATE_Z_MAX'] = '10';
if (!isset($arParams['TRANSLATE_X_MIN'])) $arParams['TRANSLATE_X_MIN'] = '-400';
if (!isset($arParams['TRANSLATE_X_MAX'])) $arParams['TRANSLATE_X_MAX'] = '400';
if (!isset($arParams['TRANSLATE_Y_MIN'])) $arParams['TRANSLATE_Y_MIN'] = '-400';
if (!isset($arParams['TRANSLATE_Y_MAX'])) $arParams['TRANSLATE_Y_MAX'] = '400';
if (!isset($arParams['TRANSLATE_Z_MIN'])) $arParams['TRANSLATE_Z_MIN'] = '350';
if (!isset($arParams['TRANSLATE_Z_MAX'])) $arParams['TRANSLATE_Z_MAX'] = '550';
?>

<div class="container">
<section class="main">
	<div class="windy-demo">
		<ul id="wi-el" class="wi-container">
			<?
			CModule::IncludeModule('yenisite.resizer2');
				$i=0;
				if(count($arResult["PATH"]) >0):
					foreach($arResult["PATH"] as $value):
						$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_DETAIL']);
						$i++;							
			?>
				<li><img src="<?=$pathb?>"/><p><?=$arResult["DESCRIPTION"][$i-1]?></p></li>
			<?
					endforeach;
				endif;		
			?>
		</ul>
		
		<nav>
			<span id="nav-prev">prev</span>
			<span id="nav-next">next</span>
		</nav>
	</div>
</section>
</div>

<script type="text/javascript">
	
	$(document).ready(
        function() {
			<?$arRes = CResizer2Set::GetByID( $arParams["SET_DETAIL"] );?>
			$('.wi-container').css( { width: <?=$arRes["w"]?>, height: <?=$arRes["w"]?> } );
		} );
	
    $(function() { var $el = $( '#wi-el' ),
					windy = $el.windy( {
						
							boundaries : {
								rotateX : { min : <?=$arParams["ROTATE_X_MIN"]?> , max : <?=$arParams["ROTATE_X_MAX"]?> },
								rotateY : { min : <?=$arParams["ROTATE_Y_MIN"]?> , max : <?=$arParams["ROTATE_Y_MAX"]?> },
								rotateZ : { min : <?=$arParams["ROTATE_Z_MIN"]?> , max : <?=$arParams["ROTATE_X_MAX"]?> },
								translateX : { min : <?=$arParams["TRANSLATE_X_MIN"]?> , max : <?=$arParams["TRANSLATE_X_MAX"]?> },
								translateY : { min : <?=$arParams["TRANSLATE_Y_MIN"]?> , max : <?=$arParams["TRANSLATE_Y_MAX"]?> },
								translateZ : { min : <?=$arParams["TRANSLATE_Z_MIN"]?> , max : <?=$arParams["TRANSLATE_Z_MAX"]?> }
							}
					} ),
					
							allownavnext = false,
							allownavprev = false;

							$( '#nav-prev' ).on( 'mousedown', function( event ) {

								allownavprev = true;
								navprev();
							
							} ).on( 'mouseup mouseleave', function( event ) {

								allownavprev = false;
							
							} );

							$( '#nav-next' ).on( 'mousedown', function( event ) {

								allownavnext = true;
								navnext();
							
							} ).on( 'mouseup mouseleave', function( event ) {

								allownavnext = false;
							
							} );

							function navnext() {
								if( allownavnext ) {
									windy.next();
									setTimeout( function() {	
										navnext();
									}, 300 );
								}
							}
							
							function navprev() {
								if( allownavprev ) {
									windy.prev();
									setTimeout( function() {	
										navprev();
									}, 150 );
								}
							}
		} );
</script>
