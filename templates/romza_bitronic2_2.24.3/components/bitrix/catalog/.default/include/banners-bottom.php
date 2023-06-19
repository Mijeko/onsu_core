<?php
if ($rz_b2_options['block_show_ad_banners'] == 'Y'): ?>
    <div class="banners">
        <?
        $arParams['ADV_BANNER_TYPE'] = $arParams['ADV_BANNER_TYPE'] ?: 'b2_catalog_bottom';
        if (\Bitrix\Main\Loader::includeModule('advertising')) {
            $APPLICATION->IncludeComponent(
                "bitrix:advertising.banner",
                "catalog_bottom",
                Array(
                    "TYPE" => $arParams['ADV_BANNER_TYPE'],
                    "NOINDEX" => "Y",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "1000"
                ),
                $component,
                array("HIDE_ICONS" => "Y")
            );
        } else {
            $APPLICATION->IncludeComponent(
                "yenisite:proxy",
                "catalog_bottom",
                array(
                    "NOINDEX" => "Y",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "3600",
                    "COMPONENT_TEMPLATE" => "bitronic2",
                    "REMOVE_POSTFIX_IN_NAMES" => "N",
                    "QUANTITY" => "1",
                    "COMPOSITE_FRAME_MODE" => "A",
                    "COMPOSITE_FRAME_TYPE" => "AUTO",
                    'FILE' => $arParams['FILE_AD_BANNER_BOTTOM'],
                    'URL_BANNER' => $arParams['URL_BANNER_AD_BANNER_BOTTOM'],
                    'IMG_ALT' => $arParams['IMG_ALT_AD_BANNER_BOTTOM'],
                ),
                $component,
                array("HIDE_ICONS" => "Y")
            );
        }
        ?>
    </div>
<? endif; ?>
