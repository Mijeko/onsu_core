<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!CModule::IncludeModule('iblock')) die('Error: Module "iblock" required.');
use Bitrix\Main\Loader;

if(empty($arParams['CACHE_FILTER']))
    $arParams['CACHE_FILTER'] = 'Y';
global $ys_options;

if($arParams['PRODUCT_DISPLAY_MODE'] == 'D' || !$arParams['PRODUCT_DISPLAY_MODE'])
    $arParams['PRODUCT_DISPLAY_MODE'] = $ys_options['sku_type'];
// fill offers properties is set PRODUCT_DISPLAY_MODE = SB
if($arParams['PRODUCT_DISPLAY_MODE'] == 'SB')
{
    foreach($arParams['OFFER_TREE_PROPS'] as $tree_prop)
    {
        if(!in_array($tree_prop, $arParams['OFFERS_PROPERTY_CODE']))
            $arParams['OFFERS_PROPERTY_CODE'][] = $tree_prop;
    }
    $arParams['LIST_OFFERS_LIMIT'] = 0;
}

// hack for correct work with SKU
if(is_array($arParams["OFFERS_PROPERTY_CODE"]))
    $arParams["OFFERS_PROPERTY_CODE"] = array_diff($arParams["OFFERS_PROPERTY_CODE"], array(0, null));
if(empty($arParams["OFFERS_PROPERTY_CODE"]))
    $arParams["OFFERS_PROPERTY_CODE"] = array('1');


global $APPLICATION;
$module_name = "yenisite.main_spec";

/*---ADD2BASKET PAGE---*/
if($_REQUEST["ys_ms_ajax_call"] === "y" && $_REQUEST["add2basket"]  === "y" )
{
    $APPLICATION->RestartBuffer();
    $message = "error";
    if (CModule::IncludeModule("sale") && CModule::IncludeModule("catalog")){
        if(Add2BasketByProductID($_REQUEST['id'])){
            $message = GetMessage("NOW_IN_BASKET");
        }
    }
    echo $message;
    require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_after.php");
    die();
}

/*SAVE PARAM*/
$save_param = new CPHPCache();
$lifetime = $arParams['CACHE_TIME'] ? intval($arParams['CACHE_TIME']) : 60*60*24;

if($_REQUEST["ys_ms_ajax_call"] === "y")
{
    $site_id = htmlspecialchars($_REQUEST["site_id"]);
}
else
{
    $site_id = SITE_ID;

    if ($this->arParams["CACHE_TYPE"] == "N" || ($this->arParams["CACHE_TYPE"] == "A" && COption::getOptionString("main", "component_cache_on", "Y") == "N"))
    {
        CPHPCache::Clean("ys_ms_params_".$site_id, "/");
    }elseif($save_param->InitCache($lifetime, "ys_ms_params_".$site_id, "/")) {
        $vars = $save_param->GetVars();
        if($arParams != $vars["arParams"])
            CPHPCache::Clean("ys_ms_params_".$site_id, "/");
    }
}


if($save_param->InitCache($lifetime, "ys_ms_params_".$site_id, "/")) :
    $vars = $save_param->GetVars();
    $arResult = $vars["arResult"];
    $arFilters = $vars["arFilters"];
    $arParams = $vars["arParams"];
    $site_dir = $vars["SITE_DIR"];
