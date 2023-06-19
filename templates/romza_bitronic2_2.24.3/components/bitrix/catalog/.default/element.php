<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Page\Asset;
use \Bitronic2\Mobile;


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
$this->setFrameMode(true);


global $rz_b2_options, $rz_current_sectionID;

$rz_b2_options['product-hover-effect'] = Mobile::isMobile() ? 'border-n-shadow' : $rz_b2_options['product-hover-effect'];

$asset = Asset::getInstance();
$asset->addString('<link href="'.SITE_TEMPLATE_PATH.'/css/print.css" media="print" rel="stylesheet">');
$asset->addJs(SITE_TEMPLATE_PATH . "/js/back-end/ajax/catalog_element.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/sliders/initHorizontalCarousels.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/jquery.mobile.just-touch.min.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/jquery.countdown.2.0.2/jquery.plugin.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/jquery.countdown.2.0.2/jquery.countdown.min.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/initTimers.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/libs/UmAccordeon.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/libs/UmTabs.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/libs/UmComboBlocks.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/jquery.magnify.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/initMainGallery.js");
//$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/sliders/initThumbs.js");
if ($rz_b2_options['product-hover-effect'] == 'detailed-expand') {
	$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/initCatalogHover.js");
}
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/toggles/initGenInfoToggle.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/libs/UmScrollSpyMenu.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/libs/UmScrollFix.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/nouislider.min.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/initCollectionHandle.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/sliders/initProductCarousel.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/pages/initProductPage.js");
CJSCore::Init(array('rz_b2_um_countdown', 'rz_b2_bx_catalog_item'));
if ('Y' == $rz_b2_options['wow-effect']) {
	$asset->addJs(SITE_TEMPLATE_PATH . "/js/3rd-party-libs/wow.min.js");
}
if ('modal' == $rz_b2_options['detail_gallery_type']) {
	$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/initModalGallery.js");
}

/**
 * @var $arPrepareParams - arParams for catalog.element
 */
include 'include/prepare_params_element.php';

if (Bitrix\Main\Loader::IncludeModule('yenisite.core')) {
	$arBrandParams = \Yenisite\Core\Ajax::getParams('yenisite:highloadblock', false, CRZBitronic2CatalogUtils::getBrandPathForUpdate());
	if (!empty($arBrandParams['PATH_TO_VIEW'])) {
		$arPrepareParams['BRAND_DETAIL'] = $arBrandParams['PATH_TO_VIEW'];
	}
}

ob_start();
$ElementID = $APPLICATION->IncludeComponent(
	"bitrix:catalog.element",
	'catalog',
	$arPrepareParams,
	$component
);

$elementHTML = ob_get_clean();
$arResult['ELEMENT_ID'] = $ElementID;

$arReplace = array();

//=== advertising
$arBannerAreas = array('element_banner_single', 'element_banner_double', 'element_banner_triple', '');
for ($i = 0; $i < 6; $i++) {
	if (!in_array($arParams['ELEMENT_BANNER_AREA_'.$i], $arBannerAreas)) $arParams['ELEMENT_BANNER_AREA_'.$i] = $arBannerAreas[$i % 3];
	ob_start();
	Yenisite\Core\Tools::IncludeArea('catalog', $arParams['ELEMENT_BANNER_AREA_'.$i], false, true, $rz_b2_options['block_show_ad_banners']);
	$arReplace['#DETAIL_BANNER_' . $i . '#'] = ob_get_clean();
}
//^^^ advertising

ob_start();
include 'include/get_cur_section.php'; // @var $arCurSection
$rz_current_sectionID = $arCurSection['ID'];

	if($arParams['DETAIL_YM_API_USE'] == 'Y')
	{
		$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
			"AREA_FILE_SHOW" => "file",
			"CACHE_TYPE" => "N",
			"EDIT_TEMPLATE" => "include_areas_template.php",
			"PATH" => SITE_DIR."include_areas/catalog/rw_ymapi.php",
			"ELEMENT_ID" => $arResult["PROPERTIES"]["TURBO_YANDEX_LINK"]["VALUE"],
			), 
			false, 
			array("HIDE_ICONS"=>"Y")
		);
	}
$arReplace['#DETAIL_RW_YM_API#'] = ob_get_clean();

if ($arPrepareParams['DISPLAY_FAVORITE']) {
    ob_start();
    $frame = new \Bitrix\Main\Page\FrameStatic("detail_favorite_count");
    $frame->setAnimation(true);
    $frame->setContainerID('bxdinamic_detail_favorite_count');
    $frame->setStub(CRZBitronic2Composite::insertCompositLoader());
    $frame->startDynamicArea();
    if (method_exists('Yenisite\Favorite\Favorite', 'getCountWithProduct')) {
        echo Yenisite\Favorite\Favorite::getCountWithProduct($ElementID);
    }
    $frame->finishDynamicArea();
    $arReplace['#DETAIL_PRODUCT_FAVORITE_COUNT#'] = ob_get_clean();
    unset($contentFavorite);
}


// LITE Comments

