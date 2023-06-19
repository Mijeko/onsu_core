<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
require_once(__DIR__ . '/functions.php');
use Yenisite\Favorite\Template\Tools;

if (CModule::IncludeModule('catalog')) {
	/** @var int[] $arDelete - indexes of products with offers to delete from items */
	$arDelete = array();

	foreach ($arResult['ITEMS'] as $index => $arItem) {
		if (isset($arItem['OFFERS']) && !empty($arItem['OFFERS'])) {
			$arDelete[] = $index;
			foreach ($arItem['OFFERS'] as $arOffer) {
				if (!in_array($arOffer['ID'], $arParams['OFFER_ID'])) continue;
				$arResult['ITEMS'][] = $arOffer;
			}
			continue;
		}
	}

	foreach ($arDelete as $index) {
		unset($arResult['ITEMS'][$index]);
	}
	unset($arDelete);
}

$arParams['RESIZER_SET'] = intval($arParams['RESIZER_SET']) > 0 ? $arParams['RESIZER_SET'] : 5;

foreach ($arResult['ITEMS'] as $index => &$arItem) {
	if (!empty($arItem['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_ALT'])) {
		$imgAlt = $arItem['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_ALT'];
	} else {
		$imgAlt = $arItem['NAME'];
	}
	$arItem['PICTURE_PRINT']['ALT'] = $imgAlt;
	$arItem['PICTURE_PRINT']['SRC'] = Tools::getElementPictureById($arItem['ID'], $arParams['RESIZER_SET']);
}
if (isset($arItem)) {
	unset($arItem);
}
