<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$stringName = '';
if(count($arResult['ITEMS'])>1 )
{
	$splitterSign = ', ';
	$splitterWord = ' '.GetMessage('BITRONIC2_CATALOG_COMPARE_OR').' ';
	$first = reset($arResult['ITEMS']);
	$last = end($arResult['ITEMS']);
	foreach($arResult['ITEMS'] as $item)
	{
		$name = (
			empty($item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
			? $item['NAME']
			: $item['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
		);
		if($item == $first)	{
			// nothing
		} elseif($item == $last) {
			$stringName .= $splitterWord;
		} else {
			$stringName .= $splitterSign;
		}
			
		$stringName .= $name;
	}
	
	$APPLICATION->SetPageProperty("keywords", str_replace('#TEXT#', $stringName, $arParams['META_KEYWORDS']));
	$APPLICATION->SetPageProperty("description", str_replace('#TEXT#', $stringName, $arParams['META_DESCRIPTION']));
}

$APPLICATION->AddChainItem(GetMessage('BITRONIC2_CATALOG_COMPARE_TITUL'));

if (empty($stringName)) {
	$APPLICATION->SetPageProperty("title", GetMessage('BITRONIC2_CATALOG_COMPARE_TITUL'));
	$APPLICATION->SetTitle(GetMessage('BITRONIC2_CATALOG_COMPARE_TITUL'));
}
else {
	$APPLICATION->SetPageProperty("title", str_replace('#TEXT#', $stringName, $arParams['META_TITLE']));
	$APPLICATION->SetTitle(str_replace('#TEXT#', $stringName, $arParams['META_H1']));
}
