<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

// if show settings panel - off composite
$frame = $this->createFrame()->begin(CRZBitronic2Composite::insertCompositLoader());

if(count($arParams["EDIT_SETTINGS"]) == 0)
{
	$frame->end();
	return;
}
$arDragSettings['type'] = 'DRAG';
$arDragSettings['TIP'] = GetMessage('DRAG_TIP');
include ('functions.php');
?>
<?
$firstHit = (int)$APPLICATION->get_cookie('YNS_FIRST_HIT', 'RZ') + (int)($_REQUEST['no_statistic'] === 'y');
?>
<button class="btn-settings-toggle btn-main <?=$firstHit == 0 ? 'pulse animated infinite': ''?>" data-toggle="modal" data-target="#settings-panel" id="settings-toggle"><i class="flaticon-parameters"></i></button>
<? if ($firstHit == 0): ?>
	<div class="notification-popup settings-info-popup click-stay" data-state="shown" data-base="#settings-toggle"
		 data-position="leftborder bottom" data-anim="flipBounceX">
		<div class="content">
			<button type="button" class="btn-close" data-popup="^.settings-info-popup">
				<i class="flaticon-close47"></i>
			</button>
			<?=GetMessage("RZ_OBRATITE_VNIMANIE_NA_MNOZHESTVO_NASTROEK")?>!
		</div>
	</div>
	<script>
		$('#settings-toggle').on('click',function(e){
			RZB2.utils.setCookie('YNS_FIRST_HIT', 1);
		});
	</script>
<? endif ?>
<?$frame->end();

