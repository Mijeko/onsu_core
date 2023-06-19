<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

if(!$arResult["NavShowAlways"])
{
    if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
        return;
}

$elementName = !empty($arResult["NavTitle"]) ? $arResult["NavTitle"] : GetMessage('BITRONIC2_PAGEN_GOODS');
?>
<span class="current-state">
<?if(intval($arResult["NavRecordCount"]) > 0):?>
	<?=$elementName?> <?=$arResult["NavFirstRecordShow"]?>-<?=$arResult["NavLastRecordShow"]?> <?=GetMessage('BITRONIC2_nav_of')?>
	<span class="current-state-total"><?=$arResult["NavRecordCount"]?></span>
<?endif;?>
</span>
<?
if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
	{	
		?><div class="pagination"></div><?
		return;		
	}
}
$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");

?>

<div class="pagination">


<?if($arResult["bDescPageNumbering"] === true):?>

	<?echo 'Desc navigation not working yet.....' // TODO?>

<?else:?>
	<?
	$bFirstPage = $arResult["NavPageNomer"] == 1;
	$bLastPage = $arResult["NavPageNomer"] == $arResult["NavPageCount"];
	?>
	
	<?if ($arResult["NavPageNomer"] > 1):?>

		<?if($arResult["bSavePage"]):?>
			<a 
				href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1" 
				class="pagination-item to-start" 
				data-pagen-key="PAGEN_<?=$arResult["NavNum"]?>"
				data-page="1"
				>
				<span class="btn-text"><?=GetMessage("BITRONIC2_nav_begin")?></span>
			</a>

            <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"
               class="pagination-item arrow prev"
               data-pagen-key="PAGEN_<?=$arResult["NavNum"]?>"
               data-page="<?=$arResult["NavPageNomer"]-1?>">
                <i class="flaticon-arrow133"></i>
                <span class="btn-text">Ctrl</span>
            </a>

		<?else:?>
			<a 
				href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" 
				class="pagination-item to-start" 
				data-pagen-key="PAGEN_<?=$arResult["NavNum"]?>"
				data-page=""
				>
				<span class="btn-text"><?=GetMessage("BITRONIC2_nav_begin")?></span>
			</a>
            <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>" class="pagination-item arrow prev"
               data-pagen-key="PAGEN_<?=$arResult["NavNum"]?>"
               data-page="<?=$arResult["NavPageNomer"]-1?>">
                <i class="flaticon-arrow133"></i>
                <span class="btn-text">Ctrl</span>
            </a>
		<?endif?>
		<?if ($arResult["nStartPage"] > 2):?>
			<a 
				href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nStartPage"] / 2)?>"
				class="pagination-item"
				data-pagen-key="PAGEN_<?=$arResult["NavNum"]?>"
				data-page="<?=round($arResult["nStartPage"] / 2)?>"
			>
				<span class="btn-text">...</span>
			</a>
		<?endif;?>
		
	<?else:?>
		<a 
			href="#"
			class="pagination-item to-start default disabled" 
			data-pagen-key="PAGEN_<?=$arResult["NavNum"]?>"
			data-page=""
			>
			<span class="btn-text"><?=GetMessage("BITRONIC2_nav_begin")?></span>
		</a>

        <a href="#" class="pagination-item arrow prev default disabled"
           data-pagen-key="PAGEN_<?=$arResult["NavNum"]?>"
           data-page="">
            <i class="flaticon-arrow133"></i>
            <span class="btn-text">Ctrl</span>
        </a>
	<?endif?>

	<?if($arResult["nStartPage"] <= $arResult["nEndPage"]):
		while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>

			<?$add_class = ($arResult["nStartPage"] == $arResult["NavPageNomer"]) ?  'active' : ''?>
				
			<?if($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
				<a 
					href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"
					class="pagination-item <?=$add_class?>"
					data-pagen-key="PAGEN_<?=$arResult["NavNum"]?>"
					data-page=""
				>
					<span class="btn-text"><?=$arResult["nStartPage"]?></span>
				</a>
			<?else:?>
				<a 
					href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"
					class="pagination-item <?=$add_class?>"
					data-pagen-key="PAGEN_<?=$arResult["NavNum"]?>"
					data-page="<?=$arResult["nStartPage"]?>"
				>
					<span class="btn-text"><?=$arResult["nStartPage"]?></span>
				</a>
			<?endif?>
			<?$arResult["nStartPage"]++?>
		<?endwhile;
	else:/*?>
		<a 
			href="#"
			class="pagination-item disabled"
			data-pagen-key="PAGEN_<?=$arResult["NavNum"]?>"
			data-page="1"
		>
			<span class="btn-text">1</span>
		</a>
	<?*/endif?>

	<?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
		<?if ($arResult["nEndPage"] < ($arResult["NavPageCount"] - 1)):?>
			<a 
				href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=round($arResult["nEndPage"] + ($arResult["NavPageCount"] - $arResult["nEndPage"]) / 2)?>"
				class="pagination-item"
				data-pagen-key="PAGEN_<?=$arResult["NavNum"]?>"
				data-page="<?=round($arResult["nEndPage"] + ($arResult["NavPageCount"] - $arResult["nEndPage"]) / 2)?>"
			>
				<span class="btn-text">...</span>
			</a>
		<?endif;?>

        <a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>" class="pagination-item arrow next"
           data-pagen-key="PAGEN_<?=$arResult["NavNum"]?>"
           data-page="<?=$arResult["NavPageNomer"]+1?>">
            <span class="btn-text">Ctrl</span>
            <i class="flaticon-right20"></i>
        </a>
				
		<a 
			href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"
			class="pagination-item to-end" 
			data-pagen-key="PAGEN_<?=$arResult["NavNum"]?>"
			data-page="<?=$arResult["NavPageCount"]?>"
		>
			<span class="btn-text"><?=GetMessage("BITRONIC2_nav_end")?></span>
		</a>
	<?else:?>

        <a href="#" class="pagination-item arrow next disabled"
           data-pagen-key="PAGEN_<?=$arResult["NavNum"]?>"
           data-page="">
            <span class="btn-text">Ctrl</span>
            <i class="flaticon-right20"></i>
        </a>

		<a 
			href="#"
			class="pagination-item to-end disabled" 
			data-pagen-key="PAGEN_<?=$arResult["NavNum"]?>"
			data-page=""
		>
			<span class="btn-text"><?=GetMessage("BITRONIC2_nav_end")?></span>
		</a>
	<?endif?>

<?endif?>

</div><!-- /pagination -->