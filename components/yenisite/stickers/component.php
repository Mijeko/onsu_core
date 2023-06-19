<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Loader;
use Yenisite\Catchbuy\Catchbuy;

if(!function_exists('yenisite_date_to_time'))
{
	function yenisite_date_to_time ($date)
	{
		list($date, $time) = explode(" ", $date); 
		list($day, $month, $year) = explode(".", $date); 
		list($hour, $minute, $second) = explode(":", $time); 
		return mktime($hour, $minute, $second, $month, $day, $year); 
	}
}
if (empty($arParams['TAB_PROPERTY_NEW'])) $arParams['TAB_PROPERTY_NEW'] = 'NEW';
if (empty($arParams['TAB_PROPERTY_HIT'])) $arParams['TAB_PROPERTY_HIT'] = 'HIT';
if (empty($arParams['TAB_PROPERTY_SALE'])) $arParams['TAB_PROPERTY_SALE'] = 'SALE';
if (empty($arParams['TAB_PROPERTY_BESTSELLER'])) $arParams['TAB_PROPERTY_BESTSELLER'] = 'BESTSELLER';

$arElement = $arParams["ELEMENT"];
if(!is_array($arElement)) return;

// catch buy
$bCatchBuy = Loader::includeModule('yenisite.catchbuy');


//discount
$pr = 0;
$kr = 0;
$kk = 0; 
$disc = 0;
if (is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && count($arElement["OFFERS"]) > 0):
	foreach ($arElement["OFFERS"] as $arOffer):
		foreach ($arOffer['PRICES'] as $k => $price) {
			if ($price['VALUE'] < $pr || $pr == 0) {
				$pr = $price['VALUE'];
				$kr = $k;
				$arElement['PRICES'][$kr] = $arOffer['PRICES'][$kr];
			}
		}
		$price = $arOffer['PRICES'][$kr]['VALUE'];
		if ($arOffer['PRICES'][$kr]['DISCOUNT_DIFF']) {
			$disc = $arOffer['PRICES'][$kr]['DISCOUNT_PERCENT'] ?: (($arOffer["PRICES"][$kr]["VALUE"] - $arOffer["PRICES"][$kr]["DISCOUNT_VALUE"]) * 100 / $arOffer["PRICES"][$kr]["VALUE"]);
		}
	endforeach;
endif;
if($disc == 0 && CModule::IncludeModule('catalog'))
{
	foreach($arElement['PRICES'] as $k => $price) {
		if($price['VALUE'] < $pr || $pr == 0 ){
			$pr  = $price['VALUE'];
			$ppr = $price['PRINT_VALUE'];
			$kr  = $k;
		}
	}
	if($arElement['PRICES'][$kr]['DISCOUNT_DIFF']) {
		$disc = $arElement["PRICES"][$kr]['DISCOUNT_PERCENT'] ?: (($arElement["PRICES"][$kr]["VALUE"] - $arElement["PRICES"][$kr]["DISCOUNT_VALUE"])*100/$arElement["PRICES"][$kr]["VALUE"]);
	}
}
$params = array();
$params['STICKER_NEW'] = $arParams['STICKER_NEW'];
$params['STICKER_HIT'] = $arParams['STICKER_HIT'];
$params['STICKER_BESTSELLER'] = $arParams['STICKER_BESTSELLER'];

/*if($arElement["IBLOCK_ID"])
{
	if($arParams["CATALOG"] == "Y")
	{
		$save_param = new CPHPCache();
		if($save_param->InitCache(86400*14, SITE_ID."_".$arElement["IBLOCK_ID"], "/ys_sticker_params/"))
			if($params != $save_param->GetVars())
				CPHPCache::Clean(SITE_ID."_".$arElement["IBLOCK_ID"], "/ys_sticker_params/");
		if($save_param->StartDataCache()):
			$save_param->EndDataCache($params);
		endif;
		unset($save_param);
	}else{
		$save_param = new CPHPCache();
		if($save_param->InitCache(86400*14, SITE_ID."_".$arElement["IBLOCK_ID"], "/ys_sticker_params/"))
		{
			$params = $save_param->GetVars();
		}
		unset($save_param);
		if(!is_array($params)) return;
	}
}*/
if ($arParams['MAIN_SP_ON_AUTO_NEW'] !== 'N'){
	$params['STICKER_NEW'] = $params['STICKER_NEW'] ? IntVal($params['STICKER_NEW']) : 14 ;
	$params['STICKER_NEW_DELTA_TIME']  = 86400 * $params['STICKER_NEW'] ; // 86400 - 1 day in seconds
	$params['STICKER_NEW_START_TIME'] = time() - $params['STICKER_NEW_DELTA_TIME'] ;
	$params['STICKER_HIT'] = $params['STICKER_HIT'] ? IntVal($params['STICKER_HIT']) : 100 ;
	$params['STICKER_BESTSELLER'] = $params['STICKER_BESTSELLER'] ? IntVal($params['STICKER_BESTSELLER']) : 3 ;

	$arResult = array();

	if($arElement["PROPERTIES"][$arParams['TAB_PROPERTY_NEW']]["VALUE"] || MakeTimeStamp($arElement['DATE_CREATE']) > $params['STICKER_NEW_START_TIME'])
		$arResult["NEW"] = true;
	if($arElement["PROPERTIES"][$arParams['TAB_PROPERTY_HIT']]["VALUE"] || $arElement["PROPERTIES"]["WEEK_COUNTER"]["VALUE"] >= $params['STICKER_HIT'])
		$arResult["HIT"] = true;
	if($arElement["PROPERTIES"][$arParams['TAB_PROPERTY_SALE']]["VALUE"] || $disc > 0)
	{
		$arResult["SALE"] = true;
		$arResult["SALE_DISC"] = $disc;
	}
	if($arElement["PROPERTIES"][$arParams['TAB_PROPERTY_BESTSELLER']]["VALUE"] || $arElement["PROPERTIES"]["SALE_INT"]["VALUE"] >= $params['STICKER_BESTSELLER'])
		$arResult["BESTSELLER"] = true;
} else
{
	if($arElement["PROPERTIES"][$arParams['TAB_PROPERTY_NEW']]["VALUE"]) $arResult["NEW"] = true;
	if($arElement["PROPERTIES"][$arParams['TAB_PROPERTY_HIT']]["VALUE"]) $arResult["HIT"] = true;
	if($arElement["PROPERTIES"][$arParams['TAB_PROPERTY_SALE']]["VALUE"]) {
		$arResult["SALE"] = true;
		$arResult["SALE_DISC"] = $disc;
	}
	if($arElement["PROPERTIES"][$arParams['TAB_PROPERTY_BESTSELLER']]["VALUE"]) $arResult["BESTSELLER"] = true;

}

	
if($bCatchBuy)
{
	$arResult["CATCHBUY"] = Catchbuy::isProductExist($arElement["ID"]);
}
$this->IncludeComponentTemplate();

return $arResult;

?>