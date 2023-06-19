<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\ModuleManager;
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
/** @var CBitrixBasketComponent $component */
$asset = Asset::getInstance();
$asset->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/libs/UmTabs.js");
$asset->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/inits/pages/initBigBasketPage.js");

global $rz_b2_options;
$rz_b2_options['product-hover-effect'] = Mobile::isMobile() ? 'border-n-shadow' : $rz_b2_options['product-hover-effect'];

if ($rz_b2_options['product-hover-effect'] == 'detailed-expand') {
	$asset->addJs(SITE_TEMPLATE_PATH . "/js/custom-scripts/inits/initCatalogHover.js");
}

$curPage = $APPLICATION->GetCurPage().'?'.$arParams["ACTION_VARIABLE"].'=';
$arUrls = array(
	"delete" => $curPage."delete&id=#ID#",
	"delay" => $curPage."delay&id=#ID#",
	"add" => $curPage."add&id=#ID#",
);
unset($curPage);

if (!empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/personal/') === false) {
	$_SESSION['RZ_B2_BASKET_PREV_PAGE'] = $_SERVER['HTTP_REFERER'];
}

$arBasketJSParams = array(
	'BASKET_CURRENCY' => CSaleLang::GetLangCurrency(SITE_ID),
	'SALE_DELETE' => GetMessage("BITRONIC2_SALE_DELETE"),
	'SALE_DELAY' => GetMessage("BITRONIC2_SALE_DELAY"),
	'SALE_TYPE' => GetMessage("BITRONIC2_SALE_TYPE"),
	'TEMPLATE_FOLDER' => $templateFolder,
	'DELETE_URL' => $arUrls["delete"],
	'DELAY_URL' => $arUrls["delay"],
	'ADD_URL' => $arUrls["add"],
	'SELF_URL' => $APPLICATION->GetCurPage(true)
);
?>
<script type="text/javascript">
	var basketJSParams = <?=CUtil::PhpToJSObject($arBasketJSParams)?>;
	BX.message({basket_sku_not_available: "<?=GetMessage('BITRONIC2_SKU_NOT_AVAILABLE')?>"});
</script>
<main class="container basket-big-page" data-page="big-basket-page">
	<h1><?$APPLICATION->ShowTitle()?></h1>
	<a href="<?=$arParams['PATH_TO_ORDER']?>" class="btn-main btn-continue king-btn" onclick="checkOut();"
		><span class="text"><?=GetMessage('BITRONIC2_SALE_ORDER')?></span></a>
	<div class="clearfix"></div>
	<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form">

<?
$APPLICATION->AddHeadScript($templateFolder."/script.js");

if ($_POST['rz_ajax_no_header'] === 'y'){
	$APPLICATION->RestartBuffer();
}

