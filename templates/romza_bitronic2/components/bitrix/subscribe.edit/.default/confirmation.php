<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
//*************************************
//show confirmation form
//*************************************
?>
<div class="row">
	<div class="col-sm-6">
		<form action="<?= $arResult["FORM_ACTION"] ?>" method="get">
			<div class="form-group">
				<div class="title-h2"><?= GetMessage("subscr_title_confirm") ?></div>
			</div>
			<div class="form-group required">
				<label><?= GetMessage("subscr_conf_code") ?><span class="required-asterisk">*</span>:</label>
				<input type="text" class="textinput form-control"
					   name="CONFIRM_CODE" value="<?= $arResult["REQUEST"]["CONFIRM_CODE"]; ?>"/>

				<p class="help-block"><?= GetMessage("subscr_conf_date") ?></p>

				<p class="help-block"><?= $arResult["SUBSCRIPTION"]["DATE_CONFIRM"]; ?></p>
			</div>
			<div class="form-group">
				<p class="help-block">
					<?= GetMessage("subscr_conf_note1") ?>
					<a title="<?= GetMessage("adm_send_code") ?>"
					   href="<?= $arResult["FORM_ACTION"] ?>?ID=<?= $arResult["ID"] ?>&amp;action=sendcode&amp;<?= bitrix_sessid_get() ?>"><?= GetMessage("subscr_conf_note2") ?>
					</a>.
				</p>
			</div>
			<div class="form-group ui-section">
				<input type="submit" class="btn-main"
					   name="confirm" value="<?= GetMessage("subscr_conf_button") ?>"/>
			</div>
			<input type="hidden" name="ID" value="<?= $arResult["ID"]; ?>"/>
			<?= bitrix_sessid_post(); ?>
		</form>
	</div>
</div>