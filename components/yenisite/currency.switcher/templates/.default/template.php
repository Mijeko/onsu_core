<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arHTMLEntities = array(
	'RUB' => '&#8381;',
	'USD' => '&#36;',
	'EUR' => '&euro;',
	'UAH' => '&#8372;',
	'BYR' => 'Br'
);
?>
<select id="currency-switch" class="currency-switch" value="<?=$arResult['ACTIVE_CURRENCY']?>"><?

	foreach ($arResult['CURRENCIES'] as $currency):?>

		<option value="<?=$currency?>"><?if(array_key_exists($currency, $arHTMLEntities)):?><?=$arHTMLEntities[$currency]?> <?endif?>(<?=$currency?>)</option><?

	endforeach?>

</select>