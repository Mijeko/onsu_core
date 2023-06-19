<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use \Bitrix\Main\Localization\Loc as Loc;
Loc::loadMessages(__FILE__);
if($arResult['BONUS']){
	$arResult['BONUS'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['BONUS_PRICE']['CURRENCY'], $arResult['BONUS'], $arResult['BONUS']);?>
	<tr>
		<td class="text" colspan="4"><?=GetMessage('VBCH_BONUS_FRO_ORDER_TITLE1')?></td>
		<td class="value" ><span id="bns"><?=$arResult['BONUS']?></span></td>
	</tr>
<?}?>