<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Page\Asset;

$APPLICATION->SetTitle(GetMessage("BITRONIC2_SEARCH_RESULTS"));
$this->setFrameMode(true);

global $rz_b2_options;

// include css and js
$asset = Asset::getInstance();
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/libs/flexGreedSort.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/jquery.countdown.2.0.2/jquery.plugin.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/jquery.countdown.2.0.2/jquery.countdown.min.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/initTimers.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/libs/UmTabs.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/pages/initSearchResultsPage.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/sliders/initPhotoThumbs.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/initCatalogHover.js");
CJSCore::Init(array('rz_b2_um_countdown', 'rz_b2_bx_catalog_item'));
if ('Y' == $rz_b2_options['wow-effect']) {
    $asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/wow.min.js");
}
if ($rz_b2_options['quick-view'] === 'Y') {
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/jquery.mobile.just-touch.min.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/initMainGallery.js");
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/toggles/initGenInfoToggle.js");
}
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/pages/initCatalogPage.js");

// Search category for catalog products
$catalogCategory = 'iblock_' . $arParams['IBLOCK_TYPE'];
$catalogIblockId = $arParams['IBLOCK_ID'];

if (!empty($_GET['where'])) {
    $catalogCategory = $_GET['where'] ?: 'iblock_' . $arParams['IBLOCK_TYPE'];
    $arParamsSearchTitle = \Yenisite\Core\Tools::getPararmsOfCMP($_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'include_areas/header/search.php', true);
    $arValuesWhere = array();
    if (!empty($arParamsSearchTitle)) {
        $countCategory = $arParamsSearchTitle['NUM_CATEGORIES'];
        $i = 0;
        $arWherre = array();
        for ($i = 0; $i <= $countCategory; $i++) {
            if (!empty($arParamsSearchTitle['CATEGORY_' . $i . '_' . $_GET['where']])) {
                $arParams["IBLOCK_ID"] = $arParamsSearchTitle['CATEGORY_' . $i . '_' . $_GET['where']];
            }
            foreach ($arParamsSearchTitle['CATEGORY_' . $i] as $key => $value) {
                $arWherre[] = $value;
                $arValuesWhere[$value] = $arParamsSearchTitle['CATEGORY_' . $i .'_'.$value];
            }
        }
    }
}

if (strpos($_GET['where'], 'iblock') !== false) {
    $arParams["IBLOCK_ID"] = strpos($_GET['where'], 'catalog') === false ? CRZBitronic2CatalogUtils::getIblockbyType(str_replace('iblock_', '', $_GET['where'])) : $catalogIblockId;
    $arParams["IBLOCK_ID"] = array($arParams["IBLOCK_ID"]);
}

if ($_GET['where'] === 'ALL' || $_REQUEST['where'] === 'ALL' || empty($_GET['where']) || empty($_REQUEST['where'])) {
    $_GET['where'] = $_REQUEST['where'] = '';
    $bAll = true;
}

// fill params for bitrix:search.page
$arSearchPageParams = array(
    "RESTART" => "Y",
    "NO_WORD_LOGIC" => "Y",
    "USE_LANGUAGE_GUESS" => "N",
    "CHECK_DATES" => "Y",
    "USE_TITLE_RANK" => "N",
    "DEFAULT_SORT" => "rank",
    "FILTER_NAME" => "offerFilter",
    "arrFILTER" => array(
        0 => $catalogCategory,
        /*1 => "iblock_news"*/
    ),
    "arrFILTER_" . $catalogCategory => $arParams["IBLOCK_ID"],
    /*"arrFILTER_iblock_news" => array(
        0 => "all",
    ),*/
    "SHOW_WHERE" => "Y",
    "arrWHERE" => array(
        0 => $catalogCategory,
        /*1 => "iblock_news",*/
    ),
    "SHOW_WHEN" => "N",
    "PAGE_RESULT_COUNT" => 20,
    "DISPLAY_TOP_PAGER" => "N",
    "DISPLAY_BOTTOM_PAGER" => "Y",
    "PAGER_TITLE" => GetMessage("BITRONIC2_SEARCH_RESULTS"),
    "PAGER_SHOW_ALWAYS" => "N",
    "PAGER_TEMPLATE" => "",

    "SEARCH_WITH_OFFERS" => false,
    "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
);

if (!empty($arWherre)) {
    $arSearchPageParams['arrWHERE'] = array_merge($arSearchPageParams['arrWHERE'], $arWherre);
    if ($bAll){
        $arSearchPageParams['arrFILTER'] = array_merge($arSearchPageParams['arrFILTER'], $arWherre);
        foreach ($arValuesWhere as $key => $arValueWhere){
            $arSearchPageParams['arrFilter_'.$key] = $arValueWhere;
        }
    }
}

