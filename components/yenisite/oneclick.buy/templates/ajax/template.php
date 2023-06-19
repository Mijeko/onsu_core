<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
if (!CModule::IncludeModule('yenisite.oneclick')) {
	die(GetMessage('RZ_ERR_NO_YENISITE_ONECLICK_MODULE_INSTALLED'));
}
use CRZ\OneClick\Tools;
?>
<?
$isAjax = Tools::isAjax();
?>
<? if (!$isAjax): ?>
	<?
	global $bRzHasModal;
	if (!is_bool($bRzHasModal)) {
		$bRzHasModal = false;
	}
	?>
	<?if (method_exists($this, 'setFrameMode')) $this->setFrameMode(true); ?>
	<div class="rz_oneclick-buy">
		<?
		$linkClass = '';
		switch($arParams['BUTTON_TYPE']) {
			case 'BUTTON':
				$linkClass = 'btn btn-primary';
				break;
			case 'LINK':
				$linkClass = 'btn btn-link';
				break;
			default:
				$linkClass = '';
				break;
		}
		?>
		<a href="javascript:;" class="do-order <?=$linkClass?>" data-arparams="<?= Tools::GetEncodedArParams($arParams) ?>"
		   data-template="<?=$templateName?>"><?=GetMessage("RZ_KUPIT_V_1_KLIK")?></a>
	<?
	if (!$bRzHasModal) {
		?>
		<div class="modal fade" id="rz_modal-oneclick" tabindex="-1" role="dialog" aria-labelledby="oneclick" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="oneclick_modalLabel"><?=GetMessage("RZ_BISTRIJ_ZAKAZ")?></h4>
					</div>
					<div class="modal-body">

					</div>
				</div>
			</div>
		</div>
		<?
	}
	?>
<? endif ?>
<? if ($isAjax): ?>
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
			<?= htmlspecialcharsback(htmlspecialcharsback($arResult['SUCCESS']))//double json decoding ?>
		</div>
	<? else: ?>
		<form>
			<input type="hidden" name="MESSAGE_OK" value="<?= htmlspecialcharsbx($arParams['MESSAGE_OK']) ?>"/>
			<?= bitrix_sessid_post() ?>
			<? foreach ($arResult['HIDDEN_FIELDS'] as $arField) {
				echo $arField['HTML'], "\n";
			} ?>
			<input type="hidden" name="ELEMENT_ID" value="<?= $arParams['ELEMENT_ID'] ?>"/>
			<? if ($arParams['FIELD_QUANTITY'] == 'Y'): ?>
				<div class="form-group">
					<label><?=GetMessage("RZ_KOLICHESTVO")?></label>
					<input type="number" class="form-control" name="QUANTITY"
						   value="<?= $arResult['QUANTITY'] ?>" title=""/>
				</div>
			<? endif ?>
			<? foreach ($arResult['FIELDS'] as $arItem): ?>
				<div class="form-group<?if(isset($arResult['ERROR'][$arItem['CODE']])):?> has-error<?endif?>">
					<label<? if ($arItem['REQ']): ?> class="req" <? endif ?>><?= $arItem['NAME'] ?></label> :
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
					<label class="req"><?= GetMessage("CAPTCHA_REGF_PROMT") ?></label> :
					<input type="text" name="captcha_word"  class="form-control" value="" title="">
				</div>
			<? endif ?>
			<p class="help-block"><b class="req"></b> &mdash; <?=GetMessage("RZ_POLYA_OBYAZATELNIE_DLYA_ZAPOLNENIYA")?></p>
			<br/>
			<button name="BUY_SUBMIT" class="btn btn-primary" value="Y"><?=GetMessage("RZ_ZAKAZAT")?></button>
		</form>
	<? endif ?>
<? endif ?>
<? if (!$isAjax): ?>
	</div>
<? endif ?>