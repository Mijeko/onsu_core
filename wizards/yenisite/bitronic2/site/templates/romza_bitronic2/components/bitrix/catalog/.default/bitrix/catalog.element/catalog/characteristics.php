<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if ($bHasDescription && !$bDifferentCharsAndDesc):?>
    <?include "include/description.php"?>
<?endif?>
<div class="detailed-tech">
	<? if (!empty($arResult['DISPLAY_PROPERTIES']) || ($arResult['SHOW_OFFERS_PROPS'] && $arResult['bSkuExt'])): ?>
		<header><?= $arParams['TITLE_CHARACTERISTICS_HEADER'] ?: GetMessage('BITRONIC2_CHARACTERISTICS_TECH') ?> <?= $productTitle ?></header>
		<?
		if (\Bitrix\Main\Loader::IncludeModule('yenisite.infoblockpropsplus') && !empty($arResult['DISPLAY_PROPERTIES'])):
			$APPLICATION->IncludeComponent('yenisite:ipep.props_groups', 'detail', array(
				'DISPLAY_PROPERTIES' => $arResult['DISPLAY_PROPERTIES'],
				'IBLOCK_ID' => $arParams['IBLOCK_ID'],
				'SKU_PROP_ID' => ($arResult['SHOW_OFFERS_PROPS'] && $arResult['bSkuExt']) ? $arItemIDs['DISPLAY_PROP_DIV'] : '',
				'SHOW_PROPERTY_VALUE_DESCRIPTION' => 'Y'
			),

				$component
			);
		else:?>
			<div class="tech-info-block expanded">
				<?if($arResult['SHOW_OFFERS_PROPS'] && $arResult['bSkuExt']):?>

				<dl class="expand-content clearfix" id="<?=$arItemIDs['DISPLAY_PROP_DIV']?>">
				</dl>
				<?endif?>

				<dl class="expand-content clearfix">
					<? foreach ($arResult['DISPLAY_PROPERTIES'] as $arProp):?>
						<dt><span class="property-name"><?= $arProp['NAME'] ?><?
								if (strlen($arProp['HINT']) > 0):
									?><sup data-tooltip title="<?= $arProp['HINT'] ?>" data-placement="right">?</sup><?
								endif ?></span></dt>
						<dd><?= (is_array($arProp['DISPLAY_VALUE']) ? implode(' / ', $arProp['DISPLAY_VALUE']) : $arProp['DISPLAY_VALUE']) ?></dd>
					<? endforeach ?>
				</dl>
			</div><!-- .tech-info-block -->
		<? endif ?>
	<? endif ?>
</div><!-- /.detailed-tech -->