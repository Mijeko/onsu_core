<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arShortInfo = array(
	array(
		'CLASS' => 'credit',
		'ICON' => 'flaticon-sale',
		'PROP' => 'RZ_CREDIT'
	),
	array(
		'CLASS' => 'delivery',
		'ICON' => 'flaticon-ecommerce16',
		'PROP' => 'RZ_DELIVERY'
	),
	array(
		'CLASS' => 'warranty',
		'ICON' => 'img-here',
		'PROP' => 'RZ_GUARANTEE'
	)
);
?>
<div class="short-info under-image">
<?foreach($arShortInfo as $arOneInfo):
	$arProp = &$arResult['PROPERTIES'][$arOneInfo['PROP']];
	$arPropHint = &$arResult['PROPERTIES'][$arOneInfo['PROP'].'_HINT'];
	if (!is_array($arProp)) continue;
	if (empty($arProp['VALUE'])) continue;
	$hint = $arProp['HINT'];
	if (is_array($arPropHint) && !empty($arPropHint['VALUE'])) {
		$hint = $arPropHint['~VALUE_ENUM'] ?: ($arPropHint['VALUE_ENUM'] ?: $arPropHint['~VALUE']);
	}
	?>
				<div class="info <?=$arOneInfo['CLASS']?>">
					<span class="pseudolink-bd link-black" data-popup="^.info>.popup_detailed" data-position="">
						<i class="<?=$arOneInfo['ICON']?>"></i>
						<span class="text"><?=$arProp['NAME']?>:</span>
						<strong><?=($arProp['~VALUE_ENUM']?:($arProp['VALUE_ENUM']?:$arProp['~VALUE']))?></strong>
					</span>
<?	if(!empty($hint)):?>
					<div class="popup_detailed">
						<?=str_replace(array('%VALUE%', '%DESCRIPTION%'), array($arProp['VALUE_ENUM']?:$arProp['~VALUE'], $arProp['~DESCRIPTION']), $hint)?>

					</div>
<?	endif?>
				</div><!-- /.info -->
<?endforeach?>
			</div><!-- /.short-info -->