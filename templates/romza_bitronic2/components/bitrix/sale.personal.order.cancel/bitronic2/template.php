<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/include/debug_info.php'; ?>
<a href="<?= $arResult['URL_TO_LIST'] ?>" class="link-bd link-std"><?= GetMessage('SALE_RECORDS_LIST') ?></a>

<div class="row">
	<div class="col-md-6">
		<? if (strlen($arResult["ERROR_MESSAGE"]) <= 0): ?>
			<form method="post" action="<?= POST_FORM_ACTION_URI ?>">
				<input type="hidden" name="CANCEL" value="Y">
				<input type="hidden" name="action" value="Y"/>
				<?= bitrix_sessid_post() ?>
				<input type="hidden" name="ID" value="<?= $arResult["ID"] ?>">
				<div class="form-group">
					<?= GetMessage("SALE_CANCEL_ORDER1") ?>
					<a href="<?= $arResult["URL_TO_DETAIL"] ?>" class="link-bd link-std"><?= GetMessage("SALE_CANCEL_ORDER2") ?>
						#<?= $arResult["ACCOUNT_NUMBER"] ?></a>?
					<b><?= GetMessage("SALE_CANCEL_ORDER3") ?></b><br/><br/>
					<?= GetMessage("SALE_CANCEL_ORDER4") ?>:<br/>
				</div>
				<div class="form-group">
					<textarea class="form-control" name="REASON_CANCELED" title=""></textarea>
				</div>
				<div class="ui-section">
					<button type="submit" class="btn-main"><span class="text"><?= GetMessage("SALE_CANCEL_ORDER_BTN") ?></span></button>
				</div>
			</form>
		<? else: ?>
			<?= ShowError($arResult["ERROR_MESSAGE"]); ?>
		<? endif; ?>
	</div>
</div>