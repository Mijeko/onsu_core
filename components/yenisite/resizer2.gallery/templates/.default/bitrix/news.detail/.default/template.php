<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<? CModule::IncludeModule('yenisite.resizer2'); ?>


<style>
	.fancybox-nav {width: 80px !important;}
	.fancybox-next{right: 30px;}
</style>
<?$wo = 0; $ho = 0?>

<div class='yr2-detail-div'>
<?if($arResult["DETAIL_TEXT"] || $arResult["PREVIEW_TEXT"]):?>
	<p class='yr2-detail'><i>&nbsp;<?=$arResult["DETAIL_TEXT"]?$arResult["DETAIL_TEXT"]:$arResult["PREVIEW_TEXT"];?></i></p>
<?endif?>	
	<?foreach($arResult["PROPERTIES"][$arParams["PROPERTY_PHOTO"]]["VALUE"] as $k=>$pid):
		$page = base64_encode("http://".$_SERVER["SERVER_NAME"]."/".$APPLICATION->GetCurPageParam("photoId=".$pid, array("photoId")));
		$desc = $arResult["PROPERTIES"][$arParams["PROPERTY_PHOTO"]]["DESCRIPTION"][$k]?$arResult["PROPERTIES"][$arParams["PROPERTY_PHOTO"]]["DESCRIPTION"][$k]:$arResult["NAME"];	
		$pathM = CResizer2Resize::ResizeGD2(CFile::GetPath($pid), $arParams["DETAIL_MIDDLE_SET"]);
		$pathB = CResizer2Resize::ResizeGD2(CFile::GetPath($pid), $arParams["DETAIL_BIG_SET"]);
		list($width, $height) = getimagesize($pathB); 
		if($width > $wo) $wo = $width;
		if($height > $ho) $ho = $height;
	?>
	<a rel="r2group" class="yr2-gal-item yr2-id<?=$pid?>" title="<?=$desc?>" href="/bitrix/components/yenisite/resizer2.gallery/ajax.php?vkApId=<?=$arParams["VK_AP_ID"]?>&page=<?=$page?>&url=<?=base64_encode($pathB)?>&photoId=<?=$pid?>"><img alt=<?=$desc;?> src='<?=$pathM;?>' /></a>
	<?endforeach?>
</div>
<?
$wo +=200;
$ho +=200;
?>
<script>
$(function(){
	$("a.yr2-gal-item").fancybox({type: "iframe", width: <?=$wo?>, height: <?=$ho?>, autoSize: false, nextClick: false, margin: 20});	
});
</script>
