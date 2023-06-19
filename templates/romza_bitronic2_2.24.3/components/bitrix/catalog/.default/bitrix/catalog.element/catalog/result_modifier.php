<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
use Bitrix\Main\Type\Collection;
use Bitrix\Iblock;

$arResult = CRZBitronic2CatalogUtils::processItemCommon($arResult);

$arParams['USE_PRICE_COUNT_'] = ($arParams['USE_PRICE_COUNT_'] === 'Y');

// PRICE MATRIX
if ($arParams["USE_PRICE_COUNT_"] && CRZBitronic2Settings::isPro() && $arResult['MODULES']['catalog'] && is_array($arResult['MIN_PRICE'])) {
	$arResult["PRICE_MATRIX"] = CRZBitronic2CatalogUtils::getPriceMatrix($arResult["ID"], $arResult['MIN_PRICE']['PRICE_ID'], $arResult['CONVERT_CURRENCY']);
}

// REVIEW COUNT. TODO
// if ($arParams['USE_REVIEW'] != "N" && $arParams['USE_OWN_REVIEW'] != "N") {
// 	$arResult['REVIEW_COUNT'] = CRZBitronic2CatalogUtils::getItemReviewCount($arResult, $arParams);
// }

// AJAX PATH
$ajaxPath = SITE_DIR."ajax/catalog.php";
$ajaxPathCompare = SITE_DIR."ajax/compare.php";
$ajaxPathFavorite = SITE_DIR."ajax/favorites.php";
$arResult['ADD_URL_TEMPLATE'] = $ajaxPath."?".$arParams["ACTION_VARIABLE"]."=ADD2BASKET&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#";
// $arResult['FAVORITE_URL'] = $ajaxPath."?".$arParams["ACTION_VARIABLE"]."=SUBSCRIBE_PRODUCT&".$arParams["PRODUCT_ID_VARIABLE"]."=".$arResult["ID"].'&ajax_basket=Y';
$arResult['COMPARE_URL_TEMPLATE'] = $ajaxPathCompare."?".$arParams["ACTION_VARIABLE"]."=ADD_TO_COMPARE_LIST&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#&ajax_basket=Y";
$arResult['COMPARE_URL_TEMPLATE_DEL'] = $ajaxPathCompare."?".$arParams["ACTION_VARIABLE"]."=DELETE_FROM_COMPARE_LIST&".$arParams["PRODUCT_ID_VARIABLE"]."=#ID#&ajax_basket=Y";

$arResult['FAVORITE_URL_TEMPLATE'] = $ajaxPathFavorite."?ACTION=ADD&ID=#ID#";
$arResult['FAVORITE_URL_TEMPLATE_DEL'] = $ajaxPathFavorite."?ACTION=DELETE&ID=#ID#";

$arResult["DISPLAY_UPDATE_DATE"] = CIBlockFormatProperties::DateFormat("d.m.Y H:i", MakeTimeStamp($arResult["TIMESTAMP_X"], CSite::GetDateFormat()));

$displayPreviewTextMode = array(
	'H' => true,
	'E' => true,
	'S' => true
);
$detailPictMode = array(
	'IMG' => true,
	'POPUP' => true,
	'MAGNIFIER' => true,
	'GALLERY' => true
);

$arDefaultParams = array(
	'ADD_PICT_PROP' => '-',
	'LABEL_PROP' => '-',
	'OFFER_ADD_PICT_PROP' => 'MORE_PHOTO',
	'OFFER_TREE_PROPS' => array('-'),
	'DISPLAY_NAME' => 'Y',
	'DETAIL_PICTURE_MODE' => 'IMG',
	'ADD_DETAIL_TO_SLIDER' => 'Y',
	'DISPLAY_PREVIEW_TEXT_MODE' => 'E',
	'PRODUCT_SUBSCRIPTION' => 'N',
	'SHOW_DISCOUNT_PERCENT' => 'N',
	'SHOW_OLD_PRICE' => 'N',
	'SHOW_MAX_QUANTITY' => 'N',
	'SHOW_BASIS_PRICE' => 'N',
	'ADD_TO_BASKET_ACTION' => array('BUY'),
	'SHOW_CLOSE_POPUP' => 'N',
	'MESS_BTN_BUY' => '',
	'MESS_BTN_ADD_TO_BASKET' => '',
	'MESS_BTN_SUBSCRIBE' => '',
	'MESS_BTN_COMPARE' => '',
	'MESS_NOT_AVAILABLE' => '',
	'USE_VOTE_RATING' => 'N',
	'VOTE_DISPLAY_AS_RATING' => 'rating',
	'USE_COMMENTS' => 'N',
	'BLOG_USE' => 'N',
	'BLOG_URL' => 'catalog_comments',
	'BLOG_EMAIL_NOTIFY' => 'N',
	'VK_USE' => 'N',
	'VK_API_ID' => '',
	'FB_USE' => 'N',
	'FB_APP_ID' => '',
	'BRAND_USE' => 'N',
	'BRAND_PROP_CODE' => ''
);
$arParams = array_merge($arDefaultParams, $arParams);

$arParams['ADD_PICT_PROP'] = trim($arParams['ADD_PICT_PROP']);
if ('-' == $arParams['ADD_PICT_PROP'])
	$arParams['ADD_PICT_PROP'] = '';
$arParams['LABEL_PROP'] = trim($arParams['LABEL_PROP']);
if ('-' == $arParams['LABEL_PROP'])
	$arParams['LABEL_PROP'] = '';
$arParams['OFFER_ADD_PICT_PROP'] = trim($arParams['OFFER_ADD_PICT_PROP']);
if ('-' == $arParams['OFFER_ADD_PICT_PROP'])
	$arParams['OFFER_ADD_PICT_PROP'] = '';
if (!is_array($arParams['OFFER_TREE_PROPS']))
	$arParams['OFFER_TREE_PROPS'] = array($arParams['OFFER_TREE_PROPS']);
foreach ($arParams['OFFER_TREE_PROPS'] as $key => $value)
{
	$value = (string)$value;
	if ('' == $value || '-' == $value)
		unset($arParams['OFFER_TREE_PROPS'][$key]);
}
if (empty($arParams['OFFER_TREE_PROPS']) && isset($arParams['OFFERS_CART_PROPERTIES']) && is_array($arParams['OFFERS_CART_PROPERTIES']))
{
	$arParams['OFFER_TREE_PROPS'] = $arParams['OFFERS_CART_PROPERTIES'];
	foreach ($arParams['OFFER_TREE_PROPS'] as $key => $value)
	{
		$value = (string)$value;
		if ('' == $value || '-' == $value)
			unset($arParams['OFFER_TREE_PROPS'][$key]);
	}
}
if ('N' != $arParams['DISPLAY_NAME'])
	$arParams['DISPLAY_NAME'] = 'Y';
if (!isset($detailPictMode[$arParams['DETAIL_PICTURE_MODE']]))
	$arParams['DETAIL_PICTURE_MODE'] = 'IMG';
