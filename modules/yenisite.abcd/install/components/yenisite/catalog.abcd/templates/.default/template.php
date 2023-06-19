<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if(!$_REQUEST["letter"] && $_REQUEST["letter"] !== '0' && $arParams["SHOW_ALL"])
	$_REQUEST["letter"]="all";
	
$arDelParams = array("letter", 'PAGEN_1');
if(isset($arParams["SEF"]) && $arParams["SEF"] == 'Y')
{
	$arDelParams = array_merge($arDelParams, array('SECTION_CODE', 'order', 'by', 'view', 'page_count', 'PAGEN_1'));
	if(!empty($arParams["FOLDER_SEF"]))
		$curDir = $arParams["FOLDER_SEF"];
	else
		$curDir = $APPLICATION->GetCurDir();

	$curDir = preg_replace( '#page-\d+\/#', '', $curDir);
	$curDir = preg_replace( '#letter-\w{1,3}\/#', '', $curDir);
}
?>

<table class="abcd" cellspacing="3" cellpadding="2">
<tr>
<?if(isset($arParams["SEF"]) && $arParams["SEF"] == 'Y'):?>
	<?if($arParams["SHOW_NUMBER"] && $arParams["GROUP_NUMBER"] && $arParams["GROUP_NUMBER_FLAG"]):?>
	<td class="abcd"><?if($_REQUEST["letter"]!="number"):?><a data-abcd="number" href='<?=$curDir?>letter-number/' rel="nofollow"><?endif;?><?=GetMessage('ys_number_group');?><?if($_REQUEST["letter"]!="number"):?></a><?endif;?></td>
	<?endif;?>
	<?if($arParams["SHOW_ENG"] && $arParams["GROUP_ENG"] && $arParams["GROUP_ENG_FLAG"]):?>
	<td class="abcd"><?if($_REQUEST["letter"]!="eng"):?><a data-abcd="eng" href='<?=$curDir?>letter-eng/' rel="nofollow"><?endif;?><?=GetMessage('ys_english_group');?><?if($_REQUEST["letter"]!="eng"):?></a><?endif;?></td>
	<?endif;?>
	<?if($arParams["SHOW_RUS"] && $arParams["GROUP_RUS"] && $arParams["GROUP_RUS_FLAG"]):?>
	<td class="abcd"><?if($_REQUEST["letter"]!="rus"):?><a data-abcd="rus" href='<?=$curDir?>letter-rus/' rel="nofollow"><?endif;?><?=GetMessage('ys_russian_group');?><?if($_REQUEST["letter"]!="rus"):?></a><?endif;?></td>
	<?endif;?>
	<?foreach($arResult['letters'] as $key=>$value):?>
	<td class="abcd"><?if(strtoupper($_REQUEST["letter"])!=$value):?><a data-abcd="<?=$value?>" href='<?=$curDir?>letter-<?=$value?>/' rel="nofollow"><?endif;?><?=$value?><?if(strtoupper($_REQUEST["letter"])!=$value):?></a><?endif;?></td>
	<?endforeach;?>
	<?if($arParams["SHOW_ALL"]):?>
	<td class="abcd"><?if($_REQUEST["letter"]!="all"):?><a data-abcd="all" href='<?=$curDir?>letter-all/' rel="nofollow"><?endif;?><?=GetMessage('ys_all');?><?if($_REQUEST["letter"]!="all"):?></a><?endif;?></td>
	<?endif;?>
<?else:?>
	<?if($arParams["SHOW_NUMBER"] && $arParams["GROUP_NUMBER"] && $arParams["GROUP_NUMBER_FLAG"]):?>
	<td class="abcd"><?if($_REQUEST["letter"]!="number"):?><a data-abcd="number" href='<?=$APPLICATION->GetCurPageParam("letter=number", $arDelParams);?>' rel="nofollow"><?endif;?><?=GetMessage('ys_number_group');?><?if($_REQUEST["letter"]!="number"):?></a><?endif;?></td>
	<?endif;?>
	<?if($arParams["SHOW_ENG"] && $arParams["GROUP_ENG"] && $arParams["GROUP_ENG_FLAG"]):?>
	<td class="abcd"><?if($_REQUEST["letter"]!="eng"):?><a data-abcd="eng" href='<?=$APPLICATION->GetCurPageParam("letter=eng", $arDelParams);?>' rel="nofollow"><?endif;?><?=GetMessage('ys_english_group');?><?if($_REQUEST["letter"]!="eng"):?></a><?endif;?></td>
	<?endif;?>
	<?if($arParams["SHOW_RUS"] && $arParams["GROUP_RUS"] && $arParams["GROUP_RUS_FLAG"]):?>
	<td class="abcd"><?if($_REQUEST["letter"]!="rus"):?><a data-abcd="rus" href='<?=$APPLICATION->GetCurPageParam("letter=rus", $arDelParams);?>' rel="nofollow"><?endif;?><?=GetMessage('ys_russian_group');?><?if($_REQUEST["letter"]!="rus"):?></a><?endif;?></td>
	<?endif;?>
	<?foreach($arResult['letters'] as $key=>$value):?>
	<td class="abcd"><?if(strtoupper($_REQUEST["letter"])!=$value):?><a data-abcd="<?=$value?>" href='<?=$APPLICATION->GetCurPageParam("letter=".$value, $arDelParams);?>' rel="nofollow"><?endif;?><?=$value?><?if(strtoupper($_REQUEST["letter"])!=$value):?></a><?endif;?></td>
	<?endforeach;?>
	<?if($arParams["SHOW_ALL"]):?>
	<td class="abcd"><?if($_REQUEST["letter"]!="all"):?><a data-abcd="all" href='<?=$APPLICATION->GetCurPageParam("letter=all", $arDelParams);?>' rel="nofollow"><?endif;?><?=GetMessage('ys_all');?><?if($_REQUEST["letter"]!="all"):?></a><?endif;?></td>
	<?endif;?>
<?endif;?>
</tr>
</table>