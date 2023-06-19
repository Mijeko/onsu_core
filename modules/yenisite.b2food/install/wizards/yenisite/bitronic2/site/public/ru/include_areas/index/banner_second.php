<?global $rz_b2_options?>
<?if(\Bitrix\Main\Loader::includeModule("advertising")):?>
<?$APPLICATION->IncludeComponent(
	"bitrix:advertising.banner",
	"bitronic2",
	Array(
		"TYPE" => "b2_index_single",
		"NOINDEX" => "Y",
        "PLACE_CLASS" => "container sBannerOne",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
        "ORDER_BANNER" => $rz_b2_options['order-sBannerOne']
	),
	false
);?>
<?else:?>
    <?$APPLICATION->IncludeComponent(
        "yenisite:proxy",
        "bitronic2",
        array(
            "PLACE_CLASS" => "container sBannerOne",
            "NOINDEX" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "COMPONENT_TEMPLATE" => "bitronic2",
            "REMOVE_POSTFIX_IN_NAMES" => "N",
            "QUANTITY" => "1",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "ORDER_BANNER" => $rz_b2_options['order-sBannerOne']
        ),
        false
    );?>
<?endif?>