if (
	'Y' == $arParams['USE_REVIEW'] &&
	'N' != $arParams['USE_OWN_REVIEW'] &&
	CModule::IncludeModule('yenisite.feedback')
):
	$reviewsId = 'feedback_comments';
	ob_start();
?>

<?$APPLICATION->IncludeComponent(
	"yenisite:feedback.add",
	"comments",
	Array(
		"IBLOCK_TYPE" => $arPrepareParams['FEEDBACK_IBLOCK_TYPE'],
		"IBLOCK" => $arPrepareParams['FEEDBACK_IBLOCK_ID'],
		"NAME_FIELD" => "NAME",//$arPrepareParams['NAME_FIELD'],
		//"TITLE" => $arPrepareParams['TITLE'],
		"SUCCESS_TEXT" => "",//$arPrepareParams['SUCCESS_TEXT'],
		"USE_CAPTCHA" => $rz_b2_options['use_google_captcha'] == 'N' ? $rz_b2_options['feedback-for-item-on-detail'] : 'N',//$arPrepareParams['USE_CAPTCHA'],
		"USE_GOOGLE_CAPTCHA" => $rz_b2_options['use_google_captcha'] == 'Y' ? $rz_b2_options['feedback-for-item-on-detail'] : 'N',//$arPrepareParams['USE_CAPTCHA'],
		"PRINT_FIELDS" => array("EMAIL", "ELEMENT_ID"),//$arPrepareParams['PRINT_FIELDS'],
		"AJAX_MODE" => 'N',//$arPrepareParams['AJAX_MODE'],
		"CACHE_TYPE" => $arPrepareParams["CACHE_TYPE"],
		"CACHE_TIME" => $arPrepareParams["CACHE_TIME"],
		"CACHE_GROUPS" => "Y",
		"AJAX_OPTION_JUMP" => 'N',//$arPrepareParams['AJAX_OPTION_JUMP'],
		"AJAX_OPTION_STYLE" => 'Y',//$arPrepareParams['AJAX_OPTION_STYLE'],
		"AJAX_OPTION_HISTORY" => "Y",//$arPrepareParams['AJAX_OPTION_HISTORY'],
		"NAME" => "NAME",//$arPrepareParams['NAME'],
		"EMAIL" => "EMAIL",//$arPrepareParams['EMAIL'],
		"PHONE" => "PHONE",//$arPrepareParams['PHONE'],
		"MESSAGE" => $_POST["romza_feedback"]["text"],//$arPrepareParams['MESSAGE'],
		"ACTIVE" => "Y",//$arPrepareParams['ACTIVE'],
		"EVENT_NAME" => "",//$arPrepareParams['EVENT_NAME'],
		"TEXT_REQUIRED" => "Y",//$arPrepareParams['TEXT_REQUIRED'],
		"TEXT_SHOW" => "Y",//$arPrepareParams['TEXT_SHOW'],
		"SECTION_CODE" => "ITEM_REVIEW_".$arResult['ELEMENT_ID'],//$arResult['VARIABLES']['SECTION_CODE'],
		"SHOW_SECTIONS" => "N",
		"ELEMENT_ID" => $arResult['ELEMENT_ID'],
		"COLOR_SCHEME" => "green",//$arPrepareParams['COLOR_SCHEME'],
	)
);?>

	<div class="comments-list" id="<?=$reviewsId?>"><?

	$dynamicArea = new \Bitrix\Main\Page\FrameStatic("catalog_reviews_dynamic");
	$dynamicArea->setAnimation(true);
	$dynamicArea->setContainerID($reviewsId);
	$dynamicArea->setStub(CRZBitronic2Composite::insertCompositLoader());
	$dynamicArea->startDynamicArea();

	if (CIBlockFindTools::GetSectionID(
		$section_id = 0,
		$section_code = "ITEM_REVIEW_".$arResult['ELEMENT_ID'],
		$arFilter = array("GLOBAL_ACTIVE" => "Y", "IBLOCK_ID" => $arPrepareParams['FEEDBACK_IBLOCK_ID'])) > 0
	):?>
	<?$APPLICATION->IncludeComponent("bitrix:news.list","comments",Array(
			"DISPLAY_DATE" => "N",
			"DISPLAY_NAME" => "N",
			"DISPLAY_PICTURE" => "N",
			"DISPLAY_PREVIEW_TEXT" => "Y",
			"AJAX_MODE" => "N",
			"IBLOCK_TYPE" => $arPrepareParams['FEEDBACK_IBLOCK_TYPE'],
			"IBLOCK_ID" => $arPrepareParams['FEEDBACK_IBLOCK_ID'],
			"NEWS_COUNT" => "0",
			"SORT_BY1" => "ACTIVE_FROM",
			"SORT_ORDER1" => "DESC",
			"SORT_BY2" => "SORT",
			"SORT_ORDER2" => "ASC",
			"FILTER_NAME" => "",
			"FIELD_CODE" => Array("ID", "DATE_CREATE", "CREATED_BY"),
			"PROPERTY_CODE" => Array("NAME", "IP"),
			"CHECK_DATES" => "Y",
			"DETAIL_URL" => "",
			"PREVIEW_TRUNCATE_LEN" => "",
			"ACTIVE_DATE_FORMAT" => "d.m.Y",
			"SET_TITLE" => "N",
			"SET_BROWSER_TITLE" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_STATUS_404" => "N",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"ADD_SECTIONS_CHAIN" => "N",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "ITEM_REVIEW_".$arResult['ELEMENT_ID'],
			"INCLUDE_SUBSECTIONS" => "N",
			"CACHE_TYPE" => $arPrepareParams["CACHE_TYPE"],
			"CACHE_TIME" => $arPrepareParams["CACHE_TIME"],
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"PAGER_TITLE" => "",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => "",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "Y",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"GAMIFICATION" => $arPrepareParams["SHOW_GAMIFICATION"],
			"RESIZER_COMMENT_AVATAR" => $arPrepareParams['RESIZER_SETS']['RESIZER_COMMENT_AVATAR']
		),$component
	);?>
	<? elseif($arPrepareParams["SHOW_GAMIFICATION"]): ?>

	<div class="mar-b-15"><?=GetMessage('BITRONIC2_BE_FIRST')?></div>
	<? endif ?><?

	$dynamicArea->finishDynamicArea();
	?>

	</div>
