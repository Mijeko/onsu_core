<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (empty($arParams['SKIN'])) $arParams['SKIN'] = 'default';
if (empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 6;
if (empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 1;
if (empty($arParams['LOADER'])) $arParams['LOADER'] = 'pie';
if (empty($arParams['LOADER_COLOR'])) $arParams['LOADER_COLOR'] = 'EEEEEE';
if (empty($arParams['LOADER_BG_COLOR'])) $arParams['LOADER_BG_COLOR'] = '222222';
if (empty($arParams['PIE_POSITION'])) $arParams['PIE_POSITION'] = 'rightTop';
if (empty($arParams['BAR_POSITION'])) $arParams['BAR_POSITION'] = 'bottom';
if (empty($arParams['BAR_DIRECTION'])) $arParams['BAR_DIRECTION'] = 'leftToRight';

if (!is_numeric($arParams['TIME'])) $arParams['TIME'] = '7000';
if (!is_numeric($arParams['TRANS_PERIOD'])) $arParams['TRANS_PERIOD'] = '1500';
if (!is_numeric($arParams['LOADER_OPACITY'])) $arParams['LOADER_OPACITY'] = '0.8';


$arParams['HOVER'] = ($arParams['HOVER'] == 'N')? 'false' : 'true';
$arParams['THUMB'] = ($arParams['THUMB'] == 'N')? 'false' : 'true';
$arParams['NAVIGATION'] = ($arParams['NAVIGATION'] == 'N')? 'false' : 'true';
$arParams['PLAY_PAUSE'] = ($arParams['PLAY_PAUSE'] == 'N')? 'false' : 'true';
$arParams['PAGINATION'] = ($arParams['PAGINATION'] == 'Y')? 'true' : 'false';
$arParams['PAUSE_ON_CLICK'] = ($arParams['PAUSE_ON_CLICK'] == 'N')? 'false' : 'true';
$arParams['NAVIGATION_HOVER'] = ($arParams['NAVIGATION_HOVER'] == 'N')? 'false' : 'true';

if (!is_array($arParams['EFFECT'])) $arParams['EFFECT'] = array('random');
if (!count($arParams['EFFECT'])) $arParams['EFFECT'] = array('random');

$fx = '';
foreach ($arParams['EFFECT'] as $fxi) {
    $fx .= $fxi . ', ';
}
$fx = substr($fx, 0, -2);

$bDesc = ($arParams['SHOW_DESCRIPTION'] != 'N');
?>

<script type="text/javascript">

$(document).ready(function(){
    $('#yenisite-gallery').camera({
        fx: '<?=$fx?>',
        barDirection: '<?=$arParams['BAR_DIRECTION']?>',
        barPosition: '<?=$arParams['BAR_POSITION']?>',
        hover: <?=$arParams['HOVER']?>,
        loader: '<?=$arParams['LOADER']?>',
		loaderColor: '#<?=$arParams['LOADER_COLOR']?>',
		loaderBgColor: '#<?=$arParams['LOADER_BG_COLOR']?>',
		loaderOpacity: <?=$arParams['LOADER_OPACITY']?>,
        navigation: <?=$arParams['NAVIGATION']?>,
        navigationHover: <?=$arParams['NAVIGATION_HOVER']?>,
        pagination: <?=$arParams['PAGINATION']?>,
        playPause: <?=$arParams['PLAY_PAUSE']?>,
        pauseOnClick: <?=$arParams['PAUSE_ON_CLICK']?>,
        piePosition: '<?=$arParams['PIE_POSITION']?>',
        thumbnails: <?=$arParams['THUMB']?>,
        time: <?=$arParams['TIME']?>,
        transPeriod: <?=$arParams['TRANS_PERIOD']?>
    });
});

</script>

<?if (count($arResult['PATH']) > 0):?>
<?CModule::IncludeModule('yenisite.resizer2');?>
<div id="yenisite-gallery"<?if($arParams['SKIN']!='default'):?> class="camera_<?=$arParams['SKIN']?>_skin"<?endif?>>
    <?$i = 0;
    foreach($arResult['PATH'] as $path):
    $pathS = CResizer2Resize::ResizeGD2($path, $arParams['SET_SMALL_DETAIL']);
    $pathB = CResizer2Resize::ResizeGD2($path, $arParams['SET_BIG_DETAIL']);
    $descr = $arResult['DESCRIPTION'][$i++];
    ?>
    <div data-src="<?=$pathB?>" data-thumb="<?=$pathS?>">
        <?if (!empty($descr) && $bDesc):?>
        <div class="camera_caption"><?=$descr?></div>
        <?endif?>
    </div>
    <?endforeach?>
</div>
<?endif?>