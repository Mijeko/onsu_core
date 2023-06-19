<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="title-h4"><?=GetMessage("RZ_PODPISKA_NA_RASSILKU")?></div>
<div id="bxdynamic-subscribe-form">
<?
$frame = $this->createFrame("bxdynamic-subscribe-form", false)->begin();
?>
<form action="<?= $arResult["FORM_ACTION"] ?>" class="form_footer-subscribe">
	<div class="hidden">
		<? foreach ($arResult["RUBRICS"] as $itemID => $itemValue): ?>
			<input type="checkbox" name="sf_RUB_ID[]" id="sf_RUB_ID_<?= $itemValue["ID"] ?>"
				   value="<?= $itemValue["ID"] ?>" checked /> <?= $itemValue["NAME"] ?>
		<? endforeach; ?>
	</div>
	<div class="textinput-wrapper">
		<input type="email" class="textinput" name="sf_EMAIL" size="20" value="<?= $arResult["EMAIL"] ?>"
		       title="<?= GetMessage("subscr_form_email_title") ?>" placeholder="<?= GetMessage("subscr_form_email_title") ?>"/>
	</div>
	<button class="btn-main" name="OK"><?= GetMessage("subscr_form_button") ?></button>
</form>
<?
$frame->beginStub();
?>
<form action="<?= $arResult["FORM_ACTION"] ?>" class="form_footer-subscribe">
	<div class="hidden">
		<? foreach ($arResult["RUBRICS"] as $itemID => $itemValue): ?>
			<label for="sf_RUB_ID_<?= $itemValue["ID"] ?>">
				<input type="checkbox" name="sf_RUB_ID[]" id="sf_RUB_ID_<?= $itemValue["ID"] ?>"
					   value="<?= $itemValue["ID"] ?>"/> <?= $itemValue["NAME"] ?>
			</label><br/>
		<? endforeach; ?>
	</div>
	<div class="textinput-wrapper">
		<input type="email" class="textinput" name="sf_EMAIL" size="20" value="" title="<?= GetMessage("subscr_form_email_title") ?>"
		       placeholder="<?= GetMessage("subscr_form_email_title") ?>"/>
	</div>
	<button class="btn-main" name="OK"><?= GetMessage("subscr_form_button") ?></button>
</form>
<?
$frame->end();
?>
</div>
