<?if(\Bitrix\Main\Loader::includeModule("advertising")):?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:advertising.banner",
		"bitronic2",
		Array(
			"TYPE" => "b2_news",
			"NOINDEX" => "Y",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600"
		),
		false
	);?>
<?else:?>
    <?$APPLICATION->IncludeComponent(
        "yenisite:proxy",
        "bitronic2",
        array(
            "PLACE_CLASS" => "container",
            "NOINDEX" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "COMPONENT_TEMPLATE" => "bitronic2",
            "REMOVE_POSTFIX_IN_NAMES" => "N",
            "QUANTITY" => "1",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO"
        ),
        false
    );?>
<?endif?>