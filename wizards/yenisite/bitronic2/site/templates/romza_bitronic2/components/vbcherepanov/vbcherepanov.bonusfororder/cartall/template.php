<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use \Bitrix\Main\Localization\Loc as Loc;
Loc::loadMessages(__FILE__);
if($arResult['BONUS']){	?>
	<td class="text"><?=Loc::getMessage('VBCH_BONUS_FRO_ORDER_TITLE')?></td>
	<td class="value"><?=CRZBitronic2CatalogUtils::getElementPriceFormat(false, $arResult['BONUS'], $arResult['BONUS'], array('ID'=>'bns'))?></td>
	
<?}?>
