<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!CModule::IncludeModule("catalog"))
	return;

include_once $_SERVER['DOCUMENT_ROOT'].SITE_DIR."ajax/include_module.php";

if (!is_array($_REQUEST["arParams"]["ELEMENT"]))
	return;

IncludeModuleLangFile(__FILE__);
$curElementId = intval($_REQUEST["arParams"]["ELEMENT"]["ID"]);
$arCurElementInfo = $_REQUEST["arParams"]["ELEMENT"];
$arSetItemsInfo = $_REQUEST["arParams"]["SET_ITEMS"];

$arSetElementsDefault = $_REQUEST["arParams"]["SET_ITEMS"]["DEFAULT"] ? : array();
$arSetElementsOther = $_REQUEST["arParams"]["SET_ITEMS"]["OTHER"] ? : array();

$setPrice = htmlspecialcharsbx($_REQUEST["arParams"]["SET_ITEMS"]["PRICE_NOT_FORMATED"]);
$setOldPrice = htmlspecialcharsbx($_REQUEST["arParams"]["SET_ITEMS"]["OLD_PRICE"]);
$setPriceDiscountDifference = htmlspecialcharsbx($_REQUEST["arParams"]["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE"]);

$bFullPriceShow = $arSetItemsInfo['PRICE_DISCOUNT_DIFFERENCE_NOT_FORMATED'] > 0;
?><div class="title-h2"><?=GetMessage('BITRONIC2_CATALOG_SET_CONSTRUCT')?>:</div>
		<div class="custom-collection-content">
			<div class="text">
				<div class="product-text"><?=GetMessage('BITRONIC2_CATALOG_SET_GOOD')?>:</div>
				<div class="items-text"><?=GetMessage('BITRONIC2_CATALOG_SET_ADD_TO_SET')?>:</div>
			</div>
			
			<div class="product"
                <?=$arCurElementInfo['OFFERS_PROP_CODES'] ? 'data-props="'.htmlspecialcharsbx($arCurElementInfo['OFFERS_PROP_CODES']).'"' : ''?>
				data-item-id="<?=htmlspecialcharsbx($arCurElementInfo['ID'])?>"
				data-discount-price="<?=htmlspecialcharsbx($arCurElementInfo['PRICE_DISCOUNT_VALUE'])?>"
				data-price="<?=htmlspecialcharsbx($arCurElementInfo['PRICE_VALUE'])?>"
				data-discount-diff-price="<?=htmlspecialcharsbx($arCurElementInfo['PRICE_DISCOUNT_DIFFERENCE_VALUE'])?>">
				
				<div class="item-photo">
					<img class="lazy" data-original="<?=htmlspecialcharsbx($arCurElementInfo['PICTURE_PRINT']['SRC'])?>" title="<?=htmlspecialcharsbx($arCurElementInfo['NAME'])?>" alt="<?=htmlspecialcharsbx($arCurElementInfo['NAME'])?>">
				</div>
				<div class="item-main-data">
					<div class="name-wrap">
						<a href="<?=htmlspecialcharsbx($arCurElementInfo['DETAIL_PAGE_URL'])?>"><span class="text"><?=htmlspecialcharsbx($arCurElementInfo['NAME'])?></span></a>
					</div>
					<div class="price-wrap">
						<?if($arCurElementInfo['PRICE_DISCOUNT_DIFFERENCE_VALUE'] > 0):?>
							<span class="price-old"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(htmlspecialcharsbx($arCurElementInfo['PRICE_CURRENCY']), htmlspecialcharsbx($arCurElementInfo['PRICE_VALUE']), htmlspecialcharsbx($arCurElementInfo['PRICE_PRINT_VALUE']));?></span>
						<?endif?>
						<span class="price"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(htmlspecialcharsbx($arCurElementInfo['PRICE_CURRENCY']), htmlspecialcharsbx($arCurElementInfo['PRICE_DISCOUNT_VALUE']), htmlspecialcharsbx($arCurElementInfo['PRICE_PRINT_DISCOUNT_VALUE']));?></span>
					</div>
				</div>
			</div>

			<div class="items-wrap frame">
				<div class="items-list slidee">
				<?foreach($arSetElementsDefault as $arItem):?>
					<div class="sign">+</div>
					<div class="item-wrap">
						<div class="item"
                            <?=$arItem['OFFERS_PROP_CODES'] ? 'data-props="'.htmlspecialcharsbx($arItem['OFFERS_PROP_CODES']).'"' : ''?>
							data-item-id="<?=htmlspecialcharsbx($arItem['ID'])?>"
							data-discount-price="<?=htmlspecialcharsbx($arItem['PRICE_DISCOUNT_VALUE'])?>"
							data-price="<?=htmlspecialcharsbx($arItem['PRICE_VALUE'])?>"
							data-discount-diff-price="<?=htmlspecialcharsbx($arItem['PRICE_DISCOUNT_DIFFERENCE_VALUE'])?>">
							<span class="custom-collection-control add">
								<i>+</i>
							</span>
							<span class="custom-collection-control remove">
								<i class="flaticon-close47"></i>
							</span>
							<div class="item-photo">
								<img class="lazy" src="<?=ConsVar::showLoaderWithTemplatePath()?>" data-original="<?=htmlspecialcharsbx($arItem['PICTURE_PRINT']['SRC'])?>" title="<?=htmlspecialcharsbx($arItem['NAME'])?>" alt="<?=htmlspecialcharsbx($arItem['NAME'])?>">
							</div>
							<div class="item-main-data">
								<div class="name-wrap">
									<a href="<?=htmlspecialcharsbx($arItem['DETAIL_PAGE_URL'])?>"><span class="text"><?=htmlspecialcharsbx($arItem['NAME'])?></span></a>
								</div>
								<div class="price-wrap">
									<?if($arItem['PRICE_DISCOUNT_DIFFERENCE_VALUE'] > 0):?>
										<span class="price-old"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(
												htmlspecialcharsbx($arItem['PRICE_CURRENCY']),
												htmlspecialcharsbx($arItem['PRICE_VALUE']),
												htmlspecialcharsbx($arItem['PRICE_PRINT_VALUE'])
											);?></span>
									<?endif?>
									<span class="price"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(
											htmlspecialcharsbx($arItem['PRICE_CURRENCY']),
											htmlspecialcharsbx($arItem['PRICE_DISCOUNT_VALUE']),
											htmlspecialcharsbx($arItem['PRICE_PRINT_DISCOUNT_VALUE'])
										);?></span>
								</div>
							</div><!-- /.item-main-data -->
						</div><!-- /.item -->
					</div><!-- /.item-wrap -->
				<?endforeach?>
				</div>
				<div class="sly-scroll horizontal">
					<div class="sly-bar"></div>
				</div>
			</div>
			<div class="sign">=</div>
			<div class="final">
				<div class="price-full" <?=(!$bFullPriceShow) ? 'style="display: none"' : ''?>>
					<?=GetMessage('BITRONIC2_CATALOG_SET_WITHOUT_DISCOUNT')?>: <strong><?=CRZBitronic2CatalogUtils::getElementPriceFormat(htmlspecialcharsbx($arCurElementInfo['PRICE_CURRENCY']), htmlspecialcharsbx($arSetItemsInfo['OLD_PRICE_NOT_FORMATED']), htmlspecialcharsbx($arCurElementInfo['OLD_PRICE']));?></strong>
				</div>
				<div class="text"><?=GetMessage('BITRONIC2_CATALOG_SET_PRICE_ITOG')?>:</div>
				<div class="price-final"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(htmlspecialcharsbx($arCurElementInfo['PRICE_CURRENCY']), htmlspecialcharsbx($arSetItemsInfo['PRICE_NOT_FORMATED']), htmlspecialcharsbx($arCurElementInfo['PRICE']));?></div>
				<div class="value-saved" <?=(!$bFullPriceShow) ? 'style="display: none"' : ''?>>
					<?=GetMessage('BITRONIC2_CATALOG_SET_ECONOM')?>: <strong><?=CRZBitronic2CatalogUtils::getElementPriceFormat(htmlspecialcharsbx($arCurElementInfo['PRICE_CURRENCY']), htmlspecialcharsbx($arSetItemsInfo['PRICE_DISCOUNT_DIFFERENCE_NOT_FORMATED']), htmlspecialcharsbx($arCurElementInfo['PRICE_DISCOUNT_DIFFERENCE']));?></strong>
				</div>
				<button type="button" class="btn-main"><span class="text"><?=GetMessage('BITRONIC2_CATALOG_SET_BUY')?></span></button>
				
			</div>
		</div>
		<div class="items-to-choose-from">
			<div class="title-h2"><?=GetMessage('BITRONIC2_CATALOG_SET_POPUP_TITLE')?></div>
			<div class="subheader"><?=GetMessage('BITRONIC2_CATALOG_SET_POPUP_DESC')?></div>
			<div class="items-wrap frame">
				<div class="items-list slidee">
					<?foreach(array_merge($arSetElementsOther, $arSetElementsDefault) as $arItem):?>
                        <?if (\Bitrix\Main\Loader::IncludeModule('yenisite.core')) {
                            $catalogParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
                        }
                        $catalogParams['HIDE_ITEMS_ZER_PRICE'] = true;
                        $catalogParams['HIDE_ITEMS_NOT_AVAILABLE'] = true;
                        if (CRZBitronic2CatalogUtils::checkAvPrFotoForElement($arItem, $catalogParams))continue;?>
						<div class="item-wrap">
							<div class="item"
                                <?=$arItem['OFFERS_PROP_CODES'] ? 'data-props="'.htmlspecialcharsbx($arItem['OFFERS_PROP_CODES']).'"' : ''?>
								data-item-id="<?=htmlspecialcharsbx($arItem['ID'])?>"
								data-discount-price="<?=htmlspecialcharsbx($arItem['PRICE_DISCOUNT_VALUE'])?>"
								data-price="<?=htmlspecialcharsbx($arItem['PRICE_VALUE'])?>"
								data-discount-diff-price="<?=htmlspecialcharsbx($arItem['PRICE_DISCOUNT_DIFFERENCE_VALUE'])?>">
								<span class="custom-collection-control add">
									<i>+</i>
								</span>
								<span class="custom-collection-control remove">
									<i class="flaticon-close47"></i>
								</span>
								<div class="item-photo">
									<img class="lazy" src="<?=ConsVar::showLoaderWithTemplatePath()?>"  data-original="<?=htmlspecialcharsbx($arItem['PICTURE_PRINT']['SRC'])?>" alt="<?=htmlspecialcharsbx($arItem['NAME'])?>" title="<?=htmlspecialcharsbx($arItem['NAME'])?>">
								</div>
								<div class="item-main-data">
									<div class="name-wrap">
										<a href="<?=htmlspecialcharsbx($arItem['DETAIL_PAGE_URL'])?>"><span class="text"><?=htmlspecialcharsbx($arItem['NAME'])?></span></a>
									</div>
									<div class="price-wrap">
										<?if($arItem['PRICE_DISCOUNT_DIFFERENCE_VALUE'] > 0):?>
											<span class="price-old"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(htmlspecialcharsbx($arItem['PRICE_CURRENCY']), htmlspecialcharsbx($arItem['PRICE_VALUE']), htmlspecialcharsbx($arItem['PRICE_PRINT_VALUE']));?></span>
										<?endif?>
										<span class="price"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(htmlspecialcharsbx($arItem['PRICE_CURRENCY']), htmlspecialcharsbx($arItem['PRICE_DISCOUNT_VALUE']), htmlspecialcharsbx($arItem['PRICE_PRINT_DISCOUNT_VALUE']));?></span>
									</div>
								</div><!-- /.item-main-data -->
							</div><!-- /.item -->
						</div><!-- /.item-wrap -->
					<?endforeach?>
				</div>
				<div class="sly-scroll horizontal">
					<div class="sly-bar"></div>
				</div>
			</div>
		</div>
		
		<?
		return
		?>

		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	<div class="bx_modal_body" id="bx_catalog_set_construct_popup_<?=$curElementId?>">
		<div class="bx_kit_one_section">
			<div class="bx_kit_item">
				<div class="bx_kit_item_children">
					<div class="bx_kit_img_container" style="background-image: url('<?=htmlspecialcharsEx($arCurElementInfo["PREVIEW_PICTURE"])?>')"></div>
					<div class="bx_kit_item_title"><a href="<?=htmlspecialcharsEx($arCurElementInfo["DETAIL_PAGE_URL"])?>" target="_blank"><?=htmlspecialcharsEx($arCurElementInfo["NAME"])?></a></div>
					<div class="bx_kit_item_price"><div class="bx_price price"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(htmlspecialcharsbx($arCurElementInfo["PRICE_CURRENCY"]), htmlspecialcharsbx($arCurElementInfo["PRICE_DISCOUNT_VALUE"]), htmlspecialcharsbx($arCurElementInfo["PRICE_PRINT_DISCOUNT_VALUE"]))?></div></div>
				</div>
				<?if ($arCurElementInfo["PRICE_DISCOUNT_DIFFERENCE_VALUE"]):?><div class="bx_kit_item_discount" style="padding-top: 3px;"><?=htmlspecialcharsEx($arCurElementInfo["PRICE_DISCOUNT_DIFFERENCE"])?></div><?endif?>
			</div>
			<div class="bx_kit_item_plus"></div>

			<?
			$curCountDefaultSetItems = 0;
			?>
			<?foreach($arSetElementsDefault as $arItem):?>
				<div class="bx_kit_item bx_drag_dest<?if ($arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"]):?> discount<?endif?>">
					<div class="bx_kit_item_children bx_kit_item_border">
						<div class="bx_kit_img_container" style="background-image: url('<?=$arItem["PREVIEW_PICTURE"]?>')"></div>
						
						<div class="bx_kit_item_title" data-item-id="<?=htmlspecialcharsbx($arItem["ID"])?>"><a href="<?=htmlspecialcharsbx($arItem["DETAIL_PAGE_URL"])?>" target="_blank"><?=htmlspecialcharsbx($arItem["NAME"])?></a></div>
						<div class="bx_kit_item_price"
							data-discount-price="<?=(htmlspecialcharsbx($arItem["PRICE_CONVERT_DISCOUNT_VALUE"])) ? htmlspecialcharsbx($arItem["PRICE_CONVERT_DISCOUNT_VALUE"]) : htmlspecialcharsbx($arItem["PRICE_DISCOUNT_VALUE"])?>"
							data-price="<?=(htmlspecialcharsbx($arItem["PRICE_CONVERT_VALUE"])) ? htmlspecialcharsbx($arItem["PRICE_CONVERT_VALUE"]) : htmlspecialcharsbx($arItem["PRICE_VALUE"])?>"
							data-discount-diff-price="<?=(htmlspecialcharsbx($arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"])) ? htmlspecialcharsbx($arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"]) : htmlspecialcharsbx($arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"])?>"><div class="bx_price price"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(htmlspecialcharsbx($arItem["PRICE_CURRENCY"]), htmlspecialcharsbx($arItem["PRICE_DISCOUNT_VALUE"]), htmlspecialcharsbx($arItem["PRICE_PRINT_DISCOUNT_VALUE"]))?></div></div>
						<div class="bx_kit_item_del" onclick="catalogSetPopupObj.catalogSetDelete(this.parentNode);"></div>
					</div>
					<?if ($arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"]):?><div class="bx_kit_item_discount">-<?=htmlspecialcharsbx($arItem["PRICE_DISCOUNT_DIFFERENCE"])?></div><?endif?>
				</div>
				<?
				$curCountDefaultSetItems++;
				if ($curCountDefaultSetItems<3):?>
				<div class="bx_kit_item_plus"></div>
				<?endif?>
			<?endforeach?>

			<?if ($curCountDefaultSetItems<3):
				for($j=1; $j<=(3-$curCountDefaultSetItems); $j++)
				{
			?>
					<div class="bx_kit_item bx_kit_item_border bx_kit_item_empty bx_drag_dest"></div>
					<?if ($j<3-$curCountDefaultSetItems):?><div class="bx_kit_item_plus"></div><?endif?>
			<?
				}
			?>
			<?endif?>

			<div class="bx_kit_item_equally"></div>

			<div class="bx_kit_item" style="padding-top:0;">
				<div class="bx_kit_result <?if (!$setOldPrice && !$setPriceDiscountDifference):?>not_sale<?endif?>" id="bx_catalog_set_construct_price_block_<?=$curElementId?>">
					<div class="bx_kit_result_one" <?if (!$setOldPrice):?>style="display: none;"<?endif?>>
						<?=htmlspecialcharsEx($arMessage["CATALOG_SET_WITHOUT_DISCOUNT"])?> <br />
						<strong class="price"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(false, $setOldPrice, false, array('ID'=>'bx_catalog_set_construct_sum_old_price_'.$curElementId))?></strong>
					</div>
					<div class="bx_kit_result_two">
						<?=htmlspecialcharsEx($arMessage["CATALOG_SET_SUM"])?>:<br />
						<strong class="price"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(false, $setPrice, false, array('ID'=>'bx_catalog_set_construct_sum_price_'.$curElementId))?></strong>
					</div>
					<div class="bx_kit_result_tre" <?if (!$setPriceDiscountDifference):?>style="display: none;"<?endif?>>
						<?=htmlspecialcharsEx($arMessage["CATALOG_SET_DISCOUNT"])?>:<br />
						<strong class="price"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(false, $setPriceDiscountDifference, false, array('ID'=>'bx_catalog_set_construct_sum_diff_price_'.$curElementId))?></strong>
					</div>
					<button class="btn-submit" onclick="catalogSetPopupObj.Add2Basket();"><span class="btn-text"><?=htmlspecialcharsEx($arMessage["CATALOG_SET_BUY"])?></span></button>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>

		<div class="bx_kit_two_section">
			<div class="bx_kit_two_section_ova">
				<div class="bx_kit_two_item_slider" id="bx_catalog_set_construct_slider_<?=$curElementId?>" data-style-left="0" style="left:0%;width:<?=(count($arSetElementsOther) <=5) ? 100 : 100 + 20*(count($arSetElementsOther)-5)?>%">
				<?if (is_array($arSetElementsOther)):?>
					<?foreach($arSetElementsOther as $arItem):?>
					<div class="bx_kit_item_slider bx_drag_obj" style="width:<?=(count($arSetElementsOther) <=5) ? "20" : (100/count($arSetElementsOther))?>%" data-main-element-id="<?=$curElementId?>">
						<div class="bx_kit_item bx_kit_item_border<?if ($arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"]):?> discount<?endif?>">
							<div class="bx_kit_img_container" style="background-image: url('<?=$arItem["PREVIEW_PICTURE"]?>')"></div>
							
							<div class="bx_kit_item_title" data-item-id="<?=$arItem["ID"]?>"><a href="<?=htmlspecialcharsbx($arItem["DETAIL_PAGE_URL"])?>" target="_blank"><?=htmlspecialcharsbx($arItem["NAME"])?></a></div>
							<div class="bx_kit_item_price"
								data-discount-price="<?=(htmlspecialcharsbx($arItem["PRICE_CONVERT_DISCOUNT_VALUE"])) ? htmlspecialcharsbx($arItem["PRICE_CONVERT_DISCOUNT_VALUE"]) : htmlspecialcharsbx($arItem["PRICE_DISCOUNT_VALUE"])?>"
								data-price="<?=(htmlspecialcharsbx($arItem["PRICE_CONVERT_VALUE"])) ? htmlspecialcharsbx($arItem["PRICE_CONVERT_VALUE"]) : htmlspecialcharsbx($arItem["PRICE_VALUE"])?>"
								data-discount-diff-price="<?=(htmlspecialcharsbx($arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"])) ? htmlspecialcharsbx($arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"]) : htmlspecialcharsbx($arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"])?>">
								<div class="bx_price"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(htmlspecialcharsbx($arItem["PRICE_CURRENCY"]), htmlspecialcharsbx($arItem["PRICE_DISCOUNT_VALUE"]), htmlspecialcharsbx($arItem["PRICE_PRINT_DISCOUNT_VALUE"]))?></div>
							</div>
							<div class="bx_kit_item_add" onclick="catalogSetPopupObj.catalogSetAdd(this.parentNode);"></div>
						</div>
						<?if ($arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"]):?><div class="bx_kit_item_discount">-<?=CRZBitronic2CatalogUtils::getElementPriceFormat(htmlspecialcharsbx($arItem["PRICE_CURRENCY"]), htmlspecialcharsbx($arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"]), htmlspecialcharsbx($arItem["PRICE_DISCOUNT_DIFFERENCE"]))?></div><?endif?>
					</div>
					<?endforeach;?>
				<?endif?>
				</div>
			</div>
			<div class="bx_kit_item_slider_arrow_left" id="bx_catalog_set_construct_slider_left_<?=$curElementId?>" <?if (count($arSetElementsOther) < 5):?>style="display:none"<?endif?> onclick="catalogSetPopupObj.scrollItems('left')"></div>
			<div class="bx_kit_item_slider_arrow_right" id="bx_catalog_set_construct_slider_right_<?=$curElementId?>" <?if (count($arSetElementsOther) < 5):?>style="display:none"<?endif?> onclick="catalogSetPopupObj.scrollItems('right')"></div>
		</div>
	</div>

