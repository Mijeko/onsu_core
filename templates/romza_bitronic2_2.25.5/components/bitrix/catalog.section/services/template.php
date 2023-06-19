<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';

if(empty($arResult['ITEMS']))
	return;
	
?>

<div class="buy-block-additional">
						<header>
							<i class="flaticon-package6"></i>
							<span class="text"><?=GetMessage('BITRONIC2_SERVICES_HEADER')?></span>
						</header>
<?foreach($arResult['ITEMS'] as $arItem):
	$strMainID = $this->GetEditAreaId('service-'.$arItem['ID']);
	$arItemIDs = array(
		'ID' => $strMainID,
		'PRICE' => $strMainID.'_price',
		'PRICE_DIV' => $strMainID.'_price_wrap',
	);
?>
						<label class="checkbox-styled">
							<input type="checkbox" data-service-id="<?=$arItem['ID']?>">
							<span class="checkbox-content">
								<i class="flaticon-check14"></i>
								<?=$arItem['NAME']?><?if(!empty($arItem['HELP'])):?> <sup data-tooltip title="<?=$arItem['HELP']?>">?</sup><?endif?> &mdash;
								<span class="price">
									<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']);?> 
								</span>
							</span>
						</label>
<? endforeach ?>

					</div><!-- /.buy-additional -->

<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
?>
