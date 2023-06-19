<?php
namespace Yenisite\Core;
use \Bitrix\Main\Loader;
use Yenisite\Core\Tools;
use Yenisite\Core\IBlock;

class Actions{
    public static $rz_prop_code_for_actions = 'RZ_ACTIONS_DATA';
    public static $rz_items_of_action = 'RZ_ITEMS';
    public static $rz_prop_code_for_sticker = 'SALE';
    private static $SITE_ID = '';

    public static function proccesAction($arDiscounts = array(), $idAction = '', $arParams = array(), $SITE_ID)
    {
        if (empty($idAction) || empty($arParams) || empty($SITE_ID)) return;
        $catalogIblockID = $arParams['CATALOG_IBLOCK_ID'];
        $actionIblockID = $arParams['IBLOCK_ID'];
        self::$SITE_ID = $SITE_ID;

        if (empty($arDiscounts) && !empty($idAction) && !empty($arParams)){
            self::clearActionFromItems($idAction,$catalogIblockID);
            self::clearItemsFromAction($idAction,$actionIblockID);
            return;
        }

        $arResult = array();
        $arDiscountsData = self::getDiscountsDataById($arDiscounts,$actionIblockID);
        $arProccesData = self::processDiscountData($arDiscountsData);
        $arFilter = $arProccesData['FILTER'];
        $arUnpacks = $arProccesData['UNPACK'];
        $catalogIblockID = $arFilter['IBLOCK_ID'] ? $arFilter['IBLOCK_ID'] : $catalogIblockID;

        self::clearActionFromItems($idAction,$catalogIblockID);
        $arElements = IBlock::getElementsByFilter($catalogIblockID,$arFilter,true);
        $arElements = self::proccesItemsAndSetDataOfAction($arElements,$catalogIblockID,$arUnpacks,$idAction);
        self::setStickerForItems($arElements,$catalogIblockID);

        self::addItemsForAction($arElements,$idAction,$catalogIblockID,$actionIblockID);
        IBlock::clearIblockCacheByTag($catalogIblockID);

        return $arResult;
    }

    private static function clearItemsFromAction($idAction = '', $iblockID){
        IBlock::updatePropOfElement('',$idAction,$iblockID,self::$rz_items_of_action);
    }

    private static function clearActionFromItems ($idAction = '', $catalogId = ''){
        if (empty($catalogId)) return array();

        $arFilter = array('PROPERTY_'.self::$rz_prop_code_for_actions => $idAction);
        $arElements = IBlock::getElementsByFilter($catalogId,$arFilter,true);

        foreach ($arElements as $arElement){
            $arPropSystemValues = $arElement['PROPERTIES'][self::$rz_prop_code_for_actions]['VALUE'];
            $arPropSystemValues = array_flip($arPropSystemValues);
            unset($arPropSystemValues[$idAction]);
            $arPropSystemValues = array_flip($arPropSystemValues);
            $arPropSystemValues = $arPropSystemValues ? : '';

            if (empty($arPropSystemValues)) {
                IBlock::updatePropOfElement($arPropSystemValues, $arElement['ID'], $catalogId, self::$rz_prop_code_for_sticker);
            }
            IBlock::updatePropOfElement($arPropSystemValues,$arElement['ID'],$catalogId,self::$rz_prop_code_for_actions);
        }

    }

    private static function addItemsForAction($arElements = array(), $idAction = '', $catalogIblock = '', $iblockIdAction = ''){
        if (empty($arElements) || empty($idAction) || empty($catalogIblock) || empty($iblockIdAction)) return false;

        $arSystemProp = array(
            'NAME' => GetMessage(self::$rz_items_of_action),
            'ACTIVE' => 'Y',
            'SMART_FILTER' => 'N',
            'MULTIPLE' => 'Y',
            'TYPE' => 'E',
            'LINK_IBLOCK_ID' => $catalogIblock,
            'LIST_TYPE' => 'C',
            'SORT' => '500',
        );

        $arPropAction = IBlock::createProps($iblockIdAction,self::$rz_items_of_action,$arSystemProp);

        $arElementsIDs = array();
        foreach ($arElements as $arElement){
            $arElementsIDs[] = $arElement['ID'];
        }

        IBlock::updatePropOfElement($arElementsIDs,$idAction,$iblockIdAction,self::$rz_items_of_action);
    }

