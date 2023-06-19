<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?\Bitrix\Main\Localization\Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH . '/header.php');
$pathToRules = COption::GetOptionString(CRZBitronic2Settings::getModuleId(),'path_tu_rules_privacy',SITE_DIR.'personal/rules/personal_data.php');
global $rz_b2_options;?>

<main class="container basket-big-page" data-page="big-basket-page">
	<h1><?=GetMessage('SOA_TITLE')?>:</h1>
<NOSCRIPT>
	<div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
</NOSCRIPT>

<?
if (IsModuleInstalled('currency')):
CJSCore::Init('currency');
?>
<script type="text/javascript">
	BX.Currency.setCurrencies(<?=CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true)?>);
</script>
<?endif?>

<?if(is_array($arResult['ERROR']))
foreach($arResult['ERROR'] as $key => $err):?>
<div class='errortext'><?=($key!=='BASKET'?GetMessage('ERROR'):'').$err?></div>
<?endforeach?>

<?if(isset($arResult["ITEMS"])):?>
<?$resizer = CModule::IncludeModule('yenisite.resizer2');?>
<form method="POST" name="basket_form" id="basket_form">
<div class="make_order" id="basket_form_container">
	<input type="hidden" name="calculate_no" id="calculate"  value="Y" />
    <input type="hidden" name="privacy_policy" value="N"/>
	<input type="hidden" name="order_no" id="order" value="<?=GetMessage("ORDER");?>" />
	<a href="<?=htmlspecialcharsbx(str_replace('#ID#', 'all', $arUrls['delete']))?>"
	   class="btn-delete pseudolink with-icon clear-basket" id="basket-delete-all" data-action="delete" data-id="all">
		<i class="flaticon-trash29"></i>
		<span class="btn-text"><?=GetMessage("BITRONIC2_SALE_CLEAR")?></span>
	</a>
	<div class="clearfix"></div>
	<div id="basket-big" class="basket-big tab-target">
		<table class="items-table" id="basket_items">
		<thead>
			<tr>
				<th colspan="2"><?=GetMessage('BITRONIC2_SALE_NAME')?></th>
				<th class="availability"><?=GetMessage('BITRONIC2_SALE_QUANTITY')?></th>
				<th class="price"><?=GetMessage('BITRONIC2_SALE_PRICE')?></th>
				<th class="sum"><?=GetMessage('BITRONIC2_SALE_SUM')?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?$addStyle = (count($arResult['ITEMS']) <= 0) ? 'display:table-row' : 'display:none'?>
			<tr id="basket_no_items_info" style="<?=$addStyle?>">
				<td colspan="6" style="text-align:center">
					<div class=""><?=GetMessage("SALE_NO_ITEMS");?></div>
				</td>
			</tr>
			<?
			foreach ($arResult["ITEMS"] as $k => $arItem):

					if ($arItem["CAN_BUY"] == "Y"):
					
					// echo "<pre style='text-align:left;'>";print_r($arItem);echo "</pre>";
				?><tr id="key_<?=$arItem['KEY']?>" data-product="<?=$arItem['ID']?>" class="table-item<?if($arItem['CAN_BUY']!='Y'):?> out-of-stock<?elseif($arItem['FOR_ORDER']):?> available-for-order<?endif?>">
						<td itemscope itemtype="http://schema.org/ImageObject" class="photo">
							<?if (strlen($arItem['FIELDS']["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem['FIELDS']["DETAIL_PAGE_URL"] ?>"><?endif;?>
							<img itemprop="contentUrl" class="lazy" data-original="<?=$arItem['PRODUCT_PICTURE_SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['FIELDS']["NAME"]?>">
							<?if (strlen($arItem['FIELDS']["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
						</td>
						<td class="name">
							<a href="<?=$arItem['FIELDS']['DETAIL_PAGE_URL']?>" class="link"><span class="text"><?=$arItem['FIELDS']['NAME']?></span></a>
							<div>
								<? if (!empty($arItem['ARTICUL'])): ?>
								<span class="art">
									<?=GetMessage('YENISITE_BASKET_ARTICUL')?>:
									<strong><?=$arItem['ARTICUL']?></strong>
								</span>
								<? endif ?>
								<? foreach ($arItem["PROPERTIES"] as $val): ?>

								<span class="art">
									<?=$val["NAME"]?>:
									<strong><?=$val["VALUE"]?></strong>
								</span><?
								endforeach ?>

							</div>
						</td>
						<td class="availability ">
						<span class="quantity-counter basket_quantity_control">
							<?
							if (!isset($arItem["MEASURE_RATIO"]))
							{
								$arItem["MEASURE_RATIO"] = 1;
							}
							$ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 1;
							$max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"".$arItem["AVAILABLE_QUANTITY"]."\"" : "";
							$useFloatQuantity = ($arParams["QUANTITY_FLOAT"] == "Y") ? true : false;
							$useFloatQuantityJS = ($useFloatQuantity ? "true" : "false");
							?>

							<button
								type="button"
								id="QUANTITY_DOWN_<?=$arItem['KEY']?>"
								class="btn-silver quantity-change decrease<?=($arItem['QUANTITY']<=$ratio?' disabled':'')?>"
								data-ratio="<?=$ratio?>"
								>
								<span class="minus"></span>
							</button>
							<input
								type="text"
								size="3"
								maxlength="18"
								id="QUANTITY_INPUT_<?=$arItem["KEY"]?>"
								name="count[<?=$arItem["KEY"]?>]"
								class="quantity-input textinput"
								value="<?=$arItem["COUNT"]?>"
								onchange="updateQuantity('QUANTITY_INPUT_<?=$arItem["KEY"]?>', '<?=$arItem["KEY"]?>', <?=$ratio?>, <?=$useFloatQuantityJS?>)"
								>
							<button
								type="button"
								class="btn-silver quantity-change increase"
								data-ratio="<?=$ratio?>"
								>
								<span class="plus"></span>
							</button>
						</span>
							<?
							$availableID = false;
							$availableClass = '';
							$availableFrame = false;
							$availableForOrderText = null;
							$availableItemID = &$arItem['PRODUCT_ID'];
							//$availableMeasure = &$arItem['MEASURE_TEXT'];
							$availableQuantity = &$arItem['AVAILABLE_QUANTITY'];
							$availableStoresPostfix = 'basket_items';
							$availableSubscribe = $arItem['SUBSCRIBE'];
							$bShowEveryStatus = true;
                            $bExpandedStore = false;
							include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/availability_info.php';
							?>

							<input type="hidden" id="QUANTITY_<?=$arItem['KEY']?>" name="QUANTITY_<?=$arItem['KEY']?>" value="<?=$arItem["QUANTITY"]?>" />
						</td>
						<td class="price">
							<span class="market itemCost"><?=$arItem['MIN_PRICE']?></span>
							<span class="market itemSum"><?=$arItem['SUM_NOT_FORMATED']?></span>
							<span class="price-new"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['CURRENCY'], $arItem["MIN_PRICE"], false, array('ID'=> 'price_'.$arItem["KEY"]))?></span>
						</td>
						<td class="sum"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['CURRENCY'], $arItem["SUM_NOT_FORMATED"], false, array('ID'=> 'sum_'.$arItem["KEY"]))?></td>
						<td class="actions">
							<button class="btn-delete pseudolink with-icon" onclick="basketDelete('<?=$arItem["KEY"]?>', 'Y'); return false;" data-tooltip title="<?=GetMessage('SALE_DELETE')?>" data-placement="bottom">
								<i class="flaticon-trash29"></i>
								<span class="btn-text"><?=GetMessage('SALE_DELETE')?></span>
							</button>
						</td>
					</tr><?
					endif;
			endforeach;
			?>
		</tbody>
	</table>
</div>


<section class="form-order">
    <div class="title-h3"><?=GetMessage('SOA_TEMPL_BUYER_INFO')?></div>

	<div class="buyer-info">
	<?
	$reqFlag = false;
	foreach($arResult["DISPLAY_PROPERTIES"] as $code => $arProp):?>
		<div style="margin-bottom: 10px">
		<?if ($code == 'PAYMENT_E' || $code == 'DELIVERY_E') continue;
		if(substr_count($arProp["INPUT"], "radio") > 0){
			$arr = explode("<br/>", $arProp["INPUT"]);
			foreach($arr as $k=>&$ar){
				if(empty($ar)) continue;

				$ar = str_replace(
					array('<input', '/>'),
					array('<input class="radio-styled" id="radio'.$code.$k.'"', '/><label for="radio'.$code.$k.'">'),
					$ar) . '</label>';
			}
			
			$arProp["INPUT"] = '<div class="inline">'.implode(" ", $arr)."</div>";
		}?>
			<div><?=$arProp["NAME"]?><?if($arProp['IS_REQUIRED'] == "Y"):$reqFlag=true;?> <span class="required">*</span><?endif?>:</div>
			<?
			$arSearch = array('<input type="text"', '<textarea');
			$arReplace = array('<input type="text" class="textinput" size="30" placeholder="'.$arProp['NAME'].'"', '<textarea class="textinput"');
			?>
			<?=str_replace($arSearch, $arReplace, $arProp["INPUT"])?>
		</div>
	<?endforeach?>
	<?if($reqFlag):?>
		<span class="req_message"><span class="required">*</span> <?=GetMessage('YS_BASKET_REQUIRED');?></span>
	<?endif?>
	</div>
</section>

<?if (array_key_exists('PAYMENT_E', $arResult['DISPLAY_PROPERTIES'])):?>
<section class="form-order">
    <div class="title-h3"><?=$arResult['DISPLAY_PROPERTIES']['PAYMENT_E']['NAME']//GetMessage("SOA_TEMPL_PAY_SYSTEM")?></div>

    <div class="" style="margin-bottom: 10px"><?
	$arr = explode("<br/>", $arResult['DISPLAY_PROPERTIES']['PAYMENT_E']['INPUT']);
	foreach($arr as $k=>&$ar):?><?
		if(empty($ar)) continue;?>

		<label class="radio-styled" for="payment<?=$k?>">
		<?=str_replace(
			array('<input', '/>'),
			array('<input id="payment'.$k.'"', '/><span class="radio-content"><span class="radio-fake"></span><span class="text">'),
			$ar).'</span></span>'?>

		</label><?
	endforeach?>

	</div>
</section>
<?endif?>

<?if (array_key_exists('DELIVERY_E', $arResult['DISPLAY_PROPERTIES'])):?>
<section class="form-order ys-delivery">
    <div class="title-h3"><?=$arResult['DISPLAY_PROPERTIES']['DELIVERY_E']['NAME']//GetMessage("SOA_TEMPL_PAY_SYSTEM")?></div>

    <div class="" style="margin-bottom:10px"><?
	$arr = explode("<br/>", $arResult['DISPLAY_PROPERTIES']['DELIVERY_E']['INPUT']);
	foreach($arr as $k=>&$ar):?><?
		if(empty($ar)) continue;?>

		<label class="radio-styled" for="delivery<?=$k?>">
		<?=str_replace(
			array('<input', '/>', '"rubl"'),
			array('<input id="delivery'.$k.'"', '/><span class="radio-content"><span class="radio-fake"></span><span class="text">', '"b-rub"'),
			$ar).'</span></span>'?>

		</label><?
	endforeach?>

	</div>
</section>
<?endif?>
</div><!--.make_order-->

<footer class="footer-order row form-order basket-big">
	<div class="checkbox col-sm-6">
		<label class="checkbox-styled" title="<?=GetMessage('AGREEMENT3')?>" for="agreement">
			<input type="checkbox" id="agreement" data-title="<?=GetMessage('AGREEMENT3')?>">
			<span class="checkbox-content">
				<i class="flaticon-check14"></i>
				<?=GetMessage('AGREEMENT1')?>
				<a title="<?=GetMessage('AGREEMENT2')?>" href="<?=SITE_DIR?>about/delivery/" class="link"><span class="text"><?=GetMessage('AGREEMENT2')?></span></a>
			</span>
		</label>
        <label class="checkbox-styled">
            <input value="Y" type="checkbox" name="privacy_policy">
            <span class="checkbox-content" tabindex="5">
			<i class="flaticon-check14"></i><?=GetMessage('BITRONIC2_I_ACCEPT')?>
                <a href="<?=$pathToRules?>" class="link"><span class="text"><?=GetMessage('BITRONIC2_POLITIC_PRIVICE')?></span></a>
		</span>
        </label>
	</div>

	<div class="buy-block col-sm-6 clearfix">
		<table class="items-table">
			<tbody class="totals">
			<tr class="ys-delivery">
				<td><?=GetMessage("DELIVERY");?>:</td>
				<td class="value"><span class="price value">0</span> <span class="b-rub"><?=GetMessage('RUB');?></span></td>
			</tr>
																							
			<tr class="total-of-total">
				<td><?=GetMessage("ITOG");?>:</td>
				<td><span class="total-price2 value"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(false, $arResult["COMMON_PRICE"])?></span></td>
			</tr>
			</tbody>
		</table>
		<button onclick="return YS_Validate()" class="main-btn make-order disabled"><?=GetMessage("ORDER");?></button>
	</div>
	
</footer>
</form>
<?else:?>
	<div class="clearfix"></div>
	<p class="errortext"><?=GetMessage("YENISITE_BASKET_EMPTY")?></p>
	<footer>
		<span class="shopping-bg">
			<i class="flaticon-shopping109"></i>
		</span>
	</footer>
<?endif;?>


<?if (isset($arResult['REFRESH_BASKET_SMALL'])):?>
<script type="text/javascript">$(function(){RZB2.ajax.BasketSmall.Refresh()});</script>
<?endif?>
<script type="text/javascript">
	var initialCost = '<?=$arResult["COMMON_PRICE"]?>';
</script>

</main><!--.container-->
<? if ($rz_b2_options['hide_all_hrefs'] == 'Y'): ?>
    <style type="text/css">
        .name a.link {
            pointer-events: none !important;
        }
    </style>
<?endif?>