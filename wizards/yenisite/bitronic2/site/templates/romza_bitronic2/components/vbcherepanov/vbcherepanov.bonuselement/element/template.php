<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use \Bitrix\Main\Localization\Loc as Loc;
Loc::loadMessages(__FILE__);
?>
<div class="element_bonus<? if(!$arResult['BONUS']): ?> hide<? endif ?>">
<?
$arResult['PREFIX'] = $arParams['PREFIX'] ?: $arResult['PREFIX'];
$arResult['BONUS']  = CRZBitronic2CatalogUtils::getElementPriceFormat(
	$arResult['BONUS_PRICE']['CURRENCY'],
	(int)$arResult['BONUS'],
	$arResult['BONUS']
);
?>
	<b><?=Loc::getMessage("VBCH_BONUS_ELEMENT_BONUSNAME",array("#BONUS#"=>$arResult['PREFIX'].$arResult['BONUS']))?></b>
</div>
<?
$arJSParams = array(
	'productID' => $arResult['DATA']['ID'],
	'IBLOCKID'  => $arResult['DATA']['IBLOCK_ID'],
	'MIN_PRICE' => base64_encode(serialize($arResult['BONUS_PRICE'])),
	'TYPE'      => $arParams['ONLY_NUM'],
	'siteid'    => SITE_ID,
);
?>
<script>
	var bonusElemUp = new ITRElementBonus(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
</script>