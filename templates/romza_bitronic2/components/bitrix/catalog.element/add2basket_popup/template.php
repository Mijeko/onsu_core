<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;

$this->setFrameMode(true);
$compositeLoader = CRZBitronic2Composite::insertCompositLoader();

$templateData = array(
	'CATALOG_VAT_INCLUDED' => $arResult['CATALOG_VAT_INCLUDED'],
	'MIN_PRICE' => $arResult['MIN_PRICE']
);

$productTitle = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
	: $arResult["NAME"]
);
$strTitle = (
	!empty($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"])
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (
	!empty($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"])
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);
$bDiscountShow = (0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF']);
$bStores = $arParams["USE_STORE"] == "Y" && Bitrix\Main\ModuleManager::isModuleInstalled("catalog");
$bShowStore = $bStores;
$canBuy = $arResult['CAN_BUY'];

$availableClass = !$canBuy ? 'out-of-stock' : ($arResult['FOR_ORDER'] ? 'available-for-order' : '');

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PRICE_WRAP' => $strMainID.'_price_wrap',
);
?>
<div class="title-h2"><?=GetMessage('BITRONIC2_POPUP_ITEM_TITLE')?></div>
	<div class="basket-content">
		<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';?>
		<div itemscope itemtype="http://schema.org/ImageObject" class="img-wrap">
			<img itemprop="contentUrl" class="lazy" data-original="<?= $arResult['PICTURE_PRINT']['SRC'] ?>" src="<?=consVar::showLoaderWithTemplatePath()?>" alt="<?= $strAlt ?>" title="<?= $strTitle ?>">
		</div>
		<div class="main-data">
			<a href="<?=$arResult['DETAIL_PAGE_URL']?>" class="link"><span class="text"><?=$productTitle?></span></a>
			<div>
			<? if ($arParams['SHOW_ARTICLE'] == 'Y'):?>
				<?if(!empty($arResult['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])):?>
					<span class="info art"><?=$arResult['PROPERTIES'][$arParams['ARTICUL_PROP']]['NAME']?>: <strong itemprop="productID"><?=is_array($arResult['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) ? implode(' / ', $arResult['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) : $arResult['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']?></strong></span>
				<?endif?>
			<?endif ?>
				<? if ($arParams['SHOW_STARS'] == 'Y'): ?>
					<?$id = 'bxdinamic_BITRONIC2_popup_item_vote_'.$arResult['ID'];
					?><div id="<?=$id?>" class="inline"><?
					$frame = $this->createFrame($id, false)->begin($compositeLoader);?>
						<?$APPLICATION->IncludeComponent("bitrix:iblock.vote", "stars", array(
						"IBLOCK_TYPE" => $arResult['IBLOCK_TYPE_ID'],
						"IBLOCK_ID" => $arResult['IBLOCK_ID'],
						"ELEMENT_ID" => $arResult['ID'],
						"CACHE_TYPE" => $arParams["CACHE_TYPE"],
						"CACHE_TIME" => $arParams["CACHE_TIME"],
						"MAX_VOTE" => "5",
						"VOTE_NAMES" => array("1", "2", "3", "4", "5"),
						"SET_STATUS_404" => "N",
						),
						$component, array("HIDE_ICONS"=>"Y")
					);?>
					<?$frame->end();?>
					</div>
				<? endif ?>
			</div>
			<div class="prices-wrap">
				<?if($bDiscountShow):?><span class="price-old"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['MIN_PRICE']['CURRENCY'],$arResult['MIN_PRICE']['VALUE'],$arResult['MIN_PRICE']['PRINT_VALUE'])?></span><?endif?>
				<span class="price-now"><?=CRZBitronic2CatalogUtils::getElementPriceFormat($arResult['MIN_PRICE']['CURRENCY'],$arResult['MIN_PRICE']['DISCOUNT_VALUE'],$arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE'])?></span>
			</div>
			<div class="quantity-counter">
				
			</div>
			<?
			$availableID = false;
			$availableFrame = false;
			$availableForOrderText = &$arResult['PROPERTIES']['RZ_FOR_ORDER_TEXT']['VALUE'];
			$availableItemID = &$arResult['ID'];
			$availableMeasure = &$arResult['CATALOG_MEASURE_NAME'];
			$availableQuantity = &$arResult['CATALOG_QUANTITY'];
			$availableStoresPostfix = 'popup_item';
			$availableSubscribe = $arResult['CATALOG_SUBSCRIBE'];
            $bExpandedStore = false;
			include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/availability_info.php';
			?>
		</div>
	</div>
<?
// echo "<pre style='text-align:left;'>";print_r($arParams);echo "</pre>";