<?
$arReplace['#YENISITE_FEEDBACK_CATALOG_COMMENTS#'] = ob_get_clean();
endif;

ob_start();
if ($arPrepareParams['HIDE_VIEWED'] != 'Y') {
    $arPrepareParams['PAGE_ELEMENT_COUNT'] = $arParams['DETAIL_CNT_ELEMENTS_IN_SLIDERS'];
    $arPrepareParams["ORDER_VIEWED_PRODUCTS"] = $rz_b2_options['order-sPrViewedProducts'];
	include 'include/viewed_products.php';
}
$arReplace['#DETAIL_RW_VIEWED_PRODUCTS#'] = ob_get_clean();

//set CNT only for feedback review
ob_start();

$frame = new \Bitrix\Main\Page\FrameStatic("detail_reviews_count");
$frame->setAnimation(true);
$frame->setContainerID('bxdinamic_detail_reviews_count');
$frame->setStub(CRZBitronic2Composite::insertCompositLoader());
$frame->startDynamicArea();
echo $this->__component->arResult['CNT_ELEMENTS'];
$frame->finishDynamicArea();

$arReplace['#COUNT_REVIEWS#'] = ob_get_clean();

//replace macros in template.php
echo str_replace(array_keys($arReplace), $arReplace, $elementHTML);
?>

<? /* =================== EDOST START =================== */ ?>
<?
if (CModule::IncludeModule('sale') && CModule::IncludeModule('edost.catalogdelivery')) {
	$location = intval($_COOKIE['YS_GEO_IP_LOC_ID']);
	if ($location < 1) {
		$location = COption::GetOptionString('sale', 'location', '', SITE_ID);
		$location = CSaleLocation::getLocationIDbyCODE($location);
	}

	if ($arPrepareParams['EDOST_PREVIEW']) {
		if ($arParams['EDOST_ECONOMIZE_INLINE'] !== 'N') {
			$arParams['EDOST_ECONOMIZE'] = 'Y';
		}
		$arParams['EDOST_MAX'] = intval($arParams['EDOST_MAX']);
		if ($arParams['EDOST_MAX'] <= 0) $arParams['EDOST_MAX'] = 5;
	} else {
		$arParams['EDOST_MAX'] = 0;
	}

	$arEdostParams = array('minimize' => '|full');
	if ($arParams['EDOST_SORT']      === 'Y') $arEdostParams['sort'] = 'ASC';
	if ($arParams['EDOST_ECONOMIZE'] === 'Y') $arEdostParams['economize'] = 'Y';
	if ($arParams['EDOST_MINIMIZE']  === 'Y') $arEdostParams['minimize'] = 'normal|full';
	if ($arParams['EDOST_MAX'] > 0)           $arEdostParams['max'] = $arParams['EDOST_MAX'];
	if ($location > 0)                        $arEdostParams['location_id_default'] = $location;

	$frame = new \Bitrix\Main\Page\FrameBuffered("edost_catalogdelivery");
	$frame->begin('');

	$APPLICATION->IncludeComponent('edost:catalogdelivery', '', array(
		'PARAM'   => $arEdostParams,
		'FRAME_X' => '650',
		'SHOW_QTY'      => ($arParams['EDOST_SHOW_QTY']      === 'N' ? 'N' : 'Y'),
		'SHOW_ADD_CART' => ($arParams['EDOST_SHOW_ADD_CART'] === 'N' ? 'N' : 'Y'),
		'COLOR'  => 'clear_white',
		'RADIUS' => '8',
		'CACHE_TYPE'   => $arParams['CACHE_TYPE'],
		'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
		'CACHE_TIME'   => $arParams['CACHE_TIME'],
	), null, array('HIDE_ICONS' => 'Y'));

	$frame->end();
}
?>
<? /* ==================== EDOST END ==================== */ ?>
