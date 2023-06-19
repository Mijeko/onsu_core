<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if(!function_exists('showFilterItem'))
{
function showFilterItem($arItem, &$arBoolean, $arParams, $obTemplate)
{
	//echo '<pre>', var_export($arItem, 1), '</pre>';
	$bBrandBlock = ('Y' === $arParams['BRAND_HIDE'] && $arItem['CODE'] === $arParams['BRAND_PROP_CODE']);
	$bSticker = ($arItem['CODE'] == 'RZ_STICKERS');
    $bLineFilter = $arParams['FILTER_PLACE'] == 'line';
	$arItem["DISPLAY_TYPE"] = $arItem["DISPLAY_TYPE"] ? : 'F';
	if(isset($arItem["PRICE"]))
	{
		$type = "PRICE";
	}
	else
	{
		switch ($arItem["DISPLAY_TYPE"]) {
			case 'A':
			case 'B': $type = "SLIDER";   break;
			case 'P':
			case 'R': $type = "SELECT";   break;
			case 'K': $type = 'RADIO';    break;
			default:  $type = "CHECKBOX"; break;
		}
		if ($bBrandBlock) $type = 'CHECKBOX';
        if ($arItem['PROPERTY_TYPE'] == 'N' && empty($arItem["DISPLAY_TYPE"])) $type = "SLIDER";
	}

	$bExpanded = !array_key_exists('DISPLAY_EXPANDED', $arItem) || ($arItem['DISPLAY_EXPANDED'] == 'Y');
	$bShowImg = in_array($arItem['DISPLAY_TYPE'], array('G','H','R'));
	$bShowText = in_array($arItem['DISPLAY_TYPE'], array('F','H','K','P','R'));
	switch($type)
	{
		case "CHECKBOX":
			if(!empty($arItem["VALUES"]))
			{
                $bChecked = false;
                $arSelectedValues = array();
                foreach($arItem["VALUES"] as $keyVal => $arValue){
                    if ($arValue["CHECKED"]){
                        $bChecked = true;
                        $arSelectedValues[] = $arValue["VALUE"];
                    }
                }
                $strPropsValue =  '';
                if ($bChecked && $bLineFilter) {
                    $strPropsValue .= ' ' . implode(', ', $arSelectedValues);
                }

				?><div data-name-prop="<?=$arItem['NAME']?>" class="filter-section allow-multiple-expanded<?=$bExpanded ? ' expanded' : ''?><?=($bBrandBlock?' brands':'')?><?=($arItem['HIDDEN']?' hidden':'')?> <?=$bChecked ? '--checked' : ''?>">
					<header>
						<span class="text"><?=$arItem['NAME']?><?=!empty($strPropsValue) ? ':' : ''?></span>
						<?if(!empty($arItem['FILTER_HINT'])):?> 
						<sup class="help" title="<?=$arItem['FILTER_HINT']?>" data-tooltip>?</sup>
						<?endif?>
                        <?if (!empty($strPropsValue)):?>
                            <span class="text choosen-values"><?=$strPropsValue?></span>
                        <?endif?>
						<button type="button" class="btn-expand"></button>
                        <button type="button" class="btn-clear-section"></button>
					</header>
					<div class="expand-content content-with-scroll">
                         <div class="baron__scroller" data-line-height="27" data-line="<?=$arParams['FILTER_SHOW_CNT']?>">
                            <?foreach($arItem["VALUES"] as $keyVal => $arValue):
                                $bDisabled = $arValue["DISABLED"] && !$arValue["CHECKED"];
                                if(!$arBoolean['bActiveFilters'] && $arValue["CHECKED"])
                                {
                                    $arBoolean['bActiveFilters'] = true;
                                }?>

                                <label class="checkbox-styled<?=($bDisabled && $arParams['HIDE_DISABLED_PROPS'])?' hidden':''?>">
                                    <input
                                        type="checkbox"
                                        value="<?=$arValue["HTML_VALUE"]?>"
                                        name="<?echo $arValue["CONTROL_NAME"]?>"
                                        id="<?echo $arValue["CONTROL_ID"]?>"
                                        <?echo $arValue["CHECKED"]? 'checked="checked"': ''?>
                                        <?echo $arValue["CHECKED"]? 'data-open-filter="Y"' : ''?>

                                        <?echo $bDisabled ? 'disabled="disabled"': ''?>

                                        onclick="smartFilter.click(this)"
                                    />
                                    <span class="checkbox-content">
                                        <i class="flaticon-check14"></i>
                                        <?if($bShowImg && isset($arValue["FILE"]['SRC']) && !empty($arValue["FILE"]['SRC'])):?>
                                            <img
                                                <?if(!$bShowText):?>
                                                    data-tooltip title="<?=$arValue["VALUE"];?>"
                                                    data-placement="right"
                                                <?endif?>
                                                alt="<?=$arValue["VALUE"];?>"
                                                title="<?=$arValue["VALUE"];?>"
                                                height="20"
                                                src="<?=CResizer2Resize::ResizeGD2($arValue["FILE"]['SRC'], $arParams['RESIZER_FILTER'])?>"
                                            >
                                        <?endif?>
                                        <?if($bShowText):?>
                                            <?=$arValue["VALUE"];?><?
                                        endif?><?
                                        if($bSticker):?>
                                            <span class="sticker <?=$arValue['CLASS']?>"><?=$arValue['VALUE']?></span><?
                                        endif?><?
                                        if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($arValue["ELEMENT_COUNT"])):
                                            ?><sup data-role="count_<?=$arValue["CONTROL_ID"]?>">&nbsp;<?=$arValue["ELEMENT_COUNT"]?></sup><?
                                        endif;?>
                                    </span>
                                </label>
                            <?endforeach;?>
                             <div class="scroller__track scroller__track_v">
                                 <div class="scroller__bar scroller__bar_v"></div>
                             </div>
                         </div>
					</div>
				</div><?
			}
			else
			{
				return false;
			}
		break;

		case 'RADIO':
			if(!empty($arItem["VALUES"]))
			{
				$arCur = current($arItem['VALUES']);

                $bChecked = false;
                $arSelectedValues = array();
                foreach($arItem["VALUES"] as $keyVal => $arValue){
                    if ($arValue["CHECKED"]){
                        $bChecked = true;
                        $arSelectedValues[] = $arValue["VALUE"];
                    }
                }
                $strPropsValue = '';
                if ($bChecked && $bLineFilter){
                    $strPropsValue .= ' ' . implode(', ', $arSelectedValues);
                }

				?><div data-name-prop="<?=$arItem['NAME']?>" class="filter-section allow-multiple-expanded<?=$bExpanded ? ' expanded' : ''?><?=($bBrandBlock?' brands':'')?><?=($arItem['HIDDEN']?' hidden':'')?> <?=$bChecked ? '--checked' : ''?>">
					<header>
						<span class="text"><?=$arItem['NAME']?><?=!empty($strPropsValue) ? ': ' : ''?></span>
						<?if(!empty($arItem['FILTER_HINT'])):?>
						<sup class="help" title="<?=$arItem['FILTER_HINT']?>" data-tooltip>?</sup>
						<?endif?>
                        <?if (!empty($strPropsValue)):?>
                            <span class="text choosen-values"><?=$strPropsValue?></span>
                        <?endif?>
						<button type="button" class="btn-expand"></button>
                        <button type="button" class="btn-clear-section"></button>
					</header>
					<div class="expand-content content-with-scroll">
                        <div class="baron__scroller" data-line-height="27" data-line="<?=$arParams['FILTER_SHOW_CNT']?>">
                            <label class="radio-styled">
                                <input
                                    type="radio"
                                    value=""
                                    name="<?echo $arCur["CONTROL_NAME_ALT"]?>"
                                    id="<?echo "all_".$arCur["CONTROL_ID"]?>"
                                    onclick="smartFilter.click(this)"
                                />
                                <span class="radio-content">
                                    <span class="radio-fake"></span>
                                    <span class="text"><?=GetMessage("BITRONIC2_BCSF_FILTER_ALL")?></span>
                                </span>
                            </label>
                            <?foreach($arItem["VALUES"] as $keyVal => $arValue):
                                $bDisabled = $arValue["DISABLED"] && !$arValue["CHECKED"];
                                if(!$arBoolean['bActiveFilters'] && $arValue["CHECKED"])
                                {
                                    $arBoolean['bActiveFilters'] = true;
                                }?>
                                <label class="radio-styled<?=($bDisabled && $arParams['HIDE_DISABLED_PROPS'])?' hidden':''?>">
                                    <input
                                        type="radio"
                                        value="<?=$arValue["HTML_VALUE_ALT"]?>"
                                        name="<?echo $arValue["CONTROL_NAME_ALT"]?>"
                                        id="<?echo $arValue["CONTROL_ID"]?>"
                                        <?echo $arValue["CHECKED"]? 'checked="checked"': ''?>
                                        <?echo $arValue["CHECKED"]? 'data-open-filter="Y"' : ''?>

                                        <?echo $bDisabled ? 'disabled="disabled"': ''?>

                                        onclick="smartFilter.click(this)"
                                    />
                                    <span class="radio-content">
                                        <span class="radio-fake"></span>
                                        <?if($bShowImg && isset($arValue["FILE"]['SRC']) && !empty($arValue["FILE"]['SRC'])):?>
                                            <img
                                                <?if(!$bShowText):?>
                                                    data-tooltip title="<?=$arValue["VALUE"];?>"
                                                    data-placement="right"
                                                <?endif?>
                                                title="<?=$arValue["VALUE"];?>"
                                                alt="<?=$arValue["VALUE"];?>"
                                                height="20"
                                                src="<?=CResizer2Resize::ResizeGD2($arValue["FILE"]['SRC'], $arParams['RESIZER_FILTER'])?>"
                                            >
                                        <?endif?>
                                        <?if($bShowText):?>
                                            <span class="text"><?=$arValue["VALUE"];?><?
                                            if ($arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($arValue["ELEMENT_COUNT"])):
                                                ?><sup data-role="count_<?=$arValue["CONTROL_ID"]?>">&nbsp;<?=$arValue["ELEMENT_COUNT"]?></sup><?
                                            endif;?></span>
                                        <?endif?>
                                    </span>
                                </label>
                            <?endforeach;?>
                            <div class="scroller__track scroller__track_v">
                                <div class="scroller__bar scroller__bar_v"></div>
                            </div>
                        </div>
                    </div>
				</div><?
			}
			else
			{
				return false;
			}
		break;

		case 'SELECT':
			if(!empty($arItem["VALUES"]))
			{
				$arCur = current($arItem['VALUES']);

                $bChecked = false;
                $arSelectedValues = array();
                foreach($arItem["VALUES"] as $keyVal => $arValue){
                    if ($arValue["CHECKED"]){
                        $bChecked = true;
                        $arSelectedValues[] = $arValue["VALUE"];
                    }
                }
                $strPropsValue =  '';
                if ($bChecked && $bLineFilter) {
                    $strPropsValue .= ' ' . implode(', ', $arSelectedValues);
                }

				?><div data-name-prop="<?=$arItem['NAME']?>" class="filter-section allow-multiple-expanded<?=$bExpanded ? ' expanded' : ''?><?=($bBrandBlock?' brands':'')?><?=($arItem['HIDDEN']?' hidden':'')?> <?=$bChecked ? '--checked' : ''?>">
					<header>
						<span class="text"><?=$arItem['NAME']?><?=!empty($strPropsValue) ? ': ' : ''?></span>
						<?if(!empty($arItem['FILTER_HINT'])):?> 
						<sup class="help" title="<?=$arItem['FILTER_HINT']?>" data-tooltip>?</sup>
						<?endif?>
                        <?if (!empty($strPropsValue)):?>
                            <span class="text choosen-values"><?=$strPropsValue?></span>
                        <?endif?>
						<button type="button" class="btn-expand"></button>
                        <button type="button" class="btn-clear-section"></button>
					</header>
					<div class="expand-content">
						<select name="<?echo $arCur["CONTROL_NAME_ALT"]?>" id="<?echo $arCur["CONTROL_ID"]?>" onchange="smartFilter.click(this)"<?if($arParams['HIDE_DISABLED_PROPS']):?> data-customclass="sku"<?endif?>>
							<option value=""><?=GetMessage('BITRONIC2_BCSF_FILTER_ALL')?></option>
							<?foreach($arItem["VALUES"] as $keyVal => $arValue):
								if(!$arBoolean['bActiveFilters'] && $arValue["CHECKED"])
								{
									$arBoolean['bActiveFilters'] = true;
								}?>

								<option
									value="<?=$arValue["HTML_VALUE_ALT"]?>" <?
									echo $arValue["CHECKED"]? 'selected="selected"': ''?>
                                    <?echo $arValue["CHECKED"]? 'data-open-filter="Y"' : ''?><?
									echo $arValue["DISABLED"] && !$arValue["CHECKED"]? 'disabled="disabled"': ''?><?
									if($bShowImg && isset($arValue["FILE"]['SRC']) && !empty($arValue["FILE"]['SRC'])):?>

									data-img="<?=CResizer2Resize::ResizeGD2($arValue["FILE"]['SRC'], $arParams['RESIZER_FILTER'])?>"<?
									endif?>
                                    <?if($bShowText && $arParams["DISPLAY_ELEMENT_COUNT"] !== "N" && isset($arValue["ELEMENT_COUNT"]) && !empty($arValue["ELEMENT_COUNT"])):?>
                                        data-sup="<?=$arValue["ELEMENT_COUNT"]?>"<?endif;?>>
									<?if($bShowText):?>
										<?=$arValue["VALUE"];?>
									<?endif?>
								</option>
							<?endforeach;?>	
						</select>
					</div>
				</div><?
			}
			else
			{
				return false;
			}
		break;
		
		case "SLIDER":
			$bSlider = ($arItem['DISPLAY_TYPE'] !== 'B');
		// break;
		
		case "PRICE":
			if(!isset($arItem["VALUES"]["MIN"]["VALUE"])
			|| !isset($arItem["VALUES"]["MAX"]["VALUE"])
			//|| 0 >    $arItem["VALUES"]["MIN"]["VALUE"]
			|| 0 >    $arItem["VALUES"]["MAX"]["VALUE"]
			|| 0 >=   $arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"])
			{
				return false;
			}

			if (!isset($bSlider)) {
				$bSlider = true;
			}

			$sliderId = "slider_".((isset($arItem["PRICE"])) ? 'price_' : '').$arItem['ID'];
			
			$min = (empty($arItem["VALUES"]["MIN"]["HTML_VALUE"])) ? "" : round($arItem["VALUES"]["MIN"]["HTML_VALUE"]);
			$max = (empty($arItem["VALUES"]["MAX"]["HTML_VALUE"])) ? "" : round($arItem["VALUES"]["MAX"]["HTML_VALUE"]);
			$bChecked = ($arItem["VALUES"]["MIN"]["VALUE"] != $min && !empty($min) || $arItem["VALUES"]["MAX"]["VALUE"] != $max && !empty($min));

			$strPropsValue =  '';
			 if ($bChecked && $bLineFilter){
			    if ($arItem["VALUES"]["MIN"]["VALUE"] != $min && !empty($min)) {
                    $strPropsValue .= ' '.$min;
                }
                if ($arItem["VALUES"]["MAX"]["VALUE"] != $min && !empty($max)) {
                    $strPropsValue .= ', ' . $max;
                }
            }
			if ($min != '' || $max != '') $arBoolean['bActiveFilters'] = true;
			?><div data-name-prop="<?=$arItem['NAME']?>" class="filter-section allow-multiple-expanded <?=$bExpanded ? 'expanded' : ''?> <?=$bChecked ? '--checked' : ''?>">
				<header>
					<span class="text"><?=$arItem["NAME"]?><?=!empty($strPropsValue) ? ': ' : ''?></span>
					<?if(!empty($arItem['FILTER_HINT'])):?> 
						<sup class="help" title="<?=$arItem['FILTER_HINT']?>" data-tooltip>?</sup>
					<?endif?>
                    <?if (!empty($strPropsValue)):?>
                        <span class="text choosen-values"><?=$strPropsValue?></span>
                    <?endif?>
					<button type="button" class="btn-expand"></button>
                    <button type="button" class="btn-clear-section"></button>
				</header>
				<div class="expand-content"><?
					if ($bSlider):?>

					<div class="range-slider price-slider <?=$arItem['DISABLED'] ? 'disabled' : ''?>" id="<?=$sliderId?>"></div><?
					endif?>

					<div class="range-slider-inputs <?=$arItem['DISABLED'] ? 'disabled' : ''?>">
						<label class="filter-input-wrap start">
							<span class="text"><?=GetMessage('BITRONIC2_BCSF_FILTER_FROM')?></span>
							<input 
								type="text" 
								class="range-input-lower textinput"
								name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
								id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>" 
								value="<?echo $min?>"
								size="5"
								onkeyup="smartFilter.keyup(this)"
								data-range-min="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>"
                                <?=$arItem["VALUES"]["MIN"]["VALUE"] != $min && !empty($min) ? 'data-open-filter="Y"' : ''?> <?if(!$bSlider):?>placeholder="<?=$arItem["VALUES"]["MIN"]["VALUE"]?>"<?endif?>
							>
						</label>
						<label class="filter-input-wrap end">
							<span class="text"><?=GetMessage('BITRONIC2_BCSF_FILTER_TO')?></span>
							<input 
								type="text" 
								class="range-input-upper textinput"
								name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
								id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
								value="<?echo $max?>"
								size="5"
								onkeyup="smartFilter.keyup(this)"
								data-range-max="<?=$arItem["VALUES"]["MAX"]["VALUE"]?>" <?if(!$bSlider):?>placeholder="<?=$arItem["VALUES"]["MAX"]["VALUE"]?>"
                                <?=$arItem["VALUES"]["MAX"]["VALUE"] != $max && !empty($min) ? 'data-open-filter="Y"' : ''?><?endif?>
							>
							<?if(isset($arItem["PRICE"])):?>
								<?=(!empty($arItem["VALUES"]["MAX"]['CURRENCY']) 
									? $arParams['CONVERT_CURRENCY'] && !empty($arParams['CURRENCY_ID'])
										? CRZBitronic2CatalogUtils::getCurrencyTemplate($arParams['CURRENCY_ID'])
										: CRZBitronic2CatalogUtils::getCurrencyTemplate($arItem["VALUES"]["MAX"]['CURRENCY']) 
									: CRZBitronic2CatalogUtils::getCurrencyTemplate('RUB'))?>
							<?endif?>
						</label>
					</div><!-- /.slider-inputs -->
					
					<?
					if ($bSlider):
					$arSliderParams = array(
						"SLIDER_ID" => $sliderId,
						"VALUES" => array(
							'MIN'=>$arItem["VALUES"]["MIN"]["VALUE"],
							'MAX'=>$arItem["VALUES"]["MAX"]["VALUE"]
						),
						"HTML_VALUES" => array(
							'MIN'=>$arItem["VALUES"]["MIN"]["HTML_VALUE"],
							'MAX'=>$arItem["VALUES"]["MAX"]["HTML_VALUE"]
						),
						"FILTERED_VALUES" => array(
							'MIN'=>intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"],
							'MAX'=>intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"]
						),
						"INPUT_ID" => array(
							'MIN'=>$arItem["VALUES"]["MIN"]["CONTROL_ID"],
							'MAX'=>$arItem["VALUES"]["MAX"]["CONTROL_ID"]
						),
					);?>
					<script type="text/javascript">
						createSlider(<? echo CUtil::PhpToJSObject($arSliderParams, false, true); ?>);
					</script><?
					endif?>

				</div>
			</div><?
		break;
	}

	if ($bBrandBlock):
		$obTemplate->setViewTarget('catalog_brands');
		foreach ($arItem['VALUES'] as $key => $arValue):
            if (empty($arValue['FILE'])) continue;
			$class = 'brand'
			       . ($arValue['CHECKED']  ? ' active'   : '')
			       . ($arValue['DISABLED'] ? ' disabled' : '');
			$file = CFile::ResizeImageGet($arValue["FILE"], array("width"=>120, "height"=>100));

				?><div class="<?=$class?>" data-checkbox="<?=$arValue['CONTROL_ID']?>">
					<img title="<?=$arValue["VALUE"]?>" src="<?=$file['src']?>" alt="<?=$arValue["VALUE"]?>">
					<?//TODO <sup>43</sup>?>

				</div><?
		endforeach;
		$obTemplate->endViewTarget();
		Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/components/brands-catalog/brands-catalog.js");
		return false;
	endif;
	
	return true;
    }
}