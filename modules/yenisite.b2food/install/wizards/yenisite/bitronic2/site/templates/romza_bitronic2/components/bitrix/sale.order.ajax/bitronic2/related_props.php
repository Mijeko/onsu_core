<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/props_format.php");

$class = (is_array($arResult["ORDER_PROP"]["RELATED"]) && count($arResult["ORDER_PROP"]["RELATED"])) ? "" : "hide";
?>
<div class="<?=$class?>">
    <div class="title-h3"><?=GetMessage("BITRONIC2_SOA_TEMPL_RELATED_PROPS")?></div>
	<div class="buyer-info"><?=PrintPropsForm($arResult["ORDER_PROP"]["RELATED"], $arParams["TEMPLATE_LOCATION"])?></div>
</div>