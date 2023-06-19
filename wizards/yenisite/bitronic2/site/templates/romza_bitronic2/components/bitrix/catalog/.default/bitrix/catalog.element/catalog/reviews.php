<?
global $arFilterReviews;
$arFilterReviews = array('ID' => $arResult['PROPERTIES'][$arParams['PROP_FOR_REVIEWS_ITEM']]['VALUE'])?>
<div class="combo-target review wow fadeIn review <?= ($arParams['DETAIL_INFO_MODE'] == 'full' && $arParams['DETAIL_INFO_FULL_EXPANDED'] == 'Y') ? ' shown' : '' ?> drag-section sPrInfReview" data-order="<?=$arParams['ORDER_DETAIL_BLOCKS']['order-sPrInfReview']?>" id="review">
    <div class="combo-header">
        <i class="flaticon-newspapre"></i>
        <span class="text"><?=$arParams['TITLE_TAB_REVIEWS_ITEM'] ? $arParams['TITLE_TAB_REVIEWS_ITEM'] : GetMessage('BITRONIC2_REVIEWS_ITEM')?></span><sup><?=count($arResult['PROPERTIES'][$arParams['PROP_FOR_REVIEWS_ITEM']]['VALUE'])?></sup>
    </div>
    <div class="combo-target-content">
        <div class="rewiew-list">
            <?$APPLICATION->IncludeComponent("bitrix:news.list","reviews_on_detail",Array(
                    "DISPLAY_DATE" => "N",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "Y",
                    "DISPLAY_PREVIEW_TEXT" => "Y",
                    "AJAX_MODE" => "N",
                    "FIELD_CODE" => array('DETAIL_PICTURE'),
                    "IBLOCK_TYPE" => $arParams['IBLOCK_REVIEWS_TYPE'],
                    "IBLOCK_ID" => $arParams['IBLOCK_REVIEWS_ID'],
                    "NEWS_COUNT" => $arParams['COUNT_REVIEWS_ITEM'],
                    "RESIZER_REVIEWS_IMG" => $arParams['RESIZER_REVIEWS_IMG'],
                    "SORT_BY1" => "ACTIVE_FROM",
                    "SORT_ORDER1" => "DESC",
                    "SORT_BY2" => "SORT",
                    "SORT_ORDER2" => "ASC",
                    "FILTER_NAME" => "arFilterReviews",
                    "CHECK_DATES" => "Y",
                    "PREVIEW_TRUNCATE_LEN" => $arParams['REVIEWS_TRUNCATE_LEN'],
                    "SET_TITLE" => "N",
                    "SET_BROWSER_TITLE" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "Y",
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                    "CACHE_TIME" => $arParams['CACHE_TIME'],
                    "CACHE_FILTER" => $arParams['CACHE_FILTER'],
                    "CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
                    "DISPLAY_TOP_PAGER" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_DESC_NUMBERING" => "Y",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams['CACHE_TIME'],
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "SET_STATUS_404" => "N",
                    "SHOW_404" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "N",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_ADDITIONAL" => ""
                )
            );?>
        </div>
    </div><!-- .combo-target-content -->
</div>