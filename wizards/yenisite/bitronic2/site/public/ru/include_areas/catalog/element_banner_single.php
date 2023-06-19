<?global $rz_b2_options;?>
<?if (\Bitrix\Main\Loader::includeModule('advertising')):?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:advertising.banner",
		"bitronic2",
		Array(
			"NUM" => $GLOBALS["rz_banner_num"]++,
			"TYPE" => "b2_catalog_element_single",
			"NOINDEX" => "Y",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600",
            'PLACE_CLASS' => 'sPrBannerOne',
            'ORDER_BANNER' => $rz_b2_options['order-sPrBannerOne']
		),
		false
	);?>
<?else:?>
    <?$APPLICATION->IncludeComponent(
        "yenisite:proxy",
        "bitronic2",
        array(
            "NUM" => $GLOBALS["rz_banner_num"]++,
            "NOINDEX" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "COMPONENT_TEMPLATE" => "bitronic2",
            "REMOVE_POSTFIX_IN_NAMES" => "N",
            "QUANTITY" => "1",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            'PLACE_CLASS' => 'sPrBannerOne',
            'ORDER_BANNER' => $rz_b2_options['order-sPrBannerOne']
        ),
        false
    );?>
<?endif?>
