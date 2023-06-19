<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?php 
if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 1;
if(empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = 2;
if(empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 6;
if(empty($arParams['AUTOPLAY'])) $arParams['AUTOPLAY'] = 'false';

if(!is_numeric($arParams['HEIGHT'])) $arParams['HEIGHT'] = 470;
if(!is_numeric($arParams['WIDTH'])) $arParams['WIDTH'] = 516;

$arParams['LIGHTBOX'] = ($arParams['LIGHTBOX'] != 'N')? 'true' : 'false';
$arParams['SHOW_COUNTER'] = ($arParams['SHOW_COUNTER'] != 'N')? 'true' : 'false';

$bDesc = ($arParams['SHOW_DESCRIPTION'] != 'N');
?>

<div id="yr-galleria">
<?$i = 0;
CModule::IncludeModule('yenisite.resizer2');
foreach($arResult['PATH'] as $path):
$pathS = CResizer2Resize::ResizeGD2($path, $arParams['SET_SMALL_DETAIL']);
$pathD = CResizer2Resize::ResizeGD2($path, $arParams['SET_DETAIL']);
$pathB = CResizer2Resize::ResizeGD2($path, $arParams['SET_BIG_DETAIL']);
$descr = $arParams['DESCRIPTION'][$i++];
?>
    <a href="<?=$pathD?>">
        <img src="<?=$pathS?>" data-big="<?=$pathB?>" data-title="<?=$descr?>">
    </a>
<?endforeach?>
</div>

<script type="text/javascript">
<!--
    Galleria.loadTheme('/yenisite.resizer2/js/galleria/themes/classic/galleria.classic.min.js');
    Galleria.run('#yr-galleria', {
<?if ($arParams['WIDTH'] != -1):?>
        width: <?=$arParams['WIDTH']?>,
<?endif?>
        height: <?=$arParams['HEIGHT']?>,
        lightbox: <?=$arParams['LIGHTBOX']?>,
        showCounter: <?=$arParams['SHOW_COUNTER']?>,
        autoplay: <?=$arParams['AUTOPLAY']?>
    });
//-->
</script>