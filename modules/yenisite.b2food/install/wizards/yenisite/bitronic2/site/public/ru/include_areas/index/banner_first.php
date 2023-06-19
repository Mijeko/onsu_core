<?global $rz_b2_options?>
<?if(\Bitrix\Main\Loader::includeModule("advertising")):?>
<?$APPLICATION->IncludeComponent(
	"bitrix:advertising.banner", 
	"bitronic2", 
	array(
		"TYPE" => "b2_index_double",
		"NOINDEX" => "Y",
        "PLACE_CLASS" => "container sBannerTwo",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"COMPONENT_TEMPLATE" => "bitronic2",
		"QUANTITY" => "2",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"ORDER_BANNER" => $rz_b2_options['order-sBannerTwo']
	),
	false
);?>
<?else:?>
    <?$APPLICATION->IncludeComponent(
        "yenisite:proxy",
        "bitronic2",
        array(
            "PLACE_CLASS" => "container sBannerTwo",
            "NOINDEX" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "COMPONENT_TEMPLATE" => "bitronic2",
            "REMOVE_POSTFIX_IN_NAMES" => "N",
            "QUANTITY" => "2",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "ORDER_BANNER" => $rz_b2_options['order-sBannerTwo']
        ),
        false
    );?>
<?endif?>