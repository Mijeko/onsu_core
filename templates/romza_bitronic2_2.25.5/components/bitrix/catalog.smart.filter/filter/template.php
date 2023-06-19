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
//echo '<pre>', var_export($arParams,1), '</pre>';

if(strtolower($_POST['rz_ajax']) == 'y') return;
global $rz_b2_options;
$bLineFilter = $arParams['FILTER_PLACE'] == 'line';

include 'functions.php';
$arBoolean = array(
	'bEmptyFilter' => true,
	'bActiveFilters' => false,
	'bShowFullFilters' => false,
	'bManualType' => $rz_b2_options['filter_type'] == 'manual',
);
?>
<form class="form_filter visible <?=($arParams['HIDE']) ? 'closed' : ''?>" name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?= htmlspecialcharsbx($arResult["FORM_ACTION"])?>" method="get" id="form_filter">
	<?foreach($arResult["HIDDEN"] as $arItem):?>
		<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
	<?endforeach;?>
	
	<div class="toggle-filter">
		<span class="text"><?=GetMessage('BITRONIC2_BCSF_FILTER_TITLE')?></span>
	</div>
	<div class="filter-content" <?=($arParams['HIDE']) ? 'style="display:none"' : ''?>>
        <? if (!empty($arResult['VALUES_CHECKED'])): ?>
            <?include 'choosen_values.php'?>
        <?endif?>
		<div class="title-h2"><?=GetMessage('BITRONIC2_BCSF_FILTER_TITLE_SUB')?></div><?
		$frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader());
		?>

		<div class="filter-short">
			<?
			if ($arParams['SHOW_NAME_FIELD'] !== 'N'):?>

			<div data-name-prop="<?=GetMessage("BITRONIC2_FILTER_NAME_FIELD")?>" class="filter-section allow-multiple-expanded expanded">
				<header>
					<span class="text"><?=GetMessage("BITRONIC2_FILTER_NAME_FIELD")?></span>
					<sup class="help" title="<?=GetMessage("BITRONIC2_FILTER_NAME_FIELD_HINT")?>" data-tooltip>?</sup>
					<button type="button" class="btn-expand"></button>
                    <button type="button" class="btn-clear-section"></button>
				</header>
				<div class="expand-content">
					<input type="text" class="textinput" onkeyup="smartFilter.keyup(this)"
						name="<?=$arParams['FILTER_NAME']?>_FIELD_NAME"
						id="<?=$arParams['FILTER_NAME']?>_FIELD_NAME" />
				</div>
			</div><?

			endif;

			$visibleCount = 0;
            $bOpenFilter = false;
			foreach($arResult["ITEMS"] as $key=>$arItem)
			{
				ob_start();
				$bShown = showFilterItem($arItem, $arBoolean, $arParams, $this);
				$filterItem = ob_get_clean();

                if ($visibleCount >= $arParams['VISIBLE_PROPS_COUNT'] && !$bOpenFilter) {
                    $bOpenFilter = strpos($filterItem, 'data-open-filter') !== false;
                }
				if($bShown)
				{

					if($visibleCount == $arParams['VISIBLE_PROPS_COUNT'])
					{
						$arBoolean['bShowFullFilters'] = true;
		?><?            $allItems .= $filterItem;
					} elseif (!$arBoolean['bShowFullFilters']){?>
					    <?echo $filterItem?>
                    <?} else if($arBoolean['bShowFullFilters']){
                        $allItems .= $filterItem;
                    }
					$visibleCount++;
				}else{
                    echo $filterItem;
                }
			}
		?><?if ($arBoolean['bShowFullFilters']):?>
                </div><!-- .filter-short
            --><div class="filter-full <?=$bOpenFilter ? 'filter-opened' : ''?>" <?if ($bOpenFilter):?>style="display: block"<?endif?>>
                <?echo $allItems?>
            <?endif?>
        </div><!--
		--><footer>
			<div class="filter-results" id="modef" style="display:none"><span class="text"><?=GetMessage('BITRONIC2_BCSF_FIND_GOODS')?> <strong id="modef_num">0</strong></span></div>
			<button class="btn-main show-results <?=(!$arBoolean['bActiveFilters']) ? ' disabled' : ''?><?= ($arBoolean['bManualType'])? '': ' hide'?>"
					id="set_filter" name="set_filter" value="y"><?=GetMessage("BITRONIC2_BCSF_SET_FILTER")?></button>
			<button class="action reset-filter <?=(!$arBoolean['bActiveFilters']) ? ' disabled' : ''?>" id="del_filter" name="del_filter" value="y"
				 data-sef-del="<?=!empty($arResult['JS_FILTER_PARAMS']['SEF_DEL_FILTER_URL']) && empty($_GET['rz_all_elements']) ?  $arResult['JS_FILTER_PARAMS']['SEF_DEL_FILTER_URL'] : $arResult['FORM_ACTION']?>">
				<i class="flaticon-two21"></i>
				<span class="text"><?=$bLineFilter ? GetMessage('BITRONIC2_RESET_FILTER') : GetMessage('BITRONIC2_BCSF_DEL_FILTER')?></span>
			</button>
			
			<?if(isset($_REQUEST['set_filter']) && !empty($_REQUEST['set_filter'])):?>
				<input type="hidden" name="set_filter" value="y" />
			<?endif;?>
			<?if($arBoolean['bShowFullFilters']):?>
				<button class="btn-main btn-toggle-full-filter <?if ($bOpenFilter):?>toggled<?endif?>">
					<span class="text when-minified"><?=GetMessage('BITRONIC2_BCSF_FILTER_SHOW')?></span>
					<span class="text when-expanded"><?=GetMessage('BITRONIC2_BCSF_FILTER_HIDE')?></span>
				</button>
			<?endif?>
		</footer><?
		$frame->end();
		?>

	</div><!-- .filter-content -->
	<script>
		var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', 'form_filter',
			<?=($arBoolean['bManualType']       ?'true':'false')?>,
			<?=($arParams['HIDE_DISABLED_PROPS']?'true':'false')?>);
		smartFilter.brandPropCode = "<?=$arParams['BRAND_PROP_CODE']?>";
	</script>
</form><!-- /.form_filter -->
<?if($arParams['FILTER_PLACE'] == 'side'):?>
	<div class="flying-results-wrap" id="flying-results-wrap">
		<div class="flying-results <?=(!$arBoolean['bManualType']) ? ' wo_button' : ''?>">
			<?=GetMessage('BITRONIC2_BCSF_FIND_GOODS')?> <span id="modef_flight_num" class="value"><?=intval($arResult["ELEMENT_COUNT"])?></span>
			<? if ($arBoolean['bManualType']): ?>
				<button type="button" class="btn-show-results"><?=GetMessage('BITRONIC2_BCSF_SET_FILTER')?></button>
			<? endif ?>
		</div>
	</div>
<?endif?>
