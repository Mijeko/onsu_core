<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="product-modifications wow fadeIn drag-section sPrModifications"
     data-order="<?= $arParams['ORDER_DETAIL_BLOCKS']['order-sPrModifications'] ?>">
    <table class="table_product-modifications" id="<?= $arItemIDs['SKU_TABLE'] ?>">
        <caption class="title"><?= GetMessage('BITRONIC2_OFFERS_TABLE_NAME') ?></caption>
        <thead>
        <tr>
            <th class="name"><span class="text no-sort"><?= GetMessage('BITRONIC2_OFFERS_NAME') ?></span></th>
            <? foreach ($arParams['OFFERS_PROPERTY_CODE'] as $propCode):
                if (!array_key_exists($propCode, $arResult['OFFERS_DISPLAY_PROPS'])) continue; ?>
                <th><span class="text no-sort"><?= $arResult['OFFERS_DISPLAY_PROPS'][$propCode] ?></span></th>
            <? endforeach ?>
            <th class="price"><span class="text no-sort"><?= GetMessage('BITRONIC2_OFFERS_PRICE') ?></span></th>
            <th></th>
            <th class="actions"></th>
        </tr>
        </thead>
        <tbody>
        <? foreach ($arResult['OFFERS'] as $arOffer):
            $availableClass = (!$arOffer['CAN_BUY'] && !$arOffer['ON_REQUEST'] ? 'out-of-stock' : ($arOffer['FOR_ORDER'] || $arOffer['ON_REQUEST'] ? 'available-for-order' : ''));
            $strAvailable = (isset($arOffer['ON_REQUEST']) && !$arOffer['ON_REQUEST'] ? ($arOffer['CAN_BUY'] ? ($arOffer['FOR_ORDER'] ? 'PreOrder' : 'InStock') : 'OutOfStock') : '');
            ?>
            <tr<?= ($availableClass ? ' class="' . $availableClass . '"' : '') ?> itemprop="offers" itemscope
                                                                                  itemtype="http://schema.org/Offer">
                <td class="name">
                    <span class="text"><?= $arOffer['NAME'] ?></span>
                </td>
                <? foreach ($arParams['OFFERS_PROPERTY_CODE'] as $propCode):
                    if (!array_key_exists($propCode, $arResult['OFFERS_DISPLAY_PROPS'])) continue; ?>
                    <td>
                        <? if ($propCode == 'MORE_PHOTO'):?>
                            <? foreach ($arOffer['MORE_PHOTO'] as $arPhoto):?>
                                <img src="<?= $arPhoto['SRC_TABLE'] ?>"/>
                            <? endforeach; ?>
                        <? else:?>
                            <?= is_array($arOffer['DISPLAY_PROPERTIES'][$propCode]['DISPLAY_VALUE'])
                                ? implode(' / ', $arOffer['DISPLAY_PROPERTIES'][$propCode]['DISPLAY_VALUE'])
                                : $arOffer['DISPLAY_PROPERTIES'][$propCode]['DISPLAY_VALUE'] ?>
                        <? endif ?>
                    </td>
                <? endforeach ?>
                <td class="price"><?
                    if (!$arOffer['ON_REQUEST']):
                        if (0 < $arOffer['MIN_PRICE']['DISCOUNT_DIFF'] && $arParams['SHOW_OLD_PRICE'] == 'Y'):
                            ?>

                            <span class="price-old"><?= CRZBitronic2CatalogUtils::getElementPriceFormat($arOffer['MIN_PRICE']['CURRENCY'], $arOffer['MIN_PRICE']['VALUE'], $arOffer['MIN_PRICE']['PRINT_VALUE']) ?></span><?
                        endif ?>

                        <meta itemprop="priceCurrency" content="<?= $arOffer['MIN_PRICE']['CURRENCY'] ?>">
                    <span class="price-new" itemprop="price" content="<?= $arOffer['MIN_PRICE']['DISCOUNT_VALUE'] ?>">
                        <?= CRZBitronic2CatalogUtils::getElementPriceFormat($arOffer['MIN_PRICE']['CURRENCY'], $arOffer['MIN_PRICE']['DISCOUNT_VALUE'], $arOffer['MIN_PRICE']['PRINT_DISCOUNT_VALUE']) ?>

                        </span><?
                    endif ?>

                </td>
                <td>
                    <? if (!empty($strAvailable)):
                        ?>
                        <link itemprop="availability" href="http://schema.org/<?= $strAvailable ?>">
                    <? endif
                    ?><?
                    $availableForOrderText = &$arOffer['PROPERTIES']['RZ_FOR_ORDER_TEXT']['VALUE'];
                    $availableItemID = &$arOffer['ID'];
                    $availableMeasure = &$arOffer['CATALOG_MEASURE_NAME'];
                    $availableQuantity = &$arOffer['CATALOG_QUANTITY'];
                    $availableStoresPostfix = 'sku';
                    include $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH . '/include/availability_dots.php';
                    ?>
                </td>
                <td class="actions <?= $arOffer['CAN_BUY'] ? '' : ($arOffer['ON_REQUEST'] ? 'on-request' : 'out-of-stock') ?>">
                    <? if ($arParams['DISPLAY_FAVORITE']):?>
                        <button
                                type="button"
                                class="btn-action favorite"
                                data-favorite-id="<?= $arOffer['ID'] ?>"
                                data-offer-id="<?= $arOffer['ID'] ?>"
                                data-tooltip title="<?= GetMessage('BITRONIC2_ADD_FAVORITE') ?>"
                        >
                            <i class="flaticon-heart3"></i>
                        </button>
                    <? endif ?>

                    <? if ($arOffer['CAN_BUY']):?>
                        <button type="button"
                                data-tooltip title="<?= GetMessage('BITRONIC2_ONECLICK') ?>"
                                class="btn-action one-click-buy"
                                data-toggle="modal" data-target="#modal_quick-buy"
                                data-id="<?= $arOffer['ID'] ?>">
                            <i class="flaticon-shopping220"></i>
                        </button>
                        <span class="quantity-counter">
								<!-- parent must have class .quantity-counter! -->
								<button type="button" class="btn-silver quantity-change decrease disabled"><span
                                            class="minus"></span></button>
								<input type="text" class="quantity-input textinput"
                                       value="<?= $arResult['CATALOG_MEASURE_RATIO'] ?>">
								<button type="button" class="btn-silver quantity-change increase"><span
                                            class="plus"></span></button>
							</span>
                        <span class="btn-buy-wrap text-only">
								<button type="button"
                                        class="btn-action buy <?= ($arOffer['FOR_ORDER'] ? 'when-available-for-order' : 'when-in-stock') ?>"
                                        data-offer-id="<?= $arOffer['ID'] ?>" data-offer-name="<?= $arOffer['NAME'] ?>">
									<span class="text"><?= COption::GetOptionString($moduleId, 'button_text_buy') ?></span>
									<span class="text in-cart"><?= COption::GetOptionString($moduleId, 'button_text_incart') ?></span>
								</button>
							</span>
                    <? elseif ($arOffer['ON_REQUEST']):?>
                        <span class="btn-buy-wrap text-only on-request">
								<button type="button" class="btn-action buy on-request"
                                        data-offer-id="<?= $arOffer['ID'] ?>" data-offer-name="<?= $arOffer['NAME'] ?>"
                                        data-measure-name="<?= $arOffer['CATALOG_MEASURE_NAME'] ?>"
                                        data-toggle="modal" data-target="#modal_contact_product">
									<span class="text"><?= COption::GetOptionString($moduleId, 'button_text_request') ?></span>
								</button>
							</span>
                        <span class="when-out-of-stock on-request"><?= GetMessage('BITRONIC2_PRODUCT_AVAILABLE_ON_REQUEST') ?></span>
                    <? else:?>
                        <span class="when-out-of-stock"><?= COption::GetOptionString($moduleId, 'button_text_na') ?></span>
                    <? endif ?>
                </td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
</div>