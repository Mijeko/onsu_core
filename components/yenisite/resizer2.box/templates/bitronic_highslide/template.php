<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (!isset($arParams['SHOW_DELAY_DETAIL'])) $arParams['SHOW_DELAY_DETAIL'] = '300';
if (!isset($arParams['HIDE_DELAY_DETAIL'])) $arParams['HIDE_DELAY_DETAIL'] = '600';
if (!isset($arParams['OUTLINE_TYPE'])) $arParams['OUTLINE_TYPE'] = '';
if (!isset($arParams['DIMMING_OPACITY'])) $arParams['DIMMING_OPACITY'] = '0.5';
if (empty($arParams['TRANSITIONS_APPEARANCE'])) $arParams['TRANSITIONS_APPEARANCE'] = 'expand';
if (empty($arParams['TRANSITIONS_CHANGE'])) $arParams['TRANSITIONS_CHANGE'] = 'expand';
if (empty($arParams['NUMBER_POSITION'])) $arParams['NUMBER_POSITION'] = 'caption';
if (empty($arParams['WRAPPER_CLASS_NAME'])) $arParams['WRAPPER_CLASS_NAME'] = 'dark';

/*
<script type="text/javascript">
    // override Highslide settings here
    // instead of editing the highslide.js file
    //hs.graphicsDir = '/yenisite.resizer2/js/highslide/graphics/';
</script>
*/
?>

<script type="text/javascript">

/*
graphicsDir = '../example1/graphics/';     // путь к оформлению
transitions = ['expand'];                  // способ смены слайдов, может принимать значени€: expand, fade, crossfade. ј также может иметь вид hs.transitions = ['expand/fade','expand/fade/crossfade'], где первый параметр Ч стиль по€влени€ слайда, второй стиль смены слайдов
fadeInOut = true;                          // "затухание" при открыти/закрытии слайдшоу            
numberPosition = 'caption';                // счетчик может принимать значение caption и heading, что соответствует позиции вверху/внизу
dimmingOpacity = 0.75;                     // прозрачность фона
align = 'auto';                            // расположение слайда относительно экрана, возможные значени€ center/left/right/top/bottom, а так же их комбинации в парах, если auto Ч распологаетс€ на своЄм месте
 */

var galleryOptions = {
	slideshowGroup: 'gallery',
	wrapperClassName: '<?=$arParams["WRAPPER_CLASS_NAME"];?>',
	outlineType: '<?=$arParams["OUTLINE_TYPE"];?>',
	dimmingOpacity: <?=$arParams["DIMMING_OPACITY"];?>,
	align: 'center',
	transitions: ['<?=$arParams["TRANSITIONS_APPEARANCE"];?>', '<?=$arParams["TRANSITIONS_CHANGE"];?>'],
	fadeInOut: true,
	
	//captionEval: "<?=$arParams["CAPTION_EVAL"];?>"
	numberPosition: '<?=$arParams["NUMBER_POSITION"];?>'
};
	
	$(document).ready(
        function(){
			$(".yenisite-icons").click(
                function(){                    
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
                }
            );
        }
    );

    function loader()
    {
        if($(".yenisite-detail").attr("src") != $(".yenisite-buff").attr("src"))
        {          
            $(".yenisite-detail").animate(
                {opacity: "hide"},
                <?=$arParams["HIDE_DELAY_DETAIL"];?>,
                function(){
                    $(".yenisite-detail").attr("src", $(".yenisite-buff").attr("src"));
                    $(".yenisite-detail").animate(
                        {opacity: "show"},
                    <?=$arParams["SHOW_DELAY_DETAIL"];?>);
                }
            );
            
        }

    }
</script>

<div class='yenisite-photos'>
	
<?
if(empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = 2;
if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 1;
if(empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 6;
CModule::IncludeModule('yenisite.resizer2');
if($arResult['PATH'][0]):
	$path = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_DETAIL']);
	$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][0], $arParams['SET_BIG_DETAIL']);
	
?>

<div class="yenisite-bigphoto">
	<a class="yenisite-zoom" href="<?=$pathb?>" onclick="return hs.expand(this, galleryOptions)" rel="tour" title="<?=$arResult["DESCRIPTION"][0]?>"><img class="yenisite-detail" src="<?=$path?>" title="<?=$arResult["DESCRIPTION"][0]?>" alt="<?=$arResult["DESCRIPTION"][0]?>" /></a>
	<img class="yenisite-buff" src="<?=$path?>" style="display: none;" onload="loader();" rel="<?=$path?>" />
	
</div>
<?endif?>

	<ul id="yenisite-gallery">
	<?
	$i=0;
	if(count($arResult["PATH"]) >1):
		foreach($arResult["PATH"] as $value):
			$path = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_SMALL_DETAIL']);
			$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_DETAIL']);
			$pathbb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_BIG_DETAIL']);
			$i++;							
        ?>
		<li><a href="javascript:void(0);"  rel="<?=$pathbb?>"><img class="yenisite-icons" src="<?=$path?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" rel="<?=$pathb?>" /></a>
		<?endforeach?>
	<?endif?>
	</ul>
</div>

