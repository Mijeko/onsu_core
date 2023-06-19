<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (empty($arParams['MAGNIFIER_SIZE'])) $arParams['MAGNIFIER_SIZE'] = '[75, 75]';
if (empty($arParams['POWER_RANGE'])) $arParams['POWER_RANGE'] = '[2, 7]';
?>

<ul id="yenisite-gallery">
<?
//if(empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = 2;
if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 7;
if(empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 4;
CModule::IncludeModule('yenisite.resizer2');
	$i=0;
	if(count($arResult["PATH"]) >0):
		foreach($arResult["PATH"] as $value):
			$path = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_SMALL_DETAIL']);
			//$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_DETAIL']);
			$pathbb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_BIG_DETAIL']);
			$i++;							
?>
	<li><a class="yenisite-zoom" href="<?=CFile::GetPath($pathbb)?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>"><img id="yenisite-image<?=$i?>" src="<?=CFile::GetPath($path)?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" /></a>
    <script type="text/javascript">
    $(document).ready(function(){
        $('#yenisite-image<?=$i?>').addpowerzoom({
            largeimage: '<?=CFile::GetPath($pathbb)?>',
            powerrange: <?=$arParams['POWER_RANGE']?>,
            magnifiersize: <?=$arParams['MAGNIFIER_SIZE']?>
        });
    });
    </script>
    </li>
<?
		endforeach;
	endif;		
?>
</ul>

