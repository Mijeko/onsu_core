<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Page\Asset;

$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_MAIN"), $arResult['SEF_FOLDER']);
$APPLICATION->AddChainItem(Loc::getMessage("SPS_CHAIN_SUBSCRIBE_NEW"));

if (!Loader::includeModule('iblock')) {
	ShowError(Loc::getMessage('SPS_RZ_MODULE_IBLOCK_REQUIRED'));
	return;
}
if (!Loader::includeModule('yenisite.core')) {
	ShowError(Loc::getMessage('SPS_RZ_MODULE_CORE_REQUIRED'));
	return;
}

// form filter to fetch all requests from current user for every types of subscribes
$arFilter = array(
	'ACTIVE' => 'Y',
	'CREATED_BY' => $USER->GetID(),
	'IBLOCK_ID' => array()
);

/**
 * @var array $arSubscribeList - List of all subscribes by current user ('IBLOCK_ID' => _CIBElement[])
 */
$arSubscribeList = array();

/**
 * @var array $arTabs ('FEEDBACK IBLOCK CODE' => 'tab id in HTML')
 */
$arTabs = array(
	'ELEMENT_EXIST'   => 'admission',
	'ELEMENT_CONTACT' => 'request',
	'FOUND_CHEAP'     => 'cheaper',
	'PRICE_LOWER'     => 'sale'
);

// fill IBlock IDs from component parameters
foreach ($arTabs as $feedbackId => $tab) {
	$iblockId = intval($arParams['FEEDBACK_'.$feedbackId.'_IBLOCK_ID']);
	$arParams['FEEDBACK_'.$feedbackId.'_IBLOCK_ID'] = $iblockId;

	if (0 >= $iblockId) {
		unset($arTabs[$feedbackId]);
		continue;
	}
	$arFilter['IBLOCK_ID'][] = $iblockId;
	$arSubscribeList[$iblockId] = array();
}

/**
 * @var bool $bOffers - indicates if catalog has iblock with offers
 */
$bOffers = false;

/**
 * @var bool $bHasSubscribes - indicates if current user has any active subscribes
 */
$bHasSubscribes = false;

/**
 * @var string $strError - error message
 */
$strError = '';

do {
	// check if there are iblocks in parameters
	if (empty($arFilter['IBLOCK_ID'])) {
		$strError = Loc::getMessage('SPS_RZ_NO_SUBSCRIBE_TYPES');
		break;
	}

	// fetch all subscribes
	$rsElements = CIBlockElement::GetList(array('sort'=>'asc'), $arFilter);
	while ($obElement = $rsElements->GetNextElement()) {
		$obElement->props = $obElement->GetProperties();

		$productId = intval($obElement->props['PRODUCT']['VALUE']);
		if (0 >= $productId) continue;

		$arSubscribeList[(int)$obElement->fields['IBLOCK_ID']][] = $obElement;
		$bHasSubscribes = true;
	}
	if (!$bHasSubscribes) {
		$strError = Loc::getMessage('SPS_RZ_NO_SUBSCRIBES');
		break;
	}

	// load catalog params
	$arTempParams = $arParams;
	$arParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());

	/**
	 * @var array $arSectionParams
	 */
	include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/components/bitrix/catalog/.default/include/prepare_params_section.php';

	$arCatalogParams = $arParams;
	$arParams = $arTempParams;
	unset($arTempParams);

	// update catalog params
	$arSectionParams['CACHE_FILTER'] = 'Y';
	$arSectionParams['CACHE_TIME']   = 3600;
	$arSectionParams['FILTER_NAME']  = 'subscribeFilter';
	$arSectionParams['RESIZER_SET']  = $arParams['FEEDBACK_RESIZER_SET'];

	// check if catalog has offers
	if (!Loader::includeModule('catalog')) break;

	$arOfferIBlock = CCatalogSKU::GetInfoByProductIBlock($arCatalogParams['IBLOCK_ID']);
	if (!is_array($arOfferIBlock)) break;

	$bOffers = true;

} while (0);

// fullfill information for every tab
foreach ($arTabs as $feedbackId => $tab) {
	$paramPrefix = 'FEEDBACK_' . $feedbackId;
	$iblockId = $arParams[$paramPrefix . '_IBLOCK_ID'];

	// remove tabs with no subscribes
	if (empty($arSubscribeList[$iblockId])) {
		unset($arTabs[$feedbackId]);
		continue;
	}

	// create lists of products and offers
	$arProducts = array();
	foreach ($arSubscribeList[$iblockId] as $obElement) {
		$arProducts[] = intval($obElement->props['PRODUCT']['VALUE']);
	}
	$arProducts = array_unique($arProducts);
	$arOffers   = false;

	if ($bOffers) {
		$arOffers = CCatalogSKU::getProductList($arProducts, $arOfferIBlock['IBLOCK_ID']);

		if (is_array($arOffers)) {
			$arOffers   = array_keys($arOffers);
			$arProducts = array_diff($arProducts, $arOffers);
		}
	}
	$arTabs[$feedbackId] = array(
		'COL_PROP_1'  => $arParams[$paramPrefix . '_PROP_1'] ?: null,
		'COL_PROP_2'  => $arParams[$paramPrefix . '_PROP_2'] ?: null,
		'COL_TITLE_1' => $arParams[$paramPrefix . '_PROP_1_TITLE'] ?: Loc::getMessage($paramPrefix . '_PROP_1_TITLE_DEFAULT'),
		'COL_TITLE_2' => $arParams[$paramPrefix . '_PROP_2_TITLE'] ?: Loc::getMessage($paramPrefix . '_PROP_2_TITLE_DEFAULT'),
		'ID'        => $tab,
		'OFFERS'    => $arOffers,
		'PRODUCTS'  => $arProducts,
		'SUBSCRIBE' => $arSubscribeList[$iblockId]
	);
}

