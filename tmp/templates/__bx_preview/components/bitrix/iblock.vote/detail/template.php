<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>

<?
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';

if($arParams["DISPLAY_AS_RATING"] == "vote_avg")
{
	if($arResult["PROPERTIES"]["vote_count"]["VALUE"])
		$votesValue = round($arResult["PROPERTIES"]["vote_sum"]["VALUE"]/$arResult["PROPERTIES"]["vote_count"]["VALUE"], 2);
	else
		$votesValue = 0;
}
else
	$votesValue = round($arResult["PROPERTIES"]["rating"]["VALUE"]);

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

$templateData['~AJAX_PARAMS'] = $arResult['~AJAX_PARAMS'];

$bActive = !($arResult["VOTED"] || $arParams["READ_ONLY"]==="Y");
$bNotRated = ($votesCount < 1);
$onclick = "RZB2.ajax.Vote.do_vote(this, ".$arResult["AJAX_PARAMS"].", event)";
?>

	<div class="rating-stars<?=$bNotRated?' no-rate-yet':''?>" data-rating="<?=intval($votesValue)?>" data-itemid="<?=$arResult["ID"]?>"
		data-params="<?=$arResult['~AJAX_PARAMS']['SESSION_PARAMS']?>" data-disabled="<?=!$bActive ? 'true' : 'false'?>"
		data-tooltip="" data-placement="top"
		title="<?=
			GetMessage('BITRONIC2_T_IBLOCK_VOTES_COUNT'), ' ', $votesCount,
			($bNotRated && $arParams['GAMIFICATION'] ? '. ' . GetMessage('BITRONIC2_IBLOCK_VOTE_BE_FIRST') : '')
			?>">
		<meta itemprop="ratingCount" content="<?= empty($votesCount) ? 1 : $votesCount ?>">
		<meta itemprop="ratingValue" content="<?= empty(intval($votesValue)) ? 1 : intval($votesValue) ?>">
		<meta itemprop="worstRating" content="0">
		<? foreach ($arResult["VOTE_NAMES"] as $i => $name): ?>

		<i data-value="<?=$i?>" data-index="<?=$i+1?>" title="<?=$name?>" class="flaticon-black13"></i>
		<? endforeach ?>

	</div>
