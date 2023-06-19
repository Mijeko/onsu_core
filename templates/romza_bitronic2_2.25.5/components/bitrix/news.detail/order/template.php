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

// @var $moduleId
include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/module_code.php';

CModule::IncludeModule($moduleId);

if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);
?>

<div class="account-order-page">
	<a href="<?=$arResult['LIST_PAGE_URL']?>" class="btn-return">
		<i class="flaticon-shopping109"></i>
		<span class="text"><?=GetMessage('ORDER_LIST_LINK')?></span>
	</a>

	<div class="title-h2"><?=GetMessage('ORDER_TITLE')?> <?=$arResult['ID']?></div>

	<div class="order-info-section">
		<header>
			<span class="text">
				<?=GetMessage('ORDER_TITLE')?> <?=GetMessage('ORDER_NUMBER')?> <?=$arResult['ID']?>
				<?if(strlen($arResult['DATE_CREATE_FORMATTED'])):?>
					<?=GetMessage('ORDER_FROM')?> <?=$arResult['DATE_CREATE_FORMATTED']?>
				<?endif?>
			</span>
		</header>
		<div class="main-content">
			<table>
				<tr>
					<td class="desc"><?=$arResult['PROPERTIES']['STATUS']['NAME']?>:</td>
					<td class="value"><?=$arResult['PROPERTIES']['STATUS']['VALUE']?></td>
				</tr>
				<tr>
					<td class="desc"><?=$arResult['PROPERTIES']['AMOUNT']['NAME']?>:</td>
					<td class="value"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(false, $arResult['PROPERTIES']['AMOUNT']['VALUE'])?></td>
				</tr>
			</table>
		</div><!-- /.main-content -->
	</div><!-- /.order-info-section -->

	<div class="order-info-section expandable allow-multiple-expanded">
		<header>
			<span class="text-wrap"><?=GetMessage('SPOD_ORDER_PROPERTIES')?></span>
		</header>
		<div class="main-content expand-content">
			<table><?
				foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>

				<tr>
					<td class="desc"><?=$arProperty["NAME"]?>:</td>
					<td class="value">
						<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
							<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
						<?elseif(is_array($arProperty['LINK_ELEMENT_VALUE'])):?>
							<?foreach($arProperty['LINK_ELEMENT_VALUE'] as $arLink):?>
								<?=$arLink['NAME']?>
							<?endforeach?>
						<?else:?>
							<?=$arProperty["DISPLAY_VALUE"];?>
						<?endif?>
					</td>
				</tr><?

				endforeach?>

			</table>
		</div><!-- /.main-content.expand-content -->
	</div><!-- /.order-info-section --><?

	if (count($arResult['PAYMENT_PROPERTIES'])):?>

	<div class="order-info-section expandable allow-multiple-expanded">
		<header>
			<span class="text-wrap"><?=GetMessage("SPOD_ORDER_PAYMENT")?></span>
		</header>
		<div class="main-content expand-content">
			<table><?
				foreach($arResult["PAYMENT_PROPERTIES"] as $arProperty):?>

				<tr>
					<td class="desc"><?=$arProperty["NAME"]?>:</td>
					<td class="value">
						<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
							<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
						<?elseif(is_array($arProperty['LINK_ELEMENT_VALUE'])):?>
							<?foreach($arProperty['LINK_ELEMENT_VALUE'] as $arLink):?>
								<?=$arLink['NAME']?>
								<?if($arLink['CODE'] == 'robokassa' && ($arResult['PROPERTIES']['STATUS']['VALUE_XML_ID'] == "added" || $arResult['PROPERTIES']['STATUS']['VALUE'] == GetMessage('ADD_ORDER'))):?>
									<div class="UI-element">
										<a href="/personal/order/?payment=Y&amp;id=<?=$arResult['ID']?>" class="btn-submit btn-pay-order"><span class="btn-text"><?=GetMessage('PAY_ORDER')?></span></a>
									</div>
								<?endif?>
							<?endforeach?>
						<?else:?>
							<?=$arProperty["DISPLAY_VALUE"];?>
						<?endif?>
					</td>
				</tr><?

				endforeach?>

			</table>
		</div><!-- /.main-content.expand-content -->
	</div><!-- /.order-info-section --><?

	endif ?>


    <div class="title-h4"><?=GetMessage('SPOD_ORDER_BASKET')?></div>
	<table class="items-table">
		<thead>
			<tr>			
				<th colspan="2"><?=GetMessage('SPOD_NAME')?></th>
				<th class="availabilit "><?=GetMessage('SPOD_QUANTITY')?></th>
				<th class="price"><?=GetMessage('SPOD_PRICE')?></th>
				<th class="sum"><?=GetMessage('BITRONIC2_SPOD_SUMM')?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					<div class="totals">
						<table class="table_totals">
							<tr class="final-total">
								<td class="text"><?=GetMessage('BITRONIC2_SPOD_SUMMARY')?>:</td>
								<td class="value"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(false, $arResult['PROPERTIES']['AMOUNT']['VALUE'])?></td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?foreach($arResult["BASKET_ITEMS"] as $prod):?>
				<tr class="table-item">
					<?$hasLink = !empty($prod["DETAIL_PAGE_URL"]);?>
					<td itemscope itemtype="http://schema.org/ImageObject" class="photo">
						<?if($hasLink):?>
							<a href="<?=$prod["DETAIL_PAGE_URL"]?>" target="_blank">
						<?endif?>

						<?if($prod['PICTURE']):?>

							<img itemprop="contentUrl"  class="lazy" data-original="<?=$prod['PICTURE']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" title="<?=$prod['NAME']?>" alt="<?=$prod['NAME']?>" />

						<?endif?>

						<?if($hasLink):?>
							</a>
						<?endif?>
					</td>		
					<td class="name">
						<?if($hasLink):?>
							<a href="<?=$prod["DETAIL_PAGE_URL"]?>" class="link" target="_blank">
						<?endif?>
						<span class="text"><?=$prod["NAME_FULL"]?></span>
						<?if($hasLink):?>
							</a>
						<?endif?>
					</td>
					<td class="availability">
						x <?=$prod["COUNT"]?>
						<?if(strlen($prod['MEASURE_TEXT'])):?>
							<?=$prod['MEASURE_TEXT']?>
						<?else:?>
							<?=GetMessage('SPOD_DEFAULT_MEASURE')?>
						<?endif?>
					</td>
					<td class="price"><span class="price-new"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(false, $prod["PRICE"])?></span></td>
					<td class="sum"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(false, floatval($prod["PRICE"])*floatval($prod['COUNT']))?></td>
				</tr>
			<?endforeach?>

		</tbody>
	</table>
	<br>
	<table class="bx_control_table" style="width: 100%;">
		<tr>
			<td>  <a href="<?=$arResult['LIST_PAGE_URL']?>" class="link"><span class="text"><?=GetMessage('SPOD_GO_BACK')?></span></a></td>
		</tr>
	</table>
</div>
<?$templateData = $arResult;?>