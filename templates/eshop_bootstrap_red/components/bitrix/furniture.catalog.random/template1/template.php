<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="special-product">
	<div class="special-product-title">
        <h2><a href="<?=$arResult["DETAIL_PAGE_URL"]?>" style="color: #E22B2B;"><?= $arResult['NAME'] ?></a></h2>
    </div>
	<div class="special-product-image">
        <a href="<?=$arResult["DETAIL_PAGE_URL"]?>">
            <img border="0" src="<?=$arResult["PICTURE"]["SRC"]?>"
                 width="<?=$arResult["PICTURE"]["WIDTH"]?>"
                 height="<?=$arResult["PICTURE"]["HEIGHT"]?>"
                 alt="<?=$arResult['NAME']?>"
                 title="<?=$arResult['NAME']?>" />
        </a>
    </div>
	<!--
    <div class="special-product"><span><?=GetMessage('CR_PRICE')?>:</span> <?=$arResult["PROPERTY_PRICE_VALUE"]?></div>
    -->
</div>