/**
 * @var array $subscribeFilter - global filter for catalog.section
 */
global $subscribeFilter;

?>

<main class="container account-personal-waiting-goods">
	<h1><? $APPLICATION->ShowTitle(false) ?></h1>
	<div class="account row">
		<? include $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'personal/left_menu.php' ?>

		<div class="account-content col-xs-12 col-sm-9 col-xl-10">
		<?
		if (!empty($strError)):
			ShowError($strError);
		else:
			Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . '/js/3rd-party-libs/bootstrap/tab.js');
			?>

			<div class="title-h3 text-center"><?= Loc::getMessage('SPS_RZ_TITLE_SUBSCRIBE_TYPE') ?>:</div>
			<ul class="nav nav-pills" role="tablist" id="goodsTab">
			<? $active = true ?><?
			foreach ($arTabs as $feedbackId => $arTab): ?>

				<li role="presentation"<?= $active ? ' class="active"' : '' ?>>
					<a href="#goods-<?= $arTab['ID'] ?>" aria-controls="goods-<?= $arTab['ID'] ?>" role="tab" data-toggle="tab">
						<?= ($arParams['FEEDBACK_'.$feedbackId.'_TITLE'] ?: Loc::getMessage('FEEDBACK_'.$feedbackId.'_TITLE_DEFAULT')) ?>

					</a>
				</li>
				<? $active = false ?><?
			endforeach ?>

			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
			<? $active = true ?><?

			foreach ($arTabs as $feedbackId => $arTab):
				?>

				<div role="tabpanel" class="tab-pane fade<?= $active ? ' in active' : '' ?>" id="goods-<?= $arTab['ID'] ?>">
					<div class="tab-description">
						<? Yenisite\Core\Tools::includeArea('personal', 'products_' . strtolower($feedbackId), false, false) ?>
					</div>
					<table class="items-table">
						<thead>
							<tr>
								<th colspan="2"><?= Loc::getMessage('SPS_RZ_COLUMN_PRODUCT_TITLE') ?></th>
								<th class="<?= $arTab['ID'] ?>"><?= $arTab['COL_TITLE_1'] ?></th>
								<? if (!empty($arTab['COL_PROP_2'])): ?>

								<th class="<?= $arTab['ID'] ?>-contacts"><?= $arTab['COL_TITLE_2'] ?></th>
								<? endif ?>

								<th class="actions-btn"><?= Loc::getMessage('SPS_RZ_COLUMN_ACTION_TITLE') ?></th>
							</tr>
						</thead>
						<tbody>
						<?
							$arSectionParams['IBLOCK_ID'] = $arCatalogParams['IBLOCK_ID'];
							$arSectionParams['PROPERTY_CODE'] = array();

							$arSectionParams['PROP_1'] = $arTab['COL_PROP_1'];
							$arSectionParams['PROP_2'] = $arTab['COL_PROP_2'];
							$arSectionParams['SUBSCRIBE_LIST'] = $arTab['SUBSCRIBE'];

                            $arSectionParams['HIDE_ITEMS_NOT_AVAILABLE'] = $arCatalogParams['HIDE_ITEMS_NOT_AVAILABLE'];
                            $arSectionParams['HIDE_ITEMS_ZER_PRICE'] = $arCatalogParams['HIDE_ITEMS_ZER_PRICE'];
                            $arSectionParams['HIDE_ITEMS_WITHOUT_IMG'] = $arCatalogParams['HIDE_ITEMS_WITHOUT_IMG'];

							$subscribeFilter = array('=ID' => $arTab['PRODUCTS']);

                            CRZBitronic2CatalogUtils::setFilterAvPrFoto($subscribeFilter, $subscribeFilter);

							$APPLICATION->IncludeComponent('bitrix:catalog.section', 'subscribe_list', $arSectionParams, $component);

							if (!empty($arTab['OFFERS'])) {
								$arSectionParams['IBLOCK_ID'] = $arOfferIBlock['IBLOCK_ID'];
								$arSectionParams['PROPERTY_CODE'] = $arCatalogParams['DETAIL_OFFERS_PROPERTY_CODE'];

								$subscribeFilter = array('=ID' => $arTab['OFFERS']);

                                CRZBitronic2CatalogUtils::setFilterAvPrFoto($subscribeFilter, $subscribeFilter);
								$APPLICATION->IncludeComponent('bitrix:catalog.section', 'subscribe_list', $arSectionParams, $component);
							}
						?>
						</tbody>
					</table>
				</div>
				<? $active = false ?><?
			endforeach ?>

			</div>
		<? endif ?>

		</div>
	</div>
</main>
