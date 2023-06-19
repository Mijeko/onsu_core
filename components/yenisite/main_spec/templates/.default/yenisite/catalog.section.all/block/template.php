<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}?>

<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(''); ?>
<div class='cache_time_debug'>
<?=date('H:i:s - d.m.Y');?>
</div>
<?if(method_exists($this, 'createFrame')) $frame->end();?>
<div class='f_loader'></div>
<?
	// FOR SKU as SelectBox
	$arSkuTemplate = array();
	if (!empty($arResult['SKU_PROPS']))
	{
		foreach ($arResult['SKU_PROPS'] as &$arProp)
		{
			ob_start();?>
			<div id="#ITEM#_prop_<?=$arProp['ID'];?>_cont">
				<div class="bx_item_section_name_gray"><?=htmlspecialcharsex($arProp['NAME']); ?>:</div>
				<select 
					id="#ITEM#_prop_<?=$arProp['ID'];?>_list" 
					class="selectBox toggle-list" 
					name="<?=$arParams["PRODUCT_PROPS_VARIABLE"]?>[<?=$pid?>]" 
					<?//onchange="onSelectChange(this);"?>
				>
					<?foreach($arProp['VALUES'] as $arOneValue):?>
						<option 
							data-treevalue="<?=$arProp['ID'].'_'.$arOneValue['ID']; ?>"
							data-onevalue="<?=$arOneValue['ID']; ?>"
							value="<?=$arOneValue['ID']?>" ><?=htmlspecialcharsex($arOneValue['NAME'])?>
						</option>
					<?endforeach;?>
				</select>
			</div><?
			
			$arSkuTemplate[$arProp['CODE']] = ob_get_contents();
			ob_end_clean();
		}
		unset($arProp);
	}

?>
<?
$is_bitronic = false;
if(CModule::IncludeModule('yenisite.bitronic')) $is_bitronic = true;
elseif(CModule::IncludeModule('yenisite.bitroniclite')) $is_bitronic = true;
elseif(CModule::IncludeModule('yenisite.bitronicpro')) $is_bitronic = true;
if($is_bitronic)
	$sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode");

global $APPLICATION;/*
if(in_array($_REQUEST["page_count"], array(20, 40, 60))){
	$page_count = htmlspecialchars($_REQUEST["page_count"]);
	$APPLICATION->set_cookie("page_count", $page_count);
}
else
	$page_count = $APPLICATION->get_cookie("page_count")?$APPLICATION->get_cookie("page_count"):20;
	*/
	
	// pages
foreach ($_REQUEST as $k => $v) {
	if (strpos($k, 'PAGEN_') === 0) {
		if ($_REQUEST[$k] > 0) {
			$pagen_key = $k;

			if (strpos($_REQUEST[$k], '?') !== false) {
				$tmp = explode('?', $_REQUEST[$k]);
				$pagen = htmlspecialchars($tmp[0]);
			} else {
				$pagen = htmlspecialchars($_REQUEST[$k]);
			}

			$APPLICATION->set_cookie($k, $pagen);
		}
	}
}

$page_count = $arParams["PAGE_ELEMENT_COUNT"];
$order = $arParams["ELEMENT_SORT_FIELD"];
$by = $arParams["ELEMENT_SORT_ORDER"];

// If params is exist ( asc?clear_cache=Y )
if(strpos($by, '?') !== false)
{
	$tmp = explode('?', $by);
	$by = $tmp[0];
}

if(!$order)
{
	if($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'PRICE')
		$order = $arParams['LIST_PRICE_SORT'] ;
	elseif($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'NAME')
		$order = 'NAME' ;
	elseif($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'SALE_EXT')
	{
		$first_order = 'SORT' ;
		$order = 'PROPERTY_SALE_EXT' ;
	}
	elseif($arParams['DEFAULT_ELEMENT_SORT_BY'] == 'SALE_INT')
	{
		$first_order = 'SORT' ;
		$order = 'PROPERTY_SALE_INT' ;
	}
	else
	{
		$first_order = 'SORT' ;
		$order = 'PROPERTY_WEEK_COUNTER' ;
	}
}
$order = $order == 'SHOW_COUNTER' ? 'PROPERTY_WEEK_COUNTER' : $order ; // it's for compatibility with < 1.3.8 version. Kill this str 03.2013
$by = $by ? $by : $arParams['DEFAULT_ELEMENT_SORT_ORDER'];
/*
if(!in_array($order, array($arParams['LIST_PRICE_SORT'], 'NAME', 'PROPERTY_SALE_EXT', 'PROPERTY_SALE_INT', 'SORT', 'PROPERTY_WEEK_COUNTER', 'SHOW_COUNTER')) ||
	!in_array($by, array('asc', 'desc', 'ASC', 'DESC')))
{
	define("ERROR_404", 1);
}*/
if(in_array($order, array($arParams['LIST_PRICE_SORT'], 'NAME', 'PROPERTY_SALE_EXT', 'PROPERTY_SALE_INT', 'SORT', 'PROPERTY_WEEK_COUNTER', 'SHOW_COUNTER')) &&
	in_array($by, array('asc', 'desc', 'ASC', 'DESC')))
{
	$APPLICATION->set_cookie("order", $order);
	$APPLICATION->set_cookie("by", $by);
}
?>