if($rz_b2_options['block_basket-gift-products'] === 'Y')
{
	CJSCore::Init(array('rz_b2_bx_catalog_item'));
	Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/custom-scripts/inits/sliders/initHorizontalCarousels.js");

	if ($arParams['GIFTS_PLACE'] === 'BOTTOM') ob_start();
	$APPLICATION->IncludeComponent(
		"bitrix:sale.gift.basket",
		"bitronic2",
		array(
			"RESIZER_SECTION" => $arResult['CATALOG_PARAMS']['RESIZER_SECTION'],

			"SHOW_PRICE_COUNT" => 1,
			"PRODUCT_SUBSCRIPTION" => 'N',
			'PRODUCT_ID_VARIABLE' => 'id',
			"PARTIAL_PRODUCT_PROPERTIES" => 'N',
			"USE_PRODUCT_QUANTITY" => 'N',
			"ACTION_VARIABLE" => "action",
			"ADD_PROPERTIES_TO_BASKET" => "Y",

			"BASKET_URL" => $APPLICATION->GetCurPage(),
			"APPLIED_DISCOUNT_LIST" => $arResult["APPLIED_DISCOUNT_LIST"],
			"FULL_DISCOUNT_LIST" => $arResult["FULL_DISCOUNT_LIST"],

			"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_SHOW_VALUE"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],

			'BLOCK_TITLE' => $arParams['GIFTS_BLOCK_TITLE'],
			'HIDE_BLOCK_TITLE' => $arParams['GIFTS_HIDE_BLOCK_TITLE'],
			'TEXT_LABEL_GIFT' => $arParams['GIFTS_TEXT_LABEL_GIFT'],
			'PRODUCT_QUANTITY_VARIABLE' => $arParams['GIFTS_PRODUCT_QUANTITY_VARIABLE'],
			'PRODUCT_PROPS_VARIABLE' => $arParams['GIFTS_PRODUCT_PROPS_VARIABLE'],
			'PRICE_CODE' => $arResult['CATALOG_PARAMS']['PRICE_CODE'],
			'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
			'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
			'SHOW_NAME' => $arParams['GIFTS_SHOW_NAME'],
			'SHOW_IMAGE' => $arParams['GIFTS_SHOW_IMAGE'],
			'MESS_BTN_BUY' => $arParams['GIFTS_MESS_BTN_BUY'],
			'MESS_BTN_DETAIL' => $arParams['GIFTS_MESS_BTN_DETAIL'],
			'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_PAGE_ELEMENT_COUNT'] ?: 10,
			'CONVERT_CURRENCY' => $rz_b2_options['convert_currency'] ? 'Y' : $arParams['GIFTS_CONVERT_CURRENCY'],
			'CURRENCY_ID' => $rz_b2_options['convert_currency'] ? $rz_b2_options['active-currency'] : $arParams['CURRENCY_ID'],
			'HIDE_NOT_AVAILABLE' => $arParams['GIFTS_HIDE_NOT_AVAILABLE'],

			"LINE_ELEMENT_COUNT" => $arParams['GIFTS_PAGE_ELEMENT_COUNT'] ?: 10,
			"HOVER-MODE" => $rz_b2_options["product-hover-effect"],
		),
		false
	);
	if ($arParams['GIFTS_PLACE'] === 'BOTTOM') $strGifts = ob_get_clean();
}

