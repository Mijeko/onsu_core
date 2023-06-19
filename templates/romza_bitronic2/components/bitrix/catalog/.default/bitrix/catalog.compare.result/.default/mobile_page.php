<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

\Bitrix\Main\Localization\Loc::loadMessages(SITE_TEMPLATE_PATH . '/header.php');
$strDif = $arResult["DIFFERENT"] ? 'N' : 'Y';
?>

    <div class="compare-outer-wrapper mobile">
        <div class="m-compare-table" id="m-compare-table">
            <div class="m-compare-table__head">
                <div class="m-compare__container">
                    <div class="m-compare__header-info fixed m-items__compare">
                        <div class="m-compare__item">
                            <div id="m-items__primary">
                                <div class="slidee">
                                 <?foreach($arResult['ITEMS'] as &$arItem):
                                    $strMainID = $this->GetEditAreaId('compare_'.$arItem['ID']);
                                    $arItemIDs = array(
                                        'ID' => $strMainID,
                                        'PICT' => $strMainID.'_pict',
                                        'SECOND_PICT' => $strMainID.'_secondpict',
                                        //'STICKER_ID' => $strMainID.'_sticker',
                                        //'SECOND_STICKER_ID' => $strMainID.'_secondsticker',
                                        'BUY_LINK' => $strMainID.'_buy_link',
                                        'BUY_ONECLICK' => $strMainID.'_buy_oneclick',
                                        'BASKET_ACTIONS' => $strMainID.'_basket_actions',
                                        'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
                                        'SUBSCRIBE_LINK' => $strMainID.'_subscribe',
                                        'COMPARE_LINK' => $strMainID.'_compare_link',
                                        'FAVORITE_LINK' => $strMainID.'_favorite_link',
                                        'REQUEST_LINK' => $strMainID.'_request_link',

                                        'OLD_PRICE' => $strMainID.'_old_price',
                                        'PRICE' => $strMainID.'_price',
                                        'DSC_PERC' => $strMainID.'_dsc_perc',
                                        'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',
                                        //'PROP_DIV' => $strMainID.'_sku_tree',
                                        //'PROP' => $strMainID.'_prop_',
                                        //'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
                                        'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
                                        'AVAILABILITY' => $strMainID . '_availability',
                                    );
                                    $strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
                                    $imgTitle = (
                                    !empty($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
                                        ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
                                        : $arItem['NAME']
                                    );
                                    $arItem['strMainID'] = $strMainID;
                                    $arItem['arItemIDs'] = $arItemIDs;
                                    $arItem['strObName'] = $strObName;

                                    $bEmptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
                                    $bBuyProps = ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$bEmptyProductProperties);
                                    ?>
                                        <div class="item" id="<?=$arItemIDs['ID']?>">
                                            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link">
                                                <div class="photo">
                                                    <img class="lazy" data-original="<?=$arItem['PICTURE_PRINT']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['PICTURE_PRINT']['ALT']?>" title="<?= $imgTitle ?>">
                                                </div>
                                                <div class="name">
                                                    <span class="text"><?=$arItem['NAME']; ?></span>
                                                </div>
                                                <a class="hidden btn-close" href="<?=$arItem['~DELETE_URL']?>" data-id="<?=$arItem['ID']?>"></a>
                                            </a>
                                        </div><!-- /compare-item -->
                                <?endforeach; unset($arItem);?>
                                </div>
                            </div>
                            <div class="m-compare__item-number">
                                <span class="cur">1</span>
                                <span class="separate">/</span>
                                <span class="total"><?=count($arResult['ITEMS']); ?></span>
                                <button class="btn-delete pseudolink with-icon" data-original-title="<?=GetMessage('BITRONIC2_CATALOG_REMOVE_PRODUCT_MOBILE')?>">
                                    <i class="flaticon-trash29"></i>
                                </button>
                            </div>
                        </div>
                        <div class="m-compare__item">
                            <div id="m-items__secondary">
                                <div class="slidee">
                                    <?foreach($arResult['ITEMS'] as &$arItem):
                                        $strMainID = $this->GetEditAreaId('compare_'.$arItem['ID']);
                                        $arItemIDs = array(
                                            'ID' => $strMainID,
                                            'PICT' => $strMainID.'_pict',
                                            'SECOND_PICT' => $strMainID.'_secondpict',
                                            //'STICKER_ID' => $strMainID.'_sticker',
                                            //'SECOND_STICKER_ID' => $strMainID.'_secondsticker',
                                            'BUY_LINK' => $strMainID.'_buy_link',
                                            'BUY_ONECLICK' => $strMainID.'_buy_oneclick',
                                            'BASKET_ACTIONS' => $strMainID.'_basket_actions',
                                            'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
                                            'SUBSCRIBE_LINK' => $strMainID.'_subscribe',
                                            'COMPARE_LINK' => $strMainID.'_compare_link',
                                            'FAVORITE_LINK' => $strMainID.'_favorite_link',
                                            'REQUEST_LINK' => $strMainID.'_request_link',

                                            'OLD_PRICE' => $strMainID.'_old_price',
                                            'PRICE' => $strMainID.'_price',
                                            'DSC_PERC' => $strMainID.'_dsc_perc',
                                            'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',
                                            //'PROP_DIV' => $strMainID.'_sku_tree',
                                            //'PROP' => $strMainID.'_prop_',
                                            //'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
                                            'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
                                            'AVAILABILITY' => $strMainID . '_availability',
                                        );
                                        $strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
                                        $imgTitle = (
                                        !empty($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"])
                                            ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"]
                                            : $arItem['NAME']
                                        );
                                        $arItem['strMainID'] = $strMainID;
                                        $arItem['arItemIDs'] = $arItemIDs;
                                        $arItem['strObName'] = $strObName;

                                        $bEmptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
                                        $bBuyProps = ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$bEmptyProductProperties);
                                        ?>
                                        <div class="item" id="<?=$arItemIDs['ID']?>">
                                            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="link">
                                                <div class="photo">
                                                    <img class="lazy" data-original="<?=$arItem['PICTURE_PRINT']['SRC']?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arItem['PICTURE_PRINT']['ALT']?>" title="<?= $imgTitle ?>">
                                                </div>
                                                <div class="name">
                                                    <span class="text"><?=$arItem['name']; ?></span>
                                                </div>
                                                <a class="hidden btn-close" href="<?=$arItem['~DELETE_URL']?>" data-id="<?=$arItem['ID']?>"></a>
                                            </a>
                                        </div><!-- /compare-item -->
                                    <?endforeach; unset($arItem);?>
                                </div>
                            </div>
                            <div class="m-compare__item-number">
                                <span class="cur">1</span>
                                <span class="separate">/</span>
                                <span class="total"><?=count($arResult['ITEMS']); ?></span>
                                <button class="btn-delete pseudolink with-icon" data-original-title="<?=GetMessage('BITRONIC2_CATALOG_REMOVE_PRODUCT_MOBILE')?>">
                                    <i class="flaticon-trash29"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="m-compare__tumbler">
                        <label class="tumbler__section">
                            <span class="tumbler__title"><?=GetMessage('BITRONIC2_CATALOG_ONLY_DIFFERENT')?></span>
                                <span class="tumbler__element tumbler">
                                    <input  data-href="<? echo str_replace($arParams['ACTION_VARIABLE'], '', $arResult['COMPARE_URL_TEMPLATE']).'DIFFERENT='. $strDif ?>" rel="nofollow" type="checkbox" class="tumbler-switch" <? echo ($arResult["DIFFERENT"] ? ' checked="checked"' : ''); ?> name="differ-switch">
                                    <span class="tumbler__box">
                                        <span class="tumbler__switch">
                                            <span class="tumbler__track track__left"></span>
                                            <span class="tumbler__track track__right"></span>
                                            <span class="tumbler__bar"></span>
                                        </span>
                                    </span>
                                </span>
                        </label>
                    </div>

                </div>
            </div>
            <div class="m-compare-table__body">
                <div class="m-compare__container">
                    <?if ($arResult['HAS_GROUPS'] === true):?>
                            <div class="m-compare__section-header"><?=GetMessage('BITRONIC2_COMMON_PROPERTIES')?></div>
                    <?endif?>
                    <?if (!empty($arResult["SHOW_FIELDS"]))
                    {
                        foreach ($arResult["SHOW_FIELDS"] as $code => $arProp)
                        {
                            $arSkipArray = array("NAME", "PREVIEW_PICTURE", "DETAIL_PICTURE");

                            if(in_array($code, $arSkipArray))
                                continue;
                            if (!isset($arResult['FIELDS_REQUIRED'][$code]) || $arResult['DIFFERENT'])
                            {
                                $arCompare = array();
                                foreach($arResult["ITEMS"] as &$arItem)
                                {
                                    $arPropertyValue = $arItem["FIELDS"][$code];
                                    if (is_array($arPropertyValue))
                                    {
                                        sort($arPropertyValue);
                                        $arPropertyValue = implode(" / ", $arPropertyValue);
                                    }
                                    $arCompare[] = $arPropertyValue;
                                }
                                unset($arItem);
                            } ?>
                            <div class="m-compare__container">
                                <div class="m-compare__section-prop"><?=GetMessage("BITRONIC2_IBLOCK_FIELD_".$code)?></div>
                                <div class="m-compare__container m-items__compare">
                                    <div class="m-compare__item m-compare__item-primary">
                                        <div class="m-compare__prop-cur">&ndash;</div>
                                        <div class="m-compare__prop-all hidden">
                                            <?
                                            $i = 0;
                                            foreach($arResult["ITEMS"] as &$arItem)
                                            {
                                                ?>
                                                <span class="item_<?=$i++?>"><?=$arItem["FIELDS"][$code] ? $arItem["FIELDS"][$code] : '&ndash;'; ?></span>
                                                <?
                                            }
                                            unset($arItem);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="m-compare__item m-compare__item-secondary">
                                        <div class="m-compare__prop-cur">&ndash;</div>
                                        <div class="m-compare__prop-all hidden">
                                            <?
                                            $i = 0;
                                            foreach($arResult["ITEMS"] as &$arItem)
                                            {
                                                ?>
                                                <span class="item_<?=$i++?>"><?=$arItem["FIELDS"][$code] ? $arItem["FIELDS"][$code] : '&ndash;'; ?></span>
                                                <?
                                            }
                                            unset($arItem);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?
                        }
                    }?>

                    <?if (!empty($arResult["SHOW_OFFER_FIELDS"]))
                    {
                        foreach ($arResult["SHOW_OFFER_FIELDS"] as $code => $arProp)
                        {
                            if ($arResult['DIFFERENT'])
                            {
                                $arCompare = array();
                                foreach($arResult["ITEMS"] as &$arItem)
                                {
                                    $Value = $arItem["OFFER_FIELDS"][$code];
                                    if(is_array($Value))
                                    {
                                        sort($Value);
                                        $Value = implode(" / ", $Value);
                                    }
                                    $arCompare[] = $Value;
                                }
                                unset($arItem);
                            }
                            ?>
                            <div class="m-compare__container">
                                <div class="m-compare__section-prop"><?=GetMessage("BITRONIC2_IBLOCK_OFFER_FIELD_".$code)?></div>
                                <div class="m-compare__container m-items__compare">
                                    <div class="m-compare__item m-compare__item-primary">
                                        <div class="m-compare__prop-cur">&ndash;</div>
                                        <div class="m-compare__prop-all hidden">
                                            <?
                                            $i = 0;
                                            foreach($arResult["ITEMS"] as &$arItem)
                                            {
                                                ?>
                                                <span class="item_<?=$i++?>"><?=(is_array($arItem["OFFER_FIELDS"][$code])? implode("/ ", $arItem["OFFER_FIELDS"][$code]): $arItem["OFFER_FIELDS"][$code])?>
                                                    <?=(empty($arItem["OFFER_FIELDS"][$code])? '&ndash;' : '')?></span>
                                                <?
                                            }
                                            unset($arItem);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="m-compare__item m-compare__item-secondary">
                                        <div class="m-compare__prop-cur">&ndash;</div>
                                        <div class="m-compare__prop-all hidden">
                                            <?
                                            $i = 0;
                                            foreach($arResult["ITEMS"] as &$arItem)
                                            {
                                                ?>
                                                <span class="item_<?=$i++?>"><?=(is_array($arItem["OFFER_FIELDS"][$code])? implode("/ ", $arItem["OFFER_FIELDS"][$code]): $arItem["OFFER_FIELDS"][$code])?>
                                                    <?=(empty($arItem["OFFER_FIELDS"][$code])? '&ndash;' : '')?></span>
                                                <?
                                            }
                                            unset($arItem);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?
                        }
                    }?>
                 </div>

                <div class="m-compare__container">
                    <?if (!empty($arResult["SHOW_PROPERTIES"]))
                    {
                        foreach ($arResult["SHOW_PROPERTIES"] as $code => $arProperty)
                        {
                            if ($arResult['HAS_GROUPS'] === true && !empty($arProperty['GROUP_NAME'])) {
                            ?></div>
                             <div class="m-compare__container">
                                <div class="m-compare__section-header"><?= $arProperty['GROUP_NAME'] ?></div>
                            <?
                            continue;
                            }
                            ?>
                            <div class="m-compare__container">
                                <div class="m-compare__section-prop"><?= $arProperty["NAME"] ?></div>
                                <div class="m-compare__container m-items__compare">
                                    <div class="m-compare__item m-compare__item-primary">
                                        <div class="m-compare__prop-cur">&ndash;</div>
                                        <div class="m-compare__prop-all hidden">
                                            <?$i = 0;
                                            foreach($arResult["ITEMS"] as &$arItem)
                                            {
                                                ?>
                                                <span class="item_<?=$i++?>">
                                                            <?=(is_array($arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
                                                            <?=(empty($arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? '&ndash;' : '')?>
                                                        </span>
                                                <?
                                            }
                                            unset($arItem);
                                            ?>
                                        </div>
                                    </div>
                                    <div class="m-compare__item m-compare__item-secondary">
                                        <div class="m-compare__prop-cur">&ndash;</div>
                                        <div class="m-compare__prop-all hidden">
                                            <?$i = 0;
                                            foreach($arResult["ITEMS"] as &$arItem)
                                            {
                                                ?>
                                                <span class="item_<?=$i++?>">
                                                            <?=(is_array($arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
                                                            <?=(empty($arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? '&ndash;' : '')?>
                                                        </span>
                                                <?
                                            }
                                            unset($arItem);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?
                        }
                    }?>

                    <?if (!empty($arResult["SHOW_OFFER_PROPERTIES"])) {
                        foreach ($arResult["SHOW_OFFER_PROPERTIES"] as $code => $arProperty) {

                            if ($arResult['DIFFERENT']) {
                                $arCompare = array();
                                foreach ($arResult["ITEMS"] as &$arItem) {
                                    $arPropertyValue = $arItem["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
                                    if (is_array($arPropertyValue)) {
                                        sort($arPropertyValue);
                                        $arPropertyValue = implode(" / ", $arPropertyValue);
                                    }
                                    $arCompare[] = $arPropertyValue;
                                }
                                unset($arItem);
                                ?>
                                <div class="m-compare__container">
                                    <div class="m-compare__section-prop"><?= $arProperty["NAME"] ?></div>
                                    <div class="m-compare__container m-items__compare">
                                        <div class="m-compare__item m-compare__item-primary">
                                            <div class="m-compare__prop-cur">&ndash;</div>
                                            <div class="m-compare__prop-all hidden">
                                                <? $i = 0;
                                                foreach ($arResult["ITEMS"] as &$arItem) {
                                                    ?>
                                                    <span class="item_<?= $i++ ?>">
                                                                            <?= (is_array($arItem["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) ? implode("/ ", $arItem["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) : $arItem["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) ?>
                                                                            <?=(empty($arItem["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? '&ndash;' : '')?>
                                                                        </span>
                                                    <?
                                                }
                                                unset($arItem);
                                                ?>
                                            </div>
                                        </div>
                                        <div class="m-compare__item m-compare__item-secondary">
                                            <div class="m-compare__prop-cur">&ndash;</div>
                                            <div class="m-compare__prop-all hidden">
                                                <? $i = 0;
                                                foreach ($arResult["ITEMS"] as &$arItem) {
                                                    ?>
                                                    <span class="item_<?= $i++ ?>">
                                                                           <?= (is_array($arItem["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) ? implode("/ ", $arItem["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) : $arItem["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]) ?>
                                                                           <?=(empty($arItem["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? '&ndash;' : '')?>
                                                                        </span>
                                                    <?
                                                }
                                                unset($arItem);
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?
                            }
                        }
                    }?>
                </div>
            </div>
        </div>
    </div>
