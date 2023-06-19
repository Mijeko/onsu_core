<?if (\Bitrix\Main\Loader::includeModule('advertising')):?>
    <?$APPLICATION->IncludeComponent(
        "bitrix:advertising.banner",
        "bitronic2",
        array(
            "TYPE" => "b2_catalog_section_in_goods",
            "NOINDEX" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "COMPONENT_TEMPLATE" => "bitronic2",
            "QUANTITY" => "1",
            "PLACE_CLASS" => "banner-catalog",
        ),
        false
    );?>
<?else:?>
    <?$APPLICATION->IncludeComponent(
        "yenisite:proxy",
        "bitronic2",
        array(
            "NOINDEX" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "COMPONENT_TEMPLATE" => "bitronic2",
            "REMOVE_POSTFIX_IN_NAMES" => "N",
            "QUANTITY" => "1",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "PLACE_CLASS" => "banner-catalog"
        ),
        false
    );?>
<?endif?>