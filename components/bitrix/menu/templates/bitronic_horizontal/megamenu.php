<div class="megamenu">
    <?foreach($hits as $hit):?>
        <div class='item'>

            <?if($hit["NEW"] == "Y"):?>
                <div class="new-label mark">NEW</div>
            <?endif;?>
            <?if($hit["HIT"] == "Y"):?>
                <div class="star2-label mark">HIT</div>
            <?endif;?>
            <?if($hit["SALE"] == "Y"):?>
                <div class="per2-label mark">%</div>
            <?endif;?>
            <?if($hit["BESTSELLER"] == "Y"):?>
                <div class="leader-label mark">BEST SELLER</div>
            <?endif;?>

            <a href="<?=$hit["DETAIL_PAGE_URL"]?>">
                <img src='<?=$hit["PHOTO"];?>' alt='<?=$hit["NAME"]?>' />
            </a>

            <a href="<?=$hit["SECTION_PAGE_URL"]?>" class="tag"><?=$hit["SECTION"]?></a>
            <h3><a href="<?=$hit["DETAIL_PAGE_URL"]?>"><?=$hit["NAME"]?></a></h3>
            <?
                if($arParams["CURRENCY"] == "RUB" && $arParams["RUB_SIGN"] == "Y")
                {
                    $hit["PRINT_DISCOUNT_VALUE"] = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.
                        GetMessage("RUB").'</span>', $hit["PRICE"][$arParams["PRICE_CODE"]]["PRINT_DISCOUNT_VALUE"]);
                }
                else
                {
                    $hit["PRINT_DISCOUNT_VALUE"] = $hit["PRICE"][$arParams["PRICE_CODE"]]["PRINT_DISCOUNT_VALUE"];
                }
                
            ?>
            <span class="price"><?=$hit["PRINT_DISCOUNT_VALUE"]?></span>
        </div>
    <?endforeach;?>
</div>