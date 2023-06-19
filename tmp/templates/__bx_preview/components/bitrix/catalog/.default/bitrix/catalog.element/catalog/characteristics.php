<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<div class="general-info<?= ($arParams['DETAIL_TEXT_DEFAULT'] == 'open') ? ' toggled' : '' ?>">
	<? if (strlen(trim($arResult['DETAIL_TEXT'])) > 0) :
		// dublicated into bitrix/catalog.section.list/catalog/template.php ?>
		<div class="desc" itemprop="description">
			<?= $arResult['DETAIL_TEXT'] ?>
		</div>
		<div class="pseudolink">
			<span class="link-text when-closed"><?= GetMessage('BITRONIC2_DESC_SHOW_FULL') ?></span>
			<span class="link-text when-opened"><?= GetMessage('BITRONIC2_DESC_HIDE_FULL') ?></span>
		</div>
	<? endif ?>
	<? if ($arResult['CATALOG_WEIGHT'] > 0): ?>
		<div class="info weight flaticon-two328">
			<?= GetMessage('BITRONIC2_ITEM_WEIGHT') ?><?= $arResult['CATALOG_WEIGHT'] ?> <?= GetMessage('BITRONIC2_ITEM_WEIGHT_GRAMM') ?>
		</div>
	<? endif ?>
	<?
	if(0 < intval($arResult['CATALOG_LENGTH'])
	|| 0 < intval($arResult['CATALOG_WIDTH'])
	|| 0 < intval($arResult['CATALOG_HEIGHT'])
	):
	?>
		<div class="info dimensions flaticon-increase10">
			<?= GetMessage('BITRONIC2_ITEM_DIMENSIONS') ?>:
			<?=$arResult['CATALOG_LENGTH']?> x <?=$arResult['CATALOG_WIDTH']?> x <?=$arResult['CATALOG_HEIGHT']?>
			<?= GetMessage('BITRONIC2_ITEM_DIMENSIONS_MM') ?>
		</div>
	<? endif ?>
	<? if(!empty($arResult['TAGS']) && is_array($arResult['TAGS'])): ?>
	<div class="general-info-tags">
		<? foreach($arResult['TAGS'] as $tag): ?>
			<span class="label label-default"><?=$tag?></span>
		<? endforeach ?>
	</div>
	<? endif ?>
</div>
<div class="detailed-tech">
	<? if (!empty($arResult['DISPLAY_PROPERTIES']) || ($arResult['SHOW_OFFERS_PROPS'] && $arResult['bSkuExt'])): ?>
		<header><?= $arParams['TITLE_CHARACTERISTICS_HEADER'] ?: GetMessage('BITRONIC2_CHARACTERISTICS_TECH') ?> </header>
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