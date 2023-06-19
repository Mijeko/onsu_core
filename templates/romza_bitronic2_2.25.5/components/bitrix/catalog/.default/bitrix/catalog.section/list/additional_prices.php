<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * @var $arItemPrices - array of item prices
 * @var $minPriceId - id of minimin item price type (PRICE_ID)
 */

if(count($arItemPrices) > 1 && CRZBitronic2Settings::isPro()):?>

<div class="wrapper baron-wrapper additional-prices-wrap">
	<div class="scroller scroller_v"><?

        foreach ($arItemPrices as $priceCode => $arPrice):
            if ($arResult['PRICES'][$priceCode]['CAN_VIEW'] == false) continue; ?>

        <div class="additional-price-type <?=$arPrice['PRICE_ID'] == $minPriceId ? 'current' : ''?>">
            <span class="price-desc"><?= $arResult['PRICES'][$priceCode]['TITLE'] ?>:</span>
            <span class="price"><?=(!empty($arItem['OFFERS']) && $arItemPrices['bSkuSimple'] ? GetMessage('RZ_OT') : '')?><?
                echo CRZBitronic2CatalogUtils::getElementPriceFormat(
                    $arPrice['CURRENCY'],
                    $arPrice['DISCOUNT_VALUE'],
                    $arPrice['PRINT_DISCOUNT_VALUE'],
                    array("itemprop" => "highPrice")
                );
                ?></span>
            </div><?
        endforeach ?>


        <div class="scroller__track scroller__track_v">
			<div class="scroller__bar scroller__bar_v"></div>
		</div>
	</div>
</div>
<?endif?>