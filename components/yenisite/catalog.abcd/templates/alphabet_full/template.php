<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);
if(!$_REQUEST["letter"] && $_REQUEST["letter"] !== '0' && $arParams["SHOW_ALL"])
	$_REQUEST["letter"]="all";
global $MESS;
?>

<div class="alphabetdiv">
    <?if($arParams["SHOW_ENG"]):?>
        <ul class="alphabet">   
        
            <?if($arParams["GROUP_ENG"] && $arParams["GROUP_ENG_FLAG"]):?>
                <li>
                    <?if($_REQUEST["letter"] != "eng"):?>
                        <a href='<?=$APPLICATION->GetCurPageParam("letter=eng", array("letter"));?>' rel="nofollow">
                            <?=GetMessage('ys_english_group');?>
                        </a>
                    <?else:?>
                        <b><?=GetMessage('ys_english_group');?></b>
                    <?endif;?>
                </li>
            <?else:?>
                <?foreach(GetMessage('ys_english_chars') as $e_ch):?>
                    <li>
                        <?if(in_array($e_ch, $arResult['letters'])):?>
                            <?if($_REQUEST["letter"]==$e_ch):?>
                                <b><?=$e_ch;?></b>
                            <?else:?>
                                <a href='<?=$APPLICATION->GetCurPageParam("letter=".$e_ch, array("letter"));?>' rel="nofollow">
                                    <?=$e_ch;?>
                                </a>
                            <?endif;?>
                        <?else:?>
                            <span><?=$e_ch;?></span>
                        <?endif;?>
                    </li>
                <?endforeach;?>
            <?endif;?>
        </ul>
    <?endif;?>
    
    <?if($arParams["SHOW_RUS"]):?>
        <ul class="alphabet">  
            <?if($arParams["GROUP_RUS"] && $arParams["GROUP_RUS_FLAG"]):?>
                <li>
                    <?if($_REQUEST["letter"] != "rus"):?>
                        <a href='<?=$APPLICATION->GetCurPageParam("letter=rus", array("letter"));?>' rel="nofollow">
                            <?=GetMessage('ys_russian_group');?>
                        </a>
                    <?else:?>
                        <b><?=GetMessage('ys_russian_group');?></b>
                    <?endif;?>
                </li>
            <?else:?>
                <?foreach(GetMessage('ys_russian_chars') as $r_ch):?>
                    <li>
                        <?if(in_array($r_ch, $arResult['letters'])):?>
                            <?if($_REQUEST["letter"]==$r_ch):?>
                                <b><?=$r_ch;?></b>
                            <?else:?>
                                <a href='<?=$APPLICATION->GetCurPageParam("letter=".$r_ch, array("letter"));?>' rel="nofollow">
                                    <?=$r_ch;?>
                                </a>
                            <?endif;?>
                        <?else:?>
                            <span><?=$r_ch;?></span>
                        <?endif;?>
                    </li>
                <?endforeach;?>
            <?endif;?>
        </ul>
    <?endif;?>
    
    <?if($arParams["SHOW_NUMBER"]):?>
        <ul class="alphabet">   
            <?if($arParams["GROUP_NUMBER"] && $arParams["GROUP_NUMBER_FLAG"]):?>
                <li>
                    <?if($_REQUEST["letter"] != "number"):?>
                        <a href='<?=$APPLICATION->GetCurPageParam("letter=eng", array("letter"));?>' rel="nofollow">
                            <?=GetMessage('ys_number_group');?>
                        </a>
                    <?else:?>
                        <b><?=GetMessage('ys_number_group');?></b>
                    <?endif;?>
                </li>
            <?else:?>
                <?foreach(GetMessage('ys_number_chars') as $n_ch):?>
                    <li>
                        <?if(in_array($n_ch, $arResult['letters'])):?>
                            <?if($_REQUEST["letter"]==$n_ch):?>
                                <b><?=$n_ch;?></b>
                            <?else:?>
                                <a href='<?=$APPLICATION->GetCurPageParam("letter=".$value, array("letter"));?>' rel="nofollow">
                                    <?=$n_ch;?>
                                </a>
                            <?endif;?>
                        <?else:?>
                            <span><?=$n_ch;?></span>
                        <?endif;?>
                    </li>
                <?endforeach;?>
            <?endif;?>
            <?if($arParams["SHOW_ALL"]):?>
                <li>
                    <?if($_REQUEST["letter"]!="all"):?>
                        <a href='<?=$APPLICATION->GetCurPageParam("letter=all", array("letter"));?>' rel="nofollow">
                            <?=GetMessage('ys_all');?>
                        </a>
                    <?else:?>
                        <b><?=GetMessage('ys_all');?></b>
                    <?endif;?>
                </li>
            <?endif;?>
        </ul>
    <?endif;?>
</div>
<div style="clear:both"></div>