if (strlen($arResult["ERROR_MESSAGE"]) <= 0)
{
	if (!empty($arResult["WARNING_MESSAGE"]) && is_array($arResult["WARNING_MESSAGE"]))
	{
		CRZBitronic2CatalogUtils::ShowMessage(array("MESSAGE"=>$arResult["WARNING_MESSAGE"], "TYPE"=>"ERROR"));
	}

	$arResult['COLUMNS'] = array();
	$bShowName = $bShowProps = $bShowDelay = $bShowDelete = false;

	if (is_array($arResult['GRID']['HEADERS'])) {
		foreach ($arResult['GRID']['HEADERS'] as $id => $arHeader) {
			switch ($arHeader['id']) {
				case 'NAME':  $arResult['COLUMNS']['NAME'] = $bShowName  = true; break;
				case 'PROPS': $arResult['COLUMNS']['NAME'] = $bShowProps = true; break;
				case 'QUANTITY': $arResult['COLUMNS']['QUANTITY'] = true; break;
				case 'PRICE':    $arResult['COLUMNS']['PRICE']    = true; break;
				case 'DISCOUNT': $arResult['COLUMNS']['DISCOUNT'] = true; break;
				case 'SUM':      $arResult['COLUMNS']['SUM']      = true; break;
				case 'DELAY':  $arResult['COLUMNS']['ACTIONS'] = $bShowDelay  = true; break;
				case 'DELETE': $arResult['COLUMNS']['ACTIONS'] = $bShowDelete = true; break;
				default: break;
			};
		}
	}
	
	$normalCount    = count($arResult["ITEMS"]["AnDelCanBuy"]);
	$delayCount     = count($arResult["ITEMS"]["DelDelCanBuy"]);
	$subscribeCount = count($arResult["ITEMS"]["ProdSubscribe"]);
	$naCount        = count($arResult["ITEMS"]["nAnCanBuy"]);

	if ($naCount > 0) {
		foreach ($arResult["ITEMS"]["nAnCanBuy"] as $key => $arItem) {
			if ($arItem['DELAY'] == "Y") {
				$delayCount++;
                $naCount--;
				continue;
			}
		}
	}

	$normalShow    = ($normalCount    > 0 || true);
	$delayShow     = ($delayCount     > 0);
	$subscribeShow = ($subscribeCount > 0);
	$naShow        = ($naCount        > 0);

	$delayActive     = ($_SESSION['RZ_BASKET_TAB'] == 'delay'     && $delayShow)     ? ' active' : '';
	$subscribeActive = ($_SESSION['RZ_BASKET_TAB'] == 'subscribe' && $subscribeShow) ? ' active' : '';
	$naActive        = ($_SESSION['RZ_BASKET_TAB'] == 'na'        && $naShow)        ? ' active' : '';
	$normalActive    = ($_SESSION['RZ_BASKET_TAB'] == 'items'
	                    || !($delayActive || $subscribeActive || $naActive))        ? ' active' : '';

	$noJSpage = CRZBitronic2CatalogUtils::noJsPage();
	$bShowStore = $arResult['USE_STORE'];
	$catalogParams = $arResult['CATALOG_PARAMS'];
	?>
		<div id="basket_form_container">
			<a href="<?=htmlspecialcharsbx(str_replace('#ID#', 'all', $arUrls['delete']))?>" class="btn-delete pseudolink with-icon" id="basket-delete-all" data-action="delete" data-id="all">
				<i class="flaticon-trash29"></i>
				<span class="btn-text"><?=GetMessage("BITRONIC2_SALE_CLEAR")?></span>
			</a>
			
			<div class="um_tabs"><?

			if ($normalShow):
			?>

				<a href="#basket-big" class="um_tab<?=$normalActive?> basket-btn" id="basket_toolbar_button" data-tab="items">
					<i class="flaticon-shopping109"></i>
					<span class="btn-text"><?=GetMessage("BITRONIC2_SALE_BASKET_ITEMS_READY")?> <span class="hidden-xs"><?=GetMessage("BITRONIC2_SALE_BASKET_ITEMS")?> </span>(<span class="items-in-basket"><?=$normalCount?></span>)</span>
				</a><?

			endif;
			?><?
			if ($delayShow):
			?>

				<a href="#waitlist-big" class="um_tab<?=$delayActive?> waitlist-btn" id="basket_toolbar_button_delayed" data-tab="delay">
					<i class="flaticon-verified18"></i>
					<span class="btn-text"><?=GetMessage("BITRONIC2_SALE_BASKET_ITEMS_DELAYED")?> <span class="hidden-xs"><?=GetMessage("BITRONIC2_SALE_BASKET_ITEMS")?> </span>(<span class="items-in-waitlist"><?=$delayCount?></span>)</span>
				</a><?

			endif;
			?><?
			if ($naShow):
			?>

				<a href="#nalist-big" class="um_tab<?=$naActive?> waitlist-btn" id="basket_toolbar_button_not_available" data-tab="na">
					<i class="flaticon-25-1"></i>
					<span class="btn-text"><?=GetMessage("BITRONIC2_SALE_BASKET_ITEMS_NOT_AVAILABLE")?> <span class="hidden-xs"><?=GetMessage("BITRONIC2_SALE_BASKET_ITEMS")?> </span>(<span class="items-in-nalist"><?=$naCount?></span>)</span>
				</a><?

			endif;
			?>

			</div>
	
			<?
			if ($normalShow)     include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");
			if ($delayShow)      include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_delayed.php");
			/* TODO
			if ($subscribedShow) include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_subscribed.php");*/
			if ($naShow)         include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_not_available.php");
			?>
			<footer>
			<?
			if ($normalCount > 0):
				if ($arParams['GIFTS_PLACE'] === 'BOTTOM') echo $strGifts;
				?>
				<div class="text-justify justify-fix">
					<a href="<?=($_SESSION['RZ_B2_BASKET_PREV_PAGE']?:SITE_DIR)?>" class="btn-return">
						<span class="text"><?=GetMessage('BITRONIC2_SALE_RETURN')?></span>
					</a>
					<?if($arParams['SHOW_ONECLICK']):?>
					<div class="one-click-buy-wrap">
						<button type="button" class="action one-click-buy" data-toggle="modal" data-target="#modal_quick-buy" data-basket="Y">
							<i class="flaticon-shopping220"></i>
							<span class="text"><?=GetMessage('BITRONIC2_ONECLICK_BUTTON')?></span>
						</button>
						<span class="helper"><?=GetMessage('BITRONIC2_ONECLICK_HELPER')?></span>
					</div>
					<?endif?>
					<!-- if authorized, the link below leads to order-step2
					if not, then to order-step1, which contains authorization/registration -->
					<a href="<?=$arParams['PATH_TO_ORDER']?>" class="btn-main btn-continue" onclick="checkOut();"><span class="text"><?=GetMessage('BITRONIC2_SALE_ORDER')?></span></a>
				</div>
				<?
				/** @noinspection PhpDynamicAsStaticMethodCallInspection */
				$infoContent = CMain::GetFileContent($_SERVER['DOCUMENT_ROOT'].SITE_DIR."include_areas/basket/info.php");
				$isExitstInfo = !empty($infoContent);?>
				<div class="basket-footer-info">
					<?$APPLICATION->IncludeComponent('bitrix:main.include', '', array("PATH" => SITE_DIR."include_areas/basket/info.php", "AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php"))?>
				</div>
			<?endif?>
				<? if (!$isExitstInfo):?>
					<span class="shopping-bg">
						<i class="flaticon-shopping109"></i>
					</span>
				<? endif ?>
			</footer>
		</div>
		<input type="hidden" id="column_headers" value="<?=CUtil::JSEscape(implode(array('NAME','DISCOUNT','PROPS','DELETE','DELAY','PRICE','QUANTITY','SUM'), ","))?>" />
		<input type="hidden" id="offers_props" value="<?=CUtil::JSEscape(implode($arParams["OFFERS_PROPS"], ","))?>" />
		<input type="hidden" id="action_var" value="<?=CUtil::JSEscape($arParams["ACTION_VARIABLE"])?>" />
		<input type="hidden" id="quantity_float" value="<?=$arParams["QUANTITY_FLOAT"]?>" />
		<input type="hidden" id="count_discount_4_all_quantity" value="<?=($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N"?>" />
		<input type="hidden" id="price_vat_show_value" value="<?=($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N"?>" />
		<input type="hidden" id="hide_coupon" value="<?=($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N"?>" />
		<input type="hidden" id="use_prepayment" value="<?=($arParams["USE_PREPAYMENT"] == "Y") ? "Y" : "N"?>" />

			<?//<input type="hidden" name="BasketOrder" value="BasketOrder" />?>
			<!-- <input type="hidden" name="ajax_post" id="ajax_post" value="Y"> -->
	<?
}
else
{
	if (!empty($arResult["GRID"]["ROWS"])) {
		CRZBitronic2CatalogUtils::ShowMessage(array("MESSAGE"=>$arResult["ERROR_MESSAGE"], "TYPE"=>"ERROR"));
	} else {
		echo '<p>', $arResult["ERROR_MESSAGE"], '</p>';
	}
	echo '
		<footer>
			<span class="shopping-bg">
				<i class="flaticon-shopping109"></i>
			</span>
		</footer>
	';
}

if ($_POST['rz_ajax_no_header'] === 'y') die();

?>
	</form>
</main>

<?
// echo "<pre style='text-align:left;'>";var_export($arResult);echo "</pre>";
