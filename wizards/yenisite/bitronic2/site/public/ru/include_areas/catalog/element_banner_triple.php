<?global $rz_b2_options;?>
<?if (\Bitrix\Main\Loader::includeModule('advertising')):?>
<?$APPLICATION->IncludeComponent(
	"bitrix:advertising.banner", 
	"bitronic2", 
	array(
		"NUM" => $GLOBALS["rz_banner_num"]++,
		"TYPE" => "b2_catalog_element_triple",
		"NOINDEX" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"COMPONENT_TEMPLATE" => "bitronic2",
		"QUANTITY" => "3",
        'PLACE_CLASS' => 'sPrBannerThird',
        'ORDER_BANNER' => $rz_b2_options['order-sPrBannerThird']
	),
	false
);?>
<?else:?>
    <?$APPLICATION->IncludeComponent(
        "yenisite:proxy",
        "bitronic2",
        array(
            "NUM" => $GLOBALS["rz_banner_num"]++,
            "QUANTITY" => "3",
            "NOINDEX" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "COMPONENT_TEMPLATE" => "bitronic2",
            "REMOVE_POSTFIX_IN_NAMES" => "N",
            "QUANTITY" => "1",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            'PLACE_CLASS' => 'sPrBannerThird',
            'ORDER_BANNER' => $rz_b2_options['order-sPrBannerThird']
        ),
        false
    );?>
<?endif?>