$this->SetViewTarget('bitronic2_settings');?>
<!-- Modal -->
<?
// echo "<pre style='text-align:left;'>";print_r($arResult);echo "</pre>";
?>
<div class="modal fade" id="settings-panel" role="dialog" tabindex="-1">
	<div class="modal-dialog modal-settings">
		<button class="btn-close" data-toggle="modal" data-target="#settings-panel">
			<span class="btn-text"><?=GetMessage('BITRONIC2_SETTINGS_CLOSE')?></span>
			<i class="flaticon-close47"></i>
		</button>
		<div class="title-h2">
			<?=GetMessage('BITRONIC2_SETTINGS_TITLE')?>
			<span class="settings-view hidden-xs">
				<?=GetMessage('BITRONIC2_SETTINGS_VIEW')?>
				<span class="settings-view-link active" data-mode="tabs"><?=GetMessage('BITRONIC2_SETTINGS_VIEW_TABS')?></span> |
				<span class="settings-view-link" data-mode="full"><?=GetMessage('BITRONIC2_SETTINGS_VIEW_FULL')?></span>
			</span>
		</div>
		<form enctype="multipart/form-data" class="combo-blocks tabs form_settings" action="#" method="post" id="settings-panel-cblocks">
			<div class="combo-links hidden-xs">
				<?foreach($arResult['GROUPS'] as $key => $arItem):?>
					<a href="#settings_<?=$key?>" class="combo-link">
						<span class="text"><?=$arItem['name']?></span>
					</a>
				<?endforeach?>
			</div><!-- .combo-links -->
			<div class="combo-targets">
				<?
				$arGroupFieldSets = array('BY_GROUPS' => array(), 'BY_ITEMS' => array());
				foreach($arResult['GROUPS'] as $groupID => $arItem) {
					foreach($arItem['SETTINGS'] as $key => $settingName) {
						if(!empty($arResult['SETTINGS'][$settingName]['fieldset']) && empty($arResult['SETTINGS'][$settingName]['hidden'])) {
							$arGroupFieldSets['BY_GROUPS'][$groupID][$arResult['SETTINGS'][$settingName]['fieldset']][$settingName] = $settingName;
							$arGroupFieldSets['BY_ITEMS'][$settingName] = $arResult['SETTINGS'][$settingName]['fieldset'];
						}
					}
				}
				foreach ($arResult['CURRENT_SETTINGS'] as $id => &$val) {
					if (isset($arResult['CURRENT_SETTINGS'][$id . '_MOBILE'])) {
						$val = array(
							'orig' => $val,
							'mobile' => $arResult['CURRENT_SETTINGS'][$id . '_MOBILE'],
						);
						unset($arResult['CURRENT_SETTINGS'][$id . '_MOBILE']);
					}
				} unset($val);
				?>
				<?foreach($arResult['GROUPS'] as $groupID => $arItem):?>
					<div class="combo-target" id="settings_<?=$groupID?>">
						<div class="combo-header">
							<span class="text"><?=$arItem['name']?></span>
						</div>

						<div class="combo-target-content">
							<?
							foreach ($arItem['SETTINGS'] as $key => $val) {
								if (!in_array($val, $arParams["EDIT_SETTINGS"])) {
									unset($arItem['SETTINGS'][$key]);
								};
							}
							?>
							<? if (isset($arGroupFieldSets['BY_GROUPS'][$groupID])):?>
								<? foreach($arGroupFieldSets['BY_GROUPS'][$groupID] as $fieldCode => $arItems):
									$bCollapsed = ($groupID != 'slider' && ($groupID != 'view' || $fieldCode != 'page_common'));
									if ($groupID == 'slider' && $fieldCode == 'slider_full') {
										showSliderDummy();
									}?>
									<div class="col-xs-12">
										<fieldset class="<?=( $arResult['FIELDSET'][$fieldCode]['class'] ? $arResult['FIELDSET'][$fieldCode]['class'] : 'row' )?><?=($bCollapsed?' no-border':'')?>">
											<legend <?if($bCollapsed):?> class="collapsed"<?endif?> data-toggle="collapse" data-target="#<?=$fieldCode,$groupID?>"><?= $arResult['FIELDSET'][$fieldCode]['name'] ?></legend>
											<div class="collapse<?if(!$bCollapsed):?> in<?endif?>" id="<?=$fieldCode,$groupID?>">
											<?
											foreach ($arItems as $itemName) {
												if(isset($arResult['SETTINGS'][$itemName]['hidden']) && $arResult['SETTINGS'][$itemName]['hidden'] == true) {
													continue;
												}
												showSettingsItem($arResult['SETTINGS'][$itemName], $arResult['CURRENT_SETTINGS'][$itemName]);
											}
											if ($groupID == 'slider' && $fieldCode == 'slider_full') {
												showSliderSettings();
											}
											?>

											</div>
										</fieldset>
									</div>
								<? endforeach ?>
								<? foreach ($arItem['SETTINGS'] as $key => $val) {
									if (isset($arGroupFieldSets['BY_ITEMS'][$val])) {
										unset($arItem['SETTINGS'][$key]);
									};
								}
								?>
							<? endif ?>
							<? foreach ($arItem['SETTINGS'] as $setting): ?>
								<? if(isset($arResult['SETTINGS'][$setting]['hidden']) && $arResult['SETTINGS'][$setting]['hidden'] == true) {
									continue;
								}
								if ($arResult['SETTINGS'][$setting]['type'] == 'DRAG'){
								    $arDragSettings[] = $arResult['SETTINGS'][$setting];
								    $arCurDragSettings[] = $arResult['CURRENT_SETTINGS'][$setting];

                                    if ($arResult['SETTINGS'][$setting]['not-close-big-div']) {
                                        $arDragSettings['NOT_CLOSE_DIV'] = true;
                                    };
                                    if ($arResult['SETTINGS'][$setting]['not-open-big-div']) {
                                        $arDragSettings['NOT_OPEN_DIV'] = true;
                                    };

                                    if (!$arResult['SETTINGS'][$setting]['end-section']) continue;
                                }
                                if (!empty($arDragSettings)&& !empty($arCurDragSettings)){
                                    showSettingsItem($arDragSettings, $arCurDragSettings);
                                    $arDragSettings = array('type' => 'DRAG');
                                    unset($arCurDragSettings);
                                } else{
                                 showSettingsItem($arResult['SETTINGS'][$setting], $arResult['CURRENT_SETTINGS'][$setting]);
                                }?>
							<? endforeach ?>
						</div><!-- .combo-target-content -->
					</div><!-- #settings_general.combo-target -->
				<?endforeach?>
			</div><!-- .combo-targets -->
			<textarea name="SETTINGS[theme-custom]" id="theme-custom" class="hide"><?=
				$arResult['CURRENT_SETTINGS']['theme-custom']
			?></textarea>
			<input type="hidden" name="settings_apply" value="Y"/>
		</form><!-- .combo-blocks -->
		<div class="modal-footer clearfix">
			<button type="button" class="action settings-to-defaults">
				<i class="flaticon-parameters"></i>
				<span class="text"><?=GetMessage('BITRONIC2_SETTINGS_RESET')?></span>
			</button>
			<button type="button" class="btn-silver btn-cancel" data-toggle="modal" data-target="#settings-panel" id="cancel-settings">
				<span class="text"><?=GetMessage('BITRONIC2_SETTINGS_ABORT')?></span>
			</button>
			<button class="btn-main btn-submit" id="settings-panel-submit">
				<span class="text"><?=GetMessage('BITRONIC2_SETTINGS_APPLY')?></span>
			</button>
			<?if ($USER->IsAdmin()):?>
				<label class="checkbox-styled set-defaults">
					<input form="settings-panel-cblocks" type="checkbox" name='SETTINGS[SET_DEFAULT]' id="SETTINGS[SET_DEFAULT]" value='Y'>
					<span class="checkbox-content">
						<i class="flaticon-check14"></i>
						<?=GetMessage('BITRONIC2_SETTINGS_SET_DEFAULT')?>
					</span>
				</label>
			<?endif?>
		</div>
	</div><!-- .modal-dialog.modal-settings -->
</div><!-- #settings-panel.modal.fade -->
<? if ($arParams['IS_DEMO'] == 'Y'): ?>
	<script type="text/javascript">
		if (typeof RZB2 != 'object') {
			RZB2 = {};
		}
		RZB2.DEMO = 'y';
	</script>
<? endif ?>

<?$this->EndViewTarget();