if ('Y' != $arParams['ADD_DETAIL_TO_SLIDER'])
	$arParams['ADD_DETAIL_TO_SLIDER'] = 'N';
if (!isset($displayPreviewTextMode[$arParams['DISPLAY_PREVIEW_TEXT_MODE']]))
	$arParams['DISPLAY_PREVIEW_TEXT_MODE'] = 'E';
if ('Y' != $arParams['PRODUCT_SUBSCRIPTION'])
	$arParams['PRODUCT_SUBSCRIPTION'] = 'N';
if ('Y' != $arParams['SHOW_DISCOUNT_PERCENT'])
	$arParams['SHOW_DISCOUNT_PERCENT'] = 'N';
if ('Y' != $arParams['SHOW_OLD_PRICE'])
	$arParams['SHOW_OLD_PRICE'] = 'N';
if ('Y' != $arParams['SHOW_MAX_QUANTITY'])
	$arParams['SHOW_MAX_QUANTITY'] = 'N';
if ($arParams['SHOW_BASIS_PRICE'] != 'Y')
	$arParams['SHOW_BASIS_PRICE'] = 'N';
if (!is_array($arParams['ADD_TO_BASKET_ACTION']))
	$arParams['ADD_TO_BASKET_ACTION'] = array($arParams['ADD_TO_BASKET_ACTION']);
