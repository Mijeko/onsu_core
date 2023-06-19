<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
require_once(__DIR__ . '/functions.php');
use Yenisite\Favorite\Template\Tools;

$this->setFrameMode(true);
$itemCount = count($arResult['ITEMS']);
$id = 'bxdinamic_bitronic2_favorite_list';
?>
	<div class="yns_favorite-list">
		<div class="top-line">
			<div class="top-line-item favorites">
				<a href="#" class="btn-favorites pseudolink with-icon" data-toggle="um_popup" data-darken data-target="#popup_favorites"
				   id="favorites-toggler">
					<i class="flaticon-heart3"></i>
					<span class="link-text"><?= GetMessage('BITRONIC2_FAVORITES_TITLE') ?></span>
			<span class="items-inside" id="<?= $id ?>">
				<?
				$frame = $this->createFrame($id, false)->begin('');
				?>
				<span class="hidden-xs">(</span><?= $itemCount ?><span class="hidden-xs">)</span>
				<?$frame->end(); ?>
			</span>
				</a>

				<div class="top-line-popup popup_favorites" id="popup_favorites">
					<button class="btn-close" data-toggle="um_popup" data-target="#popup_favorites" data-darken>
						<span class="btn-text"><?= GetMessage('BITRONIC2_MODAL_CLOSE') ?></span>
						<i class="flaticon-close47"></i>
					</button>
					<div class="popup-header">
				<span class="header-text">
					<?= GetMessage('BITRONIC2_FAVORITES_IN_LIST') ?> <?= $itemCount ?> <?= GetMessage('BITRONIC2_FAVORITES_GOODS') ?>:
				</span>
					</div>
					<div class="table-wrap">
						<div class="scroller scroller_v">
							<?$frame = $this->createFrame()->begin(''); ?>
							<?if ($itemCount > 0): ?>
								<table class="items-table">
									<thead>
									<tr>
										<th colspan="2"><?= GetMessage('BITRONIC2_FAVORITES_GOOD') ?></th>
										<th class="availability"></th>
										<th class="price"><?= GetMessage('BITRONIC2_FAVORITES_PRICE') ?></th>
										<th></th>
									</tr>
									</thead>
									<tbody>
									<?foreach ($arResult['ITEMS'] as $arItem):
										$availableClass = (
										$arItem['CAN_BUY']
											? 'in-stock'
											: 'out-of-stock'
										);?>
										<tr class="table-item popup-table-item  <?= $availableClass ?>">
											<td class="photo">
												<img src="<?= $arItem['PICTURE_PRINT']['SRC'] ?>" alt="<?= $arItem['PICTURE_PRINT']['ALT'] ?>">
											</td>
											<td class="name">
												<a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="link"><span class="text"><?= $arItem['NAME'] ?></span></a>
											</td>
											<td class="availability">
												<div class="availability-info">
													<div class="when-in-stock">
														<span class="text"><?= GetMessage('BITRONIC2_FAVORITES_AVAILABLE_TRUE') ?></span>
													</div>
													<!-- /.when-in-stock -->
													<div class="when-out-of-stock">
														<span class="text"><?= GetMessage('BITRONIC2_FAVORITES_AVAILABLE_FALSE') ?></span>
													</div>
												</div>
											</td>


											<td class="price">
										<span class="price-new">
										<?= ($arItem['bOffers']) ? GetMessage('BITRONIC2_FAVORITES_FROM') : '' ?>
										<?= Tools::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['DISCOUNT_VALUE'], $arItem['MIN_PRICE']['PRINT_DISCOUNT_VALUE']); ?></span>

												<div>
													<?if ($arItem['MIN_PRICE']['DISCOUNT_DIFF'] > 0):?>
														<span
															class="price-old"><?= Tools::getElementPriceFormat($arItem['MIN_PRICE']['CURRENCY'], $arItem['MIN_PRICE']['VALUE'], $arItem['MIN_PRICE']['PRINT_VALUE']); ?></span>
													<?endif?>
												</div>
											</td>

											<td class="actions">
												<button class="btn-delete pseudolink with-icon" data-tooltip
														title="<?= GetMessage('BITRONIC2_FAVORITES_DELETE') ?>" data-placement="bottom"
														data-id="<?= $arItem['ID'] ?>">
													<i class="flaticon-trash29"></i>
													<span class="btn-text"><?= GetMessage('BITRONIC2_FAVORITES_DELETE') ?></span>
												</button>
											</td>
										</tr>
									<?endforeach; ?>
									</tbody>
								</table>
							<?endif ?>
							<?$frame->end(); ?>
							<div class="scroller__track scroller__track_v">
								<div class="scroller__bar scroller__bar_v"></div>
							</div>
						</div>
					</div>
					<div class="popup-footer">
						<button class="btn-delete pseudolink with-icon">
							<i class="flaticon-trash29"></i>
							<span class="btn-text"><?= GetMessage('BITRONIC2_FAVORITES_CLEAN') ?></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";