<?php
if (\Bitrix\Main\Loader::includeModule('advertising')):
    $APPLICATION->IncludeComponent(
        "bitrix:advertising.banner",
        "bitronic2",
        Array(
            "FILTER" => "Y",
            "TYPE" => $arParams['ADV_BANNER_FILTER_TYPE'] ?: 'b2_catalog_filter',
            "NOINDEX" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "1000",
        ),
        $component,
        array("HIDE_ICONS" => "Y")
    );
else:
    $APPLICATION->IncludeComponent(
        "yenisite:proxy",
        "bitronic2",
        array(
            "NOINDEX" => "Y",
            "FILTER" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "COMPONENT_TEMPLATE" => "bitronic2",
            "REMOVE_POSTFIX_IN_NAMES" => "N",
            "QUANTITY" => "1",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            'FILE' => $arParams['FILE_AD_BANNER_TOP'],
            'URL_BANNER' => $arParams['URL_BANNER_AD_BANNER_TOP'],
            'IMG_ALT' => $arParams['IMG_ALT_AD_BANNER_TOP'],
        ),
        $component,
        array("HIDE_ICONS" => "Y")
    );
endif;