<?if($sef == "Y"):?>
	<input type="hidden" name="ys-sef" value="Y" />
<?endif;?>

<form name="sort_form">
        <input id='order_field' type='hidden' value='<?=$order?>' name='order' />
        <input id='by_field' type='hidden' value='<?=$by?>' name='by' />
        <div class="filter">
	        <div class="f_label"><?=GetMessage('SORT');?>:</div>
				<div class="f_price">
					<span class="lab <?=($order==$arParams['LIST_PRICE_SORT'])?"active":"";?>"><?=GetMessage('PO_PRICE');?></span>
					<button title="<?=GetMEssage("DESCENDING"); ?>" onclick="setSortFields('<?=$arParams['LIST_PRICE_SORT'];?>', 'DESC', event); return false;" class="button11 sym <?=($order==$arParams['LIST_PRICE_SORT'] && ($by=='DESC' || $by=='desc'))?"active":"";?>">&#123;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arParams["TAB_LINK"]?>sort-<?=$arParams['LIST_PRICE_SORT']?>-desc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#123;</a>
					<?endif;?>
					<button title="<?=GetMEssage("ASCENDING"); ?>" onclick="setSortFields('<?=$arParams['LIST_PRICE_SORT'];?>', 'ASC', event); return false;" class="button11 sym <?=($order==$arParams['LIST_PRICE_SORT'] && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arParams["TAB_LINK"]?>sort-<?=$arParams['LIST_PRICE_SORT']?>-asc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#125;</a>
					<?endif;?>
				</div><!---.f_price-->
				<div class="f_name">
					<span class="lab <?=($order=='NAME')?"active":"";?>"><?=GetMessage('PO_NAME');?></span>
					<button title="<?=GetMEssage("DESCENDING"); ?>" onclick="setSortFields('NAME', 'DESC', event); return false;" class="button11 sym <?=($order=='NAME' && ($by=='DESC' || $by=='desc'))?"active":"";?>" >&#123;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arParams["TAB_LINK"]?>sort-NAME-desc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#123;</a>
					<?endif;?>
					<button title="<?=GetMEssage("ASCENDING"); ?>" onclick="setSortFields('NAME', 'ASC', event); return false;" class="button11 sym <?=($order=='NAME' && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arParams["TAB_LINK"]?>sort-NAME-asc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#125;</a>
					<?endif;?>
				</div><!---.f_name-->
				<div class="f_pop">
					<span class="lab  <?=($order=='PROPERTY_WEEK_COUNTER')?"active":"";?>"><?=GetMessage('PO_POPULAR');?></span>
					<button title="<?=GetMEssage("DESCENDING"); ?>" onclick="setSortFields('PROPERTY_WEEK_COUNTER', 'DESC', event); return false;" class="button11 sym <?=($order=='PROPERTY_WEEK_COUNTER' && ($by=='DESC' || $by=='desc'))?"active":"";?>">&#123;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arParams["TAB_LINK"]?>sort-PROPERTY_WEEK_COUNTER-desc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#123;</a>
					<?endif;?>
					<button title="<?=GetMEssage("ASCENDING"); ?>" onclick="setSortFields('PROPERTY_WEEK_COUNTER', 'ASC', event); return false;" class="button11 sym <?=($order=='PROPERTY_WEEK_COUNTER' && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arParams["TAB_LINK"]?>sort-PROPERTY_WEEK_COUNTER-asc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#125;</a>
					<?endif;?>
				</div><!---.f_pop-->
				<div class="f_sales">
					<span class="lab  <?=($order=='PROPERTY_SALE_INT')?"active":"";?>"><?=GetMessage('PO_SALE');?></span>
					<button title="<?=GetMEssage("DESCENDING"); ?>" onclick="setSortFields('PROPERTY_SALE_INT', 'DESC', event); return false;" class="button11 sym <?=($order=='PROPERTY_SALE_INT' && ($by=='DESC' || $by=='desc'))?"active":"";?>">&#123;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arParams["TAB_LINK"]?>sort-PROPERTY_SALE_INT-desc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#123;</a>
					<?endif;?>
					<button title="<?=GetMEssage("ASCENDING"); ?>" onclick="setSortFields('PROPERTY_SALE_INT', 'ASC', event); return false;" class="button11 sym <?=($order=='PROPERTY_SALE_INT' && ($by=='ASC' || $by=='asc'))?"active":"";?>">&#125;</button>
					<?if($sef == "Y"):?>
						<a href="<?=$arParams["TAB_LINK"]?>sort-PROPERTY_SALE_INT-asc/page_count-<?=$page_count?>/<?if($pagen > 1) echo "page-".$pagen."/";?>" style="display: none;">&#125;</a>
					<?endif;?>
				</div><!---.f_sales-->

	        <!--<form name="view_form">-->

	        <?if($pagen_key):?>
					<input id='PAGEN_field' type='hidden' value='<?=$pagen?>' name='<?=$pagen_key?>' />
				<?else: // for ajax?>
					<input id='PAGEN_field' type='hidden' value='' name='PAGEN_1' />
				<?endif;?>			
	        
	        <!--</form>-->

        </div><!--.filter-->
    </form>
	<div class="catalog">
		<?if($arParams['BLOCK_VIEW_MODE'] != 'nopopup'):?>
			<ul class="catalog-list">
		<?else:?>
			<ul class="catalog-list ulmitem">
		<?endif;?>
		