$arParams['ADD_TO_BASKET_ACTION'] = array_filter($arParams['ADD_TO_BASKET_ACTION'], 'CIBlockParameters::checkParamValues');
if (empty($arParams['ADD_TO_BASKET_ACTION']) || (!in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']) && !in_array('BUY', $arParams['ADD_TO_BASKET_ACTION'])))
	$arParams['ADD_TO_BASKET_ACTION'] = array('BUY');
if ($arParams['SHOW_CLOSE_POPUP'] != 'Y')
	$arParams['SHOW_CLOSE_POPUP'] = 'N';

$arParams['MESS_BTN_BUY'] = trim($arParams['MESS_BTN_BUY']);
$arParams['MESS_BTN_ADD_TO_BASKET'] = trim($arParams['MESS_BTN_ADD_TO_BASKET']);
$arParams['MESS_BTN_SUBSCRIBE'] = trim($arParams['MESS_BTN_SUBSCRIBE']);
$arParams['MESS_BTN_COMPARE'] = trim($arParams['MESS_BTN_COMPARE']);
$arParams['MESS_NOT_AVAILABLE'] = trim($arParams['MESS_NOT_AVAILABLE']);
if ('Y' != $arParams['USE_VOTE_RATING'])
	$arParams['USE_VOTE_RATING'] = 'N';
if ('vote_avg' != $arParams['VOTE_DISPLAY_AS_RATING'])
	$arParams['VOTE_DISPLAY_AS_RATING'] = 'rating';
if ('Y' != $arParams['USE_COMMENTS'])
	$arParams['USE_COMMENTS'] = 'N';
if ('Y' != $arParams['BLOG_USE'])
	$arParams['BLOG_USE'] = 'N';
if ('Y' != $arParams['VK_USE'])
	$arParams['VK_USE'] = 'N';
if ('Y' != $arParams['FB_USE'])
	$arParams['FB_USE'] = 'N';
if ('Y' == $arParams['USE_COMMENTS'])
{
	if ('N' == $arParams['BLOG_USE'] && 'N' == $arParams['VK_USE'] && 'N' == $arParams['FB_USE'])
		$arParams['USE_COMMENTS'] = 'N';
}
if ('Y' != $arParams['BRAND_USE'])
	$arParams['BRAND_USE'] = 'N';

$arParams['QUICK_VIEW'] = $arParams['QUICK_VIEW'] === 'Y' ? true : false;
$arParams["RESIZER_SETS"]['RESIZER_DETAIL_SMALL'] = $arParams['QUICK_VIEW'] ? $arParams["RESIZER_SETS"]['RESIZER_QUICK_VIEW'] : $arParams["RESIZER_SETS"]['RESIZER_DETAIL_SMALL'];

$arEmptyPreview = false;
$strEmptyPreview = CResizer2Resize::ResizeGD2('', $arParams["RESIZER_SETS"]['RESIZER_DETAIL_SMALL']);
//
// THIS CODE DOES NOT WORK WHENEVER RESIZER IMAGES ARE STORED INSIDE EXTERNAL CLOUD SERVICE
//
// if (file_exists($_SERVER['DOCUMENT_ROOT'].$strEmptyPreview))
// {
// 	$arSizes = getimagesize($_SERVER['DOCUMENT_ROOT'].$strEmptyPreview);
// 	if (!empty($arSizes))
// 	{
// 		$arEmptyPreview = array(
// 			'SRC' => $strEmptyPreview,
// 			'WIDTH' => (int)$arSizes[0],
// 			'HEIGHT' => (int)$arSizes[1]
// 		);
// 	}
// 	unset($arSizes);
// }
if (!empty($strEmptyPreview)) {
	$arEmptyPreview = array(
		'SRC' => $strEmptyPreview
	);
}
unset($strEmptyPreview);

$arSKUPropList = array();
$arSKUPropIDs = array();
$arSKUPropKeys = array();
$boolSKU = false;
$strBaseCurrency = '';
$boolConvert = isset($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);

if ($arResult['MODULES']['catalog'])
{
	if (!$boolConvert)
		$strBaseCurrency = CCurrency::GetBaseCurrency();

	$arSKU = CCatalogSKU::GetInfoByProductIBlock($arParams['IBLOCK_ID']);
	$boolSKU = !empty($arSKU) && is_array($arSKU);

	if ($boolSKU && !empty($arParams['OFFER_TREE_PROPS']))
	{
		$arSKUPropList = CIBlockPriceTools::getTreeProperties(
			$arSKU,
			$arParams['OFFER_TREE_PROPS'],
			array(
				'PICT' => $arEmptyPreview,
				'NAME' => '-'
			)
		);
		$arSKUPropIDs = array_keys($arSKUPropList);
	}
}

$arResult['CHECK_QUANTITY'] = false;
if (!isset($arResult['CATALOG_MEASURE_RATIO']))
	$arResult['CATALOG_MEASURE_RATIO'] = 1;
if (!isset($arResult['CATALOG_QUANTITY']))
	$arResult['CATALOG_QUANTITY'] = 0;
$arResult['CATALOG_QUANTITY'] = (
	0 < $arResult['CATALOG_QUANTITY'] && is_float($arResult['CATALOG_MEASURE_RATIO'])
	? (float)$arResult['CATALOG_QUANTITY']
	: (int)$arResult['CATALOG_QUANTITY']
);
$arResult['CATALOG'] = false;
if (!isset($arResult['CATALOG_SUBSCRIPTION']) || 'Y' != $arResult['CATALOG_SUBSCRIPTION'])
	$arResult['CATALOG_SUBSCRIPTION'] = 'N';

CIBlockPriceTools::getLabel($arResult, $arParams['LABEL_PROP']);

$productSlider = CRZBitronic2CatalogUtils::getElementPictureArray($arResult);
if (empty($productSlider))
{
	$productSlider = array(
		0 => $arEmptyPreview
	);
}
else
{
	foreach($productSlider as &$photoId)
	{
		$photoId = CFile::GetFileArray($photoId);
	}
	unset($photoId);
}
$arResult['SHOW_SLIDER'] = true;
$arResult['MORE_PHOTO'] = $productSlider;


$arResult['MORE_PHOTO_COUNT'] = count($productSlider);
foreach ($arResult['MORE_PHOTO'] as &$arPhoto) {
	$arPhoto['SRC_ICON'] = CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_ICON']);
	$arPhoto['SRC_SMALL'] = CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_SMALL']);
	$arPhoto['SRC_BIG'] = CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_BIG']);
    $arPhoto['SRC_TABLE'] = CRZBitronic2CatalogUtils::GetResizedImg($arPhoto['SRC'], array('SET_ID' => $arParams["RESIZER_SETS"]['RESIZER_DETAIL_SKU_TABLE'], 'WIDTH' => 80, 'HEIGHT' => 60));
}
unset($arPhoto);

if (is_array($arResult["PROPERTIES"][$arParams['DETAIL_PROP_FOR_VIDEO_IN_SLIDER']]['VALUE'])){
    $arResult['MORE_PHOTO']['VIDEO'] = $arResult["PROPERTIES"][$arParams['DETAIL_PROP_FOR_VIDEO_IN_SLIDER']]['VALUE'];
    $arResult['MORE_PHOTO_COUNT']++;
}

if ($arResult['MODULES']['catalog'])
{
	$arResult['CATALOG'] = true;
	if (!isset($arResult['CATALOG_TYPE']))
		$arResult['CATALOG_TYPE'] = CCatalogProduct::TYPE_PRODUCT;
	if (
		(CCatalogProduct::TYPE_PRODUCT == $arResult['CATALOG_TYPE'] || CCatalogProduct::TYPE_SKU == $arResult['CATALOG_TYPE'])
		&& !empty($arResult['OFFERS'])
	)
	{
		$arResult['CATALOG_TYPE'] = CCatalogProduct::TYPE_SKU;
	}
	switch ($arResult['CATALOG_TYPE'])
	{
		case CCatalogProduct::TYPE_SKU:
			break;
		case CCatalogProduct::TYPE_SET:
			$arResult['OFFERS'] = array();
            $arSets = CCatalogProductSet::getAllSetsByProduct($arResult["ID"], CCatalogProductSet::TYPE_SET);

            $arSetItems = array();
            foreach($arSets as $arSet)
            {
                foreach($arSet["ITEMS"] as $arSetItem)
                {
                    $arSetItems[] = $arSetItem["ITEM_ID"];
                }
            }
            $arResult['ITEMS_IN_SET'] = $arSetItems;
			//no break
		case CCatalogProduct::TYPE_PRODUCT:
		default:
			$arResult['CHECK_QUANTITY'] = ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO']);
			$arResult['FOR_ORDER']      = ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'Y' == $arResult['CATALOG_CAN_BUY_ZERO'] && 0 >= $arResult['CATALOG_QUANTITY']);
			break;
	}
}
else
{
	$arResult['CATALOG_TYPE'] = 0;
	$arResult['OFFERS'] = array();
}

if ($arResult['CATALOG'] && isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	$arResult['bOffers'] = true;
	$arResult['bOffersNotEqual'] = false;
	$arResult['OFFERS_DISPLAY_PROPS'] = array();
	$boolSKUDisplayProps = false;

	CRZBitronic2CatalogUtils::fillSKUMultiPrice($arResult, $arResult['CAT_PRICES']);

	$arResultSKUPropIDs = array();
	$arFilterProp = array();
	$arNeedValues = array();
	foreach ($arResult['OFFERS'] as $keyOffer=> &$arOffer)
	{
		foreach ($arSKUPropIDs as &$strOneCode)
		{
			if (isset($arOffer['DISPLAY_PROPERTIES'][$strOneCode]))
			{
				$arResultSKUPropIDs[$strOneCode] = true;
				if (!isset($arNeedValues[$arSKUPropList[$strOneCode]['ID']]))
					$arNeedValues[$arSKUPropList[$strOneCode]['ID']] = array();
				$valueId = (
					$arSKUPropList[$strOneCode]['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST
					? $arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE_ENUM_ID']
					: $arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']
				);
				$arNeedValues[$arSKUPropList[$strOneCode]['ID']][$valueId] = $valueId;
				unset($valueId);
				if (!isset($arFilterProp[$strOneCode])) {
					$arFilterProp[$strOneCode] = $arSKUPropList[$strOneCode];
				}
				if (
					$arSKUPropList[$strOneCode]['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST &&
					$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['LIST_TYPE'] == Iblock\PropertyTable::CHECKBOX
				) {
					$arSKUPropList[$strOneCode]['SHOW_MODE'] = 'BOX';
				}
			}
		}
		unset($strOneCode);

		$arOffer['FOR_ORDER'] = ('Y' == $arOffer['CATALOG_QUANTITY_TRACE'] && 'Y' == $arOffer['CATALOG_CAN_BUY_ZERO'] && 0 >= $arOffer['CATALOG_QUANTITY']);
		$arOffer['ON_REQUEST'] = (empty($arOffer['MIN_PRICE']) || $arOffer['MIN_PRICE']['VALUE'] <= 0);
		if ($arOffer['ON_REQUEST']) {
			$arOffer['CAN_BUY'] = false;
		}

        if (CRZBitronic2CatalogUtils::checkAvPrFotoForElement($arOffer, $arParams)){
            unset ($arResult['OFFERS'][$keyOffer]);
            continue;
        }
	}
	unset($arOffer);

	CIBlockPriceTools::getTreePropertyValues($arSKUPropList, $arNeedValues);
	$arSKUPropIDs = array_keys($arSKUPropList);
	$arSKUPropKeys = array_fill_keys($arSKUPropIDs, false);


	$arMatrixFields = $arSKUPropKeys;
	$arMatrix = array();

	$arNewOffers = array();

	$arIDS = array($arResult['ID']);
	$arOfferSet = array();
	$arResult['OFFER_GROUP'] = false;
	$arResult['OFFERS_PROP'] = false;

	$arDouble = array();
	foreach ($arResult['OFFERS'] as $keyOffer => $arOffer)
	{
		$arOffer['ID'] = (int)$arOffer['ID'];
		if (isset($arDouble[$arOffer['ID']]))
			continue;
		$arIDS[] = $arOffer['ID'];
		$boolSKUDisplayProperties = false;
		$arOffer['OFFER_GROUP'] = false;
		$arRow = array();
		foreach ($arSKUPropIDs as $propkey => $strOneCode)
		{
			$arCell = array(
				'VALUE' => 0,
				'SORT' => PHP_INT_MAX,
				'NA' => true
			);
			if (isset($arOffer['DISPLAY_PROPERTIES'][$strOneCode]))
			{
				$arMatrixFields[$strOneCode] = true;
				$arCell['NA'] = false;
				if ('directory' == $arSKUPropList[$strOneCode]['USER_TYPE'])
				{
					$intValue = $arSKUPropList[$strOneCode]['XML_MAP'][$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE']];
					$arCell['VALUE'] = $intValue;
				}
				elseif ('L' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE'])
				{
					$arCell['VALUE'] = (int)$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE_ENUM_ID'];
				}
				elseif ('E' == $arSKUPropList[$strOneCode]['PROPERTY_TYPE'])
				{
					$arCell['VALUE'] = (int)$arOffer['DISPLAY_PROPERTIES'][$strOneCode]['VALUE'];
				}
				$arCell['SORT'] = $arSKUPropList[$strOneCode]['VALUES'][$arCell['VALUE']]['SORT'];
			}
			$arRow[$strOneCode] = $arCell;
		}
		$arMatrix[$keyOffer] = $arRow;

		CIBlockPriceTools::setRatioMinPrice($arOffer, false);

		if ($arParams['VIEW_SKU_EXTEND'] && CIBlockPriceTools::clearProperties($arOffer['DISPLAY_PROPERTIES'], $arParams['OFFER_TREE_PROPS']))
		{
			$boolSKUDisplayProps = true;
		}

		$arDouble[$arOffer['ID']] = true;
		$arNewOffers[$keyOffer] = $arOffer;
	}

	$arResult['OFFERS'] = $arNewOffers;
	$arResult['SHOW_OFFERS_PROPS'] = $boolSKUDisplayProps;

	if ($arParams['SHOW_CATCHBUY']) {
		CRZBitronic2CatalogUtils::getCatchbuyInfoList($arResult['OFFERS']);
	}

	$arUsedFields = array();
	$arSortFields = array();

	foreach ($arSKUPropIDs as $propkey => $strOneCode)
	{
		$boolExist = $arMatrixFields[$strOneCode];
		foreach ($arMatrix as $keyOffer => $arRow)
		{
			if ($boolExist)
			{
				if (!isset($arResult['OFFERS'][$keyOffer]['TREE']))
					$arResult['OFFERS'][$keyOffer]['TREE'] = array();
				$arResult['OFFERS'][$keyOffer]['TREE']['PROP_'.$arSKUPropList[$strOneCode]['ID']] = $arMatrix[$keyOffer][$strOneCode]['VALUE'];
				$arResult['OFFERS'][$keyOffer]['SKU_SORT_'.$strOneCode] = $arMatrix[$keyOffer][$strOneCode]['SORT'];
				$arUsedFields[$strOneCode] = true;
				$arSortFields['SKU_SORT_'.$strOneCode] = SORT_NUMERIC;
			}
			else
			{
				unset($arMatrix[$keyOffer][$strOneCode]);
			}
		}
	}
	$arResult['OFFERS_PROP'] = $arUsedFields;
	$arResult['OFFERS_PROP_CODES'] = (!empty($arUsedFields) ? base64_encode(serialize(array_keys($arUsedFields))) : '');


	$arResult['bSkuExt'] = $arParams['PRODUCT_DISPLAY_MODE_CUSTOM'] == 'Y' && !empty($arResult['OFFERS_PROP']);
	$arResult['bSkuSimple'] = !$arResult['bSkuExt'];

	if($arResult['bSkuExt'])
	{
		Collection::sortByColumn($arResult['OFFERS'], $arSortFields);

		$productSlider = CRZBitronic2CatalogUtils::getElementPictureArray($arResult, 'MORE_PHOTO', false);
		if (!empty($productSlider))
		{
			foreach($productSlider as &$photoId)
			{
				$photoId = CFile::GetFileArray($photoId);
				$photoId['SRC_ICON'] = CResizer2Resize::ResizeGD2($photoId['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_ICON']);
				$photoId['SRC_SMALL'] = CResizer2Resize::ResizeGD2($photoId['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_SMALL']);
				$photoId['SRC_BIG'] = CResizer2Resize::ResizeGD2($photoId['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_BIG']);
			}
			unset($photoId);
		}
		$arResult['MORE_PHOTO'] = $productSlider;
		$arResult['MORE_PHOTO_COUNT'] = count($productSlider);

        if (is_array($arResult["PROPERTIES"][$arParams['DETAIL_PROP_FOR_VIDEO_IN_SLIDER']]['VALUE'])){
            $arResult['MORE_PHOTO']['VIDEO'] = $arResult["PROPERTIES"][$arParams['DETAIL_PROP_FOR_VIDEO_IN_SLIDER']]['VALUE'];
            $arResult['MORE_PHOTO_COUNT']++;
        }
	}
	foreach ($arResult['OFFERS'] as $keyOffer => &$arOffer) {
		$arOffer['MORE_PHOTO'] = array();
		$arOffer['MORE_PHOTO_COUNT'] = 0;
		$offerSlider = CRZBitronic2CatalogUtils::getElementPictureArray($arOffer);
		if (empty($offerSlider))
		{
			$offerSlider = array(
				0 => $arEmptyPreview
			);
		}
		else
		{
			foreach($offerSlider as &$photoId)
			{
				$photoId = CFile::GetFileArray($photoId);
			}
			unset($photoId);
		}
		$arOffer['SHOW_SLIDER'] = true;
		foreach($offerSlider as &$arPhoto) {
			$arPhoto['SRC_ICON'] = CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_ICON']);
			$arPhoto['SRC_SMALL'] = CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_SMALL']);
			$arPhoto['SRC_BIG'] = CResizer2Resize::ResizeGD2($arPhoto['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_BIG']);
			$arPhoto['SRC_TABLE'] = CRZBitronic2CatalogUtils::GetResizedImg($arPhoto['SRC'], array('SET_ID' => $arParams["RESIZER_SETS"]['RESIZER_DETAIL_SKU_TABLE'], 'WIDTH' => 80, 'HEIGHT' => 60));
			$arOffer['MORE_PHOTO'][md5($arPhoto['SRC'])] = $arPhoto;
			
		} unset($arPhoto, $offerSlider);
		if ($arParams['ADD_PARENT_PHOTO'] == 'Y') {
			foreach ($arResult['MORE_PHOTO'] as $keyMorePhotoItem => &$arResPhoto) {
				$resHash = md5($arResPhoto['SRC']);
				if (!isset($arOffer['MORE_PHOTO'][$resHash]) && $keyMorePhotoItem !== 'VIDEO') {
					$arOffer['MORE_PHOTO'][$resHash] = $arResPhoto;
				} elseif ($keyMorePhotoItem === 'VIDEO') {
				    foreach ($arResPhoto as $video) {
                        $arOffer['MORE_PHOTO']['VIDEO'][] = $video;
                    }
                }
			}
			unset($arResPhoto);
		}
		$arOffer['MORE_PHOTO_COUNT'] = count($arOffer['MORE_PHOTO']);

        if (is_array($arOffer["PROPERTIES"][$arParams['DETAIL_PROP_FOR_VIDEO_IN_SLIDER']]['VALUE'])){
            $arOffer['MORE_PHOTO']['VIDEO'] = $arOffer["PROPERTIES"][$arParams['DETAIL_PROP_FOR_VIDEO_IN_SLIDER']]['VALUE'];
            $arOffer['MORE_PHOTO_COUNT']++;
        }
	}
	unset($arOffer);

	$offerSet = array();
	if (!empty($arIDS) && CBXFeatures::IsFeatureEnabled('CatCompleteSet'))
	{
		$offerSet = array_fill_keys($arIDS, false);
		$rsSets = CCatalogProductSet::getList(
			array(),
			array(
				'@OWNER_ID' => $arIDS,
				'=SET_ID' => 0,
				'=TYPE' => CCatalogProductSet::TYPE_GROUP
			),
			false,
			false,
			array('ID', 'OWNER_ID')
		);
		while ($arSet = $rsSets->Fetch())
		{
			$arSet['OWNER_ID'] = (int)$arSet['OWNER_ID'];
			$offerSet[$arSet['OWNER_ID']] = true;
			$arResult['OFFER_GROUP'] = true;
		}
		if ($offerSet[$arResult['ID']])
		{
			foreach ($offerSet as &$setOfferValue)
			{
				if ($setOfferValue === false)
				{
					$setOfferValue = true;
				}
			}
			unset($setOfferValue);
			unset($offerSet[$arResult['ID']]);
		}
		if ($arResult['OFFER_GROUP'])
		{
			$offerSet = array_filter($offerSet);
			$arResult['OFFER_GROUP_VALUES'] = array_keys($offerSet);
		}
	}

	$arMatrix = array();
	$intSelected = -1;
	//$arResult['MIN_PRICE'] = false;
	$arResult['MIN_BASIS_PRICE'] = false;
	foreach ($arResult['OFFERS'] as $keyOffer => $arOffer)
	{
		if (-1 == $intSelected)
		{
			if ($arResult['OFFER_ID_SELECTED'] > 0) {
                $foundOffer = ($arResult['OFFER_ID_SELECTED'] == $arOffer['ID']);
            }
			elseif (!empty($_GET[$arParams['OFFER_VAR_NAME']])){
                $foundOffer = $arOffer['ID'] == $_GET[$arParams['OFFER_VAR_NAME']];
            } else {
                $foundOffer = ($arOffer['CAN_BUY'] === true || $arOffer['CAN_BUY'] === 'Y');
            }
			if ($foundOffer)
			{
				$intSelected = $keyOffer;
				$arResult['MIN_PRICE'] = (isset($arOffer['RATIO_PRICE']) ? $arOffer['RATIO_PRICE'] : $arOffer['MIN_PRICE']);
				$arResult['MIN_BASIS_PRICE'] = $arOffer['MIN_PRICE'];
			}
			unset($foundOffer);
		}
		$arSKUProps = false;
		if (!empty($arOffer['DISPLAY_PROPERTIES']))
		{
			$boolSKUDisplayProps = true;
			$arSKUProps = array();
			foreach ($arOffer['DISPLAY_PROPERTIES'] as &$arOneProp)
			{
				// for skuSimple
				if(!in_array($arOneProp['CODE'], $arResult['OFFERS_DISPLAY_PROPS']))
				{
					$arResult['OFFERS_DISPLAY_PROPS'][$arOneProp['CODE']] = $arOneProp['NAME'];
				}

				if ('F' == $arOneProp['PROPERTY_TYPE'])
					continue;
				$arSKUProps[] = array(
					'NAME' => $arOneProp['NAME'],
					'VALUE' => $arOneProp['DISPLAY_VALUE']
				);
			}
			unset($arOneProp);
		}
		if (isset($arOfferSet[$arOffer['ID']]))
		{
			$arOffer['OFFER_GROUP'] = true;
			$arResult['OFFERS'][$keyOffer]['OFFER_GROUP'] = true;
		}
		if(!empty($arOffer['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])) {
			$arOffer['ARTICUL'] = is_array($arOffer['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])
			                    ? implode(' / ', $arOffer['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])
			                    : $arOffer['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'];
		}
		reset($arOffer['MORE_PHOTO']);
		$firstPhotoOriginal = current($arOffer['MORE_PHOTO']);
		$firstPhoto['SRC'] = CResizer2Resize::ResizeGD2($firstPhotoOriginal['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_SMALL']);
		$firstPhoto['SRC_BIG'] = CResizer2Resize::ResizeGD2($firstPhotoOriginal['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_BIG']);
		$firstPhoto['SRC_ICON'] = CResizer2Resize::ResizeGD2($firstPhotoOriginal['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_ICON']);
		$firstPhoto['SRC_FLY'] = CResizer2Resize::ResizeGD2($firstPhotoOriginal['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_FLY_BLOCK']);
		$arResult['OFFERS'][$keyOffer]['PHOTO'] = $firstPhoto;

		$arOffer['MIN_BUY_PRICE']['DISCOUNT_VALUE'] = INF;
		foreach ($arOffer['PRICES'] as $priceCode => $arPrice) {
			if ($arPrice['CAN_BUY'] !== 'Y') continue;
			if ($arOffer['MIN_BUY_PRICE']['DISCOUNT_VALUE'] < $arPrice['DISCOUNT_VALUE'] ) continue;

			$arOffer['MIN_BUY_PRICE'] = $arPrice;
		}
		// PRICE MATRIX
		$arOffer['PRICE_MATRIX'] = false;
		if ($arParams["USE_PRICE_COUNT_"] && CRZBitronic2Settings::isPro() && is_array($arOffer['MIN_PRICE'])) {
			$arOffer["PRICE_MATRIX"] = CRZBitronic2CatalogUtils::getPriceMatrix($arOffer["ID"], $arOffer['MIN_PRICE']['PRICE_ID'], $arResult['CONVERT_CURRENCY']);
		}
		$arOneRow = array(
			'ID' => $arOffer['ID'],
			'URL' => str_replace('&amp;', '&', $arOffer['DETAIL_PAGE_URL']),
			'NAME' => $arOffer['~NAME'],
			'TREE' => $arOffer['TREE'],
			'ARTICUL' => $arOffer['ARTICUL'],
			'PRICE' => $arOffer['MIN_PRICE'],
			'BASIS_PRICE' => $arOffer['MIN_PRICE'],
			'BUY_PRICE' => $arOffer['MIN_BUY_PRICE'],
			'PRICE_MATRIX' => $arOffer['PRICE_MATRIX'],
			'DISPLAY_PROPERTIES' => $arSKUProps,
			'PREVIEW_PICTURE' => $firstPhoto,
			'PICTURE_PRINT' => $firstPhoto,
			'FOR_ORDER' => $arOffer['FOR_ORDER'],
			'ON_REQUEST' => $arOffer['ON_REQUEST'],
			'CHECK_QUANTITY' => $arOffer['CHECK_QUANTITY'],
			'MAX_QUANTITY' => $arOffer['CATALOG_QUANTITY'],
			'STEP_QUANTITY' => $arOffer['CATALOG_MEASURE_RATIO'],
			'QUANTITY_FLOAT' => is_double($arOffer['CATALOG_MEASURE_RATIO']),
			'MEASURE' => $arOffer['~CATALOG_MEASURE_NAME'],
			'OFFER_GROUP' => (isset($offerSet[$arOffer['ID']]) && $offerSet[$arOffer['ID']]),
			'CAN_BUY' => $arOffer['CAN_BUY'],
			'SLIDER' => $arOffer['MORE_PHOTO'],
			'SLIDER_COUNT' => $arOffer['MORE_PHOTO_COUNT'],
			'PRICES' => $arOffer['PRICES']
		);
		unset($arPrice, $priceCode);
		if ($arParams['VBC_BONUS']) {
			$arOneRow['BONUS_PRICE'] = array(
				$arOffer['ID'] => array(
					'VALUE' => $arOffer['MIN_BUY_PRICE']['VALUE'],
					'DISCOUNT_VALUE' => $arOffer['MIN_BUY_PRICE']['DISCOUNT_VALUE'],
					'CURRENCY' => $arOffer['MIN_BUY_PRICE']['CURRENCY'],
				)
			);
			$arOneRow['BONUS_PRICE'] = base64_encode(serialize($arOneRow['BONUS_PRICE']));
		}
		$arMatrix[$keyOffer] = $arOneRow;
	}
	if (-1 == $intSelected)
		$intSelected = 0;
	$arResult['JS_OFFERS'] = $arMatrix;
	$arResult['OFFERS_SELECTED'] = $intSelected;

	$arResult['OFFERS_IBLOCK'] = $arSKU['IBLOCK_ID'];
}

if ($arResult['MODULES']['catalog'] && $arResult['CATALOG'])
{
	if($arResult['bSkuSimple'])
	{
		CRZBitronic2CatalogUtils::fillMinPriceFromOffers(
			$arResult,
			$boolConvert ? $arResult['CONVERT_CURRENCY']['CURRENCY_ID'] : $strBaseCurrency,
			$bForOrder = false
		);
	}
	if ($arResult['CATALOG_TYPE'] == CCatalogProduct::TYPE_PRODUCT || $arResult['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET)
	{
		CIBlockPriceTools::setRatioMinPrice($arResult, false);
		$arResult['MIN_BASIS_PRICE'] = $arResult['MIN_PRICE'];
	}
	if (
		CBXFeatures::IsFeatureEnabled('CatCompleteSet')
		&& (
			$arResult['CATALOG_TYPE'] == CCatalogProduct::TYPE_PRODUCT
			|| $arResult['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET
		)
	)
	{
		$rsSets = CCatalogProductSet::getList(
			array(),
			array(
				'@OWNER_ID' => $arResult['ID'],
				'=SET_ID' => 0,
				'=TYPE' => CCatalogProductSet::TYPE_GROUP
			),
			false,
			false,
			array('ID', 'OWNER_ID')
		);
		if ($arSet = $rsSets->Fetch())
		{
			$arResult['OFFER_GROUP'] = true;
		}
	}
	if ($arResult['MIN_PRICE']['CAN_BUY'] === 'Y') {
		$arResult['MIN_BUY_PRICE'] = $arResult['MIN_PRICE'];
	} else {
		$arResult['MIN_BUY_PRICE']['DISCOUNT_VALUE'] = INF;
		foreach ($arResult['PRICES'] as $key => $arPrice) {
			if ($arPrice['CAN_BUY'] !== 'Y') continue;
			if ($arResult['MIN_BUY_PRICE']['DISCOUNT_VALUE'] < $arPrice['DISCOUNT_VALUE'] ) continue;

			$arResult['MIN_BUY_PRICE'] = $arPrice;
		}
	}
}

if (!empty($arResult['DISPLAY_PROPERTIES']))
{
	foreach ($arResult['DISPLAY_PROPERTIES'] as $propKey => $arDispProp)
	{
		if ('F' == $arDispProp['PROPERTY_TYPE'])
			unset($arResult['DISPLAY_PROPERTIES'][$propKey]);
	}
}

$arResult['SKU_PROPS'] = $arSKUPropList;
$arResult['DEFAULT_PICTURE'] = $arEmptyPreview;

$arResult['CURRENCIES'] = array();
if ($arResult['MODULES']['currency'])
{
	if ($boolConvert)
	{
		$arResult['CURRENCIES'] = CRZBitronic2CatalogUtils::getCurrencyArray($arResult['CONVERT_CURRENCY']['CURRENCY_ID']);
	}
	else
	{
		$arResult['CURRENCIES'] = CRZBitronic2CatalogUtils::getCurrencyArray();
	}
}

if($arResult['bSkuExt'])
{

	if(empty($arResult['MIN_PRICE']))
	{
		$arResult['MIN_PRICE']['CURRENCY'] = ($arParams['CONVERT_CURRENCY'] = 'Y') ? $arParams['CURRENCY_ID'] : $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['MIN_PRICE']['CURRENCY'];
	}
}

// ===== TAGS =====
if (!empty($arResult['TAGS'])) {
	$arResult['TAGS'] = explode(',', $arResult['TAGS']);
	foreach ($arResult['TAGS'] as &$tag) {
		$tag = trim($tag);
	}
	if (isset($tag)) unset($tag);
}

// =========== PROPERTIES WITH LINKS ===========
foreach ($arResult['DISPLAY_PROPERTIES'] as $code => $arProp) {
	if (!is_array($arProp['DISPLAY_VALUE'])) {
		$arProp['DISPLAY_VALUE'] = array($arProp['DISPLAY_VALUE']);
	}
	foreach ($arProp['DISPLAY_VALUE'] as &$value) {
		if (strpos($value, '<a ') === false) continue;
		$value = preg_replace('#<a ([^>]+)>([^<]*)</a>#', '<a class="link" $1><span class="text">$2</span></a>', $value);
	}
	if (isset($value)) {
		unset($value);
	}
	if (count($arProp['DISPLAY_VALUE']) == 1) {
		$arProp['DISPLAY_VALUE'] = $arProp['DISPLAY_VALUE'][0];
	}
	$arResult['DISPLAY_PROPERTIES'][$code] = $arProp;
}

// =========== SMART FILTER LINKS ==============
$smartFilterURLTemplate = CIBlock::ReplaceDetailUrl($arParams['SEF_FILTER_RULE'], $arResult['SECTION'], $server = false, $type = 'S');
$file = $_SERVER['DOCUMENT_ROOT'] . BX_ROOT . '/components/bitrix/catalog.smart.filter/class.php';
if (file_exists($file)) {
	include_once $file;
	$smartFilterInstalled = true;
	$smartFilter = new CBitrixCatalogSmartFilter();

	$smartFilter->IBLOCK_ID = $arParams['IBLOCK_ID'];
	$smartFilter->SKU_IBLOCK_ID = $boolSKU ? $arSKU['IBLOCK_ID'] : 0;
	$smartFilter->SECTION_ID = $arResult['IBLOCK_SECTION_ID'];
	$smartFilter->FILTER_NAME = $arParams['FILTER_NAME'];
	$smartFilter->SAFE_FILTER_NAME = htmlspecialcharsBx($smartFilter->FILTER_NAME);
	$smartFilter->arResult = array();
	$smartFilter->arResult['ITEMS'] = $smartFilter->getResultItems();

	$bHiddenBrand = false;

	if ('Y' == $arParams['BRAND_USE']
	&& !array_key_exists($arParams['BRAND_PROP_CODE'], $arResult['DISPLAY_PROPERTIES'])
	&&  array_key_exists($arParams['BRAND_PROP_CODE'], $arResult['PROPERTIES'])
	&&  array_key_exists($arResult['PROPERTIES'][$arParams['BRAND_PROP_CODE']]['ID'], $smartFilter->arResult['ITEMS'])
	) {
		$arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROP_CODE']] = $arResult['PROPERTIES'][$arParams['BRAND_PROP_CODE']];
		$arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROP_CODE']]['DISPLAY_VALUE'] = $arResult['PROPERTIES'][$arParams['BRAND_PROP_CODE']]['VALUE'];
		$bHiddenBrand = true;
	}

	foreach ($arResult['DISPLAY_PROPERTIES'] as $propKey => $arProp) {
		if (!array_key_exists($arProp['ID'], $smartFilter->arResult['ITEMS'])) continue;

		if (isset($arProp['VALUE'])         && !is_array($arProp['VALUE']))         $arProp['VALUE']         = array($arProp['VALUE']);
		if (isset($arProp['VALUE_ENUM_ID']) && !is_array($arProp['VALUE_ENUM_ID'])) $arProp['VALUE_ENUM_ID'] = array($arProp['VALUE_ENUM_ID']);
		if (isset($arProp['DISPLAY_VALUE']) && !is_array($arProp['DISPLAY_VALUE'])) $arProp['DISPLAY_VALUE'] = array($arProp['DISPLAY_VALUE']);

		$filterProp = &$smartFilter->arResult['ITEMS'][$arProp['ID']];

		foreach ($arProp['DISPLAY_VALUE'] as $valueKey => $displayValue) {
			if (strpos($displayValue, '<a class="link"') !== false) continue;
			$fillValue = isset($arProp['VALUE_ENUM_ID']) ? $arProp['VALUE_ENUM_ID'][$valueKey] : $arProp['VALUE'][$valueKey];
			$smartFilter->fillItemValues($smartFilter->arResult['ITEMS'][$arProp['ID']], $fillValue);

			if ($arParams['SEF_MODE'] == 'Y') {
				if ($filterProp['PROPERTY_TYPE'] == 'N' || $filterProp['DISPLAY_TYPE'] == 'U') {
					if ($filterProp["VALUES"]["MIN"]["VALUE"])
						$filterProp["VALUES"]["MIN"]["HTML_VALUE"] = $filterProp["VALUES"]["MIN"]["VALUE"];
					if ($filterProp["VALUES"]["MAX"]["VALUE"])
						$filterProp["VALUES"]["MAX"]["HTML_VALUE"] = $filterProp["VALUES"]["MAX"]["VALUE"];
				} else {
					foreach ($filterProp['VALUES'] as $filterPropKey => $value) {
						$filterProp['VALUES'][$filterPropKey]['CHECKED'] = true;
					}
				}
				$filterURL = $smartFilter->makeSmartUrl($smartFilterURLTemplate, true);
			} else {
				$paramsToAdd = array(
					"set_filter" => "y",
				);
				foreach($filterProp["VALUES"] as $filterPropKey => $ar)
				{
					if ($filterProp['PROPERTY_TYPE'] == 'N' || $filterProp['DISPLAY_TYPE'] == 'U') {
						$paramsToAdd[$ar['CONTROL_NAME']] = $ar['VALUE'];
					} else {
						$paramsToAdd[$ar['CONTROL_NAME']] = $ar['HTML_VALUE'];
					}
				}

				$filterURL = htmlspecialcharsbx(CHTTP::urlAddParams($arResult['SECTION']['SECTION_PAGE_URL'], $paramsToAdd, array(
					"skip_empty" => true,
					"encode" => true,
				)));
			}
			if ('Y' == $arParams['BRAND_USE'] && !empty($arParams['BRAND_PROP_CODE']) && $filterProp['CODE'] == $arParams['BRAND_PROP_CODE']) {
				//FILL BRANDS IMAGE AND LINK
				foreach ($filterProp['VALUES'] as $arValue) {
					$arResult['BRAND_LOGO'] = array(
						'ALT' => $arValue['VALUE'],
						'IMG' => CFile::ResizeImageGet($arValue["FILE"], array("width"=>120, "height"=>60)),
						'URL' => $filterURL
						);
					break;
				}
				if ($arParams['BRAND_EXT'] != 'N') {
					$hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(
						array(
							"filter" => array(
								'TABLE_NAME' => $arProp['USER_TYPE_SETTINGS']['TABLE_NAME']
							)
						)
					)->fetch();
					$entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
					$entityDataClass = $entity->getDataClass();
					$arFilter = array('select' => array('ID', 'UF_XML_ID'), 'filter' => array('UF_XML_ID' => $arProp['~VALUE']));
					$arHLElement = $entityDataClass::getList($arFilter)->Fetch();
					if($arHLElement) {
						$arResult['BRAND_LOGO']['URL'] = str_replace(
							array('#ID#', '#XML_ID#'),
							array($arHLElement['ID'], $arHLElement['UF_XML_ID']),
							$arParams['BRAND_DETAIL']
						);
					}
				}
				if ($bHiddenBrand) {
					unset($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROP_CODE']]);
					$filterProp['VALUES'] = array();
					continue 2;
				}
			}
			foreach ($filterProp['VALUES'] as $arValue) {
				if (empty($arValue['FILE'])) {
					$arProp['DISPLAY_VALUE'][$valueKey] = '<a href="' . $filterURL . '" class="link" rel="nofollow"><span class="text">' . $displayValue . '</span></a>';
					continue;
				}
				$imgSrc = CResizer2Resize::ResizeGD2($arValue['FILE']['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_PROP']);
				$displayValue = strip_tags($displayValue);
				$arProp['DISPLAY_VALUE'][$valueKey] = '<a href="' . $filterURL . '" class="link prop-w-img" rel="nofollow" data-tooltip data-container="#characteristics" title="' . $displayValue . '"><img src="' . $imgSrc . '" alt="' . $displayValue . '" class="property-img"><span class="text"></span></a>';
				break;
			}
			$filterProp['VALUES'] = array();
		}
		$arResult['DISPLAY_PROPERTIES'][$propKey]['DISPLAY_VALUE'] = (count($arProp['DISPLAY_VALUE']) > 1) ? $arProp['DISPLAY_VALUE'] : $arProp['DISPLAY_VALUE'][0];
		$arResult['DISPLAY_PROPERTIES'][$propKey]['SMART_FILTER'] = 'Y';
	}
	if ($arParams['HIDE_SIMILAR_PRICE'] !== 'Y' && $arParams['SIMILAR_PRICE_SMART_FILTER'] !== 'N') {
		$arResult['smartFilterItems'] = $smartFilter->arResult['ITEMS'];
	}
}
// =============================================


// ====== GET IMAGES FROM DIRECTORY PROPS ======
$arUserType = CIBlockProperty::GetUserType('directory');
foreach ($arResult['DISPLAY_PROPERTIES'] as &$arProp) {
	if ($arProp['USER_TYPE'] !== 'directory') continue;
	if ($arProp['SMART_FILTER'] === 'Y') continue;

	//fill images
	$arProp['DISPLAY_VALUE'] = !is_array($arProp['DISPLAY_VALUE']) ? array($arProp['DISPLAY_VALUE']) : $arProp['DISPLAY_VALUE'];
	$arProp['VALUE'] = !is_array($arProp['VALUE']) ? array($arProp['VALUE']) : $arProp['VALUE'];
	
	foreach ($arProp['VALUE'] as $key => $value) {
		$arExtended = call_user_func_array(
			$arUserType["GetExtendedValue"],
			array(
				$arProp,
				array("VALUE" => $value)
			)
		);
		if (empty($arExtended['FILE_ID'])) continue;
		$arFile = CFile::GetFileArray($arExtended['FILE_ID']);
		$imgSrc = CResizer2Resize::ResizeGD2($arFile['SRC'], $arParams["RESIZER_SETS"]['RESIZER_DETAIL_PROP']);
		$value = strip_tags($arProp['DISPLAY_VALUE'][$key]);
		$arProp['DISPLAY_VALUE'][$key] = '<span class="prop-w-img" data-tooltip data-container="#characteristics" title="' . $value . '"><img src="' . $imgSrc . '" alt="' . $value . '" class="property-img"></span>';
	}
	if (count($arProp['VALUE']) == 1) {
		$arProp['VALUE'] = $arProp['VALUE'][0];
		$arProp['DISPLAY_VALUE'] = $arProp['DISPLAY_VALUE'][0];
	}
}
if (isset($arProp)) {
	unset($arProp);
}
// =============================================

if (CModule::IncludeModule('yenisite.bitronic2lite') && CModule::IncludeModule('yenisite.market')) {
	$prices = CMarketPrice::GetItemPriceValues($arResult["ID"], $arResult['PRICES']);
	if(count($prices)>0)
		unset ($arResult["PRICES"]);
	$minPrice = false;
	foreach ($prices as $k => $pr) {
		$pr = floatval($pr);
		$arResult["PRICES"][$k]["VALUE"] = $pr;
		$arResult["PRICES"][$k]["PRINT_VALUE"] = $pr;
		if ((empty($minPrice) || $minPrice > $pr) && $pr > 0) {
			$minPrice = $pr;
			$minPriceId = $k;
		}
	}
	if ($minPrice !== false) {
		$arResult['MIN_PRICE']['PRICE_ID'] = $minPriceId;
		$arResult['MIN_PRICE']['VALUE'] = $minPrice;
		$arResult['MIN_PRICE']['DISCOUNT_VALUE'] = $minPrice;
		$arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE'] = $minPrice;
		$arResult['CAN_BUY'] = true;
	}
	$arResult['CHECK_QUANTITY'] = (CMarketCatalog::UsesQuantity($arParams['IBLOCK_ID']) == 1);
	$arResult['CATALOG_QUANTITY'] = intval($arResult['PROPERTIES']['MARKET_QUANTITY']['VALUE']);

	if ($arResult['CHECK_QUANTITY'] && $arResult['CATALOG_QUANTITY'] <= 0) {
		$arResult['CAN_BUY'] = false;
	}
	$arResult['CATALOG_WEIGHT'] = $arResult['PROPERTIES']['CATALOG_WEIGHT']['VALUE'];
	$arResult['CATALOG_TYPE'] = 1; //simple product
}

if (CRZBitronic2Settings::getEdition() != 'LITE' && $arParams['USE_ACTIONS_FUNCTIONAL'] && empty($_POST['rz_quick_view'])) {
    $arResult['ACTION_DATA'] = CRZBitronic2CatalogUtils::getActionsOfElement($arResult['ID'], $arResult['IBLOCK_SECTION_ID'], $arResult['IBLOCK_ID'], $arParams['RESIZER_SETS']['RESIZER_BANNER_ACTION'], $arParams);
}

ob_start();
$arResult['STICKERS'] = $APPLICATION->IncludeComponent(
	"yenisite:stickers",
	"section",
	array(
		"ELEMENT" => $arResult,
		"STICKER_NEW" => $arParams['STICKER_NEW'],
		"STICKER_HIT" => $arParams['STICKER_HIT'],
		"TAB_PROPERTY_NEW" => $arParams['TAB_PROPERTY_NEW'],
		"TAB_PROPERTY_HIT" => $arParams['TAB_PROPERTY_HIT'],
		"TAB_PROPERTY_SALE" => $arParams['TAB_PROPERTY_SALE'],
		"TAB_PROPERTY_BESTSELLER" => $arParams['TAB_PROPERTY_BESTSELLER'],
		"MAIN_SP_ON_AUTO_NEW" => $arParams['MAIN_SP_ON_AUTO_NEW'],
		"SHOW_DISCOUNT_PERCENT" => 'N',
		"SKU_EXT" => $arResult['bSkuExt'],
		"CUSTOM_STICKERS" => $arResult['PROPERTIES'][iRZProp::STICKERS],
		"SHOW_CONTAINER" => "N",
        'ACTION_DATA' => $arResult['ACTION_DATA'],
	),
	$this->__component,
	array("HIDE_ICONS" => "Y")
);
$arResult['yenisite:stickers'] = ob_get_clean();

if ($arParams['SHOW_CATCHBUY']) {
	$arResult['CATCHBUY'] = CRZBitronic2CatalogUtils::getCatchbuyInfo($arResult['ID'], $arResult['STICKERS']);
}

$arResult['bTechTab'] = (
	   0 < strlen(trim($arResult['DETAIL_TEXT']))
	|| 0 < intval($arResult['CATALOG_WEIGHT'])
	|| 0 < intval($arResult['CATALOG_LENGTH'])
	|| 0 < intval($arResult['CATALOG_WIDTH'])
	|| 0 < intval($arResult['CATALOG_HEIGHT'])
	|| !empty($arResult['DISPLAY_PROPERTIES'])
	||(!empty($arResult['SHOW_OFFERS_PROPS']) && $arResult['bSkuExt'])
	|| !empty($arResult['TAGS'])
);

foreach (GetModuleEvents(CRZBitronic2Settings::getModuleId(), "OnAfterDetailResultmodifier", true) as $arEvent)
	ExecuteModuleEventEx($arEvent, array(&$arResult, &$arParams));


