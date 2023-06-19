<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?><?
global $ys_main_spec_filter;
?>
<?$APPLICATION->AddHeadString('<!--[if IE]><link rel="stylesheet"  href="'.$templateFolder.'/style_ie.css" type="text/css" media="screen, projection" /><![endif]-->');?>

<?if($_REQUEST["ys_ms_sef"] !== "y"):

global $is_bitronic;
$is_bitronic = false;
if(CModule::IncludeModule('yenisite.bitronic')) $is_bitronic = true;
elseif(CModule::IncludeModule('yenisite.bitroniclite')) $is_bitronic = true;
elseif(CModule::IncludeModule('yenisite.bitronicpro')) $is_bitronic = true;

if($is_bitronic): //for ajax in main page
	$arAjaxParamsCode = array('ACTION_VARIABLE','PRODUCT_ID_VARIABLE', 'USE_PRODUCT_QUANTITY', 'QUANTITY_FLOAT', 'PRODUCT_QUANTITY_VARIABLE', 'PRODUCT_PROPERTIES', 'PRODUCT_PROPS_VARIABLE', 'OFFERS_CART_PROPERTIES');
	if($arParams['PRODUCT_DISPLAY_MODE'] == 'SB' && !in_array('OFFER_TREE_PROPS',$arAjaxParamsCode))
	$arAjaxParamsCode[] = 'OFFER_TREE_PROPS';
	$arAjaxParams = array() ;
	$curAjaxParams = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "ajaxParams_main_page", '');
	if($curAjaxParams)
	{
		$curAjaxParams = unserialize($curAjaxParams) ;
	}
	$save_new_params = false;
	foreach ($arAjaxParamsCode as $code)
	{
		if($curAjaxParams[$code] != $arParams[$code])
			$save_new_params = true;

		$arAjaxParams[$code] = $arParams[$code];
	}
	if(count($arAjaxParams) && $save_new_params)
	{
		$serAjaxParams = serialize($arAjaxParams);
		COption::SetOptionString(CYSBitronicSettings::getModuleId(), "ajaxParams_main_page", $serAjaxParams);
	}
endif;
?>
<div id="ys-ms-slider"<?=$arParams["BLOCK_VIEW_MODE"] == "nopopup" ? ' class = "nopopup_slider"':'';?>>

		<?if($arParams["TABS_INDEX"] == 'one_slider'):?>
			<ul class="slider_cat<?if($is_bitronic):?> bitronic<?endif?>">
				<?foreach ($arResult['TABS'] as $name=>$tab):?>
					<li <?if($arParams["DEFAULT_TAB"] == $name):?>class="active"<?endif;?>>
						<span class="slider_cat_wr" id="tab_<?=$name?>">
							<i class="cl"></i>
							<i class="cr"></i>
							<a class="tab_main"><?=GetMessage("TAB_".$name);?></a>
							<a class="notloader ys-ms-sym">0</a>
							<?if($tab['COUNT'] > 0):?>
								<a <?if($is_bitronic):?>href="<?=$tab['LINK'];?>"<?endif;?> class="count <?=(!$is_bitronic)? "unactive":"ys-mainspec-rounded";?>"><?=$tab['COUNT']?></a>
							<?else:?>
								<a class="count <?if($is_bitronic):?>ys-mainspec-rounded <?endif?>unactive">0</a>
							<?endif;?>
						</span>
					</li>
				<?endforeach;?>
			</ul>
			<div class="slider"> 
				<div class="sl_wrapper">
				
		<?endif;?>	
	
<?endif;?>	
	<?
	foreach ($arResult['TABS'] as $name=>$tab):
	?>
	
	<?if($_REQUEST["ys_ms_sef"] === "y"):

	$APPLICATION->AddChainItem(GetMessage("TAB_".$name));
	$APPLICATION->SetTitle(GetMessage("TAB_".$name));
	$APPLICATION->SetPageProperty("title", GetMessage("TAB_".$name));
	
	endif;?>
		<?if($_REQUEST["ys_ms_sef"] !== "y"):?>
			<?if($arParams["TABS_INDEX"] != 'one_slider'):?>	
				<div class="blc_special slider">
					<div class="tit"><?=GetMessage("TAB_".$name);?>
						<?if($tab['COUNT'] > 0):?>
							<a <?if($is_bitronic):?>href="<?=$tab['LINK'];?>"<?endif;?> class="count <?=(!$is_bitronic)? "unactive":"ys-mainspec-rounded";?>"><?=$tab['COUNT']?></a>
						<?else:?>
							<a class="count <?if($is_bitronic):?>ys-mainspec-rounded <?endif?>unactive">0</a>
						<?endif;?>
					</div>
					<div class="sl_wrapper">
			<?else:
				if($_REQUEST["ys_ms_ajax_call"] !== "y")
					if($name != $arParams["DEFAULT_TAB"])
						continue;
			?>
			<?endif;?>
		<?endif;?>
		<?
		$ys_main_spec_filter = $tab['FILTER'];
		if(!is_array($arParams["PROPERTY_CODE"]))
			$arParams["PROPERTY_CODE"] = array();

