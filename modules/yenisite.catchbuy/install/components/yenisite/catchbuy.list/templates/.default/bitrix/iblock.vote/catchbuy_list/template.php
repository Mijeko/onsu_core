<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>

<?
if($arParams["DISPLAY_AS_RATING"] == "vote_avg")
{
	if($arResult["PROPERTIES"]["vote_count"]["VALUE"])
		$votesValue = round($arResult["PROPERTIES"]["vote_sum"]["VALUE"]/$arResult["PROPERTIES"]["vote_count"]["VALUE"], 2);
	else
		$votesValue = 0;
}
else
	$votesValue = intval($arResult["PROPERTIES"]["rating"]["VALUE"]);

$votesCount = intval($arResult["PROPERTIES"]["vote_count"]["VALUE"]);

if(isset($arParams["AJAX_CALL"]) && $arParams["AJAX_CALL"]=="Y")
{
	$APPLICATION->RestartBuffer();

	die(json_encode( array(
			"value" => $votesValue,
			"votes" => $votesCount
		)
	));
}
$onclick = "JCCatalogItem.Vote.do_vote(this, ".$arResult["AJAX_PARAMS"].", event)";
?>
	<div class="rating-stars r<?=intval($votesValue)?>">

		<?foreach($arResult["VOTE_NAMES"] as $i=>$name):?>
			<i data-value="<?=$i?>" data-id="<?=$arResult["ID"]?>" title="<?=$name?>" class="flaticon-black13"
				<?if(!($arResult["VOTED"] || $arParams["READ_ONLY"]==="Y")):?>

					onclick="<?echo htmlspecialcharsbx($onclick);?>"
				<?endif?>
				>

			</i>
		<?endforeach?>
	</div><!-- /rating-stars -->
<? /*
TODO
<span class="comments">
	................
</span>
*/