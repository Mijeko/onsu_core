<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
//*************************************
//show current authorization section
//*************************************
?>
<div class="col-md-12">
	<form action="<?= $arResult["FORM_ACTION"] ?>" method="post">
		<?= bitrix_sessid_post(); ?>
		<div class="form-group">
			<div class="title-h2"><?= GetMessage("subscr_title_auth") ?></td></div>
		</div>
		<div class="form-group">
			<?= GetMessage("adm_auth_user") ?>
			<?= htmlspecialcharsbx($USER->GetFormattedName(false)); ?> [<?= htmlspecialcharsbx($USER->GetLogin()) ?>].
			<? if ($arResult["ID"] == 0): ?>
				<?= GetMessage("subscr_auth_logout1") ?>
				<a class="pseudolink"
				   href="<?= $arResult["FORM_ACTION"] ?>?logout=YES&amp;sf_EMAIL=<?= $arResult["REQUEST"]["EMAIL"]
				   ?><?= $arResult["REQUEST"]["RUBRICS_PARAM"] ?>">
					<span class="link-text"><?= GetMessage("adm_auth_logout") ?></span>
				</a>
				<?= GetMessage("subscr_auth_logout2") ?>
				<br/>
			<? else: ?>
				<?= GetMessage("subscr_auth_logout3") ?>
				<a class="pseudolink" href="<?= $arResult["FORM_ACTION"] ?>?logout=YES&amp;sf_EMAIL=<?= $arResult["REQUEST"]["EMAIL"]
				?><?= $arResult["REQUEST"]["RUBRICS_PARAM"] ?>">
					<span class="link-text"><?= GetMessage("adm_auth_logout") ?></span>
				</a>
				<?= GetMessage("subscr_auth_logout4") ?>
				<br/>
			<? endif; ?>
		</div>
	</form>
</div>
