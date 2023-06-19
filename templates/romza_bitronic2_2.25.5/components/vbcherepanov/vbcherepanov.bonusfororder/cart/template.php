<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use \Bitrix\Main\Localization\Loc as Loc;
Loc::loadMessages(__FILE__);
$arResult['BONUS']=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['BONUS_PRICE']['CURRENCY'],$arResult['BONUS'],$arResult['BONUS']);
if($arResult['BONUS']){?>
	<?=Loc::getMessage('VBCH_BONUS_FRO_ORDER_TITLE1');?>
	<b><span id='bns'><?=$arResult['BONUS']?></span></b>
<?}?>
<?
 $arJSParams=array(
	'siteid'=>SITE_ID,
	'TYPE'=>$arParams['TYPE'],
 );
?>
<script>
	var bonusCartUp= new ITROrderBonus(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
</script>