<?foreach($arResult["ITEMS"] as $cell=>$arElement):
	$itemid = "ys-ms-".$arElement['IBLOCK_ID']."-".$arElement['ID'];

    $this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

	$strMainID = $arElement['ID'];
	// FOR SKU as SelectBox
	$arItemIDs = array(
		'ID' => $strMainID,
		'PICT' => 'product_photo_'.$arElement['ID'],
		'SECOND_PICT' => $strMainID.'_secondpict',

		'QUANTITY' => $strMainID.'_quantity',
		'QUANTITY_DOWN' => $strMainID.'_quant_down',
		'QUANTITY_UP' => $strMainID.'_quant_up',
		'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
		'BUY_LINK' => $itemid,
		'SUBSCRIBE_LINK' => $strMainID.'_subscribe',

		'PRICE' => 'product_price_'.$arElement['ID'],
		'DSC_PERC' => $strMainID.'_dsc_perc',
		'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',

		'PROP_DIV' => $strMainID.'_sku_tree',
		'PROP' => $strMainID.'_prop_',
		'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
		'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
	);
	?>
	
	<?$no_hide_for_order = !($arParams['HIDE_ORDER_PRICE'] =='Y' && $arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y') ;?>
	<?$pr = 0; $kr = 0; $kk = 0;?>
	<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && count($arElement["OFFERS"]) > 0):?>	
		<?foreach($arElement["OFFERS"] as $arOffer):?>

			<?
			if($arOffer["CATALOG_QUANTITY_TRACE"] == "N" || ($arOffer["CATALOG_QUANTITY_TRACE"] == "Y" && $arOffer["CATALOG_QUANTITY"] > 0) ){
				$arElement["CATALOG_QUANTITY_TRACE"] = "N";	
			}
			?>
			<?
			foreach($arOffer['PRICES'] as $k => $price)
				if($price['VALUE'] < $pr || $pr == 0 ){ $pr = $price['VALUE']; $kr = $k;  $arElement['PRICES'][$kr] = $arOffer['PRICES'][$kr]; }				
					$price = $arOffer['PRICES'][$kr]['VALUE'];				
					$disc = 0;				
				if($arOffer['PRICES'][$kr]['DISCOUNT_VALUE'])
					$disc =  ($arOffer["PRICES"][$kr]["VALUE"] - $arOffer["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arOffer["PRICES"][$kr]["VALUE"];        
			?>  
		<?endforeach?>
	<?endif?>	
	<?$pr = 0; $kr = 0; $kk = 0;?>

	<?
		// foreach($arElement[PRICES] as $k => $price)
				// if($price[VALUE] < $pr || $pr == 0 ){ $pr = $price[VALUE]; $kr = $k; }			
			// $price = $arElement[PRICES][$kr][VALUE];
			// $disc = 0;
			// if($arElement[PRICES][$kr][DISCOUNT_VALUE])
				// $disc =  ($arElement["PRICES"][$kr]["VALUE"] - $arElement["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$kr]["VALUE"];        
		
	?>
	<?
	$pr = 0; $kr = 0;				
	foreach($arElement['PRICES'] as $k => &$price){
		if(CModule::IncludeModule("catalog"))
		{
			$price['PRINT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>',  $price['PRINT_VALUE']);
			$price['PRINT_DISCOUNT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>', $price['PRINT_DISCOUNT_VALUE']);
		}
		else
		{
			$price['PRINT_VALUE'] .= '<span class="rubl">'.GetMessage('RUB').'</span>';
			$price['PRINT_DISCOUNT_VALUE'] .= '<span class="rubl">'.GetMessage('RUB').'</span>';
		}
		if($price['VALUE'] < $pr || $pr == 0 ){ $pr = $price['VALUE']; $ppr = $price['PRINT_VALUE'];  $kr = $k; }			
	}    
	unset($price);
	$disc = 0;
	if($arElement['PRICES'][$kr]['DISCOUNT_VALUE'])
		$disc =  ($arElement["PRICES"][$kr]["VALUE"] - $arElement["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$kr]["VALUE"];  
	if($arParams['PRODUCT_DISPLAY_MODE'] == 'SB' && is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && count($arElement["OFFERS"]) > 0)
		$with_sku_class = 'class="with_sku"';
	else	
		$with_sku_class = '';
    ?>
	
	<?//$path = CFile::GetPath($arElement['PROPERTIES']['MORE_PHOTO']['VALUE'][0]);?>
	<?$path = CFile::GetPath(yenisite_GetPicSrc($arElement));?>
		<li id="<?=$this->GetEditAreaId($arElement['ID']);?>" <?=$with_sku_class?>><div id="<?=$arItemIDs['ID']?>" >
			<?if($arParams['BLOCK_VIEW_MODE'] != 'nopopup'):?>
				<div class="item-popup">
					<div class="pop_pic">
						<a class="sl_link" href="<?=$arElement['DETAIL_PAGE_URL']?>"></a>
						<a href="<?=$arElement['DETAIL_PAGE_URL']?>" class="sl_img">
							<?$APPLICATION->IncludeComponent("yenisite:stickers", ".default", array(
								"ELEMENT" => $arElement,
								"IMAGE_SET" => $arParams['IMAGE_SET_BIG'],
								"STICKER_NEW" => $arParams['STICKER_NEW'],
								"STICKER_HIT" => $arParams['STICKER_HIT'],
								"STICKER_BESTSELLER" => $arParams['STICKER_BESTSELLER'],
								'MAIN_SP_ON_AUTO_NEW' => $arParams['MAIN_SP_ON_AUTO_NEW'],
								'TAB_PROPERTY_NEW' => $arParams['TAB_PROPERTY_NEW'],
								'TAB_PROPERTY_HIT' => $arParams['TAB_PROPERTY_HIT'],
								'TAB_PROPERTY_SALE' => $arParams['TAB_PROPERTY_SALE'],
								'TAB_PROPERTY_BESTSELLER' => $arParams['TAB_PROPERTY_BESTSELLER'],
								"WIDTH" => 75,
								),
								$component
							);?>
							<img id="<?=$itemid?>-photo" class="product_photo" src='<?=CResizer2Resize::ResizeGD2($path,$arParams['IMAGE_SET_BIG']);?>' alt='<?=$arElement['NAME']?>' />
						</a>
					</div>	
					
					<div class="sl_info">
						<!--<a href="#" class="tag"></a>-->
						<?if($arParams["SHOW_ELEMENT"]== "Y"):?>
							<?$res = CIBlock::GetMessages($arElement["IBLOCK_ID"])?>
						<?endif?>
						<h3><a href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$res["ELEMENT_NAME"];?> <?=$arElement['NAME']?></a></h3>
						<?if(strlen($arElement["PROPERTIES"]['ARTICLE']['VALUE'])>0):?>
							<div class="article"><?=GetMessage('ARTICLE_CODE');?><?=$arElement["PROPERTIES"]['ARTICLE']['VALUE'];?></div>
						<?endif;?>
					
						<?if($no_hide_for_order):?>
							<span class="price">
								<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
									<?=$arElement['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']?$arElement['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']:$ppr;?>
									<?if($arElement['PRICES'][$kr]['DISCOUNT_VALUE'] && $arElement['PRICES'][$kr]['DISCOUNT_VALUE'] != $arElement['PRICES'][$kr]['VALUE']):?>
										<span class="oldprice"><?=$ppr;?></span>
									<?endif?>	
								<?if(method_exists($this, 'createFrame')) $frame->end();?>
							</span>
						<?endif;?>
						
						<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(''); ?>
							<div class="stars">
								<?$APPLICATION->IncludeComponent("bitrix:iblock.vote", "ajax", array(
									"IBLOCK_TYPE" => $arElement["IBLOCK_TYPE"],
									"IBLOCK_ID" => $arElement["IBLOCK_ID"],
									"ELEMENT_ID" => $arElement['ID'],
									"CACHE_TYPE" => "A",
									"CACHE_TIME" => $arParams["CACHE_TIME"],
									"MAX_VOTE" => $arParams["IBLOCK_MAX_VOTE"],
									"VOTE_NAMES" => $arParams["IBLOCK_VOTE_NAMES"],
									"SET_STATUS_404" => "N",
									"DISPLAY_AS_RATING" => $arParams["DISPLAY_AS_RATING"],
									),
									$component
								);?>
								<span class="reply">
									<span class="ws">&#0115;</span> <?=GetMessage('CATALOG_REVIEW')?> 
									<?=$arElement['PROPERTIES']['FORUM_MESSAGE_CNT']['VALUE'];?>
								</span><!--.reply-->
							</div><!--.stars-->
						<?if(method_exists($this, 'createFrame')) $frame->end();?>
							
						<?// FOR SKU as SelectBox
						if($arParams['PRODUCT_DISPLAY_MODE'] == 'SB'):?>
							<div class="props bx_catalog_item_scu" id="<? echo $arItemIDs['PROP_DIV']; ?>">
								<?foreach ($arSkuTemplate as $code => $strTemplate)
								{
									if (!isset($arElement['OFFERS_PROP'][$code]))
										continue;
									echo str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $strTemplate);
								}?>
							</div>
						<?endif?>
							
						<?if(count($arElement["PRODUCT_PROPERTIES"]) && $arParams['PRODUCT_DISPLAY_MODE'] != 'SB'):?>
							<div class="props">
								<?$prop_flag = false;?>
								<?foreach($arElement["PRODUCT_PROPERTIES"] as $pid => $product_property):?>
									<?if (!empty($product_property["VALUES"])):?>
										<?$prop_flag = true;?>
										<div>
											<?echo $arElement["PROPERTIES"][$pid]["NAME"]?>:
										</div>
										<?if(
											$arElement["PROPERTIES"][$pid]["PROPERTY_TYPE"] == "L"
											&& $arElement["PROPERTIES"][$pid]["LIST_TYPE"] == "C"
										):?>
											<?foreach($product_property["VALUES"] as $k => $v):?>
												<label><input type="radio" name="<?echo $arParams["PRODUCT_PROPS_VARIABLE"]?>[<?echo $pid?>]" value="<?echo $k?>" <?if($k == $product_property["SELECTED"]) echo '"checked"'?>><?echo $v?></label><br>
											<?endforeach;?>
										<?else:?>
											<div class="ye-select">
												<select class="selectBox toggle-list" name="<?echo $arParams["PRODUCT_PROPS_VARIABLE"]?>[<?echo $pid?>]" onchange="ys_ms_onSelectChange(this);">
													<option value="0"><?=GetMessage('CHOOSE')?></option>
													<?foreach($product_property["VALUES"] as $k => $v):?>
														<option value="<?echo $k?>" <?if($k == $product_property["SELECTED"]) echo '"selected"'?>><?echo $v?></option>
													<?endforeach;?>
												</select>
											</div>
										<?endif;?>
									<?endif;?>
								<?endforeach;?>
							</div>
						<?endif?>
						
						<?if($arElement['CATALOG_AVAILABLE'] && $no_hide_for_order):?>	
							<?if(!($ppr <= 0)):?>
								<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && ($arParams['PRODUCT_DISPLAY_MODE'] != 'SB' || count($arElement['OFFERS_PROP']) <= 0 )):?>
									<a class="button2" href="<?echo $arElement["DETAIL_PAGE_URL"]?>"><span><?=GetMessage('CATALOG_ADD')?></span></a>
								<?else:?>
									<?if ($arParams['HIDE_BUY_IF_PROPS'] != 'Y'):?>
										<?$prop_flag = (count($arElement['OFFERS_PROP']) > 0 ) ? true : false; // FOR SKU as SelectBox?>
										<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(''); ?>
											<a id="<?=$itemid;?>" class="button2 add2basket ajax_add2basket_main ajax_a2b_<?=$arElement['ID'];?> <?if($ppr <= 0 || $prop_flag):?>button_in_basket<?endif;?> <?if($prop_flag):?>ajax_add2basket_prop<?endif;?>" href="<?echo $arElement["ADD_URL"]?>" rel="nofollow" onclick="ys_ms_ajax_add2basket(this);return false;"><span><?=GetMessage('CATALOG_ADD')?></span></a>
										<?if(method_exists($this, 'createFrame')) $frame->end();?>
									<?endif;?>
								<?endif?>
							<?endif?>
						<?endif?>
						
						<!--<a href="#" class="added">  </a>-->
						<div class="compare_list">
							<?/*
							<a href="<?echo $arElement["COMPARE_URL"]?>"><span class="ws">&#193;</span><span><?echo GetMessage("CATALOG_COMPARE")?></span></a>
							*/?>
						</div>
						
						<?if($arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
							<span class="have for_order"><?echo GetMessage("FOR_ORDER")?></span> <?// FOR_ORDER - it's message in /bitrix/templates/#template_name#/lang/header.php?>
						<?elseif(!$arElement['CATALOG_AVAILABLE']):?>
							<span class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
						<?else:?>
							<span class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
						<?endif?>
					</div><!--.sl_info-->								
				</div><!--.item-popup-->
				
				<a href="<?=$arElement['DETAIL_PAGE_URL']?>" class="sl_img">
					<?$APPLICATION->IncludeComponent("yenisite:stickers", ".default", array(
						"ELEMENT" => $arElement,
						"IMAGE_SET" => $arParams['IMAGE_SET'],
						"STICKER_NEW" => $arParams['STICKER_NEW'],
						"STICKER_HIT" => $arParams['STICKER_HIT'],
						"STICKER_BESTSELLER" => $arParams['STICKER_BESTSELLER'],
						'MAIN_SP_ON_AUTO_NEW' => $arParams['MAIN_SP_ON_AUTO_NEW'],
						'TAB_PROPERTY_NEW' => $arParams['TAB_PROPERTY_NEW'],
						'TAB_PROPERTY_HIT' => $arParams['TAB_PROPERTY_HIT'],
						'TAB_PROPERTY_SALE' => $arParams['TAB_PROPERTY_SALE'],
						'TAB_PROPERTY_BESTSELLER' => $arParams['TAB_PROPERTY_BESTSELLER'],
						"WIDTH" => 75,
						),
						$component
					);?>
					
					<img class="product_photo" src="<?=CResizer2Resize::ResizeGD2($path,$arParams['IMAGE_SET']);?>" alt="<?=$arElement['NAME']?>" />
				</a>
			<?else:?>
				<a class="sl_link" href="<?=$arElement['DETAIL_PAGE_URL']?>"></a>
				<a href="<?=$arElement[DETAIL_PAGE_URL]?>" class="sl_img" id="<?=$itemid?>-photo">
					<?$APPLICATION->IncludeComponent("yenisite:stickers", ".default", array(
						"ELEMENT" => $arElement,
						"IMAGE_SET" => $arParams['IMAGE_SET'],
						"STICKER_NEW" => $arParams['STICKER_NEW'],
						"STICKER_HIT" => $arParams['STICKER_HIT'],
						"STICKER_BESTSELLER" => $arParams['STICKER_BESTSELLER'],
						'MAIN_SP_ON_AUTO_NEW' => $arParams['MAIN_SP_ON_AUTO_NEW'],
						'TAB_PROPERTY_NEW' => $arParams['TAB_PROPERTY_NEW'],
						'TAB_PROPERTY_HIT' => $arParams['TAB_PROPERTY_HIT'],
						'TAB_PROPERTY_SALE' => $arParams['TAB_PROPERTY_SALE'],
						'TAB_PROPERTY_BESTSELLER' => $arParams['TAB_PROPERTY_BESTSELLER'],
						"WIDTH" => 75,
						),
						$component
					);?>
					<img class="product_photo" src='<?=CResizer2Resize::ResizeGD2($path,$arParams['IMAGE_SET']);?>' alt='<?=$arElement['NAME']?>' />	
				</a>
				
				<?if($arElement['CATALOG_AVAILABLE'] && $no_hide_for_order):?>
					<?if(!($ppr <= 0)):?>
						<div class="product_popup">
							<div class="btn_shop">
								<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(''); ?>
									<?if(is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && ($arParams['PRODUCT_DISPLAY_MODE'] != 'SB' || count($arElement['OFFERS_PROP']) <= 0 )):?>
										<a class="button1" href="<?echo $arElement["DETAIL_PAGE_URL"]?>"><span><?=GetMessage('CATALOG_ADD')?></span></a>
									<?else:?>
										<a id="<?=$itemid;?>" class="button1 add2basket ajax_add2basket_main ajax_a2b_<?=$arElement['ID'];?>" href="<?echo $arElement["ADD_URL"]?>" <?/* onclick="onClick2Cart();" */?> rel="nofollow" onclick="ys_ms_ajax_add2basket(this);return false;"><span><?=GetMessage('CATALOG_ADD')?></span></a>
									<?endif;?>
								<?if(method_exists($this, 'createFrame')) $frame->end();?>
							</div>
							
							<div class="compare_list">
								<?/*
								<a href="<?echo $arElement["COMPARE_URL"]?>"><span class="ws">&#193;</span><span><?echo GetMessage("CATALOG_COMPARE")?></span></a>
								*/?>
							</div>
						</div>
					<?endif;?>
				<?endif;?>
			<?endif;?>
			
			<div class="sl_info">
				<!--<a href="#" class="tag"></a>-->
				<?if($arParams["SHOW_ELEMENT"]== "Y"):?>
					<?$res = CIBlock::GetMessages($arElement["IBLOCK_ID"])?>
				<?endif?>
				
				<h3><a href="<?=$arElement['DETAIL_PAGE_URL']?>"><?=$res["ELEMENT_NAME"];?> <?=$arElement['NAME']?></a></h3>
				<?if(strlen($arElement["PROPERTIES"]['ARTICLE']['VALUE'])>0):?>
					<div class="article article_block"><?=GetMessage('ARTICLE_CODE');?><?=$arElement["PROPERTIES"]['ARTICLE']['VALUE'];?></div>
				<?endif;?>
				<?if($USER->IsAdmin()):?>
					<div class="debug"><?=GetMessage('FOR_ADMIN_ONLY');?><br/> <?=GetMessage('SELLERS');?> <?=$arElement["PROPERTIES"]["SALE_INT"]["VALUE"] ? IntVal($arElement["PROPERTIES"]["SALE_INT"]["VALUE"]) : 0;?> <?=GetMessage('WEEK_CNT');?> <?=$arElement["PROPERTIES"]["WEEK_COUNTER"]["VALUE"] ? IntVal($arElement["PROPERTIES"]["WEEK_COUNTER"]["VALUE"]) : 0;?>; <?=GetMessage('NEW_DAYS');?> <?=Round((time()-yenisite_date_to_time($arElement['DATE_CREATE']))/86400);?></div>
				<?endif;?>
				
				<?// FOR SKU as SelectBox
				if($arParams['PRODUCT_DISPLAY_MODE'] == 'SB' && $arParams['BLOCK_VIEW_MODE'] == 'nopopup'):?>
					<div class="props bx_catalog_item_scu" id="<? echo $arItemIDs['PROP_DIV']; ?>">
						<?foreach ($arSkuTemplate as $code => $strTemplate)
						{
							if (!isset($arElement['OFFERS_PROP'][$code]))
								continue;
							echo str_replace('#ITEM#_prop_', $arItemIDs['PROP'], $strTemplate);
						}?>
					</div>
				<?endif?>
				
				<?if($no_hide_for_order):?>
					<span class="price">
						<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
							<?=$arElement['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']?$arElement['PRICES'][$kr]['PRINT_DISCOUNT_VALUE']:$ppr;?>
							<?if($arElement['PRICES'][$kr]['DISCOUNT_VALUE'] && $arElement['PRICES'][$kr]['DISCOUNT_VALUE'] != $arElement['PRICES'][$kr]['VALUE']):?>
								<span class="oldprice"><?=$ppr;?></span>
							<?endif?>
						<?if(method_exists($this, 'createFrame')) $frame->end();?>
					</span>
				<?endif;?>
				
				<?if($arParams['BLOCK_VIEW_MODE'] == 'nopopup'):?>
					<?if($arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'):?>
						<span class="have for_order"><?echo GetMessage("FOR_ORDER")?></span> <?// FOR_ORDER - it's message in /bitrix/templates/#template_name#/lang/header.php?>
					<?elseif(!$arElement['CATALOG_AVAILABLE']):?>
						<span class="have not_available"><?echo GetMessage("CATALOG_NOT_AVAILABLE")?></span>
					<?else:?>
						<span class="have"><?echo GetMessage("CATALOG_AVAILABLE")?></span>
					<?endif?>	
				<?endif;?>
			</div><!--.sl_info-->
			
			<?if(count($arResult['SKU_PROPS']) && count($arElement['JS_OFFERS']) && count($arElement['OFFERS_SELECTED'])):
			
				$strObName = 'ob'.$arParams['TAB_BLOCK'].preg_replace("/[^a-zA-Z0-9_]/", "x", $arElement['ID']);

				$arSkuProps = array();
				
				foreach ($arResult['SKU_PROPS'] as $arOneProp)
				{
					if (!isset($arElement['OFFERS_PROP'][$arOneProp['CODE']]))
						continue;
					$arSkuProps[] = array(
						'ID' => $arOneProp['ID'],
						'SHOW_MODE' => $arOneProp['SHOW_MODE'],
						'VALUES_COUNT' => $arOneProp['VALUES_COUNT']
					);
				}
				
				$defPics = array('PICTURE' => '', 'PICTURE_SMALL' => '');
				if(CModule::IncludeModule('yenisite.resizer2'))
				{
					$defPics['PICTURE'] = CResizer2Resize::ResizeGD2($path,$arParams['IMAGE_SET_BIG']);
					$defPics['PICTURE_SMALL'] = CResizer2Resize::ResizeGD2($path,$arParams['IMAGE_SET']);
				}
				else
				{
					$defPics['PICTURE'] = CFile::ResizeImageGet(CFile::GetFileArray($img_id), Array("width" => 200, "height" => 200));
					$defPics['PICTURE_SMALL'] = CFile::ResizeImageGet(CFile::GetFileArray($img_id), Array("width" => 150, "height" => 150));
					
					$defPics['PICTURE'] = $defPics['PICTURE']['src'];
					$defPics['PICTURE_SMALL'] = $defPics['PICTURE_SMALL']['src'];
				}
				
				$arJSParams = array(
					'PRODUCT_TYPE' => $arElement['CATALOG_TYPE'],
					'SHOW_ABSENT' => true,
					'SHOW_OLD_PRICE' => ($arElement['PRICES'][$kr]['DISCOUNT_VALUE'] && $arElement['PRICES'][$kr]['DISCOUNT_VALUE'] != $arElement['PRICES'][$kr]['VALUE']),
					'DEFAULT_PICTURE' => array(
						'PICTURE' =>  $defPics['PICTURE'],
						'PICTURE_SMALL' => $defPics['PICTURE_SMALL']
					),
				
					'VISUAL' => array(
						'ID' => $arItemIDs['ID'],
						'TREE_ID' => $arItemIDs['PROP_DIV'],
						'TREE_ITEM_ID' => $arItemIDs['PROP'],
						'BUY_ID' => $arItemIDs['BUY_LINK'],
						'PARENT_SLIDER' => 'block_'.$arParams['TAB_BLOCK'],
					),
					
					'PRODUCT' => array(
						'ID' => $arElement['ID'],
						'NAME' => $arElement['~NAME']
					),

					'OFFERS' => $arElement['JS_OFFERS'],
					'OFFER_SELECTED' => $arElement['OFFERS_SELECTED'],
					'TREE_PROPS' => $arSkuProps,
				);?>
				
				<script language="javascript" type="text/javascript">
					var <? echo $strObName; ?> = new JCCatalogSectionAll(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
				</script>
			<?endif;?>		
		</div>
		</li>
		<? $ppr = ""; ?>						
						
	<?endforeach;?>						
</ul>

<div style="clear:both;"></div>
	
</div><!--.catalog-->

<div class="pager-block">
	<div class="show_filter">
		<?if($arParams["FILTER_BY_QUANTITY"] == 'Y'):?>
			<div class="ys_no_available_link"><a href="?set_filter=Y" title="<?=GetMessage('ALL_PRODUCTS_LINK_TITLE');?>"><?=GetMessage('ALL_PRODUCTS_LINK');?></a></div>
		<?endif;?>
		<form name='pagecount' method="post">
			<?echo GetMessage("PAGE_COUNT")?>:
			<select class="selectBox" name='page_count' onchange="document.forms['pagecount'].submit();">
				<option value='20' <?=$arParams['PAGE_ELEMENT_COUNT'] == 20?"selected='selected'":""; ?>>20</option>
				<option value='40' <?=$arParams['PAGE_ELEMENT_COUNT'] == 40?"selected='selected'":""; ?>>40</option>
				<option value='60' <?=$arParams['PAGE_ELEMENT_COUNT'] == 60?"selected='selected'":""; ?>>60</option>
			</select>
		</form>
	</div><!--.show_filter-->
		
	<?=$arResult["NAV_STRING"]?>
</div><!--.pager-block-->