<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?if($arResult["NEW"]):?>
	<div class="mark new-label"><?=GetMessage('STICKER_NEW');?></div>
<?endif?>
<?if($arResult["HIT"]):?>
	<div class="mark star-label" ><?=GetMessage('STICKER_HIT');?></div>
<?endif?>
<?if($arResult["SALE"]):?>
	<div class="mark per-label">
		<?if($arResult["SALE_DISC"]>0):?>
			-<?=Round($arResult["SALE_DISC"])?>
		<?endif?>
	%</div>
<?endif;?>
<?if($arResult["BESTSELLER"]):?>
	<div class="mark leader-label"><?=GetMessage('STICKER_BESTSELLER');?></div>
<?endif?>