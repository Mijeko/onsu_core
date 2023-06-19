<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<div class="big-stickers">
	<?if($arResult["NEW"]):?>
		<div class="big-sticker" data-tooltip title="<?=GetMessage('STICKER_SECT_NEW')?>">
			<i class="flaticon-new92"></i>
			<span class="text"></span>
		</div>
	<?endif?>
	<?if($arResult["HIT"]):?>
		<div class="big-sticker" data-tooltip title="<?=GetMessage('STICKER_SECT_HIT')?>">
			<i class="flaticon-first43"></i>
			<span class="text"></span>
		</div>
	<?endif?>
	<?if($arResult["SALE"]):?>
		<div class="big-sticker" <?if(!empty($arParams['CONT_ID_DSC_PERC'])):?> id="<?=$arParams['CONT_ID_DSC_PERC']?>" <?endif?> data-tooltip title="<?=GetMessage('STICKER_SECT_SALE')?>">
			<i class="flaticon-sale"></i>
			<span class="text"></span>
			<?/*if($arResult["SALE_DISC"]>0):?>
				-<?=Round($arResult["SALE_DISC"])?>%
			<?endif*/?>
		</div>
	<?endif;?>

	<?if($arResult["BESTSELLER"]):?>
		<div class="big-sticker" data-tooltip title="<?=GetMessage('STICKER_SECT_BESTSELLER')?>">
			<i class="flaticon-like"></i>
			<span class="text"></span>
		</div>
	<?endif?>

	<?/*if($arResult["TIME2BUY"]):?>
		<div class="big-sticker circle hurry-buy">
			<i class="flaticon-stopwatch6"></i>
			<span class="text"><?=GetMessage('STICKER_SECT_TIME2BUY')?></span>
		</div>
	<?endif*/?>
</div><!-- big-stickers -->