<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<main data-catalog-banner-pos="middle-to-top" class="container catalog-page<?= $noAside ?>" id="catalog-page"
      data-page="catalog-page">
    <div class="row">
        <aside class="catalog-aside col-sm-12 col-md-3 col-xxl-2" id="catalog-aside">
            <? if ($arResult['MENU_CATALOG'] == 'side'): ?>
                <div id="catalog-at-side" class="catalog-at-side minified">
                    <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "EDIT_TEMPLATE" => "include_areas_template.php", "PATH" => SITE_DIR . "include_areas/header/menu_catalog.php"), false, array("HIDE_ICONS" => "Y")); ?>
                </div>
            <? endif ?>
            <?if ($arResult['FILTER_PLACE'] == 'side' && 'Y' == $rz_b2_options['block_show_ad_banners']):?>
                <div id="filter-at-side">
                    <? include 'banners-top.php';?>
                </div>
            <?endif?>
        </aside>
        <div class="catalog-main-content col-sm-12 col-md-9 col-xxl-10">
            <?
            $APPLICATION->IncludeComponent("bitrix:catalog.section.list", "catalog_lvl_first", array(
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
                "TOP_DEPTH" => '3',
                "SECTION_URL" => "",
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "RESIZER_ITEM" => $arParams["RESIZER_SECTIONS_LVL_FIRST"],
                "SET_METTA" => $bShowFirstLvlOfCatalog,
                "ADD_SECTIONS_CHAIN" => "N",
                "VIEW_MODE" => "TEXT",
                "SHOW_PARENT_NAME" => "N",
                "SECTION_ID" => $arCurSection['ID'],
            ),
                $component
            ); ?>
            <?include "banners-bottom.php"?>
        </div>
    </div>
</main>

