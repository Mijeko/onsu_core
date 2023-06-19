<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
//Not cacheable
$this->setFrameMode(true);
if($arParams['EMPTY'])
{
	echo '<div>', GetMessage('BITRONIC2_ONECLICK_TITLE'), '</div>';
	return;
}
if (!empty($arResult['ERROR']))
{
	$err = '';
	foreach($arResult['ERROR'] as $arError)
	{
		$err .= $arError/*['TEXT']*/.'<br>';
	}
	CRZBitronic2CatalogUtils::ShowMessage(Array("MESSAGE" => $err, "TYPE" => "ERROR"));
}

if (isset($arResult['SUCCESS']))
{
	CRZBitronic2CatalogUtils::ShowMessage(Array("MESSAGE" => $arResult['SUCCESS'], "TYPE" => "OK"));
	return;
}
if(empty($arResult['FIELDS']))
{
	CRZBitronic2CatalogUtils::ShowMessage(Array("MESSAGE" => GetMessage('BITRONIC2_ONECLICK_EMPTY_FILEDS'), "TYPE" => "ERROR"));
	return;
}
\Bitrix\Main\Localization\Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH . '/header.php');
$pathToRules = COption::GetOptionString(CRZBitronic2Settings::getModuleId(),'path_tu_rules_privacy',SITE_DIR.'personal/rules/personal_data.php');
?>
<form method="post" action="<?= $APPLICATION->GetCurPage(true) ?>" class="form_quick-buy modal-form-w-icons">
	<div class="title-h2"><span class="header-text"><?=GetMessage('BITRONIC2_ONECLICK_TITLE')?></span></div>
	<input type="hidden" name="MESSAGE_OK" value="<?= htmlspecialcharsbx($arParams['MESSAGE_OK']) ?>"/>
	<input type="hidden" name="FORM_ID" value="<?=$arParams['FORM_ID']?>"/>
	<input type="hidden" name="BUY_SUBMIT" value="Y"/>
    <input type="hidden" name="privacy_policy" value="N"/>
	<?= bitrix_sessid_post() ?>
	<? foreach($arResult['HIDDEN_FIELDS'] as $arField) {
		echo $arField['HTML'],"\n";
	}?>
	<input type="hidden" name="id" value="<?= $arParams['IBLOCK_ELEMENT_ID'] ?>" title=""/>
	<input type="hidden" name="RZ_BASKET" value="<?=htmlspecialcharsbx($_REQUEST['RZ_BASKET'])?>" />
	
	<? if ($arParams['FIELD_QUANTITY'] == 'Y' && $_REQUEST['RZ_BASKET'] !== 'Y'): ?>
		<div>
			<div class="text-w-icon"><?=GetMessage("BITRONIC2_ONECLICK_QUANTITY")?></div>
			<label class="textinput-wrapper name">
				<i class="flaticon-balance3 icon-before-input"></i>	
				<input type="number" min="0" class="textinput" name="QUANTITY" value="<?= $arResult['QUANTITY'] ?>" title=""/>
			</label>
		</div>
	<? endif ?>
	<? foreach ($arResult['FIELDS'] as $arItem): 
		$arItem['HTML'] = ($arItem['REQ']) ? str_replace('/>',' required />', $arItem['HTML']) : $arItem['HTML']?>
		<div>
			<div class="text-w-icon"><?= $arItem['NAME'] ?><? if ($arItem['REQ']): ?><span class="required-asterisk">*</span><? endif ?>:</div>
			<label class="textinput-wrapper name">
				<i class="flaticon-<?=$arItem['ICON']?> icon-before-input"></i>
				<?= $arItem['HTML'] ?>
				<span class="textinput-icons">
					<i class="icon-valid flaticon-check33"></i>
					<i class="icon-not-valid flaticon-x5"></i>
				</span>
			</label>
		</div>
	<? endforeach ?>
	
	<? if ('Y' == $arResult['USE_CAPTCHA'] && strlen($arResult["CAPTCHA_CODE"]) > 0): ?>
		<div>
			<div><?=GetMessage('BITRONIC2_ONECLICK_CAPTCHA_REGF_TITLE')?>:</div>
			<input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>">
			<div class="modal-captcha-wrap">
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>" width="180" height="40" alt="CAPTCHA">
			</div>
			<div><?=GetMessage('BITRONIC2_ONECLICK_CAPTCHA_REGF_PROMT')?><span class="required-asterisk">*</span>:</div>
			<label class="textinput-wrapper modal-captcha-input-wrap">
				<input type="text" name="captcha_word"
						id="modal_quick-buy_captcha"
						class="textinput" required
						value="" title="">
				<span class="textinput-icons">
					<i class="icon-valid flaticon-check33"></i>
					<i class="icon-not-valid flaticon-x5"></i>
				</span>
			</label>
		</div>
	<? endif ?>
    <label class="checkbox-styled">
        <input value="Y" type="checkbox" name="privacy_policy">
        <span class="checkbox-content" tabindex="5">
			<i class="flaticon-check14"></i><?=GetMessage('BITRONIC2_I_ACCEPT')?>
            <a href="<?=$pathToRules?>" class="link"><span class="text"><?=GetMessage('BITRONIC2_POLITIC_PRIVICE')?></span></a>
		</span>
    </label>
	<div class="submit-wrap">
		<button type="submit" class="btn-submit btn-main disabled" name="submit">
			<span class="text"><?=GetMessage('BITRONIC2_ONECLICK_SUBMIT_BUTTON')?></span>
		</button>
	</div>
	<input type="hidden" name="PROPS" value="<?= \Yenisite\Core\Tools::GetEncodedArParams($arParams['OFFER_PROPS']) ?>"/>
	<div class="required-info"><span class="required-asterisk">*</span> &mdash; <?=GetMessage("BITRONIC2_ONECLICK_REQ_FIELD")?></div>
</form>