<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?if (method_exists($this, 'setFrameMode')) $this->setFrameMode(true); ?>
<div class="rz_oneclick-buy">
	<? if (!empty($arResult['ERROR'])): ?>
		<br/>
		<div class="message message-error">
			<?
			foreach($arResult['ERROR'] as $err) {
				if(is_array($err)) {
					echo $err['TEXT'], '<br>';
				} else {
					$err = trim(strip_tags($err));
					if($err{0} == ':') {
						$err{0} = '';
						$err = trim($err);
					}
					echo str_replace(':','',$err) ,'<br>';
				}
			}?>
		</div>
	<? endif; ?>
	<? if (isset($arResult['SUCCESS'])): ?>
		<br/>
		<div class="message message-success">
			<?= $arResult['SUCCESS'] ?>
		</div>
	<? else: ?>
		<form action="<?= $APPLICATION->GetCurPage(true) ?>" method="post">
			<input type="hidden" name="MESSAGE_OK" value="<?= htmlspecialcharsbx($arParams['MESSAGE_OK']) ?>"/>
			<input type="hidden" name="FORM_ID" value="<?=$arParams['FORM_ID']?>"/>
			<?= bitrix_sessid_post() ?>
			<? foreach($arResult['HIDDEN_FIELDS'] as $arField) {
				echo $arField['HTML'],"\n";
			}?>
			<input type="hidden" name="ELEMENT_ID" value="<?= $arParams['ELEMENT_ID'] ?>" title=""/>
			<? if ($arParams['FIELD_QUANTITY'] == 'Y'): ?>
			<div class="form-group">
					<label><?=GetMessage("RZ_KOLICHESTVO")?></label>
					<input type="number" class="form-control" name="QUANTITY"
						   value="<?= $arResult['QUANTITY'] ?>" title=""/>
			</div>
			<? endif ?>
			<? foreach ($arResult['FIELDS'] as $arItem): ?>
				<div class="form-group<?if(isset($arResult['ERROR'][$arItem['CODE']])):?> has-error<?endif?>">
					<label<? if ($arItem['REQ']): ?> class="req" <? endif ?>><?= $arItem['NAME'] ?>:</label>
					<?= $arItem['HTML'] ?>
				</div>
			<? endforeach ?>
			<? if ('Y' == $arResult['USE_CAPTCHA']): ?>
				<div class="form-group">
					<input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>">
					<br/>
					<label><?= GetMessage("CAPTCHA_REGF_TITLE") ?></label>
				</div>
				<div class="form-group">
					<img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>" width="180" height="40" alt="CAPTCHA">
					<br/>
					<label class="req"><?= GetMessage("CAPTCHA_REGF_PROMT") ?></label>
					<input type="text" name="captcha_word"  class="form-control" value="" title="">
				</div>
			<? endif ?>
			<p class="help-block"><b class="req"></b> &mdash; <?=GetMessage("RZ_POLYA_OBYAZATELNIE_DLYA_ZAPOLNENIYA")?></p>
			<br/>
			<button name="BUY_SUBMIT" class="btn btn-primary" value="Y"><?=GetMessage("RZ_ZAKAZAT")?></button>
		</form>
	<? endif ?>
</div>