<script type="text/javascript">
	var catalogSetPopupObj = new catalogSetConstructPopup(<?=count($arSetElementsOther)?>,
		<?=(count($arSetElementsOther) > 5) ? (100/count($arSetElementsOther)) : 20?>,
		"<?=CUtil::JSEscape(htmlspecialcharsbx($arCurElementInfo["PRICE_CURRENCY"]))?>",
		"<?=CUtil::JSEscape(htmlspecialcharsbx($arCurElementInfo["PRICE_VALUE"]))?>",
		"<?=CUtil::JSEscape(htmlspecialcharsbx($arCurElementInfo["PRICE_DISCOUNT_VALUE"]))?>",
		"<?=CUtil::JSEscape(htmlspecialcharsbx($arCurElementInfo["PRICE_DISCOUNT_DIFFERENCE_VALUE"]))?>",
		"<?=htmlspecialcharsbx($_REQUEST["arParams"]["AJAX_PATH"])?>",
		<?=CUtil::PhpToJSObject($_REQUEST["arParams"]["DEFAULT_SET_IDS"])?>,
		"<?=htmlspecialcharsbx($_REQUEST["arParams"]["SITE_ID"])?>",
		"<?=$curElementId?>",
		<?=CUtil::PhpToJSObject($_REQUEST["arParams"]["ITEMS_RATIO"])?>,
		"<?=htmlspecialcharsbx($arCurElementInfo["DETAIL_PICTURE"]["src"]) ? htmlspecialcharsbx($arCurElementInfo["DETAIL_PICTURE"]["src"]) : $curTemplatePath."/images/no_foto.png"?>"
	);

	BX.ready(function(){
		jsDD.Enable();

		var destObj = BX.findChildren(BX("bx_catalog_set_construct_popup_<?=$curElementId?>"), {className:"bx_drag_dest"}, true);
		for (var i=0; i<destObj.length; i++)
		{
			jsDD.registerDest(destObj[i]);
			destObj[i].onbxdestdragfinish =  catalogSetConstructDestFinish;  //node was thrown inside of dest
		}
		var dragObj = BX.findChildren(BX("bx_catalog_set_construct_popup_<?=$curElementId?>"), {className:"bx_drag_obj"}, true);
		for (var i=0; i<dragObj.length; i++)
		{
			dragObj[i].onbxdragstart = catalogSetConstructDragStart;
			dragObj[i].onbxdrag = catalogSetConstructDragMove;
			dragObj[i].onbxdraghover = catalogSetConstructDragHover;
			dragObj[i].onbxdraghout = catalogSetConstructDragOut;
			dragObj[i].onbxdragrelease = catalogSetConstructDragRelease;   //node was thrown outside of dest
			jsDD.registerObject(dragObj[i]);
		}
	});
</script>