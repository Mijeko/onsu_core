<?global $rz_b2_options;
$APPLICATION->IncludeComponent(
    "bitrix:catalog.section.list",
    "main",
    array(
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => "#CATALOG_IBLOCK_ID#",
        "COUNT_ELEMENTS" => "Y",
        "TOP_DEPTH" => "2",
        "SECTION_URL" => "",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "604800",
        "CACHE_GROUPS" => "N",
        "ADD_SECTIONS_CHAIN" => "N",
        "VIEW_MODE" => "TEXT",
        "SHOW_PARENT_NAME" => "N",
        "COMPONENT_TEMPLATE" => "main",
        "SECTION_ID" => $_REQUEST["SECTION_ID"],
        "SECTION_CODE" => "",
        "SECTION_FIELDS" => array(
            0 => "",
            1 => "",
        ),
        "SECTION_USER_FIELDS" => array(
            0 => "UF_IMG_BLOCK_FOTO",
            1 => "",
        ),
        "RESIZER_SECTION_ICON" => "#SMALL_BASKET_ICON_RESIZER_SET#",
        "RESIZER_SECTION_LARGE" => "#SECTION_LARGE_RESIZER_SET#",
        "RESIZER_SECTION_BIG" => "#SECTION_BIG_RESIZER_SET#",
        "CATEGORIES_ORDER" => $rz_b2_options['order-sCategories']
    ),
    false
);
