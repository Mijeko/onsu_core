<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * @var $arItemMatrix - array of item price matrix
 * @var $measureRatio - item catalog measure ratio
 * @var $measureName - item catalog measure name
 * @var $arItemPrices - array of item prices
 * @var $minPriceId - id of minimin item price type (PRICE_ID)
 */

if(CRZBitronic2Settings::isPro()):
	if (is_array($arItemMatrix)):
		foreach ($arItemMatrix['COLS'] as $colKey => $arCol):
			foreach ($arItemMatrix['ROWS'] as $rowKey => $arRow):
				if ($arRow['QUANTITY_FROM'] <= $measureRatio
				&& ($arRow['QUANTITY_TO'] >= $measureRatio || $arRow['QUANTITY_TO'] == 0)) continue;
				$arColPrice = &$arItemMatrix['MATRIX'][$colKey][$rowKey];
			?>

<div class="quan-price"><?=GetMessage('BITRONIC2_OFFERS_FROM')?> <?=$arRow['QUANTITY_FROM']?> <?=$measureName?> - <?=$arColPrice['HTML_DISCOUNT_PRICE']?></div>
<?
			endforeach;
		endforeach;
		unset($arColPrice);
	endif;
	if (count($arItemPrices) > 1):
		?>
<div class="wrapper baron-wrapper additional-prices-wrap">
	<div class="scroller scroller_v"><?

        foreach ($arItemPrices as $priceCode => $arPrice):?>
            <div class="additional-price-type <?=$arPrice['PRICE_ID'] == $minPriceId ? 'current' : ''?>">
                <span class="price-desc"><?= $arResult['CAT_PRICES'][$priceCode]['TITLE'] ?>:</span>
                <span class="price"><?
                    echo CRZBitronic2CatalogUtils::getElementPriceFormat(
                        $arPrice['CURRENCY'],
                        $arPrice['DISCOUNT_VALUE'],
                        $arPrice['PRINT_DISCOUNT_VALUE']
                    );
                    ?></span>
            </div>
        <?endforeach ?>
		<div class="scroller__track scroller__track_v">
			<div class="scroller__bar scroller__bar_v"></div>
		</div>
	</div>
</div>
<?
	endif?>
<?endif?>