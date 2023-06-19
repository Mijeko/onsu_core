<?
use Bitrix\Main\Loader;
use \Bitronic2\Mobile;

include_once "include_stop_statistic.php";
if(isset($_POST["rz_ajax"]) && $_POST["rz_ajax"] === "y")
{
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
}else{
    if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
}

if(!CModule::IncludeModule('iblock')
    || !CModule::IncludeModule('catalog')
    || !CModule::IncludeModule('sale')
    || $_SERVER['REQUEST_METHOD'] != 'POST'
    || !$_POST['action']
)
    die();

include_once "include_module.php";

include_once $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lang/".LANGUAGE_ID."/ajax.php";
include_once $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/lang/".LANGUAGE_ID."/header.php";

$addResult = array();
static $arParams = array();
$bCore = Loader::IncludeModule('yenisite.core');

switch($_POST['action'])
{
    case 'setQuantity':
        $successfulAdd = true;
        $new_quantity = $_POST['quantity'];
        $basketItem = CSaleBasket::GetByID($_POST['id']);
        if(!$basketItem['ORDER_ID'] && $basketItem['FUSER_ID'] == CSaleBasket::GetBasketUserID())
        {
            if($new_quantity > 0)
            {
                // check available quantity
                $arProduct = CCatalogProduct::GetByID($_POST['productId']);
                if($arProduct['QUANTITY_TRACE'] == 'Y' && $arProduct['CAN_BUY_ZERO'] != 'Y' && $arProduct['QUANTITY'] < $new_quantity)
                {
                    $codeError = 'not available quantity';
                    if ($arProduct['QUANTITY'] <= 0) {
                        $successfulAdd = false;
                    } else {
                        $new_quantity = $arProduct['QUANTITY'];
                    }
                }

                if($successfulAdd)
                {
                    if(CSaleBasket::Update($basketItem['ID'], array('QUANTITY' => $new_quantity))) {
                        $codeAction = 'update on new quantity '.$new_quantity;
                        if (!empty($codeError)) {
                            $successfulAdd = false;
                        }
                    }
                    else
                    {
                        $codeError = 'error in update API';
                        $successfulAdd = false;
                    }
                }
            }
            else{
                if(CSaleBasket::Delete($basketItem['ID']))
                {
                    $codeAction = 'delete';
                }
                else
                {
                    $successfulAdd = false;
                    $codeError = 'error in delete API';
                }
            }

            if($successfulAdd)
                $addResult = array('STATUS' => 'OK', 'MESSAGE' => $codeAction);
            else
                $addResult = array('STATUS' => 'ERROR', 'CODE' => $codeError);
        }
        break;

    case 'deleteAll':
        CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
        echo 'deleteAll';
        die();
        break;

    case 'updateBasket':
        include_once "include_options.php";
        include $_SERVER["DOCUMENT_ROOT"].SITE_DIR."include_areas/header/basket.php";
        die();
        break;

    case 'addList':
        if (Bitrix\Main\Loader::includeModule("sale") && Bitrix\Main\Loader::includeModule("catalog"))
        {
            $successfulListAdd = true;
            foreach($_POST['items'] as $id => $arItem)
            {
                $successfulAdd = true;
                $QUANTITY = 0;
                $product_properties = array();

                if (is_array($arItem['props'])) {
                    if (!defined('BX_UTF')) {
                        foreach ($arItem['props'] as &$arProp) {
                            $arProp = $APPLICATION->ConvertCharsetArray($arProp, 'UTF-8', 'windows-1251');
                        }
                        unset($arProp);
                    }
                    $product_properties = $arItem['props'];
                }

                if ($bCore && empty($arParams)) {
                    $arParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
                }

                if (!empty($arParams['OFFERS_CART_PROPERTIES']) || !empty($arItem['SKUprops'])){
                    $skuAddProps = $arItem['SKUprops'];
                    $iblockId = htmlspecialcharsbx($_REQUEST['iblock_id']);
                    $product_properties = \CIBlockPriceTools::GetOfferProperties(
                        $arItem['id'],
                        $iblockId,
                        $arParams['OFFERS_CART_PROPERTIES'],
                        $skuAddProps
                    );
                }

                $QUANTITY = $arItem['quantity'];
                if (0 >= $QUANTITY)
                {
                    $rsRatios = CCatalogMeasureRatio::getList(
                        array(),
                        array('PRODUCT_ID' => $arItem['id']),
                        false,
                        false,
                        array('PRODUCT_ID', 'RATIO')
                    );
                    if ($arRatio = $rsRatios->Fetch())
                    {
                        $intRatio = (int)$arRatio['RATIO'];
                        $dblRatio = doubleval($arRatio['RATIO']);
                        $QUANTITY = ($dblRatio > $intRatio ? $dblRatio : $intRatio);
                    }
                }
                // if (0 >= $QUANTITY)
                // $QUANTITY = 1;

                $arRewriteFields = array();
                if ($successfulAdd)
                {
                    if(!Add2BasketByProductID($arItem['id'], $QUANTITY, $arRewriteFields, $product_properties))
                    {
                        if ($ex = $APPLICATION->GetException())
                            $strError = $ex->GetString();
                        else
                            $strError = GetMessage("BITRONIC2_BASKET_UNKNOWN_ERROR");
                        $successfulAdd = false;
                    }
                }

                if (!$successfulAdd)
                {
                    $successfulListAdd = false;
                }
            }

            // if ($successfulListAdd)
            // {
            // $addResult = array('STATUS' => 'OK', 'MESSAGE' => GetMessage('BITRONIC2_BASKET_SUCCESS'));
            // }
            // else
            // {
            // $addResult = array('STATUS' => 'ERROR', 'MESSAGE' => $strError);
            // }
            die();
        }
        break;

    case 'addPopup':

        if(intval($_REQUEST['id']) > 0)
        {
            include_once "include_options.php";
            global $rz_b2_options;

            if ($bCore) {
                $arParams = \Yenisite\Core\Ajax::getParams('bitrix:catalog', false, CRZBitronic2CatalogUtils::getCatalogPathForUpdate());
            }
            if(!is_array($arParams) || empty($arParams)) {
                die(GetMessage('BITRONIC2_BASKET_SUCCESS'));
            }

            include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/components/bitrix/catalog/.default/include/prepare_params_element.php'; // @var $arPrepareParams

            $arPrepareParams['ELEMENT_ID'] = intval($_REQUEST['id']);
            $arPrepareParams['PARENT_ID'] = intval($_REQUEST['parentId']);
            $arPrepareParams['IBLOCK_ID_CATALOG'] = $arPrepareParams['IBLOCK_ID'];
            $arPrepareParams['IBLOCK_ID'] = (intval($_REQUEST['iblock_id']) > 0) ? intval($_REQUEST['iblock_id']) : $arPrepareParams['IBLOCK_ID'];
            $arPrepareParams['OFFER'] = (intval($_REQUEST['iblock_id_sku']) > 0) ? true : false;
            $arPrepareParams['IBLOCK_ID'] = (intval($_REQUEST['iblock_id_sku']) > 0) ? intval($_REQUEST['iblock_id_sku']) : $arPrepareParams['IBLOCK_ID'];
            $arPrepareParams['STORE_DISPLAY_TYPE'] = $rz_b2_options['store_amount_type'];
            $arPrepareParams['SLIDER_TYPE'] = $rz_b2_options['basket_popup_slider'];
            $arPrepareParams['SHOW_STARS'] = $rz_b2_options['block_show_stars'];
            $arPrepareParams['SHOW_ARTICLE'] = $rz_b2_options['block_show_article'];
            $arPrepareParams['HOVER-MODE'] =  Mobile::isMobile() ? 'border-n-shadow' : $rz_b2_options['product-hover-effect'];
            $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
            $arValuesRequest = $request->toArray();
            $arValuesRequest[$arParams['ACTION_VARIABLE']] = null;
            $request->set($arValuesRequest);
            if (CRZBitronic2CatalogUtils::isSale()){
                $arItem['ID'] = $arPrepareParams['ELEMENT_ID'];
                $bItemGift = CRZBitronic2CatalogUtils::checkIsGiftedItem($arItem);
                if ($bItemGift) {
                    $arErrors = array();
                    $arOrder = array('SITE_ID' => SITE_ID);
                    $arOrder['BASKET_ITEMS'] = CRZBitronic2CatalogUtils::getOptimalPricesOfItemsInBasket();
                    CSaleDiscount::DoProcessOrder($arOrder, array(), $arErrors);
                    $arPrepareParams['CACHE_SWITCH_BY_PRICE'] = $arOrder['BASKET_ITEMS'][$arPrepareParams['ELEMENT_ID']]['PRICE'];
                }
            }
            $APPLICATION->IncludeComponent("bitrix:catalog.element", "add2basket_popup", $arPrepareParams, false);

        }
        die();
        break;
}
$APPLICATION->RestartBuffer();
echo CUtil::PhpToJSObject($addResult);
die();