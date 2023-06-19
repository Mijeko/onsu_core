<?
use Bitrix\Main\Localization\Loc;
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
Loc::loadMessages(__FILE__);
?>
<p><b><?= str_replace("#DATE#", $arResult['DATE'], Loc::getMessage("VBCHBB_MY_ACCOUNT")) ?></b></p>
<ul>
	<li><?
	if ($arResult['BONUS']['SUMMA']) {
		$arResult['BONUS']['SUMMA'] = CRZBitronic2CatalogUtils::getElementPriceFormat(
			$arResult['BONUS_PRICE']['CURRENCY'],
			$arResult['BONUS']['SUMMA'],
			$arResult['BONUS']['SUMMA']
		);
		echo Loc::GetMessage("VBCHBONUS_ACCROUNT_BONUS") . $arResult['BONUS']['SUMMA'];
	} else {
		echo Loc::getMessage("VBCHBB_NO_ACCOUNT");
	}
	?></li>
</ul>