    private static function setStickerForItems ($arElements,$catalogIblock){
        if (empty($arElements) || !is_array($arElements) || empty($catalogIblock) || !is_string($catalogIblock)) return;

        $arSystemProp = array(
            'NAME' => GetMessage(self::$rz_prop_code_for_sticker),
            'ACTIVE' => 'Y',
            'TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'SORT' => '500',
            'VALUES' => array(
                array('VALUE' => GetMessage('YES'))
            )
        );

        $arPropAction = IBlock::createProps($catalogIblock,self::$rz_prop_code_for_sticker,$arSystemProp);
        $arValues = IBlock::getEnumValuesOfProp(self::$rz_prop_code_for_sticker,$catalogIblock);
        foreach ($arElements as $arElement){
            IBlock::updatePropOfElement($arValues[self::$rz_prop_code_for_sticker][GetMessage('YES')],$arElement['ID'],$catalogIblock,self::$rz_prop_code_for_sticker);
        }
    }

    private static function proccesItemsAndSetDataOfAction ($arElements = array(), $catalogIblock, $arUnpack = array(), $actionId = ''){
        if (empty($arElements) || empty($actionId)) return false;

        $arSystemProp = array(
            'NAME' => GetMessage(self::$rz_prop_code_for_actions),
            'ACTIVE' => 'Y',
            'SMART_FILTER' => 'N',
            'MULTIPLE' => 'Y',
            'TYPE' => 'E',
            'LINK_IBLOCK_ID' => $catalogIblock,
            'LIST_TYPE' => 'C',
            'SORT' => '500',
        );

        $arPropAction = IBlock::createProps($catalogIblock,self::$rz_prop_code_for_actions,$arSystemProp);

        foreach ($arElements as $key => $arElement){
            $valueOfPropAction = $arElement['PROPERTIES'][self::$rz_prop_code_for_actions]['VALUE'] ? : array();
            if (!self::checkActions($arElement, $arUnpack)){
                if (in_array($actionId,$valueOfPropAction)) {
                    $valueOfPropAction = self::deleteItemFromNotListArray($valueOfPropAction,$actionId) ? : '';
                    IBlock::updatePropOfElement($valueOfPropAction,$arElement['ID'],$arElement['IBLOCK_ID'],self::$rz_prop_code_for_actions);
                }
                unset($arElements[$key]);
                continue;
            }
            if (!in_array($actionId,$valueOfPropAction)) {
                $valueOfPropAction[] = $actionId;
                IBlock::updatePropOfElement($valueOfPropAction,$arElement['ID'],$arElement['IBLOCK_ID'],self::$rz_prop_code_for_actions);
            }
        }

        return $arElements;
    }

    private static function processDiscountData ($arDiscountsData = array()){
        $arReturn = array();
        if (empty($arDiscountsData)) return $arReturn;
        $arFilter = &$arReturn['FILTER'];
        $arFilter['INCLUDE_SUBSECTIONS'] = "Y";

        foreach ($arDiscountsData as $arElement) {
            if (!empty($arElement['IBLOCK_ID'])) {
                $arFilter['IBLOCK_ID'] = $arElement['IBLOCK_ID'];
            } elseif (!empty($arElement['SECTION_ID'])) {
                $arFilter['SECTION_ID'] = $arElement['SECTION_ID'];
            } elseif (!empty($arElement['PRODUCT_ID'])) {
                $arFilter['ID'] = $arElement['PRODUCT_ID'];
            }
            if (!empty($arElement['UNPACK'])) {
                foreach ($arElement['UNPACK'] as $strUnPack) {
                    $arReturn['UNPACK'][] = $strUnPack;
                }
            }
        }

        return $arReturn;
    }

