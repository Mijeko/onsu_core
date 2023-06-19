<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="account-page">
<?
if (!is_array($arParams['HISTORIC_STATUSES']) || empty($arParams['HISTORIC_STATUSES']))
	$arParams['HISTORIC_STATUSES'] = array('F');
$arChildParams = array(
	"PATH_TO_DETAIL" => $arResult["PATH_TO_DETAIL"],
	"PATH_TO_CANCEL" => $arResult["PATH_TO_CANCEL"],
	"PATH_TO_COPY" => $arResult["PATH_TO_LIST"].'?COPY_ORDER=Y&ID=#ID#',
	"PATH_TO_BASKET" => $arParams["PATH_TO_BASKET"],
	"SAVE_IN_SESSION" => $arParams["SAVE_IN_SESSION"],
	"ORDERS_PER_PAGE" => $arParams["ORDERS_PER_PAGE"],
	"SET_TITLE" =>$arParams["SET_TITLE"],
	"ID" => $arResult["VARIABLES"]["ID"],
	"NAV_TEMPLATE" => $arParams["NAV_TEMPLATE"],
	"ACTIVE_DATE_FORMAT" => $arParams["ACTIVE_DATE_FORMAT"],
	"HISTORIC_STATUSES" => $arParams["HISTORIC_STATUSES"],

	"CACHE_TYPE" => $arParams["CACHE_TYPE"],
	"CACHE_TIME" => $arParams["CACHE_TIME"],
	"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	"RESIZER_BASKET_ICON" => $arParams["RESIZER_BASKET_ICON"],
);

foreach ($arParams as $key => $val)
	if(strpos($key, "STATUS_COLOR_") !== false && strpos($key, "~") !== 0)
		$arChildParams[$key] = $val;

if($_REQUEST['filter_status'] =='canceled')
{
	unset($_REQUEST['filter_status']);
	$_REQUEST['filter_canceled'] = $_REQUEST['show_all'] = 'Y';
}
elseif(!isset($_REQUEST['filter_status']))
{
	$_REQUEST['show_all'] = 'Y';
}
else
{
	$_REQUEST['filter_history'] = in_array($_REQUEST['filter_status'], $arParams['HISTORIC_STATUSES']) ? 'Y' : 'N';
	$_REQUEST['filter_canceled'] = 'N';
}
	
$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.order.list",
	"",
	$arChildParams,
	$component
);
?>
</div>