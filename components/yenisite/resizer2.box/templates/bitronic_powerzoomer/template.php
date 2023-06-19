<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (!isset($arParams['SHOW_DELAY_DETAIL'])) $arParams['SHOW_DELAY_DETAIL'] = '300';
if (!isset($arParams['HIDE_DELAY_DETAIL'])) $arParams['HIDE_DELAY_DETAIL'] = '600';
if (empty($arParams['MAGNIFIER_SIZE'])) $arParams['MAGNIFIER_SIZE'] = '[75, 75]';
if (empty($arParams['POWER_RANGE'])) $arParams['POWER_RANGE'] = '[2, 7]';

$bDesc = ($arParams['SHOW_DESCRIPTION'] != 'N');
?>

<div class='yenisite-photos'>

<?
if(empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = 2;
if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 7;
if(empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 6;
CModule::IncludeModule('yenisite.resizer2');
if($arResult['PATH'][0]):
	$path = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_DETAIL']);
	$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_BIG_DETAIL']);
	
?>

<script type="text/javascript">

$(document).ready(function(){
    $('img.yenisite-detail').addpowerzoom({
        largeimage: '<?=CFile::GetPath($pathb)?>',
        powerrange: <?=$arParams['POWER_RANGE']?>,
        magnifiersize: <?=$arParams['MAGNIFIER_SIZE']?>
    });
    $(".yenisite-icons").click(function(){                    
		path = $(this).attr("rel");
		title = $(this).attr("title");
		pathb = $(this).parent().attr("rel");
		$("span.yenisite-desc").text(title);
		$(".yenisite-detail").attr("alt", title);
		$(".yenisite-detail").attr("title", title);
		$(".yenisite-buff").attr("rel", path);
		$(".yenisite-buff").attr("src", path);
		$(".yenisite-zoom").attr("href", pathb);
		$(".yenisite-zoom").attr("title", title);
	});
});

function loader()
{
    if($(".yenisite-detail").attr("src") != $(".yenisite-buff").attr("src"))
    {          
        $(".yenisite-detail").animate(
            {opacity: "hide"},
            <?=$arParams["HIDE_DELAY_DETAIL"];?>,
			function(){
                var $yZoom = $("a.yenisite-zoom");
                var title = $yZoom.attr('title');
                $yZoom.html('<img class="yenisite-detail" src="' + $('.yenisite-buff').attr('src') + '" title="' + title + '" alt="' + title + '" />');
                $('img.yenisite-detail').hide().addpowerzoom({
                    largeimage: $yZoom.attr('href'),
                    powerrange: <?=$arParams['POWER_RANGE']?>,
                    magnifiersize: <?=$arParams['MAGNIFIER_SIZE']?>
                });
                $('img.yenisite-detail').animate(
                    {opacity: "show"},
                <?=$arParams["SHOW_DELAY_DETAIL"];?>);
            }
        );
    }
}

</script>

	<div class="yenisite-bigphoto">
		<a class="yenisite-zoom" href="<?=$pathb?>" title="<?=$arResult["DESCRIPTION"][0]?>" rel="blogslideshow">
			<img class="yenisite-detail" src="<?=$path?>" title="<?=$arResult["DESCRIPTION"][0]?>" alt="<?=$arResult["DESCRIPTION"][0]?>" />
		</a>
		<img class="yenisite-buff" src="<?=$path?>" style="display: none;" onload="loader();" rel="<?=$path?>" />
		<?if ($bDesc):?><br />
			<span class="yenisite-desc"><?=$arResult["DESCRIPTION"][0]?></span>
		<?endif?>
	</div>
<?endif?>

<?if(count($arResult["PATH"]) > 1):?>
<ul id="yenisite-gallery">
    <?$i = 0;
    foreach($arResult['PATH'] as $path):
    $pathS = CResizer2Resize::ResizeGD2($path, $arParams['SET_SMALL_DETAIL']);
    $pathB = CResizer2Resize::ResizeGD2($path, $arParams['SET_BIG_DETAIL']);
    $pathD = CResizer2Resize::ResizeGD2($path, $arParams['SET_DETAIL']);
    $descr = $arResult['DESCRIPTION'][$i++];
    ?>
		<li><a href="javascript:void(0);" rel="<?=$pathB?>">
		<img class="yenisite-icons" src="<?=$pathS?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" rel="<?=$pathD?>" />
		</a></li>
	<?endforeach?>
</ul>
<?endif;?>

</div>