switch($name) {
	case 'NEW':        $arParams['ELEMENT_SORT_FIELD'] = 'DATE_CREATE'; break;
	case 'HIT':        $arParams['ELEMENT_SORT_FIELD'] = 'PROPERTY_WEEK_COUNTER'; break;
	case 'SALE':       $arParams['ELEMENT_SORT_FIELD'] = 'CATALOG_QUANTITY'; break;
	case 'BESTSELLER': $arParams['ELEMENT_SORT_FIELD'] = 'PROPERTY_SALE_INT'; break;
	default: break;
}
$arParams['ELEMENT_SORT_ORDER'] = 'nulls,desc';
		?>
		<?$APPLICATION->IncludeComponent("yenisite:catalog.section.all", $_REQUEST["ys_ms_sef"] === "y" ? "block" : "main_spec", array_merge($arParams, array(
				"IS_YS_MS" => "Y",
				"TAB_BLOCK" => $name,
				"TAB_LINK" => $tab['LINK'],
				"FILTER_NAME" => "ys_main_spec_filter",
				"OFFERS_SORT_FIELD" => (!empty($arParams["LIST_PRICE_SORT"]))? $arParams["LIST_PRICE_SORT"] : $arParams["OFFERS_SORT_FIELD"],
				"OFFERS_SORT_ORDER" => (!empty($arParams["LIST_PRICE_SORT"]))? "asc" : $arParams["OFFERS_SORT_ORDER"],
				"PROPERTY_CODE" => array_merge(array(
						"FORUM_MESSAGE_CNT",
					),$arParams["PROPERTY_CODE"]),
			)),
			($this->__component->__parent ? $this->__component->__parent : $component),
			array("HIDE_ICONS" => "Y")
		);?>
	
	<?if($_REQUEST["ys_ms_sef"] !== "y"):?>
		<?if($arParams["TABS_INDEX"] != 'one_slider'):?>
				</div> <!-- /slwrapper  -->
			</div> <!-- /blc_special -->	
		<?endif;?>
		<?endif;?>
	<?endforeach;?>
<?if($_REQUEST["ys_ms_sef"] !== "y"):?>
	<?if($_REQUEST["ys_ms_ajax_call"] !== "y"):?>
		<div class="ms_tab_block" id="loader">
			<button onClick="javascript:void(0);" class="button7 ys-ms-sym">&#212;</button> 
			<button onClick="javascript:void(0);" class="button8 ys-ms-sym">&#215;</button>
			<?if($arParams['BLOCK_VIEW_MODE'] != 'nopopup'):?>
				<ul>
			<?else:?>
				<ul class="ulmitem">
			<?endif;?>
				</ul>
		</div>
	<?endif;?>	
	<?if($arParams["TABS_INDEX"] == 'one_slider'):?>
			</div> <!-- /sl_wrapper -->
			</div> <!-- .slider -->
	<?endif;?>
</div> <!--#slider-->
	<?if($_REQUEST["ys_ms_ajax_call"] !== "y"):
		if($this->__folder)
			$pathToTemplateFolder = $this->__folder ;
		else
			$pathToTemplateFolder = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', dirname(__FILE__));			
		$ajax_path = "{$pathToTemplateFolder}/ajax.php";
	?>
		<script type="text/javascript">
			ys_ms_JSInit('<?=$ajax_path;?>', '<?=SITE_ID;?>', '<?=$_SERVER["PHP_SELF"] ? $_SERVER["PHP_SELF"] : "/";?>' , <?=$arParams['CACHE_TIME']?>);
		</script>
	<?endif;?>
<?endif;?>