else:
    if($_REQUEST["ys_ms_ajax_call"] === "y"):
        die("cache");
    else:
        $new_time = $arParams['STICKER_NEW'] ? intval($arParams['STICKER_NEW']) : 14;
        $new_time = date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT")), time() - $new_time * 86400);
        $hit = $arParams['STICKER_HIT'] ? intval($arParams['STICKER_HIT']) : 100;
        $bestseller = $arParams['STICKER_BESTSELLER'] ? intval($arParams['STICKER_BESTSELLER']) : 3;

        $arParams["SHOW_TABS"] = (!empty($arParams["SHOW_TABS"]) && is_array($arParams["SHOW_TABS"])) ? $arParams["SHOW_TABS"] : array('NEW','HIT','SALE','BESTSELLER');
        $arParams["DEFAULT_TAB"] = $arParams["DEFAULT_TAB"] ? $arParams["DEFAULT_TAB"] : 'NEW';
        $arParams["IBLOCK_TYPE"] = $arParams["IBLOCK_TYPE"] ? $arParams["IBLOCK_TYPE"] : array("catalog_%", "catalog", "#SITE_ID#_%");
        if(!in_array($arParams["TABS_INDEX"], array('one_slider', 'list'))) $arParams["TABS_INDEX"] = 'one_slider';

        // ### SET TABS SORT
        $arTabsSort = array(
            'NEW' => $arParams['TAB_SORT_NEW'],
            'HIT' => $arParams['TAB_SORT_HIT'],
            'SALE' => $arParams['TAB_SORT_SALE'],
            'BESTSELLER' => $arParams['TAB_SORT_BESTSELLER'],
        );
        if (Loader::includeModule('romza.ocean')){
            $arTabsSort['BIG_DATA_TAB'] =  $arParams['TAB_SORT_BIG_DATA_TAB'];
        }
        asort($arTabsSort);

        // ### SET FILTERS
        $arDefaultFilters = Array('PROPERTY_CML2_LINK' => false);	// for not filter SKU elements
        if ($arParams['MAIN_SP_ON_AUTO_NEW'] !== 'N'){
            $arFilters = array(
                'NEW' => Array("LOGIC" => "OR", array('>DATE_CREATE' => $new_time)),
                'HIT' => Array("LOGIC" => "OR", array('>PROPERTY_WEEK_COUNTER' => $hit)),
                'SALE' => Array(),
                'BESTSELLER' => Array("LOGIC" => "OR", array('>=PROPERTY_SALE_INT' => $bestseller)),
            );
        } else {
            $arFilters = array(
                'NEW' => Array(),
                'HIT' => Array(),
                'SALE' => Array(),
                'BESTSELLER' => Array(),
            );
        }

        // ### SET FILTERS PROPERTY
        foreach($arFilters as $key => $arFilter)
        {
            if(strlen($arParams['TAB_PROPERTY_'.$key]))
                $arFilters[$key][] =  Array('!PROPERTY_'.$arParams['TAB_PROPERTY_'.$key] => false);

            else
                $arFilters[$key][] =  Array('!PROPERTY_'.$key => false);
        }

        $ibs = array();
        if(IntVal($arParams["IBLOCK_ID"])>0){
            $ibs[] = $arParams["IBLOCK_ID"];
        }else{
            if(is_array($arParams["IBLOCK_TYPE"])):
                foreach($arParams["IBLOCK_TYPE"] as $key=>$val)
                {
                    if($val){
                        $val = str_replace("#SITE_ID#", SITE_ID, $val);
                        $res = CIBlock::GetList(array(), array("TYPE" => $val));
                        while($ib = $res->GetNext())
                        {
                            if($ib['VERSION'] == 1)
                                $ibs[] =  $ib['ID'];
                        }
                    }
                }
            endif;
            array_unique($ibs);
        }

        $arResult = array();
        $arResult['TABS'] = array();

        foreach ($arTabsSort as $key => $sort_index)
        {
            if(!in_array($key, $arParams["SHOW_TABS"])) continue;

            $arFilter = $arFilters[$key];
            $arFilter = array(0 => $arFilter);
            $arFilter = array_merge($arDefaultFilters, $arFilter);

            if($arParams["HIDE_NOTAVAILABLE"] == 'Y') {
                $arFilter['CATALOG_AVAILABLE'] = 'Y';
                if (CModule::IncludeModule('yenisite.market')) {
                    $arIBIDNotTrace = array();
                    foreach ($ibs as $ibid) {
                        if (!CMarketCatalog::UsesQuantity($ibid)) {
                            $arIBIDNotTrace[] = $ibid;
                        }
                    }
                    if (count($arIBIDNotTrace)) {
                        $arFilter[] = array(
                            "LOGIC" => "OR",
                            '>PROPERTY_MARKET_QUANTITY' => '0',
                            'IBLOCK_ID' => $arIBIDNotTrace,
                        );
                    } else {
                        $arFilter['>PROPERTY_MARKET_QUANTITY'] = '0';
                    }
                }
            }

            if($arParams["HIDE_WITHOUTPICTURE"] == 'Y')
            {
                $arFilter[] = array(
                    "LOGIC" => "OR",
                    '!DETAIL_PICTURE' => false,
                    '!PREVIEW_PICTURE' => false,
                    '!PROPERTY_MORE_PHOTO' => false,
                );
            }

            $arNavParams = array();

            if (!empty($arParams["PAGE_ELEMENT_COUNT"])){
                $arNavParams = array(
                    'nTopCount' => $arParams["PAGE_ELEMENT_COUNT"]);
            }

            $arResult['TABS'][$key]['FILTER'] = $arFilter;
            $arResult['TABS'][$key]['LINK'] = '/'.strtolower($key).'/' ;
            $arResult['TABS'][$key]['COUNT'] = 0;
            $rsElements = CIBlockElement::GetList(
                Array(),
                Array(
                    'ACTIVE'=>'Y',
                    'SITE_ID'=>SITE_ID,
                    'IBLOCK_ID' => $ibs,
                    $arFilter
                ),
                false,
                $arNavParams,
                array()
            );
            $arResult['TABS'][$key]['COUNT'] = $rsElements->SelectedRowsCount();
        }
    endif;
endif;
if($save_param->StartDataCache()):
    $save_param->EndDataCache(array(
        "arResult"    => $arResult,
        "arFilters"    => $arFilters,
        "arParams"    => $arParams,
        "SITE_DIR"    => SITE_DIR
    ));
endif;

if($_REQUEST["ys_ms_ajax_call"] === "y" || $_REQUEST["ys_ms_sef"] === "y")
{
    $name = $_REQUEST["tab_block"];
    $arFilters = array($name => $arFilters[$name]);
    $arResult['TABS'] = array($name => $arResult['TABS'][$name]);
    if(!$site_dir) $site_dir = "/";
    define ( "SITE_DIR_2" , $site_dir);
}

if($_REQUEST["ys_ms_sef"] === "y")
{
    $arParams["ELEMENT_SORT_FIELD"] = $_REQUEST['order'] ? htmlspecialchars($_REQUEST['order']) : $APPLICATION->get_cookie("order");
    $arParams["ELEMENT_SORT_ORDER"] = $_REQUEST['by'] ? htmlspecialchars($_REQUEST['by']) : $APPLICATION->get_cookie("by");

    if(in_array($_REQUEST["page_count"], array(20, 40, 60))){
        $arParams["PAGE_ELEMENT_COUNT"] = htmlspecialchars($_REQUEST["page_count"]);
        $APPLICATION->set_cookie("page_count", $arParams["PAGE_ELEMENT_COUNT"]);
    }
}
$this->IncludeComponentTemplate();

?>