<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var string[] $arProperty */

CBitrixComponent::includeComponentClass('bitrix:catalog.section');
include $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/components/bitrix/catalog.section/.parameters.php';

use Bitrix\Main\Loader;

$resizer_sets_list = array ();
if(Loader::IncludeModule("yenisite.resizer2")){
	$arSets = CResizer2Set::GetList();
	while($arr = $arSets->Fetch())
	{
		$resizer_sets_list[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
	}
}

// RESIZER:
$arComponentParameters["GROUPS"]["RESIZER_SETS"] = array(
	"NAME" => GetMessage("RESIZER_SETS"),
	"SORT" => 1
);

$arComponentParameters['PARAMETERS'] += array(
	"RESIZER_SET_BIG" => array(
		"PARENT" => "RESIZER_SETS",
		"NAME" => GetMessage("RESIZER_SET_BIG"),
		"TYPE" => "LIST",
		"VALUES" => $resizer_sets_list,
		"DEFAULT" => "2",
	),
	"RESIZER_SET_SMALL" => array(
		"PARENT" => "RESIZER_SETS",
		"NAME" => GetMessage("RESIZER_SET_SMALL"),
		"TYPE" => "LIST",
		"VALUES" => $resizer_sets_list,
		"DEFAULT" => "6",
	),
);

// MAINSPEC:
$arComponentParameters['GROUPS']['STICKERS'] = array(
	"NAME" => GetMessage('STICKERS'),
	"SORT" => 10
);

if (CModule::IncludeModule('yenisite.mainspec')) {
	$arStickerParameters = CYenisiteMainspec::CatalogParams(
		array_merge(array('-' => GetMessage('RZ_PROP_EMPTY')), $arProperty),
		$arCurrentValues
	);
	unset(
		$arStickerParameters['STICKER_HIT'],
		$arStickerParameters['STICKER_BESTSELLER']
	);
	foreach ($arStickerParameters as &$arParam) {
		$arParam['PARENT'] = 'STICKERS';
	} unset($arParam);
	$arComponentParameters['PARAMETERS'] += $arStickerParameters;
}


// HIDE exist params
$arComponentParameters['PARAMETERS']['LINE_ELEMENT_COUNT']['HIDDEN'] = 'Y';
$arComponentParameters['PARAMETERS']['PAGER_TEMPLATE']['HIDDEN'] = 'Y';
$arComponentParameters['PARAMETERS']['DISPLAY_TOP_PAGER']['HIDDEN'] = 'Y';
$arComponentParameters['PARAMETERS']['DISPLAY_BOTTOM_PAGER']['HIDDEN'] = 'Y';
$arComponentParameters['PARAMETERS']['PAGER_TITLE']['HIDDEN'] = 'Y';
$arComponentParameters['PARAMETERS']['PAGER_SHOW_ALWAYS']['HIDDEN'] = 'Y';
$arComponentParameters['PARAMETERS']['PAGER_DESC_NUMBERING']['HIDDEN'] = 'Y';
$arComponentParameters['PARAMETERS']['PAGER_DESC_NUMBERING_CACHE_TIME']['HIDDEN'] = 'Y';
$arComponentParameters['PARAMETERS']['PAGER_SHOW_ALL']['HIDDEN'] = 'Y';
$arComponentParameters['PARAMETERS']['AJAX_OPTION_ADDITIONAL']['HIDDEN'] = 'Y';
$arComponentParameters['PARAMETERS']['DISPLAY_COMPARE']['HIDDEN'] = 'Y';

// EDIT exist params
$arComponentParameters['PARAMETERS']['PAGE_ELEMENT_COUNT']['NAME'] = GetMessage('PAGE_ELEMENT_COUNT');
?>