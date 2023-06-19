<?php
namespace Yenisite\Catchbuy;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Events
{

    public static function OnOrderAddHandler($saleOrder)
    {
		// get all basket items from order
        $fields = $saleOrder->getFields();
        $values = $fields->getValues();
        $orderId = $values['ID'];
        $arProducts = array();
        /** @noinspection PhpDynamicAsStaticMethodCallInspection */
        $rs = \CSaleBasket::GetList(array(), array('=ORDER_ID' => $orderId), false, false, array('PRODUCT_ID', 'QUANTITY'));
        while ($ar = $rs->fetch()) {
            $arProducts[$ar['PRODUCT_ID']] = array(
                'ID' => $ar['PRODUCT_ID'],
                'QUANTITY' => $ar['QUANTITY']
            );
        }
        $arFilter = array(
            'filter' => array(
                'ACTIVE' => 'Y',
                'PRODUCT_ID' => array_keys($arProducts),
                'LID' => $values['LID']
            ),
            'select' => array(
                'ID',
                'PRODUCT_ID',
                'MAX_USES',
                'COUNT_USES'
            )
        );
        if($values['CANCELED'] == 'Y'){
            unset($arFilter['filter']['ACTIVE']);
        }
        // check whatever products has an Catchbuy entity
        $rs = Catchbuy::getList($arFilter);
        // check can buy PRODUCT_ID with it QUANTITY
        $arCatchbuy = array();
        if($values['CANCELED'] == 'Y'){
            while ($ar = $rs->fetch()) {
                    $minusCount = $ar['COUNT_USES'] - $arProducts[$ar['PRODUCT_ID']]['QUANTITY'];
                    if ($minusCount < 0) $minusCount = 0;
                    $arCatchbuy[$ar['ID']] = array(
                        'PRODUCT_ID' => $ar['PRODUCT_ID'],
                        'COUNT_USES' => $minusCount,
                        'ACTIVE' => 'Y',
                    );
            }
        } elseif($saleOrder->isNew() || $saleOrder->isClone()) {
            while ($ar = $rs->fetch()) {
                if ($ar['COUNT_USES'] < $ar['MAX_USES']) {
                    $plusCount = $ar['COUNT_USES'] + $arProducts[$ar['PRODUCT_ID']]['QUANTITY'];
                    if ($plusCount > $ar['MAX_USES']) $plusCount = $ar['MAX_USES'];
                    $arCatchbuy[$ar['ID']] = array(
                        'PRODUCT_ID' => $ar['PRODUCT_ID'],
                        'COUNT_USES' => $plusCount,
                    );
                    if ($plusCount >= $ar['MAX_USES']) {
                        $arCatchbuy[$ar['ID']]['ACTIVE'] = 'N';
                    }
                }
            }
        }
	
        foreach ($arCatchbuy as $ID => $arFields) {
            $arFields['LID'] = $values['LID'];
            $rs = Catchbuy::update($ID, $arFields, true);
            if (!$rs) {
                AddMessage2Log(GetMessage('YNS_CATCHBUY_CANT_UPDATE_CATCHBUY_ENTITY', array('#ID#' => $ID, '#FIELDS#' => print_r($arFields, true))), 'yenisite.catchbuy');
            }
        }
    }

    //check has catchbuy product witch in sale, and amount this product in catchbuy
    public static function OnBeforeSaleBasketItemSetFieldHandler($item)
    {
        $fields = $item->getFields();
        $values = $fields->getValues();
        if(self::UpdateQuantityBasketItem ($values))
        {
			$item->setFieldNoDemand('QUANTITY',$values['QUANTITY']);
			$item->save();
		}
    }

    public static function UpdateQuantityBasketItem(&$arFields)
    {
        $quantity = $arFields['QUANTITY'];
		$bChangeQuantity = false;
        //check has catchbuy this product in self
        $arList = Catchbuy::getListCatchbuyFromCache(SITE_ID);
        if (!empty($arList) && !empty($arFields['PRODUCT_ID'])) {
            if (!empty($arList[$arFields['PRODUCT_ID']])) {

                //get amount of product in catchbuy and check it on max value
                $arData = Catchbuy::getDataCatchbuyFromCache(SITE_ID, $arList[$arFields['PRODUCT_ID']]['ID']);
                $catchBuyProduct = $arData[$arFields['PRODUCT_ID']];

                if ($catchBuyProduct['COUNT_USES'] < $catchBuyProduct['MAX_USES']) {
                    $plusCount = $catchBuyProduct['COUNT_USES'] + $quantity;

                    //if quantity > max amount of catch buy then set quantity 1 or to max
                    if ($plusCount >= $catchBuyProduct['MAX_USES']) {
                        $deference = $catchBuyProduct['MAX_USES'] - $catchBuyProduct['COUNT_USES'];
                        if ($deference < $quantity) {
                            $arFields['QUANTITY'] = $deference;
							$bChangeQuantity = true;
                        }
                    }
                }
                unset($arFields['PRODUCT_ID']);
            }
        }
		
		return $bChangeQuantity;
    }
}