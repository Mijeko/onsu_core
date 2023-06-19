<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);

if(!CModule::IncludeModule('statistic'))
{
	return;
} 
ob_start();
$id = 'bxdinamic_geoip_string';
$notyID = 'bxdinamic_geoip_notify';
?>
    <div class="geo__city">
	    <div class="notification-frame" id="<?= $notyID ?>">
		<? $frame = $this->createFrame($notyID, false)->begin(''); ?>
		<? if (!empty($arResult['CITY_INLINE']) && $arParams['YOURCITY_POPUP'] !== 'N'): ?>
			<div class="notification-popup yourcity-popup click-stay" #SHOW_POPUP# data-position="centered bottom"
				 data-base="#current-city" data-container="body">
				<form action="#" method="post" class="content form_yourcity">
					<p>
						<?= GetMessage("RZ_VASH_GOROD") ?> <span class="current-city"><?= $arResult['CITY_INLINE'] ?></span>?
					</p>

					<div class="btn-wrap">
						<button id="btn-confirm-city" class="btn-main" type="button" data-popup="^.yourcity-popup"><span
								class="text"><?= GetMessage("RZ_DA__ETO_MOJ_GOROD") ?></span></button>
					</div>
					<a href="javascript:;" class="another-city"
					   data-toggle="modal" data-target="#modal_city-select-panel" id="not-my-city" data-popup="^.yourcity-popup">
						<?= GetMessage("RZ_NET__VIBRAT_DRUGOJ_GOROD") ?>
					</a>
				</form>
				<!-- .modal-content -->
			</div>
			<script>
				$(document).on('click','.yourcity-popup a, .yourcity-popup button', function(){
					RZB2.utils.setCookie('YNS_IS_YOUR_CITY', 1);
				});
			</script>
		<? endif ?>
		<? $frame->end() ?>
	</div><!-- do not delete
--><a href="javascript:;" class="btn-city-toggle pseudolink with-icon" data-toggle="modal" data-target="#modal_city-select-panel" id="current-city">
	<i class="flaticon-location4"></i>
	<span class="link-text" id="<?=$id?>">
		<? $frame = $this->createFrame($id, false)->begin(CRZBitronic2Composite::insertCompositLoader()); ?><?
		if (!empty($arResult['CITY_INLINE'])) {
			echo $arResult['CITY_INLINE'];
		} else {
			echo GetMessage('BITRONIC2_CHOOSE_CITY');
		}
		?>
		<? $frame->end(); ?>
	</span>			
</a>
</div>
<? $arResult['TEMPLATE'] = ob_get_clean(); ?>
<? $this->SetViewTarget('modal_city_select'); ?>
	<!-- Modal -->
	<div class="modal fade modal_city-select-panel" id="modal_city-select-panel" role="dialog" tabindex="-1">
		<div class="modal-dialog">
			<? $frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader()); ?>
			<? include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/include/debug_info.php'; ?>
			<button class="btn-close" data-toggle="modal" data-target="#modal_city-select-panel">
				<span class="btn-text"><?= GetMessage('BITRONIC2_MODAL_CLOSE') ?></span>
				<i class="flaticon-close47"></i>
			</button>
			<form method="post" class="form_city-select">
				<div class="textinput-wrapper">
				<span class="city-select-panel-text">
					<label class="table-cell" for="city-search"><?= GetMessage('BITRONIC2_POPUP_TITLE') ?></label>
				</span>
					<input type="text" class="textinput ys-city-query" id="city-search" autocomplete="off">
				<span class="input-controls">
					<i class="input-clear flaticon-close47"></i>
				</span>

					<div id="popup_ajax-city-search" class="ajax-city-search">
						<ul class="ys-loc-autocomplete"></ul>
					</div>
				</div>

				<span class="city-select-panel-text"><?= GetMessage('BITRONIC2_SELECT_CITY') ?>:</span>
				<ul class="city-list"><?
					if (!empty($arResult['CITY_IP']) || !empty($arResult['CITY_INLINE'])) {
						$selected_city = !empty($arResult['CITY_INLINE']) ? $arResult['CITY_INLINE'] : $arResult['CITY_IP'];
					}
					foreach ($arResult['CITY'] as $k => $city):
						?>
						<li class="<?= ((strcasecmp($selected_city, $city) == 0) ? ' active' : '') ?>"><span><?= $city ?></span></li><?
					endforeach;
					?></ul>
				<p <?= empty($arResult['CITY_INLINE']) ? 'style="display: none"' : '' ?>>
					<?= GetMessage('BITRONIC2_SELECTED_CITY') ?>: <span class="current-city"><?= $arResult['CITY_INLINE']; ?></span>
				</p>

				<div class="btn-wrap">
					<button id="btn-save-city" class="btn-main" data-toggle="modal" data-target="#modal_city-select-panel"><span
							class="btn-text"><?= GetMessage('BITRONIC2_GEOIP_OK') ?></span></button>
				</div>
			</form>
			<!-- .modal-content -->
			<? $frame->end(); ?>
		</div>
		<!-- /modal-dialog -->
	</div><!-- #modal_city-select-panel.modal.fade -->
<? $this->EndViewTarget(); ?>