<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<br/><?=$arResult["NAV_STRING"]?>
<?endif;?>

<div class="yr2-gallery-list">
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>	
	<div class="yr2-gallery-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">				
	<? $path = CFile::GetPath($arItem["PROPERTIES"][$arParams["PROPERTY_PHOTO"]]["VALUE"][0]);?>
		<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><img alt="<?=$arItem["NAME"]?>" src='<?=CResizer2Resize::ResizeGD2($path, $arParams["ICON_SET"]);?>' /></a>
		<a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><b><?echo $arItem["NAME"]?></b></a>
	</div>
<?endforeach?>
</div>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
<br/><?=$arResult["NAV_STRING"]?>
<?endif;?>
