<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$arParams["OPEN_ID"] = htmlspecialchars($_REQUEST["photoId"])?>
<?if($arParams["OPEN_ID"]):?>	
<script>
$(function(){
	$(".yr2-id<?=$arParams["OPEN_ID"]?>").click();	
});
</script>
<?endif?>