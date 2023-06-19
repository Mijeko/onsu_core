<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!CModule::IncludeModule('yenisite.resizer2') || count($arResult["SECTIONS"]) == 0) return;?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<div class="yr2">

		<?
		foreach($arResult["SECTIONS"] as $k=>$arSection):
			$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
			$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
		?>
			<span><a title="<?=$arSection["NAME"]?>" href="<?=$arSection["SECTION_PAGE_URL"]?>"><?=$arSection["NAME"]?></a></span><br/>
		<?endforeach?>
</div>
