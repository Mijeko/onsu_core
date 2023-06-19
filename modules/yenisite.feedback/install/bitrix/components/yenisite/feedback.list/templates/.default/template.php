<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<? $APPLICATION->SetAdditionalCSS('/bitrix/components/yenisite/feedback.list/templates/.default/color-'. $arResult['COLOR_SCHEME'] .'.css'); ?>

<div id='ys-feedback_list'>
    <div class='ys-sections'>
    <? foreach ($arResult['SECTIONS'] as $arSection): ?>
        <? if (!empty($arSection['CODE'])): ?>
            <div class='ys-section <?=($arResult['CUR_SECTION_CODE'] == $arSection['CODE'])? 'ys-section_cur':''?>'>
                <? if($arParams['SEF_MODE'] == 'Y') : ?>
                    <a href='<?=$arParams['SEF_FOLDER'];?><?=$arSection['CODE'];?>/'><?=$arSection['NAME'];?></a>
                <? else: ?>
                    <a href='<?=$APPLICATION->GetCurPage();?>?SECTION_CODE=<?=$arSection['CODE'];?>'><?=$arSection['NAME'];?></a>
                <? endif; ?>
            </div>
        <? endif; ?>
    <? endforeach; ?>
    </div>
    <br>
    <? if (!empty($arResult['ITEMS'])): ?>
        <? foreach ($arResult['ITEMS'] as $arItem): ?>
            <div class='message'>
                <div class='ys-message_top'>
                    <div class='ys-message_info'>
                        <?=ConvertDateTime($arItem['DATE_CREATE'], "HH:ii:ss DD.MM.YYYY");?>
                        <? if ($arResult['SHOW_INFO'] && !empty($arItem['PROPERTY_IP_VALUE'])): ?>
                            (<?=GetMessage("IP");?> <?=$arItem['PROPERTY_IP_VALUE'];?>)
                        <? endif; ?>
                    </div>
                    <? if (empty($arItem['DETAIL_TEXT']) && $arResult['CAN_RESPONSE']) : ?>
                        <div class='response_link'><a rel='<?=$arItem['ID'];?>' class='write_response' href='#'><?=GetMessage('RESPONSE');?></a></div>
                    <? endif; ?>
                </div>
                <div style='clear: both;'></div>
                <div class='middle'>
                    <h3>
                        <? if (!empty($arItem['PROPERTY_EMAIL_VALUE'])): ?>
                            <a href='mailto:<?=$arItem['PROPERTY_EMAIL_VALUE'];?>'><?=$arItem['PROPERTY_NAME_VALUE'];?></a>
                        <? else: ?>
                            <?=$arItem['PROPERTY_NAME_VALUE'];?>
                        <? endif; ?>
                    </h3>
                    <span class='text'><?=$arItem['PREVIEW_TEXT'];?></span>
                
                </div>
            
                <? if (!empty($arItem['FILE'])) : ?>
                    <div class='bottom'>
                        <div class='ys-file'><a href='<?=$arItem['FILE']['SRC']; ?>' target='_blank'><?=GetMessage('DOWNLOAD');?></a></div>
                    </div>
                <? endif; ?>
                
                <!-- ANSWERS -->
                <? if (!empty($arItem['DETAIL_TEXT'])) : ?>
                    <div class='response'>
                        <h3><?=GetMessage('ANSWER');?>:</h3>
                        <?=$arItem['DETAIL_TEXT'];?>
                        <?//$arResult['RESPONSES'][$arItem['ID']]['PROPERTY_MESSAGE_VALUE']['TEXT']; ?>
                    </div>
                <? endif; ?>
            </div>
        <? endforeach; ?>
    
        <div class='pager-block'>
            <?=$arResult['NAV']; ?>
        </div>
        
    <? else: ?>
        <? if (empty($arResult['SECTIONS'])): ?>
            <?=GetMessage("MESSAGES_EMPTY");?>
        <? elseif (!empty($arResult['CUR_SECTION_CODE']) &&!empty($arResult['SECTIONS'])): ?>
            <?=GetMessage("MESSAGES_EMPTY");?>
        <? elseif (empty($arResult['CUR_SECTION_CODE']) && !empty($arResult['SECTIONS'])): ?>
            <?=GetMessage("CHOOSE_SECTION");?>
        <? endif; ?>
    <? endif; ?>
    
    <? if ($arParams["AJAX_MODE"] == "Y" ): ?>
        <a id="ys-feedback-refresh" style="display:none;" rel="nofollow" href="<?=$APPLICATION->GetCurPageParam()?>"><?=GetMessage("REFRESH");?></a>
    <? endif; ?>
        
    <div id="response_form" style="display:none;">
        <form action="<?=$_SERVER["REQUEST_URI"]?>" method="POST"><input type='hidden' name='element_id' value=''><textarea name='response_text'></textarea><br><button id='response_submit' class='button'><?=GetMessage("RESPONSE");?></button><a href="javascript:void(0)" id='response_cancel'><?=GetMessage("CANCEL");?></a></form>
    </div>
    
    
</div>

<script>
    $(document).ready(function(){

        $('a.write_response').click(function(){
            //$('div#response_form').remove();
        
            var element_id = $(this).attr('rel');
            var response_form = $('div#response_form').get(0);
            $('div#response_form').remove();
            
            $(this).parent().parent().parent().append(response_form);
            $(this).parent().parent().parent().find('div#response_form input[name=element_id]').val(element_id);
            $(this).parent().parent().parent().find('div#response_form').fadeIn(600);
            
            /*if ($('div#response_form').length > 0)
            {
                $('div#response_form').fadeOut(500, function() {
                    $('div#response_form').remove();
                    $(this).parent().parent().parent().append(response_form);
                    $(this).parent().parent().parent().find('div#response_form').fadeIn(600);
                });
            }
            else
            {
                $(this).parent().parent().parent().append(response_form);
                $(this).parent().parent().parent().find('div#response_form').fadeIn(600);
            }*/
            
            $('#response_cancel').click(function(){
                $('div#response_form').fadeOut(500, function() {
                    //$('div#response_form').remove();
                });
                return false;
            });
            
            return false;
        });
        
        $('div.message').hover(function(){
            $(this).find('div.response_link a').fadeIn(150, function() {
                
            });
            return false;
        },
        function(){
            $(this).find('div.response_link a').fadeOut(100, function() {
                
            });
            return false;
        });
        
        //$('div.response a').fadeIn('slow', function() {
        //    // Animation complete
        //});        
    });
</script>