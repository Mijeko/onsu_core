<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
//***********************************
//status and unsubscription/activation section
//***********************************
?>
<div class="col-md-6">
	<form action="<?= $arResult["FORM_ACTION"] ?>" method="get">
		<div class="form-group">
			<div class="title-h2"><?= GetMessage("subscr_title_status") ?></div>
		</div>
		<div class="from-group">
			<div class="message message-<?=($arResult["SUBSCRIPTION"]["CONFIRMED"] == "Y" ? "success" : "error") ?>">
				<div class="col-xs-6">
					<b><?= GetMessage("subscr_conf") ?></b>
				</div>
				<div class="col-xs-6">
					<?=($arResult["SUBSCRIPTION"]["CONFIRMED"] == "Y" ? GetMessage("subscr_yes") : GetMessage("subscr_no")); ?>
				</div>
				<div class="clearfix"></div>
			</div>
			<? if ($arResult["SUBSCRIPTION"]["CONFIRMED"] <> "Y"): ?>
				<p class="help-block"><?= GetMessage("subscr_title_status_note1") ?></p>
			<? elseif ($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y"): ?>
				<p class="help-block"><?= GetMessage("subscr_title_status_note2") ?></p>
				<p class="help-block"><?= GetMessage("subscr_status_note3") ?></p>
			<? else: ?>
				<p class="help-block"><?= GetMessage("subscr_status_note4") ?></p>
				<p class="help-block"><?= GetMessage("subscr_status_note5") ?></p>
			<? endif; ?>
		</div>
		<div class="form-group">
			<div class="message message-<?=($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y" ? "success" : "error") ?>">
				<div class="col-xs-6">
					<b><?= GetMessage("subscr_act") ?></b>
				</div>
				<div class="col-xs-6">
					<?=($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y" ? GetMessage("subscr_yes") : GetMessage("subscr_no")); ?>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="form-group">
			<div class="message">
				<div class="col-xs-6">
					<b><?= GetMessage("adm_id") ?></b>
				</div>
				<div class="col-xs-6">
					<?= $arResult["SUBSCRIPTION"]["ID"]; ?>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="message">
				<div class="col-xs-6">
					<b><?= GetMessage("subscr_date_add") ?></b>
				</div>
				<div class="col-xs-6">
					<?= $arResult["SUBSCRIPTION"]["DATE_INSERT"]; ?>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="message">
				<div class="col-xs-6">
					<b><?= GetMessage("subscr_date_upd") ?></b>
				</div>
				<div class="col-xs-6">
					<?= $arResult["SUBSCRIPTION"]["DATE_UPDATE"]; ?>
				</div>
			</div>
		</div>
		<? if ($arResult["SUBSCRIPTION"]["CONFIRMED"] == "Y"): ?>
			<div class="form-group ui-section">
				<? if ($arResult["SUBSCRIPTION"]["ACTIVE"] == "Y"): ?>
					<input type="submit" name="unsubscribe" class="btn-main"
						   value="<?= GetMessage("subscr_unsubscr") ?>"/>
					<input type="hidden" name="action" value="unsubscribe"/>
				<? else: ?>
					<input type="submit" name="activate" class="btn-main"
						   value="<?= GetMessage("subscr_activate") ?>"/>
					<input type="hidden" name="action" value="activate"/>
				<? endif; ?>
			</div>
		<? endif; ?>
		<input type="hidden" name="ID" value="<?= $arResult["SUBSCRIPTION"]["ID"]; ?>"/>
		<?= bitrix_sessid_post(); ?>
	</form>
</div>