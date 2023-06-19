<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$this->setFrameMode(true);
if(count($arResult['ITEMS']) == 0) return;
require_once(__DIR__ . '/functions.php');
use Yenisite\Catchbuy\Template\Tools;
?>
	<div id="component-catch-buy" class="yns_catchbuy-list">
		<div class="hurry container">
		<header>
			<span class="text">
				<i class="flaticon-like like"></i><?= GetMessage('BITRONIC2_CATCH_BUY_TITLE') ?><i class="flaticon-sale sale"></i>
			</span>
			<div class="subheader"><?= GetMessage('BITRONIC2_CATCH_BUY_SUBTITLE') ?></div>
		</header>
		<div class="hurry-carousel">
			<div class="content">
				<? foreach ($arResult['ITEMS'] as $arItem):
					$this->AddEditAction($templateName . '-' . $arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
					$this->AddDeleteAction($templateName . '-' . $arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
					$strMainID = $this->GetEditAreaId($templateName . '-' . $arItem['ID']);
					$arItemIDs = array(
						'ID' => $strMainID,
						'QUANTITY' => $strMainID . '_quantity',
						'QUANTITY_DOWN' => $strMainID . '_quant_down',
						'QUANTITY_UP' => $strMainID . '_quant_up',
						'QUANTITY_MEASURE' => $strMainID . '_quant_measure',
						'BUY_LINK' => $strMainID . '_buy_link',
						'BASKET_ACTIONS' => $strMainID . '_basket_actions',
						'NOT_AVAILABLE_MESS' => $strMainID . '_not_avail',
						'COMPARE_LINK' => $strMainID . '_compare_link',

						'OLD_PRICE' => $strMainID . '_old_price',
						'PRICE' => $strMainID . '_price',
					);
					$strObName = 'ob' . preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
					$arCatchBuy = $arParams['CATCHBUY'][$arItem['ID']];
					$bTimer = !empty($arCatchBuy['ACTIVE_TO']);
					$bProgressBar = $arCatchBuy['MAX_USES'] > 0;
					$arCatchBuy['PERCENT'] = ($bProgressBar) ? $arCatchBuy['COUNT_USES'] / $arCatchBuy['MAX_USES'] * 100 : 0;
					?><!--
					--><div class="catalog-item-wrap" id="<?= $arItemIDs['ID'] ?>">
						<div class="catalog-item hurry-item">
							<div class="photo-wrap">
								<div class="photo">
									<a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
										<img src="<?= $arItem['PICTURE_PRINT']['SRC'] ?>" alt="<?= $arItem['PICTURE_PRINT']['ALT'] ?>">
									</a>
								</div>
							</div>
							<!-- /.photo-wrap -->
							<div class="main-data">
								<div class="name">
									<a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="link"><span
											class="text"><?= $arItem['NAME'] ?></span></a>
								</div>
								<div class="art-rate">
									<?if (!empty($arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'])):?>
										<span class="art"><?= $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['NAME'] ?>
											: <strong><?= is_array($arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) ? implode(' / ', $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE']) : $arItem['PROPERTIES'][$arParams['ARTICUL_PROP']]['VALUE'] ?></strong></span>
									<?endif?>
									<?$APPLICATION->IncludeComponent("bitrix:iblock.vote", "catchbuy_list", array(
										"IBLOCK_TYPE" => $arItem['IBLOCK_TYPE_ID'],
										"IBLOCK_ID" => $arItem['IBLOCK_ID'],
										"ELEMENT_ID" => $arItem['ID'],
										"CACHE_TYPE" => $arParams["CACHE_TYPE"],
										"CACHE_TIME" => $arParams["CACHE_TIME"],
										"MAX_VOTE" => "5",
										"VOTE_NAMES" => array("1", "2", "3", "4", "5"),
										"SET_STATUS_404" => "N",
									), $component->__parent, array("HIDE_ICONS" => "Y")
									);?>
								</div>
								<div class="prices">
								<span class="price-old" id="<?= $arItemIDs['OLD_PRICE'] ?>">
<? $frame = $this->createFrame($arItemIDs['OLD_PRICE'], false)->begin() ?>
<? if ($arItem['MIN_PRICE']['DISCOUNT_DIFF'] > 0): ?>
	<?= Tools::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['VALUE'], $arItem['MIN_PRICE']['PRINT_VALUE']); ?>
<? endif ?>
<? $frame->end() ?>
								</span>
								<span class="price" id="<?= $arItemIDs['PRICE'] ?>">
<? $frame = $this->createFrame($arItemIDs['PRICE'], false)->begin() ?>
<?= ($arItem['bOffers']) ? GetMessage('BITRONIC2_CATCH_BUY_FROM') : '' ?>
<?= Tools::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']); ?>
<? $frame->end() ?>
								</span>
								</div>
							</div>
							<div class="economy">
								<span class="text"><?= GetMessage('BITRONIC2_CATCH_BUY_ECONOM') ?></span>
							<span
								class="value"><?= Tools::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_DIFF'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_DIFF']); ?></span>
							</div>
							<?if ($bTimer):?>
								<div class="countdown">
									<span class="text"><?= GetMessage('BITRONIC2_CATCH_BUY_BY_END') ?>:</span>

									<div class="timer-wrap">
										<i class="flaticon-stopwatch6 stopwatch"></i>
										<div class="timer" data-until="<?=str_replace('XXX', 'T', ConvertDateTime($arCatchBuy['ACTIVE_TO'], 'YYYY-MM-DDXXXhh:mm:ss'))?>"></div>
									</div>
								</div>
							<?endif?>
							<?if ($bProgressBar):?>
								<div class="already-sold">
							<span class="track">
								<span class="bar" style="width: <?= $arCatchBuy['PERCENT'] ?>%"></span>
								<span class="value"><?= intVal($arCatchBuy['PERCENT']) ?>%</span>
							</span>
									<span class="text"><?= GetMessage('BITRONIC2_CATCH_BUY_ALREADY_BUY') ?></span>
								</div>
								<div class="remaining">
									<span class="text"><?= GetMessage('BITRONIC2_CATCH_BUY_ALREADY_EXIST') ?>:</span>
								<span
									class="value"><?= $arCatchBuy['MAX_USES'] - $arCatchBuy['COUNT_USES'] ?> <?= $arItem['CATALOG_MEASURE_NAME'] ?></span>
								</div>
							<?endif?>
							<div class="action-buttons" id="<?= $arItemIDs['BASKET_ACTIONS'] ?>">
								<?
								// TODO: favorite
								//include '_/buttons/btn-action_to-fav.html';
								?>
								<?if ($arParams['DISPLAY_COMPARE']):?>
									<button
										type="button"
										class="btn-action compare"
										data-compare-id="<?= $arItem['ID'] ?>"
										data-tooltip title="<?= GetMessage('BITRONIC2_CATCH_BUY_ADD_TO_COMPARE') ?>"
										id="<?= $arItemIDs['COMPARE_LINK'] ?>">
										<i class="flaticon-balance3"></i>
									</button>
								<?endif?>
								<div class="btn-buy-wrap text-only">
									<button type="button" class="btn-action buy when-in-stock" id=<?= $arItemIDs['BUY_LINK'] ?>>
										<i class="flaticon-shopping109"></i>
										<span class="text"><?= GetMessage('BITRONIC2_CATCH_BUY_ADD_TO_BASKET') ?></span>
									</button>
								</div>
							</div>
						</div>
						<? // JS PARAMS
						include 'js_params.php';
						?>
					</div><!-- /.catalog-item-wrap --><?
				endforeach ?>
			</div>
			<div class="slider-controls-wrap controls">
				<a class="slider-arrow prev">
					<i class="flaticon-arrow133"></i>
					<span class="sr-only">Previous</span>
				</a><!--
			-->
				<div class="dots">
				</div>
				<!--
							--><span class="numeric"></span><!--
			--><a class="slider-arrow next">
					<i class="flaticon-right20"></i>
					<span class="sr-only">Next</span>
				</a>
			</div>
			<!-- /.slider-controls-wrap -->
			<!-- /.slider-controls-wrap -->
		</div>
		<!-- /.hurry-carousel -->
	</div><!-- /.hurry -->
	</div>
<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";