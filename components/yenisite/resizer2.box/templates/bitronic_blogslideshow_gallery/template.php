<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (empty($arParams['EFFECT'])) $arParams['EFFECT'] = 'FadeInOut';
if (empty($arParams['DIRECTION'])) $arParams['DIRECTION'] = 'Horizontal';
if (empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 4;
if (empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 1;

$bDesc = ($arParams['SHOW_DESCRIPTION'] != 'N');
?>

<script type="text/javascript">

$(document).ready(function(){
    $('a[rel=blogslideshow]').bsShow({
        effect: '<?=$arParams['EFFECT']?>',
        direction: '<?=$arParams['DIRECTION']?>'
    });
});

</script>

<?if (count($arResult['PATH']) > 0):?>
<?CModule::IncludeModule('yenisite.resizer2');?>
<ul id="yenisite-gallery">
    <?$i = 0;
    foreach($arResult['PATH'] as $path):
    $pathS = CResizer2Resize::ResizeGD2($path, $arParams['SET_SMALL_DETAIL']);
    $pathB = CResizer2Resize::ResizeGD2($path, $arParams['SET_BIG_DETAIL']);
    $descr = $arResult['DESCRIPTION'][$i++];
    ?>
    <li><a href="<?=$pathB?>" <?if($bDesc):?>title="<?=$descr?>"<?endif?> rel="blogslideshow">
        <img class="yenisite-icons" src="<?=$pathS?>" alt="<?=$descr?>" title="<?=$descr?>"/>
    </a></li>
    <?endforeach?>
</ul>
<?endif?>