    public static function getDiscountsDataById($arDiscountsIds = array(),$actionsIblockId = ''){
        $arReturn = array();

        if (!Loader::IncludeModule('catalog') && empty($arDiscountsIds)) return $arReturn;

            $arFilter = array('SITE_ID' => self::$SITE_ID , 'ACTIVE' => 'Y');
            foreach ($arDiscountsIds as $discount) {
                $arFilter['ID'][] = $discount;
            }
            $cache_id = 'DISCOUNTS_' . implode("_", $arDiscountsIds).'_'.$actionsIblockId;

            $arReturn = Tools::getSavedValues($cache_id);

            if (empty($arReturn)) {

                $rsElement = \CCatalogDiscount::GetList(array(), $arFilter, false, false, array());
                $arIdDiscounts = array();

                while ($arElement = $rsElement->GetNext()) {
                    $returnElement = &$arReturn[$arElement['ID']];
                    if (!empty($arElement['PRODUCT_ID']) && !in_array($arElement['PRODUCT_ID'],$returnElement['PRODUCT_ID'])){
                        $returnElement['PRODUCT_ID'][] = $arElement['PRODUCT_ID'];
                    }
                    if (!empty($arElement['SECTION_ID']) && !in_array($arElement['SECTION_ID'],$returnElement['SECTION_ID'])){
                        $returnElement['SECTION_ID'][] = $arElement['SECTION_ID'];
                    }
                    if (!empty($arElement['IBLOCK_ID']) && !in_array($arElement['IBLOCK_ID'],$returnElement['IBLOCK_ID'])){
                        $returnElement['IBLOCK_ID'][] = $arElement['IBLOCK_ID'];
                    }
                    if (!empty($arElement['UNPACK']) && !in_array($arElement['UNPACK'],$returnElement['UNPACK'])){
                        $returnElement['UNPACK'][] = htmlspecialchars_decode($arElement['UNPACK']);
                    }
                }

                unset ($arElement, $rsElement);
                Tools::saveSomeValuesInCache($arReturn,$cache_id,true,$actionsIblockId);
            }

            return $arReturn;
    }


    public static function checkActions($arItem, $arUnpack)
    {
        $hasNeedDisc = false;
        $arProduct = $arItem;
        global $USER;
        $arProduct['SECTION_ID'] = $arItem['IBLOCK_SECTION_ID'];
        $discounts = \CCatalogDiscount::GetDiscount(
            $arProduct['ID'],
            $arProduct['IBLOCK_ID'],
            array(),
            $USER->GetGroups(),
            'N',
            self::$SITE_ID ,
            array()
        );
        if (!empty($discounts)) {
            foreach ($discounts as $discount) {
                if (array_search($discount['UNPACK'], $arUnpack) !== false) {
                    $hasNeedDisc = true;
                    break;
                }else{
                    if (!is_array($arUnpack)) {
                        $unpackDiscount = strval($discount['UNPACK']);
                        $unpackParams = strval($arUnpack);
                        $similarPercent = 0;
                        similar_text($unpackDiscount, $unpackParams, $similarPercent);
                    } else{
                        foreach ($arUnpack as $paramUnpack){
                            if (intval($similarPercent) > 80) break;
                            $unpackDiscount = strval($discount['UNPACK']);
                            $unpackParams = strval($paramUnpack);
                            $similarPercent = 0;
                            similar_text($unpackDiscount, $unpackParams, $similarPercent);
                        }
                    }
                    if (intval($similarPercent) > 80){
                        $hasNeedDisc = true;
                        break;
                    }
                }
            }
            unset($discount, $discounts);
        }
        return $hasNeedDisc;
    }

    private static function deleteItemFromNotListArray($array = array(), $value = ''){
        if (empty($array) || empty($value)) return $array;

        $arTmp = array_flip($array);
        unset($arTmp[$value]);
        return array_flip($arTmp);
    }
}
