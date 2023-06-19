<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!empty($arResult['ERROR']))
{
	echo $arResult['ERROR'];
	return false;
}

$this->setFrameMode(true);
include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info_dynamic.php';
?>

<div class="row news-n-articles brands-list">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>
<?foreach($arResult["rows"] as $arItem):
	if (!empty($arParams['DETAIL_URL']))
	{
		$url = str_replace(
			array('#ID#', '#XML_ID#', '#BLOCK_ID#'),
			array($arItem['ID'], $arItem['UF_XML_ID'], intval($arParams['BLOCK_ID'])),
			$arParams['DETAIL_URL']
		);
	}?>

	<div class="item col-xs-12 col-sm-6 col-md-4">
		<?if(!empty($arItem['UF_FILE'])):?>

		<a href="<?=$url?>" class="news-item-img"><?=$arItem['UF_FILE']?></a>
		<?endif?>

		<div class="content">
			<a href="<?=$url?>" class="link"><span class="text"><?echo $arItem["UF_NAME"]?></span></a>
				<div class="desc"><?echo $arItem["UF_DESCRIPTION"];?></div>
		</div><!-- /.content -->
	</div><!-- /.item.col-xs-12.col-sm-6.col-md-4 -->
<?endforeach;?>
</div>
<div class="row">
	<?=$arResult["NAV_STRING"]?>
</div>
