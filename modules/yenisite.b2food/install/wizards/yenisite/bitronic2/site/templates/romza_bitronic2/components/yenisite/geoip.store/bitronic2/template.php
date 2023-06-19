<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (method_exists($this, 'setFrameMode')) $this->setFrameMode(true);

if (!CModule::IncludeModule('statistic')) {
	return;
}

$id = 'bxdinamic_geoip_store_string'; ?>
<? if ($arParams['ONLY_GEOIP'] != 'Y'): ?>
    <?if(CRZBitronic2Settings::isPro()):?>
        <div class="geo__store">
    <?endif?>
        <span class="geoipstore_add_text before"><?=GetMessage("RZ_DOSTAVIT_IZ")?></span>
        <a href="" class="btn-store-toggle pseudolink with-icon" data-toggle="modal" data-target="#modal_store-select-panel">
            <i class="flaticon-location4"></i>
        <span class="geoipstore_store_text link-text" id="<?= $id ?>">
            <? $frame = $this->createFrame($id, false)->begin(CRZBitronic2Composite::insertCompositLoader()); ?>
            <?= (!empty($arResult['CITY']) ? $arResult['CITY'] : GetMessage('BITRONIC2_CHOOSE_CITY')) ?>
            <? $frame->end(); ?>
        </span>
        </a>
    <?if(CRZBitronic2Settings::isPro()):?>
        </div>
    <?endif?>
	<span class="geoipstore_add_text after"><?=GetMessage("RZ_V")?></span>
<? $this->SetViewTarget('modal_store_select'); ?>
	<!-- Modal -->
	<div class="modal fade modal-form" id="modal_store-select-panel" role="dialog" tabindex="-1">
		<div class="modal-dialog">
			<? $frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader()); ?>
			<button class="btn-close" data-toggle="modal" data-target="#modal_store-select-panel">
				<span class="btn-text"><?= GetMessage('BITRONIC2_MODAL_CLOSE') ?></span>
				<i class="flaticon-close47"></i>
			</button>
			<div class="content">
				<div class="title-h2"><span class="header-text"><?=GetMessage("RZ_VIBERITE_SKLAD")?></span></div>
				<div class="row items">
					<? foreach ($arResult['ITEMS'] as $item): ?>
						<div class="col-sm-6 item <?= (($arResult['ACTIVE_ITEM_ID'] == $item['ID']) ? 'active' : '') ?>">
							<div class="wrapper">
								<a href="javascript:;" class="itemlink"
								   data-ys-item-id="<?= $item['ID'] ?>"
								   data-dismiss="modal"><?= $item['CITY_DEL_NAME'] ?></a>
								<? if (!empty($item['STORES'])): ?>
									<ul class="stores">
										<? foreach ($item['STORES'] as $store): ?>
											<li><span><?= $store['TITLE'] ?></span></li>
										<? endforeach ?>
									</ul>
								<? endif ?>
							</div>
						</div>
					<? endforeach ?>
				</div>
			</div>
			<? $frame->end(); ?>
		</div>
		<!-- /modal-dialog -->
	</div><!-- #modal_store-select-panel.modal.fade -->
<? $this->EndViewTarget(); ?>
<? else: ?>
	<script>
		<?if(!empty($arResult['ACTIVE_ITEM_ID'])):?>
			var ysGeoStoreActiveId = <?=$arResult['ACTIVE_ITEM_ID']?>;
		<?endif;?>
		var ysGeoStoreList = {
			<?foreach($arResult['ITEMS'] as $item){
				echo "'{$item['CITY_DEL_NAME']}':{$item['ID']},";
				if($item['DEFAULT'] == 'Y'){
					$default_store_id = $item['ID'] ;
				}
			}?>
		};
		<?if(!empty($default_store_id)):?>
			var ysGeoStoreDefault = <?=$default_store_id?>;
		<?endif;?>
	</script>
<? endif ?>