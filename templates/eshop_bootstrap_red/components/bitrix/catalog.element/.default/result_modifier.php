<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

// Правим описание товара
$detailText = $arResult['DETAIL_TEXT'];
$detailText = str_replace('||', '</p><p>', $detailText);
$detailText = str_replace('|', ' ', $detailText);
$arResult['DETAIL_TEXT'] = $detailText;

// Меняем наименование
$arResult['NAME'] = $arResult['PREVIEW_TEXT'];

//if ($arResult['ITEM_MEASURE']['ID'] == 4) {
//    $arResult['ITEM_MEASURE_RATIOS'][0]['RATIO'] = 0.1;
//}
