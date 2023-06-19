<?php
if (Bitrix\Main\Loader::includeModule('catalog')){
    $APPLICATION->IncludeComponent(
        "yenisite:proxy",
        "viewed",
        array(
            "COMPONENT_TEMPLATE" => "viewed",
            "RESIZER_ITEM" => "#RESIZER_FOR_VIEWD_ITEMS_RESIZER_SET#",
        ),
        false
    );
}