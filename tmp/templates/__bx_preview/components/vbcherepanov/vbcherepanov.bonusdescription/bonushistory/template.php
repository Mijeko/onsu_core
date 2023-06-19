<?
use Bitrix\Main\Localization\Loc;
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php
Loc::loadMessages(__FILE__);
?>
<div class="account-order-page">
	<div class="title-h3"><?=GetMessage("VBCHBB_DESC_TITLE")?></div>
	<table class="table_order-history">
		<thead>
			<tr class="head-row">
				<td class="order-status"><?=GetMessage("VBCHBB_DESC_ACTIVE")?></td>
				<td class="order-code"><?=GetMessage("VBCHBB_DESC_CREATED")?></td>
				<td class="order-code"><?=GetMessage("VBCHBB_DESC_ACTIVE_FROM")?></td>
				<td class="order-code"><?=GetMessage("VBCHBB_DESC_ACTIVE_TO")?></td>
				<td class="order-sum"><?=GetMessage("VBCHBB_DESC_SUMM")?></td>
				<td class="order-content"><?=GetMessage("VBCHBB_DESC_DESCR")?></td>
			</tr>
		</thead>
		<tbody>
		<?if($arParams['SHOW_INNER_ACCOUNT']=="Y"){?>
			<tr><td colspan="7"><b><?=Loc::getMessage('VBCHBB_COMP_INNER')?></b></td></tr>
			<?foreach($arResult["ACCOUNTUSER"] as $arItem):?>
				<tr style="background-color:#<?=intval($arItem['SUMMA'])<0 ? 'EC9696' : '8FD552'?>">
					<td class="order-status"><?=$arItem["ACTIVE"]?></td>
					<td class="order-code"><?=$arItem["DATE"]?></td>
					<td class="order-code"><?=$arItem["ACTIVE_FROM"]?></td>
					<td class="order-code"><?=$arItem["ACTIVE_TO"]?></td>
					<td class="order-sum">
						<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['BONUS_PRICE']['CURRENCY'],$arItem["SUMMA"],$arItem["SUMMA"])?>
					</td>
					<td><?=$arItem["DESCRIPTION"]?></td>
				</tr>
			<?endforeach;?>
		<?}?>
		<?if($arParams['SHOW_BONUS_ACCOUNT']=="Y"){?>
			<tr><td colspan="7"><b><?=Loc::getMessage('VBCHBB_COMP_BONUS')?></b></td></tr>
			<?foreach($arResult["DATA"] as $arItem):?>
				<tr style="background-color:#<?=intval($arItem['SUMMA'])<0 ? 'EC9696' : '8FD552'?>">
					<td class="order-status"><?=$arItem["ACTIVE"]?></td>
					<td class="order-code"><?=$arItem["DATE"]?></td>
					<td class="order-code"><?=$arItem["ACTIVE_FROM"]?></td>
					<td class="order-code"><?=$arItem["ACTIVE_TO"]?></td>
					<td class="order-sum">
						<?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['BONUS_PRICE']['CURRENCY'],$arItem["SUMMA"],$arItem["SUMMA"])?>
					</td>
					<td class="order-content"><?=$arItem["DESCRIPTION"]?></td>
				</tr>
			<?endforeach;?>
		<?}?>
	</tbody>
	</table>
</div>