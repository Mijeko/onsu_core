<? if (IsModuleInstalled('romza.widgetinstagram')): ?>
    <? $APPLICATION->IncludeComponent(
        "yenisite:widgetinstagram",
        "main",
        array(
            "CACHE_TIME" => "36000",
            "CACHE_TYPE" => "A",
            "CLIENT_ID" => "",
            "CLIENT_SECRET_ID" => "",
            "COMPOSITE_FRAME_MODE" => "A",
            "COMPOSITE_FRAME_TYPE" => "AUTO",
            "REDIRECT_URL" => "",
            "COMPONENT_TEMPLATE" => ".default",
            "ACCESS_TOKEN" => "",
            "RESIZER_ITEM" => "#RESIZER_FOR_INSTAGRAM_IMG_RESIZER_SET#",
        ),
        false
    ); ?>
<? endif ?>
