<?global $rz_b2_options;?>
<?if (\Bitrix\Main\Loader::includeModule('advertising')):?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:advertising.banner",
		"bitronic2",
		array(
			"NUM" => $GLOBALS["rz_banner_num"]++,
			"TYPE" => "b2_catalog_element_double",
			"NOINDEX" => "Y",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600",
			"COMPONENT_TEMPLATE" => "bitronic2",
			"QUANTITY" => "2",
            'PLACE_CLASS' => 'sPrBannerTwo',
            'ORDER_BANNER' => $rz_b2_options['sPrBannerTwo']
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
            "QUANTITY" => "2",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            'PLACE_CLASS' => 'sPrBannerTwo',
            'ORDER_BANNER' => $rz_b2_options['sPrBannerTwo']
        ),
        false
    );?>
<?endif?>