global $offerFilter;
$offerFilter = array();

$bOffers = false;
$whereBackup = $_REQUEST['where'];

// check catalog offers
do {
    if (!empty($_REQUEST['where']) && ($_REQUEST['where'] !== $catalogCategory)) break;
    if (!CModule::IncludeModule('catalog')) break;

    $arOfferIBlock = CCatalogSKU::GetInfoByProductIBlock(reset($arParams['IBLOCK_ID']));
    if (!is_array($arOfferIBlock)) break;

    // fill params to search with offers
    $bOffers = true;
    $offerIBlockType = CIBlock::GetArrayByID($arOfferIBlock['IBLOCK_ID'], 'IBLOCK_TYPE_ID');
    $offerCategory = 'iblock_' . $offerIBlockType;

    if (in_array($offerCategory, $arSearchPageParams['arrFILTER'])) {
        $arSearchPageParams['arrFILTER_' . $offerCategory][] = $arOfferIBlock['IBLOCK_ID'];
    } else {
        $arSearchPageParams['arrFILTER_' . $offerCategory] = array(0 => $arOfferIBlock['IBLOCK_ID']);
        $arSearchPageParams['arrFILTER'][] = $offerCategory;
    }

    if (
        $catalogCategory === $_REQUEST['where'] &&
        $catalogCategory !== $offerCategory
    ) {
        // make custom filter for search with offers
        // because catalog iblock and offer iblock has different types
        $arSearchPageParams['SEARCH_WITH_OFFERS'] = true;
        $offerFilter['MODULE_ID'] = 'iblock';
        $offerFilter['PARAM1'] = array($arParams['IBLOCK_TYPE'], $offerIBlockType);

        $_GET['where'] = $_REQUEST['where'] = '';
    }
} while (0);

// perform search, fill elements ID list

$arElements = $APPLICATION->IncludeComponent(
    "bitrix:search.page",
    "search",
    $arSearchPageParams,
    $component,
    array('HIDE_ICONS' => 'Y')
);

// return request param to original value
$_GET['where'] = $_REQUEST['where'] = $whereBackup;

if (empty($_GET['where'])) {
    $_GET['where'] = $_REQUEST['where'] = 'ALL';
}

if ($_REQUEST['where'] !== 'iblock_' . $arParams['IBLOCK_TYPE']) return;

