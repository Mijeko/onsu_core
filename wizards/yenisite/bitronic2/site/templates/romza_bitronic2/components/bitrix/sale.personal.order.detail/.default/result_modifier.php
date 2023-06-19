<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $rz_b2_options;
if ($rz_b2_options['convert_currency']) {
    $arParams['CURRENCY_ID'] = $rz_b2_options['active-currency'];

    $currency = $arResult['CURRENCY'];
    $tmpOrder = &$arResult;
    $arBasketItems = &$arResult['BASKET'];
    $arShipments = &$arResult['SHIPMENT'];
    $arPayments = &$arResult['PAYMENT'];

    $tmpOrder['PRICE_FORMATED'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($tmpOrder['PRICE'], $currency, $arParams['CURRENCY_ID']));
    $tmpOrder['PRICE'] = CCurrencyRates::ConvertCurrency($tmpOrder['PRICE'], $currency, $arParams['CURRENCY_ID']);
    $tmpOrder['PRICE_DELIVERY_FORMATED'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($tmpOrder['PRICE_DELIVERY'], $currency, $arParams['CURRENCY_ID']));
    $tmpOrder['PRICE_DELIVERY'] = CCurrencyRates::ConvertCurrency($tmpOrder['PRICE_DELIVERY'], $currency, $arParams['CURRENCY_ID']);
    $tmpOrder['PRODUCT_SUM_FORMATED'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($tmpOrder['PRODUCT_SUM'], $currency, $arParams['CURRENCY_ID']));
    $tmpOrder['PRODUCT_SUM'] = CCurrencyRates::ConvertCurrency($tmpOrder['PRODUCT_SUM'], $currency, $arParams['CURRENCY_ID']);
    $tmpOrder['TAX_VALUE_FORMATED'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($tmpOrder['TAX_VALUE'], $currency, $arParams['CURRENCY_ID']));
    $tmpOrder['TAX_VALUE'] = CCurrencyRates::ConvertCurrency($tmpOrder['TAX_VALUE'], $currency, $arParams['CURRENCY_ID']);

    foreach ($arBasketItems as &$arBasketItem){
        $arBasketItem['PRICE_FORMATED'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arBasketItem['PRICE'], $currency, $arParams['CURRENCY_ID']));
        $arBasketItem['PRICE'] = CCurrencyRates::ConvertCurrency($arBasketItem['PRICE'], $currency, $arParams['CURRENCY_ID']);
        $arBasketItem['BASE_PRICE_FORMATED'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arBasketItem['BASE_PRICE'], $currency, $arParams['CURRENCY_ID']));
        $arBasketItem['BASE_PRICE'] = CCurrencyRates::ConvertCurrency($arBasketItem['BASE_PRICE'], $currency, $arParams['CURRENCY_ID']);
        $arBasketItem['DISCOUNT_PRICE_FORMATED'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arBasketItem['DISCOUNT_PRICE'], $currency, $arParams['CURRENCY_ID']));
        $arBasketItem['DISCOUNT_PRICE'] = CCurrencyRates::ConvertCurrency($arBasketItem['DISCOUNT_PRICE'], $currency, $arParams['CURRENCY_ID']);
        $arBasketItem['FORMATED_SUM'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency,  $arBasketItem['PRICE'] * $arBasketItem['QUANTITY']);
        $arBasketItem['DISCOUNT_PRICE'] = CCurrencyRates::ConvertCurrency($arBasketItem['DISCOUNT_PRICE'], $currency, $arParams['CURRENCY_ID']);
    }

    foreach ($arShipments as &$arShipment){
        $arShipment['PRICE_DELIVERY_FORMATED'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arShipment['PRICE_DELIVERY'], $currency, $arParams['CURRENCY_ID']));
        $arShipment['PRICE_DELIVERY'] = CCurrencyRates::ConvertCurrency($arShipment['PRICE_DELIVERY'], $currency, $arParams['CURRENCY_ID']);
        $arShipment['BASE_PRICE_DELIVERY'] = CCurrencyRates::ConvertCurrency($arShipment['BASE_PRICE_DELIVERY'], $currency, $arParams['CURRENCY_ID']);
    }

    foreach ($arPayments as &$arPayment){
        $arPayment['PRICE_FORMATED'] = CRZBitronic2CatalogUtils::getElementPriceFormat($arParams['CURRENCY_ID'] ? : $currency, CCurrencyRates::ConvertCurrency($arPayment['SUM'], $currency, $arParams['CURRENCY_ID']));
        $arPayment['SUM'] = CCurrencyRates::ConvertCurrency($arPayment['SUM'], $currency, $arParams['CURRENCY_ID']);
    }
}