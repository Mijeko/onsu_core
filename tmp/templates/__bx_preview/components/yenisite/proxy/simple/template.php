<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
$id = 'bxdinamic_banner_'/*.$arResult['BANNER_PROPERTIES']['ID'].'_'*/.'_'.$arResult['BANNER_PROPERTIES']['IMAGE_URL'].$this->randString();
?>
<div id="<?=$id?>" class="promo">
<?$frame = $this->createFrame($id, false)->begin(CRZBitronic2Composite::insertCompositLoader());?>
	<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/debug_info.php';?>
	<?if(!empty($arResult['BANNER_PROPERTIES']) && !empty($arResult['BANNER_PROPERTIES']["IMAGE_URL"]) || (\Yenisite\Core\Tools::isEditModeOn())):?>
        <? $arResult['BANNER_PROPERTIES']["IMAGE_URL"] = empty($arResult['BANNER_PROPERTIES']["IMAGE_URL"]) && \Yenisite\Core\Tools::isEditModeOn() ? SITE_TEMPLATE_PATH.'/img/def-banner.png' : $arResult['BANNER_PROPERTIES']["IMAGE_URL"]  ;?>
	<a itemscope itemtype="http://schema.org/ImageObject" title="<?=$arResult['BANNER_PROPERTIES']["IMG_ALT"]?>" href="<?=$arResult['BANNER_PROPERTIES']['URL_TARGET']?>" target="<?=$arResult['BANNER_PROPERTIES']['URL_TARGET']?>">
		<img itemprop="contentUrl" class="lazy" data-original="<?=$arResult['BANNER_PROPERTIES']["IMAGE_URL"]?>" src="<?=ConsVar::showLoaderWithTemplatePath()?>" alt="<?=$arResult['BANNER_PROPERTIES']["IMG_ALT"]?>" title="<?=$arResult['BANNER_PROPERTIES']["IMG_ALT"]?>">
	</a>
	<?endif?>
<?$frame->end();?>
</div>