// search made in catalog, output result through catalog.section
if (
    !empty($arElements) &&
    is_array($arElements)
) {
    if ($bOffers) {
        $arOffers = CCatalogSKU::getProductList($arElements, $arOfferIBlock['IBLOCK_ID']);

        if (is_array($arOffers)) {
            $arOffers = array_keys($arOffers);
            $arElements = array_diff($arElements, $arOffers);
        }
    }

    global $searchFilter;

    /**
     * @var array $arSectionParams ;
     */
    include 'include/prepare_params_section.php';

    $arSearchParams = array(
        "FILTER_NAME" => "searchFilter",
        "SECTION_ID" => "",
        "SECTION_CODE" => "",
        "SECTION_USER_FIELDS" => array(),
        "INCLUDE_SUBSECTIONS" => "Y",
        "SHOW_ALL_WO_SECTION" => "Y",
        "META_KEYWORDS" => "",
        "META_DESCRIPTION" => "",
        "BROWSER_TITLE" => "",
        "ADD_SECTIONS_CHAIN" => "N",
        "SET_TITLE" => "N",
        "SET_STATUS_404" => "N",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "N",
		"PAGE_ELEMENT_COUNT" => 20,

        "ELEMENT_SORT_FIELD" => $arSectionParams['ELEMENT_SORT_FIELD'],
        "ELEMENT_SORT_ORDER" => $arSectionParams['ELEMENT_SORT_ORDER'],
        "ELEMENT_SORT_FIELD2" => $arSectionParams['ELEMENT_SORT_FIELD2'],
        "ELEMENT_SORT_ORDER2" => $arSectionParams['ELEMENT_SORT_ORDER2'],
        'SEARCH_PAGE' => 'Y'
    );
    $arSearchParams = array_merge($arSectionParams, $arSearchParams);


    $catalogClass = 'catalog blocks active ';
    $catalogClass .= $arResult['HOVER-MODE'];
    $arSearchParams['SEARCH_PAGE_CLASS'] = $catalogClass;
    $arSearchParams['HOVER-MODE'] = $arResult['HOVER-MODE'];
    $arSearchParams['IBLOCK_ID'] = is_array($arSearchParams['IBLOCK_ID']) ? $arSearchParams['IBLOCK_ID'][0] : $arSearchParams['IBLOCK_ID'];
    $arSearchParams['SEARCH_PAGE_ONLY_CATALOG'] = $bOffers && !empty($arOffers) ? '' : 'Y';

    $this->SetViewTarget('catalog_search');

    if (!empty($arElements)) {
        $searchFilter = array(
            "=ID" => $arElements,
        );
?>

  <? //CRZBitronic2CatalogUtils::setFilterAvPrFoto($searchFilter, $arSearchParams);

        $APPLICATION->IncludeComponent(
            "bitrix:catalog.section",
            "blocks",
            $arSearchParams,
            $component,
            array('HIDE_ICONS' => 'Y')
        );
    }

    if ($bOffers && !empty($arOffers)) {
        $arSearchParams['IBLOCK_TYPE'] = $offerIBlockType;
        $arSearchParams['IBLOCK_ID'] = $arOfferIBlock['IBLOCK_ID'];
		
		if(count($arElements) > 0)
		{
			$arSearchParams['SEARCH_PAGE_CLOSE_TAG'] = 'Y';
		}
        
		unset($arSearchParams['DETAIL_URL']);

        $searchFilter = array('=ID' => $arOffers);

        //CRZBitronic2CatalogUtils::setFilterAvPrFoto($searchFilter, $arSearchParams);

        $APPLICATION->IncludeComponent(
            "bitrix:catalog.section",
            "blocks",
            $arSearchParams,
            $component,
            array('HIDE_ICONS' => 'Y')
        );
    }

    $this->EndViewTarget('catalog_search');

    if ($arResult['EMPTY_CATALOG']) {
        $this->SetViewTarget('hide_class_open_tag');
        echo '<div class="hide">';
        $this->EndViewTarget('hide_class_open_tag');

        $this->SetViewTarget('hide_class_close_tag');
        echo '</div>';
        $this->EndViewTarget('hide_class_close_tag');
    } else{
        $this->SetViewTarget('sections_elements_of_search');
        if (!empty($arResult['SECTIONS'])){
            $arSections = CRZBitronic2CatalogUtils::getSectionsByIDs($arResult['SECTIONS'],$arSearchParams);
        } else {
			$arIDs = $arElements;
			if(count($arOffers) > 0)
			{
				$arParentIDs = CRZBitronic2CatalogUtils::getParentElementsByOffer($arOffers);
				$arIDs = array_merge($arElements, $arParentIDs);
			}
			
            $arSections = CRZBitronic2CatalogUtils::getSectionByElements($arIDs, $arSearchParams);
        }

            if (!empty($arSections)):?>
                <div class="sort-n-view for-catalog no-sort no-justify">
                    <div class="sub-categories">
                        <?foreach ($arSections as $arSection):?>
                            <a href="<?=$arSection['SECTION_PAGE_URL']?>" class="link"><span
                                        class="text"><?=$arSection['NAME']?></span><sup><?=$arSection['COUNT_AFTER_FILTER_ELEMENTS'] ? :$arSection['ELEMENT_CNT']?></sup></a>
                        <?endforeach;?>
                    </div>
                </div>
            <?endif;
        $this->EndViewTarget('sections_elements_of_search');
    }
} else {
    echo GetMessage("CT_BCSE_NOT_FOUND");
}

// additional sliders with products
$this->SetViewTarget('search_sliders');
if ('N' !== $rz_b2_options['block_search-viewed']
    || 'N' !== $rz_b2_options['block_search-bestseller']
    || 'N' !== $rz_b2_options['block_search-recommend']
) {
    $arPrepareParams = $arSectionParams;
    $arPrepareParams['RESIZER_SETS'] = array('RESIZER_SECTION' => $arSectionParams['RESIZER_SECTION']);
    $asset->addJs(SITE_TEMPLATE_PATH . '/js/custom-scripts/inits/sliders/initHorizontalCarousels.js');
}
if ('N' !== $rz_b2_options['block_search-viewed']) {
    include 'include/viewed_products.php';
}
if ('N' !== $rz_b2_options['block_search-bestseller']) {
    $arPrepareParams['HEADER_TEXT'] = $arParams['BIGDATA_BESTSELL_TITLE'] ?: GetMessage('BIGDATA_BESTSELL_TITLE_DEFAULT');
    $arPrepareParams['RCM_TYPE'] = 'bestsell';
    include 'include/bigdata.php';
}
if ('N' !== $rz_b2_options['block_search-recommend']) {
    $arPrepareParams['HEADER_TEXT'] = $arParams['BIGDATA_PERSONAL_TITLE'] ?: GetMessage('BIGDATA_PERSONAL_TITLE_DEFAULT');
    $arPrepareParams['RCM_TYPE'] = 'personal';
    include 'include/bigdata.php';
}
$this->EndViewTarget('search_sliders');
?>
