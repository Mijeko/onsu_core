<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<div class="stickers">
<?if($arResult["NEW"]):?>
	<div class="cool-sticker new flaticon-new2">
		<span class="text"><?=GetMessage('STICKER_COOL_NEW')?></span>
	</div>
<?endif?>
<?if($arResult["HIT"]):?>
	<div class="cool-sticker like flaticon-first43">
		<span class="text"><?=GetMessage('STICKER_COOL_HIT')?></span>
	</div>
<?endif?>
<?if($arResult["SALE"]):?>
	<div class="cool-sticker discount flaticon-sale" <?if(!empty($arParams['CONT_ID_DSC_PERC'])):?> id="<?=$arParams['CONT_ID_DSC_PERC']?>" <?endif?>>
		<span class="text"><?=GetMessage('STICKER_COOL_SALE')?></span>
		<?if($arResult["SALE_DISC"]>0 && $arParams['SHOW_DISCOUNT_PERCENT'] !== 'N'):?>
			-<?=Round($arResult["SALE_DISC"])?>%
		<?endif?>
	</div>
<?endif;?>

<?if($arResult["BESTSELLER"]):?>
	<div class="cool-sticker like flaticon-like">
		<span class="text"><?=GetMessage('STICKER_COOL_BESTSELLER')?></span>
	</div>
<?endif?>

<?if($arResult["CATCHBUY"]):?>
	<div class="cool-sticker hurry-buy flaticon-stopwatch6">
		<span class="text"><?=GetMessage('STICKER_COOL_CATCHBUY')?></span>
	</div>
<?endif?>

<?foreach ($arResult['CUSTOM'] as $arCustom):?>
	<div class="cool-sticker <?=$arCustom['CLASS']?>">
		<span class="text"><?=$arCustom['TEXT']?></span>
	</div>
<?endforeach?>
	
</div>