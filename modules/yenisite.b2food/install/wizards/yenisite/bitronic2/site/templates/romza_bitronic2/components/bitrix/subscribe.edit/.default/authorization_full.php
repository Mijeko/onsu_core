<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
//******************************************
//subscription authorization form
//******************************************
?>
<div class="row">
	<div class="col-md-6">
		<form
			action="<?= $arResult["FORM_ACTION"] . ($_SERVER["QUERY_STRING"] <> "" ? "?" . htmlspecialcharsbx($_SERVER["QUERY_STRING"]) : "") ?>"
			method="post">
			<div class="form-group">
				<div class="title-h2"><?= GetMessage("subscr_auth_sect_title") ?></div>

				<p class="help-block"><?= GetMessage("adm_auth_note") ?></p>
			</div>
			<div class="form-group">
				<label>E-mail<span class="required-asterisk">*</span>:</label>
				<input type="text" name="sf_EMAIL" class="textinput form-control" value="<?= $arResult["REQUEST"]["EMAIL"]; ?>"
					   title="<?= GetMessage("subscr_auth_email") ?>"/>
			</div>
			<div class="form-group">
				<label><?= GetMessage("subscr_auth_pass") ?><span class="required-asterisk">*</span>:</label>
				<input type="password" name="AUTH_PASS" class="form-control textinput password" value=""
					   title="<?= GetMessage("subscr_auth_pass_title") ?>"/>
			</div>
			<div class="form-group ui-section">
				<input type="submit" name="autorize" class="btn-main"
					   value="<?= GetMessage("adm_auth_butt") ?>"/>
			</div>
			<input type="hidden" name="action" value="authorize"/>
			<?= bitrix_sessid_post(); ?>
		</form>
	</div>
	<div class="col-md-6">
		<form action="<?= $arResult["FORM_ACTION"] ?>">
			<div class="form-group">
				<div class="title-h2"><?= GetMessage("subscr_pass_title") ?></div>
				<p class="help-block"><?= GetMessage("subscr_pass_note") ?></p>
			</div>
			<div class="form-group">
				<label>E-mail<span class="required-asterisk">*</span>:</label>
				<input type="text" name="sf_EMAIL" class="textinput form-control" value="<?= $arResult["REQUEST"]["EMAIL"]; ?>"
					   title="<?= GetMessage("subscr_auth_email") ?>"/>
			</div>
			<div class="form-group ui-section">
				<input type="submit" name="sendpassword" class="btn-main"
					   value="<?= GetMessage("subscr_pass_button") ?>"/>
			</div>
			<input type="hidden" name="action" value="sendpassword"/>
			<?= bitrix_sessid_post(); ?>
		</form>
	</div>
</div>
