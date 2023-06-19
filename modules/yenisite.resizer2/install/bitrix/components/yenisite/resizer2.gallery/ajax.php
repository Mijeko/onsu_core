<div style="width: 100%; text-align: center; float: left; z-index: 1000; ">
<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?49"></script>
<img src="<?=htmlspecialchars(base64_decode($_REQUEST["url"]));?>" />

	

<!-- Put this script tag to the <head> of your page -->
<?$arParams["OPEN_ID"] = htmlspecialchars($_REQUEST["photoId"])?>
<?$arParams["PAGE"] = htmlspecialchars(base64_decode($_REQUEST["page"]))?>
<?$arParams["VK_AP_ID"] = htmlspecialchars($_REQUEST["vkApId"]);?>

	<!-- Put this script tag to the <head> of your page -->
	<script type="text/javascript">
	  VK.init({apiId: <?=$arParams["VK_AP_ID"]?>, onlyWidgets: true});
	</script>


<?if($arParams["VK_AP_ID"]):?>
<script type="text/javascript">
  VK.init({apiId: <?=$arParams["VK_AP_ID"]?>, onlyWidgets: true});
</script>
<?endif?>
<!-- Put this div tag to the place, where the Comments block will be -->
<br/>
<br/>
<div style="width: 100%; text-align: center;">
	<!-- Put this div tag to the place, where the Like block will be -->
	<div style="width: 500px; margin: 0 auto;"> <div id="vk_like"></div></div><br/>
	<script type="text/javascript">
	VK.Widgets.Like("vk_like", {type: "full", pageUrl: "<?=$arParams["PAGE"]?>"}, <?=$arParams["OPEN_ID"]?>);
	</script>
	<div style="margin: 0 auto; z-index: 999;" id="vk_comments"></div>
</div>
<?if($arParams["VK_AP_ID"]):?>
<script type="text/javascript">
VK.Widgets.Comments("vk_comments", {limit: 20, width: "500", attach: "*", pageUrl: "<?=$arParams["PAGE"]?>"}, <?=$arParams["OPEN_ID"]?>);
</script>
<?endif?>
</div>