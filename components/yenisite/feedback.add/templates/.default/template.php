<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<? $APPLICATION->AddHeadScript('/bitrix/js/main/core/core_window.js'); ?>
<? $APPLICATION->AddHeadScript('/bitrix/js/main/core/core_popup.js'); ?>
<? $APPLICATION->AddHeadScript('/bitrix/js/main/core/core_date.js'); ?>
<? $APPLICATION->SetAdditionalCSS('/bitrix/js/main/core/css/core_popup.css'); ?>
<? $APPLICATION->SetAdditionalCSS('/bitrix/js/main/core/css/core_date.css'); ?>

<? $APPLICATION->SetAdditionalCSS('/bitrix/components/yenisite/feedback.add/templates/.default/color-'. $arResult['COLOR_SCHEME'] .'.css'); ?>

<? $APPLICATION->SetAdditionalCSS('/bitrix/components/yenisite/feedback.add/templates/.default/jquery.jgrowl.css'); ?>
<? $APPLICATION->AddHeadScript('/bitrix/components/yenisite/feedback.add/templates/.default/jquery.jgrowl_minimized.js'); ?>

<? if (!$arResult['BITRONIC_EXIST']): ?>
    <? $APPLICATION->SetAdditionalCSS('/bitrix/components/yenisite/feedback.add/templates/.default/jquery-ui-1.9.2.custom.css'); ?>
    <? $APPLICATION->SetAdditionalCSS('/bitrix/components/yenisite/feedback.add/templates/.default/jgrowl.css'); ?>
<? endif; ?>

<div>
	<form method='POST' id="ys-guestbook" name="guestbook" action="<?=$APPLICATION->GetCurPageParam()?>" enctype="multipart/form-data">
            <?foreach($arResult['FIELDS'] as $arItem):?>
                <? if ($arItem['PROPERTY_TYPE'] == 'E'): ?>
                    <?=$arItem['HTML'];?>
                <? endif; ?>
            <?endforeach;?>
                <div id="ys-feedback_add">
                    <div class="req_block">
                    <span style="color: red">*</span>
                        <?=GetMessage("REQUIRED");?>
                    </div>
                    <div><h2><?=$arResult['ELEMENT_ADD'];?></h2></div>
                    
                    <? if (!empty($arResult['SECTIONS_SELECT'])): ?>
                        <?= GetMessage('SECTION_SELECT') . ': ' . $arResult['SECTIONS_SELECT']; ?>
                    <? endif; ?>
                    
                    <?foreach($arResult['FIELDS'] as $arItem):?>
                        <? if (!empty($arItem['HTML']) && $arItem['PROPERTY_TYPE'] != 'E'): ?>
                            <div class='field'>
                                <label><?=$arItem['NAME'];?><?=($arItem['IS_REQUIRED'] == 'Y')?'<span style="color: red">*</span>':'';?>:</label>
                                <?=$arItem['HTML'];?>
                            </div>
                        <? endif; ?>
                    <?endforeach;?>
                    
                    <? if ($arParams['TEXT_SHOW'] == 'Y'): ?>
                        <div class='field'>
                            <label><?=GetMessage("MESSAGE");?><?=($arParams['TEXT_REQUIRED'] == 'Y')? '<span style="color: red">*</span>': '' ?>:</label>
                            <textarea name='<?=$arResult['CODE'];?>[text]'><?=$arResult['DATA']['text'];?></textarea>
                        </div>
                    <? endif; ?>
                    <?if(!empty($arResult["CAPTCHA_CODE"])):?>
                        <div class="ys-captcha">
                                <label><?=GetMessage("CAPTCHA_TITLE")?>:</label>
                                <img alt="<?=GetMessage("CAPTCHA_ALT")?>" src="/bitrix/tools/captcha.php?captcha_code=<?=$arResult["CAPTCHA_CODE"]?>" />
                                <br>
                                <input class="txt" type="text" name="captcha_word" /><br />
                                <input type="hidden" name="captcha_code" value="<?=$arResult["CAPTCHA_CODE"]?>"/>
                        </div>
                    <?endif;?>
                    <br/>
                    <div><button class='button2'><?=GetMessage("SEND");?></button></div>
                </div>
	</form>
</div>

<? if(!empty($arResult['ERROR'])): ?>
<script>
    $(document).ready(function(){
        $.jGrowl('<?=$arResult['ERROR'];?>', {themeState: 'error', position: 'bottom-left', header: '<?=GetMessage("ERROR");?>'});
    });
</script>
<? endif; ?>

<? if ($arResult['SUCCESS'] === TRUE): ?>

<? if(!empty($arResult['SUCCESS_TEXT'])): ?>
<script>
    $(document).ready(function(){
        $.jGrowl('<?=$arResult['SUCCESS_TEXT'];?>', {themeState: 'ok', position: 'bottom-left', header: ''});
    });
</script>
<? endif; ?>
<?php //if(isset($_REQUEST['AJAX_CALL']) && 'Y' == $_REQUEST['AJAX_CALL']): ?>
<script>
    if ($('a#ys-feedback-refresh').length > 0)
    {
        $('a#ys-feedback-refresh').click();
    }
</script>
<?php //endif; ?>

<? endif; ?>