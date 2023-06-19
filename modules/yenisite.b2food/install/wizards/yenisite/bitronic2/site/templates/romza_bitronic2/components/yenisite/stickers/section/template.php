<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?if($arParams['SHOW_CONTAINER'] !== 'N'):?>
<div class="stickers clearfix">
<?endif?>
	<div class="stickers-wrap">
        <?if(!empty($arParams["ACTION_DATA"])):?>
            <?foreach ($arParams['ACTION_DATA'] as $arAction):?>
                <div class="sticker akcia">
                <span class="svg-wrap">
                    <svg>
                        <use xlink:href="#discount2"></use>
                    </svg>
                </span>
                    <div class="line">
                    <span class="svg-wrap">
                        <svg>
                            <use xlink:href="#crown"></use>
                        </svg>
                    </span>
                        <span class="pseudolink-bd link-black">
                        <span class="text"><?=GetMessage('STICKER_SECT_ACTION')?></span><span class="hidden-xs">:</span>
                        <strong class="hidden-xs" title="<?=$arAction['NAME']?>"><?=$arAction['NAME']?></strong>
                    </span>
                        <div class="popup_detailed">
                            <div class="detailed-header">
                                <span class="text"><?=$arAction['NAME']?></span>
                            </div>
                            <div class="detailed-text">
                                <?=$arAction['DETAIL_TEXT'] ? $arAction['DETAIL_TEXT'] : $arAction['PREVIEW_TEXT']?>
                            </div>
                            <a href="<?=$arAction['SRC']?>" class="link-bd link-std"><?=GetMessage('STICKER_SECT_MORE')?></a>
                        </div>
                    </div>
                </div>
            <?endforeach;?>
        <?endif?>
	<?if('N' != $arParams['STICKER_CATCHBUY'] && $arResult["CATCHBUY"] || $arParams['SKU_EXT'] === true):?>
		<div class="sticker hurry-buy flaticon-stopwatch6"<?=($arResult["CATCHBUY"]?'':' style="display:none"')?>><?=GetMessage('STICKER_SECT_CATCHBUY')?></div>
	<?endif?>

	<?if($arResult["NEW"]):?>
		<div class="sticker new flaticon-new92"><?=GetMessage('STICKER_SECT_NEW')?></div>
	<?endif?>

	<?if($arResult["SALE"]):?>
		<div class="sticker discount flaticon-sale" <?if(!empty($arParams['CONT_ID_DSC_PERC'])):?> id="<?=$arParams['CONT_ID_DSC_PERC']?>" <?endif?>>
			<?=GetMessage('STICKER_SECT_SALE')?>
		</div>
	<?endif;?>

	<?if($arResult["HIT"]):?>
		<div class="sticker hit flaticon-first43"><?=GetMessage('STICKER_SECT_HIT')?></div>
	<?endif?>

	<?if($arResult["BESTSELLER"]):?>
		<div class="sticker best-choice flaticon-like"><?=GetMessage('STICKER_SECT_BESTSELLER')?></div>
	<?endif?>

	<?foreach ($arResult['CUSTOM'] as $arCustom):?>
		<div class="sticker <?=$arCustom['CLASS']?>"><?=$arCustom['TEXT']?></div>
	<?endforeach?>
	</div>
	<?if($arResult["SALE"] && $arResult["SALE_DISC"]>0 && $arParams['SHOW_DISCOUNT_PERCENT'] !== 'N'):?>
	<div class="sticker discount-w-number">
		<span class="text">
			<?if(false):?><span class="small"><?=GetMessage('STICKER_SECT_SALE')?></span><?endif;//for those who really wanna text here?>

			-<?=Round($arResult["SALE_DISC"])?>%
		</span>
	</div>
	<?endif?>
<?if($arParams['SHOW_CONTAINER'] !== 'N'):?>
</div><!-- /.stickers -->
<?endif?>
